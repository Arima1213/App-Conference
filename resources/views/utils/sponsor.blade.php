<section id="sponsors" class="s-clients s-partners">
	<div class="container">
		<h2 class="title-conference"><span>Our partners</span></h2>
		<div class="clients-cover">

			{{-- Gold Sponsors --}}
			@php $goldSponsors = $sponsors->where('level', 'gold'); @endphp
			@if ($goldSponsors->count())
				@foreach ($goldSponsors as $sponsor)
					<div class="client-slide">
						<div class="client-slide-cover">
							<img src="{{ asset('storage/' . $sponsor->logo) }}" alt="{{ $sponsor->name }}" style="max-height: 120px;">
							<div class="sponsor-level" style="text-align:center; margin-top:8px;">
								<span class="badge badge-gold">{{ ucfirst($sponsor->level) }}</span>
							</div>
						</div>
					</div>
				@endforeach
			@endif

			{{-- Silver Sponsors --}}
			@php $silverSponsors = $sponsors->where('level', 'silver'); @endphp
			@if ($silverSponsors->count())
				@foreach ($silverSponsors as $sponsor)
					<div class="client-slide">
						<div class="client-slide-cover">
							<img src="{{ asset('storage/' . $sponsor->logo) }}" alt="{{ $sponsor->name }}" style="max-height: 90px;">
							<div class="sponsor-level" style="text-align:center; margin-top:8px;">
								<span class="badge badge-silver">{{ ucfirst($sponsor->level) }}</span>
							</div>
						</div>
					</div>
				@endforeach
			@endif

			{{-- Bronze Sponsors --}}
			@php $bronzeSponsors = $sponsors->where('level', 'bronze'); @endphp
			@if ($bronzeSponsors->count())
				@foreach ($bronzeSponsors as $sponsor)
					<div class="client-slide">
						<div class="client-slide-cover">
							<img src="{{ asset('storage/' . $sponsor->logo) }}" alt="{{ $sponsor->name }}" style="max-height: 60px;">
							<div class="sponsor-level" style="text-align:center; margin-top:8px;">
								<span class="badge badge-bronze">{{ ucfirst($sponsor->level) }}</span>
							</div>
						</div>
					</div>
				@endforeach
			@endif

			{{-- Jika tidak ada sponsor sama sekali --}}
			@if ($sponsors->count() == 0)
				<div class="client-slide">
					<div class="client-slide-cover">
						<img src="{{ asset('assets/img/client-placeholder.svg') }}" alt="No sponsors yet">
					</div>
				</div>
			@endif

		</div>
	</div>
</section>
