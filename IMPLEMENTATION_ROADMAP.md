# Implementation Roadmap - Mindova Platform

## Project Overview
Build a domain-agnostic collaboration platform that uses OpenAI for cognitive operations while maintaining a deterministic, rule-based core. The platform converts unstructured problems into structured, executable plans.

**Tech Stack:**
- Laravel 12 (PHP 8.2+)
- SQLite (development) / PostgreSQL (production)
- OpenAI API (GPT-4o, GPT-4o-mini)
- Queue workers for async processing
- LinkedIn OAuth for authentication

---

## Phase 1: Foundation & Setup

### 1.1 Environment Setup
- [ ] Configure `.env` file with database and OpenAI credentials
- [ ] Update `config/ai.php` with OpenAI settings
- [ ] Configure queue connection for database
- [ ] Set up LinkedIn OAuth credentials

### 1.2 Install Dependencies
```bash
composer require openai-php/client
composer require laravel/socialite
composer require socialiteproviders/linkedin-openid
```

### 1.3 Database Schema Implementation
Create migrations in order:

**User Management:**
- [ ] Extend `users` migration (add `user_type`, `linkedin_id`, `linkedin_profile_url`, `is_active`)
- [ ] Create `companies` migration
- [ ] Create `volunteers` migration
- [ ] Create `volunteer_skills` migration

**Challenge System:**
- [ ] Create `challenges` migration
- [ ] Create `challenge_analyses` migration
- [ ] Create `workstreams` migration
- [ ] Create `tasks` migration

**Execution & Collaboration:**
- [ ] Create `task_assignments` migration
- [ ] Create `ideas` migration
- [ ] Create `idea_votes` migration
- [ ] Create `work_submissions` migration
- [ ] Create `reviews` migration

**Supporting Tables:**
- [ ] Create `reputation_history` migration
- [ ] Create `openai_requests` migration
- [ ] Create `notifications` migration

**Run migrations:**
```bash
php artisan migrate
```

### 1.4 Create Models with Relationships

**Core Models:**
- [ ] `User` (extend existing)
- [ ] `Volunteer` (with relationships to User, VolunteerSkills, TaskAssignments)
- [ ] `VolunteerSkill`
- [ ] `Company` (with relationships to User, Challenges)
- [ ] `Challenge` (with relationships to Company, Analyses, Workstreams, Tasks, Ideas)
- [ ] `ChallengeAnalysis`
- [ ] `Workstream` (with relationship to Tasks)
- [ ] `Task` (with relationships to Workstream, TaskAssignments)
- [ ] `TaskAssignment` (with relationships to Task, Volunteer, WorkSubmissions)
- [ ] `Idea` (with relationships to Challenge, Volunteer, Votes)
- [ ] `IdeaVote`
- [ ] `WorkSubmission` (with relationship to Reviews)
- [ ] `Review`
- [ ] `ReputationHistory`
- [ ] `OpenAIRequest`
- [ ] `Notification`

**Define all relationships, casts (JSON fields), and scopes**

### Deliverable:
- Database fully migrated
- All models created with relationships
- Seed data for testing (factories)

**Estimated Time:** Foundation work (Phase 1)

---

## Phase 2: Authentication & User Management

### 2.1 LinkedIn OAuth Integration
- [ ] Configure Socialite provider for LinkedIn
- [ ] Create `LinkedInAuthController`
  - `/auth/linkedin` - redirect to LinkedIn
  - `/auth/linkedin/callback` - handle callback
- [ ] Create user on successful auth
- [ ] Store LinkedIn profile data

### 2.2 User Registration Flow

**Volunteer Registration:**
- [ ] `POST /api/volunteers/register` - complete profile after OAuth
- [ ] `VolunteerRegistrationService`
  - Create volunteer record
  - Set initial reputation score (50.0)
  - Return volunteer profile

**Company Registration:**
- [ ] `POST /api/companies/register` - complete company profile
- [ ] `CompanyRegistrationService`
  - Create company record
  - Validate company information
  - Return company profile

### 2.3 Profile Management
- [ ] `GET /api/volunteers/profile` - get volunteer profile
- [ ] `PUT /api/volunteers/profile` - update availability, bio
- [ ] `GET /api/companies/profile` - get company profile
- [ ] `PUT /api/companies/profile` - update company info

### Deliverable:
- LinkedIn OAuth working
- Volunteers and companies can register
- Profile management endpoints

**Estimated Time:** Authentication implementation

---

## Phase 3: CV Analysis System (Volunteer Onboarding)

### 3.1 CV Upload
- [ ] `POST /api/volunteers/cv/upload`
- [ ] `CVUploadController`
  - Validate file (PDF, DOCX, max 5MB)
  - Store file securely
  - Dispatch job for analysis

