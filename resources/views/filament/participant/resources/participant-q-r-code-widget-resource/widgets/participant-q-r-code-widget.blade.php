<x-filament::widget>
	<x-filament::card>
		<div class="flex flex-col gap-6 md:flex-row md:items-start">
			<div class="my-auto w-full flex-1">
				@if ($participant)
					<div class="flex h-full flex-col justify-center space-y-4 md:h-[350px] md:items-start md:justify-center">
						<h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Welcome, {{ auth()->user()->name }}</h2>

						<div class="space-y-2 py-1">
							<p class="my-1 text-sm text-gray-700 dark:text-gray-200">Participant Code: <code>{{ $participant->participant_code }}</code></p>
							<p><strong>Seminar Kit:</strong>
								@if ($participant->seminar_kit_status)
									<span class="text-green-500">Taken</span>
								@else
									<span class="text-gray-500">Not Yet</span>
								@endif
							</p>
							<p><strong>Status:</strong>
								@if ($participant->status === 'verified')
									<span class="font-semibold text-green-600">Verified</span>
								@elseif($participant->status === 'arrived')
									<span class="font-semibold text-blue-600">Arrived</span>
								@else
									<span class="font-semibold text-yellow-600">Unverified</span>
								@endif
							</p>
						</div>
						<div class="flex items-center space-x-2">
							<a href=""
								style="display: inline-flex; align-items: center; border-radius: 0.375rem; background-color: #2563eb; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: #fff; text-decoration: none; transition: background 0.2s; outline: none; border: none;"
								onmouseover="this.style.backgroundColor='#1d4ed8'" onmouseout="this.style.backgroundColor='#2563eb'"
								onfocus="this.style.boxShadow='0 0 0 2px #3b82f6, 0 0 0 4px #fff'" onblur="this.style.boxShadow='none'">
								Edit Profile
							</a>
						</div>
					</div>
				@else
					<div class="text-center">
						<h2 class="text-lg font-bold text-gray-700">Anda belum mendaftar di conference.</h2>
						<p class="mt-2 text-sm text-gray-500">Silakan mendaftar untuk mengikuti conference.</p>
					</div>
				@endif
			</div>
			<div class="flex w-full flex-1 justify-center md:justify-end">
				@if (isset($qrCodeUrl))
					<img src="{{ $qrCodeUrl }}" alt="QR Code" class="h-auto w-full max-w-xs">
				@else
					<img src="{{ asset('svg/Social biography-amico.svg') }}" alt="Social biography" class="h-auto w-full max-w-xs">
				@endif
			</div>
		</div>
	</x-filament::card>
</x-filament::widget>
