@extends('layouts.app')

@section('title', __('Terms of Service'))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h1 class="text-4xl font-bold text-gray-900 mb-8">{{ __('Terms of Service') }}</h1>
    <p class="text-gray-600 mb-8">{{ __('Last updated:') }} {{ date('F d, Y') }}</p>

    <div class="prose max-w-none">
        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('1. Acceptance of Terms') }}</h2>
            <p class="text-gray-700 mb-4">
                {{ __('By accessing and using Mindova ("the Platform"), you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to these Terms of Service, please do not use the Platform.') }}
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('2. Description of Service') }}</h2>
            <p class="text-gray-700 mb-4">
                {{ __('Mindova is an AI-powered collaboration platform that connects volunteers with companies to solve real-world challenges. The Platform provides:') }}
            </p>
            <ul class="list-disc pl-6 text-gray-700 space-y-2 mb-4">
                <li>{{ __('Challenge posting and management tools for companies') }}</li>
                <li>{{ __('AI-powered task decomposition and volunteer matching') }}</li>
                <li>{{ __('Team formation and collaboration features') }}</li>
                <li>{{ __('Progress tracking and analytics') }}</li>
                <li>{{ __('Reputation and portfolio building for volunteers') }}</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('3. User Accounts') }}</h2>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('3.1 Account Types') }}</h3>
            <p class="text-gray-700 mb-4">
                {{ __('The Platform offers two types of accounts:') }}
            </p>
            <ul class="list-disc pl-6 text-gray-700 space-y-2 mb-4">
                <li><strong>{{ __('Volunteer Accounts:') }}</strong> {{ __('For individuals contributing skills and time to challenges') }}</li>
                <li><strong>{{ __('Company Accounts:') }}</strong> {{ __('For organizations posting challenges and seeking solutions') }}</li>
            </ul>

            <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('3.2 Account Security') }}</h3>
            <p class="text-gray-700 mb-4">
                {{ __('You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account. You agree to notify us immediately of any unauthorized use of your account.') }}
            </p>

            <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('3.3 Account Termination') }}</h3>
            <p class="text-gray-700 mb-4">
                {{ __('We reserve the right to suspend or terminate accounts that violate these Terms of Service or engage in fraudulent, abusive, or illegal activities.') }}
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('4. User Conduct') }}</h2>
            <p class="text-gray-700 mb-4">{{ __('Users agree to:') }}</p>
            <ul class="list-disc pl-6 text-gray-700 space-y-2 mb-4">
                <li>{{ __('Provide accurate and truthful information') }}</li>
                <li>{{ __('Respect intellectual property rights') }}</li>
                <li>{{ __('Maintain professional and respectful communication') }}</li>
                <li>{{ __('Not engage in spam, harassment, or malicious activities') }}</li>
                <li>{{ __('Not misuse or attempt to manipulate the AI matching system') }}</li>
                <li>{{ __('Complete accepted tasks and commitments in good faith') }}</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('5. Volunteer Responsibilities') }}</h2>
            <p class="text-gray-700 mb-4">{{ __('Volunteers agree to:') }}</p>
            <ul class="list-disc pl-6 text-gray-700 space-y-2 mb-4">
                <li>{{ __('Accurately represent their skills and experience') }}</li>
                <li>{{ __('Accept only tasks they can reasonably complete') }}</li>
                <li>{{ __('Communicate availability and capacity honestly') }}</li>
                <li>{{ __('Deliver work that meets agreed-upon standards') }}</li>
                <li>{{ __('Respect confidentiality of challenge information') }}</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('6. Company Responsibilities') }}</h2>
            <p class="text-gray-700 mb-4">{{ __('Companies agree to:') }}</p>
            <ul class="list-disc pl-6 text-gray-700 space-y-2 mb-4">
                <li>{{ __('Provide clear and accurate challenge descriptions') }}</li>
                <li>{{ __('Set realistic expectations and deadlines') }}</li>
                <li>{{ __('Respect volunteer contributions and efforts') }}</li>
                <li>{{ __('Provide timely feedback and communication') }}</li>
                <li>{{ __('Not request illegal or unethical work') }}</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('7. Intellectual Property') }}</h2>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('7.1 Platform Content') }}</h3>
            <p class="text-gray-700 mb-4">
                {{ __('All Platform content, including but not limited to text, graphics, logos, and software, is the property of Mindova and protected by intellectual property laws.') }}
            </p>

            <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('7.2 User Content') }}</h3>
            <p class="text-gray-700 mb-4">
                {{ __('Users retain ownership of content they create. By posting challenges or submitting work, you grant Mindova a license to display and distribute this content through the Platform.') }}
            </p>

            <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ __('7.3 Work Product') }}</h3>
            <p class="text-gray-700 mb-4">
                {{ __('Ownership of work product created through the Platform should be agreed upon between companies and volunteers. We recommend establishing clear IP agreements before commencing work.') }}
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('8. AI Technology') }}</h2>
            <p class="text-gray-700 mb-4">
                {{ __('Our Platform uses AI technology to analyze challenges, match volunteers, and form teams. While we strive for accuracy, AI-generated recommendations are not guaranteed to be perfect. Users should exercise their own judgment when accepting matches and assignments.') }}
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('9. Limitation of Liability') }}</h2>
            <p class="text-gray-700 mb-4">
                {{ __('Mindova is provided "as is" without warranties of any kind. We are not liable for:') }}
            </p>
            <ul class="list-disc pl-6 text-gray-700 space-y-2 mb-4">
                <li>{{ __('Quality of work delivered through the Platform') }}</li>
                <li>{{ __('Disputes between users') }}</li>
                <li>{{ __('Loss of data or content') }}</li>
                <li>{{ __('Service interruptions or technical issues') }}</li>
                <li>{{ __('Accuracy of AI-generated matches and recommendations') }}</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('10. Dispute Resolution') }}</h2>
            <p class="text-gray-700 mb-4">
                {{ __('Any disputes arising from these Terms shall be resolved through binding arbitration in accordance with applicable laws. Users agree to resolve disputes individually and waive the right to participate in class actions.') }}
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('11. Changes to Terms') }}</h2>
            <p class="text-gray-700 mb-4">
                {{ __('We reserve the right to modify these Terms at any time. Users will be notified of significant changes, and continued use of the Platform constitutes acceptance of modified Terms.') }}
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('12. Contact Information') }}</h2>
            <p class="text-gray-700">
                {{ __('For questions about these Terms of Service, please contact us at:') }}
                <a href="mailto:legal@mindova.com" class="text-primary-600 hover:underline">legal@mindova.com</a>
            </p>
        </section>
    </div>

    <div class="mt-12 border-t pt-8">
        <p class="text-sm text-gray-600">
            {{ __('By using Mindova, you acknowledge that you have read, understood, and agree to be bound by these Terms of Service.') }}
        </p>
    </div>
</div>
@endsection
