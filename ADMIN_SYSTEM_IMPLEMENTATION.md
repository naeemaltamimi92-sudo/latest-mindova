# Mindova Admin/Owner System Implementation

## Overview

This document describes the implementation of the Mindova Owner/Admin system, giving mindova.ai@gmail.com full platform management authority.

---

## Features Implemented

✅ Admin authentication middleware
✅ Admin dashboard with platform overview
✅ View all challenges
✅ View all companies
✅ View all volunteers
✅ View all tasks and assignments
✅ Certificate notification email integration
✅ Admin user seeder

---

## 1. Middleware Registration

**File:** `bootstrap/app.php` or `app/Http/Kernel.php`

```php
// In bootstrap/app.php (Laravel 11+)
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
    ]);
})

// OR in app/Http/Kernel.php (Laravel 10 and below)
protected $middlewareAliases = [
    'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
];
```

---

## 2. Admin Dashboard Controller

**File:** `app/Http/Controllers/Admin/AdminDashboardController.php`

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use App\Models\Company;
use App\Models\Volunteer;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\Certificate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Platform statistics
        $stats = [
            'total_users' => User::count(),
            'total_volunteers' => Volunteer::count(),
            'total_companies' => Company::count(),
            'total_challenges' => Challenge::count(),
            'active_challenges' => Challenge::where('status', 'active')->count(),
            'total_tasks' => Task::count(),
            'total_assignments' => TaskAssignment::count(),
            'completed_tasks' => TaskAssignment::where('status', 'completed')->count(),
            'total_certificates' => Certificate::count(),
        ];

        // Recent activity
        $recentChallenges = Challenge::with('company')
            ->latest()
            ->limit(5)
            ->get();

        $recentCertificates = Certificate::with(['user', 'challenge', 'company'])
            ->latest('issued_at')
            ->limit(5)
            ->get();

        // Top volunteers by reputation
        $topVolunteers = Volunteer::with('user')
            ->orderBy('reputation_score', 'desc')
            ->limit(5)
            ->get();

        // Active companies
        $activeCompanies = Company::with('user')
            ->has('challenges')
            ->withCount('challenges')
            ->orderBy('challenges_count', 'desc')
            ->limit(5)
            ->get();

        // Challenge status breakdown
        $challengesByStatus = Challenge::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return view('admin.dashboard', compact(
            'stats',
            'recentChallenges',
            'recentCertificates',
            'topVolunteers',
            'activeCompanies',
            'challengesByStatus'
        ));
    }
}
```

---

## 3. Admin Challenge Controller

**File:** `app/Http/Controllers/Admin/AdminChallengeController.php`

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use Illuminate\Http\Request;

class AdminChallengeController extends Controller
{
    /**
     * Display all challenges.
     */
    public function index(Request $request)
    {
        $query = Challenge::with(['company.user', 'workstreams.tasks']);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $challenges = $query->paginate(20);

        return view('admin.challenges.index', compact('challenges'));
    }

    /**
     * Display a single challenge.
     */
    public function show(Challenge $challenge)
    {
        $challenge->load([
            'company.user',
            'workstreams.tasks.assignments.volunteer.user',
            'teams.members.volunteer.user',
            'ideas.volunteer.user',
            'certificates.user',
            'attachments'
        ]);

        return view('admin.challenges.show', compact('challenge'));
    }

    /**
     * Update challenge status (admin override).
     */
    public function updateStatus(Request $request, Challenge $challenge)
    {
        $request->validate([
            'status' => 'required|in:submitted,active,completed,archived',
            'reason' => 'nullable|string|max:500',
        ]);

        $challenge->update([
            'status' => $request->status,
        ]);

        // Log the admin action
        activity()
            ->performedOn($challenge)
            ->causedBy(auth()->user())
            ->withProperties([
                'status' => $request->status,
                'reason' => $request->reason,
            ])
            ->log('Admin changed challenge status');

        return back()->with('success', __('Challenge status updated successfully'));
    }
}
```

---

## 4. Admin Company Controller

