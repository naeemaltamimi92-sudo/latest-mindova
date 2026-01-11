@echo off
echo Starting Mindova Laravel Server on port 8000...
echo.
echo Server will be available at: http://localhost:8000
echo Press Ctrl+C to stop the server
echo.

cd /d "C:\xampp\htdocs\mindova"
php artisan serve --host=0.0.0.0 --port=8000
