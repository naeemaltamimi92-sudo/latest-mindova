# Mindova Platform

> An AI-powered volunteer collaboration platform connecting skilled volunteers with business challenges.

[![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-Proprietary-yellow.svg)](LICENSE)

---

## 🌟 Overview

Mindova is an innovative platform that bridges the gap between companies facing business challenges and talented volunteers looking to make an impact. Using AI-powered matching and quality assessment, Mindova ensures the right people work on the right tasks, with intelligent feedback and automated solution aggregation.

### Key Features

✨ **For Volunteers:**
- 🎯 **Smart Task Matching** - AI matches your skills to relevant tasks
- 🎓 **Community Education** - Learn by discussing level 1-2 challenges
- 📊 **AI-Powered Feedback** - Get quality scores and improvement suggestions
- 🏆 **Reputation System** - Build credibility through quality contributions
- 👥 **Team Collaboration** - Work with other skilled volunteers
- 💼 **Portfolio Building** - Showcase your work and solutions

✨ **For Companies:**
- 🤖 **Automated Challenge Decomposition** - AI breaks down challenges into tasks
- 👨‍💻 **Intelligent Volunteer Matching** - Find the perfect contributors
- ✅ **Quality-Assured Solutions** - AI evaluates all submissions
- 📦 **Aggregated Deliverables** - Receive complete solution packages
- 📈 **Progress Tracking** - Monitor challenge completion in real-time
- 💡 **Community Insights** - Get valuable perspectives on early-stage ideas

---

## 🚀 Quick Start

```bash
# Install dependencies
composer install && npm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Set up database
php artisan migrate

# Build assets
npm run build

# Start development server
php artisan serve

# Start queue worker (separate terminal)
php artisan queue:work
```

**📘 For detailed instructions, see [SETUP_GUIDE.md](SETUP_GUIDE.md)**

---

## 📚 Documentation

| Document | Description |
|----------|-------------|
| **[SETUP_GUIDE.md](SETUP_GUIDE.md)** | Complete installation and configuration guide |
| **[IMPLEMENTATION_SUMMARY.md](IMPLEMENTATION_SUMMARY.md)** | Detailed feature documentation and architecture |
| **[TESTING_GUIDE.md](TESTING_GUIDE.md)** | Testing procedures and checklists |

---

## 🎯 How It Works

### For Volunteers

1. **Register** → Create account via email or LinkedIn
2. **Complete Profile** → Upload CV, set skills and availability
3. **Get Matched** → AI assigns relevant tasks
4. **Contribute** → Work on tasks and submit solutions
5. **Get Feedback** → Receive AI-powered quality scores
6. **Build Reputation** → Earn points for quality work

### For Companies

1. **Register** → Create company account
2. **Submit Challenge** → Describe your business challenge
3. **AI Decomposition** → Challenge broken into tasks
4. **Volunteer Matching** → System finds qualified volunteers
5. **Monitor Progress** → Track completion and quality
6. **Receive Solutions** → Get aggregated, quality-scored deliverables

---

## 🏗️ Tech Stack

- **Backend:** Laravel 10.x, MySQL
- **Frontend:** Blade, Alpine.js, Tailwind CSS
- **AI:** OpenAI GPT-4o
- **Auth:** Laravel Sanctum, LinkedIn OAuth
- **Jobs:** Laravel Queue

---

## 🔥 Core Features

### Authentication
- ✅ Email & password login
- ✅ LinkedIn OAuth
- ✅ Password reset via email
- ✅ Rate limiting

### AI Quality Assessment
- **Comments:** Scored 0-10 (relevance, constructiveness, clarity)
- **Solutions:** Scored 0-100 (completeness, correctness, quality)
- Automatic reputation points for high-quality work

### Smart Workflow
- No task browsing - only AI-matched assignments
- Community education (level 1-2 challenges)
- Team collaboration
- Automated solution aggregation

### Reputation System
- High-quality comments: 5-10 points
- Excellent solutions (90-100): 20 points
- Good solutions (75-89): 15 points
- Acceptable solutions (60-74): 10 points

---

## 🔧 Configuration

**Essential .env variables:**

```env
# Database
DB_DATABASE=mindova
DB_USERNAME=root
DB_PASSWORD=

# OpenAI
OPENAI_API_KEY=sk-your-key-here

# Mail (Password Reset)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io

# Queue
QUEUE_CONNECTION=database
```

**📋 See [SETUP_GUIDE.md](SETUP_GUIDE.md) for complete configuration**

---

## 🧪 Testing

```bash
# Run tests
php artisan test

# Manual testing guide
cat TESTING_GUIDE.md
```

---

## 🔄 Background Jobs

**Start Queue Worker:**
```bash
php artisan queue:work --tries=3 --timeout=300
```

**Monitor:**
```bash
php artisan queue:failed
php artisan queue:retry all
```

---

## 🐛 Troubleshooting

```bash
# Queue not processing
php artisan queue:restart

# Cache issues
php artisan config:clear
php artisan cache:clear

# Migration errors
php artisan migrate:rollback
php artisan migrate
```

**📘 See [SETUP_GUIDE.md](SETUP_GUIDE.md) for detailed troubleshooting**

---

## 📄 Changelog

### Version 1.0.0 (December 2024)

✅ Email/password authentication
✅ LinkedIn OAuth integration
✅ Volunteer dashboard restructure
✅ Community education system
✅ AI-powered solution scoring
✅ Automated challenge aggregation
✅ Reputation system
✅ Team collaboration
✅ Notification system

---

## 📝 License

Proprietary - All rights reserved

---

**Built with ❤️ using Laravel and AI**

*Connecting talent with opportunity, one challenge at a time.*
