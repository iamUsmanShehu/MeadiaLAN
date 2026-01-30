@echo off
cd /d C:\xampp\htdocs\MediaLAN
echo Removing old vendor directory...
if exist vendor rmdir /s /q vendor
echo Removing composer.lock...
if exist composer.lock del composer.lock
echo Starting composer install...
composer install --ignore-platform-reqs --no-interaction
if errorlevel 1 (
    echo Composer install failed with error code %errorlevel%
    exit /b %errorlevel%
)
echo Composer install completed successfully!
echo Starting PHP artisan serve...
php artisan serve
pause
