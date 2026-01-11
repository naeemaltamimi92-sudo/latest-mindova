@echo off
REM Mindova Platform - Install as Windows Services
REM This script requires NSSM (Non-Sucking Service Manager)

echo ========================================
echo Mindova Platform Service Installer
echo ========================================
echo.

REM Check if running as administrator
net session >nul 2>&1
if %errorLevel% neq 0 (
    echo ERROR: This script must be run as Administrator!
    echo.
    echo Right-click this file and select "Run as administrator"
    echo.
    pause
    exit /b 1
)

echo ✓ Running as Administrator
echo.

REM Check if NSSM exists
if not exist "nssm.exe" (
    echo ERROR: nssm.exe not found!
    echo.
    echo Please download NSSM from: https://nssm.cc/download
    echo Extract nssm.exe to this folder: C:\xampp\htdocs\mindova\
    echo.
    pause
    exit /b 1
)

echo ✓ NSSM found
echo.

REM Check if services already exist
sc query MindovaServer >nul 2>&1
if %errorLevel% equ 0 (
    echo Service MindovaServer already exists. Removing...
    nssm stop MindovaServer
    nssm remove MindovaServer confirm
)

sc query MindovaQueue >nul 2>&1
if %errorLevel% equ 0 (
    echo Service MindovaQueue already exists. Removing...
    nssm stop MindovaQueue
    nssm remove MindovaQueue confirm
)

echo.
echo Installing Laravel Server Service...
nssm install MindovaServer "C:\xampp\php\php.exe" "artisan serve --host=0.0.0.0 --port=8000"
nssm set MindovaServer AppDirectory "C:\xampp\htdocs\mindova"
nssm set MindovaServer DisplayName "Mindova Laravel Server"
nssm set MindovaServer Description "Mindova platform web server on port 8000"
nssm set MindovaServer Start SERVICE_AUTO_START
nssm set MindovaServer AppExit Default Restart
nssm set MindovaServer AppRestartDelay 5000
echo ✓ MindovaServer service installed

echo.
echo Installing Queue Worker Service...
nssm install MindovaQueue "C:\xampp\php\php.exe" "artisan queue:work --sleep=3 --tries=3 --timeout=300"
nssm set MindovaQueue AppDirectory "C:\xampp\htdocs\mindova"
nssm set MindovaQueue DisplayName "Mindova Queue Worker"
nssm set MindovaQueue Description "Mindova background job processor"
nssm set MindovaQueue Start SERVICE_AUTO_START
nssm set MindovaQueue AppExit Default Restart
nssm set MindovaQueue AppRestartDelay 5000
nssm set MindovaQueue AppStopMethodSkip 0
nssm set MindovaQueue AppStopMethodConsole 30000
echo ✓ MindovaQueue service installed

echo.
echo Starting services...
net start MindovaServer
net start MindovaQueue

echo.
echo ========================================
echo ✓ Installation Complete!
echo ========================================
echo.
echo Services installed:
echo   • Mindova Laravel Server (port 8000)
echo   • Mindova Queue Worker
echo.
echo The services will:
echo   ✓ Start automatically when Windows boots
echo   ✓ Run in the background (no visible windows)
echo   ✓ Restart automatically if they crash
echo.
echo To view services:
echo   Win + R → services.msc
echo   Look for "Mindova Laravel Server" and "Mindova Queue Worker"
echo.
echo To manage services:
echo   Start:   net start MindovaServer    net start MindovaQueue
echo   Stop:    net stop MindovaServer     net stop MindovaQueue
echo   Restart: net stop + net start (both)
echo.
echo To uninstall services:
echo   Run: uninstall-services.bat (as Administrator)
echo.
echo Open browser to test: http://localhost:8000
echo.
pause