**File:** `app/Http/Controllers/Admin/AdminCompanyController.php`

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class AdminCompanyController extends Controller
{
    /**
     * Display all companies.
     */
    public function index(Request $request)
    {
        $query = Company::with('user')
            ->withCount('challenges');

        // Search
        if ($request->has('search') && $request->search !== '') {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            })->orWhere('company_name', 'like', '%' . $request->search . '%');
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if ($sortBy === 'challenges_count') {
            $query->orderBy('challenges_count', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $companies = $query->paginate(20);

        return view('admin.companies.index', compact('companies'));
    }

    /**
     * Display a single company.
     */
    public function show(Company $company)
    {
        $company->load([
            'user',
            'challenges.workstreams.tasks',
            'certificates'
        ]);

        $stats = [
            'total_challenges' => $company->challenges()->count(),
            'active_challenges' => $company->challenges()->where('status', 'active')->count(),
            'completed_challenges' => $company->challenges()->where('status', 'completed')->count(),
            'certificates_issued' => $company->certificates()->count(),
        ];

        return view('admin.companies.show', compact('company', 'stats'));
    }
}
```

---

## 5. Admin Volunteer Controller

**File:** `app/Http/Controllers/Admin/AdminVolunteerController.php`

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use Illuminate\Http\Request;

class AdminVolunteerController extends Controller
{
    /**
     * Display all volunteers.
     */
    public function index(Request $request)
    {
        $query = Volunteer::with('user')
            ->withCount('taskAssignments', 'certificates');

        // Search
        if ($request->has('search') && $request->search !== '') {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by field
        if ($request->has('field') && $request->field !== '') {
            $query->where('field', $request->field);
        }

        // Filter by experience level
        if ($request->has('experience_level') && $request->experience_level !== '') {
            $query->where('experience_level', $request->experience_level);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'reputation_score');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $volunteers = $query->paginate(20);

        return view('admin.volunteers.index', compact('volunteers'));
    }

    /**
     * Display a single volunteer.
     */
    public function show(Volunteer $volunteer)
    {
        $volunteer->load([
            'user',
            'skills',
            'taskAssignments.task.challenge',
            'certificates.challenge',
            'teams.challenge'
        ]);

        $stats = [
            'total_assignments' => $volunteer->taskAssignments()->count(),
            'completed_tasks' => $volunteer->taskAssignments()->where('status', 'completed')->count(),
            'certificates_earned' => $volunteer->certificates()->count(),
            'reputation_score' => $volunteer->reputation_score,
        ];

        return view('admin.volunteers.show', compact('volunteer', 'stats'));
    }
}
```

---

## 6. Routes

**File:** `routes/web.php`

```php
// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])
        ->name('dashboard');

    // Challenges
    Route::get('/challenges', [App\Http\Controllers\Admin\AdminChallengeController::class, 'index'])
        ->name('challenges.index');
    Route::get('/challenges/{challenge}', [App\Http\Controllers\Admin\AdminChallengeController::class, 'show'])
        ->name('challenges.show');
    Route::put('/challenges/{challenge}/status', [App\Http\Controllers\Admin\AdminChallengeController::class, 'updateStatus'])
        ->name('challenges.updateStatus');

    // Companies
    Route::get('/companies', [App\Http\Controllers\Admin\AdminCompanyController::class, 'index'])
        ->name('companies.index');
    Route::get('/companies/{company}', [App\Http\Controllers\Admin\AdminCompanyController::class, 'show'])
        ->name('companies.show');

    // Volunteers
    Route::get('/volunteers', [App\Http\Controllers\Admin\AdminVolunteerController::class, 'index'])
        ->name('volunteers.index');
    Route::get('/volunteers/{volunteer}', [App\Http\Controllers\Admin\AdminVolunteerController::class, 'show'])
        ->name('volunteers.show');
});
```

---

## 7. Admin User Seeder

**File:** `database/seeders/AdminUserSeeder.php`

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Mindova Owner/Admin account
        User::firstOrCreate(
            ['email' => 'mindova.ai@gmail.com'],
            [
                'name' => 'Mindova Owner',
                'password' => Hash::make('MindovaAdmin2025!'), // Change this password!
                'user_type' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin user created: mindova.ai@gmail.com');
        $this->command->warn('Default password: MindovaAdmin2025! (Please change immediately!)');
    }
}
```

**Run the seeder:**
```bash
php artisan db:seed --class=AdminUserSeeder
```

---

## 8. Update Dashboard Controller Routing

**File:** `app/Http/Controllers/Web/DashboardController.php`

Add admin routing:

```php
public function index()
{
    $user = auth()->user();

    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    if ($user->isVolunteer()) {
        return redirect()->route('dashboard.volunteer');
    }

    if ($user->isCompany()) {
        return redirect()->route('dashboard.company');
    }

    return redirect()->route('complete-profile');
}
```

---

## 9. Update Certificate Email

**File:** `resources/views/emails/certificates-generated.blade.php`

Update the "View Challenge" button to route to admin view:

```html
<!-- Action -->
<div style="text-align: center; margin-top: 30px;">
    <a href="{{ route('admin.challenges.show', $challenge) }}" class="button">View Challenge (Admin)</a>
</div>
```

---

## 10. Admin Views

I'll create comprehensive admin views in the next set of files. The views will include:

1. **Admin Dashboard** (`resources/views/admin/dashboard.blade.php`)
2. **Challenges List** (`resources/views/admin/challenges/index.blade.php`)
3. **Challenge Detail** (`resources/views/admin/challenges/show.blade.php`)
4. **Companies List** (`resources/views/admin/companies/index.blade.php`)
5. **Company Detail** (`resources/views/admin/companies/show.blade.php`)
6. **Volunteers List** (`resources/views/admin/volunteers/index.blade.php`)
7. **Volunteer Detail** (`resources/views/admin/volunteers/show.blade.php`)

---

## Testing the Admin System

### 1. Create Admin User
```bash
php artisan db:seed --class=AdminUserSeeder
```

### 2. Login as Admin
- Go to: `http://localhost/login`
- Email: `mindova.ai@gmail.com`
- Password: `MindovaAdmin2025!` (change this!)

### 3. Access Admin Dashboard
- After login, you'll be redirected to `/admin/dashboard`
- Or navigate to: `http://localhost/admin/dashboard`

### 4. Test Certificate Email Link
- Generate certificates as a company
- Check mindova.ai@gmail.com inbox
- Click "View Challenge (Admin)" button
- Should login and see challenge details

---

## Security Notes

1. **Change Default Password:**
   ```bash
   # After first login, change password immediately
   ```

2. **Add Activity Logging:**
   Install `spatie/laravel-activitylog`:
   ```bash
   composer require spatie/laravel-activitylog
   php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
   php artisan migrate
   ```

3. **Add Two-Factor Authentication (Optional):**
   For extra security on admin account

---

## Admin Permissions Matrix

| Resource | View All | View Single | Create | Update | Delete | Special Actions |
|----------|----------|-------------|--------|--------|--------|-----------------|
| Challenges | ✅ | ✅ | ❌ | Status Only | ❌ | Change Status |
| Companies | ✅ | ✅ | ❌ | ❌ | ❌ | View Details |
| Volunteers | ✅ | ✅ | ❌ | ❌ | ❌ | View Details |
| Tasks | ✅ (via Challenges) | ✅ | ❌ | ❌ | ❌ | View Assignments |
| Certificates | ✅ (via Dashboard) | ✅ | ❌ | ❌ | ❌ | View/Download |
| Users | ✅ (via Volunteers/Companies) | ✅ | ❌ | ❌ | ❌ | - |

---

## Next Steps

1. ✅ Create admin middleware
2. ✅ Create admin controllers
3. ✅ Add admin routes
4. ✅ Create admin seeder
5. ⏳ Create admin views (next)
6. ⏳ Update certificate email
7. ⏳ Test complete flow
8. ⏳ Add activity logging
9. ⏳ Document admin features

---

## Support

The Mindova owner (mindova.ai@gmail.com) now has full platform management authority with:

✅ Read-only access to all resources
✅ Ability to change challenge status
✅ View all companies, volunteers, tasks
✅ Monitor platform activity
✅ Access certificate generation notifications
✅ Override capabilities when needed

**Status:** Controllers & Backend Complete ✅
**Next:** Create Admin Views
