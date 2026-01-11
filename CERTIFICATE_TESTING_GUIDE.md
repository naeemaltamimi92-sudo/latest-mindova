# Certificate System - Testing Guide

## 🎯 Current Implementation Status

**✅ Completed (40%):**
- Database migration (`certificates` table)
- `Certificate` model with relationships and methods
- `CertificateService` with AI-powered summary generation
- Time tracking and role determination logic

**❌ Not Yet Implemented (60%):**
- CertificateController (no routes/endpoints)
- Company confirmation form UI
- PDF certificate template
- Volunteer profile integration
- Download functionality

---

## 🧪 How to Test What's Been Implemented

Since the UI and controller aren't ready yet, you can test the **backend logic** directly using Laravel Tinker or a test script.

### Prerequisites

1. **Ensure migrations are run:**
   ```bash
   php artisan migrate
   ```

2. **Install required dependencies:**
   ```bash
   composer require barryvdh/laravel-dompdf
   composer require openai-php/laravel
   ```

3. **Configure OpenAI in `.env`:**
   ```env
   OPENAI_API_KEY=your_openai_api_key_here
   ```

4. **Ensure you have test data:**
   - At least one completed challenge
   - Volunteers with task assignments
   - Tasks marked as 'completed' or 'submitted'

---

## 📋 Testing Method 1: Laravel Tinker (Quick Test)

### Step 1: Open Tinker
```bash
php artisan tinker
```

### Step 2: Test Certificate Generation

```php
// Load the service
$service = new App\Services\CertificateService();

// Find a completed challenge
$challenge = App\Models\Challenge::where('status', 'completed')->first();

// If no completed challenge, pick any challenge
if (!$challenge) {
    $challenge = App\Models\Challenge::first();
}

// Find a volunteer who worked on this challenge
$volunteer = App\Models\User::whereHas('taskAssignments', function($q) use ($challenge) {
    $q->whereHas('task', function($q2) use ($challenge) {
        $q2->where('challenge_id', $challenge->id);
    });
})->first();

// Generate a single certificate
$certificate = $service->generateCertificate($volunteer, $challenge, 'participation');

// Check the result
echo "Certificate Number: " . $certificate->certificate_number . "\n";
echo "Role: " . $certificate->role . "\n";
echo "Total Hours: " . $certificate->total_hours . "\n";
echo "Summary: " . $certificate->contribution_summary . "\n";
echo "PDF Path: " . $certificate->pdf_path . "\n";
```

### Step 3: Verify Database
```php
// Check if certificate was created
App\Models\Certificate::latest()->first();

// View all certificates
App\Models\Certificate::all();

// Check certificate number format (MDVA-YYYY-XXXXXX)
App\Models\Certificate::pluck('certificate_number');
```

### Step 4: Test Certificate Methods
```php
$cert = App\Models\Certificate::latest()->first();

// Test validation
$cert->isValid();  // Should return true

// Test revocation
$cert->revoke('Testing revocation');
$cert->isValid();  // Should return false

// Check PDF URL
$cert->pdf_url;

// Check formatted type
$cert->formatted_type;  // "Participation Certificate"
```

---

## 📋 Testing Method 2: PHP Script (Detailed Test)

Create a file: `test_certificates.php` in your project root:

