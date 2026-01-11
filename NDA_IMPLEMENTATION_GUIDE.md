# NDA (Non-Disclosure Agreement) System Implementation Guide

## Overview
This document provides a comprehensive guide for implementing the NDA system in the Mindova platform as requested.

## Requirements Summary
1. All challenges are protected under challenge-specific NDAs
2. Volunteers must sign a general NDA upon registration
3. Volunteers cannot view task details or accept invitations without signing the challenge-specific NDA
4. Company owners receive notifications when volunteers accept, but without team details for privacy

## Database Schema ✅ COMPLETED

### Tables Created:
1. **nda_agreements** - Stores NDA templates (general and challenge-specific)
2. **challenge_nda_signings** - Tracks NDA signings per challenge per user
3. **Updated volunteers table** - Added: general_nda_signed, general_nda_signed_at, general_nda_version
4. **Updated challenges table** - Added: requires_nda, nda_custom_terms, confidentiality_level
5. **Updated task_assignments table** - Added: nda_signed, nda_signed_at

## Models Created ✅ COMPLETED
- `App\Models\NdaAgreement`
- `App\Models\ChallengeNdaSigning`

---

## Step-by-Step Implementation

### STEP 1: Create NDA Seeder
Create the default NDA content that all volunteers must sign.

**File**: `database/seeders/NdaSeeder.php`

```php
<?php

namespace Database\Seeders;

use App\Models\NdaAgreement;
use Illuminate\Database\Seeder;

class NdaSeeder extends Seeder
{
    public function run(): void
    {
        // General Platform NDA
        NdaAgreement::create([
            'title' => 'Mindova Platform General Non-Disclosure Agreement',
            'type' => 'general',
            'version' => '1.0',
            'is_active' => true,
            'effective_date' => now(),
            'content' => $this->getGeneralNdaContent(),
        ]);

        // Challenge-Specific NDA Template
        NdaAgreement::create([
            'title' => 'Challenge-Specific Non-Disclosure Agreement',
            'type' => 'challenge_specific',
            'version' => '1.0',
            'is_active' => true,
            'effective_date' => now(),
            'content' => $this->getChallengeNdaContent(),
        ]);
    }

    private function getGeneralNdaContent(): string
    {
        return <<<'NDA'
# MINDOVA PLATFORM NON-DISCLOSURE AGREEMENT

This Non-Disclosure Agreement ("Agreement") is entered into as of the date of signing between:

**DISCLOSING PARTY**: Companies and challenge owners using the Mindova platform
**RECEIVING PARTY**: Volunteer (the individual signing this agreement)

## 1. PURPOSE
The Receiving Party may have access to Confidential Information (as defined below) in connection with participating in challenges and tasks on the Mindova platform.

## 2. CONFIDENTIAL INFORMATION
"Confidential Information" means all information disclosed by the Disclosing Party, including but not limited to:
- Challenge descriptions, requirements, and details
- Business strategies, plans, and processes
- Technical data, designs, and specifications
- Financial information
- Customer and supplier information
- Any information marked as "Confidential" or that would reasonably be considered confidential

## 3. OBLIGATIONS
The Receiving Party agrees to:
a) Maintain strict confidentiality of all Confidential Information
b) Use Confidential Information solely for completing assigned tasks
c) Not disclose Confidential Information to any third party without prior written consent
d) Protect Confidential Information with the same degree of care used for their own confidential information
e) Not use Confidential Information for personal benefit or competitive purposes

## 4. EXCLUSIONS
This Agreement does not apply to information that:
a) Was publicly known prior to disclosure
b) Becomes publicly known through no fault of the Receiving Party
c) Was independently developed by the Receiving Party
d) Was rightfully received from a third party without breach of confidentiality

## 5. TERM
This Agreement remains in effect for 5 years from the date of signing, or until Confidential Information becomes publicly available through no fault of the Receiving Party.

## 6. RETURN OF INFORMATION
Upon request or upon completion of work, the Receiving Party shall promptly return or destroy all Confidential Information.

## 7. NO LICENSE
Nothing in this Agreement grants any license or rights to Confidential Information except as expressly stated.

## 8. GOVERNING LAW
This Agreement shall be governed by applicable laws.

By signing below, the Receiving Party acknowledges having read, understood, and agreed to be bound by this Agreement.
NDA;
    }

    private function getChallengeNdaContent(): string
    {
        return <<<'NDA'
# CHALLENGE-SPECIFIC NON-DISCLOSURE AGREEMENT

This Challenge-Specific NDA supplements the General Platform NDA and applies specifically to:

**CHALLENGE**: {{challenge_title}}
**CONFIDENTIALITY LEVEL**: {{confidentiality_level}}

## ADDITIONAL TERMS FOR THIS CHALLENGE

1. **Specific Scope**: This NDA covers all information related to the above-mentioned challenge, including:
   - Challenge requirements and specifications
   - Task details and deliverables
   - Communications with the challenge owner
   - Solutions and work products created
   - Any additional materials provided

2. **Enhanced Protection**: Given the confidential nature of this challenge:
   - Screenshots, recordings, or copies are strictly prohibited without written permission
   - All work must be conducted in secure environments
   - No discussion of challenge details outside the Mindova platform

3. **Custom Terms**: {{custom_terms}}

4. **Breach Consequences**: Unauthorized disclosure may result in:
   - Immediate removal from the challenge
   - Account suspension or termination
   - Legal action for damages
   - Loss of reputation score and future opportunities

5. **Acknowledgment**: By signing, you confirm that:
   - You have no conflicts of interest with this challenge
   - You will not work on competing challenges simultaneously
   - You understand the sensitive nature of the information
   - You accept full responsibility for maintaining confidentiality

**NOTE**: This agreement is legally binding and enforceable.
NDA;
    }
}
```

