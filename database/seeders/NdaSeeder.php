<?php

namespace Database\Seeders;

use App\Models\NdaAgreement;
use Illuminate\Database\Seeder;

class NdaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create General NDA
        NdaAgreement::create([
            'title' => 'Mindova Platform General Non-Disclosure Agreement',
            'type' => 'general',
            'version' => '1.0',
            'is_active' => true,
            'effective_date' => now(),
            'content' => $this->getGeneralNdaContent(),
        ]);

        // Create Challenge-Specific NDA Template
        NdaAgreement::create([
            'title' => 'Mindova Challenge-Specific Non-Disclosure Agreement',
            'type' => 'challenge_specific',
            'version' => '1.0',
            'is_active' => true,
            'effective_date' => now(),
            'content' => $this->getChallengeNdaContent(),
        ]);
    }

    /**
     * Get the general NDA content.
     */
    private function getGeneralNdaContent(): string
    {
        return <<<'EOT'
MINDOVA PLATFORM GENERAL NON-DISCLOSURE AGREEMENT

This Non-Disclosure Agreement ("Agreement") is entered into as of the date of electronic signature below ("Effective Date") by and between:

MINDOVA PLATFORM ("Disclosing Party")
- A platform facilitating collaboration between companies and volunteers for challenge-based innovation

AND

THE VOLUNTEER ("Receiving Party")
- The individual registering on the Mindova platform

WHEREAS, the Receiving Party desires to participate in challenges, projects, and activities on the Mindova Platform that may involve access to confidential and proprietary information;

NOW, THEREFORE, in consideration of the mutual covenants and agreements contained herein, the parties agree as follows:

1. DEFINITION OF CONFIDENTIAL INFORMATION

"Confidential Information" means any and all information disclosed by or on behalf of companies using the Mindova Platform, including but not limited to:
   a) Business plans, strategies, and methodologies
   b) Technical data, designs, specifications, and processes
   c) Research and development information
   d) Financial information and projections
   e) Customer and supplier information
   f) Marketing plans and strategies
   g) Software, algorithms, and source code
   h) Trade secrets and proprietary information
   i) Any information marked as "Confidential" or that should reasonably be understood to be confidential

2. OBLIGATIONS OF RECEIVING PARTY

The Receiving Party agrees to:
   a) Hold all Confidential Information in strict confidence
   b) Not disclose Confidential Information to any third party without prior written consent
   c) Use Confidential Information solely for the purpose of participating in Mindova Platform challenges
   d) Protect Confidential Information with the same degree of care used to protect their own confidential information, but no less than reasonable care
   e) Limit access to Confidential Information to only those individuals who need to know for authorized purposes

3. TERM

This Agreement shall remain in effect for the duration of the Receiving Party's participation on the Mindova Platform and shall continue for a period of five (5) years following the termination of such participation.

4. RETURN OF MATERIALS

Upon request or upon termination of participation on the Mindova Platform, the Receiving Party shall promptly return or destroy all Confidential Information and certify in writing that such return or destruction has been completed.

5. NO LICENSE

Nothing in this Agreement grants the Receiving Party any license or right to use any Confidential Information except as expressly permitted herein.

6. LEGAL COMPLIANCE

The Receiving Party agrees to comply with all applicable laws and regulations in connection with their use of Confidential Information.

7. GOVERNING LAW

This Agreement shall be governed by and construed in accordance with the laws of the jurisdiction in which Mindova Platform operates.

BY ELECTRONICALLY SIGNING THIS AGREEMENT, THE RECEIVING PARTY ACKNOWLEDGES THAT THEY HAVE READ, UNDERSTOOD, AND AGREE TO BE BOUND BY ITS TERMS AND CONDITIONS.

