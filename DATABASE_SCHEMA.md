# Database Schema Design - Mindova Platform

## Overview
This document defines the complete database structure for the collaboration platform that uses OpenAI to convert unstructured problems into structured, executable plans.

---

## Core Entities

### 1. users
**Purpose:** Base authentication and user management (already exists - will be extended)

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| name | string | Full name |
| email | string | Unique email |
| password | string | Hashed password |
| user_type | enum | 'volunteer', 'company', 'admin' |
| linkedin_id | string | LinkedIn OAuth ID (nullable) |
| linkedin_profile_url | string | LinkedIn profile link (nullable) |
| email_verified_at | timestamp | Email verification |
| is_active | boolean | Account status |
| created_at | timestamp | |
| updated_at | timestamp | |

---

### 2. volunteers
**Purpose:** Extended profile for volunteers with AI-analyzed data

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| user_id | bigint | Foreign key to users |
| cv_file_path | string | Path to uploaded CV |
| availability_hours_per_week | integer | Weekly availability |
| experience_level | enum | 'Junior', 'Mid', 'Expert', 'Manager' (AI-determined) |
| bio | text | Professional summary (AI-extracted) |
| education | json | Education history (AI-extracted) |
| work_experience | json | Work history (AI-extracted) |
| professional_domains | json | Inferred domains (AI-extracted) |
| years_of_experience | decimal(4,1) | Total years (AI-calculated) |
| reputation_score | decimal(5,2) | Current reputation (0-100) |
| total_tasks_completed | integer | Count of completed tasks |
| total_hours_contributed | decimal(8,2) | Total contribution hours |
| ai_analysis_status | enum | 'pending', 'processing', 'completed', 'failed' |
| ai_analysis_confidence | decimal(5,2) | AI confidence score (0-100) |
| validation_status | enum | 'pending', 'passed', 'failed' |
| validation_errors | json | What failed validation |
| skills_normalized | boolean | Skills taxonomy normalization complete |
| ai_analyzed_at | timestamp | When CV was analyzed |
| created_at | timestamp | |
| updated_at | timestamp | |

---

### 3. volunteer_skills
**Purpose:** Skills extracted from CV by AI

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| volunteer_id | bigint | Foreign key to volunteers |
| skill_name | string | Skill/technology name |
| category | string | e.g., 'programming', 'design', 'management' |
| proficiency_level | enum | 'beginner', 'intermediate', 'advanced', 'expert' |
| years_of_experience | decimal(4,1) | AI-estimated years |
| source | enum | 'cv_analysis', 'manual' |
| created_at | timestamp | |
| updated_at | timestamp | |

**Indexes:** volunteer_id, skill_name

---

### 4. companies
**Purpose:** Company profiles that submit challenges

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| user_id | bigint | Foreign key to users |
| company_name | string | Official company name |
| industry | string | Industry sector |
| website | string | Company website (nullable) |
| description | text | Company description |
| logo_path | string | Logo file path (nullable) |
| total_challenges_submitted | integer | Count of challenges |
| created_at | timestamp | |
| updated_at | timestamp | |

---

### 5. challenges
**Purpose:** Problems submitted by companies

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| company_id | bigint | Foreign key to companies |
| title | string | Challenge title |
| original_description | text | Unstructured problem description |
| complexity_level | integer | 1-4 (AI-determined) |
| challenge_type | enum | 'community_discussion', 'team_execution' |
| status | enum | 'submitted', 'analyzing', 'active', 'in_progress', 'completed', 'archived' |
| deadline | date | Optional deadline |
| ai_analysis_status | enum | 'pending', 'processing', 'completed', 'failed' |
| ai_analyzed_at | timestamp | When challenge was analyzed |
| created_at | timestamp | |
| updated_at | timestamp | |

**Indexes:** company_id, status, complexity_level

---

### 6. challenge_analyses
**Purpose:** AI-generated analysis of challenges (asynchronous pipeline)

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| challenge_id | bigint | Foreign key to challenges |
| stage | enum | 'brief', 'complexity', 'decomposition' |
| summary | text | AI-generated summary |
| objectives | json | Identified objectives array |
| constraints | json | Identified constraints array |
| assumptions | json | Identified assumptions |
| stakeholders | json | Identified stakeholders |
| missing_information | json | What's missing array |
| success_criteria | json | What success looks like |
| complexity_level | integer | 1-4 |
| complexity_justification | text | Why this complexity level |
| risk_assessment | text | Risk evaluation |
| confidence_score | decimal(5,2) | AI confidence (0-100) |
| recommended_approach | text | AI recommendation |
| estimated_effort_hours | decimal(8,2) | AI estimate |
| validation_status | enum | 'pending', 'passed', 'failed', 'needs_review' |
| validation_errors | json | What failed validation |
| requires_human_review | boolean | Below confidence threshold |
| openai_model_used | string | e.g., 'gpt-4o' |
| tokens_used | integer | API usage tracking |
| created_at | timestamp | |
| updated_at | timestamp | |

