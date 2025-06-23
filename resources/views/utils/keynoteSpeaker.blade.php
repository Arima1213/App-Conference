@if ($keynoteSpeakers->count())
	<section class="s-our-speaker team-our-speaker">
		<div class="container mt-5 pt-5">
			<h2 class="title-conference"><span>Keynote Speaker</span></h2>
			<div class="slider-our-speaker">
				@foreach ($keynoteSpeakers as $speaker)
					<div class="slide-our-speaker">
						<div class="our-speaker-item">
							<img src="{{ $speaker->photo ? asset('storage/' . $speaker->photo) : asset('assets/img/placeholder-all.png') }}" alt="{{ $speaker->name }}">
							<div class="speaker-item-info">
								<h3 class="name">{{ $speaker->name }}</h3>
								<p class="prof">{{ $speaker->position ?? '-' }}</p>
							</div>
						</div>
					</div>
				@endforeach
			</div>
		</div>
	</section>
@endif
