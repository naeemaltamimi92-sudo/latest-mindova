# NDA File-Based System & Supervisor Implementation

## Overview

This document describes the implementation of two major improvements to the MINDOVA platform:

1. **File-Based NDA System** - Converted from database seeder to static file storage
2. **Supervisor Configuration** - Automated queue worker management for production

---

## ✅ Implementation Status: 100% Complete

---

## Part 1: File-Based NDA System

### Problem

Previously, NDA content was stored in the database via a seeder (`NdaSeeder.php`). This approach had several drawbacks:

- NDAs were seeded on every database refresh
- Content was not easily version-controlled
- Updating NDA content required database migrations
- No clear separation between code and content

### Solution

Converted to a file-based system where NDA content is stored in static markdown files in the project storage.

---

## Implementation Details

### 1. Created NDA Content Files

**Location:** `storage/app/nda/`

Two NDA files were created:

#### General NDA
**File:** `storage/app/nda/general_nda.md`

Contains the general platform NDA that all volunteers must sign before participating in any challenge.

**Content includes:**
- Definition of confidential information
- Obligations of receiving party
- Term (5 years)
- Return of materials
- No license clause
- Legal compliance
- Governing law

#### Challenge-Specific NDA
**File:** `storage/app/nda/challenge_nda.md`

Contains the challenge-specific NDA template used for challenges requiring additional confidentiality.

**Content includes:**
- Challenge-specific confidential information
- Enhanced confidentiality obligations
- Confidentiality level placeholder
- Custom terms placeholder
- Intellectual property rights
- Non-circumvention clause
- Term (7 years)
- Remedies

### 2. Updated NdaAgreement Model

**File:** `app/Models/NdaAgreement.php`

**Changes Made:**

Modified the two static methods to read from files instead of database:

```php
public static function getActiveGeneralNda()
{
    $filePath = storage_path('app/nda/general_nda.md');

    if (!file_exists($filePath)) {
        \Log::error('General NDA file not found', ['path' => $filePath]);
        return null;
    }

    $content = file_get_contents($filePath);

    // Return an object with the expected properties
    $nda = new \stdClass();
    $nda->id = 1;
    $nda->title = 'Mindova Platform General Non-Disclosure Agreement';
    $nda->type = 'general';
    $nda->version = '1.0';
    $nda->content = $content;
    $nda->is_active = true;
    $nda->effective_date = now();

    return $nda;
}

public static function getActiveChallengeNda()
{
    $filePath = storage_path('app/nda/challenge_nda.md');

    if (!file_exists($filePath)) {
        \Log::error('Challenge NDA file not found', ['path' => $filePath]);
        return null;
    }

    $content = file_get_contents($filePath);

    // Return an object with the expected properties
    $nda = new \stdClass();
    $nda->id = 2;
    $nda->title = 'Mindova Challenge-Specific Non-Disclosure Agreement';
    $nda->type = 'challenge_specific';
    $nda->version = '1.0';
    $nda->content = $content;
    $nda->is_active = true;
    $nda->effective_date = now();

    return $nda;
}
```

**Key Features:**
- Reads content from markdown files
- Returns a standard object with expected properties
- Logs errors if files are missing
- Maintains backward compatibility with existing controller code

### 3. No Controller Changes Required

**File:** `app/Http/Controllers/NdaController.php`

The controller remains unchanged because it uses the static methods:
- `NdaAgreement::getActiveGeneralNda()`
- `NdaAgreement::getActiveChallengeNda()`

These methods now read from files instead of database, but the interface remains the same.

---

## Benefits of File-Based Approach

### 1. Version Control
- ✅ NDA content is version-controlled with Git
- ✅ Easy to track changes over time
- ✅ Reviewable in pull requests

### 2. Easy Updates
- ✅ Update NDA by editing markdown files
- ✅ No database migrations required
- ✅ Changes deploy with code

### 3. Clear Separation
- ✅ Content separated from code
- ✅ Legal team can review files directly
- ✅ No database dependencies

### 4. Backup & Recovery
- ✅ NDAs backed up with code
- ✅ Easy to restore from Git history
- ✅ No risk of losing content on database refresh

