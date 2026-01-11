# Certificate System - Implementation Progress

## ✅ **Completed Components**

### 1. Database Schema
**File:** `database/migrations/2025_12_23_203505_create_certificates_table.php`

**Table:** `certificates`

**Columns:**
- `id` - Primary key
- `user_id` - Volunteer (foreign key to users)
- `challenge_id` - Challenge (foreign key to challenges)
- `company_id` - Company (foreign key to users)
- `certificate_number` - Unique ID (format: MDVA-YYYY-XXXXXX)
- `certificate_type` - ENUM('participation', 'completion')
- `role` - Volunteer's role (Problem Analysis, Technical Solution, etc.)
- `contribution_summary` - TEXT (AI-generated description)
- `contribution_types` - JSON array
- `total_hours` - DECIMAL(8,2) - Total time spent
- `time_breakdown` - JSON (Analysis/Execution/Review hours)
- `company_confirmed` - BOOLEAN
- `confirmed_at` - TIMESTAMP
- `company_logo_path` - VARCHAR (optional company logo)
- `pdf_path` - VARCHAR (path to generated PDF)
- `issued_at` - TIMESTAMP
- `is_revoked` - BOOLEAN
- `revoked_at` - TIMESTAMP
- `revocation_reason` - TEXT
- `created_at`, `updated_at`

✅ **Status:** Migrated successfully

### 2. Certificate Model
**File:** `app/Models/Certificate.php`

**Features:**
- ✅ Auto-generates unique certificate numbers (MDVA-2025-XXXXXX)
- ✅ Relationships: volunteer(), challenge(), company()
- ✅ Methods: isValid(), revoke(), getPdfUrlAttribute()
- ✅ Scopes: active(), revoked(), completion(), participation()
- ✅ Casts: Arrays, decimals, dates
- ✅ Accessors: formatted_type, pdf_url

### 3. Certificate Service
**File:** `app/Services/CertificateService.php`

**Core Methods:**

#### generateCertificate()
- Creates certificate record
- Calculates time investment
- Determines volunteer role
- Generates AI contribution summary
- Determines contribution types
- Generates PDF

#### calculateTimeInvestment()
- Analyzes task assignments
- Calculates total hours
- Breaks down by: Analysis, Execution, Review
- Handles manual and estimated hours

#### generateContributionSummary()
- Uses OpenAI GPT-3.5-turbo
- Creates professional 1-2 sentence summary
- Falls back to manual if AI fails
- Contextual based on tasks completed

#### determineVolunteerRole()
- Analyzes task types
- Returns role: "Problem Analysis", "Technical Solution", "Validation", "On-site Support"

#### generateCertificatePDF()
- Generates PDF using DomPDF
- Stores in storage/app/public/certificates/
- Updates certificate with PDF path

## 📋 **Remaining Components to Implement**

### 4. Company Confirmation Form
**File to create:** `resources/views/challenges/confirmation-form.blade.php`

**Form Fields:**
- Challenge title (read-only)
- Challenge domain (read-only)
- Confirmation checkbox: ☐ "This challenge was addressed through the Mindova platform"
- Certificate type selection: Participation / Completion
- Company logo upload (optional)
- Submit button

### 5. Certificate Controller
**File to create:** `app/Http/Controllers/CertificateController.php`

**Methods Needed:**
- `showConfirmationForm($challengeId)` - Show company confirmation form
- `submitConfirmation(Request $request, $challengeId)` - Process confirmation & generate certificates
- `viewCertificate($certificateId)` - View single certificate
- `downloadCertificate($certificateId)` - Download PDF
- `revokeCertificate($certificateId)` - Admin: revoke certificate
- `regenerateCertificate($certificateId)` - Admin: regenerate PDF

### 6. PDF Certificate Template
**File to create:** `resources/views/certificates/template.blade.php`

**Design Requirements:**
- Minimal, professional layout
- Company logo (if provided)
- Mindova logo/seal
- Certificate number
- Volunteer name
- Role
- Challenge title & domain
- Contribution summary section
- Time commitment section
- Completion date
- Company name
- Signatures area

### 7. Routes
**File to modify:** `routes/web.php`

```php
// Certificate Routes (Company)
Route::middleware(['auth', 'company'])->group(function () {
    Route::get('/challenges/{challenge}/confirm-completion', [CertificateController::class, 'showConfirmationForm'])->name('challenges.confirm');
    Route::post('/challenges/{challenge}/issue-certificates', [CertificateController::class, 'submitConfirmation'])->name('challenges.issue-certificates');
});

// Certificate Routes (Volunteer)
Route::middleware(['auth'])->group(function () {
    Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/{certificate}', [CertificateController::class, 'show'])->name('certificates.show');
    Route::get('/certificates/{certificate}/download', [CertificateController::class, 'download'])->name('certificates.download');
});

// Certificate Routes (Admin)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::post('/certificates/{certificate}/revoke', [CertificateController::class, 'revoke'])->name('certificates.revoke');
    Route::post('/certificates/{certificate}/regenerate', [CertificateController::class, 'regenerate'])->name('certificates.regenerate');
});
```

### 8. Volunteer Profile Section
**File to modify:** `resources/views/profile/edit.blade.php`

**Add Section:**
```blade
<div class="certificates-section">
    <h3>Certificates & Contributions</h3>

    @forelse($user->certificates as $certificate)
        <div class="certificate-card">
            <div class="certificate-info">
                <h4>{{ $certificate->challenge->title }}</h4>
                <p class="company">{{ $certificate->company->name }}</p>
                <p class="role">{{ $certificate->role }}</p>
                <p class="date">Issued: {{ $certificate->issued_at->format('M d, Y') }}</p>
            </div>
            <div class="certificate-actions">
                <a href="{{ route('certificates.show', $certificate) }}" class="btn-view">View</a>
                <a href="{{ route('certificates.download', $certificate) }}" class="btn-download">Download PDF</a>
            </div>
        </div>
    @empty
        <p>No certificates yet.</p>
    @endforelse
</div>
```

