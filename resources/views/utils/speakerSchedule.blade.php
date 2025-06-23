@if ($conference && $conference->schedules->count())
	<section id="schedule" class="s-speakers-schedule">
		<div class="container">
			<h2 class="title-conference"><span>Speaker & Schedule</span></h2>
			<div class="speakers-timeline-cover">
				@foreach ($conference->schedules as $schedule)
					@php
						$speaker = $schedule->speaker;
						$image = $speaker && $speaker->photo ? asset('storage/' . $speaker->photo) : asset('assets/img/placeholder-all.png');
						$time = \Carbon\Carbon::parse($schedule->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($schedule->end_time)->format('H:i');
					@endphp
					<div class="speakers-timeline-item">
						<div class="speakers-timeline-img">
							<a href="#" class="our-speaker-item">
								<img class="rx-lazy" src="{{ asset('assets/img/placeholder-all.png') }}" data-src="{{ $image }}"
									alt="{{ $speaker->name ?? 'Speaker' }}">
								<div class="speaker-item-info">
									<h3 class="name">{{ $speaker->name ?? 'Unknown' }}</h3>
									<p class="prof">{{ $speaker->position ?? '-' }}</p>
								</div>
							</a>
						</div>
						<div class="speakers-timeline-info">
							<div class="date">{{ $time }}</div>
							<h3 class="title">{{ $schedule->title ?? 'Untitled' }}<br>{{ $schedule->subtitle ?? '' }}</h3>
							<p>{{ $schedule->description ?? '-' }}</p>
						</div>
					</div>
				@endforeach
			</div>
		</div>
	</section>
@endif