### 3.2 OpenAI Integration Base
- [ ] Create `app/Services/AI/OpenAIService.php` (base class)
  - `chat()` method with JSON schema support
  - Logging to `openai_requests` table
  - Cost calculation
  - Error handling

### 3.3 CV Analyzer Service
- [ ] Create `app/Services/AI/CVAnalyzerService.php`
  - `analyze()` method
  - Build prompt for CV extraction
  - Define strict JSON schema
  - Return `CVAnalysisDTO`

### 3.4 CV Analysis Job
- [ ] Create `app/Jobs/Volunteer/AnalyzeCV.php`
  - Extract text from CV file
  - Call `CVAnalyzerService`
  - Validate confidence score
  - If passed: update volunteer profile
  - If failed: flag for human review
  - Emit `CVAnalysisCompleted` event

### 3.5 Skill Normalization
- [ ] Create `app/Services/Volunteer/SkillNormalizationService.php`
  - Normalize skill names (e.g., "JavaScript" === "javascript" === "JS")
  - Create unified taxonomy
  - Store in `volunteer_skills` table

### 3.6 Validation & Quality Control
- [ ] Create `app/Services/Validation/AIOutputValidationService.php`
  - Schema validation
  - Confidence threshold checking
  - Logical consistency checks (years of experience vs level)
  - Business rule validation

### 3.7 DTOs
- [ ] Create `app/DTOs/CVAnalysisDTO.php`
  - Structured data from OpenAI response
  - `passesConfidenceThreshold()` method

### Deliverable:
- Volunteers can upload CVs
- CVs analyzed asynchronously by OpenAI
- Skills extracted and normalized
- Profiles enriched with AI data
- Confidence-based validation working

**Estimated Time:** CV analysis pipeline

---

## Phase 4: Challenge Submission & Analysis

### 4.1 Challenge Submission
- [ ] `POST /api/challenges`
- [ ] `ChallengeSubmissionService`
  - Validate company ownership
  - Create challenge record
  - Dispatch analysis pipeline

### 4.2 Challenge Analyzer Service
- [ ] Create `app/Services/AI/ChallengeAnalyzerService.php`
  - `analyzeBrief()` - Stage 1
  - `analyzeComplexity()` - Stage 2
  - `decomposeIntoWorkstreams()` - Stage 3
  - Strict JSON schemas for each stage

### 4.3 Analysis Pipeline Jobs
- [ ] `AnalyzeChallengeStage1_Brief.php`
  - Call `ChallengeAnalyzerService::analyzeBrief()`
  - Store in `challenge_analyses` (stage = 'brief')
  - Chain next job

- [ ] `ValidateAnalysisOutput.php`
  - Validate AI response
  - Check confidence threshold
  - If passed: continue chain
  - If failed: flag for review, stop chain

- [ ] `AnalyzeChallengeStage2_Complexity.php`
  - Call `ChallengeAnalyzerService::analyzeComplexity()`
  - Update challenge `complexity_level`
  - Store in `challenge_analyses` (stage = 'complexity')
  - Chain next job

- [ ] `AnalyzeChallengeStage3_Decomposition.php`
  - Call `ChallengeAnalyzerService::decomposeIntoWorkstreams()`
  - Create `workstreams` records
  - Store in `challenge_analyses` (stage = 'decomposition')
  - Dispatch task generation jobs

### 4.4 Task Generation
- [ ] Create `app/Services/AI/TaskGeneratorService.php`
  - `generateTasks()` method
  - Input: workstream + brief data
  - Output: structured tasks with skills, criteria, estimates

- [ ] `GenerateTasks.php` job
  - For each workstream, call `TaskGeneratorService`
  - Validate task quality
  - Store tasks in `tasks` table
  - Emit `TasksGenerated` event

### 4.5 Challenge Routing Service
- [ ] Create `app/Services/Challenge/ChallengeRoutingService.php`
  - Based on `complexity_level`:
    - Level 1-2: Route to community discussion
    - Level 3-4: Route to team formation
  - Update challenge `challenge_type`

### Deliverable:
- Companies can submit challenges
- Challenges analyzed through 3-stage pipeline
- Workstreams and tasks generated
- Challenges routed based on complexity
- All AI outputs validated

**Estimated Time:** Challenge analysis system

---

## Phase 5: Community Discussion Mode (Low Complexity)

### 5.1 Idea Submission
- [ ] `POST /api/challenges/{id}/ideas`
- [ ] `IdeaSubmissionService`
  - Create idea record
  - Dispatch scoring job