```php
<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\CertificateService;
use App\Models\Challenge;
use App\Models\User;
use App\Models\Certificate;

echo "=== Certificate System Test ===\n\n";

// Initialize service
$service = new CertificateService();

// Find a challenge with volunteers
$challenge = Challenge::whereHas('tasks.assignments')->first();

if (!$challenge) {
    echo "ERROR: No challenge with task assignments found.\n";
    echo "Please create a challenge, add tasks, and assign volunteers first.\n";
    exit;
}

echo "Testing with Challenge: {$challenge->title}\n";
echo "Challenge ID: {$challenge->id}\n\n";

// Find volunteers
$volunteers = User::whereHas('taskAssignments', function($q) use ($challenge) {
    $q->whereHas('task', function($q2) use ($challenge) {
        $q2->where('challenge_id', $challenge->id);
    });
})->get();

echo "Found {$volunteers->count()} volunteer(s)\n\n";

if ($volunteers->isEmpty()) {
    echo "ERROR: No volunteers found for this challenge.\n";
    exit;
}

// Test generating certificates for all volunteers
echo "=== Generating Certificates ===\n\n";

foreach ($volunteers as $volunteer) {
    try {
        echo "Processing volunteer: {$volunteer->name}\n";

        $certificate = $service->generateCertificate(
            $volunteer,
            $challenge,
            'participation'
        );

        echo "✓ Certificate created!\n";
        echo "  - Number: {$certificate->certificate_number}\n";
        echo "  - Role: {$certificate->role}\n";
        echo "  - Total Hours: {$certificate->total_hours}\n";
        echo "  - Contribution Summary: {$certificate->contribution_summary}\n";
        echo "  - Contribution Types: " . implode(', ', $certificate->contribution_types) . "\n";
        echo "  - Time Breakdown: Analysis={$certificate->time_breakdown['analysis']}h, ";
        echo "Execution={$certificate->time_breakdown['execution']}h, ";
        echo "Review={$certificate->time_breakdown['review']}h\n";
        echo "  - PDF Path: {$certificate->pdf_path}\n";
        echo "  - PDF URL: {$certificate->pdf_url}\n\n";

    } catch (Exception $e) {
        echo "✗ Error: {$e->getMessage()}\n\n";
    }
}

// Test certificate queries
echo "=== Testing Certificate Queries ===\n\n";

$totalCerts = Certificate::count();
echo "Total Certificates: {$totalCerts}\n";

$activeCerts = Certificate::active()->count();
echo "Active Certificates: {$activeCerts}\n";

$participationCerts = Certificate::participation()->count();
echo "Participation Certificates: {$participationCerts}\n";

$completionCerts = Certificate::completion()->count();
echo "Completion Certificates: {$completionCerts}\n\n";

// Test revocation
echo "=== Testing Revocation ===\n\n";

$testCert = Certificate::latest()->first();
if ($testCert) {
    echo "Testing revocation on certificate: {$testCert->certificate_number}\n";
    echo "Before: isValid = " . ($testCert->isValid() ? 'true' : 'false') . "\n";

    $testCert->revoke('Testing revocation functionality');

    echo "After: isValid = " . ($testCert->isValid() ? 'true' : 'false') . "\n";
    echo "Revoked at: {$testCert->revoked_at}\n";
    echo "Reason: {$testCert->revocation_reason}\n\n";
}

echo "=== Test Complete ===\n";
```

### Run the script:
```bash
php test_certificates.php
```

---

## 📋 Testing Method 3: Check Files Manually

### 1. Verify Migration
```bash
# Check if certificates table exists
php artisan tinker
>>> Schema::hasTable('certificates');  // Should return true
>>> DB::table('certificates')->count();
```

### 2. Check Generated PDFs
```bash
# Navigate to storage directory
cd storage/app/public/certificates

# List generated certificate PDFs
ls -la

# Or on Windows
dir
```

The PDF filenames should match: `certificate_MDVA-YYYY-XXXXXX.pdf`

### 3. Check Database Records
```bash
# Run MySQL query
php artisan tinker
>>> DB::table('certificates')->get();
```

Or use a database client (phpMyAdmin, TablePlus, etc.) to inspect the `certificates` table.

---

## 🔍 What to Verify During Testing

### ✅ Certificate Record Creation
- [ ] Certificate number follows format: `MDVA-YYYY-XXXXXX`
- [ ] Certificate number is unique
- [ ] `user_id`, `challenge_id`, `company_id` are correctly set
- [ ] `certificate_type` is either 'participation' or 'completion'

### ✅ Role Determination
- [ ] Role matches volunteer's actual work:
  - "Problem Analysis" for analysis/research tasks
  - "Technical Solution" for implementation tasks
  - "Validation" for review/testing tasks
  - "On-site Support" for deployment tasks

### ✅ Time Calculation
- [ ] `total_hours` is calculated correctly
- [ ] `time_breakdown` contains analysis, execution, review hours
- [ ] Hours are reasonable (not negative, not excessively high)

### ✅ AI Contribution Summary
- [ ] Summary is generated (not null)
- [ ] Summary is 1-2 sentences
- [ ] Summary is professional and relevant
- [ ] If AI fails, fallback summary is used

### ✅ Contribution Types
- [ ] Array contains relevant contribution types
- [ ] Types match the tasks completed

