# Mindova - Quick Start Guide

## 🚀 Getting Started (5 Minutes)

### 1. Fix Database Migration Issue

```bash
# Run the fix script
fix_database.bat
```

This will:
- Remove the problematic migration file
- Run all migrations in correct order
- Set up your database schema

### 2. Seed Test Data (Optional but Recommended)

```bash
php artisan db:seed --class=TestDataSeeder
```

**Test Accounts Created:**
- **Companies:**
  - `company1@mindova.test` / `password`
  - `company2@mindova.test` / `password`

- **Volunteers:**
  - `john@mindova.test` / `password` (Technology)
  - `sarah@mindova.test` / `password` (Healthcare)
  - `mike@mindova.test` / `password` (Technology/Design)

### 3. Start the Application

**Option A: Use the Menu** (Recommended)
```bash
start.bat
```
Then select option 5 to start both server and queue worker.

**Option B: Manual Start**

Terminal 1:
```bash
php artisan serve
```

Terminal 2:
```bash
php artisan queue:work
```

### 4. Access the Application

Open your browser: **http://localhost:8000**

---

## 📝 Quick Test Workflow

### As a Volunteer

1. **Login**
   - Go to: http://localhost:8000/login
   - Email: `john@mindova.test`
   - Password: `password`

2. **Check Dashboard**
   - See community education section (level 1-2 challenges)
   - View stats and reputation score

3. **Post a Comment**
   - Go to Community → Click on a challenge
   - Post a thoughtful comment
   - Wait ~30 seconds for AI scoring
   - Check if you earned reputation points

4. **Test Solution Submission** (if you have an assigned task)
   - Go to task details
   - Click "Submit Solution"
   - Fill the form and submit
   - Wait for AI analysis

### As a Company

1. **Login**
   - Email: `company1@mindova.test`
   - Password: `password`

2. **View Challenges**
   - See your challenges
   - Check challenge analytics
   - View community comments

---

## 🔧 Common Commands

### Database

```bash
# Run migrations
php artisan migrate

# Reset database and re-run migrations
php artisan migrate:fresh

# Seed test data
php artisan db:seed --class=TestDataSeeder

# Check migration status
php artisan migrate:status
```

### Cache

```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Or use the menu (option 7 in start.bat)
```

### Queue

```bash
# Start queue worker
php artisan queue:work

# View failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all

# Restart queue worker
php artisan queue:restart
```

### Development

```bash
# Start server
php artisan serve

# Watch Laravel logs
tail -f storage/logs/laravel.log

# Open Laravel Tinker (interactive shell)
php artisan tinker
```

---

## 🐛 Troubleshooting

### Queue Not Processing

**Problem:** AI analysis not running
**Solution:**
```bash
php artisan queue:restart
php artisan queue:work
```

### Database Connection Error

**Problem:** SQLSTATE[HY000] connection refused
**Solution:**
1. Make sure XAMPP MySQL is running
2. Check `.env` database settings
3. Run: `php artisan config:clear`

### Migration Error

**Problem:** Foreign key constraint error
**Solution:**
```bash
fix_database.bat
```

### Session/Cache Issues

**Problem:** Old data showing, routes not working
**Solution:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

---

## 📚 Documentation Files

| File | Purpose |
|------|---------|
| `README.md` | Project overview |
| `SETUP_GUIDE.md` | Detailed installation guide |
| `IMPLEMENTATION_SUMMARY.md` | Complete feature documentation |
| `TESTING_GUIDE.md` | Testing procedures |
| `QUICK_START.md` | This file - quick reference |

---

## 🎯 Next Steps

After getting everything running:

1. **Test Features**
   - Follow `TESTING_GUIDE.md`
   - Test login, comments, solutions

2. **Configure Email** (for password reset)
   - Sign up at https://mailtrap.io (free)
   - Update `.env` with SMTP credentials
   - Test password reset flow

3. **Add OpenAI API Key** (for AI features)
   - Get key from https://platform.openai.com/api-keys
   - Add to `.env`: `OPENAI_API_KEY=sk-...`
   - Restart server

4. **Customize**
   - Update company/volunteer profiles
   - Create real challenges
   - Invite real users

---

## ⚡ Useful Scripts

### Created for You

- `start.bat` - Interactive menu for all common tasks
- `fix_database.bat` - Fix migration order issues
- `create_database.sql` - SQL script to create database

---

## 🔗 Important URLs

- **Application:** http://localhost:8000
- **Login:** http://localhost:8000/login
- **Register:** http://localhost:8000/register
- **Community:** http://localhost:8000/community
- **phpMyAdmin:** http://localhost/phpmyadmin

---

## 📞 Getting Help

- Check `SETUP_GUIDE.md` for detailed troubleshooting
- Check `TESTING_GUIDE.md` for testing procedures
- Check Laravel logs: `storage/logs/laravel.log`
- Check queue worker output

---

**You're all set! 🎉**

Start with `start.bat` and choose option 5 to launch everything!
