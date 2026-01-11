@extends('layouts.app')

@section('title', __('Privacy Policy'))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h1 class="text-4xl font-bold text-gray-900 mb-8">{{ __('Privacy Policy') }}</h1>
    <p class="text-gray-600 mb-8">{{ __('Last updated:') }} {{ date('F d, Y') }}</p>

    <div class="prose max-w-none">
        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">1. Introduction</h2>
            <p class="text-gray-700 mb-4">
                Mindova ("we," "our," or "us") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our Platform.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">2. Information We Collect</h2>

            <h3 class="text-xl font-semibold text-gray-900 mb-3">2.1 Information You Provide</h3>
            <ul class="list-disc pl-6 text-gray-700 space-y-2 mb-4">
                <li><strong>Account Information:</strong> Name, email address, password, account type (volunteer/company)</li>
                <li><strong>Profile Information:</strong> Skills, experience, availability, bio, LinkedIn profile data</li>
                <li><strong>Company Information:</strong> Company name, industry, website, description, logo</li>
                <li><strong>CV/Resume:</strong> Uploaded documents for skill extraction and matching</li>
                <li><strong>Challenge Information:</strong> Challenge descriptions, requirements, deadlines</li>
                <li><strong>Communications:</strong> Messages, feedback, and support requests</li>
            </ul>

            <h3 class="text-xl font-semibold text-gray-900 mb-3">2.2 Automatically Collected Information</h3>
            <ul class="list-disc pl-6 text-gray-700 space-y-2 mb-4">
                <li>IP address and device information</li>
                <li>Browser type and version</li>
                <li>Usage data and activity logs</li>
                <li>Cookies and similar tracking technologies</li>
            </ul>

            <h3 class="text-xl font-semibold text-gray-900 mb-3">2.3 AI-Generated Data</h3>
            <ul class="list-disc pl-6 text-gray-700 space-y-2 mb-4">
                <li>Extracted skills from CV analysis</li>
                <li>Match scores and compatibility ratings</li>
                <li>Team formation recommendations</li>
                <li>Challenge decomposition and task analysis</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">3. How We Use Your Information</h2>
            <p class="text-gray-700 mb-4">We use collected information to:</p>
            <ul class="list-disc pl-6 text-gray-700 space-y-2 mb-4">
                <li>Create and manage your account</li>
                <li>Match volunteers with appropriate challenges and tasks</li>
                <li>Form optimal teams using AI algorithms</li>
                <li>Facilitate communication between users</li>
                <li>Track progress and provide analytics</li>
                <li>Improve our AI matching and team formation algorithms</li>
                <li>Send notifications about matches, invitations, and updates</li>
                <li>Provide customer support</li>
                <li>Ensure Platform security and prevent fraud</li>
                <li>Comply with legal obligations</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">4. AI Processing and Analysis</h2>
            <p class="text-gray-700 mb-4">
                We use advanced AI technology (including OpenAI's GPT-4o) to process and analyze:
            </p>
            <ul class="list-disc pl-6 text-gray-700 space-y-2 mb-4">
                <li>CV/Resume content for skill extraction</li>
                <li>Challenge descriptions for task decomposition</li>
                <li>Volunteer profiles for matching optimization</li>
                <li>Team composition for optimal collaboration</li>
            </ul>
            <p class="text-gray-700 mb-4">
                AI processing is conducted securely, and we maintain logs of API requests for quality assurance and debugging purposes.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">5. Information Sharing and Disclosure</h2>

            <h3 class="text-xl font-semibold text-gray-900 mb-3">5.1 Within the Platform</h3>
            <p class="text-gray-700 mb-4">We share information as necessary for Platform functionality:</p>
            <ul class="list-disc pl-6 text-gray-700 space-y-2 mb-4">
                <li>Volunteer profiles are visible to companies for matching purposes</li>
                <li>Company challenges are visible to matched volunteers</li>
                <li>Team members can see each other's names, roles, and assigned skills</li>
                <li>Public leaderboards display volunteer reputation scores</li>
            </ul>

            <h3 class="text-xl font-semibold text-gray-900 mb-3">5.2 Third-Party Services</h3>
            <p class="text-gray-700 mb-4">We may share data with:</p>
            <ul class="list-disc pl-6 text-gray-700 space-y-2 mb-4">
                <li><strong>OpenAI:</strong> For AI analysis and processing</li>
                <li><strong>LinkedIn:</strong> For OAuth authentication (with your consent)</li>
                <li><strong>Cloud Services:</strong> For hosting and data storage</li>
                <li><strong>Analytics Providers:</strong> For Platform improvement</li>
            </ul>

            <h3 class="text-xl font-semibold text-gray-900 mb-3">5.3 Legal Requirements</h3>
            <p class="text-gray-700 mb-4">
                We may disclose information to comply with legal obligations, enforce our Terms of Service, or protect rights and safety.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">6. Data Security</h2>
            <p class="text-gray-700 mb-4">We implement security measures including:</p>
            <ul class="list-disc pl-6 text-gray-700 space-y-2 mb-4">
                <li>Encryption of data in transit and at rest</li>
                <li>Secure password hashing</li>
                <li>Regular security audits</li>
                <li>Access controls and authentication</li>
                <li>Monitoring for suspicious activity</li>
            </ul>
            <p class="text-gray-700 mb-4">
                However, no system is completely secure. You are responsible for maintaining the security of your account credentials.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">7. Data Retention</h2>
            <p class="text-gray-700 mb-4">
                We retain your information for as long as your account is active or as needed to provide services. You may request account deletion at any time, after which we will delete or anonymize your data, except where retention is required by law.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">8. Your Rights and Choices</h2>
            <p class="text-gray-700 mb-4">You have the right to:</p>
            <ul class="list-disc pl-6 text-gray-700 space-y-2 mb-4">
                <li><strong>Access:</strong> Request a copy of your personal data</li>
                <li><strong>Correction:</strong> Update inaccurate or incomplete information</li>
                <li><strong>Deletion:</strong> Request deletion of your account and data</li>
                <li><strong>Portability:</strong> Export your data in a machine-readable format</li>
                <li><strong>Objection:</strong> Object to certain data processing activities</li>
                <li><strong>Opt-Out:</strong> Unsubscribe from marketing communications</li>
            </ul>
            <p class="text-gray-700 mb-4">
                To exercise these rights, please contact us at <a href="mailto:privacy@mindova.com" class="text-primary-600 hover:underline">privacy@mindova.com</a>
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">9. Cookies and Tracking</h2>
            <p class="text-gray-700 mb-4">
                We use cookies and similar technologies for authentication, preferences, and analytics. You can control cookie settings through your browser, though this may affect Platform functionality.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">10. Children's Privacy</h2>
            <p class="text-gray-700 mb-4">
                Our Platform is not intended for users under 18 years of age. We do not knowingly collect information from children.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">11. International Data Transfers</h2>
            <p class="text-gray-700 mb-4">
                Your information may be transferred to and processed in countries other than your own. We ensure appropriate safeguards are in place for such transfers.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">12. Changes to This Policy</h2>
            <p class="text-gray-700 mb-4">
                We may update this Privacy Policy periodically. We will notify you of significant changes through the Platform or via email. Continued use after changes indicates acceptance of the updated policy.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">13. Contact Us</h2>
            <p class="text-gray-700">
                For questions about this Privacy Policy or our data practices, contact us at:
            </p>
            <p class="text-gray-700 mt-4">
                Email: <a href="mailto:privacy@mindova.com" class="text-primary-600 hover:underline">privacy@mindova.com</a><br>
                Address: Mindova Privacy Team, [Your Address]
            </p>
        </section>
    </div>
</div>
@endsection
