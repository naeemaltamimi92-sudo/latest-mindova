# Challenge Processing Workflow - Database Storage Architecture

## Overview
This document explains how each stage of challenge processing is stored in separate database tables, ensuring complete tracking and audit trails.

---

## 📊 Complete Workflow with Database Storage

### **Stage 1: Challenge Creation & Submission**
**Job/Controller**: `ChallengeController@store`
**Database Table**: `challenges`

```php
// What gets stored:
challenges {
    id
    company_id
    title
    original_description  // Original challenge as submitted by company
    field
    status = 'submitted'  // Initial status
    challenge_type = NULL // Not yet determined
    complexity_level = NULL  // Not yet scored
    score = NULL  // Not yet scored
    created_at
}
```

**Flow**: Company submits challenge → Stored in `challenges` table → Triggers AI analysis

---

### **Stage 2: AI Brief Analysis**
**Job**: `AnalyzeChallengeBrief`
**Service**: `ChallengeBriefService`
**Database Table**: `challenge_analyses` (stage = 'brief')

```php
// What gets stored:
challenge_analyses {
    id
    challenge_id
    stage = 'brief'  // Identifies this as Stage 2
    objectives  // AI-extracted objectives
    constraints  // AI-identified constraints
    success_criteria  // AI-defined success criteria
    confidence_score  // AI confidence (0-100)
    validation_status  // 'passed' or 'needs_review'
    requires_human_review  // Boolean
    validation_errors  // Any concerns
    created_at
}

// Challenge table gets updated:
challenges {
    refined_brief  // AI-improved challenge description
    status = 'analyzing'
}
```

**Flow**: AI analyzes challenge brief → Stores analysis in `challenge_analyses` → Triggers complexity evaluation

---

### **Stage 3: Complexity Evaluation & Scoring**
**Job**: `EvaluateChallengeComplexity`
**Service**: `ComplexityEvaluationService`
**Database Table**: `challenge_analyses` (stage = 'complexity')

```php
// What gets stored:
challenge_analyses {
    id
    challenge_id
    stage = 'complexity'  // Identifies this as Stage 3
    objectives = NULL
    constraints = NULL
    success_criteria = NULL
    confidence_score  // AI confidence in complexity evaluation
    validation_status  // 'passed' or 'needs_review'
    ai_reasoning  // JSON with complexity evaluation details
    created_at
}

// Challenge table gets updated with SCORE:
challenges {
    complexity_level  // 1-10 complexity score
    score  // 1-10 (same as complexity_level)
    challenge_type  // 'team_execution' if score >= 3, 'community_discussion' if score 1-2
}
```

**Decision Point**:
- **Score 3-10**: Proceed to Task Decomposition (Stage 4)
- **Score 1-2**: Move to community discussion (no task decomposition)

---

### **Stage 4: Task Decomposition** (Only for scores 3-10)
**Job**: `DecomposeChallengeTasks`
**Service**: `TaskDecompositionService`
**Database Tables**:
- `challenge_analyses` (stage = 'decomposition')
- `workstreams`
- `tasks`

```php
// 1. Decomposition Analysis stored:
challenge_analyses {
    id
    challenge_id
    stage = 'decomposition'  // Identifies this as Stage 4
    confidence_score  // AI confidence in decomposition
    validation_status  // 'passed' or 'needs_review'
    requires_human_review  // Boolean
    validation_errors  // Any concerns or notes
    created_at
}

// 2. Workstreams created:
workstreams {
    id
    challenge_id
    title  // Workstream name (e.g., "Backend Development")
    description
    objectives  // JSON array of workstream objectives
    dependencies  // JSON array of dependent workstreams
    status = 'pending'
    order  // Execution order
    created_at
}

// 3. Tasks created (2-8 tasks per workstream):
tasks {
    id
    challenge_id
    workstream_id
    title  // Task name
    description  // Detailed task description
    required_skills  // JSON array of required skills
    required_experience_level  // 'Junior', 'Mid', 'Expert', 'Manager'
    expected_output  // What deliverable is expected
    acceptance_criteria  // JSON array of success criteria
    estimated_hours  // Hours needed (4-40)
    complexity_score  // Task complexity (3-10, never > 10)
    priority  // 'low', 'medium', 'high', 'critical'
    status = 'pending'
    order  // Task order within workstream
    created_at
}

// Challenge status updated:
challenges {
    status = 'active'  // Challenge is now ready for volunteers
}
```

