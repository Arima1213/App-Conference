<section class="s-conference-slider">
	<div class="conference-slider">
		@if (isset($conferences) && $conferences->count())
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
		@else
			<div class="conference-slide" style="background-image: url('{{ asset('assets/img/slide1-home-2.jpg') }}');">
				<img class="conference-slide-tringle" src="{{ asset('assets/img/effect-tringle-slider.svg') }}" alt="Triangle Effect">
				<img class="conference-slide-effect" src="{{ asset('assets/img/effect-slider-left.svg') }}" alt="Slider Effect">
				<div class="container">
					<div class="conference-slide-item text-center">
						<div class="date">-</div>
						<div class="conference-slider-title">No Conference Available</div>
						<h2 class="title"><span>No Upcoming Events</span></h2>
						<p>
							Saat ini belum ada data konferensi yang tersedia.<br>
							Silakan cek kembali nanti atau hubungi administrator untuk informasi lebih lanjut.
						</p>
						<a href="{{ url('/') }}" class="btn btn-primary mt-3">Kembali ke Beranda</a>
					</div>
				</div>
			</div>
		@endif
	</div>
</section>