Digital Signature Information:
- IP Address: [Recorded at signing]
- Timestamp: [Recorded at signing]
- Signature Hash: [Generated for verification]
EOT;
    }

    /**
     * Get the challenge-specific NDA content.
     */
    private function getChallengeNdaContent(): string
    {
        return <<<'EOT'
MINDOVA CHALLENGE-SPECIFIC NON-DISCLOSURE AGREEMENT

This Challenge-Specific Non-Disclosure Agreement ("Agreement") is entered into as of the date of electronic signature below ("Effective Date") by and between:

THE COMPANY (Challenge Owner)
- The organization that has submitted a specific challenge to the Mindova Platform

AND

THE VOLUNTEER ("Receiving Party")
- The individual who has been invited to participate in the challenge

WHEREAS, the Receiving Party has been selected to participate in a specific challenge that involves access to highly confidential and proprietary information belonging to the Company;

NOW, THEREFORE, in consideration of being granted access to the challenge details and the opportunity to participate, the parties agree as follows:

1. CHALLENGE-SPECIFIC CONFIDENTIAL INFORMATION

In addition to the General NDA already signed, "Challenge Confidential Information" specifically includes:
   a) Detailed challenge brief and objectives
   b) Company-specific technical requirements
   c) Proprietary methodologies and processes related to the challenge
   d) Research data and findings specific to the challenge
   e) Budget and resource allocation information
   f) Timeline and milestone details
   g) Evaluation criteria and scoring mechanisms
   h) Team composition and collaboration details
   i) All submissions, ideas, and solutions developed during the challenge
   j) Any additional custom terms specified by the Company

2. ENHANCED CONFIDENTIALITY OBLIGATIONS

The Receiving Party agrees to:
   a) Maintain absolute confidentiality regarding all Challenge Confidential Information
   b) Not discuss the challenge or its details with anyone outside the assigned team
   c) Not use Challenge Confidential Information for any purpose other than completing the assigned challenge
   d) Not reverse engineer, decompile, or attempt to derive any proprietary information
   e) Immediately report any suspected breach of confidentiality
   f) Not make copies of Challenge Confidential Information without explicit authorization

3. CONFIDENTIALITY LEVEL

This challenge has been classified with the following confidentiality level:
[CONFIDENTIALITY_LEVEL: To be specified at signing]

Additional custom terms for this challenge:
[CUSTOM_TERMS: To be specified if applicable]

4. INTELLECTUAL PROPERTY

   a) All intellectual property rights in the Challenge Confidential Information remain with the Company
   b) Any work product, ideas, or solutions created during the challenge shall be subject to separate IP agreements
   c) The Receiving Party acknowledges that they acquire no rights to the Company's intellectual property through participation

5. NON-CIRCUMVENTION

The Receiving Party agrees not to:
   a) Contact the Company directly regarding the challenge outside of the Mindova Platform
   b) Attempt to establish a separate business relationship with the Company based on Challenge Confidential Information
   c) Solicit other volunteers or team members for projects outside the Mindova Platform related to the challenge

6. TERM AND SURVIVAL

This Agreement shall remain in effect for:
   a) The duration of the challenge
   b) A period of seven (7) years following the completion or termination of the challenge
   c) Indefinitely for information that constitutes a trade secret under applicable law

7. REMEDIES

The Receiving Party acknowledges that:
   a) Breach of this Agreement may cause irreparable harm to the Company
   b) The Company shall be entitled to seek injunctive relief in addition to any other remedies available at law or in equity
   c) The Receiving Party may be liable for damages resulting from any breach

8. AGREEMENT BINDING

This Agreement is legally binding and enforceable. The Receiving Party has had the opportunity to review these terms and seek legal counsel if desired before signing.

BY ELECTRONICALLY SIGNING THIS AGREEMENT, THE RECEIVING PARTY ACKNOWLEDGES THAT THEY HAVE READ, UNDERSTOOD, AND AGREE TO BE BOUND BY ITS TERMS AND CONDITIONS, AND THAT THEY ACCEPT FULL RESPONSIBILITY FOR MAINTAINING THE CONFIDENTIALITY OF ALL CHALLENGE-SPECIFIC INFORMATION.

Digital Signature Information:
- Challenge ID: [Recorded at signing]
- IP Address: [Recorded at signing]
- Timestamp: [Recorded at signing]
- Signature Hash: [Generated for verification]
- Confidentiality Level: [Recorded at signing]
EOT;
    }
}