**Run the seeder**:
```bash
php artisan db:seed --class=NdaSeeder
```

---

### STEP 2: Create NDA Controller
Handle NDA signing operations.

**File**: `app/Http/Controllers/NdaController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\NdaAgreement;
use App\Models\ChallengeNdaSigning;
use App\Models\Challenge;
use Illuminate\Http\Request;

class NdaController extends Controller
{
    /**
     * Show the general NDA for signing during registration.
     */
    public function showGeneralNda()
    {
        $nda = NdaAgreement::getActiveGeneralNda();

        if (!$nda) {
            abort(500, 'General NDA not found. Please contact support.');
        }

        return view('nda.general', compact('nda'));
    }

    /**
     * Sign the general NDA (during volunteer registration).
     */
    public function signGeneralNda(Request $request)
    {
        $request->validate([
            'accept' => 'required|accepted',
            'full_name' => 'required|string|max:255',
        ]);

        $user = $request->user();

        if (!$user->volunteer) {
            return back()->with('error', 'Only volunteers can sign NDAs.');
        }

        $nda = NdaAgreement::getActiveGeneralNda();

        $user->volunteer->update([
            'general_nda_signed' => true,
            'general_nda_signed_at' => now(),
            'general_nda_version' => $nda->version,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'NDA signed successfully. You can now participate in challenges!');
    }

    /**
     * Show challenge-specific NDA before viewing task details.
     */
    public function showChallengeNda(Challenge $challenge)
    {
        $user = auth()->user();

        // Check if already signed
        if (ChallengeNdaSigning::hasSignedNda($user->id, $challenge->id)) {
            return redirect()->route('challenges.show', $challenge);
        }

        $ndaTemplate = NdaAgreement::getActiveChallengeNda();

        // Replace placeholders with challenge-specific data
        $ndaContent = str_replace(
            ['{{challenge_title}}', '{{confidentiality_level}}', '{{custom_terms}}'],
            [
                $challenge->title,
                ucfirst($challenge->confidentiality_level ?? 'standard'),
                $challenge->nda_custom_terms ?? 'No additional terms.'
            ],
            $ndaTemplate->content
        );

        return view('nda.challenge', [
            'challenge' => $challenge,
            'nda' => $ndaTemplate,
            'ndaContent' => $ndaContent,
        ]);
    }

    /**
     * Sign challenge-specific NDA.
     */
    public function signChallengeNda(Challenge $challenge, Request $request)
    {
        $request->validate([
            'accept' => 'required|accepted',
            'full_name' => 'required|string|max:255',
            'signature' => 'required|string', // Digital signature field
        ]);

        $user = $request->user();

        // Check if general NDA is signed
        if (!$user->volunteer->general_nda_signed) {
            return back()->with('error', 'You must sign the general platform NDA first.');
        }

        // Check if already signed
        if (ChallengeNdaSigning::hasSignedNda($user->id, $challenge->id)) {
            return redirect()->route('challenges.show', $challenge)
                ->with('info', 'You have already signed the NDA for this challenge.');
        }

        $nda = NdaAgreement::getActiveChallengeNda();
        $timestamp = now()->toDateTimeString();

        ChallengeNdaSigning::create([
            'user_id' => $user->id,
            'challenge_id' => $challenge->id,
            'nda_agreement_id' => $nda->id,
            'signer_name' => $request->full_name,
            'signer_email' => $user->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'signed_at' => now(),
            'signature_hash' => ChallengeNdaSigning::generateSignatureHash(
                $user->id,
                $challenge->id,
                $timestamp
            ),
            'is_valid' => true,
        ]);

        return redirect()->route('challenges.show', $challenge)
            ->with('success', 'NDA signed successfully! You can now view challenge details and accept task invitations.');
    }
}
```

