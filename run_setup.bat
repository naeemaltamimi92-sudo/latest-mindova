@echo off
title Mindova - Complete Setup
color 0A

echo ========================================
echo   MINDOVA PLATFORM - COMPLETE SETUP
echo ========================================
echo.

echo Step 1: Deleting old migration with wrong timestamp...
if exist database\migrations\2025_12_18_055253_create_challenge_analyses_table.php (
    del /F database\migrations\2025_12_18_055253_create_challenge_analyses_table.php
    echo ✓ Old file deleted
) else (
    echo ✓ No old file found (already clean)
)
echo.

echo Step 2: Creating database...
C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS mindova CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
echo ✓ Database created
echo.

echo Step 3: Clearing all caches...
php artisan config:clear
php artisan cache:clear
echo ✓ Caches cleared
echo.

echo Step 4: Running fresh migrations...
php artisan migrate:fresh --force
echo.

if %ERRORLEVEL% NEQ 0 (
    echo.
    echo ❌ Migration failed! Check the error above.
    echo.
    pause
    exit /b 1
)

echo ✓ Migrations completed successfully!
echo.

echo Step 5: Would you like to seed test data? (Y/N)
set /p seed="Enter choice: "

if /i "%seed%"=="Y" (
    echo.
    echo Seeding test data...
    php artisan db:seed --class=TestDataSeeder
    echo.
    echo ✓ Test data seeded!
    echo.
    echo Test Accounts:
    echo   📧 Volunteers: john@mindova.test / password
    echo   📧 Companies: company1@mindova.test / password
)

echo.
echo ========================================
echo   SETUP COMPLETE! 🎉
echo ========================================
echo.
echo Next steps:
echo   1. Run: start.bat
echo   2. Choose option 5 (Start Both)
echo   3. Open: http://localhost:8000
echo.
pause
