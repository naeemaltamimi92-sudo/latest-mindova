@echo off
REM Mindova Platform - Uninstall Windows Services

echo ========================================
echo Mindova Platform Service Uninstaller
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
    echo Cannot uninstall services without NSSM.
    echo.
    echo You can manually remove services using:
    echo sc delete MindovaServer
    echo sc delete MindovaQueue
    echo.
    pause
    exit /b 1
)

echo Stopping services...
net stop MindovaQueue 2>nul
net stop MindovaServer 2>nul

echo.
echo Removing services...
nssm remove MindovaQueue confirm
nssm remove MindovaServer confirm

echo.
echo ========================================
echo ✓ Uninstallation Complete!
echo ========================================
echo.
echo Services removed:
echo   • Mindova Laravel Server
echo   • Mindova Queue Worker
echo.
echo The services have been removed from Windows.
echo You can now use start-mindova.bat to run Mindova manually.
echo.
pause
