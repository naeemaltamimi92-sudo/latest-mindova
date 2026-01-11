# Mindova Platform - Setup Guide

## Prerequisites

- PHP 8.1 or higher
- Composer
- MySQL/MariaDB
- Node.js and npm (for frontend assets)
- OpenAI API key

---

## Installation Steps

### 1. Environment Configuration

Create or update your `.env` file with the following settings:

```env
# Application
APP_NAME=Mindova
APP_ENV=local
APP_KEY=base64:your_app_key_here
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mindova
DB_USERNAME=root
DB_PASSWORD=

# Mail Configuration (for password reset emails)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@mindova.com
MAIL_FROM_NAME="${APP_NAME}"

# For production, use real SMTP:
# MAIL_MAILER=smtp
# MAIL_HOST=smtp.gmail.com
# MAIL_PORT=587
# MAIL_USERNAME=your_email@gmail.com
# MAIL_PASSWORD=your_app_password
# MAIL_ENCRYPTION=tls

# OpenAI Configuration
OPENAI_API_KEY=sk-your-openai-api-key-here

# LinkedIn OAuth (optional)
LINKEDIN_CLIENT_ID=your_linkedin_client_id
LINKEDIN_CLIENT_SECRET=your_linkedin_client_secret
LINKEDIN_REDIRECT_URI=http://localhost/auth/linkedin/callback

# Session & Cache
SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=database

# AI Models (optional - defaults are set)
AI_MODEL_CV_ANALYSIS=gpt-4o
AI_MODEL_CHALLENGE_ANALYSIS=gpt-4o
AI_MODEL_TASK_GENERATION=gpt-4o
AI_MODEL_VOLUNTEER_MATCHING=gpt-4o-mini
AI_MODEL_IDEA_SCORING=gpt-4o-mini
AI_MODEL_COMMENT_ANALYSIS=gpt-4o
AI_MODEL_SOLUTION_ANALYSIS=gpt-4o
```

---

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Build frontend assets
npm run build
```

---

### 3. Database Setup

```bash
# Create database (if not exists)
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS mindova CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Run all migrations
php artisan migrate

# Run the new migrations specifically (if needed)
php artisan migrate --path=/database/migrations/2025_12_19_070320_add_solution_fields_to_work_submissions_table.php
php artisan migrate --path=/database/migrations/2025_12_19_070349_add_aggregation_fields_to_challenges_table.php

# Seed database (optional - for testing)
php artisan db:seed
```

---

### 4. Storage Setup

```bash
# Create symbolic link for public storage
php artisan storage:link

