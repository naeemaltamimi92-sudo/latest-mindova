# Supervisor Setup Guide for MINDOVA Queue Workers

## Overview

This guide explains how to set up Supervisor to automatically manage Laravel queue workers in production. Supervisor ensures that queue workers are always running and automatically restarts them if they fail.

---

## What is Supervisor?

**Supervisor** is a process control system for Linux that:
- Monitors and controls processes
- Automatically starts processes on system boot
- Restarts crashed processes automatically
- Provides process management interface
- Logs process output

---

## Prerequisites

- Linux-based production server (Ubuntu, Debian, CentOS, etc.)
- Root or sudo access
- MINDOVA application deployed
- Queue system configured in `.env`

---

## Installation

### Ubuntu/Debian

```bash
sudo apt-get update
sudo apt-get install supervisor
```

### CentOS/RHEL

```bash
sudo yum install supervisor
sudo systemctl enable supervisord
```

### Verify Installation

```bash
sudo supervisorctl version
```

---

## Configuration

### Step 1: Copy Configuration File

Copy the Supervisor configuration from your project to the system directory:

```bash
sudo cp /path/to/mindova/supervisor/mindova-worker.conf /etc/supervisor/conf.d/
```

### Step 2: Edit Configuration

Edit the configuration file to match your server paths:

```bash
sudo nano /etc/supervisor/conf.d/mindova-worker.conf
```

**Update the following paths:**

```ini
[program:mindova-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/mindova/artisan queue:work --sleep=3 --tries=3 --max-time=3600 --timeout=180
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/mindova/storage/logs/worker.log
stopwaitsecs=3600
```

**Configuration Parameters Explained:**

- `command`: Full path to your artisan command
  - `--sleep=3`: Wait 3 seconds when no jobs available
  - `--tries=3`: Retry failed jobs 3 times
  - `--max-time=3600`: Worker runs for max 1 hour before restarting
  - `--timeout=180`: Maximum 180 seconds per job

- `user`: The system user (usually `www-data`, `nginx`, or your deploy user)
- `numprocs`: Number of worker processes (adjust based on your needs)
- `stdout_logfile`: Path to log file (must be writable by the user)
- `autostart`: Start workers on system boot
- `autorestart`: Restart workers if they crash
- `stopwaitsecs`: Wait time before force-killing the process

### Step 3: Adjust Number of Workers

**For Production (High Load):**
```ini
numprocs=4
```

**For Staging/Small Production:**
```ini
numprocs=2
```

**For Development (Local):**
```ini
numprocs=1
```

### Step 4: Set Correct User

The `user` parameter should match your web server user:

**For Apache:**
```ini
user=www-data
```

**For Nginx:**
```ini
user=www-data
# or
user=nginx
```

**For custom deployment user:**
```ini
user=deploy
```

---

## File Permissions

Ensure the log file and storage directories are writable:

```bash
# Create log file if it doesn't exist
sudo touch /var/www/mindova/storage/logs/worker.log

# Set correct ownership
sudo chown -R www-data:www-data /var/www/mindova/storage

# Set correct permissions
sudo chmod -R 775 /var/www/mindova/storage
```

---

## Starting Supervisor

### Reload Configuration

After making changes to the config file:

```bash
sudo supervisorctl reread
sudo supervisorctl update
```

### Start Workers

```bash
sudo supervisorctl start mindova-worker:*
```

### Check Status

```bash
sudo supervisorctl status
```

You should see output like:
```
mindova-worker:mindova-worker_00   RUNNING   pid 12345, uptime 0:00:10
mindova-worker:mindova-worker_01   RUNNING   pid 12346, uptime 0:00:10
```

---

## Managing Workers

### View All Processes

```bash
sudo supervisorctl status
```

### Start Workers

```bash
# Start all workers
sudo supervisorctl start mindova-worker:*

# Start specific worker
sudo supervisorctl start mindova-worker:mindova-worker_00
```

### Stop Workers

```bash
# Stop all workers
sudo supervisorctl stop mindova-worker:*

# Stop specific worker
sudo supervisorctl stop mindova-worker:mindova-worker_00
```

### Restart Workers

