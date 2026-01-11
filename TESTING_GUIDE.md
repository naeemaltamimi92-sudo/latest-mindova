# Testing Guide - New Features

This guide walks you through testing all the new features that were implemented.

---

## Prerequisites

Before testing, ensure:
- ✅ Migrations have been run
- ✅ Queue worker is running: `php artisan queue:work`
- ✅ OpenAI API key is configured in `.env`
- ✅ Email is configured (Mailtrap for testing)

---

## Test 1: Email/Password Login & Registration

### Test Registration

1. **Go to Registration Page**
   - Navigate to: `http://localhost:8000/register`

2. **Fill Registration Form**
   - Select: **Volunteer** or **Company**
   - Full Name: `Test User`
   - Email: `test@example.com`
   - Password: `password123`
   - Confirm Password: `password123`
   - Check: Terms agreement
   - Click: **Create Account**

3. **Complete Profile**
   - Should redirect to profile completion
   - Fill in required fields
   - Upload CV (if volunteer)
   - Submit

4. **Expected Result**
   - ✅ Account created
   - ✅ Logged in automatically
   - ✅ Redirected to dashboard

### Test Login

1. **Logout**
   - Click profile dropdown → Logout

2. **Login with Email/Password**
   - Navigate to: `http://localhost:8000/login`
   - Email: `test@example.com`
   - Password: `password123`
   - Optional: Check "Remember me"
   - Click: **Sign In**

3. **Expected Result**
   - ✅ Successfully logged in
   - ✅ Redirected to dashboard
   - ✅ Session persists

### Test Password Reset

1. **Request Reset**
   - Go to login page
   - Click: **Forgot password?**
   - Enter email: `test@example.com`
   - Click: **Send Password Reset Link**

2. **Check Email**
   - Open Mailtrap inbox
   - Find password reset email
   - Click reset link

3. **Reset Password**
   - Enter new password
   - Confirm new password
   - Click: **Reset Password**

4. **Login with New Password**
   - Try logging in with new password

5. **Expected Result**
   - ✅ Reset link sent
   - ✅ Email received in Mailtrap
   - ✅ Password updated successfully
   - ✅ Can login with new password

---

## Test 2: Volunteer Dashboard

### Setup

Create a volunteer account or login as existing volunteer.

### Test Dashboard Sections

1. **Stats Overview**
   - Check displays:
     - Team Invitations count
     - Task Assignments count
     - Completed Tasks count
     - Reputation Score

2. **Community Education Section**
   - Should show level 1-2 challenges
   - Matched to volunteer's field
   - Display:
     - Challenge title
     - Level badge (1 or 2)
     - Field badge
     - Comment count
     - "Join Discussion" button

3. **Team Invitations** (if any)
   - Shows pending invitations
   - Display:
     - Team name
     - Match score
     - Role description
     - Accept/Decline buttons

4. **Pending Task Assignments** (if any)
   - Shows tasks assigned to volunteer
   - Display:
     - Task title
     - Match percentage
     - Complexity score
     - Estimated hours
     - Accept/Decline buttons

5. **Active Tasks** (if any)
   - Shows accepted/in-progress tasks
   - Display:
     - Task title
     - Status badge
     - "View Task" button

### Expected Results

- ✅ All sections display correctly
- ✅ No "Browse Available Tasks" link
- ✅ Community shows only level 1-2 challenges
- ✅ Navigation shows: Dashboard, My Teams, Community

---

## Test 3: Community Engagement

### Test Commenting

1. **Navigate to Community**
   - Click: **Community** in navigation
   - Or click "Join Discussion" on dashboard

2. **View Challenge**
   - Click on a level 1-2 challenge
   - Should see:
     - Challenge details
     - Existing comments (if any)
     - Comment form

3. **Post Comment**
   - Enter meaningful comment (min 10 chars):
     ```
     This challenge could be approached by first analyzing the user requirements,
     then breaking it down into smaller components. I suggest starting with
     a prototype to validate the concept before full implementation.
     ```
   - Click: **Post Comment**

4. **Wait for AI Analysis**
   - Comment appears with "Analyzing..." status
   - Queue worker processes job
   - After ~10-30 seconds, AI score appears (0-10)

5. **Check Reputation**
   - If score ≥7, reputation points awarded:
     - Score 9-10: +10 points
     - Score 7-8: +5 points
   - Check dashboard for updated reputation

### Expected Results

- ✅ Comment posted successfully
- ✅ AI analysis completes
- ✅ Score displayed (0-10)
- ✅ High-quality comments earn reputation
- ✅ Company owner notified (if score ≥7)

---

## Test 4: Solution Submission

### Setup

You need:
- Volunteer account
- Assigned task (status: accepted or in_progress)

### Test Submission Flow

1. **Navigate to Task**
   - From dashboard → Click "View Task" on active task
   - Or accept a pending assignment first

