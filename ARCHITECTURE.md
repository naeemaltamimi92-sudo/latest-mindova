# Laravel Application Architecture - Mindova Platform

## Overview
This platform uses a **service-oriented architecture** that strictly separates concerns:
- **AI Layer (OpenAI):** Reasoning, analysis, scoring, matching
- **Core Engine (Laravel):** Validation, rule enforcement, decision execution, workflow management
- **Queue System:** Asynchronous processing, cost control, performance optimization

---

## Architecture Layers

### Layer 1: API / Controllers
**Purpose:** HTTP request handling, input validation, response formatting

```
app/Http/
├── Controllers/
│   ├── Auth/
│   │   ├── LinkedInAuthController.php
│   │   └── LoginController.php
│   ├── Volunteer/
│   │   ├── ProfileController.php
│   │   ├── CVUploadController.php
│   │   ├── TaskController.php
│   │   └── IdeaController.php
│   ├── Company/
│   │   ├── ProfileController.php
│   │   ├── ChallengeController.php
│   │   └── TeamController.php
│   └── Admin/
│       ├── ReviewController.php
│       └── AnalyticsController.php
├── Requests/
│   ├── CVUploadRequest.php
│   ├── ChallengeSubmissionRequest.php
│   └── IdeaSubmissionRequest.php
└── Resources/
    ├── VolunteerResource.php
    ├── ChallengeResource.php
    └── TaskResource.php
```

**Responsibilities:**
- Validate request data (FormRequest classes)
- Delegate to Services
- Return JSON responses
- Handle authentication/authorization

---

### Layer 2: Services (Business Logic)
**Purpose:** Domain logic, orchestration, deterministic rules

```
app/Services/
├── Volunteer/
│   ├── VolunteerRegistrationService.php
│   ├── CVAnalysisService.php
│   ├── SkillNormalizationService.php
│   └── ReputationService.php
├── Company/
│   ├── CompanyRegistrationService.php
│   └── ChallengeSubmissionService.php
├── Challenge/
│   ├── ChallengeAnalysisPipelineService.php
│   ├── WorkstreamGenerationService.php
│   ├── TaskGenerationService.php
│   └── ChallengeRoutingService.php
├── Matching/
│   ├── VolunteerMatchingService.php
│   ├── InvitationService.php
│   └── TeamFormationService.php
├── Community/
│   ├── IdeaScoringService.php
│   └── VotingService.php
├── Validation/
│   ├── AIOutputValidationService.php
│   ├── ConfidenceThresholdService.php
│   └── QualityCheckService.php
└── Workflow/
    ├── TaskWorkflowService.php
    ├── ReviewService.php
    └── NotificationService.php
```

**Responsibilities:**
- Enforce business rules
- Validate AI outputs
- Apply confidence thresholds
- Orchestrate multi-step processes
- Manage state transitions
- Call AI Services when needed

---

### Layer 3: AI Services (OpenAI Integration)
**Purpose:** All cognitive operations via OpenAI API

```
app/Services/AI/
├── OpenAIService.php (base service)
├── CVAnalyzerService.php
├── ChallengeAnalyzerService.php
├── TaskGeneratorService.php
├── IdeaEvaluatorService.php
├── VolunteerMatcherService.php
└── Contracts/
    └── AIServiceInterface.php
```

**Responsibilities:**
- Construct prompts
- Call OpenAI API
- Parse JSON responses
- Log all requests/responses
- Handle rate limiting
- Return structured DTOs

**Example Structure:**
```php
class CVAnalyzerService implements AIServiceInterface
{
    public function analyze(string $cvContent): CVAnalysisDTO
    {
        $prompt = $this->buildPrompt($cvContent);
        $response = $this->openAIService->chat($prompt);
        $data = $this->parseResponse($response);

        // Log the request
        $this->logRequest('cv_analysis', $prompt, $response);

        return new CVAnalysisDTO($data);
    }
}
```

---

### Layer 4: Jobs (Async Processing)
**Purpose:** Queue-based asynchronous operations

```
app/Jobs/
├── Volunteer/
│   ├── AnalyzeCV.php
│   ├── NormalizeSkills.php
│   └── UpdateReputationScore.php
├── Challenge/
│   ├── AnalyzeChallengeStage1_Brief.php
│   ├── AnalyzeChallengeStage2_Complexity.php
│   ├── AnalyzeChallengeStage3_Decomposition.php
│   ├── ValidateAnalysisOutput.php
│   └── GenerateTasks.php
├── Matching/
│   ├── FindMatchingVolunteers.php
│   └── SendInvitations.php
├── Community/
│   ├── ScoreIdea.php
│   └── UpdateIdeaRankings.php
└── Notifications/
    └── SendNotification.php
```

