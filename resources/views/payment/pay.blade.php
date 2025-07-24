<!-- Basic payment view for web route if needed -->
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Payment - PPPKMI Conference</title>
	<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<style>
		body {
			font-family: Arial, sans-serif;
			margin: 0;
			padding: 20px;
			background-color: #f5f5f5;
		}

		.payment-container {
			max-width: 600px;
			margin: 0 auto;
			background: white;
			padding: 30px;
			border-radius: 8px;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
		}

		.payment-info {
			margin-bottom: 20px;
		}

		.payment-info h2 {
			color: #333;
			margin-bottom: 15px;
		}

		.info-row {
			display: flex;
			justify-content: space-between;
			margin-bottom: 10px;
			padding: 8px 0;
			border-bottom: 1px solid #eee;
		}

		.info-label {
			font-weight: bold;
			color: #666;
		}

		.info-value {
			color: #333;
		}

		.pay-button {
			width: 100%;
			padding: 15px;
			background-color: #007AFF;
			color: white;
			border: none;
			border-radius: 5px;
			font-size: 16px;
			font-weight: bold;
			cursor: pointer;
			margin-top: 20px;
		}

		.pay-button:hover {
			background-color: #0056CC;
		}

		.logo {
			text-align: center;
			margin-bottom: 20px;
		}

		.logo img {
			max-height: 60px;
		}
	</style>
</head>

<body>
	<div class="payment-container">
		<div class="logo">
			<img src="{{ asset('assets/img/logo-pppkmi-ultimate.svg') }}" alt="PPPKMI Logo">
		</div>

		<div class="payment-info">
			<h2>Payment Details</h2>
			<div class="info-row">
				<span class="info-label">Invoice Code:</span>
				<span class="info-value">{{ $payment->invoice_code }}</span>
			</div>
			<div class="info-row">
				<span class="info-label">Amount:</span>
				<span class="info-value">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
			</div>
			<div class="info-row">
				<span class="info-label">Participant:</span>
				<span class="info-value">{{ $payment->participant->user->name }}</span>
			</div>
			<div class="info-row">
				<span class="info-label">Conference:</span>
				<span class="info-value">{{ $payment->participant->conference->title }}</span>
			</div>
			<div class="info-row">
				<span class="info-label">Status:</span>
				<span class="info-value">{{ ucfirst($payment->payment_status) }}</span>
			</div>
		</div>

		<button id="pay-button" class="pay-button">
			🔒 Pay Now - Secure Payment
		</button>
	</div>

	<script>
		document.getElementById('pay-button').addEventListener('click', function() {
			snap.pay('{{ $snapToken }}', {
				onSuccess: function(result) {
					Swal.fire({
						icon: 'success',
						title: 'Payment Successful!',
						text: 'Thank you! Your payment has been processed successfully.',
						confirmButtonText: 'OK'
					}).then(() => {
						window.location.href = '/participant';
					});
				},
				onPending: function(result) {
					Swal.fire({
						icon: 'info',
						title: 'Payment Pending',
						text: 'Your payment is being processed. Please wait for confirmation.',
						confirmButtonText: 'OK'
					});
				},
				onError: function(result) {
					Swal.fire({
						icon: 'error',
						title: 'Payment Failed',
						text: 'There was an error processing your payment. Please try again.',
						confirmButtonText: 'Retry'
					});
				},
				onClose: function() {
					Swal.fire({
						icon: 'warning',
						title: 'Payment Cancelled',
						text: 'You closed the payment window before completing the transaction.',
						confirmButtonText: 'OK'
					});
				}
			});
		});
	</script>
</body>

</html>
