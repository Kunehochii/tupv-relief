# RELIEF - Railway Deployment Guide

> Complete deployment guide for deploying the Relief application to Railway

**Last Updated:** January 16, 2026

---

## Table of Contents

1. [Prerequisites](#1-prerequisites)
2. [Project Structure for Railway](#2-project-structure-for-railway)
3. [Database Setup (MySQL)](#3-database-setup-mysql)
4. [Web Service Setup](#4-web-service-setup)
5. [Environment Variables](#5-environment-variables)
6. [File Storage Configuration](#6-file-storage-configuration)
7. [Scheduled Jobs (Cron)](#7-scheduled-jobs-cron)
8. [Domain & SSL](#8-domain--ssl)
9. [Deployment Workflow](#9-deployment-workflow)
10. [Post-Deployment Checklist](#10-post-deployment-checklist)
11. [Troubleshooting](#11-troubleshooting)

---

## 1. Prerequisites

Before deploying to Railway, ensure you have:

- [ ] A [Railway account](https://railway.app) (free tier available)
- [ ] Your code pushed to a **GitHub repository**
- [ ] A [SendGrid account](https://sendgrid.com) with API key for emails
- [ ] A [Google Cloud Console](https://console.cloud.google.com) project for OAuth

---

## 2. Project Structure for Railway

### 2.1 Create Required Configuration Files

Railway needs specific files to understand how to build and run your Laravel app.

#### Create `Procfile` (in project root)

```
web: php artisan serve --host=0.0.0.0 --port=$PORT
```

> **Better Alternative:** Use Nixpacks (Railway's default). No Procfile needed if you add `nixpacks.toml`.

#### Create `nixpacks.toml` (in project root) - RECOMMENDED

```toml
[phases.setup]
nixPkgs = ["php82", "php82Extensions.pdo_mysql", "php82Extensions.mbstring", "php82Extensions.xml", "php82Extensions.curl", "php82Extensions.zip", "php82Extensions.gd"]

[phases.install]
cmds = ["composer install --no-dev --optimize-autoloader"]

[phases.build]
cmds = [
    "php artisan config:cache",
    "php artisan route:cache",
    "php artisan view:cache"
]

[start]
cmd = "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"
```

#### Create `.dockerignore` (in project root)

```
/node_modules
/vendor
/.git
/.idea
/.vscode
.env
.env.local
.env.*.local
/storage/*.key
```

---

## 3. Database Setup (MySQL)

### 3.1 Add MySQL Service

1. Go to your Railway project dashboard
2. Click **"+ New"** → **"Database"** → **"MySQL"**
3. Railway will provision a MySQL instance automatically

### 3.2 Get Database Credentials

After MySQL is provisioned, click on the MySQL service and go to **"Variables"** tab. You'll see:

| Variable        | Description                  |
| --------------- | ---------------------------- |
| `MYSQLHOST`     | Database host                |
| `MYSQLPORT`     | Database port (usually 3306) |
| `MYSQLDATABASE` | Database name                |
| `MYSQLUSER`     | Database username            |
| `MYSQLPASSWORD` | Database password            |
| `MYSQL_URL`     | Full connection URL          |

### 3.3 Link Database to Web Service

Railway can auto-inject these variables. In your web service:

1. Go to **"Variables"** tab
2. Click **"+ Add Variable"** → **"Add Reference"**
3. Select your MySQL service and link the variables

**Map Railway MySQL variables to Laravel variables:**

| Laravel Variable | Railway Reference          |
| ---------------- | -------------------------- |
| `DB_HOST`        | `${{MySQL.MYSQLHOST}}`     |
| `DB_PORT`        | `${{MySQL.MYSQLPORT}}`     |
| `DB_DATABASE`    | `${{MySQL.MYSQLDATABASE}}` |
| `DB_USERNAME`    | `${{MySQL.MYSQLUSER}}`     |
| `DB_PASSWORD`    | `${{MySQL.MYSQLPASSWORD}}` |

---

## 4. Web Service Setup

### 4.1 Deploy from GitHub

1. Go to [Railway Dashboard](https://railway.app/dashboard)
2. Click **"+ New Project"**
3. Select **"Deploy from GitHub repo"**
4. Authorize Railway to access your GitHub
5. Select your `relief` repository
6. Railway will auto-detect Laravel and start building

### 4.2 Configure Build Settings

In your service settings:

- **Root Directory:** `/` (leave default)
- **Build Command:** (handled by nixpacks.toml)
- **Start Command:** (handled by nixpacks.toml)

---

## 5. Environment Variables

### 5.1 Required Variables

Add these in your Railway web service **"Variables"** tab:

#### Application Settings

| Variable       | Value                          | Description                                     |
| -------------- | ------------------------------ | ----------------------------------------------- |
| `APP_NAME`     | `Relief`                       | Application name                                |
| `APP_ENV`      | `production`                   | Environment                                     |
| `APP_KEY`      | `base64:xxxxx`                 | Generate with `php artisan key:generate --show` |
| `APP_DEBUG`    | `false`                        | **IMPORTANT: Must be false in production**      |
| `APP_URL`      | `https://your-app.railway.app` | Your Railway domain                             |
| `APP_TIMEZONE` | `Asia/Manila`                  | Philippine timezone                             |

#### Database (Reference Variables)

| Variable        | Value                      |
| --------------- | -------------------------- |
| `DB_CONNECTION` | `mysql`                    |
| `DB_HOST`       | `${{MySQL.MYSQLHOST}}`     |
| `DB_PORT`       | `${{MySQL.MYSQLPORT}}`     |
| `DB_DATABASE`   | `${{MySQL.MYSQLDATABASE}}` |
| `DB_USERNAME`   | `${{MySQL.MYSQLUSER}}`     |
| `DB_PASSWORD`   | `${{MySQL.MYSQLPASSWORD}}` |

#### SendGrid Email Configuration

| Variable            | Value                    | Description                  |
| ------------------- | ------------------------ | ---------------------------- |
| `MAIL_MAILER`       | `smtp`                   | Use SMTP                     |
| `MAIL_HOST`         | `smtp.sendgrid.net`      | SendGrid SMTP server         |
| `MAIL_PORT`         | `587`                    | TLS port                     |
| `MAIL_USERNAME`     | `apikey`                 | Always "apikey" for SendGrid |
| `MAIL_PASSWORD`     | `SG.xxxxxxxx`            | Your SendGrid API Key        |
| `MAIL_ENCRYPTION`   | `tls`                    | Use TLS                      |
| `MAIL_FROM_ADDRESS` | `noreply@yourrelief.com` | Sender email                 |
| `MAIL_FROM_NAME`    | `Relief DSWD`            | Sender name                  |

#### Google OAuth Configuration

| Variable               | Value                                               | Description               |
| ---------------------- | --------------------------------------------------- | ------------------------- |
| `GOOGLE_CLIENT_ID`     | `xxxxx.apps.googleusercontent.com`                  | From Google Cloud Console |
| `GOOGLE_CLIENT_SECRET` | `GOCSPX-xxxxxxx`                                    | From Google Cloud Console |
| `GOOGLE_REDIRECT_URI`  | `https://your-app.railway.app/auth/google/callback` | OAuth callback URL        |

#### Session & Cache

| Variable           | Value      |
| ------------------ | ---------- |
| `SESSION_DRIVER`   | `database` |
| `CACHE_DRIVER`     | `database` |
| `QUEUE_CONNECTION` | `database` |

#### Logging

| Variable      | Value    |
| ------------- | -------- |
| `LOG_CHANNEL` | `stderr` |
| `LOG_LEVEL`   | `error`  |

### 5.2 Generate APP_KEY

Run this locally to generate your app key:

```bash
php artisan key:generate --show
```

Copy the output (e.g., `base64:xxxxxxxxx...`) and paste it as the `APP_KEY` value in Railway.

---

## 6. File Storage Configuration

### 6.1 Understanding Storage in Railway

Railway uses **ephemeral storage** by default—files are lost on redeploy. For NGO certificate uploads, you have options:

### Option A: Railway Volume (Recommended for Simplicity)

1. In your web service, go to **"Settings"** → **"Volumes"**
2. Click **"+ Add Volume"**
3. Set:
   - **Mount Path:** `/app/storage/app`
   - **Size:** 1GB (adjust as needed)

This persists your `storage/app` directory across deployments.

### Option B: Cloud Storage (Recommended for Production)

For better scalability, use AWS S3 or Cloudflare R2:

1. Install the flysystem S3 adapter:

   ```bash
   composer require league/flysystem-aws-s3-v3 "^3.0"
   ```

2. Add these environment variables:

   ```
   FILESYSTEM_DISK=s3
   AWS_ACCESS_KEY_ID=your-key
   AWS_SECRET_ACCESS_KEY=your-secret
   AWS_DEFAULT_REGION=ap-southeast-1
   AWS_BUCKET=relief-storage
   AWS_URL=https://your-bucket.s3.amazonaws.com
   ```

3. Update your file upload code to use `Storage::disk('s3')`.

### 6.2 Storage Symlink

Add this to your `nixpacks.toml` build commands:

```toml
[phases.build]
cmds = [
    "php artisan storage:link",
    "php artisan config:cache",
    "php artisan route:cache",
    "php artisan view:cache"
]
```

---

## 7. Scheduled Jobs (Cron)

Relief has two scheduled commands that need to run:

| Command               | Schedule          | Purpose                     |
| --------------------- | ----------------- | --------------------------- |
| `pledges:warn-expiry` | Daily at 8:00 AM  | Send 18-hour warning emails |
| `pledges:expire`      | Daily at midnight | Expire unverified pledges   |

### 7.1 Option A: Railway Cron Service (Recommended)

Railway supports cron jobs as separate services:

1. **Create a new service** in your Railway project
2. Click **"+ New"** → **"Empty Service"**
3. Name it `relief-scheduler`
4. Connect it to the same GitHub repo
5. Set **Start Command** to:
   ```bash
   php artisan schedule:work
   ```

This runs Laravel's scheduler continuously, executing jobs at their scheduled times.

**Add these variables to the cron service** (same as web service):

- All `DB_*` variables (reference MySQL)
- All `MAIL_*` variables
- `APP_KEY`, `APP_ENV`, `APP_URL`

### 7.2 Option B: External Cron Service

Use a free service like [cron-job.org](https://cron-job.org):

1. Create an endpoint in your app (add to `routes/web.php`):

   ```php
   Route::get('/cron/run-scheduler', function () {
       if (request('token') !== config('app.cron_token')) {
           abort(403);
       }
       Artisan::call('schedule:run');
       return 'Scheduler executed';
   });
   ```

2. Add `CRON_TOKEN` to your environment variables

3. Set up cron-job.org to hit:
   ```
   https://your-app.railway.app/cron/run-scheduler?token=YOUR_SECRET_TOKEN
   ```
   - Schedule: Every minute (or every 5 minutes)

### 7.3 Scheduler Commands Reference

These are your scheduled tasks (defined in `routes/console.php`):

```php
// Send expiry warnings daily at 8:00 AM (Philippine Time)
Schedule::command('pledges:warn-expiry')->dailyAt('08:00');

// Expire unverified pledges daily at midnight
Schedule::command('pledges:expire')->dailyAt('00:00');
```

---

## 8. Domain & SSL

### 8.1 Railway-Provided Domain

Railway automatically provides a domain like:

```
relief-production.up.railway.app
```

SSL is automatically configured for Railway domains.

### 8.2 Custom Domain

1. Go to your web service → **"Settings"** → **"Domains"**
2. Click **"+ Custom Domain"**
3. Enter your domain (e.g., `relief.dswd.gov.ph`)
4. Add the provided CNAME record to your DNS:
   ```
   Type: CNAME
   Name: relief (or @ for root)
   Value: your-app.up.railway.app
   ```
5. Wait for DNS propagation (up to 48 hours)

**Update `APP_URL` and `GOOGLE_REDIRECT_URI`** after adding custom domain!

---

## 9. Deployment Workflow

### 9.1 Initial Deployment

```
┌─────────────────────────────────────────────────────────────┐
│  1. Create Railway Project                                   │
│     └── Connect GitHub Repository                           │
├─────────────────────────────────────────────────────────────┤
│  2. Add MySQL Database                                       │
│     └── Link to Web Service                                 │
├─────────────────────────────────────────────────────────────┤
│  3. Configure Environment Variables                          │
│     ├── App settings (APP_KEY, APP_URL, etc.)              │
│     ├── Database (reference MySQL service)                  │
│     ├── Mail (SendGrid credentials)                         │
│     └── Google OAuth credentials                            │
├─────────────────────────────────────────────────────────────┤
│  4. Add Storage Volume (optional)                            │
│     └── Mount at /app/storage/app                           │
├─────────────────────────────────────────────────────────────┤
│  5. Deploy & Run Migrations                                  │
│     └── Automatic via nixpacks.toml                         │
├─────────────────────────────────────────────────────────────┤
│  6. Seed Database                                            │
│     └── Run: php artisan db:seed (via Railway shell)        │
├─────────────────────────────────────────────────────────────┤
│  7. Set Up Scheduler Service                                 │
│     └── Create separate service for cron jobs               │
├─────────────────────────────────────────────────────────────┤
│  8. Configure Custom Domain (optional)                       │
│     └── Update APP_URL and GOOGLE_REDIRECT_URI              │
└─────────────────────────────────────────────────────────────┘
```

### 9.2 Subsequent Deployments

Just push to your GitHub repository:

```bash
git add .
git commit -m "Your changes"
git push origin main
```

Railway automatically:

1. Detects the push
2. Builds the application
3. Runs migrations (`php artisan migrate --force`)
4. Deploys the new version

---

## 10. Post-Deployment Checklist

### 10.1 Run Database Seeder

After first deployment, seed the admin user:

1. Go to your web service in Railway
2. Click **"Settings"** → **"Open Shell"** (or use Railway CLI)
3. Run:
   ```bash
   php artisan db:seed --class=AdminSeeder
   ```

### 10.2 Verify Everything Works

- [ ] **Homepage loads:** `https://your-app.railway.app`
- [ ] **Admin login works:** Login as `admin@relief.dswd.gov.ph`
- [ ] **Database connected:** Create a test drive
- [ ] **File uploads work:** Test NGO registration with certificate
- [ ] **Emails send:** Test pledge creation (check SendGrid logs)
- [ ] **Google OAuth works:** Test donor registration via Google
- [ ] **Scheduler runs:** Check logs for `pledges:warn-expiry` execution

### 10.3 Update Google OAuth Redirect

In Google Cloud Console:

1. Go to **APIs & Services** → **Credentials**
2. Edit your OAuth 2.0 Client
3. Add authorized redirect URI:
   ```
   https://your-app.railway.app/auth/google/callback
   ```

---

## 11. Troubleshooting

### Common Issues

#### "SQLSTATE[HY000] Connection refused"

- Check that MySQL service is running
- Verify `DB_HOST` uses the Railway reference `${{MySQL.MYSQLHOST}}`

#### "Class not found" errors

- Run `composer install` locally and commit `composer.lock`
- Clear config cache: `php artisan config:clear`

#### Emails not sending

- Verify SendGrid API key is correct
- Check SendGrid dashboard for bounced/blocked emails
- Ensure `MAIL_FROM_ADDRESS` is verified in SendGrid

#### Google OAuth "redirect_uri_mismatch"

- Ensure `GOOGLE_REDIRECT_URI` matches exactly in:
  - Railway environment variables
  - Google Cloud Console authorized redirects
- Include `https://` and the exact domain

#### Storage/file upload issues

- Ensure Railway Volume is mounted at `/app/storage/app`
- Run `php artisan storage:link` in Railway shell

### Viewing Logs

1. Go to your service in Railway
2. Click **"Deployments"** → Select a deployment
3. Click **"View Logs"**

Or use Railway CLI:

```bash
railway logs
```

### Running Artisan Commands

Option 1: Railway Dashboard

- Service → Settings → Open Shell

Option 2: Railway CLI

```bash
railway run php artisan <command>
```

---

## Quick Reference

### Environment Variables Summary

```env
# Application
APP_NAME=Relief
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY
APP_DEBUG=false
APP_URL=https://your-app.railway.app
APP_TIMEZONE=Asia/Manila

# Database (use Railway references)
DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}

# Session & Cache
SESSION_DRIVER=database
CACHE_DRIVER=database
QUEUE_CONNECTION=database

# SendGrid Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=SG.your-sendgrid-api-key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@relief.dswd.gov.ph
MAIL_FROM_NAME="Relief DSWD"

# Google OAuth
GOOGLE_CLIENT_ID=your-client-id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT_URI=https://your-app.railway.app/auth/google/callback

# Logging
LOG_CHANNEL=stderr
LOG_LEVEL=error
```

### Useful Commands

```bash
# Generate app key
php artisan key:generate --show

# Run migrations
php artisan migrate --force

# Seed database
php artisan db:seed

# Clear all caches
php artisan optimize:clear

# Cache for production
php artisan optimize

# Check scheduled tasks
php artisan schedule:list
```

---

## Cost Estimation

Railway's pricing (as of 2026):

| Resource           | Free Tier       | Estimated Monthly |
| ------------------ | --------------- | ----------------- |
| Web Service        | 500 hours/month | $5-10             |
| MySQL Database     | 1GB included    | $5-10             |
| Scheduler Service  | Shared hours    | $2-5              |
| Storage Volume     | 1GB             | $0.25/GB          |
| **Total Estimate** | -               | **$12-25/month**  |

> Free tier covers ~20 days of continuous operation. For production, expect ~$15-25/month.

---

_Last Updated: January 16, 2026_
