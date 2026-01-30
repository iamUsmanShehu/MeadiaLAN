# MediaLAN - Complete Setup Reference

## Current Status

### ✅ Completed
- [x] Mobile responsive UI (all templates optimized)
- [x] Media upload form with validation and progress feedback
- [x] Admin dashboard with responsive design
- [x] PHP 7.4 compatibility platform check (fixed)
- [x] Database migrations and seeders ready
- [x] Laravel 8 downgrade completed
- [x] Environment configuration (.env) created
- [x] Fallback lightweight PHP interface (working)
- [x] Comprehensive documentation

### ⏳ In Progress
- [ ] Composer dependency installation (running in background)
- [ ] Laravel framework download and installation

### 📋 Pending (After Composer Finishes)
- [ ] `php artisan key:generate`
- [ ] `php artisan migrate` (create database tables)
- [ ] `php artisan db:seed` (populate initial data)
- [ ] `php artisan serve` (start development server)

---

## System Requirements ✅

| Component | Required | Installed |
|-----------|----------|-----------|
| PHP | 7.4+ | 7.4.2 (XAMPP) ✅ |
| MySQL | 5.7+ | (XAMPP) ✅ |
| Composer | Latest | Yes ✅ |
| Node.js | 14+ | For Vite (optional) |

---

## Accessing MediaLAN

### While Installing (Temporary Interface)

**Fallback Lightweight Interface**:
```
http://localhost/MediaLAN/public/fallback.php
```

Features:
- ✅ View categories
- ✅ Browse media structure
- ✅ Admin demo login
- ✅ System status check
- ✅ Responsive mobile design

### After Installation (Full Laravel Application)

```
http://localhost:8000
```

Or from phone on same WiFi:
```
http://[YOUR_COMPUTER_IP]:8000
```

To find your IP:
```bash
ipconfig | find "IPv4 Address"
```

---

## Complete Installation Steps

### Step 1: Wait for Composer

Composer is currently downloading Laravel and dependencies in the background.

**Check Progress**:
```bash
Get-Content c:\xampp\htdocs\MediaLAN\composer_final.log -Tail 20
```

**Monitor Size**:
```bash
(Get-ChildItem c:\xampp\htdocs\MediaLAN\vendor -Recurse | Measure-Object -Sum Length).Sum / 1MB
```

**Expected Final Size**: ~50-80 MB

### Step 2: Generate Application Key

Once Composer finishes:

```bash
cd c:\xampp\htdocs\MediaLAN
php artisan key:generate
```

This generates the `APP_KEY` in `.env` required for encryption.

### Step 3: Create Database Tables

```bash
php artisan migrate
```

This creates the database schema:
- `categories` - Media categories
- `media` - Media files metadata
- `pins` - Download verification codes
- `downloads_log` - Download history
- `admin_users` - Admin accounts

### Step 4: Populate Initial Data

```bash
php artisan db:seed
```

Seeds the database with:
- 8 default categories (Movies, TV, Documentaries, etc.)
- 1 admin user (username: `admin`, password: `admin123`)

### Step 5: Start Development Server

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Output should show:
```
Laravel development server started: http://127.0.0.1:8000
```

### Step 6: Access from Phone

1. Connect phone to same WiFi as computer
2. Find computer IP: `ipconfig | find "IPv4"`
3. Visit: `http://[COMPUTER_IP]:8000`

---

## Default Credentials

| Field | Value | Notes |
|-------|-------|-------|
| **Admin Username** | `admin` | Case-sensitive |
| **Admin Password** | `admin123` | Change after first login |
| **Database Name** | `medialan` | Auto-created |
| **Database User** | `root` | XAMPP default |
| **Max Upload** | 2 GB | Per file |
| **Upload Timeout** | 10 minutes | Configured in .htaccess |

---

## Database Architecture

### Tables Schema

#### `categories`
```sql
id, name, description, created_at, updated_at
```

#### `media`
```sql
id, category_id, title, description, file_path, 
file_size, duration, poster_url, created_at, updated_at
```

