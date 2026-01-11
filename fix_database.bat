@echo off
echo ========================================
echo Mindova Database Setup Fix
echo ========================================
echo.

echo Step 1: Deleting old migration file with wrong timestamp...
del /F database\migrations\2025_12_18_055253_create_challenge_analyses_table.php 2>nul
echo Old migration file removed.
echo.

echo Step 2: Rolling back any partial migrations...
php artisan migrate:rollback --force
echo.

echo Step 3: Running fresh migrations...
php artisan migrate:fresh --force
echo.

echo Step 4: Verifying migration status...
php artisan migrate:status
echo.

echo ========================================
echo Database setup complete!
echo ========================================
echo.
echo You can now:
echo 1. Start the server: php artisan serve
echo 2. Start queue worker: php artisan queue:work
echo.
pause
