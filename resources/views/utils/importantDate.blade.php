@if ($importantDates && $importantDates->count())
	<section id="schedule" class="s-speakers-schedule">
		<div class="container">
			<h2 class="title-conference"><span>Important Dates</span></h2>
			<div class="speakers-timeline-cover">
				@forelse ($importantDates as $date)
					<div class="speakers-timeline-item">
						<div class="speakers-timeline-img">
						</div>
						<div class="speakers-timeline-info">
							<div class="date">{{ $date->date->format('d M Y') }}</div>
							<h3 class="title">{{ $date->title ?? 'Untitled' }}<br>{{ $date->subtitle ?? '' }}</h3>
							<p>
								{{ $date->description ?? 'lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.' }}
							</p>
						</div>
					</div>
				@endforeach
			</div>
		</div>
	</section>
@endif
