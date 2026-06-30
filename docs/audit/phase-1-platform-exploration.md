# Mindova — Phase 1: Platform Exploration

**Method:** This document is grounded in (a) live, hands-on testing against a running local instance — real account registration, profile completion, NDA signing, and a real company challenge submission processed through the actual AI pipeline — and (b) direct reading of every relevant controller, model, middleware, and Blade view in the codebase. Nothing here is invented. Anything inferred rather than directly observed is labeled **[ASSUMPTION]**.

---

## 1. Platform Overview

Mindova is a Laravel 12 marketplace that connects **companies** (who have problems) with **volunteers** (independent experts who solve them), wrapped in an AI layer that classifies, scores, and routes work. Tagline (from `Design.md`): *"Where human expertise meets AI innovation."*

Two work modes exist for a posted problem ("Challenge"):
- **`team_execution`** — complex challenges get AI-decomposed into Workstreams → Tasks, matched to individual volunteers, who get assigned, accept/decline, work, submit, and get reviewed.
- **`community_discussion`** — lighter, open-ended challenges where any volunteer in the matching field can post an "Idea" (a proposed solution/comment), the community/AI scores it, and the challenge owner picks a winner.

The AI classification (which mode a challenge becomes, and its complexity score 1–10) happens automatically via a real Anthropic Claude API integration — confirmed live in testing (see §7, Verified Defects).

There are **three separate user/admin systems**, which is an important architectural fact:
1. Regular platform users (`users` table): `user_type` = `volunteer` | `company` | `admin`.
2. `/admin/*` — a company-facing-looking but actually platform-staff panel, gated by `user_type = 'admin'` on the same `users` table.
3. `/mindova-admin/*` — a **fully separate** internal staff backend with its own session-based auth, own tables (`mindova_team_members`, `mindova_roles`, `mindova_permissions`, `mindova_audit_logs`), used for managing Mindova's own employees, roles, and audit trail. Not connected to Laravel's auth guards at all.

---

## 2. User Types

| Type | Table/flag | How created | Primary purpose |
|---|---|---|---|
| **Volunteer** | `users.user_type='volunteer'` + `volunteers` row | Self-registration or LinkedIn OAuth (default for new OAuth users) | Browse/solve challenges, submit ideas, get assigned tasks, build reputation, earn certificates |
| **Company** | `users.user_type='company'` + `companies` row | Self-registration | Post challenges, review submissions, issue certificates, view analytics |
| **Admin** | `users.user_type='admin'` | **No UI exists to create one** — must be set directly in the database/tinker [confirmed in code: no promotion endpoint] | Platform-wide oversight: challenges, companies, volunteers, site settings |
| **Mindova staff** | `mindova_team_members` (separate table, 5 roles: Owner, Admin, Accounting, Support, Feedback-QA) | First-run `/mindova-admin/setup` creates the Owner (requires a setup key); Owner invites others | Internal business operations — team management, audit logs. **Finance, Support, and Feedback modules have permissions defined but no controllers — they don't exist yet.** |

Registration auto-verifies email (`email_verified_at = now()` immediately, no confirmation email) and auto-logs the user in.

---

## 3. Main Workflows (verified live)

### 3.1 Volunteer onboarding (tested end-to-end)
`Register (user_type=volunteer)` → `Complete Profile` (field, weekly availability, bio, optional CV) → **mandatory** `Sign General NDA` → `Dashboard`.
A volunteer cannot reach the dashboard, browse tasks, or accept assignments until the NDA is signed (enforced by `EnsureGeneralNdaSigned` middleware on tasks/assignments routes).

### 3.2 Company onboarding (tested end-to-end)
`Register (user_type=company)` → `Complete Profile` (company name, industry, website, description, logo) → `Dashboard` directly (no NDA requirement for companies).

### 3.3 Challenge lifecycle (tested live against the real AI pipeline)
1. Company submits a challenge (title + 100–5000 char description) → status `submitted`.
2. **`AnalyzeChallengeBrief` job** (Claude API) refines the brief, extracts objectives/constraints/risks → sets `field` (e.g. "UX Design"), produces a `score` 1–10.
3. **`EvaluateChallengeComplexity` job** sets `challenge_type`: score ≥3 → `team_execution`; score 1–2 → `community_discussion`.
4. If `team_execution`: **`DecomposeChallengeTasks` job** should break the brief into Workstreams/Tasks. **In live testing this job failed** (see §7).
5. If `community_discussion`: challenge appears in `/community`, scoped to volunteers whose `field` matches, who post Ideas/comments.
6. Tasks (team_execution) get auto-matched to volunteers (`MatchVolunteersToTasks` job, AI-scored), who are invited → accept/decline → start → submit work → company reviews (approve/revision/reject) → reputation awarded → all-tasks-complete triggers challenge completion → company can issue certificates.
7. Ideas (community_discussion) get AI-scored, community-voted; owner can "Mark Correct," which closes the challenge, flags the winning idea, and awards the author 50 reputation points.

