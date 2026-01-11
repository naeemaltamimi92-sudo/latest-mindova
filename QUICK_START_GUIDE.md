# 🚀 Mindova Platform - Quick Start Guide

## 🎯 Start the Platform (Every Time)

### Step 1: Start MySQL
```
1. Open XAMPP Control Panel
2. Click "Start" next to MySQL
3. Wait for green "Running" status
```

### Step 2: Start Mindova
```
Double-click: start-mindova.bat
```

**This opens TWO windows:**
- 🌐 **Window 1:** Laravel Server (port 8000)
- ⚙️ **Window 2:** Queue Worker (background jobs)

**IMPORTANT:** Keep BOTH windows open!

### Step 3: Access Platform
```
Open browser: http://localhost:8000
```

---

## 👥 USER ROLES & FLOWS

### 🏢 COMPANY USER

**Registration:**
1. Go to http://localhost:8000
2. Click "Register"
3. Fill company details
4. Complete profile

**Create Challenge:**
1. Login → Dashboard
2. Click "+ Submit New Challenge"
3. Fill title and description
4. Submit
5. **Wait 60 seconds** - AI analyzes and creates tasks

**What Happens:**
- AI scores challenge 1-10
- **Score 1-2:** Goes to community discussion
- **Score 3-10:** Broken into tasks → Assigned to volunteers

**View Progress:**
1. Dashboard shows your challenges
2. Progress bars show completion
3. Task status visible
4. Estimated time remaining

---

### 👨‍💻 VOLUNTEER USER

**Registration:**
1. Go to http://localhost:8000
2. Click "Register" or "Login with LinkedIn"
3. Complete profile
4. **Upload CV** (REQUIRED for AI analysis)
5. **Wait 30 seconds** - AI extracts skills

**What Happens:**
- AI analyzes your CV
- Extracts: Skills, Experience Level, Years
- Stores in database forever
- Matches you to relevant tasks

**Find Tasks:**
1. Dashboard → See pending invitations
2. Community → See low-level challenges
3. Accept task → Submit solution

**Submit Solution:**
1. Go to assigned task
2. Upload files/attachments
3. Write description
4. Submit
5. **Wait 30 seconds** - AI scores solution

**What Happens:**
- AI scores solution 0-100
- **Score < 60:** Revision required → Resubmit
- **Score ≥ 60:** Accepted → Earns reputation points

---

## 🔄 COMPLETE SYSTEM FLOW

### Low-Score Challenges (1-2):
```
Company creates → AI scores 1-2 → Community discussion →
Volunteers comment → AI scores comments →
High-score comments (≥7) → Notify company
```

### High-Score Challenges (3-10):
```
Company creates → AI scores 3-10 → Break into tasks →
Match volunteers (by field + experience) →
Volunteer submits solution → AI scores →
IF score < 60: Request revision →
IF score ≥ 60: Accept → Aggregate all tasks →
Send complete solution to company
```

---

## ⚡ CRITICAL POINTS

### ❌ WITHOUT Queue Worker:
- CV analysis: NEVER completes
- Challenges: NEVER analyzed
- Tasks: NEVER created
- Solutions: NEVER scored
- Notifications: NEVER sent

### ✅ WITH Queue Worker:
- Everything processes automatically
- 20-30 seconds for most operations
- Real-time notifications
- Instant updates

---

## 🐛 TROUBLESHOOTING

### Problem: CV stays "pending"
**Cause:** Queue worker not running
**Fix:**
```bash
1. Close any open Mindova windows
2. Double-click: start-mindova.bat
3. Wait 30 seconds
4. Refresh dashboard
```

### Problem: LinkedIn login fails
**Cause:** Redirect URI mismatch
**Fix:**
```
1. Go to: https://www.linkedin.com/developers/apps
2. Select your app
3. Auth tab → Redirect URLs
4. Add: http://localhost:8000/auth/linkedin/callback
5. Save
```

### Problem: No tasks showing for challenge
**Cause:** Queue worker not processing
**Fix:**
```bash
Check queue worker window - should show "DONE" messages
If not running, restart: start-mindova.bat
```

### Problem: Skills not updating after CV upload
**Cause:** Queue worker not running OR not waiting long enough
**Fix:**
```bash
1. Ensure queue worker is running (check window)
2. Wait full 30 seconds
3. Hard refresh: Ctrl + F5
4. Check logs: storage/logs/laravel.log
```

---

## 📊 EXPECTED TIMINGS

| Operation | Time |
|-----------|------|
| CV Analysis | 20-30 sec |
| Challenge Analysis | 30-60 sec |
| Task Decomposition | 60-90 sec |
| Solution Scoring | 15-30 sec |
| Comment Scoring | 10-20 sec |

**Always wait the full time before refreshing!**

---

## 🗂️ IMPORTANT FILES

| File | Purpose |
|------|---------|
| `start-mindova.bat` | Start everything (server + queue) |
| `start-server.bat` | Start only Laravel server |
| `start-queue-worker.bat` | Start only queue worker |
| `SYSTEM_VERIFICATION_REPORT.md` | Complete QA report |
| `storage/logs/laravel.log` | System logs |

---

## ✅ DAILY CHECKLIST

**Before using platform:**
- [ ] MySQL running in XAMPP
- [ ] Executed `start-mindova.bat`
- [ ] Both windows open (server + queue)
- [ ] Browser open to http://localhost:8000

**After finishing:**
- [ ] Close browser
- [ ] Close both Mindova windows
- [ ] Stop MySQL in XAMPP (optional)

---

## 🎓 LEARNING PATH

### New Company User:
1. Register account
2. Complete company profile
3. Create first challenge (simple)
4. Wait for AI analysis
5. Check if it goes to community or tasks
6. Monitor progress

### New Volunteer User:
1. Register account (LinkedIn recommended)
2. Upload CV
3. Wait for skills extraction
4. Check dashboard for profile
5. Explore community challenges
6. Accept task invitation
7. Submit solution

---

## 📞 NEED HELP?

**Check Logs:**
```bash
tail -100 storage/logs/laravel.log
```

**Check Queue Status:**
```bash
php artisan queue:work --stop-when-empty
```

**Clear Cache:**
```bash
php artisan config:clear
php artisan cache:clear
```

---

**Last Updated:** December 19, 2025
**Version:** 1.0
**Status:** ✅ Production Ready
