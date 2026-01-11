# Full Cycle Test Data Seeder - Documentation

## Overview

The `FullCycleTestSeeder` creates comprehensive test data covering ALL workflow states in the Mindova platform, allowing you to manually test every feature from start to finish.

## What Gets Created

### 👥 Users
- **4 Companies** (various industries: Tech, Manufacturing, Healthcare, E-commerce)
- **8 Volunteers** (diverse skills and experience levels)

### 🎯 Challenges (All States)
1. **COMPLETED** - E-commerce Recommendation Engine
2. **ACTIVE (In Progress)** - AI Customer Support Chatbot
3. **ACTIVE (Mixed States)** - Chemical Production Optimization
4. **ANALYZING** - Patient Analytics Dashboard
5. **SUBMITTED** - Digital Marketing Strategy
6. **COMMUNITY DISCUSSION (Active)** - Mobile App UX Improvements
7. **COMMUNITY DISCUSSION (New)** - E-commerce Checkout Optimization

### 📋 Tasks & Assignments (All States)
- ✅ **Completed** tasks with submissions
- 🔄 **In Progress** tasks being worked on
- ✓ **Accepted** tasks ready to start
- ✉️ **Invited** tasks waiting for response
- ❌ **Declined** tasks rejected by volunteers
- ⏳ **Pending** tasks not yet assigned

### 👥 Teams
- **Active Team** - AI Chatbot Squad (4 members, 1 pending)
- **Forming Team** - Process Optimization Team (2 members)

### 📤 Work Submissions
- 1 approved submission with detailed feedback
- AI quality score and feedback included

### 💬 Community Content
- High-quality ideas with AI scoring
- Comments on ideas
- Demonstrates community discussion flow

### 🔒 NDAs
- General NDAs signed by all volunteers
- Challenge-specific NDAs for sensitive projects

---

## Installation & Usage

### Option 1: Fresh Database (Recommended for Testing)

```bash
# Reset database and run migrations
php artisan migrate:fresh

# Seed with comprehensive test data
php artisan db:seed

# Or run specifically
php artisan db:seed --class=FullCycleTestSeeder
```

### Option 2: Add to Existing Database

```bash
# Run only the Full Cycle seeder
php artisan db:seed --class=FullCycleTestSeeder
```

⚠️ **Warning**: The seeder will prompt you to clear existing data. Answer 'yes' only if you want to start fresh!

---

## Test User Login Credentials

### 🏢 COMPANIES

| Email | Password | Industry | Description |
|-------|----------|----------|-------------|
| `tech@company.com` | `password123` | Technology | AI & ML solutions provider |
| `manufacturing@company.com` | `password123` | Manufacturing | Sustainable manufacturing |
| `health@company.com` | `password123` | Healthcare | Digital health platform |
| `shop@company.com` | `password123` | E-commerce | AI-powered e-commerce |

### 👥 VOLUNTEERS

| Email | Password | Field | Level | Reputation |
|-------|----------|-------|-------|------------|
| `alex@volunteer.com` | `password123` | Software Engineering | Expert (10y) | 92 |
| `sarah@volunteer.com` | `password123` | Data Science | Expert (8y) | 95 |
| `michael@volunteer.com` | `password123` | Chemical Engineering | Mid (6y) | 78 |
| `emma@volunteer.com` | `password123` | UX Design | Mid (5y) | 82 |
| `james@volunteer.com` | `password123` | Frontend Development | Mid (4y) | 75 |
| `sophia@volunteer.com` | `password123` | Marketing | Mid (6y) | 70 |
| `david@volunteer.com` | `password123` | Project Management | Manager (12y) | 98 |
| `olivia@volunteer.com` | `password123` | Backend Development | Mid (5y) | 68 |

---

## Manual Testing Guide

### 🏢 Company Workflow Testing

#### 1. Login as Company (tech@company.com)
```
Email: tech@company.com
Password: password123
```

#### 2. View Dashboard
- ✅ See submitted challenges (3)
- ✅ View challenge statuses
- ✅ Check stats overview

#### 3. Submit New Challenge
- Navigate to "Submit Challenge"
- Fill in details
- Submit and wait for AI analysis
- **Expected**: Status changes to "analyzing" → "active"

#### 4. View Active Challenge
- Click on "AI Customer Support Chatbot"
- **Verify**:
  - Challenge details displayed
  - Workstreams visible
  - Tasks listed with statuses
  - NO teams section (removed as requested)

#### 5. Review Submitted Work
- Navigate to task with "submitted" status
- View submission details
- Check AI quality score
- Approve or request changes
- **Expected**: Task status updates

---

### 👥 Volunteer Workflow Testing

#### 1. Login as Volunteer (alex@volunteer.com)
```
Email: alex@volunteer.com
Password: password123
```

#### 2. View Dashboard
- ✅ See task invitations
- ✅ View active tasks
- ✅ Check reputation score (92)
- ✅ Review skills profile

#### 3. Accept Task Invitation
- Click on pending invitation
- Review task details
- Click "Accept"
- **Expected**: Status → "accepted", appears in "My Active Tasks"

#### 4. Start Working on Task
- Navigate to accepted task
- Click "Start Working"
- **Expected**: Status → "in_progress"

#### 5. Submit Work
- Complete the task
- Click "Submit Work"
- Upload files (optional)
- Add description
- Submit
- **Expected**: Status → "submitted", awaits review

#### 6. Join Team
- View team invitation
- Review team details
- Accept invitation
- **Expected**: Can access team chat and collaboration features

#### 7. Participate in Community Discussion
- Navigate to Community Challenges
- Filter by field (UX Design)
- View challenge "Mobile App UX Improvement Ideas"
- Post idea or comment
- **Expected**: AI quality score assigned

---

### 🔄 Full Cycle End-to-End Test

