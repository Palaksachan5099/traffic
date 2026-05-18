@echo off
REM Traffic App - Windows Server Deployment Script
REM Usage: deploy.bat

setlocal enabledelayedexpansion

echo.
echo ============================================
echo Traffic App - Deployment Script
echo ============================================
echo.

REM Check if .env file exists
if not exist .env (
    echo Error: .env file not found!
    echo Please run: copy .env.example .env
    echo Then configure your .env file and run this script again.
    exit /b 1
)

echo Step 1: Installing PHP dependencies...
call composer install --no-dev --optimize-autoloader
if errorlevel 1 goto error
echo [OK] PHP dependencies installed
echo.

echo Step 2: Installing frontend dependencies...
call npm install
if errorlevel 1 goto error
echo [OK] Frontend dependencies installed
echo.

echo Step 3: Building frontend assets...
call npm run build
if errorlevel 1 goto error
echo [OK] Frontend assets built
echo.

echo Step 4: Generating application key...
call php artisan key:generate
if errorlevel 1 goto error
echo [OK] Application key generated
echo.

echo Step 5: Creating storage symlink...
call php artisan storage:link
if errorlevel 1 goto error
echo [OK] Storage symlink created
echo.

echo Step 6: Clearing caches...
call php artisan cache:clear
call php artisan config:clear
call php artisan route:clear
echo [OK] Caches cleared
echo.

echo Step 7: Running database migrations...
call php artisan migrate --force
if errorlevel 1 goto error
echo [OK] Migrations completed
echo.

echo.
echo ============================================
echo Deployment completed successfully!
echo ============================================
echo.
echo Next steps:
echo 1. Verify .env configuration
echo 2. Set up Nginx/Apache vhost
echo 3. Configure SSL certificate
echo 4. Configure MongoDB connection
echo 5. Set up email settings in .env
echo.

goto end

:error
echo.
echo An error occurred during deployment!
echo Check the error message above and fix the issue.
exit /b 1

:end
endlocal
