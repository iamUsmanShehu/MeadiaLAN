# MediaLAN - Local Network Media Streaming System

A complete Laravel-based local network (LAN) media browsing and download system with PIN-based access control.

## 🎯 Overview

MediaLAN allows users on a local network to:
- Browse movies and TV series by category
- View detailed media information
- Download content using PIN codes
- Each PIN allows exactly 3 downloads before automatic expiration

This is a **completely offline system** with no external dependencies or internet requirements.

## 🚀 Features

### User Features
- 🎬 Browse movies and series by category
- 🔍 Search functionality
- 📝 Detailed media information (year, director, cast, duration)
- 🔐 PIN-based download verification
- 📊 Remaining downloads display
- 💾 Direct media file download with PIN validation

### Admin Features
- 🛡️ Secure admin login
- 📂 Category management (CRUD)
- 🎥 Media management and upload
- 🔑 PIN generation (single or bulk)
- 🖨️ Printable PIN cards for physical distribution
- 📊 Dashboard with statistics
- 📋 Download history and PIN usage tracking
- 📤 CSV export of PINs

## 📋 Tech Stack

- **Backend**: Laravel 11 (PHP 8.1+)
- **Database**: MySQL
- **Frontend**: Tailwind CSS
- **Build Tool**: Vite
- **Server**: XAMPP or any Apache/MySQL environment
- **Network**: Local Area Network (LAN)

## 📁 Project Structure

```
MediaLAN/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── HomeController.php       # User browsing
│   │   │   ├── DownloadController.php   # PIN verification & downloads
│   │   │   └── Admin/
│   │   │       ├── AuthController.php   # Admin login
│   │   │       ├── DashboardController.php
│   │   │       ├── CategoryController.php
│   │   │       ├── MediaController.php
│   │   │       └── PinController.php
│   │   ├── Middleware/
│   │   │   └── AdminAuth.php            # Admin authentication
│   │   └── Kernel.php
│   ├── Models/
│   │   ├── Category.php
│   │   ├── Media.php
│   │   ├── Pin.php
│   │   ├── DownloadLog.php
│   │   └── AdminUser.php
│   └── Services/
│       ├── PinService.php               # PIN generation & management
│       └── DownloadService.php          # Download validation & logging
├── database/
│   ├── migrations/                      # Database schema
│   └── seeders/                         # Initial data seeding
├── resources/
│   ├── views/
│   │   ├── layouts/                     # Base templates
│   │   ├── home.blade.php               # Home page
│   │   ├── category.blade.php           # Category browsing
│   │   ├── search.blade.php             # Search results
│   │   ├── media/show.blade.php         # Media details
│   │   ├── download/verify.blade.php    # PIN verification
│   │   └── admin/                       # Admin templates
│   ├── css/app.css                      # Tailwind styles
│   └── js/app.js
├── routes/
│   └── web.php                          # All routes (user & admin)
├── storage/app/media/                   # Media file storage
├── tailwind.config.js
├── vite.config.js
└── .env                                 # Environment configuration
```

## 🗄️ Database Schema

### Categories
Stores media categories (Action, Drama, Comedy, etc.)
- `id`, `name`, `description`, `slug`, `timestamps`

### Media
Stores all media metadata
- `id`, `title`, `description`, `type` (movie/series)
- `category_id`, `filename`, `size`, `poster_url`
- `duration`, `year`, `director`, `cast`, `episodes`

### Pins
Stores download PIN codes
- `id`, `pin_code` (unique), `max_downloads` (default: 3)
- `used_downloads`, `status` (active/expired/revoked)
- `created_at`, `expires_at`, `updated_at`

### Downloads_Log
Tracks all downloads for security
- `id`, `pin_id`, `media_id`, `ip_address`, `user_agent`
- `downloaded_at`

### Admin_Users
Stores admin accounts
- `id`, `username`, `email`, `password` (hashed)
- `is_active`, `last_login_at`, `timestamps`

## 🔐 PIN System