---

### STEP 3: Create NDA Middleware
Protect routes that require NDA signing.

**File**: `app/Http/Middleware/EnsureNdaSigned.php`

```bash
php artisan make:middleware EnsureNdaSigned
```

```php
<?php

namespace App\Http\Middleware;

use App\Models\ChallengeNdaSigning;
use Closure;
use Illuminate\Http\Request;

class EnsureNdaSigned
{
    public function handle(Request $request, Closure $next, string $type = 'general')
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Check general NDA
        if ($type === 'general') {
            if (!$user->volunteer || !$user->volunteer->general_nda_signed) {
                return redirect()->route('nda.general')
                    ->with('warning', 'Please sign the platform NDA to continue.');
            }
        }

        // Check challenge-specific NDA
        if ($type === 'challenge') {
            $challenge = $request->route('challenge');

            if ($challenge && !ChallengeNdaSigning::hasSignedNda($user->id, $challenge->id)) {
                return redirect()->route('nda.challenge', $challenge)
                    ->with('warning', 'Please sign the challenge-specific NDA to view details.');
            }
        }

        return $next($request);
    }
}
```

**Register middleware in** `app/Http/Kernel.php`:
```php
protected $middlewareAliases = [
    // ... existing middleware
    'nda.signed' => \App\Http\Middleware\EnsureNdaSigned::class,
];
```

---

### STEP 4: Update Routes
Add NDA routes and protect existing routes.

**File**: `routes/web.php`

```php
// NDA Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/nda/general', [NdaController::class, 'showGeneralNda'])->name('nda.general');
    Route::post('/nda/general/sign', [NdaController::class, 'signGeneralNda'])->name('nda.general.sign');

    Route::get('/challenges/{challenge}/nda', [NdaController::class, 'showChallengeNda'])->name('nda.challenge');
    Route::post('/challenges/{challenge}/nda/sign', [NdaController::class, 'signChallengeNda'])->name('nda.challenge.sign');
});

// Protected Routes - Require NDA
Route::middleware(['auth', 'nda.signed:general'])->group(function () {
    // Tasks routes require both general AND challenge NDA
    Route::middleware('nda.signed:challenge')->group(function () {
        Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
        Route::post('/assignments/{assignment}/accept', [TaskAssignmentController::class, 'accept'])->name('assignments.accept');
    });
});
```

---

### STEP 5: Update TaskAssignmentController
Add NDA validation before accepting assignments.

**Add to the `accept` method** (around line 73):

```php
public function accept(TaskAssignment $assignment, Request $request)
{
    $user = $request->user();

    if (!$user->volunteer || $assignment->volunteer_id !== $user->volunteer->id) {
        return response()->json([
            'message' => 'Unauthorized to accept this assignment',
        ], 403);
    }

    // === NDA CHECK - NEW CODE ===
    // Check if volunteer has signed general NDA
    if (!$user->volunteer->general_nda_signed) {
        return back()->with('error', 'You must sign the platform NDA before accepting tasks.')
            ->withInput();
    }

    // Check if volunteer has signed challenge-specific NDA
    $task = $assignment->task;
    $challenge = $task->challenge;

    if ($challenge->requires_nda && !ChallengeNdaSigning::hasSignedNda($user->id, $challenge->id)) {
        return redirect()->route('nda.challenge', $challenge)
            ->with('warning', 'You must sign the challenge-specific NDA before accepting this task.');
    }
    // === END NDA CHECK ===

    if ($assignment->invitation_status !== 'invited') {
        // ... rest of existing code
    }

    // ... existing availability checks ...

    // Accept the assignment
    $assignment->update([
        'invitation_status' => 'accepted',
        'responded_at' => now(),
        'nda_signed' => true,  // NEW: Mark NDA as signed
        'nda_signed_at' => now(),  // NEW: Record when NDA was signed
    ]);

    // ... rest of existing code
}
```

