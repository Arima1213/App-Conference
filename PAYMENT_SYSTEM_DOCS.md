# Improved Payment System Documentation

## Overview

The payment system has been enhanced to prevent duplicate order_id issues with Midtrans and provide better handling of existing transactions.

## Key Improvements

### 1. Prevention of Duplicate Order IDs
- **Problem**: Midtrans doesn't allow reusing the same order_id for new transactions
- **Solution**: Check for existing pending payments before creating new ones
- **Implementation**: `PaymentService::getOrCreatePayment()` method

### 2. Token Management
- **Problem**: Snap tokens expire after 24 hours
- **Solution**: Store and validate token expiry, reuse valid tokens
- **Database Fields Added**:
  - `snap_token` (TEXT) - Stores the Midtrans Snap token
  - `snap_token_created_at` (TIMESTAMP) - Token creation timestamp

### 3. Enhanced Status Management
- **New Status**: `challenge` - For payments under bank verification
- **New Status**: `expired` - For very old pending payments
- **Improved Status Tracking**: Better logging and notification system

## Database Changes

### Migration: `add_snap_token_to_payments_table`
```sql
ALTER TABLE payments ADD COLUMN snap_token TEXT NULL;
ALTER TABLE payments ADD COLUMN snap_token_created_at TIMESTAMP NULL;
ALTER TABLE payments MODIFY payment_status ENUM('pending', 'paid', 'failed', 'challenge', 'expired');
```

## New Classes

### PaymentService (`app/Services/PaymentService.php`)
Main service class handling payment logic:

#### Key Methods:
- `getOrCreatePayment(int $participantId): Payment` - Gets existing or creates new payment
- `getSnapToken(Payment $payment): string` - Gets valid token (reuses or generates new)
- `processCallback(array $callbackData): ?Payment` - Processes Midtrans callbacks

### Payment Model Enhancements
New methods added to `app/Models/Payment.php`:

- `hasValidSnapToken(): bool` - Checks if token is still valid (< 24 hours)
- `canBePaid(): bool` - Checks if payment can be processed
- `isPaid(): bool` - Checks if payment is completed
- `generateSnapToken(): string` - Creates new Midtrans token

## Controller Updates

### PaymentController (`app/Http/Controllers/paymentController.php`)
- Refactored to use `PaymentService`
- Better error handling and logging
- Improved callback processing
- Enhanced notification system

### PaymentPage (`app/Filament/Participant/Pages/PaymentPage.php`)
- Uses `PaymentService` for token generation
- Better error handling
- Consistent logging

## Flow Improvements

### Payment Creation Flow
1. **Check Existing**: Look for pending/failed payments for participant
2. **Reuse or Create**: Use existing payment or create new one
3. **Token Validation**: Check if existing token is valid
4. **Generate if Needed**: Create new token only when necessary

### Token Management Flow
1. **Valid Token Check**: Verify token age (< 24 hours)
2. **Reuse Valid Token**: Return existing token if valid
3. **Generate New Token**: Create new token if expired/missing
4. **Update Database**: Store new token with timestamp

### Callback Processing Flow
1. **Enhanced Validation**: Better signature verification
2. **Status Mapping**: Improved status handling
3. **Participant Updates**: Automatic status updates
4. **Notification System**: Comprehensive user notifications
5. **Error Logging**: Detailed error tracking

## Security Enhancements

### Signature Verification
- Enhanced signature validation with detailed logging
- Protection against callback manipulation
- Proper error responses for security violations

### Data Validation
- Required field validation in callbacks
- Payment existence verification
- Status transition validation

## Monitoring & Maintenance

### Cleanup Command
`php artisan payments:cleanup-expired`
- Removes expired tokens (> 24 hours)
- Marks very old payments as expired (> 7 days)
- Provides cleanup summary

### Logging
- Payment creation events
- Token generation/reuse
- Callback processing
- Error conditions
- Status changes

## Usage Examples

### For Participants
1. **First Payment Attempt**: System creates new payment with fresh token
2. **Retry Payment**: System reuses existing payment and valid token
3. **After Token Expiry**: System generates new token for same payment
4. **Payment Success**: System updates status and sends notifications

### For Administrators
1. **Monitor Payments**: Enhanced Filament interface shows token status
2. **Track Issues**: Detailed logging helps debug problems
3. **Cleanup System**: Run cleanup command to maintain system health

## Benefits

1. **No Duplicate Order IDs**: Prevents Midtrans errors
2. **Better User Experience**: Smoother payment flow
3. **Reduced API Calls**: Token reuse saves Midtrans API calls
4. **Enhanced Monitoring**: Better visibility into payment status
5. **Improved Reliability**: Better error handling and recovery

## Configuration

### Midtrans Settings
Ensure these are set in your `.env`:
```env
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false
```

### Scheduled Tasks (Optional)
Add to `app/Console/Kernel.php`:
```php
$schedule->command('payments:cleanup-expired')->daily();
```

## Testing

Run the test suite to verify functionality:
```bash
php artisan test --filter PaymentServiceTest
```

## Troubleshooting

### Common Issues

1. **"Order ID already exists"**: 
   - Check for existing pending payments
   - Verify PaymentService is being used correctly

2. **"Invalid token"**: 
   - Check token expiry (24 hours)
   - Verify snap_token_created_at is updated

3. **Callback failures**: 
   - Check signature validation
   - Verify webhook URL configuration
   - Review error logs for details

### Debug Commands
```bash
# Check payment status
php artisan tinker
>>> App\Models\Payment::where('invoice_code', 'INV-XXX')->first()

# Cleanup expired payments
php artisan payments:cleanup-expired

# Check logs
tail -f storage/logs/laravel.log
```
