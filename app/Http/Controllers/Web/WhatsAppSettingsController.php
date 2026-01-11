<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class WhatsAppSettingsController extends Controller
{
    /**
     * Show WhatsApp settings page.
     */
    public function index()
    {
        return view('settings.whatsapp', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * Enable WhatsApp notifications.
     */
    public function enable(Request $request)
    {
        $validated = $request->validate([
            'whatsapp_number' => [
                'required',
                'string',
                'regex:/^\+?[1-9]\d{1,14}$/', // E.164 format validation
            ],
            'consent' => 'required|accepted',
        ], [
            'whatsapp_number.regex' => 'Please enter a valid phone number with country code (e.g., +966501234567)',
            'consent.accepted' => 'You must agree to receive WhatsApp notifications',
        ]);

        // Format phone number to E.164
        $phoneNumber = WhatsAppService::formatPhoneNumber($validated['whatsapp_number']);

        // Validate formatted number
        if (!WhatsAppService::validatePhoneNumber($phoneNumber)) {
            return back()->withErrors([
                'whatsapp_number' => 'Invalid phone number format. Use international format: +966501234567',
            ]);
        }

        // Enable WhatsApp for user
        auth()->user()->enableWhatsApp($phoneNumber);

        return back()->with('success', 'WhatsApp notifications enabled successfully!');
    }

    /**
     * Disable WhatsApp notifications.
     */
    public function disable()
    {
        auth()->user()->disableWhatsApp();

        return back()->with('success', 'WhatsApp notifications disabled successfully.');
    }

    /**
     * Update WhatsApp phone number.
     */
    public function updateNumber(Request $request)
    {
        $validated = $request->validate([
            'whatsapp_number' => [
                'required',
                'string',
                'regex:/^\+?[1-9]\d{1,14}$/',
            ],
        ], [
            'whatsapp_number.regex' => 'Please enter a valid phone number with country code (e.g., +966501234567)',
        ]);

        // Format phone number
        $phoneNumber = WhatsAppService::formatPhoneNumber($validated['whatsapp_number']);

        // Validate formatted number
        if (!WhatsAppService::validatePhoneNumber($phoneNumber)) {
            return back()->withErrors([
                'whatsapp_number' => 'Invalid phone number format.',
            ]);
        }

        // Update number
        auth()->user()->update([
            'whatsapp_number' => $phoneNumber,
        ]);

        return back()->with('success', 'WhatsApp number updated successfully!');
    }
}
