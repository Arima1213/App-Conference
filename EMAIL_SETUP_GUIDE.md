# Email System Setup and Troubleshooting Guide

## ✅ Current Email Configuration

### Environment Settings (.env)
```
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@pppkmi-conference.com"
MAIL_FROM_NAME="Conference"
QUEUE_CONNECTION=database
```

## 📧 Email Types Implemented

### 1. User Email Verification
- **Trigger**: When user registers (automatic Laravel feature)
- **Template**: Laravel default verification email
- **Purpose**: Verify email address before using system

### 2. Participant Registration Email
- **Trigger**: After successful participant registration
- **Template**: `emails/participant/registration.blade.php`
- **Class**: `ParticipantRegistrationMail`
- **Purpose**: Welcome email with registration details and payment link

### 3. Payment Success Email
- **Trigger**: After successful payment
- **Template**: `emails/payment/success.blade.php`
- **Class**: `PaymentSuccessMail`
- **Purpose**: Payment confirmation

## 🔧 Testing Email System

### Test Basic Email Configuration
```bash
php artisan email:test your-email@example.com
```

### Test Registration Email
```bash
# Test with latest participant
php artisan email:test-registration

# Test with specific participant
php artisan email:test-registration 1 your-email@example.com
```

### Check Queue Jobs
```bash
# Start queue worker
php artisan queue:work --tries=3

# Check failed jobs
php artisan queue:failed
```

## 🐛 Troubleshooting Common Issues

### 1. Emails Not Sending

**Check Email Configuration:**
```bash
php artisan config:clear
php artisan email:test test@example.com
```

**Verify SMTP Settings:**
- **Host**: 127.0.0.1 (for MailHog)
- **Port**: 1025 (for MailHog)
- **No Authentication**: username/password should be null

### 2. Queue Issues

**Ensure Queue Table Exists:**
```bash
php artisan migrate
```

**Start Queue Worker:**
```bash
php artisan queue:work --tries=3
```

**Check Queue Status:**
```bash
php artisan queue:monitor
```

### 3. Registration Email Not Triggered

**Check CreateParticipant Logic:**
- Verify `afterCreate()` method is called
- Check for exceptions in logs
- Ensure participant relations are loaded

**Check Logs:**
```bash
tail -f storage/logs/laravel.log
```

## 📋 Email Flow During Registration

### Step 1: User Creates Account
1. User registers through Filament
2. Laravel automatically sends email verification
3. User must verify email to access system

### Step 2: Participant Registration
1. User fills participant registration form
2. `CreateParticipant::afterCreate()` is called
3. Payment record is created
4. Registration email is sent via queue
5. Success notification is shown

### Step 3: Email Verification Routes
- **Notice**: `/email/verify` - Shows verification reminder
- **Verify**: `/email/verify/{id}/{hash}` - Actual verification link
- **Resend**: `/email/verification-notification` - Resend verification

## 🔍 Debugging Commands

### Check Email Queue
```bash
# See pending jobs
php artisan queue:work --once

# Process all jobs
php artisan queue:work

# Clear failed jobs
php artisan queue:flush
```

### Check Email Configuration
```bash
php artisan tinker
>>> config('mail')
>>> config('mail.mailers.smtp')
```

### Manual Email Test
```bash
php artisan tinker
>>> use App\Mail\ParticipantRegistrationMail;
>>> use App\Models\Participant;
>>> $participant = Participant::with(['user', 'conference'])->first();
>>> Mail::to('test@example.com')->send(new ParticipantRegistrationMail($participant));
```

## 📦 MailHog Setup (Development)

If using MailHog for local development:

1. **Install MailHog**: Download from https://github.com/mailhog/MailHog
2. **Run MailHog**: `./MailHog` (default port 8025)
3. **Web Interface**: http://localhost:8025
4. **SMTP**: localhost:1025

## 🚀 Production Email Setup

For production, update .env with real SMTP settings:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@your-domain.com"
MAIL_FROM_NAME="PPPKMI Conference"
```

## ✅ Verification Checklist

- [ ] SMTP configuration in .env is correct
- [ ] Queue worker is running (`php artisan queue:work`)
- [ ] Database migrations are up to date
- [ ] User model implements `MustVerifyEmail`
- [ ] Email verification routes are registered
- [ ] Participant registration triggers email sending
- [ ] Logs show successful email sending
- [ ] MailHog/email client receives emails

## 📞 Support

If emails are still not working:

1. Check Laravel logs: `storage/logs/laravel.log`
2. Verify queue jobs: `php artisan queue:failed`
3. Test basic SMTP: `php artisan email:test`
4. Check MailHog interface: http://localhost:8025

The system is now configured to send:
- ✅ Email verification upon user registration
- ✅ Welcome email upon participant registration
- ✅ Payment confirmation emails
- ✅ Proper queue processing for reliable delivery
