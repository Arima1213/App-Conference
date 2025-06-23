<section id="sponsors" class="s-clients s-partners">
	<div class="container">
		<h2 class="title-conference"><span>Our partners</span></h2>
		<div class="clients-cover">
			@forelse ($sponsors as $sponsor)
				<div class="client-slide">
					<div class="client-slide-cover">
						<img src="{{ asset('storage/' . $sponsor->logo) }}" alt="{{ $sponsor->name }}" style="max-height: 80px;">
					</div>
				</div>
			@empty
				<div class="client-slide">
					<div class="client-slide-cover">
						<img src="{{ asset('assets/img/client-placeholder.svg') }}" alt="No sponsors yet">
					</div>
				</div>
			@endforelse
		</div>
	</div>
</section>
