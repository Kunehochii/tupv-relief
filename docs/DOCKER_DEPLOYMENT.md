# Docker Deployment Guide for Railway

This guide walks you through building, tagging, and pushing the Relief Docker image to Docker Hub, then deploying it on Railway.

---

## Prerequisites

1. **Docker Desktop** installed and running
2. **Docker Hub account** - Create one at [hub.docker.com](https://hub.docker.com)
3. **Railway account** - Sign up at [railway.app](https://railway.app)

---

## Step 1: Build the Docker Image

Open a terminal in the project root directory (`tupv-relief`) and run:

```powershell
# Build the image
docker build -t relief-app .
```

This will:
- Install Composer dependencies
- Set up PHP 8.2 with required extensions
- Configure Nginx and Supervisor
- Prepare the application for production

**Build time:** ~3-5 minutes on first build

---

## Step 2: Test Locally (Optional)

Before pushing, you can test the image locally:

```powershell
# Run with environment variables
docker run -d -p 8080:8080 \
  -e APP_KEY=base64:your-app-key-here \
  -e APP_ENV=production \
  -e APP_DEBUG=false \
  -e DB_CONNECTION=mysql \
  -e DB_HOST=host.docker.internal \
  -e DB_PORT=3306 \
  -e DB_DATABASE=relief \
  -e DB_USERNAME=root \
  -e DB_PASSWORD=your-password \
  --name relief-test \
  relief-app
```

Visit `http://localhost:8080` to verify it works.

```powershell
# Stop and remove test container
docker stop relief-test
docker rm relief-test
```

---

## Step 3: Login to Docker Hub

```powershell
docker login
```

Enter your Docker Hub username and password when prompted.

---

## Step 4: Tag the Image

Replace `yourusername` with your Docker Hub username:

```powershell
# Tag with your Docker Hub username and version
docker tag relief-app yourusername/relief-app:latest
docker tag relief-app yourusername/relief-app:v1.0.0
```

**Tagging convention:**
- `latest` - Always points to the most recent stable version
- `v1.0.0` - Specific version for rollback capability

---

## Step 5: Push to Docker Hub

```powershell
# Push both tags
docker push yourusername/relief-app:latest
docker push yourusername/relief-app:v1.0.0
```

**Upload time:** ~2-5 minutes depending on connection speed

---

## Step 6: Deploy on Railway

### 6.1 Create a New Project

1. Go to [railway.app](https://railway.app) and log in
2. Click **"New Project"**
3. Select **"Empty Project"**

### 6.2 Add MySQL Service

1. Click **"+ New"** → **"Database"** → **"MySQL"**
2. Wait for the database to provision
3. Click on the MySQL service to view connection details

### 6.3 Add the Relief App Service

1. Click **"+ New"** → **"Docker Image"**
2. Enter your image: `yourusername/relief-app:latest`
3. Click **"Deploy"**

### 6.4 Configure Environment Variables

Click on the Relief service, go to **"Variables"** tab, and add:

```
APP_NAME=Relief
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:generate-a-new-key
APP_URL=https://your-railway-domain.up.railway.app

DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME=Relief

GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=${APP_URL}/auth/google/callback
```

**Generate APP_KEY:**
```powershell
# Run locally to generate a key
php artisan key:generate --show
```

### 6.5 Add Persistent Storage (Volume)

1. Click on the Relief service
2. Go to **"Settings"** → **"Volumes"**
3. Click **"+ New Volume"**
4. Set mount path: `/var/www/html/storage`
5. This persists uploaded files, logs, and cache across deployments

### 6.6 Configure Networking

1. Go to **"Settings"** → **"Networking"**
2. Click **"Generate Domain"** to get a public URL
3. Or add a custom domain if you have one

### 6.7 Deploy

Railway will automatically redeploy when you:
- Push a new image to Docker Hub with the same tag
- Update environment variables

---

## Updating the Application

When you make code changes:

```powershell
# 1. Build new image
docker build -t relief-app .

# 2. Tag with new version
docker tag relief-app yourusername/relief-app:latest
docker tag relief-app yourusername/relief-app:v1.1.0

# 3. Push to Docker Hub
docker push yourusername/relief-app:latest
docker push yourusername/relief-app:v1.1.0

# 4. Railway will auto-deploy if using :latest tag
#    Or manually trigger redeploy in Railway dashboard
```

---

## Quick Reference Commands

```powershell
# Build
docker build -t relief-app .

# Tag (replace 'yourusername')
docker tag relief-app yourusername/relief-app:latest

# Push
docker push yourusername/relief-app:latest

# View running containers
docker ps

# View logs
docker logs <container-id>

# Shell into container
docker exec -it <container-id> /bin/sh

# Remove old images to free space
docker image prune -a
```

---

## Troubleshooting

### Container exits immediately
Check logs: `docker logs <container-id>`

Common issues:
- Missing `APP_KEY` - Generate with `php artisan key:generate --show`
- Database connection failed - Verify DB environment variables

### Permission errors
The entrypoint script handles permissions, but if issues persist:
```sh
docker exec -it <container-id> chown -R www-data:www-data /var/www/html/storage
```

### Migration errors
```sh
# Shell into container and run manually
docker exec -it <container-id> php artisan migrate --force
```

### Clear caches after env changes
```sh
docker exec -it <container-id> php artisan config:clear
docker exec -it <container-id> php artisan cache:clear
```

---

## Railway-Specific Notes

1. **PORT variable**: Railway automatically sets `PORT=8080` which matches our Nginx config

2. **Health checks**: Railway monitors the `/` endpoint by default

3. **Logs**: View in Railway dashboard under the service's **"Logs"** tab

4. **Costs**: Check Railway's pricing - they bill by resource usage

5. **Sleep mode**: Free tier apps may sleep after inactivity; consider upgrading for production

---

*Last updated: February 2026*