### PIN Generation
- Format: `XXXX-XXXX-XXXX-XXXX` (4 groups of 4 alphanumeric characters)
- Example: `A7KM-3P9L-5Q2X-8WN6`
- Completely random and unique

### PIN Lifecycle
1. **Created**: PIN starts as `active` with `used_downloads = 0`
2. **Each Download**: 
   - PIN validation performed server-side
   - Download is logged with IP address
   - `used_downloads` incremented
3. **Expiration**: 
   - After 3 downloads (default)
   - `status` automatically set to `expired`
   - PIN can no longer be used

### PIN Security
- ✓ All downloads validated before serving
- ✓ Direct file URLs not exposed
- ✓ IP address logging
- ✓ Expired PINs blocked automatically
- ✓ Can be manually revoked

## 📦 Installation

### Prerequisites
- XAMPP with PHP 8.1+ and MySQL
- Composer
- Node.js and npm

### Quick Setup

1. **Navigate to project**
   ```bash
   cd c:\xampp\htdocs\MediaLAN
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node dependencies**
   ```bash
   npm install
   ```

4. **Create database**
   ```sql
   CREATE DATABASE medialan;
   ```

5. **Configure environment** (already done in `.env`)
   - DB_DATABASE=medialan
   - DB_USERNAME=root
   - DB_PASSWORD=

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed initial data**
   ```bash
   php artisan db:seed
   ```

   This creates:
   - **Admin User**: `admin` / `admin123`
   - **8 Categories**: Action, Drama, Comedy, Thriller, Horror, Romance, Animation, Documentary

8. **Build frontend**
   ```bash
   npm run build
   ```

9. **Start development server**
   ```bash
   php artisan serve
   ```

10. **Access the application**
    - User: http://localhost:8000
    - Admin: http://localhost:8000/admin/login

## 🎮 Usage

### For Users

1. **Home Page** (`/`)
   - Browse categories
   - See latest additions
   - Search for content

2. **Browse Category** (`/category/{id}`)
   - View all media in category
   - Paginated results

3. **Media Details** (`/media/{id}`)
   - View complete information
   - Year, director, cast, duration
   - Click "Download with PIN"

4. **Download** (`/download/{id}/verify`)
   - Enter PIN code
   - System validates PIN
   - If valid: download starts immediately
   - Download is logged

### For Admins

1. **Login** (`/admin/login`)
   - Username: `admin`
   - Password: `admin123`

2. **Dashboard** (`/admin/dashboard`)
   - View statistics
   - Recent downloads
   - Recent PINs
   - Quick action buttons

3. **Manage Categories** (`/admin/categories`)
   - Create, edit, delete categories
   - View media count per category

4. **Manage Media** (`/admin/media`)
   - Upload new movies/series
   - Edit metadata
   - Delete media

5. **Manage PINs** (`/admin/pins`)
   - Create single PIN
   - Bulk generate PINs
   - View PIN details and download history
   - Print PIN cards
   - Revoke PINs
   - Export as CSV

## 🎨 Frontend Features

### Responsive Design
- Desktop, tablet, and mobile optimized
- Dark theme with Tailwind CSS

### User Interface
- Modern navigation bar with search
- Category grid layout
- Media card design with hover effects
- Detailed media page
- Intuitive PIN verification form

### Admin Interface
- Sidebar navigation
- Dashboard with statistics
- Forms for CRUD operations
- Table views with pagination
- Print-friendly PIN cards

## 🔄 Complete Workflow Example

1. **Admin Setup**
   - Login to admin panel
   - Create/edit categories (optional)
   - Upload movies/series

2. **PIN Distribution**
   - Generate bulk PINs
   - Print PIN cards
   - Sell/distribute physically in store

3. **User Access**
   - Customer connects to LAN
   - Opens home page
   - Browses categories
   - Selects media
   - Enters purchased PIN
   - Downloads at LAN speed

4. **PIN Expiration**
   - After 3 downloads → PIN expires
   - Customer must buy new PIN
   - System revenue model

## 🚀 Commands

### Database
```bash
# Run all migrations
php artisan migrate

