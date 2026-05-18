cd e:\xampp\htdocs\traffic
git add RENDER_DEPLOYMENT.md render.yaml .env.render
git commit -m "Add Render deployment configuration"
git push origin mainAPP_NAME=Traffic
APP_ENV=production
APP_DEBUG=false
APP_URL=https://traffic-app.onrender.com
APP_KEY=base64:YOUR_KEY_FROM_STEP_1
APP_TIMEZONE=UTC
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_MAINTENANCE_DRIVER=file
BCRYPT_ROUNDS=12
HASH_DRIVER=bcrypt
DB_CONNECTION=mongodb
DB_URI=YOUR_MONGODB_STRING_FROM_STEP_2
MONGO_DATABASE=trafficDB
LOG_CHANNEL=stderr
LOG_STACK=single
LOG_LEVEL=info
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_DOMAIN=
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
CACHE_STORE=file
CACHE_PREFIX=laravel_cache
CACHE_REDIS_CONNECTION=default
QUEUE_CONNECTION=sync
QUEUE_FAILED_TABLE=failed_jobs
FILESYSTEM_DISK=local
FILESYSTEM_VISIBILITY=private
BROADCAST_DRIVER=log
BROADCAST_CONNECTION=default
MAIL_MAILER=log
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@traffic.com
MAIL_FROM_NAME=Traffic# Traffic App - Deployment Guide

Complete step-by-step guide to deploy the Traffic application to a production server.

