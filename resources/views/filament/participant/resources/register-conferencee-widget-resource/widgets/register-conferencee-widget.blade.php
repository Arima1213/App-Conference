<x-filament-widgets::widget>
	<style>
		.conference-flex-container {
			display: flex;
			gap: 2rem;
			align-items: stretch;
		}

		.conference-image-col {
			flex: 0 0 320px;
			max-width: 350px;
		}

		.conference-info-col {
			flex: 1;
			display: flex;
			flex-direction: column;
			justify-content: center;
		}

		.register-btn {
			display: inline-block;
			background: #f59e42;
			color: #fff;
			padding: 0.75rem 2rem;
			border-radius: 0.5rem;
			font-weight: 600;
			font-size: 1.125rem;
			transition: background 0.2s;
			text-decoration: none;
			box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
			margin-top: 1.5rem;
		}

		.register-btn:hover {
			background: #d97706;
		}

		.registered-btn {
			display: inline-block;
			background: #22c55e;
			color: #fff;
			padding: 0.75rem 2rem;
			border-radius: 0.5rem;
			font-weight: 600;
			font-size: 1.125rem;
			text-decoration: none;
			box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
			margin-top: 1.5rem;
			cursor: default;
			opacity: 0.85;
		}

		@media (max-width: 900px) {
			.conference-image-col {
				max-width: 220px;
				flex-basis: 180px;
			}
		}

		@media (max-width: 640px) {
			.conference-flex-container {
				flex-direction: column;
				gap: 1rem;
			}

			.conference-image-col,
			.conference-info-col {
				max-width: 100%;
				flex-basis: auto;
			}

			.register-btn {
				width: 100%;
				text-align: center;
			}
		}
	</style>
	<x-filament::section>
		@if ($conference)
			<div class="conference-flex-container">
				<div class="conference-image-col">
					<div class="relative w-full overflow-hidden rounded-xl shadow-lg">
						<img src="{{ asset('storage/' . $conference->banner) }}" alt="{{ $conference->title }}" class="h-64 w-full object-cover">
					</div>
				</div>
				<div class="conference-info-col">
					<div class="conference-title text-white-600 dark:text-dark-400 mb-2 text-xl font-bold">
						{{ $conference->title }}
					</div>
					<div class="conference-description text-gray-700 dark:text-gray-200">
						{{ $conference->description }}
					</div>
					@if (!$isRegistered)
						<a href="{{ route('filament.participant.resources.participants.create', ['conference' => $encryptedConferenceId]) }}" class="register-btn">
							Register Now
						</a>
					@else
						<a class="registered-btn">
							Registered
						</a>
					@endif
				</div>
			</div>
		@elseif (!empty($noActiveConferenceMessage))
			<div class="p-6 text-center text-gray-500 dark:text-gray-400">
				{{ $noActiveConferenceMessage }}
			</div>
		@else
			<div class="p-6 text-center text-gray-500 dark:text-gray-400">
				No active conference available.
			</div>
		@endif
	</x-filament::section>
</x-filament-widgets::widget>