**Responsibilities:**
- Execute async AI calls
- Handle failures with retries
- Chain dependent jobs
- Update status fields
- Emit events on completion

**Example Pipeline:**
```php
AnalyzeChallengeStage1_Brief::dispatch($challenge)
    ->chain([
        new ValidateAnalysisOutput($challenge, 'brief'),
        new AnalyzeChallengeStage2_Complexity($challenge),
        new ValidateAnalysisOutput($challenge, 'complexity'),
        new AnalyzeChallengeStage3_Decomposition($challenge),
        new GenerateTasks($challenge),
    ]);
```

---

### Layer 5: Events & Listeners
**Purpose:** Decoupled event-driven architecture

```
app/Events/
├── Volunteer/
│   ├── CVUploaded.php
│   ├── CVAnalysisCompleted.php
│   ├── SkillsNormalized.php
│   └── ReputationUpdated.php
├── Challenge/
│   ├── ChallengeSubmitted.php
│   ├── ChallengeAnalyzed.php
│   ├── TasksGenerated.php
│   └── ChallengeCompleted.php
└── Task/
    ├── TaskAssigned.php
    ├── WorkSubmitted.php
    └── WorkReviewed.php

app/Listeners/
├── Volunteer/
│   ├── QueueCVAnalysis.php
│   └── NotifyVolunteerProfileReady.php
├── Challenge/
│   ├── StartAnalysisPipeline.php
│   ├── RouteChallenge.php
│   └── NotifyCompany.php
└── Task/
    ├── UpdateTaskStatus.php
    └── NotifyVolunteer.php
```

---

### Layer 6: Data Transfer Objects (DTOs)
**Purpose:** Structured data between layers

```
app/DTOs/
├── CVAnalysisDTO.php
├── ChallengeAnalysisDTO.php
├── TaskDTO.php
├── VolunteerMatchDTO.php
└── IdeaScoringDTO.php
```

**Example:**
```php
class CVAnalysisDTO
{
    public function __construct(
        public readonly array $education,
        public readonly array $workExperience,
        public readonly array $skills,
        public readonly array $professionalDomains,
        public readonly string $experienceLevel,
        public readonly float $yearsOfExperience,
        public readonly float $confidenceScore,
    ) {}

    public function passesConfidenceThreshold(): bool
    {
        return $this->confidenceScore >= config('ai.confidence_threshold');
    }
}
```

---

### Layer 7: Models (Data Layer)
**Purpose:** Database interaction via Eloquent ORM

```
app/Models/
├── User.php
├── Volunteer.php
├── VolunteerSkill.php
├── Company.php
├── Challenge.php
├── ChallengeAnalysis.php
├── Workstream.php
├── Task.php
├── TaskAssignment.php
├── Idea.php
├── IdeaVote.php
├── WorkSubmission.php
├── Review.php
├── ReputationHistory.php
├── OpenAIRequest.php
└── Notification.php
```

**Responsibilities:**
- Define relationships
- Implement scopes
- Cast JSON fields
- Handle timestamps
- Define observers

---

## Core Design Patterns

### 1. Service Pattern
**All business logic lives in services, not controllers or models.**

```php
// BAD: Logic in controller
public function upload(Request $request) {
    $volunteer = auth()->user()->volunteer;
    $file = $request->file('cv');
    $path = $file->store('cvs');
    $volunteer->cv_file_path = $path;
    $volunteer->save();

    // Call OpenAI...
    // Validate...
    // Update DB...
}

// GOOD: Delegate to service
public function upload(CVUploadRequest $request) {
    $result = app(CVAnalysisService::class)
        ->processUpload(auth()->user()->volunteer, $request->file('cv'));

    return response()->json(['message' => 'CV uploaded successfully']);
}
```

### 2. Job Chaining Pattern
**Multi-stage AI pipelines use job chains for sequential processing.**

```php
// Example: Challenge Analysis Pipeline
AnalyzeChallengeStage1_Brief::dispatch($challenge)
    ->chain([
        new ValidateAnalysisOutput($challenge, 'brief'),
        new AnalyzeChallengeStage2_Complexity($challenge),
        new ValidateAnalysisOutput($challenge, 'complexity'),
        new AnalyzeChallengeStage3_Decomposition($challenge),
        new ValidateAnalysisOutput($challenge, 'decomposition'),
        new GenerateTasks($challenge),
    ]);
```

### 3. Validation Pattern
**Every AI output passes through validation before affecting the system.**

