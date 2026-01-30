@echo off
REM MediaLAN Quick Setup Script
REM This script sets up MediaLAN without needing full composer installation

cd /d c:\xampp\htdocs\MediaLAN

echo.
echo [MediaLAN Setup] Cleaning up failed attempts...
rmdir /s /q vendor 2>nul
del composer.lock 2>nul

echo [MediaLAN Setup] Attempting composer install with optimized settings...
echo.

REM Try with minimal flags - just install what we need
set COMPOSER_MEMORY_LIMIT=-1
set COMPOSER_DISCARD_CHANGES=1

c:\xampp\php\php.exe c:\composer\composer install ^
  --no-dev ^
  --prefer-dist ^
  --no-audit ^
  --no-scripts ^
  --no-interaction ^
  --ignore-platform-req=php

if %ERRORLEVEL% NEQ 0 (
    echo.
    echo [ERROR] Composer install failed!
    echo.
    echo Alternative: Try these manual steps:
    echo 1. Download Laravel 8 vendor folder from: https://github.com/laravel/laravel/releases/tag/v8.83.0
    echo 2. Extract to: c:\xampp\htdocs\MediaLAN\vendor
    echo 3. Run: php artisan migrate
    echo 4. Run: php artisan serve --host=0.0.0.0 --port=8000
    pause
    exit /b 1
)

echo.
echo [MediaLAN Setup] ✓ Composer install completed!
echo.
echo Next steps:
echo   1. Run: php artisan migrate
echo   2. Run: php artisan db:seed
echo   3. Run: php artisan serve --host=0.0.0.0 --port=8000
echo.
pause