2. **Open Submission Form**
   - Click: **Submit Solution** button
   - Modal opens with form

3. **Fill Submission Form**
   - **Description** (required):
     ```
     I implemented the user authentication system using JWT tokens.
     The solution includes:
     - User registration and login endpoints
     - Password hashing with bcrypt
     - Token-based authentication middleware
     - Password reset functionality
     - Input validation and error handling

     All tests are passing and the code follows best practices.
     ```

   - **Deliverable URL** (optional):
     ```
     https://github.com/yourusername/project-repo
     ```

   - **Attachments** (optional):
     - Upload a test PDF or screenshot

   - **Hours Worked** (required):
     ```
     8.5
     ```

4. **Submit**
   - Click: **Submit Solution**
   - Should see success message

5. **Wait for AI Analysis**
   - Queue worker processes job (~30-60 seconds)
   - AI analyzes:
     - Quality score (0-100)
     - Whether it solves the task
     - Strengths, weaknesses, suggestions

6. **Check Results**
   - Refresh task page
   - Status should be updated:
     - "Approved" if quality ≥60 and solves_task=true
     - "Revision Requested" if below threshold

7. **Check Reputation**
   - Dashboard shows updated reputation:
     - Excellent (90-100): +20 points
     - Good (75-89): +15 points
     - Acceptable (60-74): +10 points
     - Minimal (<60): +5 points
     - Partial: +1-3 points

### Expected Results

- ✅ Solution submitted
- ✅ Files uploaded to storage
- ✅ AI analysis completes
- ✅ Quality score assigned (0-100)
- ✅ Solves task determination (true/false)
- ✅ Reputation points awarded
- ✅ Status updated correctly
- ✅ Company notified

---

## Test 5: Challenge Completion

### Setup

You need:
- Company account
- Challenge with multiple tasks
- Volunteers assigned to all tasks

### Test Aggregation Flow

1. **Create Test Challenge** (as company)
   - Title: "Test Challenge for Aggregation"
   - Description: Detailed challenge description
   - Submit and wait for AI decomposition

2. **Assign Tasks** (wait for automatic matching)
   - System matches volunteers to tasks
   - Volunteers receive notifications

3. **Submit Solutions** (as volunteers)
   - Each volunteer submits solution for their task
   - All solutions must pass AI analysis (quality ≥60)

4. **Wait for Aggregation**
   - When last task solution is approved
   - `AggregateChallengeCompletion` job runs
   - Takes ~30-60 seconds

5. **Check Company Notification**
   - Company receives notification:
     - "Challenge Completed!"
     - Shows task count
     - Shows average quality score

6. **View Aggregated Solutions** (as company)
   - Go to challenge details
   - Should see all solutions aggregated
   - Each solution includes:
     - Task details
     - Volunteer name
     - Solution description
     - Deliverable links
     - Quality score
     - Hours worked

### Expected Results

- ✅ All solutions collected
- ✅ Average quality calculated
- ✅ Challenge status → "completed"
- ✅ Company notified
- ✅ Aggregated package created

---

## Test 6: Queue Jobs

### Monitor Queue Processing

1. **Start Queue Worker** (in separate terminal):
   ```bash
   php artisan queue:work --verbose
   ```

2. **Trigger Jobs**
   - Post a comment → `AnalyzeCommentQuality` job
   - Submit solution → `AnalyzeSolutionQuality` job
   - Complete challenge → `AggregateChallengeCompletion` job

3. **Watch Terminal**
   - See jobs being processed
   - Check for errors
   - Monitor execution time

### Check Failed Jobs

```bash
# View failed jobs
php artisan queue:failed

# If any failures, check details
# Fix issue and retry
php artisan queue:retry all
```

### Expected Results

- ✅ Jobs process successfully
- ✅ No failed jobs
- ✅ Reasonable execution times (<60s each)

---

## Test 7: Notifications

### Test Notification System

1. **As Volunteer**
   - Accept task → Company gets notification
   - Submit solution → Company gets notification
   - Post high-quality comment → Company gets notification
   - Complete task → Company gets notification

2. **As Company**
   - Challenge analyzed → Company gets notification
   - All tasks complete → Company gets notification

3. **Check Notification Display**
   - Click bell icon in navbar
   - See notification dropdown
   - Unread count badge
   - Click notification → marks as read
   - Click "Mark all read"

### Expected Results

- ✅ Notifications created
- ✅ Unread count accurate
- ✅ Dropdown displays correctly
- ✅ Mark as read works
- ✅ Action URLs work

---

## Test 8: File Uploads

### Test Solution Attachments

1. **Upload Files**
   - Submit solution with attachments:
     - PDF file (~1MB)
     - Image file (~500KB)
     - Multiple files

2. **Check Storage**
   - Files should be in: `storage/app/public/submissions/`
   - Accessible via: `http://localhost:8000/storage/submissions/filename`

