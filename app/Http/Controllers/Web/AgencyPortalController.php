<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AgencyPortal;
use App\Models\Certificate;
use App\Models\HireRequest;
use App\Models\Volunteer;
use App\Services\TalentRankingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AgencyPortalController extends Controller
{
    public function __construct(private readonly TalentRankingService $ranking) {}

    /**
     * Agency setup / onboarding form.
     */
    public function setup(Request $request)
    {
        $portal = AgencyPortal::where('user_id', $request->user()->id)->first();

        return view('agency.setup', compact('portal'));
    }

    /**
     * Create or update the agency portal.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'agency_name'     => 'required|string|max:150',
            'description'     => 'nullable|string|max:1000',
            'primary_color'   => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'secondary_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'contact_email'   => 'nullable|email|max:200',
            'website'         => 'nullable|url|max:200',
            'logo'            => 'nullable|image|max:2048',
        ]);

        $slug = Str::slug($validated['agency_name']) . '-' . $request->user()->id;

        $portal = AgencyPortal::firstOrNew(['user_id' => $request->user()->id]);
        $portal->fill([
            'agency_name'     => $validated['agency_name'],
            'slug'            => $slug,
            'primary_color'   => $validated['primary_color'],
            'secondary_color' => $validated['secondary_color'],
            'description'     => $validated['description'] ?? null,
            'contact_email'   => $validated['contact_email'] ?? null,
            'website'         => $validated['website'] ?? null,
            'is_active'       => true,
        ]);

        if ($request->hasFile('logo')) {
            if ($portal->logo_path) {
                Storage::disk('public')->delete($portal->logo_path);
            }
            $portal->logo_path = $request->file('logo')->store('agency-logos', 'public');
        }

        $portal->save();

        return redirect()->route('agency.dashboard')
            ->with('success', 'Agency portal created. You can now search and hire verified professionals.');
    }

    /**
     * Agency white-label dashboard.
     */
    public function dashboard(Request $request)
    {
        $portal = AgencyPortal::where('user_id', $request->user()->id)->firstOrFail();

        // Talent pipeline
        $hireRequests = HireRequest::where('company_user_id', $request->user()->id)
            ->with(['volunteer.user', 'volunteer.skills'])
            ->latest()
            ->get();

        $stats = [
            'total_requests' => $hireRequests->count(),
            'pending'        => $hireRequests->where('status', 'pending')->count(),
            'hired'          => $hireRequests->where('status', 'converted')->count(),
            'declined'       => $hireRequests->where('status', 'declined')->count(),
        ];

        // Top-ranked volunteers snapshot (20)
        $topTalent = $this->getTopTalent()->take(20);

        return view('agency.dashboard', compact('portal', 'hireRequests', 'stats', 'topTalent'));
    }

    /**
     * Public white-label portal view (accessible by slug).
     */
    public function publicPortal(string $slug)
    {
        $portal = AgencyPortal::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $topTalent = $this->getTopTalent()->take(12);

        return view('agency.public-portal', compact('portal', 'topTalent'));
    }

    /**
     * Ranked list of eligible volunteers, shared by the dashboard and
     * public portal (which just take() different slice sizes off the
     * same snapshot). Cached briefly since ranking the full eligible
     * pool is expensive and both pages would otherwise recompute it
     * from scratch on every request.
     */
    private function getTopTalent()
    {
        return Cache::remember('agency.top_talent', 300, function () {
            return $this->ranking->rank(
                Volunteer::with(['user', 'skills'])
                    ->where('general_nda_signed', true)
                    ->whereHas('user', fn ($q) => $q->whereNotNull('email_verified_at'))
                    ->get()
            )->take(20)->values();
        });
    }
}
