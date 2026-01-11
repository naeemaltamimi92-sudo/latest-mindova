@echo off
echo Setting up Mindova to auto-start on Windows login...
echo.

REM Get the startup folder path
set STARTUP_FOLDER=%APPDATA%\Microsoft\Windows\Start Menu\Programs\Startup

REM Create shortcut to start-mindova.bat
echo Creating shortcut in Startup folder...
powershell -Command "$WS = New-Object -ComObject WScript.Shell; $SC = $WS.CreateShortcut('%STARTUP_FOLDER%\Mindova Platform.lnk'); $SC.TargetPath = 'C:\xampp\htdocs\mindova\start-mindova.bat'; $SC.WorkingDirectory = 'C:\xampp\htdocs\mindova'; $SC.Description = 'Mindova Platform - Laravel Server and Queue Worker'; $SC.Save()"

echo.
echo ✅ Done! Mindova will now start automatically when you login to Windows.
echo.
echo To disable auto-start:
echo 1. Press Win + R
echo 2. Type: shell:startup
echo 3. Delete "Mindova Platform" shortcut
echo.
pause
