@echo off
title Mindova Platform - Startup
color 0A

echo ========================================
echo    MINDOVA PLATFORM - QUICK START
echo ========================================
echo.

:MENU
echo Choose an option:
echo.
echo [1] Setup Database (First Time Only)
echo [2] Seed Test Data
echo [3] Start Application Server
echo [4] Start Queue Worker
echo [5] Start Both (Server + Queue)
echo [6] Run Migrations
echo [7] Clear All Caches
echo [8] Exit
echo.
set /p choice="Enter your choice (1-8): "

if "%choice%"=="1" goto SETUP_DB
if "%choice%"=="2" goto SEED_DATA
if "%choice%"=="3" goto START_SERVER
if "%choice%"=="4" goto START_QUEUE
if "%choice%"=="5" goto START_BOTH
if "%choice%"=="6" goto RUN_MIGRATIONS
if "%choice%"=="7" goto CLEAR_CACHE
if "%choice%"=="8" goto EXIT

echo Invalid choice. Please try again.
echo.
goto MENU

:SETUP_DB
echo.
echo ========================================
echo   DATABASE SETUP
echo ========================================
echo.
echo Step 1: Creating database...
C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS mindova CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
echo Database created!
echo.
echo Step 2: Running migrations...
php artisan migrate --force
echo.
echo ✅ Database setup complete!
echo.
pause
goto MENU

:SEED_DATA
echo.
echo ========================================
echo   SEEDING TEST DATA
echo ========================================
echo.
php artisan db:seed --class=TestDataSeeder
echo.
echo ✅ Test data seeded!
echo.
echo Test Accounts Created:
echo   Companies: company1@mindova.test / password
echo   Volunteers: john@mindova.test / password
echo.
pause
goto MENU

:START_SERVER
echo.
echo ========================================
echo   STARTING APPLICATION SERVER
echo ========================================
echo.
echo Server will start at: http://localhost:8000
echo Press Ctrl+C to stop
echo.
php artisan serve
pause
goto MENU

:START_QUEUE
echo.
echo ========================================
echo   STARTING QUEUE WORKER
echo ========================================
echo.
echo Queue worker is processing AI analysis jobs...
echo Press Ctrl+C to stop
echo.
php artisan queue:work --tries=3 --timeout=300
pause
goto MENU

:START_BOTH
echo.
echo ========================================
echo   STARTING SERVER AND QUEUE WORKER
echo ========================================
echo.
echo Opening 2 windows:
echo   1. Application Server (http://localhost:8000)
echo   2. Queue Worker (Background Jobs)
echo.
start "Mindova Server" cmd /k "title Mindova Server && color 0B && php artisan serve"
start "Mindova Queue" cmd /k "title Mindova Queue Worker && color 0E && php artisan queue:work --tries=3 --timeout=300"
echo.
echo ✅ Both services started!
echo   Close the windows to stop them.
echo.
pause
goto MENU

:RUN_MIGRATIONS
echo.
echo ========================================
echo   RUNNING MIGRATIONS
echo ========================================
echo.
php artisan migrate
echo.
echo ✅ Migrations complete!
echo.
pause
goto MENU

:CLEAR_CACHE
echo.
echo ========================================
echo   CLEARING CACHES
echo ========================================
echo.
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo.
echo ✅ All caches cleared!
echo.
pause
goto MENU

:EXIT
echo.
echo Thank you for using Mindova Platform!
echo.
exit