**Goal**: Test complete workflow from challenge submission to completion

1. **Company submits challenge** (health@company.com)
   - Submit new marketing challenge
   - Wait for AI analysis

2. **AI processes challenge** (run queue worker)
   ```bash
   php artisan queue:work
   ```
   - Brief gets refined
   - Complexity scored
   - Tasks created
   - Volunteers matched

3. **Volunteers receive invitations** (sophia@volunteer.com)
   - Check dashboard for new invitation
   - Review match score
   - Accept task

4. **Volunteer works on task**
   - Start working
   - Track progress
   - Submit solution

5. **Company reviews submission**
   - View submitted work
   - Check AI quality score
   - Approve or request changes

6. **Challenge completion**
   - All tasks completed
   - Final review
   - Challenge marked complete

---

## Data Structure Overview

### Challenge States Covered
- ✅ `submitted` - Just created, needs AI analysis
- ✅ `analyzing` - Being processed by AI
- ✅ `active` - Ready for work, tasks assigned
- ✅ `completed` - All work finished

### Task Assignment States
- ✅ `invited` - Waiting for volunteer response
- ✅ `accepted` - Volunteer accepted, ready to start
- ✅ `in_progress` - Currently being worked on
- ✅ `submitted` - Work submitted for review
- ✅ `declined` - Volunteer declined the task
- ✅ `completed` - Reviewed and approved

### Team States
- ✅ `forming` - Gathering members
- ✅ `active` - All members accepted, working
- ✅ `completed` - Challenge finished

---

## Testing Specific Features

### Test AI Analysis Pipeline
```bash
# Trigger AI analysis for submitted challenge
php artisan tinker

$challenge = Challenge::where('status', 'submitted')->first();
\App\Jobs\AnalyzeChallengeBrief::dispatch($challenge);

# Run queue worker to process
php artisan queue:work --stop-when-empty
```

### Test Volunteer Matching
```bash
php artisan tinker

$challenge = Challenge::find(2); // AI Chatbot
\App\Jobs\MatchVolunteersToTasks::dispatch($challenge);
```

### Test Team Formation
```bash
php artisan tinker

$challenge = Challenge::where('challenge_type', 'team_execution')->first();
\App\Jobs\FormTeamsForChallenge::dispatch($challenge);
```

---

## Verification Checklist

After seeding, verify:

### Database Counts
```sql
SELECT 'Users' as table_name, COUNT(*) as count FROM users
UNION ALL SELECT 'Companies', COUNT(*) FROM companies
UNION ALL SELECT 'Volunteers', COUNT(*) FROM volunteers
UNION ALL SELECT 'Challenges', COUNT(*) FROM challenges
UNION ALL SELECT 'Tasks', COUNT(*) FROM tasks
UNION ALL SELECT 'TaskAssignments', COUNT(*) FROM task_assignments
UNION ALL SELECT 'Teams', COUNT(*) FROM teams
UNION ALL SELECT 'TeamMembers', COUNT(*) FROM team_members
UNION ALL SELECT 'WorkSubmissions', COUNT(*) FROM work_submissions
UNION ALL SELECT 'Ideas', COUNT(*) FROM ideas
UNION ALL SELECT 'Comments', COUNT(*) FROM comments;
```

**Expected Counts:**
- Users: 12 (4 companies + 8 volunteers)
- Companies: 4
- Volunteers: 8
- Challenges: 7 (various states)
- Tasks: ~15-20
- Task Assignments: ~7-10
- Teams: 2
- Team Members: ~6
- Work Submissions: 1-2
- Ideas: 2-3
- Comments: 2-3

### Functional Tests
- [ ] All companies can login
- [ ] All volunteers can login
- [ ] Company dashboard loads
- [ ] Volunteer dashboard loads
- [ ] Challenges display correctly
- [ ] Tasks show proper statuses
- [ ] Team details page loads
- [ ] Work submissions visible
- [ ] Community ideas display
- [ ] NDAs are signed

---

## Troubleshooting

### Issue: "Table not found" error
**Solution**: Run migrations first
```bash
php artisan migrate:fresh
php artisan db:seed
```

### Issue: "Queue jobs not processing"
**Solution**: Start queue worker
```bash
php artisan queue:work
```

### Issue: "AI analysis fails"
**Solution**: Check OpenAI API key in `.env`
```
OPENAI_API_KEY=your-key-here
```

### Issue: "Images/CVs not found"
**Solution**: CV paths are placeholders. For full testing, manually upload CVs through UI

---

## Advanced Usage

### Customize Seeder

Edit `database/seeders/FullCycleTestSeeder.php` to:
- Add more users
- Create additional challenges
- Modify skill sets
- Adjust task complexity

### Run Specific Sections

```php
// In tinker or custom seeder
$seeder = new FullCycleTestSeeder();
$companies = $seeder->createCompanies();
$volunteers = $seeder->createVolunteers();
// ... etc
```

### Reset and Reseed

```bash
# Complete reset
php artisan migrate:fresh --seed

# Or just clear data and reseed
php artisan db:seed --class=FullCycleTestSeeder
# Answer 'yes' when prompted
```

---

## Integration with CI/CD

Add to your testing pipeline:

```yaml
# .github/workflows/test.yml
- name: Seed test database
  run: |
    php artisan migrate:fresh
    php artisan db:seed --class=FullCycleTestSeeder

- name: Run feature tests
  run: php artisan test
```

---

## Support

For issues or questions:
1. Check `PLATFORM_TEST_REPORT.md` for platform status
2. Review Laravel logs: `storage/logs/laravel.log`
3. Verify queue jobs: `php artisan queue:failed`

---

**Last Updated**: 2025-12-20
**Seeder Version**: 1.0
**Compatible With**: Mindova Platform v1.0

