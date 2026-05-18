# Render Deployment Guide for Traffic App

Complete guide to deploy your Laravel Traffic application on **Render.com** with MongoDB.

## Table of Contents
1. [Create Render Account](#create-render-account)
2. [Set Up MongoDB](#set-up-mongodb)
3. [Prepare Your Repository](#prepare-your-repository)
4. [Deploy on Render](#deploy-on-render)
5. [Post-Deployment Configuration](#post-deployment-configuration)
6. [Troubleshooting](#troubleshooting)

---

## Create Render Account

1. **Go to** [Render.com](https://render.com)
2. **Sign up** with GitHub (recommended)
3. **Connect** your GitHub account to Render
4. **Authorize** Render to access your repositories

---

## Set Up MongoDB

### Option 1: Use Render's Marketplace (Easiest)
1. Go to Render Dashboard → Click **"New +"**
2. Select **"Database"** → **"MongoDB"**
3. Configure:
   - **Name**: `traffic-mongodb`
   - **Region**: Choose closest to your users
   - **MongoDB Version**: 7.0
4. Click **"Create Database"**
5. **Copy the connection string** (shown under "Connection String")
   - Save it for later (you'll need it in Step 5)

### Option 2: Use External MongoDB
- MongoDB Atlas (cloud): https://www.mongodb.com/cloud/atlas
- Your own managed MongoDB server

---

## Prepare Your Repository

### 1. Verify render.yaml Exists
Check if `/render.yaml` is in your project root. If not, create one:

```yaml
services:
  - type: web
    name: traffic-app
    env: docker
    region: oregon
    dockerfilePath: ./Dockerfile
    envVars:
      - key: APP_ENV
        value: production
      - key: APP_DEBUG
        value: "false"
      - key: LOG_CHANNEL
        value: stderr
      - key: QUEUE_CONNECTION
        value: sync
      - key: CACHE_STORE
        value: file
      - key: SESSION_DRIVER
        value: file
      - key: DB_CONNECTION
        value: mongodb
```

### 2. Push to GitHub
```powershell
git add render.yaml deploy.sh deploy.bat DEPLOYMENT.md
git commit -m "Add Render deployment configuration"
git push origin main
```

---

## Deploy on Render

### Step 1: Create New Web Service

1. Go to [Render Dashboard](https://dashboard.render.com)
2. Click **"New +"** → **"Web Service"**
3. Select **"Deploy an existing repository"**
4. Choose your **traffic** repository
5. Click **"Connect"**

### Step 2: Configure Service

Fill in the following:

| Field | Value |
|-------|-------|
| **Name** | traffic-app |
| **Environment** | Docker |
| **Region** | Oregon (or closest to you) |
| **Branch** | main |
| **Dockerfile Path** | ./Dockerfile |
| **Plan** | Free (or Starter paid plan) |

### Step 3: Set Environment Variables

Click **"Advanced"** and add these environment variables:

```
APP_NAME=Traffic
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app-name.onrender.com
APP_KEY=                           # Leave empty, will generate on first deploy
LOG_CHANNEL=stderr
LOG_LEVEL=info
DB_CONNECTION=mongodb
DB_URI=mongodb://username:password@traffic-mongodb:27017/trafficDB
MONGO_DATABASE=trafficDB
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

**Replace with your MongoDB connection details:**
- `username` - Your MongoDB username
- `password` - Your MongoDB password
- `traffic-mongodb` - Your MongoDB service name on Render

### Step 4: Build & Deploy

1. Click **"Create Web Service"**
2. Render will start building (takes 3-5 minutes)
3. Watch the build logs for any errors
4. Once deployed, you'll get a URL: `https://traffic-app-xxxxx.onrender.com`

---

## Post-Deployment Configuration

### 1. Generate Application Key

After first deployment, you need to generate the APP_KEY:

```bash
# SSH into your service (from Render dashboard)
# Or use Render Shell

php artisan key:generate
```

### 2. Set Generated Key in Environment Variables

1. Go to your service on Render Dashboard
2. Click **"Environment"**
3. Find `APP_KEY` and update it with the generated key
4. Click **"Save"**
5. Service will auto-restart

### 3. Run Migrations

Option A: Via Render Shell (Dashboard)
```bash
php artisan migrate --force
```

Option B: Add to Dockerfile (automatic)
Edit your `Dockerfile` to add migrations to the entrypoint.

### 4. Update Your Domain (Optional)

If you have a custom domain:

1. Go to **Settings** → **Custom Domain**
2. Add your domain (e.g., `traffic.yourdomain.com`)
3. Add DNS records as Render instructs
4. Wait for SSL to be issued (10-15 minutes)

---

## Important Notes

### ⚠️ Free Tier Limitations
- **Spins down after 15 min of inactivity** (restarting takes ~30 sec)
- **Shared CPU** (good for development/testing)
- **No email delivery** from free tier
- **Limited storage**

### ✅ Upgrade to Paid Plan
For production reliability, upgrade to **Starter Plan** ($12.50/month):
- Always running (no spin down)
- Dedicated resources
- Better performance

### 📧 Email Configuration
Email from free tier doesn't work. Options:
1. Upgrade to paid plan
2. Use external email service (SendGrid, Mailgun)
3. Disable password reset emails temporarily

---

## Environment Variables Full List

```
# App
APP_NAME=Traffic
APP_ENV=production
APP_DEBUG=false
APP_URL=https://traffic-app-xxxxx.onrender.com
APP_KEY=base64:your-generated-key-here
APP_TIMEZONE=UTC

# Database
DB_CONNECTION=mongodb
DB_URI=mongodb://user:pass@traffic-mongodb:27017/trafficDB
MONGO_DATABASE=trafficDB

# Logging
LOG_CHANNEL=stderr
LOG_LEVEL=info

# Session & Cache
SESSION_DRIVER=file
SESSION_DOMAIN=
SESSION_SECURE_COOKIE=true
CACHE_STORE=file

# Queue
QUEUE_CONNECTION=sync

# Filesystem
FILESYSTEM_DISK=local

# Mail (optional, set if you upgrade)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=noreply@traffic.com
MAIL_FROM_NAME=Traffic
```

---

## Deployment Flow Diagram

```
GitHub Repository
        ↓
   Render Webhook
        ↓
   Docker Build (Dockerfile)
        ↓
   Install Dependencies
   (Composer, NPM)
        ↓
   Build Frontend Assets
        ↓
   Start PHP & Nginx
        ↓
   ✅ Live on Render
```

---

## Monitoring & Logs

### View Live Logs
1. Go to your service on Render Dashboard
2. Click **"Logs"** tab
3. See real-time application logs

### Common Issues in Logs

**"Connection refused to MongoDB"**
```
→ Check DB_URI environment variable
→ Verify MongoDB service is running
→ Check username/password
```

**"APP_KEY not set"**
```
→ Generate key: php artisan key:generate
→ Update APP_KEY in environment variables
→ Redeploy
```

**"Permission denied on storage"**
```
→ Usually not an issue on Render
→ Check storage directory permissions
```

---

## Useful Render CLI Commands

### Install Render CLI
```bash
npm install -g @render-oss/render-cli
render login
```

### View Logs
```bash
render logs --service-id=srv_xxxxx
```

### SSH into Service
```bash
render shell --service-id=srv_xxxxx
```

### View Deployed Services
```bash
render services --owner-id=your_owner_id
```

---

## Troubleshooting

### Build Fails
1. Check the **Build Logs** in Render Dashboard
2. Common causes:
   - PHP extension not installed
   - Node/npm version mismatch
   - Composer dependency conflict
3. Solution: Push fix to GitHub, Render auto-redeploys

### Service Won't Start
1. Check **Runtime Logs**
2. Verify all environment variables are set
3. Check database connection
4. Run: `php artisan config:cache`

### Can't Connect to MongoDB
```bash
# SSH into service and test
mongosh "mongodb://user:pass@traffic-mongodb:27017/trafficDB"
```

### Slow Performance
- Upgrade to paid plan (free tier spins down)
- Enable caching: `CACHE_STORE=redis`
- Optimize database queries

### File Upload Issues
Files uploaded to Render are ephemeral (deleted on redeploy).

Solution: Use external storage:
```env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket
```

---

## Cost Estimation

| Plan | Price | For Production | Notes |
|------|-------|----------------|-------|
| **Free** | $0 | ❌ No | Spins down, limited features |
| **Starter** | $12.50/mo | ✅ Yes | Always on, good start |
| **MongoDB** | $15/mo | ✅ Yes | If using Render MongoDB |
| **Total** | ~$28/mo | ✅ | Full production setup |

---

## Next Steps

1. ✅ Push your code to GitHub
2. ✅ Create Render account
3. ✅ Set up MongoDB on Render
4. ✅ Deploy your web service
5. ✅ Generate APP_KEY
6. ✅ Run migrations
7. ✅ Test your application
8. ✅ (Optional) Add custom domain
9. ✅ (Optional) Upgrade to paid plan

---

## Support & Resources

- **Render Docs**: https://render.com/docs
- **Laravel Docs**: https://laravel.com/docs/11.x
- **MongoDB Docs**: https://docs.mongodb.com
- **Community Help**: https://render.com/slack

---

**Your app will be live at:** 🎉
```
https://traffic-app-xxxxx.onrender.com
```

Good luck! 🚀
