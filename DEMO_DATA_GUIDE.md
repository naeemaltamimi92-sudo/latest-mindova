# Demo Data Seeder Guide

This comprehensive demo data seeder creates a fully functional demo environment for the Mindova platform, showcasing the complete workflow from challenge creation to task completion.

## 🚀 How to Run the Seeder

### Step 1: Reset the Database (Optional but Recommended)
```bash
php artisan migrate:fresh
```

### Step 2: Run the Seeder
```bash
php artisan db:seed
```

Or run the specific seeder:
```bash
php artisan db:seed --class=DemoDataSeeder
```

## 📊 What Gets Created

### 1. **Companies (3)**
All company accounts use password: `password`

| Company Name | Email | Industry | Description |
|--------------|-------|----------|-------------|
| TechCorp Solutions | company1@techcorp.com | Technology | AI and ML software development |
| Global Manufacturing Inc | company2@globalmanuf.com | Manufacturing | Industrial manufacturing |
| HealthTech Innovations | company3@healthtech.com | Healthcare | Digital health platform |

### 2. **Volunteers (6)**
All volunteer accounts use password: `password`

| Name | Email | Field | Experience | Skills |
|------|-------|-------|------------|--------|
| John Developer | john@example.com | Software Engineering | Senior (8 years) | PHP, Laravel, JavaScript, React, MySQL |
| Sarah Chen | sarah@example.com | Chemical Engineering | Mid-level (5 years) | Process Optimization, Chemical Analysis |
| Mike Analytics | mike@example.com | Data Science | Senior (7 years) | Python, ML, TensorFlow, Data Viz |
| Emma Design | emma@example.com | UX Design | Mid-level (4 years) | Figma, User Research, Wireframing |
| Lisa Marketing | lisa@example.com | Marketing | Mid-level (5 years) | Digital Marketing, SEO, Content |
| David Manager | david@example.com | Project Management | Senior (10 years) | Agile, Scrum, Risk Management |

### 3. **Challenges (5)**

#### Challenge 1: AI-Powered Customer Support Chatbot ✅ ACTIVE
- **Company**: TechCorp Solutions
- **Status**: Active (team_execution)
- **Complexity**: Level 8
- **Teams**: 1 active team with 3 members
- **Tasks**: 6 tasks across 3 workstreams
- **Progress**: In progress with completed, in-progress, and pending tasks

#### Challenge 2: Optimize Chemical Production Process 🔄 ACTIVE
- **Company**: Global Manufacturing Inc
- **Status**: Active (team_execution)
- **Complexity**: Level 7
- **Teams**: 1 forming team with 2 members
- **Tasks**: 3 tasks across 2 workstreams
- **Progress**: New invitations pending

#### Challenge 3: Patient Data Analytics Dashboard 🔍 ANALYZING
- **Company**: HealthTech Innovations
- **Status**: Analyzing
- **Complexity**: Level 9
- **Note**: Recently submitted, AI analysis in progress

#### Challenge 4: Improve Mobile App UX 💬 COMMUNITY DISCUSSION
- **Company**: TechCorp Solutions
- **Status**: Active (community_discussion)
- **Complexity**: Level 2
- **Type**: Community feedback challenge

#### Challenge 5: Digital Marketing Strategy 📝 SUBMITTED
- **Company**: HealthTech Innovations
- **Status**: Just submitted
- **Note**: Awaiting AI analysis

### 4. **Workstreams & Tasks**

#### Challenge 1 Tasks:
1. **Backend Development Workstream**
   - ✅ Design and implement RESTful API (In Progress - John)
   - 🎯 Implement NLP intent recognition (Accepted - Mike)

2. **Frontend Development Workstream**
   - ✅ Build chat widget UI component (Completed & Submitted - John)
   - 📋 Admin dashboard for monitoring (Pending - Invited to Emma)

3. **Integration & Testing Workstream**
   - 📋 Salesforce CRM integration (Pending - Declined by Lisa)

#### Challenge 2 Tasks:
1. **Process Analysis Workstream**
   - 📨 Map current production workflow (Invited - Sarah)
   - 📋 Identify process bottlenecks (Pending)

2. **Optimization Recommendations Workstream**
   - 📋 Design process improvements (Pending)

### 5. **Teams (2)**

#### Team 1: AI Chatbot Squad
- **Challenge**: AI Chatbot
- **Status**: Active
- **Leader**: John Developer
- **Members**:
  - ✅ John Developer (Leader, Backend) - Accepted
  - ✅ Mike Analytics (ML Engineer) - Accepted
  - 📨 Emma Design (UX Designer) - Invited

#### Team 2: Process Optimization Team
- **Challenge**: Chemical Process
- **Status**: Forming
- **Leader**: Sarah Chen
- **Members**:
  - ✅ Sarah Chen (Leader, Chemical Engineer) - Accepted
  - 📨 David Manager (Project Manager) - Invited

### 6. **Task Assignments**

Demonstrates all possible states:

