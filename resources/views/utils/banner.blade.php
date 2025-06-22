<section class="s-conference-slider">
	<div class="conference-slider">
		@forelse($conferences as $conference)
			@php
				$schedule = $conference->schedules->first();
				$date = $schedule ? \Carbon\Carbon::parse($schedule->start_time)->format('d M Y') : '-';
				$background = $conference->banner_image ?? 'assets/img/slide1-home-2.jpg';
			@endphp
			<div class="conference-slide" style="background-image: url('{{ asset($background) }}');">
				<img class="conference-slide-tringle" src="{{ asset('assets/img/effect-tringle-slider.svg') }}" alt="Triangle Effect">
				<img class="conference-slide-effect" src="{{ asset('assets/img/effect-slider-left.svg') }}" alt="Slider Effect">
				<div class="container">
					<div class="conference-slide-item">
						<div class="date">{{ $date }}</div>
						<div class="conference-slider-title">{{ $conference->subtitle ?? 'No Subtitle' }}</div>
						<h2 class="title"><span>{{ $conference->title ?? 'No Title' }}</span></h2>
						<p>{{ $conference->description ?? 'No description available.' }}</p>
					</div>
				</div>
			</div>
		@empty
			<div class="conference-slide" style="background-image: url('{{ asset('assets/img/slide1-home-2.jpg') }}');">
				<img class="conference-slide-tringle" src="{{ asset('assets/img/effect-tringle-slider.svg') }}" alt="Triangle Effect">
				<img class="conference-slide-effect" src="{{ asset('assets/img/effect-slider-left.svg') }}" alt="Slider Effect">
				<div class="container">
					<div class="conference-slide-item">
						<div class="date">-</div>
						<div class="conference-slider-title">No Conference</div>
						<h2 class="title"><span>No Title</span></h2>
						<p>No conference data available.</p>
					</div>
				</div>
			</div>
		@endforelse
	</div>
</section>
