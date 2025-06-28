<x-filament::widget>
	<x-filament::card>
		<div class="flex flex-col gap-6 md:flex-row md:items-start">
			<div class="my-auto w-full flex-1">
				@if ($participant)
					<div class="flex h-full flex-col justify-center space-y-4 md:h-[350px] md:items-start md:justify-center">
						<h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Welcome, {{ auth()->user()->name }}</h2>

						<div class="space-y-2 py-1">
							<p class="my-1 text-base text-gray-700 dark:text-gray-200"><span class="font-medium">NIK:</span> {{ $participant->nik ?? '-' }}</p>
							<p class="my-1 text-base text-gray-700 dark:text-gray-200"><span class="font-medium">University:</span> {{ $participant->university ?? '-' }}</p>
							<p class="my-1 text-base text-gray-700 dark:text-gray-200"><span class="font-medium">Phone:</span> {{ $participant->phone ?? '-' }}</p>
							<p class="my-1 text-base text-gray-700 dark:text-gray-200"><span class="font-medium">Paper Title:</span> {{ $participant->paper_title ?? '-' }}
							</p>
						</div>

						<div class="flex items-center space-x-2">
							<a href="#"
								class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
								Edit Profile
							</a>
						</div>
					</div>
				@else
					<div class="text-center">
						<h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">You have not registered for the conference.</h2>
						<p class="mt-2 text-base text-gray-500 dark:text-gray-400">Please register to participate in the conference.</p>
					</div>
				@endif
			</div>
			<div class="flex w-full flex-1 justify-center md:justify-end">
				<img src="{{ asset('svg/Social biography-amico.svg') }}" alt="Social biography" class="h-auto w-full max-w-xs">
			</div>
		</div>
	</x-filament::card>
</x-filament::widget>
