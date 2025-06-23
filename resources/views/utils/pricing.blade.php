<section class="s-pricing-table mt-5">
	<div class="container">
		<div class="row">
			<!-- INTERNATIONAL -->
			<div class="col-md-6 text-center">
				<h3 class="mb-3"><strong>INTERNATIONAL PARTICIPANT</strong></h3>
				<table class="table-bordered mx-auto table text-center" style="width: auto;">
					<thead class="thead-light">
						<tr>
							<th rowspan="2" class="align-middle">Category of Participants</th>
							<th colspan="2">International Participant (USD)</th>
						</tr>
						<tr>
							<th>Early Bird</th>
							<th>Regular</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($internationalFees as $fee)
							<tr>
								<td>{{ $fee->category }}</td>
								<td>{{ number_format($fee->early_bird_price, 0) }}</td>
								<td>{{ number_format($fee->regular_price, 0) }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>

			<!-- NATIONAL -->
			<div class="col-md-6 text-center">
				<h3 class="mb-3"><strong>NATIONAL PARTICIPANT</strong></h3>
				<table class="table-bordered mx-auto table text-center" style="width: auto;">
					<thead class="thead-light">
						<tr>
							<th rowspan="2" class="align-middle">Category of Participants</th>
							<th colspan="2">Indonesian Participant (IDR)</th>
						</tr>
						<tr>
							<th>Early Bird</th>
							<th>Regular</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($nationalFees as $fee)
							<tr>
								<td>{{ $fee->category }}</td>
								<td>{{ number_format($fee->early_bird_price, 0, ',', '.') }}</td>
								<td>{{ number_format($fee->regular_price, 0, ',', '.') }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>
<style>
	.table-bordered {
		border: 2px solid #000;
	}

	.table-bordered th,
	.table-bordered td {
		border: 1px solid #000;
		padding: 0.75rem;
		vertical-align: middle;
	}
</style>
<style>
	.table-bordered {
		border: 2px solid #000;
	}

	.table-bordered th,
	.table-bordered td {
		border: 1px solid #000;
		padding: 0.75rem;
		vertical-align: middle;
		font-size: 1.1rem;
		color: #222;
		background-color: #fff;
	}

	.table-bordered th {
		background-color: #f8f9fa;
		font-weight: bold;
		color: #111;
	}

	.table-bordered tbody tr:hover td {
		background-color: #f1f1f1;
	}
</style>
