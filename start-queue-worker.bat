@echo off
REM Queue Worker for Mindova Platform
REM This script keeps the queue worker running continuously

echo Starting Mindova Queue Worker...
echo.
echo This window must remain open for background jobs to process.
echo Jobs include: CV Analysis, Challenge Analysis, Task Matching, etc.
echo.
echo Press Ctrl+C to stop the worker.
echo.

cd /d "C:\xampp\htdocs\mindova"

:loop
php artisan queue:work --sleep=3 --tries=3 --timeout=300
echo.
echo Worker stopped. Restarting in 5 seconds...
timeout /t 5
goto loop