### 5. Portability
- ✅ Works across all environments (dev, staging, production)
- ✅ No seeder required
- ✅ Consistent across deployments

---

## NDA Workflow

### General NDA Signing Flow

```
1. New volunteer registers on platform
   ↓
2. System redirects to general NDA page
   ↓
3. NdaController calls NdaAgreement::getActiveGeneralNda()
   ↓
4. Model reads content from storage/app/nda/general_nda.md
   ↓
5. Content displayed to volunteer
   ↓
6. Volunteer signs (electronic signature)
   ↓
7. Signature recorded in volunteers table
   ↓
8. Volunteer can now browse challenges
```

### Challenge-Specific NDA Signing Flow

```
1. Volunteer views challenge requiring NDA
   ↓
2. System checks if general NDA is signed
   ↓
3. NdaController calls NdaAgreement::getActiveChallengeNda()
   ↓
4. Model reads content from storage/app/nda/challenge_nda.md
   ↓
5. Content customized with challenge details
   ↓
6. Customized NDA displayed to volunteer
   ↓
7. Volunteer signs (electronic signature)
   ↓
8. Signature recorded in challenge_nda_signings table
   ↓
9. Volunteer can now access full challenge details
```

---

## Updating NDA Content

### To Update General NDA

1. Edit the file:
   ```bash
   nano storage/app/nda/general_nda.md
   ```

2. Make your changes

3. Commit to version control:
   ```bash
   git add storage/app/nda/general_nda.md
   git commit -m "Update general NDA terms"
   git push
   ```

4. Deploy to production:
   ```bash
   git pull origin main
   ```

5. Consider updating the version number in the model if significant changes

### To Update Challenge NDA

1. Edit the file:
   ```bash
   nano storage/app/nda/challenge_nda.md
   ```

2. Follow same commit and deploy process as above

### Version Management

To track NDA versions, update the version number in the model:

```php
$nda->version = '2.0'; // Changed from '1.0'
```

You can also add version info to the markdown file itself:

```markdown
<!-- Version: 2.0 -->
<!-- Last Updated: 2025-12-24 -->
<!-- Changes: Added new clause about data retention -->
```

---

## Part 2: Supervisor Configuration

### Purpose

Supervisor is a process control system that manages queue workers in production. It ensures:

- Queue workers start automatically on server boot
- Workers restart automatically if they crash
- Multiple workers run in parallel
- Email notifications are sent reliably
- Certificate generation emails are processed

---

## Supervisor Files Created

### 1. Configuration File

**File:** `supervisor/mindova-worker.conf`

```ini
[program:mindova-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/mindova/artisan queue:work --sleep=3 --tries=3 --max-time=3600 --timeout=180
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

**Configuration Parameters:**

- `process_name`: Unique name for each worker process
- `command`: Laravel queue worker command
  - `--sleep=3`: Wait 3 seconds when queue is empty
  - `--tries=3`: Retry failed jobs 3 times
  - `--max-time=3600`: Worker runs for max 1 hour before restart
  - `--timeout=180`: Maximum 180 seconds per job
- `autostart`: Start on system boot
- `autorestart`: Restart if crashed
- `user`: System user (www-data for Apache/Nginx)
- `numprocs`: Number of parallel workers (2 by default)
- `stdout_logfile`: Log file location

### 2. Setup Guide

**File:** `SUPERVISOR_SETUP_GUIDE.md`

Comprehensive guide covering:
- Installation instructions
- Configuration steps
- File permissions
- Starting and managing workers
- Deployment workflow
- Troubleshooting
- Monitoring
- Production best practices

---

## Supervisor Setup (Quick Start)

### On Production Server

1. **Install Supervisor:**
   ```bash
   sudo apt-get install supervisor
   ```

2. **Copy configuration:**
   ```bash
   sudo cp /var/www/mindova/supervisor/mindova-worker.conf /etc/supervisor/conf.d/
   ```

3. **Edit paths in configuration:**
   ```bash
   sudo nano /etc/supervisor/conf.d/mindova-worker.conf
   ```

   Update:
   - `/path/to/mindova` → actual path (e.g., `/var/www/mindova`)
   - `user` → your web server user (e.g., `www-data`)

4. **Set permissions:**
   ```bash
   sudo chown -R www-data:www-data /var/www/mindova/storage
   sudo chmod -R 775 /var/www/mindova/storage
   ```

5. **Start workers:**
   ```bash
   sudo supervisorctl reread
   sudo supervisorctl update
   sudo supervisorctl start mindova-worker:*
   ```

6. **Verify:**
   ```bash
   sudo supervisorctl status
   ```

---

## Managing Queue Workers

### Check Status
```bash
sudo supervisorctl status
```

### Restart Workers
```bash
sudo supervisorctl restart mindova-worker:*
```

### View Logs
```bash
sudo tail -f /var/www/mindova/storage/logs/worker.log
```

### After Code Deployment
```bash
# Always restart workers after deploying new code
sudo supervisorctl restart mindova-worker:*
```

---

## Integration with Email System

### How It Works Together

The certificate email notification system relies on Supervisor:

```
1. Company generates certificates
   ↓