#### `pins`
```sql
id, media_id, pin_code, max_downloads, 
downloads_count, expires_at, created_at
```

#### `downloads_log`
```sql
id, media_id, pin_id, ip_address, 
downloaded_at, file_size
```

#### `admin_users`
```sql
id, name, username, password, created_at, updated_at
```

---

## File Locations

```
c:\xampp\htdocs\MediaLAN\
├── .env                          # Environment variables
├── .env.example                  # Template (don't edit)
├── app/
│   ├── Models/                   # Database models
│   ├── Http/
│   │   └── Controllers/          # Business logic
│   └── Services/                 # Helper classes
├── database/
│   ├── migrations/               # Table schemas
│   └── seeders/                  # Initial data
├── resources/
│   ├── css/app.css               # Tailwind styles
│   └── views/                    # Blade templates
├── routes/
│   ├── api.php                   # API routes
│   └── web.php                   # Web routes
├── storage/
│   ├── app/media/                # Uploaded media files
│   ├── logs/                     # Application logs
│   └── framework/                # Cache files
├── vendor/                       # Composer dependencies
├── public/
│   ├── index.php                 # Entry point
│   ├── fallback.php              # Temporary interface
│   └── .htaccess                 # Upload limits
└── composer.json                 # Project configuration
```

---

## Troubleshooting

### ❓ "Composer seems stuck"

**Solution 1** - Kill and retry:
```bash
taskkill /IM php.exe /F
composer install --no-dev --prefer-dist
```

**Solution 2** - Check if actually running:
```bash
# See vendor folder growth
(Get-ChildItem c:\xampp\htdocs\MediaLAN\vendor -Recurse | Measure-Object -Sum Length).Sum / 1MB
```

**Solution 3** - Use alternative installation:
- Download pre-built Laravel 8.83 vendor folder
- Extract to `vendor/` folder
- Run: `php artisan migrate`

### ❓ "php artisan: command not found"

**Cause**: Vendor autoloader not loaded or PHP path issue

**Solution**:
```bash
cd c:\xampp\htdocs\MediaLAN
php artisan --version
```

If still fails:
```bash
c:\xampp\php\php.exe artisan migrate
```

### ❓ "SQLSTATE[HY000]: General error - database doesn't exist"

**Solution**:
1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Create database `medialan` (UTF-8)
3. Re-run: `php artisan migrate`

Or via MySQL:
```sql
CREATE DATABASE medialan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### ❓ "Port 8000 already in use"

**Solution**:
```bash
# Use different port
php artisan serve --host=0.0.0.0 --port=8001

# Access at: http://localhost:8001
```

### ❓ "CSRF token mismatch"

**Solution**: This is expected in development. Token validation is automatic.

### ❓ "Uploaded file not found"

**Check file permissions**:
```bash
# Windows - Run as Administrator
icacls "c:\xampp\htdocs\MediaLAN\storage" /grant "*S-1-1-0":(OI)(CI)F /T
```

### ❓ "Can't access from phone"

**Checklist**:
- [ ] Phone on same WiFi as computer
- [ ] Firewall allows port 8000 (Windows Defender)
- [ ] Using computer's IP not `localhost`
- [ ] Server is running (`php artisan serve`)
- [ ] Correct port in URL

**Add Firewall Exception**:
```bash
# Run as Administrator
netsh advfirewall firewall add rule name="Laravel 8000" dir=in action=allow protocol=tcp localport=8000
```

---

## Configuration Files

### .env (Important Settings)

```dotenv
APP_NAME=MediaLAN
APP_DEBUG=true              # Set to false in production
APP_URL=http://localhost:8000

DB_DATABASE=medialan
DB_USERNAME=root
DB_PASSWORD=

