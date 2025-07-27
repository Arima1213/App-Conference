# 🎯 PPPKMI Conference - cPanel Deployment Instructions

## 📋 READY-TO-USE CRON JOB COMMANDS

### ✅ COMMAND UTAMA (WAJIB) - Laravel Scheduler
**Frequency:** `* * * * *` (Every minute)
**Command:**
```bash
cd /home/pppkmior/public_html/conference && /usr/local/bin/ea-php82 artisan schedule:run >> storage/logs/scheduler.log 2>&1
```

### ✅ BACKUP QUEUE WORKER (OPSIONAL)
**Frequency:** `*/5 * * * *` (Every 5 minutes)
**Command:**
```bash
cd /home/pppkmior/public_html/conference && /usr/local/bin/ea-php82 artisan queue:work --stop-when-empty --timeout=300 >> storage/logs/queue-worker.log 2>&1
```

### ✅ DAILY CLEANUP (REKOMENDASI)
**Frequency:** `0 2 * * *` (Daily at 2 AM)
**Command:**
```bash
cd /home/pppkmior/public_html/conference && /usr/local/bin/ea-php82 artisan queue:monitor --cleanup >> storage/logs/cleanup.log 2>&1
```

---

## 🔧 LANGKAH DEPLOYMENT DI cPANEL

### 1. Upload Files
- Upload semua file project ke `/home/pppkmior/public_html/conference/`
- Pastikan struktur folder sesuai:
  ```
  /home/pppkmior/public_html/conference/
  ├── app/
  ├── artisan
  ├── storage/
  ├── database/
  ├── .env
  └── ...
  ```

### 2. Setup Database & Environment
```bash
# Di terminal cPanel atau SSH (jika tersedia)
cd /home/pppkmior/public_html/conference
/usr/local/bin/ea-php82 artisan migrate --force
/usr/local/bin/ea-php82 artisan config:cache
```

### 3. Update File .env untuk Production
```env
APP_ENV=production
APP_DEBUG=false
QUEUE_CONNECTION=database
QUEUE_FAILED_DRIVER=database-uuids
LOG_CHANNEL=daily
LOG_LEVEL=info

# Email settings untuk production
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-server
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
```

### 4. Setup Cron Jobs di cPanel
1. **Login ke cPanel** → Pilih **"Cron Jobs"**
2. **Add New Cron Job:**
   - **Common Settings:** Custom
   - **Minute:** `*`
   - **Hour:** `*`
   - **Day:** `*`
   - **Month:** `*`
   - **Weekday:** `*`
   - **Command:** Copy command dari atas

### 5. Verifikasi PHP Version
1. Buka **MultiPHP Manager** di cPanel
2. Cek PHP version untuk domain conference
3. Update command jika perlu:
   - PHP 8.1: `/usr/local/bin/ea-php81`
   - PHP 8.2: `/usr/local/bin/ea-php82`
   - PHP 8.3: `/usr/local/bin/ea-php83`

---

## 🧪 TESTING COMMANDS

### Test Manual di Terminal (jika SSH tersedia):
```bash
# Test Laravel berjalan
cd /home/pppkmior/public_html/conference
/usr/local/bin/ea-php82 artisan --version

# Test environment
/usr/local/bin/ea-php82 artisan cpanel:setup-queue --check-env

# Test queue monitoring
/usr/local/bin/ea-php82 artisan queue:monitor --check

# Test scheduler
/usr/local/bin/ea-php82 artisan schedule:list
```

### Test via File Manager:
Buat file `test-cron.php` di root conference:
```php
<?php
// test-cron.php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$status = $kernel->call('queue:monitor', ['--check' => true]);
echo "Queue monitoring test completed. Status: $status\n";
```

Akses via browser: `yourdomain.com/conference/test-cron.php`

---

## 📊 MONITORING & TROUBLESHOOTING

### Akses Dashboard Admin:
- URL: `https://yourdomain.com/conference/manage`
- Login dengan admin credentials
- Check widget "Queue Monitor" untuk metrics real-time

### Log Files Location:
```
/home/pppkmior/public_html/conference/storage/logs/
├── scheduler.log      # Laravel scheduler logs
├── queue-worker.log   # Queue worker logs  
├── cleanup.log        # Daily cleanup logs
├── laravel.log        # Application logs
└── queue-monitor.log  # Queue monitoring logs
```

### Common Issues & Solutions:

#### ❌ Cron Job Tidak Jalan
```bash
# Check PHP path
which ea-php82
# Result should be: /usr/local/bin/ea-php82

# Test command manual
cd /home/pppkmior/public_html/conference && /usr/local/bin/ea-php82 artisan schedule:run
```

#### ❌ Queue Jobs Tidak Diproses
```bash
# Check queue table
/usr/local/bin/ea-php82 artisan queue:monitor --check

# Manual queue processing
/usr/local/bin/ea-php82 artisan queue:work --stop-when-empty
```

#### ❌ Email Tidak Terkirim
```bash
# Test email
/usr/local/bin/ea-php82 artisan email:test your-email@domain.com

# Check SMTP settings di .env
```

---

## ⚡ QUICK DEPLOYMENT CHECKLIST

- [ ] **Files uploaded** to `/home/pppkmior/public_html/conference/`
- [ ] **Database migrated** with `php artisan migrate --force`
- [ ] **.env updated** untuk production
- [ ] **Cron job created** dengan command scheduler
- [ ] **PHP version verified** di MultiPHP Manager
- [ ] **Permissions set** untuk storage/ (755)
- [ ] **Dashboard accessible** via `/manage`
- [ ] **Queue monitoring** widget working
- [ ] **Email system tested**
- [ ] **Logs monitored** for errors

---

## 🎉 READY TO GO!

Setelah semua langkah di atas selesai:

1. ✅ **Queue system** akan otomatis manage email delivery
2. ✅ **Scheduler** akan jalankan maintenance tasks
3. ✅ **Dashboard** akan show real-time queue metrics
4. ✅ **Email verification** akan berjalan otomatis
5. ✅ **Payment system** akan process notifications

**Your PPPKMI Conference system is production ready! 🚀**

---

## 📞 Support Commands

```bash
# Generate fresh cron commands
/usr/local/bin/ea-php82 artisan cpanel:setup-queue --generate-cron

# Full environment check  
/usr/local/bin/ea-php82 artisan cpanel:setup-queue --check-env

# Queue health monitoring
/usr/local/bin/ea-php82 artisan queue:monitor --check

# Create test jobs
/usr/local/bin/ea-php82 artisan test:queue-job --count=3
```
