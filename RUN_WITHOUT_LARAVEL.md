# MediaLAN - Run WITHOUT Full Laravel (Workaround)

## Problem

`php artisan serve` fails because Laravel framework classes aren't found.  
Reason: Composer installation is incomplete (vendor folder empty).

## Solution

Use the **lightweight fallback interface** that doesn't require Laravel framework.

---

## ✅ Quick Start (RIGHT NOW!)

### Option 1: Use Built-in PHP Server

**Windows Command Prompt:**
```bash
cd c:\xampp\htdocs\MediaLAN
c:\xampp\php\php.exe -S 0.0.0.0:8000 -t public -r router.php
```

**Then visit:**
- Desktop: `http://localhost:8000`
- Phone: `http://[YOUR_IP]:8000`

### Option 2: Run Quick Server Script

```bash
cd c:\xampp\htdocs\MediaLAN
php run_server.php 8000
```

### Option 3: Access Fallback Directly

```
http://localhost/MediaLAN/public/fallback.php
```

---

## 🔑 Login Details

- **Email:** `admin@medialn.local`
- **Password:** `admin123`

---

## 📊 Current Status

| Component | Status |
|-----------|--------|
| **Database** | ✅ Ready |
| **Admin User** | ✅ Created |
| **Categories** | ✅ Seeded |
| **Mobile UI** | ✅ Responsive |
| **Laravel Framework** | ⏳ Installing |

---

## Troubleshooting

### Port Already in Use

Use a different port:
```bash
c:\xampp\php\php.exe -S 0.0.0.0:8001 -t public -r router.php
```

Then access: `http://localhost:8001`

### Can't Access from Phone

1. Make sure phone is on **same WiFi**
2. Find your IP:
   ```bash
   ipconfig | find "IPv4"
   ```
3. Visit: `http://[YOUR_IP]:8000/fallback.php`

### Still Want to Use `php artisan`

The Composer installation needs to complete first. You can:

1. **Check Composer progress:**
   ```bash
   (Get-ChildItem c:\xampp\htdocs\MediaLAN\vendor -Recurse | Measure-Object -Sum Length).Sum / 1MB
   ```

2. **Try restarting Composer:**
   ```bash
   cd c:\xampp\htdocs\MediaLAN
   composer install --no-dev --prefer-dist --no-audit --ignore-platform-req=php
   ```

3. **Or manually download vendor folder** from a working Laravel 8 project and extract to `vendor/`

---

## What's Working in Fallback

✅ Browse media categories
✅ View media structure
✅ Admin login
✅ System status
✅ Mobile responsive interface
✅ All static pages

---

## What Requires Full Laravel

❌ File uploads (until Laravel ready)
❌ API endpoints (until Laravel ready)
❌ Advanced features (until Laravel ready)

---

## Next Steps

1. **Access the lightweight interface now** (option above)
2. **Wait for Composer** (check progress occasionally)
3. **Once Laravel is ready**, migrate to full `php artisan serve`

---

**Note:** The lightweight fallback interface is fully functional for browsing and administration. Full Laravel features available once Composer completes installation.