```bash
# Restart all workers
sudo supervisorctl restart mindova-worker:*

# Restart specific worker
sudo supervisorctl restart mindova-worker:mindova-worker_00
```

### View Logs

```bash
# Tail the log file
sudo tail -f /var/www/mindova/storage/logs/worker.log

# View with supervisorctl
sudo supervisorctl tail -f mindova-worker:mindova-worker_00
```

---

## Deployment Workflow

When deploying new code, you should restart workers to pick up changes:

### Manual Deployment

```bash
# Pull latest code
cd /var/www/mindova
git pull origin main

# Update dependencies
composer install --optimize-autoloader --no-dev

# Run migrations
php artisan migrate --force

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart queue workers
sudo supervisorctl restart mindova-worker:*
```

### Automated Deployment Script

Create a deployment script `deploy.sh`:

```bash
#!/bin/bash

cd /var/www/mindova

# Pull latest code
git pull origin main

# Install dependencies
composer install --optimize-autoloader --no-dev

# Run migrations
php artisan migrate --force

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart workers
sudo supervisorctl restart mindova-worker:*

echo "Deployment complete!"
```

Make it executable:
```bash
chmod +x deploy.sh
```

---

## Troubleshooting

### Workers Not Starting

**Check configuration syntax:**
```bash
sudo supervisorctl reread
```

**Check logs:**
```bash
sudo tail -f /var/log/supervisor/supervisord.log
```

**Check worker logs:**
```bash
sudo tail -f /var/www/mindova/storage/logs/worker.log
```

### Permission Denied Errors

```bash
# Fix storage permissions
sudo chown -R www-data:www-data /var/www/mindova/storage
sudo chmod -R 775 /var/www/mindova/storage
```

### Workers Keep Restarting

Check Laravel logs for errors:
```bash
tail -f /var/www/mindova/storage/logs/laravel.log
```

Common issues:
- Database connection errors
- Missing environment variables
- Memory limits exceeded

### Workers Not Processing Jobs

**Verify queue configuration in `.env`:**
```env
QUEUE_CONNECTION=database
# or
QUEUE_CONNECTION=redis
```

**Check pending jobs:**
```bash
php artisan queue:monitor
```

**Manually process a job:**
```bash
php artisan queue:work --once
```

---

## Monitoring

### Check Worker Status

```bash
# Quick status check
sudo supervisorctl status mindova-worker:*

# Detailed info
sudo supervisorctl tail -f mindova-worker:mindova-worker_00
```

### Monitor Queue Performance

Create a monitoring script `monitor-queue.sh`:

```bash
#!/bin/bash

echo "=== Supervisor Status ==="
sudo supervisorctl status mindova-worker:*

echo ""
echo "=== Recent Worker Logs ==="
sudo tail -n 20 /var/www/mindova/storage/logs/worker.log

echo ""
echo "=== Failed Jobs ==="
cd /var/www/mindova
php artisan queue:failed
```

### Set Up Alerts

You can configure Supervisor to send email alerts when workers fail.

Edit `/etc/supervisor/supervisord.conf`:

```ini
[eventlistener:crashmail]
command=/usr/bin/crashmail -a -m your-email@example.com
events=PROCESS_STATE_EXITED
```

---

## Production Best Practices

### 1. Use Redis for Queue

For better performance, use Redis instead of database:

