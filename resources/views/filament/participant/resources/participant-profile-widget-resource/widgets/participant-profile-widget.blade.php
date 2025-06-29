<x-filament::widget>
	<x-filament::card class="p-6">
		<div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
			{{-- Gambar Kiri --}}
			<div class="flex w-full flex-shrink-0 items-center justify-center md:w-1/3">
				<img src="{{ asset('svg/Social biography-amico.svg') }}" alt="Profile Illustration" class="h-40 w-40 rounded-md object-cover">
			</div>

			{{-- Informasi dan Tombol --}}
			<div class="flex w-full flex-col justify-between gap-4 md:w-2/3">
				@if ($participant)
					<div class="space-y-2">
						<h2 class="text-xl font-semibold text-gray-900 dark:text-white">Welcome, {{ auth()->user()->name }}</h2>

						<p class="text-sm text-gray-700 dark:text-gray-200">
							<span class="font-medium">NIK:</span> {{ $participant->nik ?? '-' }}
						</p>
						<p class="text-sm text-gray-700 dark:text-gray-200">
							<span class="font-medium">University:</span> {{ $participant->educationalInstitution->nama_pt ?? '-' }}
						</p>
						<p class="text-sm text-gray-700 dark:text-gray-200">
							<span class="font-medium">Phone:</span> {{ $participant->phone ?? '-' }}
						</p>
						<p class="text-sm text-gray-700 dark:text-gray-200">
							<span class="font-medium">Paper Title:</span> {{ $participant->paper_title ?? '-' }}
						</p>
					</div>

					<div class="pt-3">
						<a href="#"
							style="
                                display:inline-flex;
                                align-items:center;
                                border-radius:0.375rem;
                                background-color:#2563eb;
                                padding:0.5rem 1rem;
                                font-size:0.875rem;
                                font-weight:500;
                                color:#fff;
                                box-shadow:0 1px 2px 0 rgba(0,0,0,0.05);
                                transition:background-color 0.2s;
                                text-decoration:none;"
							onmouseover="this.style.backgroundColor='#1d4ed8'" onmouseout="this.style.backgroundColor='#2563eb'">
							Edit Profile
						</a>
					</div>
				@else
					<div class="w-full text-center">
						<h2 class="text-xl font-semibold text-gray-800 dark:text-white">
							You have not registered for the conference.
						</h2>
						<p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
							Please register to participate in the conference.
						</p>
					</div>
				@endif
			</div>
		</div>
	</x-filament::card>
</x-filament::widget>
