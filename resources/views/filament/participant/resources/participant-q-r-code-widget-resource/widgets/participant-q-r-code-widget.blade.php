<x-filament::widget>
	<x-filament::card>
		<div class="flex flex-col gap-8 md:flex-row md:items-start">
			<div class="my-auto w-full flex-1">
				@if ($participant)
					<div class="flex h-full flex-col justify-center space-y-6 md:h-[350px] md:items-start md:justify-center">
						<h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Registration QR Code</h2>

						<div class="space-y-3 py-2">
							<p class="text-base text-gray-700 dark:text-gray-200">
								<span class="font-medium">Participant Code:</span>
								<code class="rounded bg-gray-100 px-2 py-1 dark:bg-gray-800">{{ $participant->participant_code }}</code>
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
							@if (isset($qrCodeUrl))
								<a href="{{ $qrCodeUrl }}" download="participant-qr-code.png"
									style="
                                        display: inline-flex;
                                        align-items: center;
                                        border-radius: 0.375rem;
                                        background-color: #2563eb;
                                        padding: 0.5rem 1rem;
                                        font-size: 0.875rem;
                                        font-weight: 500;
                                        color: #fff;
                                        box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);
                                        transition: background-color 0.2s;
                                        text-decoration: none;
                                    "
									onmouseover="this.style.backgroundColor='#1d4ed8'" onmouseout="this.style.backgroundColor='#2563eb'">
									Download QR Code
								</a>
							@endif
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
		<div class="rounded bg-blue-50 p-3 text-sm text-blue-900 dark:bg-blue-900/30 dark:text-blue-100">
			<strong>Note:</strong> Show this QR code at check-in. Staff will scan it to confirm your arrival.
		</div>
	</x-filament::card>
</x-filament::widget>
