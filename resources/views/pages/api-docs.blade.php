@extends('layouts.app')

@section('title', __('API Documentation'))

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h1 class="text-4xl font-bold text-gray-900 mb-8">{{ __('API Documentation') }}</h1>
    <p class="text-xl text-gray-600 mb-12">{{ __('Integrate Mindova into your applications with our RESTful API') }}</p>

    <div class="card mb-8 bg-primary-50 border-primary-200">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Getting Started') }}</h2>
        <p class="text-gray-700 mb-4">
            {{ __('The Mindova API allows developers to programmatically interact with challenges, contributors, tasks, and teams. All endpoints below require authentication using a Laravel Sanctum bearer token.') }}
        </p>
        <div class="bg-white rounded-lg p-4 border border-primary-300">
            <h3 class="font-semibold text-gray-900 mb-2">{{ __('Base URL') }}</h3>
            <code class="text-sm text-blue-700">{{ url('/api') }}</code>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <div class="lg:col-span-1">
            <div class="card sticky top-4">
                <h3 class="font-semibold text-gray-900 mb-4">{{ __('Quick Links') }}</h3>
                <nav class="space-y-2">
                    <a href="#authentication" class="block text-blue-700 hover:underline">{{ __('Authentication') }}</a>
                    <a href="#volunteers" class="block text-blue-700 hover:underline">{{ __('Contributors') }}</a>
                    <a href="#companies" class="block text-blue-700 hover:underline">{{ __('Companies') }}</a>
                    <a href="#challenges" class="block text-blue-700 hover:underline">{{ __('Challenges') }}</a>
                    <a href="#tasks" class="block text-blue-700 hover:underline">{{ __('Tasks') }}</a>
                    <a href="#teams" class="block text-blue-700 hover:underline">{{ __('Teams') }}</a>
                    <a href="#notifications" class="block text-blue-700 hover:underline">{{ __('Notifications') }}</a>
                    <a href="#errors" class="block text-blue-700 hover:underline">{{ __('Error Codes') }}</a>
                    <a href="#rate-limits" class="block text-blue-700 hover:underline">{{ __('Rate Limits') }}</a>
                </nav>
            </div>
        </div>

        <div class="lg:col-span-3 space-y-8">
            <section id="authentication" class="card">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Authentication') }}</h2>
                <p class="text-gray-700 mb-4">
                    {{ __('All API requests must include an authentication token in the Authorization header.') }}
                </p>
                <div class="bg-gray-800 text-gray-100 p-4 rounded-lg mb-4 overflow-x-auto">
                    <pre><code>Authorization: Bearer YOUR_API_TOKEN
