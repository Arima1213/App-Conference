<x-filament::widget>
	<x-filament::card>
		<div class="flex flex-col gap-8 md:flex-row md:items-start">
			<div class="my-auto w-full flex-1">
				@if ($participant)
					<div class="flex h-full flex-col justify-center space-y-6 md:h-[350px] md:items-start md:justify-center">
						<h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Participant Registration QR Code</h2>

						<div class="space-y-3 py-2">
							<p class="text-base text-gray-700 dark:text-gray-200">
								<span class="font-medium">Participant Code:</span>
								<code class="rounded bg-gray-100 px-2 py-1 dark:bg-gray-800">{{ $participant->participant_code }}</code>
							</p>
							<p class="text-base text-gray-700 dark:text-gray-200">
								<span class="font-medium">Seminar Kit:</span>
								@if ($participant->seminar_kit_status)
									<span class="font-semibold text-green-600">Collected</span>
								@else
									<span class="text-gray-500">Not Collected</span>
								@endif
							</p>
							<p class="text-base text-gray-700 dark:text-gray-200">
								<span class="font-medium">Status:</span>
								@if ($participant->status === 'verified')
									<span class="font-semibold text-green-600">Verified</span>
								@elseif($participant->status === 'arrived')
									<span class="font-semibold text-blue-600">Arrived</span>
								@else
									<span class="font-semibold text-yellow-600">Unverified</span>
								@endif
							</p>
						</div>
						<div class="flex items-center space-x-3">
							<a href="{{ $qrCodeUrl ?? '#' }}" download="participant-qr-code.png"
								class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
								Download QR Code
							</a>
						</div>
					</div>
				@else
					<div class="text-center">
						<h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">You have not registered for the conference.</h2>
						<p class="mt-2 text-base text-gray-500 dark:text-gray-400">Please register to participate in the conference.</p>
					</div>
				@endif
			</div>
			<div class="flex w-full flex-1 justify-center md:justify-end">
				@if (isset($qrCodeUrl))
					<img src="{{ $qrCodeUrl }}" alt="Participant QR Code"
						class="h-auto w-full max-w-xs rounded border border-gray-200 shadow-md dark:border-gray-700">
				@else
					<img src="{{ asset('svg/Social biography-rafiki.svg') }}" alt="Conference Illustration" class="h-auto w-full max-w-xs">
				@endif
			</div>
		</div>
	</x-filament::card>
</x-filament::widget>