---

### 7. workstreams
**Purpose:** Parallel tracks of work within a challenge

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| challenge_id | bigint | Foreign key to challenges |
| title | string | Workstream name |
| description | text | What this track accomplishes |
| order | integer | Sequence in challenge |
| status | enum | 'pending', 'active', 'completed' |
| created_at | timestamp | |
| updated_at | timestamp | |

**Indexes:** challenge_id

---

### 8. tasks
**Purpose:** Actionable tasks broken down from workstreams

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| workstream_id | bigint | Foreign key to workstreams |
| challenge_id | bigint | Foreign key to challenges (for easy querying) |
| title | string | Task title |
| description | text | What needs to be done |
| required_skills | json | Skills array (AI-determined) |
| required_experience_level | enum | 'Junior', 'Mid', 'Expert', 'Manager' |
| expected_output | text | Clear deliverable description |
| acceptance_criteria | json | How to know it's done right |
| estimated_hours | decimal(6,2) | Time estimate |
| priority | enum | 'low', 'medium', 'high', 'critical' |
| status | enum | 'pending', 'open', 'assigned', 'in_progress', 'submitted', 'under_review', 'completed', 'rejected' |
| order | integer | Sequence in workstream |
| dependencies | json | Task IDs that must complete first |
| validation_status | enum | 'pending', 'passed', 'failed', 'needs_regeneration' |
| quality_check_passed | boolean | Platform validation result |
| created_at | timestamp | |
| updated_at | timestamp | |

**Indexes:** workstream_id, challenge_id, status

---

### 9. task_assignments
**Purpose:** Matching volunteers to tasks

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| task_id | bigint | Foreign key to tasks |
| volunteer_id | bigint | Foreign key to volunteers |
| invitation_status | enum | 'invited', 'accepted', 'declined', 'auto_matched' |
| ai_match_score | decimal(5,2) | Match confidence (0-100) |
| ai_match_reasoning | text | Why this volunteer was matched |
| invited_at | timestamp | When invitation sent |
| responded_at | timestamp | When volunteer responded |
| started_at | timestamp | When work began |
| submitted_at | timestamp | When work submitted |
| completed_at | timestamp | When marked complete |
| actual_hours | decimal(6,2) | Actual time spent |
| created_at | timestamp | |
| updated_at | timestamp | |

**Indexes:** task_id, volunteer_id, invitation_status

---

### 10. ideas
**Purpose:** Community ideas for low-complexity challenges

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| challenge_id | bigint | Foreign key to challenges |
| volunteer_id | bigint | Foreign key to volunteers |
| content | text | The idea/suggestion |
| ai_quality_score | decimal(5,2) | AI score (0-100) |
| ai_relevance_score | decimal(5,2) | Relevance (0-100) |
| ai_feasibility_score | decimal(5,2) | Feasibility (0-100) |
| ai_feedback | text | AI reasoning |
| community_votes_up | integer | Upvotes count |
| community_votes_down | integer | Downvotes count |
| total_score | decimal(8,2) | Combined AI + community score |
| status | enum | 'pending_review', 'approved', 'implemented', 'rejected' |
| created_at | timestamp | |
| updated_at | timestamp | |

**Indexes:** challenge_id, volunteer_id, total_score DESC

---

### 11. idea_votes
**Purpose:** Track community voting on ideas

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| idea_id | bigint | Foreign key to ideas |
| volunteer_id | bigint | Foreign key to volunteers |
| vote_type | enum | 'up', 'down' |
| created_at | timestamp | |
| updated_at | timestamp | |

**Unique Index:** (idea_id, volunteer_id)

---

### 12. work_submissions
**Purpose:** Submitted deliverables for tasks

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| task_assignment_id | bigint | Foreign key to task_assignments |
| description | text | What was delivered |
| deliverable_url | string | Link to work (GitHub, etc.) |
| attachments | json | File paths array |
| status | enum | 'submitted', 'under_review', 'approved', 'rejected', 'revision_requested' |
| submitted_at | timestamp | Submission time |
| reviewed_at | timestamp | Review time |
| created_at | timestamp | |
| updated_at | timestamp | |

