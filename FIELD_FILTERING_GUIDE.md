# Field-Based Filtering Guide

## Overview
The Mindova platform now filters all community challenges and task invitations based on the volunteer's field of expertise. Volunteers only see content relevant to their education and professional domain.

---

## Test Accounts & What They See

### 1. Chemical Engineering Volunteer
**Login:** `ahmed@mindova.test` / `password`
**Field:** Chemical Engineering

**What Ahmed Sees:**
- ✅ "Optimizing Chemical Reactor Efficiency" (Chemical Engineering, Level 2)
- ✅ "Sustainable Chemical Process Design" (Chemical Engineering, Level 1)
- ❌ Healthcare challenges (Hidden)
- ❌ Technology challenges (Hidden)

**Dashboard Community Section:** Only Chemical Engineering challenges
**Community Page:** Only Chemical Engineering challenges
**Task Invitations:** Only Chemical Engineering tasks

---

### 2. Healthcare Volunteer
**Login:** `sarah@mindova.test` / `password`
**Field:** Healthcare

**What Sarah Sees:**
- ✅ "Exploring AI Ethics in Healthcare" (Healthcare, Level 2)
- ✅ "Defining Future of Telemedicine" (Healthcare, Level 1)
- ❌ Chemical Engineering challenges (Hidden)
- ❌ Technology challenges (Hidden)

**Dashboard Community Section:** Only Healthcare challenges
**Community Page:** Only Healthcare challenges
**Task Invitations:** Only Healthcare tasks

---

### 3. Technology Volunteers
**Login:** `john@mindova.test` / `password` OR `mike@mindova.test` / `password`
**Field:** Technology

**What They See:**
- ✅ "Future of Web Development Frameworks" (Technology, Level 1)
- ❌ Chemical Engineering challenges (Hidden)
- ❌ Healthcare challenges (Hidden)

**Dashboard Community Section:** Only Technology challenges
**Community Page:** Only Technology challenges
**Task Invitations:** Only Technology tasks

---

## How to Test

### Step 1: Start the Application
```bash
start.bat
# Choose option 5 to start both server and queue worker
```

### Step 2: Test Chemical Engineer
1. Go to http://localhost:8000/login
2. Login as: `ahmed@mindova.test` / `password`
3. View Dashboard - Community section shows only Chemical challenges
4. Navigate to Community page - Only Chemical challenges appear
5. Try to access a Healthcare challenge URL directly - You'll be redirected

### Step 3: Test Healthcare Professional
1. Logout and login as: `sarah@mindova.test` / `password`
2. View Dashboard - Community section shows only Healthcare challenges
3. Navigate to Community page - Only Healthcare challenges appear
4. Try to access a Chemical challenge URL - You'll be redirected

### Step 4: Test Technology Professional
1. Logout and login as: `john@mindova.test` / `password`
2. View Dashboard - Community section shows only Technology challenges
3. Navigate to Community page - Only Technology challenges appear
4. Cannot see Healthcare or Chemical challenges

---

## Challenge Distribution

### Community Challenges (Level 1-2)

| Challenge Title | Field | Level | Visible To |
|----------------|-------|-------|------------|
| Sustainable Chemical Process Design | Chemical Engineering | 1 | Ahmed only |
| Optimizing Chemical Reactor Efficiency | Chemical Engineering | 2 | Ahmed only |
| Defining Future of Telemedicine | Healthcare | 1 | Sarah only |
| Exploring AI Ethics in Healthcare | Healthcare | 2 | Sarah only |
| Future of Web Development Frameworks | Technology | 1 | John, Mike only |

---

## Security Features

### 1. View Protection
Volunteers cannot view challenges outside their field, even with direct URL access.

**Example:**
- Ahmed tries to access Healthcare challenge → Redirected with error message
- Sarah tries to access Chemical challenge → Redirected with error message

### 2. Comment Protection
Volunteers cannot comment on challenges outside their field.

**Example:**
- Ahmed attempts to comment on Healthcare challenge → Error: "You can only comment on challenges in your field: Chemical Engineering"

### 3. Task Invitation Filtering
The AI matching service only invites volunteers whose field matches the challenge field.

**Example:**
- Healthcare task created → Only Sarah gets invited
- Chemical task created → Only Ahmed gets invited
- Technology task created → Only John and Mike get invited

---

## Field Values in System

Current fields configured:
- Chemical Engineering
- Healthcare
- Technology

Additional fields can be added by:
1. Setting volunteer's field during profile setup
2. Setting challenge's field when creating challenges

---

## Database-Level Filtering

The filtering happens at multiple levels:

### Level 1: Database Query
```php
->where('field', $volunteer->field)
```
Only retrieves matching challenges from database.

### Level 2: Controller Validation
Blocks access to non-matching challenges even with direct URLs.

### Level 3: AI Matching Service
Only matches volunteers to tasks in their field.

---

## Expected Behavior

### Volunteer Dashboard
- Community section shows 0-3 challenges in volunteer's field
- No challenges shown if volunteer has no field set
- Empty state displayed if no matching challenges exist

### Community Page
- Lists only challenges matching volunteer's field
- Pagination works within filtered results
- Search/filter applies only to volunteer's field challenges

### Task Invitations
- Volunteer receives notifications only for tasks in their field
- Invitation list shows only matching field tasks
- Cannot accept tasks outside their field

---

## Testing Checklist

- [ ] Chemical engineer sees only Chemical challenges
- [ ] Healthcare professional sees only Healthcare challenges
- [ ] Technology professional sees only Technology challenges
- [ ] Cannot access other field challenges via direct URL
- [ ] Cannot comment on other field challenges
- [ ] Task invitations respect field matching
- [ ] Dashboard shows correct filtered challenges
- [ ] Community page shows correct filtered challenges
- [ ] Empty state shows when no matching challenges exist

---

## Support

If volunteers see challenges from other fields, check:
1. Volunteer profile has correct field set
2. Challenge has correct field set
3. Cache has been cleared (`php artisan cache:clear`)
4. Database queries are using field filtering

---

**Last Updated:** 2025-12-19
**Version:** 1.0
