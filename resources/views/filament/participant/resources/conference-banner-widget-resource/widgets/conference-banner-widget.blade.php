<x-filament-widgets::widget>
	<x-filament::section>
		@if ($conference)
			<div class="relative w-full overflow-hidden rounded-xl shadow-lg">
				<img src="{{ asset('storage/' . $conference->banner) }}" alt="{{ $conference->title }}" class="h-64 w-full object-cover">

				<div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50">
					<a href="{{ route('register') }}" class="rounded-md bg-yellow-500 px-6 py-3 text-lg font-semibold text-white transition hover:bg-yellow-600">
						Register Now
					</a>
				</div>
			</div>
		@else
			<div class="p-6 text-center text-gray-500">
				No active conference available.
			</div>
		@endif
	</x-filament::section>
</x-filament-widgets::widget>
