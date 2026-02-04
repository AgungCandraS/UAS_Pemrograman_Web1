@echo off
echo ========================================
echo Preparing Files for Rumahweb Upload
echo Domain: acas.my.id
echo ========================================
echo.

REM Create backup folder
echo [1/5] Creating backup...
if not exist "backup" mkdir backup
xcopy /E /I /Y "." "backup\bisnisku-backup-%date:~-4,4%%date:~-10,2%%date:~-7,2%" > nul
echo       Done!

REM Copy production config
echo [2/5] Setting production config...
copy /Y "config\env.production.php" "config\env.php" > nul
echo       Done!

REM Copy production htaccess
echo [3/5] Setting production .htaccess...
copy /Y ".htaccess.production" ".htaccess" > nul
echo       Done!

REM Create necessary folders
echo [4/5] Checking folders...
if not exist "storage\uploads" mkdir "storage\uploads"
if not exist "storage\exports" mkdir "storage\exports"
echo       Done!

REM Create ZIP for upload
echo [5/5] Creating ZIP file...
echo.
echo Creating bisnisku-upload.zip...
echo This may take a moment...
powershell Compress-Archive -Path .\* -DestinationPath bisnisku-upload.zip -Force
echo       Done!

echo.
echo ========================================
echo  PREPARATION COMPLETE!
echo ========================================
echo.
echo Next Steps:
echo 1. Edit config\env.php - Update database info
echo 2. Upload bisnisku-upload.zip to Rumahweb
echo 3. Extract in public_html via File Manager
echo 4. Import database.sql via phpMyAdmin
echo 5. Set folder permissions (storage = 777)
echo 6. Test website: https://acas.my.id
echo.
echo File: bisnisku-upload.zip
echo Size: 
dir bisnisku-upload.zip | find "bisnisku-upload.zip"
echo.
pause