Accept: application/json</code></pre>
                </div>

                <h3 class="text-lg font-semibold text-gray-900 mb-3 mt-6">{{ __('Obtaining a Token') }}</h3>
                <p class="text-gray-700 mb-3">
                    {{ __('There is no standalone JSON login endpoint. A Sanctum token is issued automatically when a user authenticates through the web app — either with email/password or via LinkedIn — and is then available to that browser session.') }}
                </p>
                <ul class="list-disc pl-6 text-gray-700 text-sm space-y-1 mb-3">
                    <li><strong>POST</strong> <code class="text-blue-700">/login</code> {{ __('(web form login, not under /api)') }}</li>
                    <li><strong>GET</strong> <code class="text-blue-700">/api/auth/linkedin/redirect</code> {{ __('and') }} <code class="text-blue-700">/api/auth/linkedin/callback</code> {{ __('(LinkedIn OAuth)') }}</li>
                </ul>
                <p class="text-gray-700 mb-3">
                    {{ __('On successful login, the server creates a Sanctum personal access token and stores it in the session. The app layout writes this token into') }} <code>localStorage.api_token</code> {{ __('for use by in-page JavaScript as a Bearer token. If you are building an external integration, generate a token for a service account the same way (log in once as that user) and store the resulting token securely on your side — it is not rotated automatically.') }}
                </p>

                <h4 class="font-semibold text-gray-900 mb-2 mt-4">{{ __('Revoking a Token') }}</h4>
                <p class="text-gray-700 mb-2"><span class="bg-blue-600 text-white px-2 py-1 rounded text-xs font-semibold">POST</span> <code class="text-blue-700">/api/auth/logout</code></p>
                <p class="text-gray-700">{{ __('Revokes all active tokens for the authenticated user.') }}</p>
            </section>

            <section id="volunteers" class="card">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Contributors') }}</h2>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Complete Contributor Profile') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-blue-600 text-white px-2 py-1 rounded text-xs font-semibold">POST</span> <code class="text-blue-700">/api/volunteers/complete-profile</code></p>
                    <p class="text-gray-700 mb-3">{{ __('Creates the volunteer profile for the authenticated user. Returns 422 if a profile already exists.') }}</p>
                    <div class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm">
                        <pre><code>{
  "availability_hours_per_week": 20,
  "bio": "Full-stack developer passionate about...",
  "field": "Software Engineering"
}</code></pre>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Get Current Contributor Profile') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-green-600 text-white px-2 py-1 rounded text-xs font-semibold">GET</span> <code class="text-blue-700">/api/volunteers/profile</code></p>
                    <p class="text-gray-700 mb-3">{{ __('Retrieves the authenticated volunteer\'s profile information including skills and statistics.') }}</p>

                    <h4 class="font-semibold text-gray-900 mb-2">{{ __('Response') }}</h4>
                    <div class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm">
                        <pre><code>{
  "id": 1,
  "user_id": 1,
  "availability_hours_per_week": 20,
  "bio": "Full-stack developer passionate about...",
  "reputation_score": 75,
  "skills": [
    {
      "id": 1,
      "name": "JavaScript",
      "proficiency_level": "expert"
    }
  ]
}</code></pre>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Update Contributor Profile') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-yellow-700 text-white px-2 py-1 rounded text-xs font-semibold">PUT</span> <code class="text-blue-700">/api/volunteers/profile</code></p>

                    <h4 class="font-semibold text-gray-900 mb-2 mt-3">{{ __('Request Body') }}</h4>
                    <div class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm">
                        <pre><code>{
  "availability_hours_per_week": 25,
  "bio": "Updated bio text...",
  "field": "Data Science"
}</code></pre>
                    </div>
                    <p class="text-gray-600 text-sm mt-2">{{ __('All fields are optional on update. CVs are not part of this endpoint — use the upload endpoint below.') }}</p>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Upload CV') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-blue-600 text-white px-2 py-1 rounded text-xs font-semibold">POST</span> <code class="text-blue-700">/api/volunteers/upload-cv</code></p>
                    <p class="text-gray-700 mb-3">{{ __('Multipart upload. Replaces any existing CV and queues it for AI analysis.') }}</p>
                    <ul class="list-disc pl-6 text-gray-700 text-sm space-y-1">
                        <li><code>cv</code> — {{ __('required file, one of pdf/doc/docx, max 10MB') }}</li>
                    </ul>
                </div>
            </section>

            <section id="companies" class="card">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Companies') }}</h2>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Complete Company Profile') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-blue-600 text-white px-2 py-1 rounded text-xs font-semibold">POST</span> <code class="text-blue-700">/api/companies/complete-profile</code></p>
                    <p class="text-gray-700 mb-3">{{ __('Creates the company profile for the authenticated user. Returns 422 if a profile already exists.') }}</p>
                    <div class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm">
                        <pre><code>{
  "company_name": "Acme Inc.",
  "industry": "Technology",
  "website": "https://acme.example.com",
  "description": "What the company does...",
  "logo": "(multipart file, optional, jpeg/png/jpg/gif, max 2MB)"
}</code></pre>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Get Current Company Profile') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-green-600 text-white px-2 py-1 rounded text-xs font-semibold">GET</span> <code class="text-blue-700">/api/companies/profile</code></p>
                    <p class="text-gray-700">{{ __('Returns the authenticated company\'s profile along with its challenges.') }}</p>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Update Company Profile') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-yellow-700 text-white px-2 py-1 rounded text-xs font-semibold">PUT</span> <code class="text-blue-700">/api/companies/profile</code></p>
                    <p class="text-gray-700 text-sm">{{ __('Accepts the same fields as profile completion; all fields are optional.') }}</p>
                </div>
            </section>

            <section id="challenges" class="card">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Challenges') }}</h2>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('List All Challenges') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-green-600 text-white px-2 py-1 rounded text-xs font-semibold">GET</span> <code class="text-blue-700">/api/challenges</code></p>
                    <p class="text-gray-700 mb-3">{{ __('Returns a paginated list of challenges.') }}</p>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('My Challenges') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-green-600 text-white px-2 py-1 rounded text-xs font-semibold">GET</span> <code class="text-blue-700">/api/challenges/my-challenges</code></p>
                    <p class="text-gray-700 mb-3">{{ __('Returns challenges submitted by the authenticated company.') }}</p>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Get Single Challenge') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-green-600 text-white px-2 py-1 rounded text-xs font-semibold">GET</span> <code class="text-blue-700">/api/challenges/{id}</code></p>
                    <p class="text-gray-700 mb-3">{{ __('Returns detailed information about a specific challenge including its company, AI analyses, workstreams, tasks, and ideas.') }}</p>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Create Challenge') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-blue-600 text-white px-2 py-1 rounded text-xs font-semibold">POST</span> <code class="text-blue-700">/api/challenges</code></p>
                    <p class="text-gray-700 mb-3">{{ __('Create a new challenge. Requires a company account. Dispatches AI brief analysis on submission.') }}</p>

                    <h4 class="font-semibold text-gray-900 mb-2 mt-3">{{ __('Request Body') }}</h4>
                    <div class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm">
                        <pre><code>{
  "title": "Challenge Title",
  "description": "Detailed description (100-5000 characters)..."
}</code></pre>
                    </div>
                    <p class="text-gray-600 text-sm mt-2">{{ __('Challenges have no deadline fields — there is no submission_deadline or completion_deadline.') }}</p>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Update Challenge') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-yellow-700 text-white px-2 py-1 rounded text-xs font-semibold">PUT</span> <code class="text-blue-700">/api/challenges/{id}</code></p>
                    <p class="text-gray-700 mb-3">{{ __('Owning company only, and only while the challenge is still submitted or analyzing.') }}</p>
                    <div class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm">
                        <pre><code>{
  "title": "Updated title",
  "description": "Updated description (100-5000 characters)..."
}</code></pre>
                    </div>
                    <p class="text-gray-600 text-sm mt-2">{{ __('Both fields are optional on update.') }}</p>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Archive Challenge') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-blue-600 text-white px-2 py-1 rounded text-xs font-semibold">POST</span> <code class="text-blue-700">/api/challenges/{id}/archive</code></p>
                    <p class="text-gray-700">{{ __('Owning company only. Sets the challenge status to archived.') }}</p>
                </div>
            </section>

            <section id="tasks" class="card">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Tasks') }}</h2>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('List Tasks') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-green-600 text-white px-2 py-1 rounded text-xs font-semibold">GET</span> <code class="text-blue-700">/api/tasks</code></p>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Get Available Tasks') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-green-600 text-white px-2 py-1 rounded text-xs font-semibold">GET</span> <code class="text-blue-700">/api/tasks/available</code></p>
                    <p class="text-gray-700 mb-3">{{ __('Returns open tasks matched to the authenticated volunteer\'s skills, excluding tasks they\'re already assigned to.') }}</p>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('My Tasks') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-green-600 text-white px-2 py-1 rounded text-xs font-semibold">GET</span> <code class="text-blue-700">/api/tasks/my-tasks</code></p>
                    <p class="text-gray-700 mb-3">{{ __('Returns tasks the authenticated volunteer has an assignment on.') }}</p>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Get Single Task') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-green-600 text-white px-2 py-1 rounded text-xs font-semibold">GET</span> <code class="text-blue-700">/api/tasks/{id}</code></p>
                </div>

                <h3 class="text-lg font-semibold text-gray-900 mb-3 mt-8">{{ __('Task Assignments') }}</h3>
                <p class="text-gray-600 text-sm mb-4">{{ __('Assignments link a volunteer to a task and move through a status lifecycle: invited → accepted/declined → in_progress → submitted → completed.') }}</p>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('My Assignments') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-green-600 text-white px-2 py-1 rounded text-xs font-semibold">GET</span> <code class="text-blue-700">/api/assignments</code></p>
                    <p class="text-gray-700 mb-3">{{ __('Returns assignments for the authenticated volunteer, or for tasks belonging to the authenticated company\'s challenges.') }}</p>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Pending Assignments') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-green-600 text-white px-2 py-1 rounded text-xs font-semibold">GET</span> <code class="text-blue-700">/api/assignments/pending</code></p>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Accept / Reject Assignment') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-blue-600 text-white px-2 py-1 rounded text-xs font-semibold">POST</span> <code class="text-blue-700">/api/assignments/{id}/accept</code></p>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-blue-600 text-white px-2 py-1 rounded text-xs font-semibold">POST</span> <code class="text-blue-700">/api/assignments/{id}/reject</code></p>
                    <p class="text-gray-700 mb-2">{{ __('Only the invited volunteer can respond, and only while status is invited. A volunteer may only have one active task at a time, so accepting can fail with 422 if they already have one in progress.') }}</p>
                    <div class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm">
                        <pre><code>// reject body (optional)
{
  "reason": "Not available this sprint"
}</code></pre>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Start / Complete Assignment') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-blue-600 text-white px-2 py-1 rounded text-xs font-semibold">POST</span> <code class="text-blue-700">/api/assignments/{id}/start</code></p>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-blue-600 text-white px-2 py-1 rounded text-xs font-semibold">POST</span> <code class="text-blue-700">/api/assignments/{id}/complete</code></p>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Submit Solution') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-blue-600 text-white px-2 py-1 rounded text-xs font-semibold">POST</span> <code class="text-blue-700">/api/assignments/{id}/submit-solution</code></p>
                    <div class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm">
                        <pre><code>{
  "description": "What was built and how (min 10 characters)",
  "deliverable_url": "https://github.com/...",
  "hours_worked": 12.5,
  "attachments[]": "(optional files, max 10MB each)"
}</code></pre>
                    </div>
                    <p class="text-gray-600 text-sm mt-2">{{ __('Note: this endpoint currently always responds with a redirect rather than JSON, even for API clients. Treat a 2xx/3xx status as success and re-fetch the assignment to confirm.') }}</p>
                </div>
            </section>

            <section id="teams" class="card">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Teams') }}</h2>
                <p class="text-gray-700 mb-4">
                    {{ __('Team formation and invitation responses are currently web-only features (session-based, not part of this token API). The API surface for teams is limited to in-team messaging.') }}
                </p>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('List Team Messages') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-green-600 text-white px-2 py-1 rounded text-xs font-semibold">GET</span> <code class="text-blue-700">/api/teams/{id}/messages</code></p>
                    <p class="text-gray-700">{{ __('Caller must be a member of the team.') }}</p>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Send Team Message') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-blue-600 text-white px-2 py-1 rounded text-xs font-semibold">POST</span> <code class="text-blue-700">/api/teams/{id}/messages</code></p>
                    <div class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm">
                        <pre><code>{
  "message": "Text content, max 2000 characters"
}</code></pre>
                    </div>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-sm text-yellow-800">
                    {{ __('Accepting or declining a team invitation is done via the web app at') }} <code>/teams/{id}/accept</code> {{ __('and') }} <code>/teams/{id}/decline</code> {{ __('— these require a logged-in browser session and are not reachable with a bearer token.') }}
                </div>
            </section>

            <section id="notifications" class="card">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Notifications') }}</h2>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('List Notifications') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-green-600 text-white px-2 py-1 rounded text-xs font-semibold">GET</span> <code class="text-blue-700">/api/notifications</code></p>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Unread Count') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-green-600 text-white px-2 py-1 rounded text-xs font-semibold">GET</span> <code class="text-blue-700">/api/notifications/unread-count</code></p>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Mark Read') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-blue-600 text-white px-2 py-1 rounded text-xs font-semibold">POST</span> <code class="text-blue-700">/api/notifications/{id}/mark-read</code></p>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-blue-600 text-white px-2 py-1 rounded text-xs font-semibold">POST</span> <code class="text-blue-700">/api/notifications/mark-all-read</code></p>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Delete Notification') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-red-600 text-white px-2 py-1 rounded text-xs font-semibold">DELETE</span> <code class="text-blue-700">/api/notifications/{id}</code></p>
                </div>

                <h3 class="text-lg font-semibold text-gray-900 mb-3 mt-8">{{ __('Ideas (Community Discussion Challenges)') }}</h3>
                <ul class="list-disc pl-6 text-gray-700 text-sm space-y-1">
                    <li><span class="font-mono text-xs bg-green-600 text-white px-1.5 py-0.5 rounded">GET</span> <code class="text-blue-700">/api/challenges/{challenge}/ideas</code> — {{ __('list ideas for a community-discussion challenge') }}</li>
                    <li><span class="font-mono text-xs bg-blue-600 text-white px-1.5 py-0.5 rounded">POST</span> <code class="text-blue-700">/api/challenges/{challenge}/ideas</code> — <code>{ "title", "description" }</code> ({{ __('description 100-2000 chars, volunteer only, challenge must be active') }})</li>
                    <li><span class="font-mono text-xs bg-green-600 text-white px-1.5 py-0.5 rounded">GET</span> <code class="text-blue-700">/api/ideas/my-ideas</code> — {{ __('ideas submitted by the authenticated volunteer') }}</li>
                    <li><span class="font-mono text-xs bg-green-600 text-white px-1.5 py-0.5 rounded">GET</span> <code class="text-blue-700">/api/ideas/{id}</code></li>
                    <li><span class="font-mono text-xs bg-blue-600 text-white px-1.5 py-0.5 rounded">POST</span> <code class="text-blue-700">/api/ideas/{id}/vote</code> — <code>{ "vote": -1 | 0 | 1 }</code> ({{ __('can\'t vote on your own idea; idea must already be AI-scored') }})</li>
                </ul>
            </section>

            <section id="errors" class="card">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Error Codes') }}</h2>
                <p class="text-gray-700 mb-4">{{ __('The API uses standard HTTP status codes:') }}</p>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase font-bold">{{ __('Code') }}</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase font-bold">{{ __('Meaning') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="px-4 py-3 text-sm font-mono">200</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ __('OK - Request successful') }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm font-mono">201</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ __('Created - Resource created successfully') }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm font-mono">401</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ __('Unauthorized - Invalid or missing token') }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm font-mono">403</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ __('Forbidden - Insufficient permissions, e.g. wrong account type or not the resource owner') }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm font-mono">404</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ __('Not Found - Resource does not exist') }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm font-mono">422</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ __('Unprocessable Entity - Validation failed, or the action is not valid for the resource\'s current status') }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm font-mono">500</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ __('Server Error - Something went wrong') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h3 class="text-lg font-semibold text-gray-900 mb-3 mt-6">{{ __('Error Response Format') }}</h3>
                <div class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm">
                    <pre><code>{
  "message": "Error message",
  "errors": {
    "field": ["Validation error message"]
  }
}</code></pre>
                </div>
                <p class="text-gray-600 text-sm mt-2">
                    {{ __('The "errors" object is only present on 422 validation failures. A few endpoints (notably submit-solution) are built primarily for the web UI and currently return a redirect instead of JSON — see the note on that endpoint above.') }}
                </p>
            </section>

            <section id="rate-limits" class="card">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Rate Limits') }}</h2>
                <p class="text-gray-700 mb-4">
                    {{ __('There is currently no request-rate throttling configured on the API. Requests are not limited beyond standard server capacity, and no 429 responses or X-RateLimit-* headers are returned today.') }}
                </p>
                <p class="text-gray-700">
                    {{ __('Login attempts are the exception: the web login form is rate-limited to 5 attempts per email/IP combination before further attempts are blocked. Build integrations defensively and avoid tight polling loops — explicit API throttling may be introduced in the future.') }}
                </p>
            </section>
        </div>
    </div>

    <div class="mt-12 card bg-primary-50 border-primary-200">
        <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Need Help?') }}</h3>
        <p class="text-gray-700 mb-4">
            {{ __('For additional support with the API, please contact our developer support team.') }}
        </p>
        <x-ui.button as="a" href="{{ route('contact') }}">{{ __('Contact Developer Support') }}</x-ui.button>
    </div>
</div>
@endsection