# Set proper permissions (Linux/Mac)
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Windows - no permissions needed
```

---

### 5. Queue Configuration

The platform uses queues for AI analysis jobs. You need to run the queue worker:

**Option 1: Development (run in separate terminal)**
```bash
php artisan queue:work --tries=3 --timeout=300
```

**Option 2: Production (using Supervisor)**

Create `/etc/supervisor/conf.d/mindova-worker.conf`:
```ini
[program:mindova-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/mindova/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/mindova/storage/logs/worker.log
stopwaitsecs=3600
```

Then:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start mindova-worker:*
```

**Option 3: Windows (using Task Scheduler)**
- Create a batch file `run-queue.bat`:
  ```batch
  @echo off
  cd C:\xampp\htdocs\mindova
  php artisan queue:work --tries=3 --timeout=300
  ```
- Open Task Scheduler
- Create new task that runs on startup
- Set action to run the batch file

---

### 6. Application Key

```bash
# Generate application key (if not set)
php artisan key:generate
```

---

### 7. Cache Configuration

```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Cache configuration for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Email Configuration

### Development (Mailtrap)

1. Sign up at https://mailtrap.io (free)
2. Get SMTP credentials from your inbox
3. Update `.env` with credentials
4. Emails will be caught in Mailtrap inbox

### Production (Gmail example)

1. Enable 2FA on your Gmail account
2. Generate an App Password: https://myaccount.google.com/apppasswords
3. Update `.env`:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=your.email@gmail.com
   MAIL_PASSWORD=your_16_char_app_password
   MAIL_ENCRYPTION=tls
   ```

### Testing Email

```bash
php artisan tinker
```

Then in tinker:
```php
Mail::raw('Test email', function ($message) {
    $message->to('test@example.com')
            ->subject('Test Email from Mindova');
});
```

---

## OpenAI API Setup

### 1. Get API Key

1. Go to https://platform.openai.com/api-keys
2. Create new secret key
3. Copy the key (only shown once!)
4. Add to `.env`: `OPENAI_API_KEY=sk-...`

### 2. Check API Usage

Monitor your usage at: https://platform.openai.com/usage

### 3. Cost Estimation

With default configuration (gpt-4o):
- Comment analysis: ~$0.01 per comment
- Solution analysis: ~$0.05 per solution
- Challenge analysis: ~$0.10 per challenge

Monthly estimates:
- 100 solutions/month: ~$5
- 500 comments/month: ~$5
- 50 challenges/month: ~$5
- **Total: ~$15/month** for moderate usage

---

## Running the Application

### Development Server

```bash
# Start PHP development server
php artisan serve

# In separate terminal, run queue worker
php artisan queue:work

# Access application at:
# http://localhost:8000
```

### Production (Apache)

**Virtual Host Configuration:**
```apache
<VirtualHost *:80>
    ServerName mindova.local
    DocumentRoot "C:/xampp/htdocs/mindova/public"

    <Directory "C:/xampp/htdocs/mindova/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

**Update hosts file (Windows: `C:\Windows\System32\drivers\etc\hosts`):**
```
127.0.0.1 mindova.local
```

Access at: http://mindova.local

---

## Testing the Installation

### 1. Test Login

1. Go to http://localhost:8000/login
2. Create account via registration
3. Try email/password login
4. Try "Forgot password" flow

### 2. Test Volunteer Workflow

**As Volunteer:**
1. Register as volunteer
2. Complete profile (upload CV)
3. Check dashboard - should see:
   - Community education section
   - Team invitations (if any)
   - Pending assignments (if any)
4. Go to Community page
5. Post a comment on a level 1-2 challenge
6. Wait for AI analysis (check queue worker is running)
7. Check reputation score increase

### 3. Test Solution Submission

**As Volunteer with assigned task:**
1. Accept a task assignment
2. Go to task details
3. Click "Submit Solution"
4. Fill form:
   - Description
   - Deliverable URL
   - Upload attachment
   - Hours worked
5. Submit
6. Check queue worker processes the job
7. Check reputation points awarded
8. Verify status updated

### 4. Test Company Workflow

**As Company:**
1. Register as company
2. Complete profile
3. Create a challenge
4. Wait for AI decomposition
5. Check tasks created
6. When volunteers submit solutions:
   - Check notifications
   - View challenge analytics
   - When all tasks complete, receive aggregated solutions

---

## Queue Jobs Monitoring

### View Failed Jobs

```bash
php artisan queue:failed
```

### Retry Failed Jobs

```bash
# Retry all
php artisan queue:retry all

# Retry specific job
php artisan queue:retry {job-id}
```

### Clear Failed Jobs

```bash
php artisan queue:flush
```

---

## Troubleshooting

### Queue Not Processing

**Check if queue worker is running:**
```bash
# Linux/Mac
ps aux | grep "queue:work"

# Windows
tasklist | findstr php
```

**Restart queue worker:**
```bash
# Kill existing
php artisan queue:restart

# Start new
php artisan queue:work
```

### Migrations Failed

```bash
# Rollback last migration
php artisan migrate:rollback

# Rollback specific migration
php artisan migrate:rollback --step=1

# Fresh migration (WARNING: deletes all data)
php artisan migrate:fresh
```

### Storage Permission Errors

```bash
# Linux/Mac
sudo chown -R www-data:www-data storage
sudo chmod -R 775 storage

# Windows
# Right-click storage folder → Properties → Security
# Give IUSR and IIS_IUSRS full control
```

### OpenAI API Errors

**Rate limit exceeded:**
- Wait a few seconds
- Upgrade OpenAI plan
- Add retry logic (already included)

**Invalid API key:**
- Check `.env` has correct key
- Clear config cache: `php artisan config:clear`
- Restart server

**Timeout errors:**
- Increase timeout in `config/ai.php`
- Check internet connection
- Verify OpenAI service status

### Email Not Sending

**Development:**
- Check Mailtrap credentials
- Verify `.env` settings
- Check `storage/logs/laravel.log`

**Production:**
- Verify SMTP credentials
- Check firewall allows port 587
- Try different MAIL_ENCRYPTION (tls/ssl)
- Enable less secure apps (if Gmail)

---

## Security Checklist for Production

- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Use strong `APP_KEY`
- [ ] Use HTTPS (SSL certificate)
- [ ] Set proper file permissions (775/644)
- [ ] Enable rate limiting
- [ ] Use real SMTP for emails
- [ ] Set up regular database backups
- [ ] Monitor OpenAI API usage
- [ ] Keep dependencies updated
- [ ] Set up error monitoring (e.g., Sentry)
- [ ] Configure queue workers to restart on failure
- [ ] Set up log rotation

---

## Maintenance

### Daily

```bash
# Check failed queue jobs
php artisan queue:failed

# Check logs for errors
tail -f storage/logs/laravel.log
```

### Weekly

```bash
# Update dependencies
composer update

# Clear and rebuild cache
php artisan optimize:clear
php artisan optimize
```

### Monthly

```bash
# Backup database
mysqldump -u root -p mindova > backup_$(date +%Y%m%d).sql

# Check OpenAI API usage
# Review application performance
# Update documentation
```

---

## Getting Help

- **Laravel Documentation:** https://laravel.com/docs
- **OpenAI API Docs:** https://platform.openai.com/docs
- **Project Documentation:** See `IMPLEMENTATION_SUMMARY.md`

---

## Quick Reference Commands

```bash
# Start development environment
php artisan serve              # Web server
php artisan queue:work        # Queue worker

# Database
php artisan migrate           # Run migrations
php artisan db:seed           # Seed database
php artisan tinker           # Laravel REPL

# Cache management
php artisan config:clear     # Clear config cache
php artisan cache:clear      # Clear application cache
php artisan route:clear      # Clear route cache
php artisan view:clear       # Clear view cache

# Queue management
php artisan queue:work       # Start queue worker
php artisan queue:restart    # Restart queue worker
php artisan queue:failed     # Show failed jobs
php artisan queue:retry all  # Retry all failed jobs

# Logs
tail -f storage/logs/laravel.log  # Watch Laravel logs
```

---

**Setup Complete!** Your Mindova platform is ready to use. 🎉