2. CertificateController queues email notification
   ↓
3. Email job added to database queue
   ↓
4. Supervisor-managed worker picks up job
   ↓
5. Worker processes email
   ↓
6. Email sent to mindova.ai@gmail.com
   ↓
7. Job marked as complete
```

**Without Supervisor:**
- Emails queued but never sent
- Need to manually run `php artisan queue:work`
- Workers stop when SSH session ends
- No automatic restart on crash

**With Supervisor:**
- ✅ Workers always running
- ✅ Emails sent automatically
- ✅ Auto-restart on failure
- ✅ Starts on server boot

---

## Files Modified/Created

### NDA System

**Created:**
- `storage/app/nda/general_nda.md` - General NDA content
- `storage/app/nda/challenge_nda.md` - Challenge NDA content

**Modified:**
- `app/Models/NdaAgreement.php` - Updated to read from files

**Can Be Removed:**
- `database/seeders/NdaSeeder.php` - No longer needed

### Supervisor System

**Created:**
- `supervisor/mindova-worker.conf` - Supervisor configuration
- `SUPERVISOR_SETUP_GUIDE.md` - Complete setup documentation
- `NDA_AND_SUPERVISOR_IMPLEMENTATION.md` - This document

---

## Testing

### Test NDA System

1. **Test General NDA:**
   ```bash
   # Create a new volunteer account
   # System should display NDA from file
   ```

2. **Test Challenge NDA:**
   ```bash
   # Create a challenge with requires_nda = true
   # Volunteer should see customized NDA from file
   ```

3. **Verify File Loading:**
   ```bash
   # Check Laravel logs for any errors
   tail -f storage/logs/laravel.log
   ```

### Test Supervisor (Production)

1. **Install and configure Supervisor**

2. **Start workers:**
   ```bash
   sudo supervisorctl start mindova-worker:*
   ```

3. **Generate certificates:**
   - Log in as company
   - Complete a challenge
   - Generate certificates

4. **Verify email sent:**
   - Check worker logs: `sudo tail -f /var/www/mindova/storage/logs/worker.log`
   - Check email received at mindova.ai@gmail.com

5. **Test auto-restart:**
   ```bash
   # Kill a worker process
   sudo supervisorctl stop mindova-worker:mindova-worker_00

   # Wait a few seconds
   sleep 5

   # Check status - should auto-restart
   sudo supervisorctl status
   ```

---

## Migration Notes

### From Database Seeder to Files

**No database migration required!**

The NDA table and records can remain in the database, but they won't be used. The static methods now read from files instead.

**Optional Cleanup:**

If you want to clean up the database:

```bash
# Remove NDA seeder from DatabaseSeeder
# Edit database/seeders/DatabaseSeeder.php
# Remove: $this->call(NdaSeeder::class);

