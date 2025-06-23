<x-filament::widget>
	<x-filament::card>
		@if ($participant)
			<div class="space-y-2">
				<h2 class="text-lg font-bold">Welcome, {{ auth()->user()->name }}</h2>

				<p><strong>NIK:</strong> {{ $participant->nik ?? '-' }}</p>
				<p><strong>University:</strong> {{ $participant->university ?? '-' }}</p>
				<p><strong>Phone:</strong> {{ $participant->phone }}</p>
				<p><strong>Participant Code:</strong> <code>{{ $participant->participant_code }}</code></p>
				<p><strong>Paper Title:</strong> {{ $participant->paper_title ?? '-' }}</p>
				<p><strong>Status:</strong>
					@if ($participant->status === 'verified')
						<span class="font-semibold text-green-600">Verified</span>
					@elseif($participant->status === 'arrived')
						<span class="font-semibold text-blue-600">Arrived</span>
					@else
						<span class="font-semibold text-yellow-600">Unverified</span>
					@endif
				</p>
				<p><strong>Seminar Kit:</strong>
					{!! $participant->seminar_kit_status ? '<span class="text-green-500">Taken</span>' : '<span class="text-gray-500">Not Yet</span>' !!}
				</p>
			</div>
		@else
			<div class="text-center">
				<h2 class="text-lg font-bold text-gray-700">Anda belum mendaftar di conference.</h2>
				<p class="mt-2 text-sm text-gray-500">Silakan mendaftar untuk mengikuti conference.</p>
			</div>
		@endif
	</x-filament::card>
</x-filament::widget>
