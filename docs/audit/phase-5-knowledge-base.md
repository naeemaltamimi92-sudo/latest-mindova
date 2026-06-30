# Mindova — Knowledge Base (Official Reference Document)

*Compiled from direct platform exploration and source code review. Last updated 2026-06-23. Anything not directly observed in the running app or the codebase is explicitly labeled **[ASSUMPTION]**. See [Phase 1](./phase-1-platform-exploration.md), [Phase 2](./phase-2-product-analysis.md), [Phase 3](./phase-3-brutal-critique.md), and [Phase 4](./phase-4-roadmap.md) for the full supporting analysis behind this summary.*

---

## Executive Summary

Mindova is a Laravel 12 web platform connecting **companies** that have unsolved problems ("Challenges") with **volunteers** — independent experts — who solve them, with an AI layer (Anthropic Claude) used to triage, classify, score, and (when working) decompose challenges into assignable tasks. The platform's currency is reputation, leaderboard standing, and publicly-verifiable PDF certificates — there is currently **no payment, escrow, or payout mechanism anywhere in the system**. This makes Mindova, as built today, structurally closer to an innovation-challenge / portfolio-building platform than a paid freelancing marketplace.

The product is a strong alpha: authentication, onboarding, NDA signing, the challenge data model, and the brief-analysis AI step all work as verified by live testing. The flagship "AI decomposes a complex challenge into tasks" workflow does not currently work reliably against real AI responses (verified live, reproducible, not isolated to this test). The platform's biggest open question is not a feature gap but an unvalidated assumption: whether reputation and certificates, with no monetary incentive, are sufficient to sustain a two-sided marketplace.

## Product Vision

Per the platform's own marketing copy (`Design.md`): *"Where human expertise meets AI innovation — a platform that transforms how the world solves problems together."* The stated three-step model shown on the public site is: **Submit Your Challenge → AI Matches Experts → Collaborate & Solve**, supported by "built-in tools, NDAs, and progress tracking." [The marketing copy's specific numbers — "2,486 Active Projects," "4,953 Expert Solvers," "86 Countries," "50K+ Challenges Solved," "$2.4B Value Generated" — are static design-spec placeholder content, not live platform statistics; the actual database contains a small number of seed/dev records, not evidence these figures are real.]

## Problem Statement

- **Companies** often have problems — research questions, product/UX issues, open-ended innovation challenges — that don't justify a full hire, a slow agency engagement, or don't need a contractually binding deliverable, but would benefit from outside expert attention.
- **Independent experts (volunteers)**, particularly students and early-career professionals, want real problems to work on, a way to build a public, verifiable track record, and recognition — without necessarily needing immediate payment for every engagement.
- Matching the right expertise to the right problem, and decomposing complex problems into tractable pieces, is slow to do manually — Mindova's premise is that AI can do this faster and more consistently than human triage.

## Solution

Mindova provides:
1. A **structured challenge submission and AI-classification pipeline** that scores complexity (1–10) and routes a challenge down one of two paths:
   - **Team Execution** (complex): AI decomposes the brief into Workstreams and Tasks, which get AI-matched to individual volunteers who accept, work, submit, and get reviewed.
   - **Community Discussion** (lighter/open-ended): volunteers in the matching expertise field post "Ideas," the community votes, AI scores quality, and the challenge owner picks a winning idea.
2. A **trust layer**: general (platform-wide) and per-challenge customized NDAs, with signature hashing and IP/timestamp capture, gating access to sensitive challenge content.
3. A **reputation and recognition system**: a reputation-point ledger (`ReputationHistory`), a 4-dimension leaderboard (reputation / tasks completed / hours / idea quality), and publicly-verifiable PDF certificates issued per completed challenge, with AI-written contribution summaries.
4. **Operational infrastructure** for Mindova itself: a company-facing-style `/admin` panel (platform stats, challenge/company/volunteer management, 140+ feature-flag settings) and a fully separate `/mindova-admin` internal staff system (its own auth, roles/permissions, audit logging) for managing Mindova's own team.

## Platform Workflow

```
Visitor → Register (volunteer or company) → Auto-login
   │
   ├─ Volunteer: Complete Profile (field, availability, bio, CV)
   │              → Sign General NDA (mandatory)
   │              → Dashboard
   │
   └─ Company: Complete Profile (company name, industry, website, logo)
                → Dashboard (no NDA required)
```
From the dashboard, volunteers browse Tasks (team_execution) or the Community feed (community_discussion) and build assignments/ideas; companies create Challenges, review submissions, view analytics, and (after completion) issue certificates. Public, unauthenticated visitors can browse marketing pages and verify a certificate by number at any time.