### 5.2 Idea Scoring
- [ ] Create `app/Services/AI/IdeaEvaluatorService.php`
  - `scoreIdea()` method
  - Scores: quality, relevance, feasibility
  - Return feedback and overall score

- [ ] `ScoreIdea.php` job
  - Call `IdeaEvaluatorService`
  - Store scores in `ideas` table
  - Update `total_score` (AI + community votes)

### 5.3 Community Voting
- [ ] `POST /api/ideas/{id}/vote`
  - Validate one vote per volunteer
  - Store in `idea_votes`
  - Update `ideas.community_votes_up/down`
  - Recalculate `total_score`

- [ ] `VotingService`
  - Calculate combined score (AI 60% + community 40%)
  - Ranking algorithm

### 5.4 Idea Ranking
- [ ] `GET /api/challenges/{id}/ideas`
  - Return ideas sorted by `total_score`
  - Include AI feedback and vote counts

### Deliverable:
- Volunteers can submit ideas for low-complexity challenges
- Ideas scored by AI
- Community can vote on ideas
- Ranked list of best solutions

**Estimated Time:** Community discussion features

---

## Phase 6: Team Formation Mode (High Complexity)

### 6.1 Volunteer Matching
- [ ] Create `app/Services/AI/VolunteerMatcherService.php`
  - `findMatches()` method
  - Input: task + available volunteers
  - Output: ranked matches with scores and reasoning

- [ ] `FindMatchingVolunteers.php` job
  - Get available volunteers (availability > 0, not overloaded)
  - Call `VolunteerMatcherService` for each task
  - Store top matches

### 6.2 Invitation System
- [ ] Create `app/Services/Matching/InvitationService.php`
  - `sendInvitation()` method
  - Create `task_assignment` with status 'invited'
  - Store AI match score and reasoning
  - Send notification

- [ ] `SendInvitations.php` job
  - For each task, send invitations to top 3 matches
  - Respect workload limits

### 6.3 Invitation Response
- [ ] `POST /api/tasks/{id}/accept`
  - Update `task_assignment.invitation_status` = 'accepted'
  - Update task status to 'assigned'
  - Send notification to company

- [ ] `POST /api/tasks/{id}/decline`
  - Update `task_assignment.invitation_status` = 'declined'
  - Invite next candidate

### 6.4 Task Management
- [ ] `GET /api/volunteers/tasks` - list assigned tasks
- [ ] `POST /api/tasks/{id}/start` - mark 'in_progress'
- [ ] `GET /api/tasks/{id}` - task details

### Deliverable:
- AI-powered volunteer matching
- Invitation system
- Volunteers can accept/decline tasks
- Task assignment workflow

**Estimated Time:** Team formation system

---

## Phase 7: Work Execution & Review

### 7.1 Work Submission
- [ ] `POST /api/tasks/{id}/submit`
- [ ] `WorkSubmissionService`
  - Create `work_submission` record
  - Update task status to 'submitted'
  - Store deliverables (URLs, attachments)
  - Notify company for review

### 7.2 Review System
- [ ] `POST /api/work-submissions/{id}/review`
- [ ] `ReviewService`
  - Create `review` record
  - Scores: quality, timeliness, communication
  - Decision: approved / rejected / revision_requested
  - Update work submission status

### 7.3 Reputation System
- [ ] Create `app/Services/Volunteer/ReputationService.php`
  - `updateReputation()` method
  - Calculate change based on review scores
  - Store in `reputation_history`
  - Update `volunteers.reputation_score`

**Reputation Algorithm:**
```
- Approved work: +2 to +5 points (based on quality score)
- Rejected work: -1 to -3 points
- Revision requested: No change
- Timeliness bonus: +0.5 if submitted early
- Max reputation: 100, Min: 0
```

### 7.4 Task Completion
- [ ] When work approved:
  - Mark task as 'completed'
  - Update `volunteers.total_tasks_completed`
  - Update `volunteers.total_hours_contributed`
  - Check if all challenge tasks completed
  - If yes, mark challenge as 'completed'

### Deliverable:
- Work submission workflow
- Review system
- Reputation scoring
- Task completion tracking

**Estimated Time:** Execution and review workflow

---

## Phase 8: Notifications & Real-time Updates

### 8.1 Notification Service
- [ ] Create `app/Services/Workflow/NotificationService.php`
  - `send()` method
  - Create `notification` record
  - Optionally email (using queues)

### 8.2 Event Listeners
- [ ] `CVAnalysisCompleted` → Notify volunteer
- [ ] `ChallengeAnalyzed` → Notify company
- [ ] `TaskAssigned` → Notify volunteer
- [ ] `WorkSubmitted` → Notify company
- [ ] `WorkReviewed` → Notify volunteer

