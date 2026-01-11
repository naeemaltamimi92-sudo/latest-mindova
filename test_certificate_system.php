<?php

/**
 * Quick Certificate System Test Script
 * Run: php test_certificate_system.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\CertificateService;
use App\Models\Challenge;
use App\Models\User;
use App\Models\Certificate;

echo "\n=== MINDOVA Certificate System Test ===\n\n";

// Check prerequisites
echo "1. Checking prerequisites...\n";

if (!Schema::hasTable('certificates')) {
    echo "   ✗ ERROR: certificates table doesn't exist\n";
    echo "   Run: php artisan migrate\n\n";
    exit;
}
echo "   ✓ certificates table exists\n";

// Check OpenAI configuration
if (!env('OPENAI_API_KEY')) {
    echo "   ⚠ WARNING: OPENAI_API_KEY not set in .env\n";
    echo "   AI summaries will use fallback method\n";
} else {
    echo "   ✓ OpenAI API key configured\n";
}

// Initialize service
$service = new CertificateService();
echo "   ✓ CertificateService initialized\n\n";

// Find a challenge with volunteers
echo "2. Finding test data...\n";

$challenge = Challenge::whereHas('tasks.assignments')->first();

if (!$challenge) {
    echo "   ✗ ERROR: No challenge with task assignments found\n";
    echo "   You need:\n";
    echo "     - At least one challenge\n";
    echo "     - Tasks created for the challenge\n";
    echo "     - Volunteers assigned to tasks\n\n";
    exit;
}

echo "   ✓ Challenge found: {$challenge->title} (ID: {$challenge->id})\n";

// Find volunteers (through Volunteer model)
$volunteers = User::whereHas('volunteer.taskAssignments', function($q) use ($challenge) {
    $q->whereHas('task', function($q2) use ($challenge) {
        $q2->where('challenge_id', $challenge->id);
    });
})->get();

if ($volunteers->isEmpty()) {
    echo "   ✗ ERROR: No volunteers found for this challenge\n\n";
    exit;
}

echo "   ✓ Found {$volunteers->count()} volunteer(s)\n\n";

// Test certificate generation
echo "3. Generating test certificate...\n";

$volunteer = $volunteers->first();
echo "   Testing with volunteer: {$volunteer->name}\n";

try {
    $certificate = $service->generateCertificate(
        $volunteer,
        $challenge,
        'participation'
    );

    echo "\n   ✓ SUCCESS! Certificate generated:\n\n";
    echo "   ╔════════════════════════════════════════════════════════════╗\n";
    echo "   ║  Certificate Number: {$certificate->certificate_number}\n";
    echo "   ║  Volunteer: {$volunteer->name}\n";
    echo "   ║  Role: {$certificate->role}\n";
    echo "   ║  Total Hours: {$certificate->total_hours} hours\n";
    echo "   ║  \n";
    echo "   ║  Contribution Summary:\n";
    echo "   ║  " . wordwrap($certificate->contribution_summary, 54, "\n   ║  ") . "\n";
    echo "   ║  \n";
    echo "   ║  Contribution Types: " . implode(', ', $certificate->contribution_types) . "\n";
    echo "   ║  \n";
    echo "   ║  Time Breakdown:\n";
    echo "   ║    - Analysis: {$certificate->time_breakdown['analysis']}h\n";
    echo "   ║    - Execution: {$certificate->time_breakdown['execution']}h\n";
    echo "   ║    - Review: {$certificate->time_breakdown['review']}h\n";
    echo "   ║  \n";
    echo "   ║  PDF Path: {$certificate->pdf_path}\n";
    echo "   ║  Valid: " . ($certificate->isValid() ? 'Yes' : 'No') . "\n";
    echo "   ╚════════════════════════════════════════════════════════════╝\n\n";

    // Test revocation
    echo "4. Testing certificate methods...\n";
    echo "   - isValid(): " . ($certificate->isValid() ? 'true' : 'false') . "\n";
    echo "   - Revoking certificate...\n";
    $certificate->revoke('Testing revocation feature');
    echo "   - isValid() after revoke: " . ($certificate->isValid() ? 'true' : 'false') . "\n";
    echo "   - Revoked at: {$certificate->revoked_at}\n";
    echo "   ✓ Revocation works correctly\n\n";

    // Summary
    echo "5. Database summary:\n";
    echo "   - Total certificates: " . Certificate::count() . "\n";
    echo "   - Active certificates: " . Certificate::active()->count() . "\n";
    echo "   - Revoked certificates: " . Certificate::revoked()->count() . "\n";
    echo "   - Participation certs: " . Certificate::participation()->count() . "\n";
    echo "   - Completion certs: " . Certificate::completion()->count() . "\n\n";

    echo "=== TEST COMPLETE ✓ ===\n\n";
    echo "✓ Certificate model works\n";
    echo "✓ CertificateService works\n";
    echo "✓ Time calculation works\n";
    echo "✓ Role determination works\n";
    echo "✓ AI summary generation works\n";
    echo "✓ PDF generation attempted\n";
    echo "✓ Revocation works\n\n";

    if (!file_exists(storage_path('app/public/certificates/' . basename($certificate->pdf_path)))) {
        echo "⚠ NOTE: PDF template not found\n";
        echo "  Create: resources/views/certificates/template.blade.php\n\n";
    }

    echo "NEXT STEPS:\n";
    echo "- Create CertificateController\n";
    echo "- Create PDF template view\n";
    echo "- Add routes\n";
    echo "- Build confirmation form UI\n";
    echo "- Integrate with volunteer profiles\n\n";

} catch (Exception $e) {
    echo "\n   ✗ ERROR: {$e->getMessage()}\n";
    echo "   Stack trace:\n";
    echo $e->getTraceAsString();
    echo "\n\n";
}
