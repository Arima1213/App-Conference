<section id="pricing" class="s-pricing-table mt-5">
	<h2 class="title-conference"><span>Seminar Fees</span></h2>
	<div class="container">
		<div class="row">
			<!-- OFFLINE -->
			<div style="width: 50%; float: left; padding-top: 1.5rem; text-align: center;">
				<h3 style="margin-bottom: 1rem;"><strong>OFFLINE PARTICIPANT</strong></h3>
				<table style="border-collapse: collapse; margin-left: auto; margin-right: auto; text-align: center; width: auto;">
					<thead style="background-color: #f8f9fa;">
						<tr>
							<th rowspan="2" style="border: 1px solid #dee2e6; vertical-align: middle; padding: 8px;">Category of Participants</th>
							<th colspan="2" style="border: 1px solid #dee2e6; padding: 8px;">Offline Fee</th>
						</tr>
						<tr>
							<th style="border: 1px solid #dee2e6; padding: 8px;">Early Bird</th>
							<th style="border: 1px solid #dee2e6; padding: 8px;">Regular</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($offlineFees as $fee)
							<tr>
								<td style="border: 1px solid #dee2e6; padding: 8px;">{{ $fee->category }}</td>
								<td style="border: 1px solid #dee2e6; padding: 8px;">{{ number_format($fee->early_bird_price, 0, ',', '.') }}</td>
								<td style="border: 1px solid #dee2e6; padding: 8px;">{{ number_format($fee->regular_price, 0, ',', '.') }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>

			<!-- ONLINE -->
			<div style="width: 50%; float: left; padding-top: 1.5rem; text-align: center;">
				<h3 style="margin-bottom: 1rem;"><strong>ONLINE PARTICIPANT</strong></h3>
				<table style="border-collapse: collapse; margin-left: auto; margin-right: auto; text-align: center; width: auto;">
					<thead style="background-color: #f8f9fa;">
						<tr>
							<th rowspan="2" style="border: 1px solid #dee2e6; vertical-align: middle; padding: 8px;">Category of Participants</th>
							<th colspan="2" style="border: 1px solid #dee2e6; padding: 8px;">Online Fee</th>
						</tr>
						<tr>
							<th style="border: 1px solid #dee2e6; padding: 8px;">Early Bird</th>
							<th style="border: 1px solid #dee2e6; padding: 8px;">Regular</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($onlineFees as $fee)
							<tr>
								<td style="border: 1px solid #dee2e6; padding: 8px;">{{ $fee->category }}</td>
								<td style="border: 1px solid #dee2e6; padding: 8px;">{{ number_format($fee->early_bird_price, 0, ',', '.') }}</td>
								<td style="border: 1px solid #dee2e6; padding: 8px;">{{ number_format($fee->regular_price, 0, ',', '.') }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			<div style="clear: both;"></div>
		</div>
	</div>
</section>