## AI Workflow

```
Challenge submitted (status: submitted)
   → AnalyzeChallengeBrief (Claude): refines brief, sets `field`, sets complexity `score` (1–10)
   → EvaluateChallengeComplexity (Claude): sets `challenge_type`
        score ≥ 3 → team_execution
        score ≤ 2 → community_discussion
   ─── team_execution path ───
   → DecomposeChallengeTasks (Claude): generates Workstreams + Tasks
       [Status: verified unreliable against real API responses as of this audit — see Phase 1 §7]
   → MatchVolunteersToTasks (Claude): scores and invites candidate volunteers per task
       [Status: verified failing due to a database enum mismatch — see Phase 1 §7]
   ─── community_discussion path ───
   → Volunteer posts Idea → ScoreIdea (Claude): scores quality/feasibility/relevance (0–100)
   → Community up/downvotes; owner marks one idea "correct" to close the challenge
```
Additional AI-touched areas confirmed in code but not independently re-verified live in this audit: solution/submission quality scoring exists as a service but is **deliberately disabled** (commented out — all work-submission review is currently manual); CV analysis, team formation, and comment-quality scoring services exist with no confirmed call site (likely unused scaffolding).

## Community Workflow

```
Challenge classified community_discussion (low complexity score, or volunteer-submitted)
   → Appears in /community, visible only to volunteers whose `field` matches the challenge's field
   → Volunteers submit Ideas (100–2000 characters)
   → AI scores each idea; community casts up/down votes (self-voting blocked)
   → Ideas ranked by net votes (AI score weighting described in product copy as "40% AI + 60% votes,"
      though the exact blended formula was not found explicitly implemented in the reviewed code)
   → Challenge owner (company or the volunteer who originally posted it) marks one idea "Correct"
   → Challenge closes; the winning idea's author receives +50 reputation points and a notification
```
Volunteers can also submit their own problems directly to the community feed (not just respond to company challenges) via "My Challenges" — these go through the identical AI classification pipeline.

## Company Workflow

```
Company → Create Challenge (title + 100-5000 char description, optional PDF attachments)
   → AI analysis runs automatically (see AI Workflow above)
   → Company monitors Challenge Show page (tabs: overview / tasks / ideas / teams / comments / attachments)
   → If team_execution: Company reviews incoming Work Submissions
        → Company decision: approve (with quality/timeliness/communication scores) | request revision | reject
        → Approval awards the volunteer tiered reputation points and may trigger challenge completion
   → Company views Challenge Analytics (completion rate, contributor leaderboard, top ideas, participation, timeline)
   → On completion: Company opens the Certificate Confirmation Form, selects contributing volunteers,
     and the platform generates a PDF certificate per volunteer with an AI-written contribution summary
```
Companies do not sign NDAs (NDA enforcement applies only to volunteers); they cannot edit a challenge once it has active or completed tasks, and deleting a challenge cascades to all of its workstreams, tasks, analyses, attachments, and comments.

## Future Expansion Strategy

Per the [Phase 4 Roadmap](./phase-4-roadmap.md), the recommended sequencing is:

1. **Stabilize the AI pipeline** (task decomposition and volunteer matching) before any further go-to-market investment — this is the platform's core differentiator and it does not currently work reliably.
2. **Validate the incentive model directly with prospective companies and volunteers** before building further on top of the reputation-only system — this is the single largest unproven assumption underlying the entire product (see [Phase 3](./phase-3-brutal-critique.md)).
3. **Close operational gaps** that block a credible pilot: admin-promotion UI, real settings-reset behavior, basic submission moderation, a minimal dispute/escalation path, and reconciling the two parallel admin systems.
4. **Only after the above**, pursue: a monetization layer (shape to be determined by what step 2 reveals companies/volunteers actually want), an employer/partner recognition program to give certificates external credibility, and staffing out the Finance/Support/Feedback-QA modules whose permissions already exist in the internal admin system but have no functionality behind them yet.

The platform's long-term differentiation case rests on two things actually being true: that AI-driven task decomposition and matching can outperform manual proposal-browsing (a solvable engineering problem), and that a non-monetary reputation economy can sustain meaningful two-sided engagement (an unsolved, unvalidated business-model question that no amount of further engineering will answer on its own).

---

*This document, together with Phases 1–4, constitutes the complete Phase 1–5 deliverable set requested for the Mindova platform audit. No presentation has been created, per instruction — this knowledge base is the reference document from which any future presentation should be built, once reviewed and approved.*