# Optionally drop the table (NOT RECOMMENDED if you have signing records)
# php artisan migrate:rollback --step=1
```

**Recommended:**
- Keep the database table for backward compatibility
- Keep the migration file
- Just don't call the seeder

---

## Production Deployment Checklist

### Initial Setup

- [ ] NDA files exist in `storage/app/nda/`
- [ ] Supervisor installed on server
- [ ] Supervisor config copied to `/etc/supervisor/conf.d/`
- [ ] Paths updated in supervisor config
- [ ] Correct user set in supervisor config
- [ ] File permissions set correctly
- [ ] Workers started and running
- [ ] Workers verified with status command

### Every Deployment

- [ ] Pull latest code
- [ ] Run migrations if any
- [ ] Clear and cache config
- [ ] Restart queue workers: `sudo supervisorctl restart mindova-worker:*`
- [ ] Verify workers are running
- [ ] Test email notification (optional)

---

## Troubleshooting

### NDA Not Displaying

**Check file exists:**
```bash
ls -la storage/app/nda/
```

**Check file permissions:**
```bash
# Make readable by web server
chmod 644 storage/app/nda/*.md
```

**Check logs:**
```bash
tail -f storage/logs/laravel.log | grep -i "nda"
```

### Supervisor Workers Not Starting

**Check config syntax:**
```bash
sudo supervisorctl reread
```

**Check permissions:**
```bash
sudo chown -R www-data:www-data /var/www/mindova/storage
```

**Check logs:**
```bash
sudo tail -f /var/log/supervisor/supervisord.log
```

### Emails Not Sending

**Check workers running:**
```bash
sudo supervisorctl status
```

**Check queue connection:**
```bash
# In .env
QUEUE_CONNECTION=database
```

**Check worker logs:**
```bash
sudo tail -f /var/www/mindova/storage/logs/worker.log
```

**Process queue manually:**
```bash
php artisan queue:work --once
```

---

## Benefits Summary

### NDA System Benefits

✅ **Easy Updates:** Edit markdown files, commit, deploy
✅ **Version Control:** Track all changes in Git
✅ **No Database Dependency:** Works immediately after code deployment
✅ **Legal Review:** Legal team can review files directly
✅ **Backup:** Backed up with code automatically
✅ **Portability:** Same content across all environments

### Supervisor Benefits

✅ **Auto-Start:** Workers start on server boot
✅ **Auto-Restart:** Workers restart if crashed
✅ **Reliable Email:** Emails always processed
✅ **Scalable:** Run multiple workers in parallel
✅ **Monitoring:** Easy to check status and logs
✅ **Production Ready:** Battle-tested process manager

---

## Future Enhancements

### NDA System

- [ ] Add versioning system with change tracking
- [ ] Create admin UI to edit NDA content
- [ ] Support multiple languages
- [ ] Add NDA templates for different industries
- [ ] Generate PDF versions of NDAs

### Supervisor

- [ ] Add email alerts for worker failures
- [ ] Implement priority queues (high, default, low)
- [ ] Add monitoring dashboard
- [ ] Set up queue metrics collection
- [ ] Configure auto-scaling based on load

---

## Conclusion

### ✅ NDA System: Complete

The NDA system has been successfully converted from database seeder to file-based storage. NDAs are now:
- Stored in version-controlled markdown files
- Easy to update and deploy
- Independent of database state
- Ready for production use

### ✅ Supervisor: Configured

Supervisor configuration is ready for production deployment. Queue workers will:
- Start automatically on server boot
- Process email notifications reliably
- Restart automatically if crashed
- Run in parallel for better performance

---

**Implementation Date:** December 24, 2025
**Status:** ✅ Production Ready
**Tested:** Development Environment (NDA System)
**Deployment Required:** Supervisor setup on production server

---

## Quick Reference

### NDA Files
- General: `storage/app/nda/general_nda.md`
- Challenge: `storage/app/nda/challenge_nda.md`

### Supervisor
- Config: `supervisor/mindova-worker.conf`
- Guide: `SUPERVISOR_SETUP_GUIDE.md`

### Commands
```bash
# Restart workers after deployment
sudo supervisorctl restart mindova-worker:*

# Check worker status
sudo supervisorctl status

# View worker logs
sudo tail -f /var/www/mindova/storage/logs/worker.log

# Edit NDA
nano storage/app/nda/general_nda.md
```
