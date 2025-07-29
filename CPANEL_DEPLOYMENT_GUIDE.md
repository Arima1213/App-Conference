# 🚀 PPPKMI Conference - Production Deployment Guide for cPanel

## 📋 Overview
Panduan lengkap untuk deploy aplikasi PPPKMI Conference ke cPanel hosting dengan semua fitur yang telah dikonfigurasi:
- ✅ Anti-duplicate payment system
- ✅ Email verification dengan queue
- ✅ Queue monitoring dashboard
- ✅ Production security headers
- ✅ Email SMTP production

## 🛠️ Pre-Deployment Checklist

### 1. File Yang Sudah Disiapkan
```
✅ public/.htaccess - Security & performance optimization
✅ app/Console/Commands/QueueMonitorCommand.php - Queue management
✅ app/Filament/Widgets/QueueMonitorWidget.php - Dashboard monitoring
✅ app/Filament/Resources/QueueManagementResource.php - Queue admin
✅ app/Console/Commands/ValidateProductionSetup.php - Production validation
✅ public/403.html, 404.html, 500.html - Custom error pages
✅ public/robots.txt - SEO optimization
✅ .env - Production configuration
```

### 2. Database Migration
```bash
# Di cPanel terminal atau via SSH
cd /home/pppkmior/public_html/conference
/usr/local/bin/ea-php82 artisan migrate --force
/usr/local/bin/ea-php82 artisan db:seed --force
```

### 3. Storage & Permissions
```bash
# Setup storage symlink
/usr/local/bin/ea-php82 artisan storage:link

# Set proper permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

## 🔧 cPanel Configuration

### 1. Cron Jobs Setup (WAJIB)
Masuk ke cPanel → Cron Jobs dan tambahkan:

**Schedule: Every minute (* * * * *) - MAIN SCHEDULER**
```bash
cd /home/pppkmior/public_html/conference && /usr/local/bin/ea-php82 artisan schedule:run >> storage/logs/scheduler.log 2>&1
```

**Schedule: Every 5 minutes (*/5 * * * *) - BACKUP QUEUE PROCESSOR**
```bash
cd /home/pppkmior/public_html/conference && /usr/local/bin/ea-php82 artisan queue:work --stop-when-empty --timeout=300 --tries=3 >> storage/logs/queue-backup.log 2>&1
```

⚠️ **IMPORTANT NOTES:**
- NEVER use `artisan storage:link` in cron jobs (requires exec() function)
- Use `artisan storage:link-cpanel` instead - safe for shared hosting
- Main scheduler handles all scheduled tasks automatically
- Backup queue processor ensures queue continues if scheduler fails

### 2. Environment Variables
File `.env` sudah dikonfigurasi dengan:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://conference.pppkmi.org

# Email Configuration
MAIL_MAILER=smtp
MAIL_HOST=conference.pppkmi.org
MAIL_PORT=465
MAIL_USERNAME=test@conference.pppkmi.org
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=noreply@conference.pppkmi.org
MAIL_FROM_NAME="PPPKMI Conference"

# Queue Configuration  
QUEUE_CONNECTION=database
QUEUE_FAILED_DRIVER=database-uuids

# Session & Cache
SESSION_DRIVER=database
CACHE_DRIVER=file

# Logging
LOG_CHANNEL=daily
LOG_LEVEL=info
LOG_DAYS=14
```

### 3. SSL Certificate
Pastikan SSL sudah aktif di cPanel:
- cPanel → SSL/TLS → Let's Encrypt (recommended)
- Atau upload certificate manual

## 🚀 Deployment Steps

### Method 1: Automated Deployment (Recommended)
```bash
cd /home/pppkmior/public_html/conference
chmod +x deploy-cpanel.sh
./deploy-cpanel.sh
```

### Method 2: Manual Step-by-Step

### Step 1: Upload Files
1. Compress seluruh project (kecuali node_modules)
2. Upload ke `/home/pppkmior/public_html/conference/`
3. Extract files

### Step 2: Composer Install
```bash
cd /home/pppkmior/public_html/conference
/usr/local/bin/ea-php82 /usr/local/bin/composer install --no-dev --optimize-autoloader
```

### Step 3: Storage Link (cPanel Safe)
```bash
# NEVER use: artisan storage:link (will fail with exec() error)
# Use this instead:
/usr/local/bin/ea-php82 artisan storage:link-cpanel
```

### Step 4: Laravel Optimization
```bash
/usr/local/bin/ea-php82 artisan config:cache
/usr/local/bin/ea-php82 artisan route:cache
/usr/local/bin/ea-php82 artisan view:cache
/usr/local/bin/ea-php82 artisan event:cache
```

### Step 4: Database Setup
```bash
/usr/local/bin/ea-php82 artisan migrate --force
/usr/local/bin/ea-php82 artisan db:seed --force
```

### Step 5: Storage & Permissions
```bash
/usr/local/bin/ea-php82 artisan storage:link
chmod -R 755 storage/ bootstrap/cache/
```

