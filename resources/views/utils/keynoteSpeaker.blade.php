@if ($speakers->count())
	<section id="our-speaker" class="s-our-speaker team-our-speaker">
		<div class="container mt-5 pt-5">
			<h2 class="title-conference"><span>Speakers</span></h2>
			<div class="slider-our-speaker">
				@foreach ($speakers as $speaker)
					<div class="slide-our-speaker">
						<div class="our-speaker-item">
							<img src="{{ $speaker->photo ? asset('storage/' . $speaker->photo) : asset('assets/img/placeholder-all.png') }}" alt="{{ $speaker->name }}">
							<div class="speaker-item-info"
								@if ($speaker->is_keynote) style="background-color: #FFC300; padding-top: 0.3rem; padding-bottom: 0.3rem;" @else style="padding-top: 0.3rem; padding-bottom: 0.3rem;" @endif>
								@if ($speaker->is_keynote)
									<span title="Keynote Speaker" class="text-warning" style="font-size: 0.9rem;">Keynote</span>
								@endif
								<h3 class="name" style="font-size: 1rem;">
									{{ $speaker->name }}
								</h3>
								<p class="prof"
									style="font-size: 0.85rem; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical;">
									{{ $speaker->position ?? '-' }}
								</p>
							</div>
						</div>
					</div>
				@endforeach
			</div>
		</div>
	</section>
@endif
