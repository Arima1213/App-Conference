<div class="fi-wi-quick-actions">
	<x-filament::section>
		<x-slot name="heading">
			<div class="flex items-center gap-2">
				<x-heroicon-o-bolt class="h-5 w-5" />
				Quick Actions
			</div>
		</x-slot>

		<div class="grid grid-cols-2 gap-4 md:grid-cols-4">
			@foreach ($actions as $action)
				<a href="{{ $action['url'] }}"
					class="hover:border-primary-500 hover:bg-primary-50 dark:hover:border-primary-400 dark:hover:bg-primary-900/20 group flex flex-col items-center rounded-lg border border-gray-200 p-4 transition-all duration-200 dark:border-gray-700">
					<div
						class="bg-primary-100 group-hover:bg-primary-200 dark:bg-primary-900/50 dark:group-hover:bg-primary-800/50 mb-3 flex h-12 w-12 items-center justify-center rounded-full">
						@switch($action['icon'])
							@case('heroicon-o-user-plus')
								<x-heroicon-o-user-plus class="text-primary-600 dark:text-primary-400 h-6 w-6" />
							@break

							@case('heroicon-o-qr-code')
								<x-heroicon-o-qr-code class="text-primary-600 dark:text-primary-400 h-6 w-6" />
							@break

							@case('heroicon-o-banknotes')
								<x-heroicon-o-banknotes class="text-primary-600 dark:text-primary-400 h-6 w-6" />
							@break

							@case('heroicon-o-building-office-2')
								<x-heroicon-o-building-office-2 class="text-primary-600 dark:text-primary-400 h-6 w-6" />
							@break

							@default
								<x-heroicon-o-cog class="text-primary-600 dark:text-primary-400 h-6 w-6" />
						@endswitch
					</div>
					<span class="text-center text-sm font-medium text-gray-900 dark:text-white">{{ $action['label'] }}</span>
				</a>
			@endforeach
		</div>
	</x-filament::section>
</div>