3. **Verify Database**
   - Check `work_submissions.attachments` field
   - Should contain JSON array of file paths

### Expected Results

- ✅ Files upload successfully
- ✅ Stored in correct location
- ✅ Paths saved in database
- ✅ Files accessible via URL

---

## Test 9: Rate Limiting

### Test Login Rate Limit

1. **Attempt Multiple Failed Logins**
   - Try logging in with wrong password
   - Repeat 5 times quickly

2. **Expected Result**
   - After 5 attempts, should see:
     - "Too many login attempts. Please try again in X seconds."
   - Wait 60 seconds
   - Try again → Should work

---

## Test 10: Edge Cases

### Test Empty States

1. **New Volunteer (no data)**
   - Dashboard should show empty states
   - No team invitations
   - No pending assignments
   - No active tasks
   - Reputation = 50 (default)

2. **No Community Challenges**
   - If no level 1-2 challenges exist
   - Section should be hidden

### Test Error Handling

1. **Submit Solution Without Queue Worker**
   - Stop queue worker
   - Submit solution
   - Should save submission
   - Status: "pending analysis"
   - Start queue worker → Processes automatically

2. **Invalid File Upload**
   - Try uploading file >10MB
   - Should see validation error

3. **OpenAI API Failure**
   - Temporarily set invalid API key
   - Try submitting solution
   - Job should fail and retry
   - Check failed jobs queue

---

## Performance Testing

### Load Test

1. **Multiple Simultaneous Submissions**
   - Create 5-10 volunteers
   - Submit solutions simultaneously
   - Check all process correctly

2. **Database Performance**
   - With 100+ comments
   - With 50+ solutions
   - Check query performance

---

## Debugging Tips

### Check Logs

```bash
# Watch Laravel logs
tail -f storage/logs/laravel.log

# Watch queue worker output
php artisan queue:work --verbose
```

### Check Database

```sql
-- Check work submissions
SELECT * FROM work_submissions ORDER BY created_at DESC LIMIT 10;

-- Check notifications
SELECT * FROM notifications ORDER BY created_at DESC LIMIT 10;

-- Check reputation history
SELECT * FROM reputation_history ORDER BY created_at DESC LIMIT 10;

-- Check challenge status
SELECT id, title, status, average_solution_quality, completed_at
FROM challenges
WHERE status = 'completed';
```

### Clear Caches

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

---

## Test Checklist

Use this checklist to verify all features:

### Authentication ✅
- [ ] Email/password registration
- [ ] Email/password login
- [ ] Remember me
- [ ] Logout
- [ ] Forgot password
- [ ] Reset password
- [ ] LinkedIn OAuth (optional)
- [ ] Rate limiting

### Volunteer Dashboard ✅
- [ ] Stats display
- [ ] Community education section
- [ ] Team invitations
- [ ] Pending assignments
- [ ] Active tasks
- [ ] No "available tasks" browse

### Community ✅
- [ ] List level 1-2 challenges only
- [ ] Post comment
- [ ] AI scoring (0-10)
- [ ] Reputation points
- [ ] Vote on comments
- [ ] Company notifications

### Solution Submission ✅
- [ ] Form validation
- [ ] File uploads
- [ ] AI analysis (0-100)
- [ ] Solves task determination
- [ ] Reputation points
- [ ] Status updates
- [ ] Company notifications

### Challenge Completion ✅
- [ ] All solutions aggregated
- [ ] Average quality calculated
- [ ] Challenge marked complete
- [ ] Company notification

### Queue Jobs ✅
- [ ] Comment analysis
- [ ] Solution analysis
- [ ] Challenge aggregation
- [ ] Failed job handling
- [ ] Retry logic

### Notifications ✅
- [ ] Creation
- [ ] Display
- [ ] Unread count
- [ ] Mark as read
- [ ] Action URLs

---

## Success Criteria

All features are working correctly when:

✅ Registration and login work smoothly
✅ Volunteer dashboard shows correct sections
✅ Community comments get AI scored
✅ Solutions get AI analyzed and scored
✅ Reputation points awarded correctly
✅ Challenge aggregation completes
✅ Company receives final solution package
✅ Notifications delivered properly
✅ Queue jobs process without errors
✅ No PHP errors in logs
✅ All tests in checklist pass

---

## Reporting Issues

If you find bugs:

1. **Check logs first**:
   - `storage/logs/laravel.log`
   - Queue worker output
   - Browser console

2. **Document**:
   - Steps to reproduce
   - Expected vs actual result
   - Error messages
   - Screenshots

3. **Common fixes**:
   - Clear caches
   - Restart queue worker
   - Check .env configuration
   - Verify database migrations

---

**Happy Testing!** 🧪

For more details, see `IMPLEMENTATION_SUMMARY.md` and `SETUP_GUIDE.md`.
