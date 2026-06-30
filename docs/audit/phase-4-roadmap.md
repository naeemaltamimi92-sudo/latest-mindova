# Mindova — Phase 4: Improvement Roadmap

Prioritized by: (a) what's load-bearing for the core promise to function at all, (b) what's cheap to validate before more engineering, (c) what's purely additive polish. Reasoning is given for every item — this is not a generic backlog.

---

## Critical (blocks the core product from working or being trustable)

1. **Fix `DecomposeChallengeTasks` JSON parsing against real Claude responses.**
   *Why:* Verified broken live in this audit, and confirmed not a one-off via historical logs. This is the literal mechanism that turns a "team_execution" challenge into doable work. Nothing downstream of it (matching, assignment, submission, review, certificates) can be exercised for that path until this is fixed. Likely fix direction: the existing `sanitizeJsonString()` isn't sufficient for Claude responses containing multi-line string values; either constrain the prompt to return single-line-escaped JSON explicitly, or parse with a more permissive JSON mode, or post-process with a proper JSON-repair library instead of regex sanitization.

2. **Fix the `tasks.status = 'matching'` enum mismatch in `MatchVolunteersToTasks`.**
   *Why:* Same severity class as #1 — a straightforward schema/code mismatch silently failing the matching step. Either add `'matching'` to the `tasks.status` enum migration or stop writing that value.

3. **Add a visible failure/retry state for AI analysis.**
   *Why:* Right now, when #1 or #2 happens, the company or volunteer sees an indefinitely "analyzing" badge with no explanation. Given AI calls *will* sometimes fail (rate limits, malformed responses, network errors — not just the two specific bugs above), the product needs a user-facing "this failed, here's what to do" state, not just better-behaved jobs. This is a trust issue, not a cosmetic one — see Phase 3 §2.

4. **Decide and communicate the actual incentive model before any real company or volunteer touches this.**
   *Why:* Per Phase 3, the entire platform rests on an unvalidated assumption (reputation/certificates are motivating enough without payment). This isn't a code task, it's a go-to-market precondition — shipping more features on an unvalidated incentive model risks building the wrong product faster. Concretely: run the 10+10 conversations described in Phase 3 §5 before investing further in Phase 4 items below #6.

---

## High Priority (needed for a credible MVP/pilot, once Critical is resolved)

5. **Build the admin-promotion UI.**
   *Why:* Right now making someone an `admin` requires direct database access. That's a hard operational dependency on whoever has DB access being available — unacceptable once anyone other than the original developer needs to run support/operations.

6. **Implement `resetGroup()` for real, or remove the button.**
   *Why:* A settings action that claims to reset values to defaults but only clears cache is worse than no button — it actively misleads an admin into believing a change was made. Either implement it or relabel/remove it; shipping a stub that lies about what it did is a trust bug, same class as the AI silent-failure issue above.

7. **Build a basic moderation/spam pipeline for Work Submissions** (the `is_spam` field already exists but has no detection logic, unlike Ideas which do).
   *Why:* Once real volunteers and companies use the platform, low-effort or bad-faith submissions are inevitable, and review queues need a first-pass filter before relying entirely on company manual review.

8. **Add a basic dispute/escalation path** for rejected work and revoked certificates — even something as simple as a flagged support ticket routed to Mindova staff, given the internal admin panel already has an audit log and a (currently empty) Support permission group.
   *Why:* No-recourse rejection is fine for a free hobby platform; it's a liability the moment a company or volunteer feels wronged and has nowhere to go, which directly feeds the trust risks in Phase 3 §2.

9. **Reconcile the two admin systems** (`/admin` user-flag-based vs. `/mindova-admin` separate auth), or at minimum, document clearly which does what and why both exist.
   *Why:* This will confuse every future engineer and every new internal hire; cheap to fix or document now, expensive to untangle later once both have more functionality built on top.

---

## Medium Priority (real gaps, but the product survives a pilot without them)

10. **Build the volunteer skills self-declaration UI** to actually populate `VolunteerSkill`, and fix the dashboard so it doesn't show "No Profile Data Yet" for data that was never collectible in the first place.
    *Why:* Directly tied to the bad first-impression UX finding in Phase 1/Phase 3. Low engineering cost relative to the trust damage it's currently causing.

11. **Either activate or remove the WhatsApp notification integration.**
    *Why:* It's fully built (Meta Cloud API, opt-in checks, queued job) but triggers nowhere. Dead infrastructure that nobody maintains or tests is a future security/cost liability (an unused webhook endpoint is still an attack surface) for no current benefit. Cheapest fix: wire it into at least one real notification trigger, or remove the route/webhook until it's needed.

12. **Decide what to do with the in-app contextual guidance system.**
    *Why:* Backend-complete, frontend-absent. Either it's a near-term onboarding-improvement opportunity (cheap to finish, given the backend already exists) or dead weight that should be removed to reduce surface area. Given the bad first-dashboard-impression finding, finishing this might be one of the highest-ROI medium-priority items — a guided tour could mask some of the rough edges while #10 gets fixed.

13. **Add email verification.**
    *Why:* Currently auto-verified at registration. Fine for a closed pilot; a basic liability once NDA-bound submissions and reputation/certificates are tied to unverified email addresses in anything resembling production use.

---

## Future Features (genuinely "later," contingent on validating the core model first)

14. **Monetization layer** (whatever form the Phase 3 §5 conversations reveal is actually wanted — escrow payments, subscription access for companies, paid "boost" for visibility, sponsorship of challenges, etc.).
    *Why explicitly last, not first:* Building a payment system before knowing whether anyone wants to pay (or be paid) in this model, and before the core delivery pipeline (#1–#3) reliably works, is the most expensive possible way to learn the same lesson Phase 3 says can be learned with 20 conversations.

15. **Employer/partner recognition program for certificates** (the actual mechanism that would make a Mindova certificate worth something beyond the platform itself).
    *Why later:* Meaningless to pursue before there's a track record of completed challenges and a population of certificate holders to make the case to a first partner.

16. **Finance, Support, and Feedback-QA modules in the Mindova internal panel** (permissions already seeded, no controllers exist).
    *Why later:* These only matter once there's a monetization layer (Finance), a real support volume (Support), and a real feedback-QA pipeline worth staffing (Feedback) — building the tooling before the workload exists is premature investment.

17. **Formal dispute resolution / insurance / legal remedy layer beyond the basic escalation path in #8.**
    *Why later:* Appropriate once the platform has enough real transaction volume (of trust, if not money) that the basic ticket-based escalation in #8 becomes insufficient.

---

*This roadmap assumes Critical items are sequenced before High Priority, and that #4 (validating the incentive model) gates further investment past #9 — building more features on an unvalidated core assumption is the single biggest risk this roadmap can't engineer its way around.*