### 8.3 Notification Endpoints
- [ ] `GET /api/notifications` - list unread
- [ ] `POST /api/notifications/{id}/read` - mark as read
- [ ] `POST /api/notifications/read-all` - mark all read

### Deliverable:
- Comprehensive notification system
- Email notifications (optional)
- Event-driven architecture working

**Estimated Time:** Notification system

---

## Phase 9: Admin Panel & Quality Control

### 9.1 Admin Review Queue
- [ ] `GET /api/admin/reviews/pending`
  - List items needing human review:
    - CVs below confidence threshold
    - Challenge analyses below threshold
    - Tasks that failed quality checks

### 9.2 Manual Override
- [ ] `POST /api/admin/reviews/{type}/{id}/approve`
- [ ] `POST /api/admin/reviews/{type}/{id}/reject`

### 9.3 Analytics Dashboard
- [ ] Total volunteers, challenges, tasks
- [ ] OpenAI API usage and costs
- [ ] Average confidence scores
- [ ] Success rates

### Deliverable:
- Admin can review flagged items
- Analytics for platform health
- Cost tracking

**Estimated Time:** Admin features

---

## Phase 10: Testing & Quality Assurance

### 10.1 Unit Tests
- [ ] Test DTOs
- [ ] Test Services (with mocked OpenAI)
- [ ] Test Validation logic
- [ ] Test Reputation calculations

### 10.2 Feature Tests
- [ ] Test CV upload flow
- [ ] Test challenge submission flow
- [ ] Test idea submission and voting
- [ ] Test task assignment flow
- [ ] Test work submission and review

### 10.3 Integration Tests
- [ ] Test full pipelines (CV analysis, challenge analysis)
- [ ] Test job chains
- [ ] Test event propagation

### 10.4 Manual Testing
- [ ] End-to-end volunteer journey
- [ ] End-to-end company journey
- [ ] Error handling and edge cases

### Deliverable:
- Comprehensive test suite
- 80%+ code coverage
- All critical paths tested

**Estimated Time:** Testing phase

---

## Phase 11: Deployment & Production Readiness

### 11.1 Environment Configuration
- [ ] Configure production database (PostgreSQL)
- [ ] Set up Redis for queues and cache
- [ ] Configure mail service (SendGrid, SES)
- [ ] Set up file storage (S3)
- [ ] Configure LinkedIn OAuth for production

### 11.2 Performance Optimization
- [ ] Database indexes
- [ ] Query optimization
- [ ] Implement caching (CV analysis results, challenge data)
- [ ] Queue worker optimization

### 11.3 Security
- [ ] Rate limiting on API endpoints
- [ ] Input sanitization
- [ ] CORS configuration
- [ ] API authentication (Sanctum tokens)
- [ ] Secure file uploads

### 11.4 Monitoring
- [ ] Set up error tracking (Sentry, Bugsnag)
- [ ] Log aggregation
- [ ] Performance monitoring
- [ ] OpenAI cost alerts

### 11.5 Documentation
- [ ] API documentation (OpenAPI/Swagger)
- [ ] Deployment guide
- [ ] Environment setup guide

### Deliverable:
- Production-ready application
- Deployed and monitored
- Documentation complete

**Estimated Time:** Deployment preparation

---

## Quick Start Commands

**Setup:**
```bash
# Install dependencies
composer install
npm install

# Configure environment
cp .env.example .env
# Add OPENAI_API_KEY, LINKEDIN_CLIENT_ID, LINKEDIN_CLIENT_SECRET

# Run migrations
php artisan migrate

# Seed test data
php artisan db:seed

# Generate application key
php artisan key:generate
```

**Development:**
```bash
# Start dev server
php artisan serve

# Run queue worker
php artisan queue:work

# Run tests
php artisan test

# Watch for changes
npm run dev
```

---

## Success Metrics

**Technical:**
- All AI operations have >80% confidence average
- <5% of items need human review
- API response time <500ms (95th percentile)
- OpenAI cost <$0.50 per challenge analyzed

**Business:**
- Volunteer profiles enriched with AI data
- Challenges decomposed into actionable tasks
- Successful volunteer-task matching
- Quality work delivered and reviewed

---

## Next Steps

1. **Phase 1-3:** Foundation, auth, CV analysis (Core volunteer onboarding)
2. **Phase 4-5:** Challenge system, community mode (Core company workflow)
3. **Phase 6-7:** Team formation, execution (Advanced features)
4. **Phase 8-11:** Notifications, admin, testing, deployment (Polish & ship)

Start with Phase 1 and work sequentially. Each phase builds on the previous one.
