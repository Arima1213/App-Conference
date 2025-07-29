# 📧 EMAIL PRODUCTION CONFIGURATION - PPPKMI Conference

## ✅ KONFIGURASI EMAIL PRODUCTION

### 🔧 **UPDATE .ENV UNTUK PRODUCTION**

Ganti konfigurasi berikut di file `.env`:

```env
# ===========================================
# EMAIL CONFIGURATION FOR PRODUCTION
# ===========================================

MAIL_MAILER=smtp
MAIL_HOST=conference.pppkmi.org
MAIL_PORT=465
MAIL_USERNAME=test@conference.pppkmi.org
MAIL_PASSWORD=your_actual_email_password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="test@conference.pppkmi.org"
MAIL_FROM_NAME="PPPKMI Conference"

# Email queue untuk production reliability
MAIL_QUEUE_CONNECTION=database

# Alternative settings jika port 465 bermasalah
# MAIL_PORT=587
# MAIL_ENCRYPTION=tls
```

### 📋 **DETAIL KONFIGURASI**

#### Server Settings:
- **SMTP Host:** `conference.pppkmi.org`
- **Port:** `465` (SSL) atau `587` (TLS)
- **Encryption:** `ssl` untuk port 465, `tls` untuk port 587
- **Authentication:** Required (username & password)

#### Email Account:
- **Username:** `test@conference.pppkmi.org`
- **Password:** Password dari cPanel email account
- **From Address:** `test@conference.pppkmi.org`
- **From Name:** `PPPKMI Conference`

---

## 🧪 **TESTING EMAIL CONFIGURATION**

### 1. **Update Password**
Ganti `your_actual_email_password` dengan password sebenarnya dari cPanel.

### 2. **Test Email Configuration**
```bash
# Di server cPanel atau SSH
cd /home/pppkmior/public_html/conference
/usr/local/bin/ea-php82 artisan email:test your-email@domain.com
```

### 3. **Test Registration Email**
```bash
# Test email registration system
/usr/local/bin/ea-php82 artisan email:test-registration
```

### 4. **Monitor Queue Jobs**
```bash
# Check email queue
/usr/local/bin/ea-php82 artisan queue:monitor --check
```

---

## 🔧 **TROUBLESHOOTING EMAIL ISSUES**

### Common Problems & Solutions:

#### 1. **"Connection timed out" Error**
```env
# Try alternative port dan encryption
MAIL_PORT=587
MAIL_ENCRYPTION=tls

# Atau tanpa encryption (tidak direkomendasikan)
MAIL_PORT=25
MAIL_ENCRYPTION=null
```

#### 2. **"Authentication failed" Error**
- Pastikan username dan password benar
- Cek di cPanel Email Accounts untuk verify credentials
- Pastikan email account tidak suspended

#### 3. **"SSL connection failed" Error**
```env
# Switch dari SSL ke TLS
MAIL_PORT=587
MAIL_ENCRYPTION=tls

# Atau coba tanpa SSL verification
MAIL_ENCRYPTION=null
```

#### 4. **Email masuk ke SPAM**
- Setup SPF record di DNS: `v=spf1 include:conference.pppkmi.org ~all`
- Setup DKIM di cPanel jika tersedia
- Setup DMARC policy
- Gunakan proper From name dan address

---

## 📧 **EMAIL TEMPLATES OPTIMIZATION**

### Update Email Templates untuk Production:

#### 1. **Registration Email Template**
File: `resources/views/emails/registration-confirmation.blade.php`

```html
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome to PPPKMI Conference</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #2c3e50;">PPPKMI Conference</h1>
            <p style="font-size: 18px; color: #7f8c8d;">Registration Confirmation</p>
        </div>
        
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <h2>Hello {{ $participant->name }},</h2>
            <p>Thank you for registering for the PPPKMI Conference. Your registration has been successfully submitted.</p>
            
            <h3>Registration Details:</h3>
            <ul>
                <li><strong>Name:</strong> {{ $participant->name }}</li>
                <li><strong>Email:</strong> {{ $participant->email }}</li>
                <li><strong>Institution:</strong> {{ $participant->educational_institution->name ?? 'N/A' }}</li>
                <li><strong>Registration Date:</strong> {{ $participant->created_at->format('d M Y, H:i') }}</li>
            </ul>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/participant') }}" 
               style="background: #3498db; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">
                Access Dashboard
            </a>
        </div>
        
        <div style="border-top: 1px solid #dee2e6; padding-top: 20px; color: #6c757d; font-size: 14px;">
            <p>If you have any questions, please contact us at:</p>
            <p>Email: test@conference.pppkmi.org<br>
               Website: https://conference.pppkmi.org</p>
        </div>
    </div>
</body>
</html>
```

