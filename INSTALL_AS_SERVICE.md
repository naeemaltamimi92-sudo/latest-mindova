# Install Mindova as Windows Service

This guide shows how to run Mindova as a Windows Service using NSSM (Non-Sucking Service Manager).

## Benefits:
- ✅ Runs in background (no visible windows)
- ✅ Starts automatically on computer boot
- ✅ Keeps running even if you log out
- ✅ Automatically restarts if it crashes
- ✅ Managed through Windows Services

---

## Step 1: Download NSSM

1. Go to: https://nssm.cc/download
2. Download the latest version (nssm-2.24.zip or newer)
3. Extract the ZIP file
4. Copy `nssm.exe` from `win64` folder to: `C:\xampp\htdocs\mindova\`

---

## Step 2: Create Service Scripts

### Create Laravel Server Service:
```batch
cd C:\xampp\htdocs\mindova
nssm install MindovaServer "C:\xampp\php\php.exe" "artisan serve --host=0.0.0.0 --port=8000"
nssm set MindovaServer AppDirectory "C:\xampp\htdocs\mindova"
nssm set MindovaServer DisplayName "Mindova Laravel Server"
nssm set MindovaServer Description "Mindova platform web server on port 8000"
nssm set MindovaServer Start SERVICE_AUTO_START
```

### Create Queue Worker Service:
```batch
cd C:\xampp\htdocs\mindova
nssm install MindovaQueue "C:\xampp\php\php.exe" "artisan queue:work --sleep=3 --tries=3 --timeout=300"
nssm set MindovaQueue AppDirectory "C:\xampp\htdocs\mindova"
nssm set MindovaQueue DisplayName "Mindova Queue Worker"
nssm set MindovaQueue Description "Mindova background job processor"
nssm set MindovaQueue Start SERVICE_AUTO_START
nssm set MindovaQueue AppStopMethodSkip 0
nssm set MindovaQueue AppStopMethodConsole 30000
```

---

## Step 3: Start Services

**Using Command Prompt (Run as Administrator):**
```batch
net start MindovaServer
net start MindovaQueue
```

**Or using Windows Services:**
1. Press `Win + R`
2. Type: `services.msc`
3. Find "Mindova Laravel Server" and "Mindova Queue Worker"
4. Right-click → Start

---

## Step 4: Verify Services are Running

**Check in Services:**
```
Win + R → services.msc
Look for "Mindova Laravel Server" and "Mindova Queue Worker"
Status should show "Running"
```

**Test the platform:**
```
Open browser: http://localhost:8000
Should show Mindova platform
```

---

## Management Commands

### Start Services:
```batch
net start MindovaServer
net start MindovaQueue
```

### Stop Services:
```batch
net stop MindovaQueue
net stop MindovaServer
```

### Restart Services:
```batch
net stop MindovaQueue
net stop MindovaServer
net start MindovaServer
net start MindovaQueue
```

### Remove Services (if needed):
```batch
nssm stop MindovaQueue
nssm stop MindovaServer
nssm remove MindovaQueue confirm
nssm remove MindovaServer confirm
```

---

## View Service Logs

**Using NSSM GUI:**
```batch
nssm edit MindovaServer
nssm edit MindovaQueue
```

**Check Laravel Logs:**
```
C:\xampp\htdocs\mindova\storage\logs\laravel.log
```

---

## Troubleshooting

### Services won't start:
1. Check PHP path is correct: `C:\xampp\php\php.exe`
2. Verify working directory: `C:\xampp\htdocs\mindova`
3. Check Windows Event Viewer for errors
4. Make sure MySQL service is running

### Queue worker stops working:
```batch
net stop MindovaQueue
net start MindovaQueue
```

### Need to update code:
```batch
# Stop services first
net stop MindovaQueue
net stop MindovaServer

# Update your code
# Then restart:
net start MindovaServer
net start MindovaQueue
```

---

## Automatic Service Recovery

Configure services to auto-restart on failure:

```batch
# For Server
nssm set MindovaServer AppExit Default Restart
nssm set MindovaServer AppRestartDelay 5000

# For Queue Worker
nssm set MindovaQueue AppExit Default Restart
nssm set MindovaQueue AppRestartDelay 5000
```

---

## Dependencies

Make sure these services start in order:
1. MySQL (XAMPP)
2. MindovaServer
3. MindovaQueue

**Set dependencies:**
```batch
nssm set MindovaServer DependOnService MySQL
nssm set MindovaQueue DependOnService MindovaServer
```

---

## Uninstall Everything

If you want to remove all services:

```batch
# Stop all services
net stop MindovaQueue
net stop MindovaServer

# Remove services
nssm remove MindovaQueue confirm
nssm remove MindovaServer confirm

# Verify removal
services.msc
# "Mindova" services should be gone
```

---

**Note:** After installing as services, you don't need to run `start-mindova.bat` anymore!
The services will start automatically when your computer boots.