### 3.4 NDA enforcement
General NDA (volunteer-wide) gates Tasks/Assignments. A second, per-challenge NDA (with content customized per challenge's confidentiality level) additionally gates viewing a specific challenge if that challenge is flagged `requires_nda`.

### 3.5 Certification
After a challenge is completed, a company can open a confirmation form, select contributing volunteers, and the platform generates a PDF certificate per volunteer (via `laravel-dompdf`, with an AI-written contribution summary). Certificates carry a public verification number (format `MDVA-YYYY-XXXXXX`) checkable at `/certificates/verify` with **no login required**. Admins/owners can revoke (with a reason); admins can regenerate the PDF.

---

## 4. Feature Inventory

### Public / Marketing (all return HTTP 200, static content, no auth)
Home, How It Works, Success Stories, Help, Guidelines, API Docs, Blog, About, Contact, Privacy, Terms, Features, Pricing, Security, Integrations, Changelog, Careers, Press, Partners, Documentation, Maintenance page.

### Auth & Account
Login (rate-limited 5 attempts/email+IP), Register, LinkedIn OAuth login/signup, Forgot/Reset Password, **TOTP 2FA** (Google Authenticator-compatible, QR enrollment, 8 single-use recovery codes), Logout. **WhatsApp is not used for 2FA** despite WhatsApp infrastructure existing elsewhere.

### Onboarding
Complete Profile (volunteer/company branches), General NDA signing, Challenge-specific NDA signing (with AJAX status check).

### Challenges (company-authored)
List/My Challenges, Create, Edit (blocked once tasks are active/completed), Delete (cascades workstreams/tasks/analyses/attachments/comments), Show (tabbed: overview/tasks/ideas/teams/comments/attachments), Analytics (completion rate, contributor leaderboard, top ideas, participation, timeline), PDF attachments (upload/list/download/delete, 10MB cap).

### Challenges (volunteer-authored, "My Challenges")
Volunteers can submit their own problems to the community feed; same AI pipeline; volunteer can edit/delete/track AI-analysis status via polling.

### Community
Field-scoped challenge feed, challenge detail with AI-scored Ideas sorted by net votes, idea/comment voting (toggle up/down, self-vote blocked), "Mark Correct Answer" (closes challenge, awards reputation).

### Tasks / Assignments (team_execution path)
Browse open tasks (skill/complexity filters), task detail with full assignment lifecycle UI, "My Assignments" dashboard grouped by status (invited/accepted/in_progress/completed/declined) with AI match-score rings and reasoning.

### Teams
Auto-formed teams for multi-volunteer tasks, skills-coverage visualization, accept/decline invitation, **live team chat** (5-second polling, not websockets), performance metrics per member, "My Teams" list.

### Work Submission & Review
Volunteer submits description/hours/deliverable URL/attachments; company reviews with decision (approve/revision/reject) + quality/timeliness/communication scores + feedback; approval awards tiered reputation (5–20 pts by quality score); spam flag field exists on submissions but **no detection logic runs**.

### Certificates
Confirmation form (company selects volunteers + cert type), AI-written contribution summary, PDF generation, public verification (no login), revoke (admin/owner), regenerate (admin only).

### Reputation & Leaderboard
`ReputationHistory` audit trail (50 starting points; awards from submission approval, idea "mark correct," etc.). Leaderboard ranks top 50 volunteers by 4 switchable filters: reputation, completed tasks, hours, average idea quality. Top 6 companies by challenge count shown alongside.

### Profile / Security
Tabbed profile editor (Profile/Account/Security), password change, full 2FA management (enable/confirm/disable/regenerate recovery codes), public volunteer/company profile pages.

### Notifications
In-app notifications (read/unread) — actively used on idea scoring, submission approval/revision, task completion. **WhatsApp notification infrastructure is fully built (Meta Cloud API, opt-in checks, queued job) but not triggered anywhere in the live user flows** — effectively dormant.

### Admin Panel (`/admin/*`, `user_type='admin'`)
Dashboard (platform stats, notifications feed), Challenges (filterable/searchable index, detail with full admin audit log, analytics, CSV/JSON export, delete/bulk-delete with mandatory reason), Companies (index/detail), Volunteers (index/detail, raw-text email sending — no template system), Settings (140+ keys across 22 groups: general, branding, AI, gamification, community, certificates, security, legal, performance, developer, etc. — with toggle/preset/import/export/search/bulk-update, but **`resetGroup()` only clears cache, it doesn't actually reset values** — a stub).

### Mindova Internal Staff Panel (`/mindova-admin/*`, separate auth system)
First-run Owner setup (setup-key gated), forced password change on first login, Team management (Owner-only invite/edit/deactivate/delete/resend), 5 roles with a real permission matrix, Audit log (9 logged action types, IP/user-agent capture, CSV export). Dashboard pulls live counts from the main platform (`User`, `Company` totals). **Finance, Support, and Feedback/QA sections have permissions seeded in the database but no controllers/routes/views exist for them — they are placeholders.**

### AI Integration (confirmed live, runs on real Claude API calls)
- Challenge brief analysis & complexity scoring — **working**, verified live.
- Idea scoring (community discussions) — wired end-to-end per code; not independently re-verified live in this pass.
- Volunteer-to-task matching — wired; triggered on decline.
- Task decomposition (team_execution) — **verified live and currently broken** (see §7).
- Solution/work-submission AI quality scoring — **deliberately disabled in code** (the dispatch call is commented out; review is fully manual).
- CV analysis, team formation, comment scoring — service classes exist but no confirmed trigger point found in any controller (likely unused/abandoned scaffolding).

### Onboarding/Guidance tooltip system
Backend models, services, and API endpoints exist (`InAppGuide`, `ContextualGuidance`, dismiss/reset/status endpoints) but **no Blade view or frontend JS was found consuming them** — this looks backend-complete, frontend-never-wired.

### Misc
In-app bug reporting (emails the owner, fully wired), database-backed i18n (English + Arabic, with file⇄DB sync), `VolunteerSkill` model (skills taxonomy) with **no controller anywhere** — schema-only, unused.

---

## 5. Navigation Map

```
Public (no auth)
├── / (home → redirects to /dashboard if logged in)
├── /how-it-works, /success-stories, /help, /guidelines, /api-docs, /blog,
│   /about, /contact, /privacy, /terms, /features, /pricing, /security,
│   /integrations, /changelog, /careers, /press, /partners, /documentation
├── /login, /register, /forgot-password, /reset-password/{token}
├── /auth/linkedin/redirect, /auth/linkedin/callback
├── /certificates/verify   (public certificate lookup)
└── /maintenance

Authenticated (auth middleware)
├── /complete-profile  → /nda/general (volunteer) → /dashboard
├── /dashboard
├── /challenges  ├── /create  ├── /{id}  ├── /{id}/edit  ├── /{id}/analytics
│                └── /{id}/attachments, /{id}/confirm-completion
├── /community  → /community/{challenge}  (comment/vote/mark-correct)
├── /my-challenges  (volunteer-submitted challenges)
├── /ideas/{idea}
├── /tasks/available → /tasks/{task}                [requires general NDA]
├── /assignments  (accept/decline/start/submit-solution)  [requires general NDA]
├── /teams/{team}  (accept/decline, chat)  /my-teams
├── /company/submissions → /company/submissions/{id}   (company review queue)
├── /certificates → /certificates/{id} (show/download/revoke/regenerate)
├── /leaderboard
├── /volunteers/{id}, /companies/{id}  (public profiles)
├── /profile  (tabs: profile / account / security)
├── /security/* (2FA + password endpoints)
├── /settings/notifications
└── /bug-reports

/admin/*  (auth + user_type=admin)
├── /admin/dashboard
├── /admin/challenges  (index/show/analytics/export/bulk-delete)
├── /admin/companies, /admin/volunteers (+ send-email)
└── /admin/settings  (140+ keys, presets, import/export)

/mindova-admin/*  (separate internal auth — Owner/Team)
├── /mindova-admin/setup  (first-run only)
├── /mindova-admin/login, /password/change
├── /mindova-admin/dashboard
├── /mindova-admin/team  (Owner-only CRUD)
└── /mindova-admin/audit  (+ export)
```

---

## 6. Workflow Diagrams

### 6.1 Challenge lifecycle (team_execution path)
```
Company creates challenge
        │
        ▼
   status: submitted ──► AnalyzeChallengeBrief (AI) ──► EvaluateChallengeComplexity (AI)
        │                                                        │
        │                                              score ≥3 → team_execution
        │                                              score ≤2 → community_discussion
        ▼
  DecomposeChallengeTasks (AI) ──► Workstreams + Tasks created
        │
        ▼
  MatchVolunteersToTasks (AI) ──► TaskAssignment created, status=invited
        │
   accept ──────────────► decline → back to matching pool
        │
        ▼
   accepted → start → in_progress → submit-solution → submitted
                                                            │
                                          ┌─────────────────┼─────────────────┐
                                          ▼                 ▼                 ▼
                                      approved       revision_requested    rejected
                                          │                 │
                              reputation awarded      back to in_progress
                              all tasks done? → Task/Challenge completed
                                          │
                                          ▼
                              Company issues Certificates (manual action)
```

### 6.2 Challenge lifecycle (community_discussion path)
```
Challenge classified as community_discussion (score 1-2, or volunteer-submitted)
        │
        ▼
  Appears in /community, scoped to volunteers with matching `field`
        │
        ▼
  Volunteers post Ideas (100-2000 chars) ──► ScoreIdea (AI: quality/feasibility/relevance, 0-100)
        │
        ▼
  Community upvotes/downvotes ideas (final ranking ≈ 40% AI + 60% votes, per UI copy —
  exact blended formula not found implemented in code)
        │
        ▼
  Owner clicks "Mark Correct" on an idea ──► challenge.status = completed, +50 reputation to author
```

### 6.3 Volunteer task-assignment state machine
```
invited → accepted → in_progress → submitted → completed
   │                                   │
   └─► declined                       ├─► revision_requested → (back to in_progress)
                                       └─► rejected (terminal, no reputation)
```

---

## 7. Verified Defects (observed live, not theoretical)

These were reproduced by actually running the app, not inferred from reading code:

1. **Task decomposition is broken for real AI responses.** Submitting a real challenge and letting it flow through the live Claude pipeline, `DecomposeChallengeTasks` failed with `Failed to parse JSON response: Control character error, possibly incorrectly encoded` (`AnthropicService.php:82`). The code already has a `sanitizeJsonString()` step meant to prevent exactly this, and it still fails. Checked the log history: **this is not a one-off** — the identical error occurred previously against a different challenge (id 6) on 2026-06-21. This means: any `team_execution` challenge with a sufficiently rich AI-generated breakdown **never gets tasks created** and is permanently stuck showing 0 workstreams/0 tasks, with `ai_analysis_status` stuck at `processing`. This is the single most important workflow in the product (the whole "team execution" value proposition) and it does not reliably work end-to-end with live AI output.
2. **`MatchVolunteersToTasks` job fails with a database error**: `SQLSTATE[01000]: Data truncated for column 'status'` trying to set `tasks.status = 'matching'` — but the `tasks.status` enum (`pending, open, assigned, in_progress, submitted, under_review, completed, rejected`) **does not contain `matching`** as a legal value. This is a straightforward enum/code mismatch bug, also found in the historical log (not just triggered by this audit).
3. Both failures point to the same root issue: **the AI/task-decomposition pipeline has not been exercised end-to-end with the live Claude API in this environment before** — these are pre-existing, dormant defects, not something this audit caused.

---

## 8. What's Genuinely Live vs. Scaffolded (summary)

| Status | Examples |
|---|---|
| **Fully working, verified live** | Registration, profile completion, NDA signing, challenge submission + AI brief analysis + complexity scoring, public marketing pages, queue-driven job processing |
| **Wired end-to-end per code, plausible but not independently re-verified live this pass** | Idea scoring & voting, work submission/review, certificate generation, leaderboard, admin panel, Mindova internal panel, i18n |
| **Wired but broken when actually exercised** | Task decomposition (AI JSON parsing), volunteer-to-task auto-matching (DB enum mismatch) |
| **Deliberately disabled** | AI quality scoring of work submissions (commented out — human review only) |
| **Built but never triggered anywhere** | WhatsApp notifications (full Meta Cloud API integration, no call site) |
| **Backend-only, no frontend integration found** | In-app contextual guidance / onboarding tooltip system |
| **Schema-only, no controller/service at all** | `VolunteerSkill` (skills taxonomy) |
| **Stub returning success without doing the work** | Admin Settings "Reset Group to Defaults" (only clears cache) |
| **No UI exists, requires direct DB access** | Promoting a user to `admin` |

---

*End of Phase 1. Awaiting review before proceeding to Phase 2 (Product Analysis).*