| Volunteer | Task | Status | Match Score |
|-----------|------|--------|-------------|
| John | RESTful API | 🔄 In Progress | 95% |
| Mike | NLP Intent | ✅ Accepted | 98% |
| John | Chat Widget | 📤 Submitted (Completed) | 88% |
| Sarah | Process Mapping | 📨 Invited | 90% |
| Emma | Admin Dashboard | 📨 Invited | 85% |
| Lisa | Salesforce Integration | ❌ Declined | 45% |

### 7. **Work Submissions (1)**

- **Task**: Build chat widget UI component
- **Volunteer**: John Developer
- **Status**: Approved
- **AI Score**: 88/100
- **Hours**: 32 hours
- **Deliverable**: GitHub repository with complete implementation

### 8. **NDA Signings**

- All volunteers have signed the general NDA
- Challenge-specific NDAs signed for:
  - John, Mike, Emma (Challenge 1 - AI Chatbot)
  - Sarah (Challenge 2 - Chemical Process)

## 🎯 Demo Scenarios You Can Test

### Scenario 1: Company View (Login as TechCorp)
- View active challenge with AI workflow progress
- See team formation and member status
- Monitor task progress and submissions
- Review work submission with AI quality score

**Login**: `company1@techcorp.com` / `password`

### Scenario 2: Team Leader View (Login as John)
- See "My Tasks" with current in-progress task
- View team management capabilities
- Access full challenge workstreams and tasks
- Submit solutions and track progress
- Previously completed task with approval

**Login**: `john@example.com` / `password`

### Scenario 3: Volunteer with Pending Invitations (Login as Sarah)
- View task invitation with match reasoning
- See team invitation
- Accept/decline task assignments
- One-task-at-a-time enforcement

**Login**: `sarah@example.com` / `password`

### Scenario 4: Volunteer with Multiple Invitations (Login as Emma)
- Multiple pending task invitations
- Team invitation pending
- View match scores and reasoning
- Cannot accept if already has active task

**Login**: `emma@example.com` / `password`

### Scenario 5: ML Expert (Login as Mike)
- Accepted task ready to start
- Part of active team
- Can start working on assigned task

**Login**: `mike@example.com` / `password`

### Scenario 6: Volunteer Who Declined (Login as Lisa)
- See declined task history
- Reason for declining visible
- Available for new opportunities

**Login**: `lisa@example.com` / `password`

## 🔄 Complete Workflow Demonstrated

1. **Challenge Submission** → Challenge 5 (just submitted)
2. **AI Analysis** → Challenge 3 (analyzing)
3. **Task Decomposition** → Challenges 1 & 2 (completed)
4. **Volunteer Matching** → All active challenges
5. **Team Formation** → 2 teams in different stages
6. **Task Invitations** → Multiple statuses (invited, accepted, declined)
7. **Work in Progress** → John working on API
8. **Task Submission** → Chat widget submitted
9. **AI Quality Review** → Submission approved with 88 score
10. **Team Collaboration** → Active team with mixed statuses

## 📋 Key Features Showcased

✅ Multiple user roles (Companies & Volunteers)
✅ Challenge lifecycle (Submitted → Analyzing → Active)
✅ AI workflow visualization
✅ Workstream and task organization
✅ Team formation and management
✅ Volunteer-task matching with reasoning
✅ One-task-at-a-time enforcement
✅ Task assignment states (invited, accepted, in_progress, submitted, declined)
✅ Work submission and AI quality scoring
✅ NDA signing workflow
✅ Team leader vs regular volunteer permissions
✅ Skills matching and gap analysis

## 🔍 Testing Access Control

### What Volunteers Can See:
- ✅ Their own task invitations
- ✅ Their assigned tasks
- ✅ Tasks they're working on
- ✅ Their team memberships
- ❌ Other challenges (no browse)
- ❌ Full challenge details (unless team leader)
- ❌ Other volunteers' tasks

### What Team Leaders Can See:
- ✅ Everything regular volunteers see
- ✅ Full challenge workstreams and tasks
- ✅ All team members and progress
- ✅ Challenge context and details

### What Companies Can See:
- ✅ All their challenges
- ✅ All teams and members
- ✅ All tasks and progress
- ✅ Work submissions
- ✅ AI analysis results

## 💡 Tips for Demo

1. **Start with Company View** to see the overview
2. **Login as John** to see active work in progress
3. **Login as Sarah** to see fresh invitations
4. **Check Emma's account** for one-task-at-a-time enforcement
5. **Review the submitted work** to see AI quality analysis
6. **Compare Team Leader (John) vs Regular Volunteer (Mike)** views

## 🛠️ Troubleshooting

If you encounter errors:

1. **Foreign Key Errors**: Make sure to run `migrate:fresh` before seeding
2. **Class Not Found**: Run `composer dump-autoload`
3. **Connection Error**: Check database connection in `.env`
4. **Queue Jobs**: Some features may require queue worker: `php artisan queue:work`

## 🎨 Customization

To modify the demo data, edit:
```
database/seeders/DemoDataSeeder.php
```

You can adjust:
- Number of companies/volunteers
- Challenge descriptions
- Task complexities
- Team compositions
- Assignment statuses

---

**Happy Testing! 🚀**