## Table of Contents
1. [Server Requirements](#server-requirements)
2. [Quick Start](#quick-start)
3. [Manual Deployment](#manual-deployment)
4. [Nginx Configuration](#nginx-configuration)
5. [MongoDB Setup](#mongodb-setup)
6. [SSL/HTTPS Setup](#sslhttps-setup)
7. [Post-Deployment](#post-deployment)
8. [Troubleshooting](#troubleshooting)

---

## Server Requirements

### Minimum Specifications
- **OS**: Ubuntu 20.04+ / Debian 11+ / CentOS 8+ / Windows Server 2019+
- **PHP**: 8.2 or higher
- **RAM**: 2GB minimum (4GB recommended)
- **Disk Space**: 5GB minimum (10GB recommended)

### Required Software
```bash
# Ubuntu/Debian
sudo apt update
sudo apt install -y \
    php8.2 \
    php8.2-fpm \
    php8.2-curl \
    php8.2-mbstring \
    php8.2-xml \
    php8.2-bcmath \
    php8.2-json \
    php8.2-mongodb \
    nginx \
    composer \
    nodejs \
    npm \
    git \
    curl \
    mongodb-org
```

### PHP Extensions Verification
```bash
php -m | grep -E 'mongodb|curl|mbstring|xml'
```

---

## Quick Start

### 1. Clone Repository
```bash
cd /var/www
git clone https://github.com/Palaksachan5099/traffic.git
cd traffic
```

### 2. Run Deployment Script
```bash
# Linux/Mac
chmod +x deploy.sh
./deploy.sh

# Windows
deploy.bat
```

That's it! The script handles all installation steps.

---

## Manual Deployment

### Step 1: Upload Files
```bash
# Via Git
cd /var/www
git clone https://github.com/Palaksachan5099/traffic.git
cd traffic

# Or via FTP/SCP
# Upload entire project directory to /var/www/traffic
```

### Step 2: Configure Environment
```bash
cp .env.example .env
nano .env  # Edit with your settings
```

**Essential .env settings:**
```env
APP_NAME=Traffic
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
APP_KEY=  # Will be generated

# Database
DB_CONNECTION=mongodb
DB_URI=mongodb://localhost:27017
MONGO_DATABASE=trafficDB

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

# Cache & Session
CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### Step 3: Install Dependencies
```bash
# PHP packages
composer install --no-dev --optimize-autoloader

# Frontend packages
npm install
npm run build

# Generate key
php artisan key:generate

# Create storage symlink
php artisan storage:link
```

### Step 4: Database Setup
```bash
# Run migrations
php artisan migrate --force

# (Optional) Seed database
php artisan db:seed
```

### Step 5: Set Permissions
```bash
# Ownership
sudo chown -R www-data:www-data /var/www/traffic

# Permissions
chmod -R 775 storage bootstrap/cache
chmod 644 .env
```

### Step 6: Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## Nginx Configuration

### Create Vhost File
```bash
sudo nano /etc/nginx/sites-available/traffic
```

### Paste Configuration
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    
    # Redirect HTTP to HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;
    
    root /var/www/traffic/public;
    index index.php index.html;
    
    # SSL certificates (will be set up by Let's Encrypt)
    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;
    
    # Security headers
    add_header Strict-Transport-Security "max-age=31536000" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    
    # Gzip compression
    gzip on;
    gzip_types text/plain text/css text/javascript application/json application/javascript;
    gzip_min_length 1024;
    
    # Laravel routes
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    # PHP-FPM
    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        
        # Prevent .htaccess processing
        fastcgi_intercept_errors on;
    }
    
    # Deny access to hidden files
    location ~ /\. {
        deny all;
    }
    
    # Logging
    access_log /var/log/nginx/traffic_access.log;
    error_log /var/log/nginx/traffic_error.log;
    
    # File upload limit
    client_max_body_size 50M;
}
```

### Enable Vhost
```bash
sudo ln -s /etc/nginx/sites-available/traffic /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

---

## MongoDB Setup

### Install MongoDB (Ubuntu/Debian)
```bash
curl -fsSL https://www.mongodb.org/static/pgp/server-7.0.asc | sudo apt-key add -
echo "deb [ arch=amd64,arm64 ] https://repo.mongodb.org/apt/ubuntu focal/mongodb-org/7.0 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-7.0.list
sudo apt-get update
sudo apt-get install -y mongodb-org
sudo systemctl start mongod
sudo systemctl enable mongod
```

### Create Database & User
```bash
mongosh
```

Inside mongosh:
```javascript
use trafficDB
db.createUser({
  user: "traffic",
  pwd: "secure_password_here",
  roles: ["dbOwner"]
})
exit
```

### Update .env
```env
DB_CONNECTION=mongodb
DB_URI=mongodb://traffic:secure_password_here@localhost:27017/trafficDB?authSource=trafficDB
MONGO_DATABASE=trafficDB
```

---

## SSL/HTTPS Setup

### Install Certbot
```bash
sudo apt install -y certbot python3-certbot-nginx
```

### Generate Certificate
```bash
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
sudo certbot renew --dry-run
```

Update nginx config above with the certificate paths Certbot provides.

---

## Post-Deployment

### 1. Verify Installation
```bash
# Check application
curl https://yourdomain.com

# Test email
php artisan tinker
> Mail::raw('Test', function ($m) { $m->to('your@email.com'); });

# Check logs
tail -f storage/logs/laravel.log
```

### 2. Set Up Supervisor (For Queue Jobs)
```bash
sudo nano /etc/supervisor/conf.d/traffic.conf
```

Paste:
```ini
[program:traffic-queue-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/traffic/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/traffic/storage/logs/queue.log
```

Then:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start traffic-queue-worker:*
```

### 3. Set Up Cron Job
```bash
sudo crontab -e
```

Add:
```cron
* * * * * cd /var/www/traffic && php artisan schedule:run >> /dev/null 2>&1
```

### 4. Monitor Application
```bash
# View recent logs
tail -f /var/www/traffic/storage/logs/laravel.log

# Check Nginx errors
tail -f /var/log/nginx/traffic_error.log

# Monitor processes
sudo supervisorctl status
```

---

## Troubleshooting

### 502 Bad Gateway
```bash
# Check PHP-FPM status
sudo systemctl status php8.2-fpm

# Restart PHP-FPM
sudo systemctl restart php8.2-fpm

# Check socket permissions
ls -la /run/php/php8.2-fpm.sock
```

### Permission Denied Errors
```bash
sudo chown -R www-data:www-data /var/www/traffic
chmod -R 755 /var/www/traffic
chmod -R 775 storage bootstrap/cache
```

### MongoDB Connection Failed
```bash
# Check MongoDB status
sudo systemctl status mongod

# Verify connection
mongosh "mongodb://traffic:password@localhost:27017/trafficDB"
```

### High Memory Usage
```bash
# Restart PHP-FPM workers
sudo systemctl restart php8.2-fpm

# Check Nginx worker count
ps aux | grep nginx
```

### Storage Symlink Issues
```bash
# Remove and recreate
rm public/storage
php artisan storage:link
chmod -R 775 storage
```

---

## Support

For issues, check:
- Application logs: `storage/logs/laravel.log`
- Nginx logs: `/var/log/nginx/traffic_*.log`
- MongoDB logs: `sudo journalctl -u mongod -f`
- PHP-FPM logs: `sudo tail -f /var/log/php8.2-fpm.log`

---

**Deployment complete!** 🎉