### ✅ PDF Generation
- [ ] PDF file is created in `storage/app/public/certificates/`
- [ ] `pdf_path` field is populated
- [ ] PDF filename matches certificate number
- [ ] PDF file is valid (can be opened)

### ✅ Model Methods
- [ ] `isValid()` returns true for new certificates
- [ ] `revoke()` marks certificate as revoked
- [ ] `isValid()` returns false after revocation
- [ ] Scopes work: `active()`, `revoked()`, `participation()`, `completion()`

---

## 🐛 Common Issues & Solutions

### Issue 1: "Table 'certificates' doesn't exist"
**Solution:**
```bash
php artisan migrate
# Or specifically:
php artisan migrate --path=database/migrations/2025_12_23_203505_create_certificates_table.php
```

### Issue 2: "Class 'Barryvdh\DomPDF\Facade\Pdf' not found"
**Solution:**
```bash
composer require barryvdh/laravel-dompdf
```

### Issue 3: "OpenAI API error"
**Solution:**
- Check `.env` has `OPENAI_API_KEY=your_key`
- Verify API key is valid
- Check internet connection
- Check OpenAI API status

If OpenAI fails, the system will use the fallback summary.

### Issue 4: "PDF template not found: certificates.template"
**Solution:**
This is expected! The PDF template hasn't been created yet. Create:
`resources/views/certificates/template.blade.php`

Temporary fix - create a simple template:
```blade
<!DOCTYPE html>
<html>
<head>
    <title>Certificate</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        h1 { color: #333; }
    </style>
</head>
<body>
    <h1>Certificate of {{ ucfirst($certificate->certificate_type) }}</h1>
    <p>This certifies that</p>
    <h2>{{ $certificate->volunteer->name }}</h2>
    <p>{{ $certificate->contribution_summary }}</p>
    <p>Role: {{ $certificate->role }}</p>
    <p>Challenge: {{ $certificate->challenge->title }}</p>
    <p>Total Hours: {{ $certificate->total_hours }}</p>
    <p>Certificate Number: {{ $certificate->certificate_number }}</p>
    <p>Issued: {{ $certificate->issued_at->format('F d, Y') }}</p>
</body>
</html>
```

### Issue 5: "No volunteers found"
**Solution:**
Ensure you have:
1. A challenge created
2. Tasks created for the challenge
3. Volunteers assigned to tasks
4. Task assignments marked as 'completed' or 'submitted'

---

## 📊 Expected Test Results

### Successful Test Output:
```
Certificate Number: MDVA-2025-A7F3B9
Role: Technical Solution
Total Hours: 16.00
Summary: Contributed to developing a circuit optimization solution that improved system performance and reliability.
Contribution Types: Technical Solution, Validation
Time Breakdown: Analysis=2.00h, Execution=12.00h, Review=2.00h
PDF Path: certificates/certificate_MDVA-2025-A7F3B9.pdf
PDF URL: http://localhost/storage/certificates/certificate_MDVA-2025-A7F3B9.pdf
```

---

## 🚀 What's Needed for Full End-to-End Testing

To test the **complete certificate flow** (company confirms → volunteers download), you need to implement:

1. **CertificateController** - Handle HTTP requests
2. **Routes** - Define endpoints
3. **Confirmation Form View** - UI for companies
4. **PDF Template** - Professional certificate design
5. **Profile Integration** - Show certificates in volunteer profiles

**Estimated implementation time:** 4-6 hours

Would you like me to implement these missing components so you can test the full flow?

---

## 📝 Quick Test Checklist

- [ ] Run migrations
- [ ] Install dependencies (dompdf, openai-php)
- [ ] Configure OpenAI API key
- [ ] Create test data (challenge + volunteers + tasks)
- [ ] Run Tinker test
- [ ] Verify certificate created in database
- [ ] Check PDF file exists in storage
- [ ] Test certificate methods (isValid, revoke)
- [ ] Verify AI summary generation
- [ ] Check time calculations
- [ ] Test scopes (active, revoked, etc.)

---

## 🎯 Next Steps

After testing the backend:

1. **If everything works:** Implement the remaining UI components
2. **If errors occur:** Check the Common Issues section above
3. **For production:** Add proper error handling and logging
4. **For security:** Add authorization checks in controller

---

**Last Updated:** December 24, 2025
**Implementation Status:** Backend Complete (40%), UI Pending (60%)