MAIL_FROM_ADDRESS="your-email@example.com"
```

### composer.json (Dependencies)

Key packages:
- `laravel/framework: 8.83.x` - Core framework
- `laravel/sanctum: 2.x` - API authentication
- `guzzlehttp/guzzle: ^7.2` - HTTP client
- `laravel/tinker: ^2.8` - Debug console

### public/.htaccess (Upload Limits)

```apache
upload_max_filesize 2G
post_max_size 2G
max_execution_time 600
max_input_time 600
```

---

## Performance Tips

### Local Network Optimization

1. **Use .local domain** (optional):
   - Add to hosts file: `192.168.x.x medialanlocal`
   - Access: `http://medialanlocal:8000`

2. **Cache queries** (in production):
   ```php
   $media = Cache::remember('all_media', 3600, function() {
       return Media::all();
   });
   ```

3. **Lazy-load images** (frontend):
   - Use `loading="lazy"` on image tags
   - Already implemented in templates

### Upload Optimization

- **Max file size**: 2 GB (adjust in .htaccess if needed)
- **Timeout**: 10 minutes
- **Chunk upload** (for large files): Implement in admin controller

---

## API Endpoints (After Installation)

All endpoints return JSON and support JWT authentication via Sanctum.

### Public Endpoints

```
GET     /api/categories          - List all categories
GET     /api/media               - List all media
GET     /api/media/{id}          - Get media details
POST    /api/verify-pin          - Verify download PIN
```

### Admin Endpoints (requires auth)

```
POST    /api/media               - Create media
PUT     /api/media/{id}          - Update media
DELETE  /api/media/{id}          - Delete media
POST    /api/pins                - Create download PIN
GET     /api/downloads/log       - View downloads
```

### Authentication

```
POST    /api/login               - Get auth token
POST    /api/logout              - Revoke token
GET     /api/user                - Get current user
```

---

## Development Commands

### Database

```bash
# Create tables
php artisan migrate

# Populate data
php artisan db:seed

# Rollback last migration
php artisan migrate:rollback

# Fresh install (destructive)
php artisan migrate:fresh --seed
```

### Cache Management

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Optimize autoloader
composer dump-autoload -o
```

### Debugging

```bash
# Interactive shell
php artisan tinker

# Check environment
php artisan env

# List all routes
php artisan route:list
```

---

## Deployment (Production Ready)

When deploying to production server:

1. **Use HTTPS** (Let's Encrypt recommended)
2. **Set APP_DEBUG=false** in .env
3. **Increase database limits** for concurrent users
4. **Enable query caching**
5. **Use CDN** for media files
6. **Set up automated backups**

---

## Security Checklist

- [ ] Change default admin password
- [ ] Set strong database password
- [ ] Enable HTTPS
- [ ] Set APP_KEY (auto-done by artisan key:generate)
- [ ] Keep Laravel updated
- [ ] Validate all file uploads (already done)
- [ ] Rate limit API endpoints
- [ ] Regular backups

---

## Monitoring

### Check Application Health

```bash
# Test database connection
php artisan db:table

# Verify permissions
php artisan storage:link

# Check logs
Get-Content storage/logs/laravel.log -Tail 50
```

### Monitor Resources

```bash
# CPU/Memory usage
Get-Process php | Select-Object Name, CPU, WorkingSet

# Disk space for uploads
(Get-ChildItem storage/app/media -Recurse | Measure-Object -Sum Length).Sum / 1GB
```

---

## Support Resources

- **Laravel Docs**: https://laravel.com/docs/8.x
- **Tailwind CSS**: https://tailwindcss.com
- **MySQL**: https://dev.mysql.com/doc/
- **XAMPP**: https://www.apachefriends.org/

---

## Next Steps

1. ✅ Wait for Composer to finish
2. ✅ Run `php artisan key:generate`
3. ✅ Run `php artisan migrate`
4. ✅ Run `php artisan db:seed`
5. ✅ Run `php artisan serve --host=0.0.0.0 --port=8000`
6. ✅ Access from phone: `http://[IP]:8000`
7. ✅ Login with `admin/admin123`
8. ✅ Upload media and create PINs
9. ✅ Share with friends on your network!

---

**Created**: 2024
**Version**: 1.0
**Status**: Ready for Installation