#### 2. **Payment Confirmation Email**
File: `resources/views/emails/payment-success.blade.php`

```html
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Confirmation - PPPKMI Conference</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #27ae60;">Payment Successful!</h1>
            <p style="font-size: 18px; color: #7f8c8d;">PPPKMI Conference</p>
        </div>
        
        <div style="background: #d4edda; border: 1px solid #c3e6cb; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <h2>Dear {{ $participant->name }},</h2>
            <p>Your payment has been successfully processed. You are now fully registered for the PPPKMI Conference.</p>
            
            <h3>Payment Details:</h3>
            <ul>
                <li><strong>Amount:</strong> Rp {{ number_format($payment->amount, 0, ',', '.') }}</li>
                <li><strong>Transaction ID:</strong> {{ $payment->order_id }}</li>
                <li><strong>Payment Method:</strong> {{ $payment->payment_type }}</li>
                <li><strong>Date:</strong> {{ $payment->created_at->format('d M Y, H:i') }}</li>
            </ul>
        </div>
        
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <h3>Next Steps:</h3>
            <ol>
                <li>Access your participant dashboard</li>
                <li>Download your seminar kit information</li>
                <li>Join the conference WhatsApp group (link will be provided)</li>
                <li>Prepare for the conference date</li>
            </ol>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/participant') }}" 
               style="background: #27ae60; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">
                Access Dashboard
            </a>
        </div>
        
        <div style="border-top: 1px solid #dee2e6; padding-top: 20px; color: #6c757d; font-size: 14px;">
            <p>For support, contact us at test@conference.pppkmi.org</p>
        </div>
    </div>
</body>
</html>
```

---

## 🔒 **SECURITY CONSIDERATIONS**

### 1. **Email Account Security**
- Gunakan strong password untuk email account
- Enable 2FA jika tersedia di cPanel
- Monitor email usage regularly

### 2. **SMTP Security**
- Selalu gunakan SSL/TLS encryption
- Jangan expose email credentials di code
- Store password di environment variables

### 3. **Email Content Security**
- Validate semua user input dalam email
- Escape HTML content untuk prevent XSS
- Use signed URLs untuk sensitive links

---

## 📊 **MONITORING EMAIL DELIVERY**

### 1. **Email Logs**
Monitor di lokasi berikut:
- `storage/logs/laravel.log` - Application logs
- cPanel Email logs - Server-side delivery logs
- Midtrans notifications logs

### 2. **Queue Monitoring**
```bash
# Check email queue status
/usr/local/bin/ea-php82 artisan queue:monitor --check

# Monitor specific email jobs
/usr/local/bin/ea-php82 artisan queue:work --queue=emails --verbose
```

### 3. **Delivery Rate Monitoring**
Track metrics via dashboard:
- Total emails sent
- Delivery success rate
- Failed deliveries
- Queue processing time

---

## ✅ **PRODUCTION DEPLOYMENT CHECKLIST**

### Email Configuration:
- [ ] **SMTP settings** configured dengan cPanel data
- [ ] **Email password** updated di .env
- [ ] **From address** menggunakan domain conference.pppkmi.org
- [ ] **Queue system** enabled untuk reliability

### Testing:
- [ ] **Test email** sent successfully
- [ ] **Registration email** working
- [ ] **Payment confirmation** email working
- [ ] **Email templates** optimized untuk production

### Security:
- [ ] **SSL/TLS** encryption enabled
- [ ] **Email credentials** secure
- [ ] **SPF/DKIM** configured (jika available)
- [ ] **Email logs** monitoring setup

### Performance:
- [ ] **Email queue** processing efficiently
- [ ] **Delivery time** acceptable
- [ ] **Server resources** tidak overloaded
- [ ] **Bounce handling** implemented

---

## 🚀 **FINAL PRODUCTION SETTINGS**

Copy konfigurasi berikut ke `.env` production:

```env
# ===========================================
# PRODUCTION EMAIL CONFIGURATION
# ===========================================

MAIL_MAILER=smtp
MAIL_HOST=conference.pppkmi.org
MAIL_PORT=465
MAIL_USERNAME=test@conference.pppkmi.org
MAIL_PASSWORD=YOUR_ACTUAL_PASSWORD_HERE
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="test@conference.pppkmi.org"
MAIL_FROM_NAME="PPPKMI Conference"
MAIL_QUEUE_CONNECTION=database

# Alternative jika SSL bermasalah:
# MAIL_PORT=587
# MAIL_ENCRYPTION=tls
```

**Email system siap untuk production! 📧✅**
