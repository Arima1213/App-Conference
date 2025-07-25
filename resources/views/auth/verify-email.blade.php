<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Verify Your Email - PPPKMI Conference</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			margin: 0;
			padding: 20px;
			background-color: #f5f5f5;
			display: flex;
			justify-content: center;
			align-items: center;
			min-height: 100vh;
		}

		.container {
			max-width: 500px;
			background: white;
			padding: 40px;
			border-radius: 8px;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
			text-align: center;
		}

		.logo {
			margin-bottom: 30px;
		}

		.logo img {
			max-height: 60px;
		}

		h1 {
			color: #333;
			margin-bottom: 20px;
			font-size: 24px;
		}

		p {
			color: #666;
			line-height: 1.6;
			margin-bottom: 20px;
		}

		.btn {
			display: inline-block;
			padding: 12px 24px;
			background-color: #007AFF;
			color: white;
			text-decoration: none;
			border-radius: 5px;
			font-weight: bold;
			border: none;
			cursor: pointer;
			font-size: 14px;
		}

		.btn:hover {
			background-color: #0056CC;
		}

		.success-message {
			background-color: #d4edda;
			color: #155724;
			padding: 10px;
			border-radius: 4px;
			margin-bottom: 20px;
		}

		.email-info {
			background-color: #f8f9fa;
			padding: 15px;
			border-radius: 4px;
			margin: 20px 0;
			border-left: 4px solid #007AFF;
		}
	</style>
</head>

<body>
	<div class="container">
		<div class="logo">
			<img src="{{ asset('assets/img/logo-pppkmi-ultimate.svg') }}" alt="PPPKMI Logo">
		</div>

		<h1>📧 Verify Your Email Address</h1>

		@if (session('message'))
			<div class="success-message">
				{{ session('message') }}
			</div>
		@endif

		<div class="email-info">
			<strong>Email:</strong> {{ auth()->user()->email }}
		</div>

		<p>
			Thanks for signing up for PPPKMI Conference! Before getting started, could you verify your email address by clicking on the link we just emailed to
			you?
		</p>

		<p>
			If you didn't receive the email, we will gladly send you another.
		</p>

		<form method="POST" action="{{ route('verification.send') }}">
			@csrf
			<button type="submit" class="btn">
				📤 Resend Verification Email
			</button>
		</form>

		<p style="margin-top: 30px; font-size: 12px; color: #999;">
			Having trouble? Contact our support team for assistance.
		</p>
	</div>
</body>

</html>
