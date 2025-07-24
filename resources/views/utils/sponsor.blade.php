<section id="sponsors" class="s-clients s-partners">
	<div class="container">
		<h2 class="title-conference"><span>Our Partners</span></h2>

		@if ($sponsors->count() > 0)
			{{-- Gold Sponsors --}}
			@php $goldSponsors = $sponsors->where('level', 'gold'); @endphp
			@if ($goldSponsors->count())
				<div class="sponsor-tier gold-tier">
					<h4 class="tier-title gold-title">
						<i class="fas fa-crown"></i>Gold Partners
					</h4>
					<div class="tier-sponsors gold-sponsors">
						@foreach ($goldSponsors as $sponsor)
							<div class="sponsor-card sponsor-gold">
								<div class="sponsor-content">
									@if ($sponsor->website)
										<a href="{{ $sponsor->website }}" target="_blank" rel="noopener" class="sponsor-link">
											<img src="{{ asset('storage/' . $sponsor->logo) }}" alt="{{ $sponsor->name }}" class="sponsor-logo">
										</a>
									@else
										<img src="{{ asset('storage/' . $sponsor->logo) }}" alt="{{ $sponsor->name }}" class="sponsor-logo">
									@endif
									<div class="sponsor-info">
										<span class="sponsor-badge badge-gold">{{ ucfirst($sponsor->level) }} Partner</span>
										<h5 class="sponsor-name">{{ $sponsor->name }}</h5>
									</div>
								</div>
							</div>
						@endforeach
					</div>
				</div>
			@endif

			{{-- Silver Sponsors --}}
			@php $silverSponsors = $sponsors->where('level', 'silver'); @endphp
			@if ($silverSponsors->count())
				<div class="sponsor-tier silver-tier">
					<h4 class="tier-title silver-title">
						<i class="fas fa-medal"></i>Silver Partners
					</h4>
					<div class="tier-sponsors silver-sponsors">
						@foreach ($silverSponsors as $sponsor)
							<div class="sponsor-card sponsor-silver">
								<div class="sponsor-content">
									@if ($sponsor->website)
										<a href="{{ $sponsor->website }}" target="_blank" rel="noopener" class="sponsor-link">
											<img src="{{ asset('storage/' . $sponsor->logo) }}" alt="{{ $sponsor->name }}" class="sponsor-logo">
										</a>
									@else
										<img src="{{ asset('storage/' . $sponsor->logo) }}" alt="{{ $sponsor->name }}" class="sponsor-logo">
									@endif
									<div class="sponsor-info">
										<span class="sponsor-badge badge-silver">{{ ucfirst($sponsor->level) }} Partner</span>
										<h5 class="sponsor-name">{{ $sponsor->name }}</h5>
									</div>
								</div>
							</div>
						@endforeach
					</div>
				</div>
			@endif

			{{-- Bronze Sponsors --}}
			@php $bronzeSponsors = $sponsors->where('level', 'bronze'); @endphp
			@if ($bronzeSponsors->count())
				<div class="sponsor-tier bronze-tier">
					<h4 class="tier-title bronze-title">
						<i class="fas fa-award"></i>Bronze Partners
					</h4>
					<div class="tier-sponsors bronze-sponsors">
						@foreach ($bronzeSponsors as $sponsor)
							<div class="sponsor-card sponsor-bronze">
								<div class="sponsor-content">
									@if ($sponsor->website)
										<a href="{{ $sponsor->website }}" target="_blank" rel="noopener" class="sponsor-link">
											<img src="{{ asset('storage/' . $sponsor->logo) }}" alt="{{ $sponsor->name }}" class="sponsor-logo">
										</a>
									@else
										<img src="{{ asset('storage/' . $sponsor->logo) }}" alt="{{ $sponsor->name }}" class="sponsor-logo">
									@endif
									<div class="sponsor-info">
										<span class="sponsor-badge badge-bronze">{{ ucfirst($sponsor->level) }} Partner</span>
										<h5 class="sponsor-name">{{ $sponsor->name }}</h5>
									</div>
								</div>
							</div>
						@endforeach
					</div>
				</div>
			@endif
		@else
			{{-- Jika tidak ada sponsor sama sekali --}}
			<div class="no-sponsors">
				<div class="no-sponsors-content">
					<i class="fas fa-handshake"></i>
					<h4>Become Our Partner</h4>
					<p>Join us as a partner and showcase your brand to our community.</p>
				</div>
			</div>
		@endif
	</div>
</section>
