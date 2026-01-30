# MediaLAN - Setup & Installation Guide

## Quick Start

### 1. Prerequisites
- XAMPP with PHP 8.1+ and MySQL
- Composer
- Node.js and npm

### 2. Installation Steps

#### Step 1: Clone/Setup Project
```bash
cd c:\xampp\htdocs\MediaLAN
```

#### Step 2: Install Dependencies
```bash
composer install
npm install
```

#### Step 3: Create Database
Create a MySQL database named `medialan`:
```sql
CREATE DATABASE medialan;
```

#### Step 4: Configure Environment
The `.env` file is already configured:
- APP_NAME=Laravel
- DB_DATABASE=medialan
- DB_USERNAME=root
- DB_PASSWORD=

#### Step 5: Run Migrations
```bash
php artisan migrate
```

#### Step 6: Seed Database
```bash
php artisan db:seed
```

This creates:
- **Default Admin User**
  - Username: `admin`
  - Password: `admin123`
  - Email: `admin@medialan.local`

- **Default Categories**
  - Action, Drama, Comedy, Thriller, Horror, Romance, Animation, Documentary

#### Step 7: Build Frontend Assets
```bash
npm run build
```

For development with hot reload:
```bash
npm run dev
```

#### Step 8: Start Laravel Server
```bash
php artisan serve
```

The application will be available at: `http://localhost:8000`

### 3. Admin Access

1. Go to: `http://localhost:8000/admin/login`
2. Login with:
   - Username: `admin`
   - Password: `admin123`
3. Dashboard: `http://localhost:8000/admin/dashboard`

### 4. First Steps in Admin Panel

1. **Create Categories** (optional - default ones already exist)
   - Go to Categories > Add Category
   - Create any custom categories you need

2. **Upload Media**
   - Go to Media > Upload Media
   - Select category, type (movie/series), and upload file
   - Add metadata (title, year, director, cast, etc.)

3. **Generate PINs**
   - Go to PINs > Create Single PIN or Bulk Generate
   - Single: Create one PIN at a time
   - Bulk: Generate multiple PINs for mass distribution
   - Print PINs for physical distribution

4. **View Dashboard**
   - Monitor total PINs, downloads, media count
   - See recent download activity
   - Track PIN usage

### 5. User Features

**Home Page**: `http://localhost:8000/`
- Browse by category
- See latest additions
- Search functionality

**Download Flow**:
1. User visits home page
2. Selects a movie/series
3. Clicks "Download with PIN"
4. Enters PIN code
5. If PIN is valid and has downloads remaining:
   - Download starts immediately
   - PIN usage increments
   - Download is logged
6. After 3 downloads (default):
   - PIN automatically expires
   - User can no longer use it

### 6. Media Storage

- Media files are stored in: `storage/app/media/`
- Downloads are served through the server (not direct URLs)
- This prevents direct file access without PIN verification

### 7. Database Structure

**Categories**: Store media categories (Action, Drama, etc.)

**Media**: Store metadata for movies/series
- title, description, type, category
- filename, size, poster_url
- year, director, cast, duration, episodes

**PINs**: Store download codes
- pin_code (unique), status (active/expired/revoked)
- max_downloads, used_downloads
- created_at, expires_at

**Downloads_Log**: Track all downloads
- pin_id, media_id, ip_address
- user_agent, downloaded_at

**Admin_Users**: Store admin accounts
- username, email, password (hashed)
- is_active, last_login_at

### 8. Security Notes

✓ PINs are 4 groups of 4 alphanumeric characters (e.g., ABCD-EFGH-IJKL-MNOP)
✓ All downloads validated server-side before serving files
✓ Admin passwords are hashed with bcrypt
✓ Session-based admin authentication
✓ IP logging for all downloads
✓ PIN expiration after max downloads

### 9. Troubleshooting

**Migrations fail:**
```bash
php artisan migrate:refresh
php artisan db:seed
```

**Storage permissions issue:**
```bash
chmod -R 777 storage/
chmod -R 777 bootstrap/cache/
```

**Clear caches:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

**Rebuild assets:**
```bash
npm run build
```

### 10. Production Deployment

1. Update `.env` for production database
2. Set `APP_DEBUG=false`
3. Set `APP_ENV=production`
4. Run: `php artisan migrate --force`
5. Run: `php artisan config:cache`
6. Run: `npm run build`
7. Setup proper file permissions
8. Use a production web server (Apache/Nginx)
9. Enable HTTPS
10. Regular backups of database and media files

### 11. Useful Commands

```bash
# Create new admin user
php artisan tinker
App\Models\AdminUser::create(['username' => 'newadmin', 'email' => 'admin@example.com', 'password' => Hash::make('password')])

# Generate test PINs
php artisan tinker
$service = app('App\Services\PinService');
$pins = $service->createBulkPins(10);

# Clear all expired PINs
php artisan tinker
App\Models\Pin::where('status', 'expired')->delete();
```

---

**System Ready!** Your MediaLAN instance is now fully operational.
