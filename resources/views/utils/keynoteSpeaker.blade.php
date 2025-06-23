@if ($speakers->count())
	<section id="our-speaker" class="s-our-speaker team-our-speaker">
		<div class="container mt-5 pt-5">
			<h2 class="title-conference"><span>Speakers</span></h2>
			<div class="slider-our-speaker">
				@foreach ($speakers as $speaker)
					<div class="slide-our-speaker">
						<div class="our-speaker-item">
							<img src="{{ $speaker->photo ? asset('storage/' . $speaker->photo) : asset('assets/img/placeholder-all.png') }}" alt="{{ $speaker->name }}">
							<div class="speaker-item-info" @if ($speaker->is_keynote) style="background-color: #FFD700;" @endif>
								@if ($speaker->is_keynote)
									<span title="Keynote Speaker" class="text-warning" style="font-size: 1.2rem;">Keynote</span>
								@endif
								<h3 class="name">
									{{ $speaker->name }}
								</h3>
								<p class="prof">{{ \Illuminate\Support\Str::limit($speaker->position ?? '-', 30) }}</p>
							</div>
						</div>
					</div>
				@endforeach
			</div>
		</div>
	</section>
@endif
