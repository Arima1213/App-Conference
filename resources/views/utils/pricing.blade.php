<section id="pricing" class="s-pricing-table mt-5">
	<h2 class="title-conference"><span>Seminar Fees</span></h2>
	<div class="container">
		<div class="pricing-grid">
			<!-- OFFLINE -->
			<div class="pricing-card">
				<h3 class="pricing-title"><strong>OFFLINE PARTICIPANT</strong></h3>
				<table class="pricing-table">
					<thead>
						<tr>
							<th rowspan="2">Category of Participants</th>
							<th colspan="2">Offline Fee</th>
						</tr>
						<tr>
							<th>Early Bird</th>
							<th>Regular</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($offlineFees as $fee)
							<tr>
								<td>{{ $fee->category }}</td>
								<td>IDR {{ number_format($fee->early_bird_price, 0, ',', '.') }}</td>
								<td>IDR {{ number_format($fee->regular_price, 0, ',', '.') }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>

			<!-- ONLINE -->
			<div class="pricing-card">
				<h3 class="pricing-title"><strong>ONLINE PARTICIPANT</strong></h3>
				<table class="pricing-table">
					<thead>
						<tr>
							<th rowspan="2">Category of Participants</th>
							<th colspan="2">Online Fee</th>
						</tr>
						<tr>
							<th>Early Bird</th>
							<th>Regular</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($onlineFees as $fee)
							<tr>
								<td>{{ $fee->category }}</td>
								<td>IDR {{ number_format($fee->early_bird_price, 0, ',', '.') }}</td>
								<td>IDR {{ number_format($fee->regular_price, 0, ',', '.') }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>

		<style>
			.pricing-grid {
				display: grid;
				grid-template-columns: 1fr;
				gap: 2rem;
			}

			.pricing-card {
				padding-top: 1.5rem;
				text-align: center;
			}

			.pricing-title {
				margin-bottom: 1rem;
			}

			.pricing-table {
				border-collapse: collapse;
				margin-left: auto;
				margin-right: auto;
				text-align: center;
				width: auto;
			}

			.pricing-table th,
			.pricing-table td {
				border: 1px solid #dee2e6;
				padding: 8px;
			}

			.pricing-table thead {
				background-color: #f8f9fa;
			}

			@media (min-width: 768px) {
				.pricing-grid {
					grid-template-columns: 1fr 1fr;
				}
			}
		</style>
	</div>
</section>