**Task Complexity Rules**:
- Task complexity_score is **between 3-10**
- Task complexity **never exceeds 10**
- Tasks are distributed based on challenge complexity:
  - Challenge score 3-4: Tasks 3-5
  - Challenge score 5-6: Tasks 4-6
  - Challenge score 7-8: Tasks 5-8
  - Challenge score 9-10: Tasks 7-10

**Flow**: AI decomposes challenge → Stores in 3 tables → Triggers volunteer matching

---

### **Stage 5: Volunteer Matching & Invitations**
**Job**: `MatchVolunteersToTasks`
**Service**: `VolunteerMatchingService`
**Database Table**: `task_assignments`

```php
// What gets stored (for EACH matched volunteer):
task_assignments {
    id
    task_id
    volunteer_id
    match_score  // AI match score (0-100)
    match_reasoning  // JSON with AI reasoning:
    /* {
        "reasoning": "Why this volunteer was matched",
        "strengths": ["Strength 1", "Strength 2"],
        "gaps": ["Gap 1", "Gap 2"],
        "skill_matches": ["Matched skill 1", "Matched skill 2"]
    } */
    invitation_status = 'invited'  // Initial status
    invited_at  // Timestamp when invitation was sent
    created_at
}

// Task status updated:
tasks {
    status = 'matching'  // Task has invitations sent
}

// Notifications sent:
notifications {
    id
    user_id  // Volunteer's user_id
    type = 'task_assignment'
    data  // JSON with task details
    read_at = NULL
    created_at
}
```

**Flow**: AI matches volunteers → Creates `task_assignments` → Sends notifications → Triggers team formation

---

## 📋 Complete Database Tables Summary

### Data Storage by Stage:

| Stage | Job/Service | Primary Table | Secondary Tables | What Gets Stored |
|-------|------------|---------------|------------------|------------------|
| **1. Creation** | ChallengeController | `challenges` | - | Original challenge data |
| **2. Brief Analysis** | AnalyzeChallengeBrief | `challenge_analyses` | `challenges` (update) | AI-refined brief, objectives, constraints |
| **3. Complexity Scoring** | EvaluateChallengeComplexity | `challenge_analyses` | `challenges` (update) | Complexity score (1-10), challenge type |
| **4. Task Decomposition** | DecomposeChallengeTasks | `challenge_analyses` | `workstreams`, `tasks` | Workstreams, tasks with complexity 3-10 |
| **5. Volunteer Matching** | MatchVolunteersToTasks | `task_assignments` | `notifications`, `tasks` (update) | Match scores, invitations |

---

## 🔄 Workflow State Transitions

```
Challenge Submitted (status='submitted')
    ↓
AI Brief Analysis (status='analyzing')
    ↓ [stores in challenge_analyses stage='brief']
Complexity Evaluation
    ↓ [stores in challenge_analyses stage='complexity']
    ├─→ Score 1-2: Community Discussion (status='active', type='community_discussion')
    └─→ Score 3-10: Task Decomposition
            ↓ [stores in challenge_analyses stage='decomposition']
        Workstreams & Tasks Created
            ↓ [stores in workstreams and tasks tables]
        Challenge Active (status='active')
            ↓
        Volunteer Matching
            ↓ [stores in task_assignments table]
        Invitations Sent
            ↓
        Teams Formed
```

---

## 📊 Task Complexity Distribution

Tasks are assigned complexity scores based on the challenge's overall complexity:

```
Challenge Score  →  Task Complexity Range
─────────────────────────────────────────
      3-4       →       3-5
      5-6       →       4-6
      7-8       →       5-8
      9-10      →       7-10
```