```php
class AIOutputValidationService
{
    public function validate(mixed $data, string $schema): ValidationResult
    {
        // Schema validation
        if (!$this->validateSchema($data, $schema)) {
            return ValidationResult::failed('Schema validation failed');
        }

        // Confidence threshold check
        if ($data['confidence_score'] < config('ai.confidence_threshold')) {
            return ValidationResult::needsReview('Below confidence threshold');
        }

        // Business rule validation
        if (!$this->validateBusinessRules($data)) {
            return ValidationResult::failed('Business rules violated');
        }

        return ValidationResult::passed();
    }
}
```

### 4. Repository Pattern (Optional)
**For complex queries, use repositories to encapsulate data access.**

```php
app/Repositories/
├── VolunteerRepository.php
├── ChallengeRepository.php
└── TaskRepository.php
```

---

## Configuration Structure

```
config/
├── ai.php (OpenAI settings, thresholds, models)
├── reputation.php (Scoring rules)
├── matching.php (Matching algorithms)
└── workflows.php (State machine configs)
```

**Example: config/ai.php**
```php
return [
    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'organization' => env('OPENAI_ORGANIZATION'),
        'default_model' => env('OPENAI_MODEL', 'gpt-4o'),
        'timeout' => 60,
    ],

    'confidence_threshold' => [
        'cv_analysis' => 70.0,
        'challenge_analysis' => 75.0,
        'task_generation' => 80.0,
        'volunteer_matching' => 65.0,
        'idea_scoring' => 60.0,
    ],

    'models' => [
        'cv_analysis' => 'gpt-4o',
        'challenge_analysis' => 'gpt-4o',
        'task_generation' => 'gpt-4o',
        'volunteer_matching' => 'gpt-4o-mini',
        'idea_scoring' => 'gpt-4o-mini',
    ],
];
```

---

## Queue Configuration

**config/queue.php**
- Default connection: `database` (for simplicity)
- Separate queues for priority:
  - `high`: User-facing operations (CV analysis)
  - `default`: Background processing
  - `low`: Analytics, cleanup

**Queue Workers:**
```bash
php artisan queue:work --queue=high,default,low
```

---

## API Routes Structure

```
routes/api.php

/api/v1/
├── /auth
│   ├── POST /linkedin (LinkedIn OAuth callback)
│   └── POST /logout
├── /volunteers
│   ├── POST /register
│   ├── POST /cv/upload
│   ├── GET /profile
│   ├── GET /tasks
│   └── GET /reputation
├── /companies
│   ├── POST /register
│   ├── POST /challenges
│   ├── GET /challenges/{id}
│   └── GET /challenges/{id}/team
├── /challenges
│   ├── GET / (list public challenges)
│   ├── GET /{id}
│   └── POST /{id}/ideas
├── /tasks
│   ├── GET /{id}
│   ├── POST /{id}/accept
│   ├── POST /{id}/submit
│   └── POST /{id}/review
└── /ideas
    ├── POST /{id}/vote
    └── GET /challenge/{challengeId}
```

---

## Key Principles

1. **AI Never Acts Alone:** Every AI output is validated before execution
2. **Async by Default:** All AI calls happen in queues
3. **Fail Gracefully:** Below-threshold confidence triggers human review, not rejection
4. **Audit Everything:** All AI requests logged with input/output/cost
5. **Deterministic Core:** Business rules and workflows are pure Laravel code
6. **Type Safety:** Use DTOs for AI responses, typed arrays for JSON fields
7. **Event-Driven:** Decouple processes via events/listeners
8. **Testable:** Services are unit-testable, jobs are integration-testable

---

## Testing Strategy

```
tests/
├── Unit/
│   ├── Services/
│   │   ├── CVAnalysisServiceTest.php
│   │   ├── ValidationServiceTest.php
│   │   └── ReputationServiceTest.php
│   └── DTOs/
│       └── CVAnalysisDTOTest.php
├── Feature/
│   ├── Volunteer/
│   │   ├── CVUploadTest.php
│   │   └── ProfileTest.php
│   └── Challenge/
│       ├── ChallengeSubmissionTest.php
│       └── TaskGenerationTest.php
└── Integration/
    └── Jobs/
        └── ChallengeAnalysisPipelineTest.php
```

**Mock OpenAI in tests:**
```php
// Use a fake OpenAI service in tests
$this->app->instance(OpenAIService::class, new FakeOpenAIService());
```

---

## Next Steps

1. Install required packages (OpenAI PHP SDK, Laravel Socialite)
2. Create migrations from database schema
3. Build core models with relationships
4. Implement OpenAI service layer
5. Build validation services
6. Create job pipelines
7. Implement API endpoints
8. Add authentication (LinkedIn OAuth)