---

### STEP 6: Create NDA Views
Create Blade templates for NDA signing.

**File**: `resources/views/nda/general.blade.php`

```blade
@extends('layouts.app')

@section('title', 'Platform Non-Disclosure Agreement')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="card">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">
            🔒 Mindova Platform NDA
        </h1>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <p class="text-sm text-blue-900">
                <strong>Important:</strong> All challenges on Mindova are protected under this Non-Disclosure Agreement.
                You must sign this agreement to participate in any challenges or view task details.
            </p>
        </div>

        <div class="prose max-w-none bg-gray-50 p-6 rounded-lg mb-6 max-h-96 overflow-y-auto">
            {!! nl2br(e($nda->content)) !!}
        </div>

        <form action="{{ route('nda.general.sign') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label class="label">Full Legal Name *</label>
                <input type="text" name="full_name" value="{{ auth()->user()->name }}"
                       class="input-field" required>
                <p class="text-xs text-gray-500 mt-1">Enter your full legal name as it appears on official documents</p>
            </div>

            <div class="mb-6">
                <label class="flex items-start">
                    <input type="checkbox" name="accept" value="1" class="mt-1" required>
                    <span class="ml-2 text-sm text-gray-700">
                        I have read and understood the above Non-Disclosure Agreement, and I agree to be legally bound by its terms.
                        I understand that breaching this agreement may result in legal consequences.
                    </span>
                </label>
            </div>

            <div class="flex justify-between items-center">
                <p class="text-xs text-gray-500">
                    Signed on: {{ now()->format('F d, Y') }}<br>
                    IP Address: {{ request()->ip() }}
                </p>
                <button type="submit" class="btn-primary px-8">
                    ✍️ Sign NDA and Continue
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
```

**File**: `resources/views/nda/challenge.blade.php`

```blade
@extends('layouts.app')

@section('title', 'Challenge NDA - ' . $challenge->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="card">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">
            🔐 Challenge-Specific NDA
        </h1>
        <p class="text-gray-600 mb-6">{{ $challenge->title }}</p>

        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <p class="text-sm text-red-900">
                <strong>⚠️ Confidential Challenge:</strong> This challenge contains sensitive business information.
                You must sign this challenge-specific NDA before viewing task details or accepting invitations.
            </p>
        </div>

        <div class="mb-4">
            <span class="px-3 py-1 rounded-full text-sm font-semibold
                {{ $challenge->confidentiality_level === 'critical' ? 'bg-red-100 text-red-800' : '' }}
                {{ $challenge->confidentiality_level === 'high' ? 'bg-orange-100 text-orange-800' : '' }}
                {{ $challenge->confidentiality_level === 'standard' ? 'bg-blue-100 text-blue-800' : '' }}">
                Confidentiality: {{ ucfirst($challenge->confidentiality_level) }}
            </span>
        </div>

        <div class="prose max-w-none bg-gray-50 p-6 rounded-lg mb-6 max-h-96 overflow-y-auto">
            {!! \Illuminate\Support\Str::markdown($ndaContent) !!}
        </div>

        <form action="{{ route('nda.challenge.sign', $challenge) }}" method="POST">
            @csrf

            <div class="mb-6">
                <label class="label">Full Legal Name *</label>
                <input type="text" name="full_name" value="{{ auth()->user()->name }}"
                       class="input-field" required>
            </div>

            <div class="mb-6">
                <label class="label">Digital Signature *</label>
                <input type="text" name="signature" class="input-field"
                       placeholder="Type your full name to sign" required>
                <p class="text-xs text-gray-500 mt-1">Type your full name exactly as entered above</p>
            </div>

            <div class="mb-6">
                <label class="flex items-start">
                    <input type="checkbox" name="accept" value="1" class="mt-1" required>
                    <span class="ml-2 text-sm text-gray-700">
                        I acknowledge that I have read, understood, and agree to be bound by both the General Platform NDA
                        and this Challenge-Specific NDA. I understand the confidential nature of this challenge and
                        accept full responsibility for maintaining confidentiality.
                    </span>
                </label>
            </div>

            <div class="flex justify-between items-center">
                <a href="{{ route('assignments.my') }}" class="text-gray-600 hover:text-gray-900">
                    ← Back to Assignments
                </a>
                <button type="submit" class="btn-primary px-8">
                    ✍️ Sign NDA and Access Challenge
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
```

