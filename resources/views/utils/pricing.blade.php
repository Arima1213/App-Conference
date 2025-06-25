<section id="pricing" class="s-pricing-table mt-5">
	<h2 class="title-conference"><span>Seminar Fees</span></h2>
	<div class="container">
		<div class="row">
			<!-- OFFLINE -->
			<div class="col-md-6 pt-4 text-center">
				<h3 class="mb-3"><strong>OFFLINE PARTICIPANT</strong></h3>
				<table class="table-bordered mx-auto table text-center" style="width: auto;">
					<thead class="thead-light">
						<tr>
							<th rowspan="2" class="align-middle">Category of Participants</th>
							<th rowspan="2" class="align-middle">Type</th>
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
								<td class="text-capitalize">{{ $fee->type }}</td>
								<td>{{ number_format($fee->early_bird_price, 0, ',', '.') }}</td>
								<td>{{ number_format($fee->regular_price, 0, ',', '.') }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>

			<!-- ONLINE -->
			<div class="col-md-6 pt-4 text-center">
				<h3 class="mb-3"><strong>ONLINE PARTICIPANT</strong></h3>
				<table class="table-bordered mx-auto table text-center" style="width: auto;">
					<thead class="thead-light">
						<tr>
							<th rowspan="2" class="align-middle">Category of Participants</th>
							<th rowspan="2" class="align-middle">Type</th>
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
								<td class="text-capitalize">{{ $fee->type }}</td>
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
