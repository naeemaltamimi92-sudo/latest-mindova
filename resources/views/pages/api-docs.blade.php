@extends('layouts.app')

@section('title', __('API Documentation'))

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h1 class="text-4xl font-bold text-gray-900 mb-8">{{ __('API Documentation') }}</h1>
    <p class="text-xl text-gray-600 mb-12">{{ __('Integrate Mindova into your applications with our RESTful API') }}</p>

    <div class="card mb-8 bg-primary-50 border-primary-200">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Getting Started') }}</h2>
        <p class="text-gray-700 mb-4">
            {{ __('The Mindova API allows developers to programmatically interact with challenges, volunteers, tasks, and teams. All API endpoints require authentication using Laravel Sanctum tokens.') }}
        </p>
        <div class="bg-white rounded-lg p-4 border border-primary-300">
            <h3 class="font-semibold text-gray-900 mb-2">{{ __('Base URL') }}</h3>
            <code class="text-sm text-blue-700">https://mindova.com/api</code>
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
                    <pre><code>Authorization: Bearer YOUR_API_TOKEN</code></pre>
                </div>

                <h3 class="text-lg font-semibold text-gray-900 mb-3 mt-6">{{ __('Obtaining a Token') }}</h3>
                <p class="text-gray-700 mb-2"><strong>POST</strong> <code class="text-blue-700">/api/login</code></p>
                <div class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto">
                    <pre><code>{
  "email": "user@example.com",
  "password": "your_password"
}</code></pre>
                </div>

                <h4 class="font-semibold text-gray-900 mb-2 mt-4">{{ __('Response') }}</h4>
                <div class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto">
                    <pre><code>{
  "token": "1|abcdef123456...",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "user@example.com",
    "user_type": "volunteer"
  }
}</code></pre>
                </div>
            </section>

            <section id="volunteers" class="card">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Contributors') }}</h2>

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
  "cv": "base64_encoded_file"
}</code></pre>
                    </div>
                </div>
            </section>

            <section id="challenges" class="card">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Challenges') }}</h2>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('List All Challenges') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-green-600 text-white px-2 py-1 rounded text-xs font-semibold">GET</span> <code class="text-blue-700">/api/challenges</code></p>
                    <p class="text-gray-700 mb-3">{{ __('Returns a paginated list of all challenges.') }}</p>

                    <h4 class="font-semibold text-gray-900 mb-2">{{ __('Query Parameters') }}</h4>
                    <ul class="list-disc pl-6 text-gray-700 text-sm space-y-1 mb-3">
                        <li><code>status</code> - {{ __('Filter by status') }} (analysis_pending, active, completed)</li>
                        <li><code>page</code> - {{ __('Page number for pagination') }}</li>
                        <li><code>per_page</code> - {{ __('Results per page') }} ({{ __('default') }}: 15)</li>
                    </ul>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Get Single Challenge') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-green-600 text-white px-2 py-1 rounded text-xs font-semibold">GET</span> <code class="text-blue-700">/api/challenges/{id}</code></p>
                    <p class="text-gray-700 mb-3">{{ __('Returns detailed information about a specific challenge including workstreams, tasks, and teams.') }}</p>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Create Challenge') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-blue-600 text-white px-2 py-1 rounded text-xs font-semibold">POST</span> <code class="text-blue-700">/api/challenges</code></p>
                    <p class="text-gray-700 mb-3">{{ __('Create a new challenge. Requires company account.') }}</p>

                    <h4 class="font-semibold text-gray-900 mb-2 mt-3">{{ __('Request Body') }}</h4>
                    <div class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm">
                        <pre><code>{
  "title": "Challenge Title",
  "description": "Detailed description (min 100 characters)...",
  "submission_deadline": "2025-12-31",
  "completion_deadline": "2026-01-31"
}</code></pre>
                    </div>
                </div>
            </section>

            <section id="tasks" class="card">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Tasks') }}</h2>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Get Available Tasks') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-green-600 text-white px-2 py-1 rounded text-xs font-semibold">GET</span> <code class="text-blue-700">/api/tasks/available</code></p>
                    <p class="text-gray-700 mb-3">{{ __('Returns tasks matched to the authenticated volunteer based on their skills.') }}</p>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Get My Task Assignments') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-green-600 text-white px-2 py-1 rounded text-xs font-semibold">GET</span> <code class="text-blue-700">/api/assignments/my</code></p>
                    <p class="text-gray-700 mb-3">{{ __('Returns all task assignments for the authenticated volunteer.') }}</p>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Update Task Assignment') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-yellow-700 text-white px-2 py-1 rounded text-xs font-semibold">PUT</span> <code class="text-blue-700">/api/assignments/{id}</code></p>

                    <h4 class="font-semibold text-gray-900 mb-2 mt-3">{{ __('Request Body') }}</h4>
                    <div class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm">
                        <pre><code>{
  "invitation_status": "in_progress",
  "hours_worked": 5
}</code></pre>
                    </div>
                </div>
            </section>

            <section id="teams" class="card">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Teams') }}</h2>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Get Team Details') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-green-600 text-white px-2 py-1 rounded text-xs font-semibold">GET</span> <code class="text-blue-700">/api/teams/{id}</code></p>
                    <p class="text-gray-700 mb-3">{{ __('Returns detailed information about a team including all members and their roles.') }}</p>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Accept Team Invitation') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-blue-600 text-white px-2 py-1 rounded text-xs font-semibold">POST</span> <code class="text-blue-700">/api/teams/{id}/accept</code></p>
                    <p class="text-gray-700 mb-3">{{ __('Accept an invitation to join a team.') }}</p>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Decline Team Invitation') }}</h3>
                    <p class="text-sm text-gray-600 mb-2"><span class="bg-blue-600 text-white px-2 py-1 rounded text-xs font-semibold">POST</span> <code class="text-blue-700">/api/teams/{id}/decline</code></p>
                    <p class="text-gray-700 mb-3">{{ __('Decline an invitation to join a team.') }}</p>
                </div>
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
                                <td class="px-4 py-3 text-sm font-mono">400</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ __('Bad Request - Invalid parameters') }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm font-mono">401</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ __('Unauthorized - Invalid or missing token') }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm font-mono">403</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ __('Forbidden - Insufficient permissions') }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm font-mono">404</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ __('Not Found - Resource does not exist') }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm font-mono">422</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ __('Unprocessable Entity - Validation failed') }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm font-mono">429</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ __('Too Many Requests - Rate limit exceeded') }}</td>
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
            </section>

            <section id="rate-limits" class="card">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Rate Limits') }}</h2>
                <p class="text-gray-700 mb-4">
                    {{ __('API requests are rate-limited to ensure fair usage and system stability.') }}
                </p>
                <ul class="list-disc pl-6 text-gray-700 space-y-2">
                    <li><strong>{{ __('Authenticated requests:') }}</strong> 60 {{ __('requests per minute') }}</li>
                    <li><strong>{{ __('Unauthenticated requests:') }}</strong> 10 {{ __('requests per minute') }}</li>
                </ul>
                <p class="text-gray-700 mt-4">
                    {{ __('Rate limit information is included in response headers:') }}
                </p>
                <div class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm mt-3">
                    <pre><code>X-RateLimit-Limit: 60
X-RateLimit-Remaining: 45
X-RateLimit-Reset: 1640995200</code></pre>
                </div>
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