### Step 6: Queue Tables
```bash
/usr/local/bin/ea-php82 artisan queue:table
/usr/local/bin/ea-php82 artisan queue:failed-table
/usr/local/bin/ea-php82 artisan migrate --force
```

## 🔍 Production Validation

### Gunakan Command Validator
```bash
# Test semua konfigurasi
/usr/local/bin/ea-php82 artisan production:validate

# Test dengan email
/usr/local/bin/ea-php82 artisan production:validate --email=your@email.com
```

### Manual Testing Checklist
- [ ] Website dapat diakses via HTTPS
- [ ] Login admin berfungsi
- [ ] Dashboard widgets menampilkan data
- [ ] Form registrasi bekerja
- [ ] Email verification terkirim
- [ ] Payment gateway berfungsi
- [ ] Queue processing aktif

## 📊 Monitoring & Maintenance

### 1. Queue Monitoring
Akses dashboard admin:
- Widget Queue Monitor menampilkan status real-time
- Resource Queue Management untuk detail

### 2. Command Monitoring
```bash
# Cek status queue
/usr/local/bin/ea-php82 artisan queue:monitor --check

# Restart failed jobs
/usr/local/bin/ea-php82 artisan queue:monitor --restart

# Cleanup old jobs
/usr/local/bin/ea-php82 artisan queue:monitor --cleanup
```

### 3. Log Files
Monitor log files di:
```
storage/logs/laravel.log - Application logs
storage/logs/scheduler.log - Cron jobs
storage/logs/queue.log - Queue processing
```

### 4. Performance Monitoring
- Gunakan cPanel metrics untuk CPU/Memory usage
- Monitor database performance
- Check email delivery logs

## 🔒 Security Features Aktif

### Headers Security (.htaccess)
```
✅ HTTPS Redirect
✅ X-Frame-Options: DENY
✅ X-Content-Type-Options: nosniff
✅ X-XSS-Protection: 1; mode=block
✅ Referrer-Policy: strict-origin-when-cross-origin
✅ Content-Security-Policy
```

### File Protection
```
✅ .env file protection
✅ Composer files protection
✅ Config files protection
✅ Log files protection
```

### Performance Optimization
```
✅ GZIP Compression
✅ Browser Caching (1 year for assets)
✅ ETags enabled
✅ Keep-Alive connections
```

## 🚨 Troubleshooting

### Common Issues

**1. Queue not processing**
```bash
# Check cron jobs are running
tail -f storage/logs/scheduler.log

# Manual queue work
/usr/local/bin/ea-php82 artisan queue:work --once
```

**2. Email not sending**
```bash
# Test email configuration
/usr/local/bin/ea-php82 artisan production:validate --email=test@example.com

# Check email logs
tail -f storage/logs/laravel.log | grep mail
```

**3. Payment issues**
- Check Midtrans configuration in .env
- Verify webhook URL: https://conference.pppkmi.org/midtrans/webhook
- Test with sandbox first

**4. Database connection**
```bash
# Test database
/usr/local/bin/ea-php82 artisan tinker
DB::connection()->getPdo();
```

### Emergency Commands
```bash
# Clear all caches
/usr/local/bin/ea-php82 artisan cache:clear
/usr/local/bin/ea-php82 artisan config:clear
/usr/local/bin/ea-php82 artisan route:clear
/usr/local/bin/ea-php82 artisan view:clear

# Restart queues
/usr/local/bin/ea-php82 artisan queue:restart

# Fix permissions
chmod -R 755 storage/ bootstrap/cache/
```

## 📧 Email Configuration Notes

### SMTP Settings Verified
```
Host: conference.pppkmi.org
Port: 465 (SSL)
Security: SSL/TLS
Username: test@conference.pppkmi.org
```

### Email Templates Location
```
resources/views/emails/ - Custom email templates
app/Mail/PaymentSuccessMail.php - Payment notifications
```

## 🎯 Post-Deployment Verification

### 1. Functional Testing
- [ ] User registration + email verification
- [ ] Admin login dan dashboard
- [ ] Payment flow (sandbox)
- [ ] Queue processing
- [ ] Error pages (403, 404, 500)

### 2. Performance Testing
- [ ] Page load speed < 3 seconds
- [ ] Mobile responsiveness
- [ ] HTTPS redirect working
- [ ] Assets loading properly

### 3. Security Testing
- [ ] Security headers present
- [ ] File access restrictions
- [ ] SQL injection protection
- [ ] XSS protection

## 📞 Support Information

### Technical Contacts
- Development: [Your contact]
- Hosting: cPanel support
- Domain: PPPKMI IT team

### Documentation
- Laravel: https://laravel.com/docs/11.x
- Filament: https://filamentphp.com/docs/3.x
- Midtrans: https://docs.midtrans.com

---

**Status: ✅ Production Ready**
**Last Updated: 2024-12-19**
**Deployment Target: conference.pppkmi.org**
