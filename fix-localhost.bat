@echo off
echo Fixing localhost for Laravel...
echo.

echo Step 1: Stopping IIS (Internet Information Services)...
net stop w3svc
echo.

echo Step 2: Starting XAMPP Apache...
echo Please start Apache from XAMPP Control Panel manually
echo.

echo Step 3: After Apache starts, press any key to test...
pause

echo Testing localhost...
curl -I http://localhost/
echo.

echo If you see "Apache" in the server name above, it's working!
echo If you still see "IIS", Apache is not running.
pause
