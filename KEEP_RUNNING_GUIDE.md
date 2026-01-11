# 🔄 How to Keep Mindova Running Continuously

This guide shows you different ways to keep Mindova running based on your needs.

---

## 🎯 OPTION 1: Simple Method (Just Minimize Windows)

**Best for:** Daily development work

**How it works:**
1. Double-click: `start-mindova.bat`
2. Two windows open (Server + Queue Worker)
3. **Click minimize (─) on both windows**
4. Windows stay in taskbar
5. Mindova keeps running

**Pros:**
- ✅ Super easy
- ✅ No configuration needed
- ✅ Can see logs in real-time
- ✅ Easy to stop (just close windows)

**Cons:**
- ❌ Stops if you close windows
- ❌ Stops if computer restarts
- ❌ Need to start manually each time

**When computer sleeps:** Processes keep running ✅
**When you log out:** Processes stop ❌

---

## 🚀 OPTION 2: Auto-Start on Login

**Best for:** Daily use without manual starting

**Setup (One-time):**
1. Double-click: `setup-autostart.bat`
2. Done!

**How it works:**
- Mindova starts automatically when you login to Windows
- Two windows open automatically
- Minimize them to taskbar
- They stay running until you log out

**Pros:**
- ✅ Automatic startup
- ✅ No manual intervention needed
- ✅ Easy to disable

**Cons:**
- ❌ Windows still visible (need to minimize)
- ❌ Stops if you log out

**To disable:**
1. Press `Win + R`
2. Type: `shell:startup`
3. Delete "Mindova Platform" shortcut

---

## 💎 OPTION 3: Windows Service (Professional)

**Best for:** Production/Always-on server

**Setup (One-time):**

### Step 1: Download NSSM
1. Go to: https://nssm.cc/download
2. Download latest version
3. Extract `nssm.exe` from `win64` folder
4. Put `nssm.exe` in: `C:\xampp\htdocs\mindova\`

### Step 2: Install Services
1. Right-click `install-services.bat`
2. Select "Run as administrator"
3. Follow prompts
4. Done!

**How it works:**
- Mindova runs as Windows Service
- No visible windows
- Runs in background completely
- Starts on computer boot
- Keeps running even if you log out
- Auto-restarts if it crashes

**Pros:**
- ✅ True background service
- ✅ Starts on boot automatically
- ✅ Runs without login
- ✅ Auto-restart on failure
- ✅ Professional setup

**Cons:**
- ❌ Requires NSSM download
- ❌ Needs administrator rights
- ❌ Slightly more complex setup

**Manage services:**
```batch
# Start
net start MindovaServer
net start MindovaQueue

# Stop
net stop MindovaServer
net stop MindovaQueue

# View in Windows Services
Win + R → services.msc
```

**To uninstall:**
1. Right-click `uninstall-services.bat`
2. Select "Run as administrator"

---

## 📊 COMPARISON TABLE

| Feature | Minimize Windows | Auto-Start | Windows Service |
|---------|-----------------|------------|-----------------|
| Setup difficulty | ⭐ Easy | ⭐ Easy | ⭐⭐⭐ Medium |
| Starts on boot | ❌ No | ❌ No | ✅ Yes |
| Runs when logged out | ❌ No | ❌ No | ✅ Yes |
| Background (no windows) | ❌ No | ❌ No | ✅ Yes |
| Auto-restart on crash | ❌ No | ❌ No | ✅ Yes |
| Easy to stop | ✅ Yes | ✅ Yes | ⭐⭐ Medium |
| See logs easily | ✅ Yes | ✅ Yes | ❌ No* |
| Requires admin | ❌ No | ❌ No | ✅ Yes |

*Logs available in `storage/logs/laravel.log`

---

## 🎯 WHICH OPTION TO CHOOSE?

### Choose **OPTION 1** if:
- You're developing/testing
- You restart computer rarely
- You want to see logs in real-time
- You want simplest setup

### Choose **OPTION 2** if:
- You use Mindova daily
- You don't want to start it manually
- You're okay with minimized windows
- You stay logged in most of the time

### Choose **OPTION 3** if:
- This is a production server
- You need 24/7 uptime
- Multiple users access it
- You want professional setup
- Computer needs to restart often

---

## 🔧 TROUBLESHOOTING

### Windows close accidentally
**Option 1:** Just run `start-mindova.bat` again
**Option 2:** Log out and back in (auto-starts)
**Option 3:** Services keep running regardless

### Computer restarts
**Option 1:** Run `start-mindova.bat` manually
**Option 2:** Log in (auto-starts)
**Option 3:** Services start automatically

### Need to update code
**Option 1 & 2:**
```batch
1. Close both windows
2. Update code
3. Run start-mindova.bat again
```

**Option 3:**
```batch
1. net stop MindovaQueue
2. net stop MindovaServer
3. Update code
4. net start MindovaServer
5. net start MindovaQueue
```

### Check if running
**Option 1 & 2:**
- Look for windows in taskbar
- Open: http://localhost:8000

**Option 3:**
```batch
# Check services
services.msc
# Or
net start | findstr Mindova

# Check website
http://localhost:8000
```

---

## 📝 RECOMMENDED APPROACH

**For most users:**
1. Start with **Option 1** (minimize windows)
2. If you get tired of starting manually, upgrade to **Option 2** (auto-start)
3. If you need production setup, upgrade to **Option 3** (Windows service)

You can switch between options anytime!

---

## 🎓 STEP-BY-STEP GUIDE

### For Daily Development Use:

**Morning:**
```
1. Turn on computer
2. Open XAMPP → Start MySQL
3. Double-click: start-mindova.bat
4. Minimize both windows
5. Open: http://localhost:8000
6. Start working!
```

**Evening:**
```
1. Close both Mindova windows
2. Stop MySQL in XAMPP (optional)
3. Shut down computer
```

### For Production Server:

**One-time setup:**
```
1. Download NSSM
2. Run install-services.bat as admin
3. Verify services running
4. Done! Never worry about it again
```

**Daily use:**
```
Nothing! It's always running automatically.
Just open: http://localhost:8000
```

---

## 🚨 IMPORTANT NOTES

### MySQL Must Be Running
Regardless of which option you choose, **MySQL must be running** in XAMPP!

**Check MySQL:**
```
XAMPP Control Panel → MySQL should show "Running" (green)
```

**If Mindova doesn't work:**
1. Check MySQL is running
2. Check Laravel server is running
3. Check Queue worker is running
4. Check: http://localhost:8000

---

**Need help?** Check `QUICK_START_GUIDE.md` for troubleshooting!
