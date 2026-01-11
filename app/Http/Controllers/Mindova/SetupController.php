<?php

namespace App\Http\Controllers\Mindova;

use App\Http\Controllers\Controller;
use App\Models\MindovaTeamMember;
use App\Services\MindovaInvitationService;
use Illuminate\Http\Request;

class SetupController extends Controller
{
    protected MindovaInvitationService $invitationService;

    public function __construct(MindovaInvitationService $invitationService)
    {
        $this->invitationService = $invitationService;
    }

    /**
     * Show setup form (only if no owner exists).
     */
    public function showSetupForm()
    {
        if (MindovaTeamMember::ownerExists()) {
            return redirect()->route('mindova.login');
        }

        return view('mindova.setup');
    }

    /**
     * Handle owner setup.
     */
    public function setup(Request $request)
    {
        if (MindovaTeamMember::ownerExists()) {
            return redirect()->route('mindova.login')
                ->with('error', __('An owner already exists.'));
        }

        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'setup_key' => ['required', 'string'],
        ]);

        // Verify setup key (should match env variable)
        $setupKey = config('app.mindova_setup_key', env('MINDOVA_SETUP_KEY', 'mindova-setup-2024'));

        if ($validated['setup_key'] !== $setupKey) {
            return back()->withInput()
                ->withErrors(['setup_key' => __('Invalid setup key.')]);
        }

        $result = $this->invitationService->setupOwner(
            $validated['email'],
            $validated['name']
        );

        if (!$result['success']) {
            return back()->withInput()->with('error', $result['message']);
        }

        // In development, show the password directly since email might not work
        if (config('app.debug')) {
            return redirect()->route('mindova.login')
                ->with('success', __('Owner account created successfully.'))
                ->with('dev_password', $result['temp_password'] ?? null);
        }

        return redirect()->route('mindova.login')
            ->with('success', __('Owner account created. Check your email for login credentials.'));
    }
}