# Reset database
php artisan migrate:refresh

# Seed database
php artisan db:seed
```

### Assets
```bash
# Build for production
npm run build

# Development with hot reload
npm run dev
```

### Server
```bash
# Start Laravel development server
php artisan serve

# Serve on different host/port
php artisan serve --host=0.0.0.0 --port=8000
```

### Artisan Tinker (Interactive Shell)
```bash
php artisan tinker

# Create admin user
App\Models\AdminUser::create(['username' => 'user2', 'email' => 'user2@example.com', 'password' => Hash::make('password')])

# Generate PINs
$service = app('App\Services\PinService');
$pins = $service->createBulkPins(50);

# View all PINs
App\Models\Pin::all()
```

## 📊 API Endpoints

### User Routes
- `GET /` - Home page
- `GET /category/{id}` - Browse category
- `GET /media/{id}` - Media details
- `GET /search?q=query` - Search media
- `GET /download/{id}/verify` - PIN entry form
- `POST /download/{id}/verify` - Verify PIN & download
- `GET /api/pin-status` - Check PIN status (JSON)

### Admin Routes
- `GET /admin/login` - Login form
- `POST /admin/login` - Process login
- `POST /admin/logout` - Logout
- `GET /admin/dashboard` - Dashboard
- `GET /admin/categories` - List categories
- `GET /admin/categories/create` - Create form
- `POST /admin/categories` - Store category
- `GET /admin/categories/{id}/edit` - Edit form
- `PUT /admin/categories/{id}` - Update category
- `DELETE /admin/categories/{id}` - Delete category
- `GET /admin/media` - List media
- `GET /admin/media/create` - Create form
- `POST /admin/media` - Store media
- `GET /admin/media/{id}/edit` - Edit form
- `PUT /admin/media/{id}` - Update media
- `DELETE /admin/media/{id}` - Delete media
- `GET /admin/pins` - List PINs
- `GET /admin/pins/create` - Create single PIN
- `POST /admin/pins` - Store PIN
- `GET /admin/pins/create-bulk` - Bulk form
- `POST /admin/pins/bulk` - Generate bulk
- `GET /admin/pins/{id}` - PIN details
- `GET /admin/pins/{id}/print` - Print single
- `GET /admin/pins-print-bulk` - Print multiple
- `POST /admin/pins/{id}/revoke` - Revoke PIN
- `GET /admin/pins-export` - Export CSV

## 🔒 Security Features

✓ **PIN-Based Access**: Downloads require valid PIN
✓ **Server-Side Validation**: All downloads verified server-side
✓ **Hidden File URLs**: Direct access prevented
✓ **Session Management**: Secure admin sessions
✓ **Password Hashing**: Bcrypt for admin passwords
✓ **IP Logging**: Track all downloads by IP
✓ **Input Validation**: CSRF protection, input sanitization
✓ **Automatic Expiration**: PINs expire after usage limit

## ⚙️ Configuration

The application uses a `.env` file for configuration:

```env
APP_NAME=MediaLAN
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=medialan
DB_USERNAME=root
DB_PASSWORD=
```

## 📝 File Uploads

- **Location**: `storage/app/media/`
- **Max Size**: 5GB per file
- **Access**: Only through PIN-verified downloads
- **Storage**: Keeps original filename + timestamp

## 🐛 Troubleshooting

### Database Connection Error
```bash
# Verify database exists and credentials are correct
# Check .env file configuration
php artisan migrate --force
```

### Storage Permissions
```bash
# On Linux/Mac
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

# On Windows, ensure write permissions for IIS/Apache
```

### Clear All Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

## 📄 License

MIT License - This project is open source.

## 🤝 Support

For issues or questions, check:
- [Laravel Documentation](https://laravel.com/docs)
- [Tailwind CSS](https://tailwindcss.com)
- Check database migrations and seeders

---

**MediaLAN is ready for deployment!** Start uploading media and generating PINs.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
#   M e a d i a L A N  
 