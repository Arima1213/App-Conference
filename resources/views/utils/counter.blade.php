<section id="about" class="s-conference-mission" style="background-image: url(assets/img/bg-about-home2.png);">
	<div class="s-conference-counter">
		<div class="container">
			<div class="conference-counter-wrap">
				<img class="conference-counter-effect-1" src="assets/img/counter-icon-1.svg" alt="img">
				<div class="conference-counter-cover">
					<h4>The event will begin through</h4>
					<div id="clockdiv" class="clock-timer clock-timer-conference">
						<div class="clock-item days-item">
							<span class="days"></span>
							<div class="smalltext">Days</div>
						</div>
						<div class="clock-item hours-item">
							<span class="hours"></span>
							<div class="smalltext">Hours</div>
						</div>
						<div class="clock-item minutes-item">
							<span class="minutes"></span>
							<div class="smalltext">Minutes</div>
						</div>
						<div class="clock-item seconds-item">
							<span class="seconds"></span>
							<div class="smalltext">Seconds</div>
						</div>
					</div>
				</div>
				<img class="conference-counter-effect-2" src="assets/img/counter-icon-2.svg" alt="img">
			</div>
		</div>
	</div>
	<div class="s-our-mission s-about-speaker">
		<div class="container">
			<h2 class="title-conference">
				<span>
					{{ $conference->title ?? 'Our mission' }}
				</span>
			</h2>
			<div class="row">
				<div class="col-lg-6 our-mission-img">
					<span>
						<img src="assets/img/placeholder-all.png" data-src="assets/img/our-mission-2.svg" alt="" class="mission-img-effect-1 rx-lazy">
						<img class="mission-img rx-lazy" src="assets/img/placeholder-all.png"
							data-src="{{ $conference->banner ? 'storage/' . $conference->banner : 'assets/img/img-about-home2.jpg' }}" alt="img">
						<img src="assets/img/placeholder-all.png" data-src="assets/img/tringle-gray-little.svg" alt="" class="about-img-effect-2 rx-lazy">
					</span>
				</div>
				<div class="col-lg-6 our-mission-info">
					<ul class="mission-meta flex-column" style="display: flex; flex-direction: column; gap: 8px;">
						<li>
							<i aria-hidden="true" class="fas fa-map-marker-alt"></i>
							{{ optional($conference->venues->first())->name ?? 'Location' }}
						</li>
						<li>
							<i aria-hidden="true" class="fas fa-calendar-alt"></i>
							{{ optional($conference->schedules->first())->start_time ? \Carbon\Carbon::parse($conference->schedules->first()->start_time)->format('d.m.Y') : '-' }}
							&nbsp;
							<i class="far fa-clock"></i>
							@if (optional($conference->schedules->first()))
								{{ \Carbon\Carbon::parse($conference->schedules->first()->start_time)->format('H:i') }}
								-
								{{ \Carbon\Carbon::parse($conference->schedules->first()->end_time)->format('H:i') }}
							@else
								-
							@endif
						</li>
					</ul>
					<h4>{{ $conference->subtitle ?? 'Od tempor incididunt ut labore aliqua. ullamco laboris nisi ut aliquip' }}</h4>
					<div class="mission-info-text">
						<p>
							{{ $conference->description ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusm od tempor incididunt ut labore et dolore magna aliqua. Ut enim minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip' }}
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
