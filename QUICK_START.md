# MediaLAN - Quick Start Guide (RIGHT NOW!)

## 🎉 Great News!

**Database is ready and seeded!** You can start using MediaLAN immediately while Composer finishes installing.

---

## Current Status ✅

| Component | Status | Details |
|-----------|--------|---------|
| **PHP Version** | ✅ Ready | 7.4.2 (XAMPP) |
| **MySQL Database** | ✅ Ready | `medialan` created |
| **Database Tables** | ✅ Ready | 9 tables with schema |
| **Admin User** | ✅ Ready | admin@medialn.local / admin123 |
| **Categories** | ✅ Ready | 8 default categories seeded |
| **Laravel Framework** | ⏳ Downloading | Composer running in background |

---

## How to Access MediaLAN RIGHT NOW

### Option 1: Temporary Lightweight Interface

This interface works WITHOUT the full Laravel framework:

**Desktop**:
```
http://localhost/MediaLAN/public/fallback.php
```

**From Phone** (same WiFi):
```
http://[YOUR_COMPUTER_IP]/MediaLAN/public/fallback.php
```

Features:
- ✅ Browse categories
- ✅ View media structure
- ✅ Admin login demo
- ✅ Mobile responsive
- ✅ Full status info

### Option 2: Wait for Full Laravel (~2-15 minutes)

Once Composer finishes:

```bash
cd c:\xampp\htdocs\MediaLAN
php artisan serve --host=0.0.0.0 --port=8000
```

Then access: `http://[YOUR_IP]:8000`

---

## Find Your Computer's IP Address

### Windows Command Prompt:
```bash
ipconfig | find "IPv4 Address"
```

Look for `192.168.x.x` (usually in range 192.168.0.0 - 192.168.255.255)

### Then on Phone:
- Connect to same WiFi
- Visit: `http://192.168.x.x/MediaLAN/public/fallback.php`

---

## Admin Login Credentials

| Field | Value |
|-------|-------|
| **Email** | `admin@medialn.local` |
| **Password** | `admin123` |

⚠️ **Change this password after first login!**

---

## Complete Setup (When Ready)

### Step 1: Wait for Composer
Check progress:
```bash
# Check vendor folder
(Get-ChildItem c:\xampp\htdocs\MediaLAN\vendor -Recurse | Measure-Object -Sum Length).Sum / 1MB
```

Expected final size: 50-80 MB

### Step 2: Run Laravel Server

```bash
cd c:\xampp\htdocs\MediaLAN
php artisan serve --host=0.0.0.0 --port=8000
```

Output should show:
```
Laravel development server started: http://127.0.0.1:8000
```

### Step 3: Access from Phone

1. Get your IP: `ipconfig | find "IPv4"`
2. Visit: `http://[YOUR_IP]:8000`
3. Login with `admin@medialn.local` / `admin123`

---

## What's Already Done ✅

1. ✅ Mobile responsive UI (all templates)
2. ✅ Media upload form with validation
3. ✅ Admin dashboard
4. ✅ Database design (5 tables)
5. ✅ Database tables created
6. ✅ Admin user seeded
7. ✅ Categories seeded
8. ✅ Environment configuration (.env)
9. ✅ PHP 7.4 compatibility

---

## What's Happening Now ⏳

Composer is downloading:
- Laravel 8 framework
- Sanctum (authentication)
- Guzzle HTTP client
- ~40+ dependencies

Total: ~200 MB to download

**This is running in the background - no action needed!**

---

## When You Can Start Using It

### Immediately (Lightweight):
```
http://localhost/MediaLAN/public/fallback.php
```

### After Composer Finishes (Full App):
```
php artisan serve --host=0.0.0.0 --port=8000
```

---

## Troubleshooting

### "I can't reach the server from my phone"

1. Check phone is on **same WiFi** as computer
2. Check your IP with: `ipconfig | find "IPv4"`
3. Verify Windows Firewall allows port 8000
4. Try different port: `php artisan serve --host=0.0.0.0 --port=8001`

### "Admin login doesn't work"

Use the email format:
- Email: `admin@medialn.local`
- Password: `admin123`

### "Composer is still running"

That's OK! It can take 5-15 minutes depending on internet speed.

Once it finishes, you can start the Laravel server.

### "I get 'command not found' for php artisan"

Run using full PHP path:
```bash
c:\xampp\php\php.exe artisan serve --host=0.0.0.0 --port=8000
```

---

## File Locations

| Component | Location |
|-----------|----------|
| **MediaLAN Files** | `c:\xampp\htdocs\MediaLAN\` |
| **Database** | `medialan` (MySQL) |
| **Uploads** | `storage\app\media\` |
| **Logs** | `storage\logs\laravel.log` |
| **Config** | `.env` |
| **Temporary UI** | `public\fallback.php` |
| **Composer Status** | Check `composer_final.log` |

---

## Quick Reference

### Access Points

| Use Case | URL | When |
|----------|-----|------|
| Check status | `http://localhost/MediaLAN/public/status.html` | Anytime |
| Lightweight UI | `http://localhost/MediaLAN/public/fallback.php` | Now |
| Full Laravel | `http://localhost:8000` | After composer |
| Admin | Login with `admin@medialn.local` | Anytime |

### Commands

```bash
# Start server
php artisan serve --host=0.0.0.0 --port=8000

# Check database
php artisan tinker

# View logs
Get-Content storage/logs/laravel.log -Tail 20

# Stop server
Ctrl + C (in terminal)
```

---

## Next Steps

1. **Try the lightweight interface now**: `http://localhost/MediaLAN/public/fallback.php`
2. **Share phone IP with friends** so they can access your media server
3. **Monitor Composer**: Check if it's still running
4. **When ready**: Run `php artisan serve` for full app
5. **Upload content**: Use admin panel to add media
6. **Create PINs**: Generate access codes for downloads
7. **Share with friends**: They access with PIN codes

---

## Important Notes

⚠️ This is a **development setup** (not production-ready)

✅ Perfect for **local network use**

⚠️ Change default password after first login

✅ All files stored **locally on your computer**

⚠️ Restart server if you modify code

---

## Success Checklist

- [ ] Can access: `http://localhost/MediaLAN/public/fallback.php`
- [ ] Found your IP address
- [ ] Can access from phone: `http://[IP]/MediaLAN/public/fallback.php`
- [ ] Logged in with `admin@medialn.local`
- [ ] Composer finished (optional, full app will have more features)
- [ ] Started Laravel server with `php artisan serve`
- [ ] Accessed full app from phone

---

**Version**: 1.0
**Created**: 2024
**Status**: Ready to use!

Enjoy your local media server! 🎬📱
