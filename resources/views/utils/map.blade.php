<section id="location" class="conference-map">
	<div class="container">
		<h2 class="title-conference"><span>Our location</span></h2>
		<div class="row">
			<div class="col-lg-5 conference-map-info">
				@if ($conference && $conference->venues->first())
					@php
						$venue = $conference->venues->first();
						$startTime = optional($conference->schedules->first())->start_time;
						$date = $startTime ? \Carbon\Carbon::parse($startTime)->format('d.m.Y') : '-';
						$time = $startTime ? \Carbon\Carbon::parse($startTime)->format('H:i') : '-';
					@endphp
					<ul class="mission-meta">
						<li><i class="fas fa-calendar-alt"></i>{{ $date }}</li>
						<li><i class="far fa-clock"></i>{{ $time }}</li>
					</ul>
					@php
						$venueName = $venue->name;
						$words = explode(' ', $venueName);
						$highlightWord = count($words) > 1 ? $words[1] : $words[0];
						$venueNameHighlighted = str_replace($highlightWord, "<span>{$highlightWord}</span>", $venueName, $count);
					@endphp
					<h3>{!! $venueNameHighlighted !!}</h3>
					<ul class="mission-meta">
						<li><i class="fas fa-map-marker-alt"></i>{{ $venue->address }}</li>
					</ul>
				@else
					<p>No venue available</p>
				@endif
			</div>
			<div class="col-lg-7 conference-map-item">
				@if ($venue?->map_url)
					<div class="mapouter">
						<div class="gmap_canvas">
							<iframe class="gmap_iframe" width="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
								src="{{ $venue->map_url }}"></iframe>
						</div>
						<style>
							.mapouter {
								position: relative;
								text-align: right;
								width: 100%;
								height: 400px;
							}

							.gmap_canvas {
								overflow: hidden;
								background: none !important;
								width: 100%;
								height: 400px;
							}

							.gmap_iframe {
								height: 400px !important;
							}
						</style>
					</div>
				@endif
			</div>
		</div>
	</div>
</section>
