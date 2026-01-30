# MediaLAN Quick Reference Guide

## 🎯 Getting Started (5 Minutes)

### Installation
```bash
cd c:\xampp\htdocs\MediaLAN
composer install
npm install
php artisan migrate
php artisan db:seed
npm run build
php artisan serve
```

### First Login
- URL: http://localhost:8000/admin/login
- Username: `admin`
- Password: `admin123`

---

## 📚 User URLs

| Feature | URL |
|---------|-----|
| Home | http://localhost:8000/ |
| Browse Category | http://localhost:8000/category/{id} |
| Media Details | http://localhost:8000/media/{id} |
| Search | http://localhost:8000/search?q=query |
| Download Form | http://localhost:8000/download/{id}/verify |

---

## 🛠️ Admin URLs

| Feature | URL |
|---------|-----|
| Login | http://localhost:8000/admin/login |
| Dashboard | http://localhost:8000/admin/dashboard |
| Categories | http://localhost:8000/admin/categories |
| Media | http://localhost:8000/admin/media |
| PINs | http://localhost:8000/admin/pins |
| Change Password | http://localhost:8000/admin/change-password |

---

## 💾 Database Tables

### categories
```sql
SELECT * FROM categories;
-- Stores media categories
```

### media
```sql
SELECT * FROM media;
-- Stores movie/series information
-- Fields: title, type, category_id, filename, size, etc.
```

### pins
```sql
SELECT * FROM pins;
-- Stores download codes
-- Fields: pin_code, status, used_downloads, max_downloads
```

### downloads_log
```sql
SELECT * FROM downloads_log;
-- Tracks all downloads
-- Fields: pin_id, media_id, ip_address, downloaded_at
```

### admin_users
```sql
SELECT * FROM admin_users;
-- Stores admin accounts
-- Fields: username, email, password, is_active
```

---

## 📁 Important Directories

| Directory | Purpose |
|-----------|---------|
| `app/Http/Controllers/` | Business logic |
| `app/Models/` | Database models |
| `app/Services/` | PIN & download services |
| `resources/views/` | HTML templates |
| `storage/app/media/` | Media file storage |
| `database/migrations/` | Database schema |
| `routes/web.php` | All routes |

---

## 🔑 PIN Generation Examples

### Create Single PIN
```php
php artisan tinker
$service = app('App\Services\PinService');
$pin = $service->createPin(3);  // 3 downloads
echo $pin->pin_code;  // XXXX-XXXX-XXXX-XXXX
```

### Create 10 PINs
```php
$pins = $service->createBulkPins(10, 3);
$pins->map(fn($p) => echo $p->pin_code . "\n");
```

### Check PIN Status
```php
$pin = App\Models\Pin::where('pin_code', 'ABCD-EFGH-IJKL-MNOP')->first();
echo $pin->status;  // active, expired, or revoked
echo $pin->getRemainingDownloads();  // 0-3
```

---

## 📊 Common Queries

### Get all active PINs
```sql
SELECT * FROM pins WHERE status = 'active';
```

### Get PIN with most downloads
```sql
SELECT * FROM pins ORDER BY used_downloads DESC LIMIT 1;
```

### Recent downloads (last 24 hours)
```sql
SELECT * FROM downloads_log 
WHERE downloaded_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
ORDER BY downloaded_at DESC;
```

### Media per category
```sql
SELECT c.name, COUNT(m.id) as count 
FROM categories c 
LEFT JOIN media m ON c.id = m.category_id
GROUP BY c.id, c.name;
```

### Most downloaded media
```sql
SELECT m.title, COUNT(dl.id) as downloads
FROM media m
LEFT JOIN downloads_log dl ON m.id = dl.media_id
GROUP BY m.id, m.title
ORDER BY downloads DESC;
```

---

## 🔄 Workflow Examples

### Admin: Upload Media
1. Login to `/admin/login`
2. Go to Media section
3. Click "Upload Media"
4. Fill in details:
   - Title, description
   - Type (movie/series)
   - Category
   - Upload file
5. Optional: Add metadata (year, director, cast)

### Admin: Generate & Print PINs
1. Go to PINs section
2. Click "Bulk Generate" (or "Create Single PIN")
3. Enter quantity (e.g., 50)
4. Set max downloads (default: 3)
5. Submit
6. Click "Print" to generate PDF
7. Print and cut PIN cards

### User: Download Media
1. Visit http://localhost:8000
2. Browse or search for media
3. Click media to view details
4. Click "Download with PIN"
5. Enter PIN code
6. If valid: Download starts
7. Download logged automatically

---

## 🚨 Troubleshooting

### Database Migration Failed
```bash
php artisan migrate:reset
php artisan migrate
php artisan db:seed
```

### Can't Login to Admin
```bash
php artisan tinker
App\Models\AdminUser::where('username', 'admin')->update(['password' => Hash::make('admin123')])
```

### Create New Admin User
```bash
php artisan tinker
App\Models\AdminUser::create([
    'username' => 'user2',
    'email' => 'user2@test.com',
    'password' => Hash::make('password123')
])
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Storage Permissions
```bash
chmod -R 777 storage/ bootstrap/cache/
```

---

## 🎛️ Configuration

### Max file upload
File: `app/Http/Controllers/Admin/MediaController.php`
Current: 5GB (5242880 bytes)

### PIN format
File: `app/Services/PinService.php`
Current: XXXX-XXXX-XXXX-XXXX (4 groups of 4)

### Max downloads per PIN
File: `app/Models/Pin.php`
Default: 3 downloads
Change in PIN controller when creating

---

## 📋 Checklist: Pre-Launch

- [ ] Database created (`CREATE DATABASE medialan;`)
- [ ] Migrations run (`php artisan migrate`)
- [ ] Database seeded (`php artisan db:seed`)
- [ ] Dependencies installed (`composer install && npm install`)
- [ ] Assets built (`npm run build`)
- [ ] Server running (`php artisan serve`)
- [ ] Can login as admin (admin/admin123)
- [ ] Can access home page
- [ ] Can upload media
- [ ] Can generate PINs
- [ ] Can print PINs
- [ ] Storage folder writable
- [ ] Media folder exists (`storage/app/media/`)

---

## 📞 Key Files to Review

1. **Routes**: `routes/web.php` - All endpoints
2. **Models**: `app/Models/*.php` - Database relationships
3. **Services**: `app/Services/*.php` - Core logic
4. **Controllers**: `app/Http/Controllers/*.php` - Business logic
5. **Database**: `database/migrations/*.php` - Schema
6. **Seeder**: `database/seeders/DatabaseSeeder.php` - Initial data

---

## 💡 Tips

- Admin credentials: username is `admin`, not email
- PINs auto-expire after max downloads
- Downloads logged with IP address
- All files stored in `storage/app/media/`
- Session-based auth (no tokens)
- Use Tinker for quick database operations
- Check browser DevTools for any errors
- Keep `.env` secrets safe

---

## 🔗 Important Links

- [README.md](README.md) - Full documentation
- [SETUP_GUIDE.md](SETUP_GUIDE.md) - Installation steps
- [IMPLEMENTATION_COMPLETE.md](IMPLEMENTATION_COMPLETE.md) - What was built
- [Laravel Docs](https://laravel.com/docs) - Framework reference

---

**Happy MediaLAN-ing! 🚀**