**.env:**
```env
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

Install Redis:
```bash
sudo apt-get install redis-server
```

### 2. Separate Queue Workers by Priority

Create multiple worker configurations for different queues:

**High Priority Workers:**
```ini
[program:mindova-worker-high]
command=php /var/www/mindova/artisan queue:work --queue=high --sleep=1 --tries=3
numprocs=2
```

**Default Workers:**
```ini
[program:mindova-worker-default]
command=php /var/www/mindova/artisan queue:work --queue=default --sleep=3 --tries=3
numprocs=4
```

**Low Priority Workers:**
```ini
[program:mindova-worker-low]
command=php /var/www/mindova/artisan queue:work --queue=low --sleep=5 --tries=1
numprocs=1
```

### 3. Monitor Memory Usage

Add memory limit to prevent memory leaks:

```ini
command=php /var/www/mindova/artisan queue:work --sleep=3 --tries=3 --memory=512
```

### 4. Log Rotation

Configure log rotation to prevent log files from growing too large.

Create `/etc/logrotate.d/mindova`:

```
/var/www/mindova/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
    sharedscripts
}
```

### 5. Auto-restart on Code Changes

For automatic worker restart on deployment, add to your deploy script:

```bash
# After successful deployment
sudo supervisorctl restart mindova-worker:*
```

---

## Email Notification Configuration

### Ensure Queue Works for Emails

The certificate email notification system uses queued emails. Supervisor ensures these are processed:

1. **Email is queued** when certificates are generated
2. **Supervisor workers** process the queue
3. **Emails are sent** via SMTP (Gmail)

**Verify email queue is working:**

```bash
# Check if workers are running
sudo supervisorctl status

# Monitor email jobs being processed
sudo tail -f /var/www/mindova/storage/logs/worker.log | grep -i "mail"
```

---

## System Boot Configuration

### Ensure Supervisor Starts on Boot

**Ubuntu/Debian:**
```bash
sudo systemctl enable supervisor
sudo systemctl start supervisor
```

**CentOS/RHEL:**
```bash
sudo systemctl enable supervisord
sudo systemctl start supervisord
```

**Verify:**
```bash
sudo systemctl status supervisor
```

---

## Complete Setup Checklist

- [ ] Install Supervisor
- [ ] Copy configuration file to `/etc/supervisor/conf.d/`
- [ ] Update paths in configuration
- [ ] Set correct user (www-data, nginx, etc.)
- [ ] Adjust number of workers (numprocs)
- [ ] Set correct file permissions
- [ ] Create log file and make it writable
- [ ] Reload Supervisor configuration
- [ ] Start workers
- [ ] Verify workers are running
- [ ] Test job processing
- [ ] Configure log rotation
- [ ] Enable Supervisor on system boot
- [ ] Test worker restart after deployment
- [ ] Set up monitoring/alerts (optional)

---

## Quick Reference Commands

```bash
# Reload config after changes
sudo supervisorctl reread && sudo supervisorctl update

# Restart all workers
sudo supervisorctl restart mindova-worker:*

# View status
sudo supervisorctl status

# View logs
sudo supervisorctl tail -f mindova-worker:mindova-worker_00

# Stop all workers
sudo supervisorctl stop mindova-worker:*

# Start all workers
sudo supervisorctl start mindova-worker:*
```

---

## Testing the Setup

### Test 1: Generate Certificates

1. Log in as a company
2. Complete a challenge
3. Generate certificates
4. Check that email is queued and sent

### Test 2: Monitor Worker Processing

```bash
# In one terminal, watch the logs
sudo tail -f /var/www/mindova/storage/logs/worker.log

# In another terminal, trigger a job
cd /var/www/mindova
php artisan queue:work --once
```

### Test 3: Simulate Worker Crash

```bash
# Kill a worker process
sudo supervisorctl stop mindova-worker:mindova-worker_00

# Wait a few seconds and check status
sudo supervisorctl status

# It should automatically restart
```

---

## Support

### Log Locations

- **Supervisor logs:** `/var/log/supervisor/supervisord.log`
- **Worker logs:** `/var/www/mindova/storage/logs/worker.log`
- **Laravel logs:** `/var/www/mindova/storage/logs/laravel.log`

### Common Issues

1. **Workers not starting:** Check permissions and paths
2. **Jobs not processing:** Verify queue configuration in `.env`
3. **Memory errors:** Increase PHP memory limit or add `--memory` flag
4. **Timeout errors:** Increase `--timeout` parameter

---

## Conclusion

With Supervisor properly configured:

✅ Queue workers start automatically on server boot
✅ Workers restart automatically if they crash
✅ Multiple workers process jobs in parallel
✅ Email notifications are sent reliably
✅ Certificate generation triggers emails automatically
✅ Production deployment is seamless

---

**Last Updated:** December 24, 2025
**Status:** Production Ready
**Tested:** Ubuntu 20.04, Ubuntu 22.04
