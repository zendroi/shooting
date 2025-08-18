# Laravel Hosting Setup Guide

## Requirements
- PHP 8.2 or higher
- Composer
- MySQL/PostgreSQL (or SQLite for simple apps)
- mod_rewrite enabled

## Quick Setup for Shared Hosting

### 1. Upload Files
Upload all files to your hosting root directory (public_html/ or www/).

### 2. Run Setup Script
Visit: `https://yourdomain.com/setup-hosting.php`
This will:
- Create .env file
- Set proper permissions
- Create necessary directories
- Generate APP_KEY
- Create SQLite database (if using SQLite)

### 3. Set Document Root
Point your domain's document root to the `public/` folder.

### 4. Database Setup
**Option A: SQLite (Recommended for shared hosting)**
- Already configured in setup script
- No additional setup needed

**Option B: MySQL**
- Create MySQL database in hosting panel
- Update .env file:
```
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run Migrations (if needed)
If you have database migrations, run:
```bash
php artisan migrate --force
```

## Common Issues & Solutions

### 1. "Vendor directory not found"
**Solution:** Upload the `vendor/` folder from your local machine to the server.

### 2. "Permission denied" errors
**Solution:** Set these permissions:
- `storage/` → 755
- `bootstrap/cache/` → 755
- `database/` → 755

### 3. "500 Internal Server Error"
**Check:**
- PHP version (must be 8.2+)
- mod_rewrite enabled
- .htaccess file exists in public/
- APP_KEY is set in .env

### 4. "Class not found" errors
**Solution:** Run these commands:
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

### 5. Assets not loading
**Solution:** Build assets locally and upload:
```bash
npm run build
```
Then upload the `public/build/` folder.

## Security Checklist
- [ ] Delete `setup-hosting.php` after setup
- [ ] Set `APP_DEBUG=false` in production
- [ ] Set `APP_ENV=production`
- [ ] Use HTTPS
- [ ] Set proper file permissions

## File Structure for Hosting
```
your-domain.com/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/          ← Document root
│   ├── index.php
│   ├── .htaccess
│   └── build/
├── resources/
├── routes/
├── storage/
├── vendor/
├── .env
└── setup-hosting.php
```

## Support
If you encounter issues:
1. Check error logs in hosting panel
2. Enable debug mode temporarily: `APP_DEBUG=true`
3. Check PHP version compatibility
4. Verify all required PHP extensions are enabled 