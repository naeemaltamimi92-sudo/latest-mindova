@echo off
echo ========================================
echo Starting Mindova Platform
echo ========================================
echo.

cd /d "C:\xampp\htdocs\mindova"

echo [1/3] Checking MySQL...
echo Please make sure MySQL is running in XAMPP!
echo.

echo [2/3] Starting Laravel Server on port 8000...
start "Mindova Server" cmd /k "cd /d C:\xampp\htdocs\mindova && php artisan serve --host=0.0.0.0 --port=8000"
timeout /t 3 >nul

echo [3/3] Starting Queue Worker for background jobs...
start "Mindova Queue Worker" cmd /k "cd /d C:\xampp\htdocs\mindova && php artisan queue:work --sleep=3 --tries=3 --timeout=300"
timeout /t 2 >nul

echo.
echo ========================================
echo Mindova is now running!
echo ========================================
echo.
echo Server: http://localhost:8000
echo.
echo TWO windows have opened:
echo 1. Mindova Server (website)
echo 2. Mindova Queue Worker (CV analysis, etc.)
echo.
echo IMPORTANT: Keep BOTH windows open!
echo Close them to stop Mindova.
echo.
echo Press any key to open Mindova in your browser...
pause >nul

start http://localhost:8000

echo.
echo Mindova is running!
echo To stop: Close the two command windows
echo.