---

### STEP 7: Update Notifications
Ensure company notifications don't reveal team details.

**File**: `app/Notifications/TaskAcceptedNotification.php` (modify or create)

```php
public function toMail($notifiable)
{
    return (new MailMessage)
        ->subject('Task Accepted - ' . $this->assignment->task->title)
        ->greeting('Hello!')
        ->line('A volunteer has accepted a task invitation for your challenge: ' . $this->assignment->task->challenge->title)
        ->line('Task: ' . $this->assignment->task->title)
        ->line('Status: Accepted')
        // DO NOT include volunteer name or details for privacy
        ->line('A qualified volunteer has been assigned to this task.')
        ->action('View Challenge Progress', url('/challenges/' . $this->assignment->task->challenge_id))
        ->line('Thank you for using Mindova!');
}
```

---

### STEP 8: Update Task Show View
Hide details until NDA is signed.

**File**: `resources/views/tasks/show.blade.php`

Add at the top (after @section('content')):

```blade
@php
    $user = auth()->user();
    $challenge = $task->challenge;
    $hasSignedNda = $user && \App\Models\ChallengeNdaSigning::hasSignedNda($user->id, $challenge->id);
@endphp

@if(!$hasSignedNda)
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="card border-2 border-yellow-400">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">🔒 NDA Required</h2>
            <p class="text-gray-700 mb-6">
                This task is part of a confidential challenge. You must sign a Non-Disclosure Agreement
                before viewing task details or accepting invitations.
            </p>
            <a href="{{ route('nda.challenge', $challenge) }}" class="btn-primary">
                📝 Sign NDA to Continue
            </a>
        </div>
    </div>
    @php return; @endphp
@endif

{{-- Rest of your existing task show content --}}
```

---

## Summary of Changes

### ✅ Completed:
1. **Database Schema** - 3 migrations created and run
2. **Models** - NdaAgreement and ChallengeNdaSigning models created
3. **Relationships** - Proper Eloquent relationships established

### 📋 TODO (Follow the guide above):
4. **NDA Seeder** - Create default NDA content
5. **NDA Controller** - Handle signing operations
6. **NDA Middleware** - Protect routes
7. **Routes** - Add NDA routes and protect existing ones
8. **TaskAssignmentController** - Add NDA checks
9. **Views** - Create NDA signing pages
10. **Notifications** - Update to hide team details
11. **Task Views** - Hide details until NDA signed

---

## Testing Checklist

- [ ] Volunteer can sign general NDA during registration
- [ ] Volunteer cannot view task details without signing challenge NDA
- [ ] Volunteer cannot accept assignment without NDA
- [ ] Challenge NDA is properly recorded in database
- [ ] Company receives notification without volunteer details
- [ ] NDA signature hash is properly generated
- [ ] Multiple volunteers can sign same challenge NDA
- [ ] IP address and user agent are recorded
- [ ] General NDA version is tracked
- [ ] Middleware properly protects routes

---

## Security Considerations

1. **Signature Hash**: Uses SHA-256 with app key for verification
2. **IP Tracking**: Records IP address for legal evidence
3. **Timestamps**: All signatures timestamped for audit trail
4. **Validation**: Prevents duplicate signings with unique constraint
5. **Revocation**: Built-in support for revoking NDAs if needed

---

## Legal Disclaimer

⚠️ **Important**: The NDA content provided in this implementation is for demonstration purposes only.
Before deploying to production, you **MUST**:
- Consult with a qualified attorney
- Review and customize NDA language for your jurisdiction
- Ensure compliance with applicable laws
- Consider electronic signature regulations (e.g., ESIGN Act, eIDAS)
- Implement proper audit logging
- Add terms for dispute resolution

---

## Next Steps

1. Run the NDA seeder to create default agreements
2. Implement the NdaController
3. Create the middleware and register it
4. Update routes with NDA protection
5. Create the NDA signing views
6. Test the complete workflow
7. Have legal review NDA content before production use

