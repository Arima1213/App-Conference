<div class="space-y-4">
	<div class="grid grid-cols-2 gap-4">
		<div>
			<label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Invoice Code</label>
			<p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $payment->invoice_code }}</p>
		</div>
		<div>
			<label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
			<p class="mt-1 text-sm">
				<span
					class="@if ($payment->payment_status === 'paid') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                    @elseif($payment->payment_status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                    @elseif($payment->payment_status === 'failed') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                    @elseif($payment->payment_status === 'challenge') bg-orange-100 text-orange-800 dark:bg-orange-800 dark:text-orange-100
                    @else bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100 @endif inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">
					{{ ucfirst($payment->payment_status) }}
				</span>
			</p>
		</div>
		<div>
			<label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount</label>
			<p class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100">
				Rp {{ number_format($payment->amount, 0, ',', '.') }}
			</p>
		</div>
		<div>
			<label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Method</label>
			<p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
				{{ $payment->payment_method ?: 'Not specified' }}
			</p>
		</div>
		@if ($payment->va_number)
			<div>
				<label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Virtual Account / Card</label>
				<p class="mt-1 font-mono text-sm text-gray-900 dark:text-gray-100">{{ $payment->va_number }}</p>
			</div>
		@endif
		@if ($payment->paid_at)
			<div>
				<label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Paid At</label>
				<p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
					{{ $payment->paid_at->format('d M Y, H:i') }}
				</p>
			</div>
		@endif
		<div>
			<label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Token Status</label>
			<p class="mt-1 text-sm">
				@if ($payment->hasValidSnapToken())
					<span
						class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-800 dark:text-green-100">
						Valid Token Available
					</span>
				@else
					<span
						class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
						New Token Required
					</span>
				@endif
			</p>
		</div>
		@if ($payment->snap_token_created_at)
			<div>
				<label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Token Created At</label>
				<p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
					{{ $payment->snap_token_created_at->format('d M Y, H:i') }}
				</p>
			</div>
		@endif
	</div>

	<div class="mt-6 border-t border-gray-200 pt-4 dark:border-gray-700">
		<div>
			<label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Conference</label>
			<p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $payment->participant->conference->title }}</p>
		</div>
		<div class="mt-2">
			<label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Participant</label>
			<p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $payment->participant->user->name }}</p>
		</div>
	</div>
</div>
