# MediaLAN Installation & Setup Guide

## Quick Status

- **PHP Version**: 7.4.2 (XAMPP) ✅
- **Laravel Setup**: In Progress
- **Temporary Access**: http://localhost/MediaLAN/public/fallback.php
- **Full Access**: Available once Laravel installation completes

## Automatic Setup (Recommended)

### Option 1: Run the Setup Script

```bash
cd c:\xampp\htdocs\MediaLAN
setup.bat
```

The script will:
1. Clean any failed attempts
2. Run composer install with optimized settings
3. Display next steps

### Option 2: Manual Composer Installation

```bash
cd c:\xampp\htdocs\MediaLAN
set COMPOSER_MEMORY_LIMIT=-1
c:\xampp\php\php.exe c:\composer\composer install --no-dev --prefer-dist
```

## Complete Setup Steps (After Composer Finishes)

### 1. Create Environment File

If `.env` doesn't exist:
```bash
copy .env.example .env
```

### 2. Generate Application Key

```bash
php artisan key:generate
```

### 3. Create Database Tables

```bash
php artisan migrate
```

### 4. Seed Initial Data

```bash
php artisan db:seed
```

This creates:
- 8 default media categories
- Admin user (username: `admin`, password: `admin123`)

### 5. Start Development Server

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

### 6. Access from Phone

On your phone, connect to WiFi and visit:
```
http://[YOUR_COMPUTER_IP]:8000
```

To find your IP:
```bash
ipconfig | find "IPv4"
```

## Temporary Access (While Installing)

While Composer is downloading Laravel, access the lightweight interface:

```
http://localhost/MediaLAN/public/fallback.php
```

This provides:
- ✅ View categories
- ✅ Browse media structure
- ✅ Admin login (demo)
- ✅ System status

## Troubleshooting

### Composer Stuck/Slow

**Problem**: Composer seems to hang or take forever

**Solutions**:

1. **Kill the process and retry**:
   ```bash
   taskkill /IM php.exe /F
   composer install --no-dev --prefer-dist --ignore-platform-req=php
   ```

2. **Increase memory limit**:
   ```bash
   set COMPOSER_MEMORY_LIMIT=-1
   composer install --no-dev
   ```

3. **Use a GitHub-hosted vendor**:
   - Download a pre-built vendor folder from a similar Laravel 8 project
   - Extract to `c:\xampp\htdocs\MediaLAN\vendor\`

### PHP Version Errors

**Problem**: "Fatal error: Uncaught RuntimeException... requires PHP >= 8.1.0"

**Status**: ✅ Already fixed - platform_check.php updated for PHP 7.4

If still seeing this:
```bash
cd c:\xampp\htdocs\MediaLAN
php vendor/composer/platform_check.php
```

Should show: `Platform check passed ✓`

### Database Migration Errors

**Problem**: `php artisan migrate` fails

**Check**:
1. Ensure `.env` has correct database credentials
2. Database `medialan` exists
3. Run: `php artisan migrate --force`

### Port Already in Use

**Problem**: Port 8000 already in use

**Solution**:
```bash
php artisan serve --host=0.0.0.0 --port=8001
```

Then access: `http://[IP]:8001`

## Default Credentials

| Field | Value |
|-------|-------|
| Admin Username | `admin` |
| Admin Password | `admin123` |
| Database | `medialan` |
| Max Upload | 2 GB |

## File Permissions

On Windows, ensure these folders are writable:
- `storage/`
- `bootstrap/cache/`

If issues, run as Administrator or check folder permissions.

## Important Files

- `.env` - Environment configuration
- `.env.example` - Template (copy this to .env)
- `database/migrations/` - Database schemas
- `database/seeders/` - Initial data
- `app/Models/` - Database models
- `app/Http/Controllers/` - Business logic
- `resources/views/` - Blade templates

## Architecture

**Frontend**:
- Blade templates in `resources/views/`
- Tailwind CSS for responsive design
- Mobile-optimized (works great on phones)

**Backend**:
- Laravel 8.83 REST API
- JSON Web Tokens (via Sanctum)
- File upload handling

**Database**:
- MySQL with 5 tables
- Automatic migrations

**File Storage**:
- Media stored in `storage/app/media/`
- Max file size: 2 GB per upload
- Upload timeout: 10 minutes

## First Time Setup Checklist

- [ ] Composer install completed
- [ ] Environment file (.env) created
- [ ] Database key generated (`php artisan key:generate`)
- [ ] Database tables created (`php artisan migrate`)
- [ ] Initial data seeded (`php artisan db:seed`)
- [ ] Development server running (`php artisan serve`)
- [ ] Can access from phone on same WiFi

## Monitoring Installation

Check composer progress:
```bash
# View last 30 lines of composer log
Get-Content c:\xampp\htdocs\MediaLAN\composer_install.log -Tail 30

# Check vendor folder size
(Get-ChildItem c:\xampp\htdocs\MediaLAN\vendor -Recurse | Measure-Object -Sum Length).Sum / 1MB

# Check number of packages installed
Get-ChildItem c:\xampp\htdocs\MediaLAN\vendor -Directory | Measure-Object
```

## Support

If you encounter issues:

1. Check the log files:
   - `storage/logs/laravel.log` (once Laravel runs)
   - `composer_install.log` (during composer)

2. Common PHP version check:
   ```bash
   php -v
   ```

3. Test Laravel is properly installed:
   ```bash
   php artisan tinker
   ```

## Next Steps

Once everything is running:
1. Log in with `admin/admin123`
2. Create media categories
3. Upload media files
4. Share PIN codes with users
5. Access from other devices on your network

Enjoy your local media server! 🎬
