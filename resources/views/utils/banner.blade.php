<section class="s-conference-slider">
	<div class="conference-slider">

		@foreach ($conferences as $conference)
			@php
				$schedule = $conference->schedules->first();
				$date = $schedule ? \Carbon\Carbon::parse($schedule->start_time)->format('d M Y') : '-';
				$background = 'assets/img/slide1-home-2.jpg';
				$titleParts = explode(' ', $conference->title ?? 'No Title', 2);
				$subtitle = $titleParts[0] ?? '';
				$title = $titleParts[1] ?? '';
			@endphp
			<div class="conference-slide" style="background-image: url('{{ asset($background) }}');">
				<img class="conference-slide-tringle" src="{{ asset('assets/img/effect-tringle-slider.svg') }}" alt="Triangle Effect">
				<img class="conference-slide-effect" src="{{ asset('assets/img/effect-slider-left.svg') }}" alt="Slider Effect">
				<div class="container">
					<div class="conference-slide-item">
						<div class="date">{{ $date }}</div>
						<div class="conference-slider-title">{{ $subtitle }}</div>
						<h2 class="title"><span>{{ $title }}</span></h2>
						<p>{{ $conference->description ?? 'No description available.' }}</p>
					</div>
				</div>
			</div>
		@endforeach
	</div>
</section>
