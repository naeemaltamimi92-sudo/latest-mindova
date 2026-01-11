@extends('layouts.app')

@section('title', __('Community Guidelines'))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h1 class="text-4xl font-bold text-gray-900 mb-8">{{ __('Community Guidelines') }}</h1>
    <p class="text-gray-600 mb-8">{{ __('Last updated:') }} {{ date('F d, Y') }}</p>

    <div class="prose max-w-none">
        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Welcome to Mindova') }}</h2>
            <p class="text-gray-700 mb-4">
                {{ __('Our community is built on collaboration, respect, and the shared goal of solving meaningful challenges. These guidelines help ensure a positive experience for everyone.') }}
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Core Values') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="card">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">🤝 Collaboration</h3>
                    <p class="text-sm text-gray-600">Work together toward shared goals, support teammates, and celebrate collective achievements.</p>
                </div>
                <div class="card">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">💡 Innovation</h3>
                    <p class="text-sm text-gray-600">Bring creative solutions, think outside the box, and embrace new approaches to problem-solving.</p>
                </div>
                <div class="card">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">🎯 Quality</h3>
                    <p class="text-sm text-gray-600">Deliver your best work, meet commitments, and maintain high standards of excellence.</p>
                </div>
                <div class="card">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">🌟 Respect</h3>
                    <p class="text-sm text-gray-600">Value diverse perspectives, communicate professionally, and treat all members with dignity.</p>
                </div>
            </div>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Expected Behavior</h2>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">For All Users</h3>
            <ul class="list-disc pl-6 text-gray-700 space-y-2 mb-4">
                <li><strong>Be Honest:</strong> Provide accurate information about your skills, availability, and capabilities</li>
                <li><strong>Communicate Clearly:</strong> Keep team members and companies informed of your progress and any challenges</li>
                <li><strong>Respect Time:</strong> Meet deadlines when possible, and communicate early if you cannot</li>
                <li><strong>Give Credit:</strong> Acknowledge contributions from others and respect intellectual property</li>
                <li><strong>Stay Professional:</strong> Maintain courteous communication even during disagreements</li>
                <li><strong>Protect Privacy:</strong> Respect confidential information shared by companies and team members</li>
            </ul>

            <h3 class="text-xl font-semibold text-gray-900 mb-3">For Contributors</h3>
            <ul class="list-disc pl-6 text-gray-700 space-y-2 mb-4">
                <li>Only accept tasks you can realistically complete</li>
                <li>Update your availability status regularly</li>
                <li>Communicate with your team if you need help or face obstacles</li>
                <li>Respect the leadership of team leaders while contributing your perspective</li>
                <li>Ask questions when requirements are unclear</li>
                <li>Share knowledge and help other team members when possible</li>
            </ul>

            <h3 class="text-xl font-semibold text-gray-900 mb-3">For Companies</h3>
            <ul class="list-disc pl-6 text-gray-700 space-y-2 mb-4">
                <li>Provide clear, detailed challenge descriptions</li>
                <li>Set realistic deadlines and expectations</li>
                <li>Respond promptly to volunteer questions and submissions</li>
                <li>Give constructive feedback on completed work</li>
                <li>Respect that volunteers are contributing their time and skills</li>
                <li>Credit volunteer contributions appropriately</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Prohibited Conduct</h2>
            <p class="text-gray-700 mb-4">The following behaviors are not tolerated:</p>
            <ul class="list-disc pl-6 text-gray-700 space-y-2 mb-4">
                <li><strong>Harassment:</strong> Including offensive comments, personal attacks, or unwelcome contact</li>
                <li><strong>Discrimination:</strong> Based on race, gender, age, religion, disability, or any other protected characteristic</li>
                <li><strong>Spam:</strong> Unsolicited promotional content, repetitive messaging, or off-topic communications</li>
                <li><strong>Fraud:</strong> Misrepresenting skills, experience, or completed work</li>
                <li><strong>Plagiarism:</strong> Submitting others' work as your own</li>
                <li><strong>System Abuse:</strong> Attempting to manipulate matching algorithms, reputation scores, or platform features</li>
                <li><strong>Illegal Activity:</strong> Requesting or performing work that violates laws or regulations</li>
                <li><strong>Confidentiality Breaches:</strong> Sharing private information without authorization</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Communication Best Practices</h2>
            <div class="bg-primary-50 border border-primary-200 rounded-lg p-6 mb-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">✅ Do</h3>
                <ul class="list-disc pl-6 text-gray-700 space-y-2">
                    <li>Use clear, professional language</li>
                    <li>Respond to messages within 24-48 hours when possible</li>
                    <li>Provide context when asking questions</li>
                    <li>Use constructive language when giving feedback</li>
                    <li>Acknowledge receipt of important messages</li>
                    <li>Express appreciation for good work</li>
                </ul>
            </div>

            <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">❌ Don't</h3>
                <ul class="list-disc pl-6 text-gray-700 space-y-2">
                    <li>Use all caps (it appears as shouting)</li>
                    <li>Send excessive messages or spam</li>
                    <li>Share personal contact information publicly</li>
                    <li>Make demands without context or reasoning</li>
                    <li>Ignore messages from teammates or companies</li>
                    <li>Use offensive or inappropriate language</li>
                </ul>
            </div>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Handling Conflicts</h2>
            <p class="text-gray-700 mb-4">
                Disagreements may arise. When they do:
            </p>
            <ol class="list-decimal pl-6 text-gray-700 space-y-2 mb-4">
                <li><strong>Address Issues Directly:</strong> Communicate respectfully with the person involved first</li>
                <li><strong>Focus on Solutions:</strong> Identify the problem and work toward resolution rather than assigning blame</li>
                <li><strong>Seek Mediation:</strong> If direct communication doesn't resolve the issue, involve team leaders or our support team</li>
                <li><strong>Document Issues:</strong> Keep records of problematic interactions if needed for escalation</li>
                <li><strong>Contact Support:</strong> For serious violations, contact us at <a href="mailto:mindova.ai@gmail.com" class="text-primary-600 hover:underline">mindova.ai@gmail.com</a></li>
            </ol>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Reputation and Consequences</h2>
            <p class="text-gray-700 mb-4">
                Your reputation on Mindova reflects your contributions and conduct. Violations of these guidelines may result in:
            </p>
            <ul class="list-disc pl-6 text-gray-700 space-y-2 mb-4">
                <li><strong>Warning:</strong> For first-time or minor violations</li>
                <li><strong>Reputation Reduction:</strong> Impacting future matching and opportunities</li>
                <li><strong>Temporary Suspension:</strong> Restricting platform access for a set period</li>
                <li><strong>Permanent Ban:</strong> Removal from the platform for serious or repeated violations</li>
            </ul>
            <p class="text-gray-700 mb-4">
                We review each case individually and aim for fair, proportionate responses.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Reporting Violations</h2>
            <p class="text-gray-700 mb-4">
                If you witness behavior that violates these guidelines:
            </p>
            <ul class="list-disc pl-6 text-gray-700 space-y-2 mb-4">
                <li>Use the in-platform reporting feature (if available)</li>
                <li>Email <a href="mailto:conduct@mindova.com" class="text-primary-600 hover:underline">conduct@mindova.com</a> with details</li>
                <li>Include relevant screenshots, messages, or evidence</li>
                <li>Provide your account information and the date/time of the incident</li>
            </ul>
            <p class="text-gray-700 mb-4">
                All reports are reviewed confidentially. We do not tolerate retaliation against those who report violations in good faith.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Building a Better Community</h2>
            <p class="text-gray-700 mb-4">
                Everyone plays a role in making Mindova a welcoming, productive environment. We encourage you to:
            </p>
            <ul class="list-disc pl-6 text-gray-700 space-y-2 mb-4">
                <li>Welcome new members and help them get started</li>
                <li>Share knowledge and mentor others</li>
                <li>Celebrate team and individual achievements</li>
                <li>Provide constructive feedback to help others improve</li>
                <li>Suggest improvements to the platform and community</li>
                <li>Lead by example in your conduct and contributions</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Updates to Guidelines</h2>
            <p class="text-gray-700 mb-4">
                We may update these guidelines as our community grows and evolves. Significant changes will be communicated through the Platform. Continued use of Mindova indicates acceptance of updated guidelines.
            </p>
        </section>

        <section>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Questions?</h2>
            <p class="text-gray-700 mb-4">
                If you have questions about these guidelines, please contact us at:
            </p>
            <p class="text-gray-700">
                <a href="mailto:community@mindova.com" class="text-primary-600 hover:underline">community@mindova.com</a>
            </p>
        </section>
    </div>

    <div class="mt-12 border-t pt-8 text-center">
        <p class="text-gray-600">
            Thank you for being part of the Mindova community and helping us create a collaborative, respectful environment for solving meaningful challenges together.
        </p>
    </div>
</div>
@endsection