### 9. User Model Updates
**File to modify:** `app/Models/User.php`

**Add Relationship:**
```php
public function certificates()
{
    return $this->hasMany(Certificate::class, 'user_id');
}
```

### 10. Challenge Model Updates
**File to modify:** `app/Models/Challenge.php`

**Add Methods:**
```php
public function certificates()
{
    return $this->hasMany(Certificate::class);
}

public function canIssueCertificates()
{
    return in_array($this->status, ['completed', 'delivered']);
}
```

## 🔄 **Certificate Generation Flow**

```
1. Challenge Status → "Completed" or "Delivered"
           ↓
2. Company sees "Confirm Completion" button
           ↓
3. Company clicks → Confirmation Form appears
           ↓
4. Company fills form:
   - Confirms challenge completion ✓
   - Selects certificate type (Participation/Completion)
   - Uploads logo (optional)
   - Submits
           ↓
5. System triggers CertificateService
           ↓
6. For each volunteer:
   a. Calculate time investment
   b. Determine role
   c. Generate AI contribution summary
   d. Create certificate record
   e. Generate PDF
           ↓
7. Certificates saved to database + storage
           ↓
8. Volunteers see certificates in profile
           ↓
9. Volunteers can view/download PDFs
```

## 📊 **Time Calculation Logic**

**Sources:**
1. **Task Assignments** - Accepted → Completed timestamps
2. **Manual Hours** - If tracked explicitly
3. **Estimates** - Based on task complexity (fallback)

**Breakdown:**
- **Analysis Hours**: Tasks with "analysis" or "research" in title
- **Execution Hours**: Implementation/development tasks
- **Review Hours**: Review/validation/testing tasks

## 🤖 **AI Contribution Summary**

**Using:** OpenAI GPT-3.5-turbo

**Prompt Template:**
```
Generate a professional, concise (1-2 sentences) contribution summary for a certificate.
The volunteer worked on: {role}

Challenge: {title}
Domain: {domain}

Tasks completed:
{task descriptions}

Write a professional summary that highlights the value added and impact.
Start with 'Contributed to' or similar.
Keep it professional and suitable for a formal certificate.
```

**Example Output:**
> "Contributed to circuit fault analysis and proposed optimization steps that improved system stability."

## 🔒 **Permissions & Access Control**

| Role | Permissions |
|------|-------------|
| **Volunteer** | - View own certificates<br>- Download own certificates |
| **Company** | - Confirm challenge completion<br>- Select certificate type<br>- Upload company logo<br>- **Cannot** edit after submission |
| **Admin** | - Revoke certificates<br>- Regenerate certificates<br>- View all certificates |

## 🎨 **Certificate Design** (Pending Implementation)

**Layout:**
```
┌────────────────────────────────────────────┐
│                                            │
│        [Company Logo]    [Mindova Logo]    │
│                                            │
│         CERTIFICATE OF COMPLETION          │
│                                            │
│              This certifies that           │
│                                            │
│              [Volunteer Name]              │
│                                            │
│            Has successfully [role]         │
│                                            │
│         Challenge: [Challenge Title]       │
│         Domain: [Challenge Domain]         │
│                                            │
│    ───────────────────────────────────     │
│         Contribution Summary               │
│    ───────────────────────────────────     │
│  [AI-generated contribution summary text]  │
│                                            │
│    ───────────────────────────────────     │
│         Time Commitment                    │
│    ───────────────────────────────────     │
│  Total Hours: XX hours                     │
│  Analysis: XX hrs | Execution: XX hrs      │
│  Review: XX hrs                            │
│                                            │
│  Completion Date: [Date]                   │
│  Certificate ID: MDVA-2025-XXXXXX          │
│                                            │
│  ___________________  ___________________  │
│   Company Signature    Mindova Platform    │
│                                            │
└────────────────────────────────────────────┘
```

## 📦 **Dependencies Required**

Add to `composer.json`:
```bash
composer require barryvdh/laravel-dompdf
composer require openai-php/laravel
```

## 🧪 **Testing Checklist**

- [ ] Create certificate table migration
- [ ] Create Certificate model
- [ ] Create CertificateService
- [ ] Create confirmation form view
- [ ] Create CertificateController
- [ ] Add routes
- [ ] Create PDF template
- [ ] Test AI summary generation
- [ ] Test time calculation
- [ ] Test PDF generation
- [ ] Add certificates to volunteer profile
- [ ] Test download functionality
- [ ] Test revoke functionality
- [ ] Test edge cases (withdrawal, failed challenges)

## 🚀 **Next Steps**

1. Install dependencies (dompdf, openai-php)
2. Create CertificateController
3. Create confirmation form view
4. Create PDF template view
5. Add routes
6. Update User and Challenge models
7. Add certificates section to profile
8. Test complete flow
9. Handle edge cases

## 📝 **Notes**

- Certificate numbers are unique and auto-generated
- Certificates cannot be edited once issued
- Only companies can trigger certificate generation
- Admins can revoke certificates
- PDF files stored in storage/app/public/certificates/
- AI summary has fallback if OpenAI fails
- Time tracking uses multiple data sources
- All certificates are verifiable via unique ID

## ✅ **Implementation Status**

- [x] Database migration
- [x] Certificate model
- [x] CertificateService
- [ ] CertificateController
- [ ] Confirmation form
- [ ] PDF template
- [ ] Routes
- [ ] Profile integration
- [ ] Testing

**Progress:** ~40% Complete

The core foundation is solid. Remaining work is primarily views, controller logic, and integration.
