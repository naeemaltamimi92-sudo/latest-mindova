@echo off
echo ========================================
echo Fixing Mindova Database Migrations
echo ========================================
echo.

echo Step 1: Rolling back failed migrations...
php artisan migrate:rollback
echo.

echo Step 2: Refreshing database (this will drop all tables)...
php artisan migrate:fresh
echo.

echo ========================================
echo Migration fix complete!
echo ========================================
pause