**Rules**:
- Task complexity_score is **always between 3-10**
- Task complexity **never exceeds 10**
- Distribution ensures realistic task complexity relative to challenge

---

## 🎯 Key Design Principles

1. **Separation of Concerns**: Each stage stores data in its own table
2. **Audit Trail**: Every AI analysis is tracked in `challenge_analyses` with different `stage` values
3. **Traceability**: Each stage references previous analyses for context
4. **Scalability**: Jobs are queued and can be retried on failure
5. **Data Integrity**: Foreign keys maintain referential integrity
6. **Complexity Constraints**: Tasks never exceed complexity of 10

---

## 💾 Database Schema Relationships

```
companies (1) ──→ (N) challenges
                      │
                      ├──→ (N) challenge_analyses [stage: brief, complexity, decomposition]
                      ├──→ (N) workstreams
                      │         └──→ (N) tasks
                      │                   └──→ (N) task_assignments
                      │                             └──→ (1) volunteers
                      └──→ (N) teams
                                └──→ (N) team_members
                                          └──→ (1) volunteers
```

---

## ✅ Verification Queries

### Check Challenge Processing Stages:
```sql
-- See all analysis stages for a challenge
SELECT stage, confidence_score, validation_status, created_at
FROM challenge_analyses
WHERE challenge_id = 1
ORDER BY created_at;

-- Expected Result:
-- stage: 'brief', 'complexity', 'decomposition'
```

### Check Task Complexity Distribution:
```sql
-- Verify tasks have complexity 3-10
SELECT
    c.id as challenge_id,
    c.title as challenge_title,
    c.score as challenge_score,
    t.id as task_id,
    t.title as task_title,
    t.complexity_score
FROM challenges c
JOIN tasks t ON t.challenge_id = c.id
WHERE c.score >= 3
ORDER BY c.id, t.complexity_score;

-- Verify no tasks exceed complexity 10:
SELECT COUNT(*) FROM tasks WHERE complexity_score > 10;
-- Expected: 0
```

### Check Volunteer Invitations:
```sql
-- See all task assignments for a challenge
SELECT
    t.title as task_title,
    v.user_id,
    u.name as volunteer_name,
    ta.match_score,
    ta.invitation_status,
    ta.invited_at
FROM task_assignments ta
JOIN tasks t ON t.id = ta.task_id
JOIN volunteers v ON v.id = ta.volunteer_id
JOIN users u ON u.id = v.user_id
WHERE t.challenge_id = 1
ORDER BY ta.match_score DESC;
```

---

## 🚀 Testing the Complete Workflow

### 1. Create a Challenge:
```bash
# Login as company and submit a challenge through UI
# OR use tinker:
php artisan tinker
$challenge = Challenge::create([
    'company_id' => 1,
    'title' => 'Test Chemical Process Optimization',
    'original_description' => 'Optimize our chemical production...',
    'field' => 'Chemical Engineering',
    'status' => 'submitted',
]);

// Manually dispatch analysis:
App\Jobs\AnalyzeChallengeBrief::dispatch($challenge);
```

### 2. Run Queue Worker:
```bash
php artisan queue:work --stop-when-empty
```

### 3. Verify Each Stage:
```sql
-- Stage 1: Challenge exists
SELECT * FROM challenges WHERE id = X;

-- Stage 2: Brief analysis
SELECT * FROM challenge_analyses WHERE challenge_id = X AND stage = 'brief';

-- Stage 3: Complexity analysis
SELECT * FROM challenge_analyses WHERE challenge_id = X AND stage = 'complexity';

-- Stage 4: Decomposition & tasks
SELECT * FROM challenge_analyses WHERE challenge_id = X AND stage = 'decomposition';
SELECT * FROM workstreams WHERE challenge_id = X;
SELECT * FROM tasks WHERE challenge_id = X;

-- Stage 5: Assignments
SELECT * FROM task_assignments WHERE task_id IN (SELECT id FROM tasks WHERE challenge_id = X);
```

---

**Last Updated**: 2025-12-20
**Version**: 1.0
**Status**: Production Ready ✅
