# cPanel Queue Management Setup untuk PPPKMI Conference

## 1. Struktur Cron Job di cPanel

### Setup Cron Job untuk Laravel Scheduler
Buat cron job di cPanel dengan pengaturan berikut:

```bash
# Jalankan setiap menit
* * * * * cd /home/pppkmior/public_html/conference && /usr/local/bin/ea-php82 artisan schedule:run >> storage/logs/scheduler.log 2>&1

# Alternatif tanpa logging (untuk menghindari file besar)
* * * * * cd /home/pppkmior/public_html/conference && /usr/local/bin/ea-php82 artisan schedule:run >> /dev/null 2>&1
```

### Setup Queue Worker sebagai Background Process
```bash
# Jalankan setiap 5 menit (akan restart otomatis jika berhenti)
*/5 * * * * cd /home/pppkmior/public_html/conference && /usr/local/bin/ea-php82 artisan queue:work --stop-when-empty --timeout=300 >> storage/logs/queue-worker.log 2>&1

# Daily cleanup jam 2 pagi
0 2 * * * cd /home/pppkmior/public_html/conference && /usr/local/bin/ea-php82 artisan queue:monitor --cleanup >> storage/logs/cleanup.log 2>&1
```

## 2. Laravel 11 Queue Scheduling Setup ✅

### AppServiceProvider telah diupdate dengan:
- Queue monitoring setiap 5 menit
- Cleanup expired payments setiap jam  
- Retry failed jobs setiap 30 menit
- Cleanup logs harian jam 2 pagi
- Email queue processing prioritas tinggi
- Regular queue processing
- Health monitoring untuk dashboard

## 3. File yang Dibuat

### Command untuk Monitoring
- `app/Console/Commands/QueueMonitorCommand.php` ✅
- `app/Console/Commands/CleanupExpiredPayments.php` ✅ (sudah ada)

### Filament Widgets & Resources  
- `app/Filament/Widgets/QueueMonitorWidget.php` ✅
- `app/Filament/Resources/QueueManagementResource.php` ✅
- `app/Filament/Resources/QueueManagementResource/Pages/ListQueueManagement.php` ✅

## 4. Environment Setup untuk Production

### Update .env untuk Production
```env
# Queue Configuration
QUEUE_CONNECTION=database
QUEUE_FAILED_DRIVER=database-uuids

# Email Queue Priority
MAIL_QUEUE_CONNECTION=database

# Logging
LOG_CHANNEL=daily
LOG_LEVEL=info
```

### Database Migrations Required
Pastikan migrations berikut sudah dijalankan:
```bash
php artisan migrate --path=database/migrations/0001_01_01_000002_create_jobs_table.php
```

## 5. Manual Commands untuk Troubleshooting

### Check Queue Status
```bash
php artisan queue:monitor --check
```

### Restart Failed Jobs
```bash
php artisan queue:monitor --restart
```

### Cleanup Old Jobs
```bash
php artisan queue:monitor --cleanup
```

### Process Queue Manually
```bash
php artisan queue:work --timeout=300 --stop-when-empty
```

## 6. cPanel Specific Instructions

### Path PHP yang Benar
Gunakan path PHP yang sesuai dengan versi hosting:
- PHP 8.2: `/opt/cpanel/ea-php82/root/usr/bin/php`
- PHP 8.1: `/opt/cpanel/ea-php81/root/usr/bin/php`
- PHP 8.0: `/opt/cpanel/ea-php80/root/usr/bin/php`

### Directory Structure
```
/home/username/
├── public_html/          # Root website
│   ├── artisan
│   ├── storage/
│   │   └── logs/         # Queue logs
│   └── database/
└── logs/                 # cPanel logs
```

### Cron Job Settings di cPanel
1. Login ke cPanel
2. Pilih "Cron Jobs"
3. Set "Common Settings" ke "Once Per Minute (* * * * *)"
4. Command: masukkan command Laravel scheduler
5. Email notifications: matikan untuk menghindari spam

## 7. Monitoring Dashboard

### Akses Admin Panel
- URL: `https://yourdomain.com/manage`
- Widget "Queue Monitor" akan menampilkan:
  - Pending Jobs
  - Failed Jobs  
  - Jobs Today
  - Queue Health
  - Email Queue
  - Last Check Time

### Resource "Queue Management"  
- Melihat semua jobs (pending & failed)
- Retry failed jobs
- Delete jobs
- Bulk operations
- Real-time refresh (30 detik)

## 8. Best Practices

### Monitoring & Alerting
- Check queue health via dashboard minimal 2x sehari
- Setup email alerts untuk failed jobs (>10 failures)
- Monitor storage space untuk log files
- Backup database termasuk jobs tables

### Performance Optimization
- Gunakan `--stop-when-empty` untuk menghindari infinite running
- Set timeout yang reasonable (300 detik)
- Pisahkan email queue dengan priority tinggi
- Cleanup logs secara berkala

### Security
- Protect admin panel dengan strong authentication
- Monitor access logs untuk queue management
- Backup job data sebelum cleanup
- Test di staging environment dulu

## 9. Troubleshooting Common Issues

### Queue Worker Tidak Jalan
1. Check cron job di cPanel running
2. Verify PHP path correct
3. Check file permissions (755 untuk artisan)
4. Review error logs di storage/logs/

### Failed Jobs Terus Bertambah  
1. Check email configuration
2. Verify database connection
3. Review failed job payload untuk error
4. Check memory limits

### Dashboard Tidak Update
1. Verify widget registered di ManagePanelProvider  
2. Check cache configuration
3. Clear application cache
4. Review browser console untuk errors

### Email Tidak Terkirim
1. Check queue:work processing emails
2. Verify SMTP settings
3. Check email queue specifically
4. Test dengan php artisan email:test

## 10. Production Deployment Checklist

- [ ] Update .env dengan production settings
- [ ] Setup cron jobs di cPanel
- [ ] Test queue worker manual
- [ ] Verify migrations applied
- [ ] Test email sending
- [ ] Setup monitoring dashboard
- [ ] Configure log rotation
- [ ] Backup database
- [ ] Test failover scenarios
- [ ] Document recovery procedures