---

### 13. reviews
**Purpose:** Reviews of submitted work

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| work_submission_id | bigint | Foreign key to work_submissions |
| reviewer_id | bigint | Foreign key to users (company or admin) |
| rating | integer | 1-5 stars |
| quality_score | integer | 1-10 for quality |
| timeliness_score | integer | 1-10 for timeliness |
| communication_score | integer | 1-10 for communication |
| feedback | text | Written feedback |
| decision | enum | 'approved', 'rejected', 'revision_requested' |
| revision_notes | text | What needs to change |
| created_at | timestamp | |
| updated_at | timestamp | |

---

### 14. reputation_history
**Purpose:** Audit trail of reputation changes

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| volunteer_id | bigint | Foreign key to volunteers |
| change_amount | decimal(5,2) | Positive or negative |
| new_total | decimal(5,2) | Reputation after change |
| reason | string | Why reputation changed |
| related_type | string | Polymorphic type |
| related_id | bigint | Polymorphic ID |
| created_at | timestamp | |

**Indexes:** volunteer_id, created_at DESC

---

### 15. openai_requests
**Purpose:** Audit trail of all AI operations

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| request_type | enum | 'cv_analysis', 'challenge_analysis', 'task_generation', 'idea_scoring', 'volunteer_matching' |
| model | string | e.g., 'gpt-4o', 'gpt-4o-mini' |
| prompt | text | Input sent to OpenAI |
| response | text | Raw response received |
| tokens_prompt | integer | Prompt tokens used |
| tokens_completion | integer | Completion tokens used |
| tokens_total | integer | Total tokens |
| cost_usd | decimal(10,6) | Estimated cost |
| duration_ms | integer | Response time |
| status | enum | 'success', 'failed', 'rate_limited' |
| error_message | text | If failed |
| related_type | string | Polymorphic type |
| related_id | bigint | Polymorphic ID |
| created_at | timestamp | |

**Indexes:** request_type, created_at DESC, status

---

### 16. notifications
**Purpose:** User notifications

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| user_id | bigint | Foreign key to users |
| type | string | Notification type |
| title | string | Notification title |
| message | text | Notification content |
| action_url | string | Link to relevant page |
| is_read | boolean | Read status |
| read_at | timestamp | When marked read |
| created_at | timestamp | |
| updated_at | timestamp | |

**Indexes:** user_id, is_read, created_at DESC

---

## Relationships Summary

```
users (1) -> (1) volunteers
users (1) -> (1) companies

volunteers (1) -> (*) volunteer_skills
volunteers (1) -> (*) task_assignments
volunteers (1) -> (*) ideas
volunteers (1) -> (*) idea_votes
volunteers (1) -> (*) reputation_history

companies (1) -> (*) challenges

challenges (1) -> (*) challenge_analyses (analysis pipeline stages)
challenges (1) -> (*) workstreams
challenges (1) -> (*) tasks (denormalized for easy querying)
challenges (1) -> (*) ideas

workstreams (1) -> (*) tasks

tasks (1) -> (*) task_assignments

task_assignments (1) -> (*) work_submissions

work_submissions (1) -> (*) reviews

ideas (1) -> (*) idea_votes

Polymorphic relationships:
- openai_requests (related_type, related_id)
- reputation_history (related_type, related_id)
```

---

## Key Design Decisions

1. **User Type Hierarchy:** Single `users` table with `user_type` field, extended by `volunteers` and `companies` tables
2. **AI Audit Trail:** All OpenAI requests logged in `openai_requests` for accountability
3. **Polymorphic Relations:** `openai_requests` and `reputation_history` use polymorphic relationships
4. **JSON Storage:** Skills, goals, constraints stored as JSON for flexibility
5. **Status Tracking:** Every entity has comprehensive status fields for workflow management
6. **Scoring System:** Separate AI and community scoring for ideas
7. **Time Tracking:** Estimated vs actual hours for continuous improvement
8. **Soft Deletes:** Can be added to all tables for data retention

---

## Indexes Strategy

- Primary keys on all tables (automatic)
- Foreign keys indexed for join performance
- Status fields indexed for filtering
- Composite unique indexes where needed (e.g., idea_votes)
- Timestamp indexes for audit trails

---

## Next Steps

1. Create Laravel migrations for all tables
2. Create Eloquent models with relationships
3. Implement database seeders for testing
4. Set up model factories
