# Complete Render Environment Variables - Ready to Copy & Paste

## COPY THESE EXACTLY INTO RENDER DASHBOARD

# Step 1: Generate APP_KEY First
# Run this command on your local machine:
# php artisan key:generate --show
# It will output something like: base64:abc123def456...

# Step 2: Get MongoDB Connection String
# After creating MongoDB on Render, it shows the connection string like:
# mongodb://default:password123@mongo.onrender.com:27017/trafficDB

---

# PASTE THESE INTO RENDER ENVIRONMENT VARIABLES:

APP_NAME=Traffic
APP_ENV=production
APP_DEBUG=false
APP_URL=https://traffic-app.onrender.com
APP_KEY=base64:your_generated_key_here
APP_TIMEZONE=UTC
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_MAINTENANCE_DRIVER=file
BCRYPT_ROUNDS=12
HASH_DRIVER=bcrypt

DB_CONNECTION=mongodb
DB_URI=mongodb://default:your_password@traffic-mongodb:27017/trafficDB?authSource=admin
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
MAIL_FROM_NAME=Traffic

---

## HOW TO SET UP:

### 1. GENERATE APP_KEY (on your Windows machine first)

Open PowerShell in your project:
```
php artisan key:generate --show
```

It will output:
```
base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

Copy this entire value (including "base64:")

### 2. CREATE MONGODB ON RENDER

1. Go to render.com dashboard
2. Click "New +" → "Database" → "MongoDB"
3. Name: `traffic-mongodb`
4. Tier: Free
5. Click "Create"
6. Wait 2-3 minutes for creation
7. Click on the database → Copy connection string
8. It looks like: `mongodb://default:RandomPassword123@mongo.onrender.com:27017/trafficDB`

### 3. SET VARIABLES IN RENDER

In Render Dashboard of your Web Service:

1. Go to "Settings" → "Environment"
2. Add each variable one by one:
   - Key: `APP_NAME`
   - Value: `Traffic`
   - Click "Add"

3. For APP_KEY:
   - Key: `APP_KEY`
   - Value: `base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx` (from step 1)

4. For DB_URI:
   - Key: `DB_URI`
   - Value: `mongodb://default:RandomPassword123@mongo.onrender.com:27017/trafficDB` (from step 2)

5. Continue with rest of variables...

---

## EXAMPLE WITH REAL VALUES:

APP_NAME=Traffic
APP_ENV=production
APP_DEBUG=false
APP_URL=https://traffic-app-xyz123.onrender.com
APP_KEY=base64:AbCdEfGhIjKlMnOpQrStUvWxYzAbCdEfGhIjKlMnOpQrStUvWxYzAb
APP_TIMEZONE=UTC
APP_LOCALE=en

DB_CONNECTION=mongodb
DB_URI=mongodb://default:xyz123abc456@mongo-12345.onrender.com:27017/trafficDB?authSource=admin
MONGO_DATABASE=trafficDB

LOG_CHANNEL=stderr
LOG_LEVEL=info

SESSION_DRIVER=file
SESSION_SECURE_COOKIE=true

CACHE_STORE=file
QUEUE_CONNECTION=sync
MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@traffic.com

---

## QUICK REFERENCE:

Replace ONLY these values:
- `your_generated_key_here` → Your APP_KEY from step 1
- `your_password` → Your MongoDB password (from Render MongoDB creation)
- `traffic-mongodb` → Your MongoDB host (from Render)
- `traffic-app.onrender.com` → Your actual Render domain

Everything else stays the same!
