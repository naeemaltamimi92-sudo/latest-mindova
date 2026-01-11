<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Challenge;
use App\Services\CertificateService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    protected CertificateService $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    /**
     * Show company confirmation form for issuing certificates
     */
    public function showConfirmationForm(Challenge $challenge)
    {
        // Only company that owns the challenge can confirm
        if ($challenge->company_id !== Auth::user()->company->id) {
            abort(403, __('Unauthorized to confirm this challenge'));
        }

        // Check if challenge can issue certificates
        if (!$challenge->canIssueCertificates()) {
            return redirect()
                ->route('challenges.show', $challenge)
                ->with('error', __('This challenge is not in a state where certificates can be issued'));
        }

        // Get volunteers who participated
        $volunteers = \App\Models\User::whereHas('volunteer.taskAssignments', function ($query) use ($challenge) {
            $query->whereHas('task', function ($q) use ($challenge) {
                $q->where('challenge_id', $challenge->id);
            });
        })->with('volunteer')->get();

        return view('certificates.confirmation-form', compact('challenge', 'volunteers'));
    }

    /**
     * Process confirmation and generate certificates
     */
    public function submitConfirmation(Request $request, Challenge $challenge)
    {
        // Validate request
        $validated = $request->validate([
            'confirmed' => 'required|accepted',
            'certificate_type' => 'required|in:participation,completion',
            'company_logo' => 'nullable|image|max:2048',
        ]);

        // Only company that owns the challenge can submit
        if ($challenge->company_id !== Auth::user()->company->id) {
            abort(403, __('Unauthorized'));
        }

        try {
            // Handle logo upload
            $logoPath = null;
            if ($request->hasFile('company_logo')) {
                $logoPath = $request->file('company_logo')->store('certificates/logos', 'public');
            }

            // Generate certificates for all volunteers
            $certificates = $this->certificateService->generateCertificatesForChallenge(
                $challenge,
                $validated['certificate_type'],
                $logoPath
            );

            // Update challenge status
            $challenge->update(['certificates_issued' => true]);

            // Notify admins about certificates issued
            app(NotificationService::class)->notifyCertificatesIssued($challenge, count($certificates));

            // Send notification email to MINDOVA
            try {
                // Load volunteer relationships for email
                $certificatesWithRelations = collect($certificates)->each(function($cert) {
                    $cert->load('volunteer');
                });

                \Mail::to('mindova.ai@gmail.com')->send(
                    new \App\Mail\CertificatesGeneratedNotification(
                        challenge: $challenge,
                        certificates: $certificatesWithRelations->toArray(),
                        companyName: $challenge->company->company_name ?? $challenge->company->name ?? 'Company',
                        certificateType: $validated['certificate_type']
                    )
                );
                \Log::info('Certificate notification email sent', [
                    'challenge_id' => $challenge->id,
                    'certificates_count' => count($certificates)
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to send certificate notification email', [
                    'challenge_id' => $challenge->id,
                    'error' => $e->getMessage()
                ]);
                // Don't fail the whole process if email fails
            }

            return redirect()
                ->route('challenges.show', $challenge)
                ->with('success', __('Certificates generated successfully for :count volunteers', ['count' => count($certificates)]));

        } catch (\Exception $e) {
            \Log::error('Certificate generation failed', [
                'challenge_id' => $challenge->id,
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->back()
                ->with('error', __('Failed to generate certificates: :error', ['error' => $e->getMessage()]));
        }
    }

    /**
     * List all certificates for the authenticated user
     */
    public function index()
    {
        $user = Auth::user();

        // Get certificates for this user
        $certificates = Certificate::where('user_id', $user->id)
            ->with(['challenge', 'company'])
            ->orderBy('issued_at', 'desc')
            ->get();

        return view('certificates.index', compact('certificates'));
    }

    /**
     * Show a single certificate
     */
    public function show(Certificate $certificate)
    {
        // Check authorization
        if ($certificate->user_id !== Auth::id() &&
            (!Auth::user()->company || $certificate->company_id !== Auth::user()->company->id)) {
            abort(403, __('Unauthorized to view this certificate'));
        }

        $certificate->load(['volunteer', 'challenge', 'company']);

        return view('certificates.show', compact('certificate'));
    }

    /**
     * Download certificate PDF
     */
    public function download(Certificate $certificate)
    {
        // Check authorization
        if ($certificate->user_id !== Auth::id() &&
            (!Auth::user()->company || $certificate->company_id !== Auth::user()->company->id)) {
            abort(403, __('Unauthorized to download this certificate'));
        }

        if (!$certificate->pdf_path || !Storage::disk('public')->exists($certificate->pdf_path)) {
            // Try to regenerate PDF if missing
            try {
                $this->certificateService->regeneratePDF($certificate);
            } catch (\Exception $e) {
                abort(404, __('Certificate PDF not found'));
            }
        }

        return Storage::disk('public')->download(
            $certificate->pdf_path,
            "certificate_{$certificate->certificate_number}.pdf"
        );
    }

    /**
     * Revoke a certificate (admin only)
     */
    public function revoke(Request $request, Certificate $certificate)
    {
        // Only admins or company owners can revoke
        if (!Auth::user()->is_admin &&
            (!Auth::user()->company || $certificate->company_id !== Auth::user()->company->id)) {
            abort(403, __('Unauthorized'));
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $certificate->revoke($validated['reason']);

        return redirect()
            ->back()
            ->with('success', __('Certificate revoked successfully'));
    }

    /**
     * Regenerate certificate PDF (admin only)
     */
    public function regenerate(Certificate $certificate)
    {
        // Only admins can regenerate
        if (!Auth::user()->is_admin) {
            abort(403, __('Unauthorized'));
        }

        try {
            $this->certificateService->regeneratePDF($certificate);

            return redirect()
                ->back()
                ->with('success', __('Certificate PDF regenerated successfully'));

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', __('Failed to regenerate PDF: :error', ['error' => $e->getMessage()]));
        }
    }

    /**
     * Verify certificate by certificate number (public)
     */
    public function verify(Request $request)
    {
        $certificateNumber = $request->input('certificate_number');

        if (!$certificateNumber) {
            return view('certificates.verify');
        }

        $certificate = Certificate::where('certificate_number', $certificateNumber)
            ->with(['volunteer', 'challenge', 'company'])
            ->first();

        if (!$certificate) {
            return view('certificates.verify', [
                'searched' => true,
                'found' => false,
                'certificate_number' => $certificateNumber,
            ]);
        }

        return view('certificates.verify', [
            'searched' => true,
            'found' => true,
            'certificate' => $certificate,
        ]);
    }
}
