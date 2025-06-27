<x-filament::page>
	<div class="mx-auto w-full max-w-none space-y-6">

		<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
		<x-filament::card class="w-full rounded-xl border border-gray-200 bg-white p-8 shadow-lg dark:border-gray-800 dark:bg-gray-900">
			<div class="flex flex-col items-center gap-8 md:flex-row">
				<!-- Illustration or Info Section -->
				<div class="hidden flex-1 flex-col items-center justify-center md:flex">
					<img src="{{ asset('storage/' . $payment->participant->conference->banner) }}" alt="Conference Banner"
						class="mb-4 h-64 w-64 rounded-lg object-contain shadow-md">
					<p class="text-center text-sm text-gray-500 dark:text-gray-400">Pastikan data pembayaran Anda sudah benar sebelum melanjutkan proses pembayaran.
					</p>
				</div>
				<!-- Payment Info Section -->
				<div class="flex-1 space-y-4">
					<div class="space-y-2 rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
						<div class="flex items-center justify-between">
							<span class="font-medium text-gray-500 dark:text-gray-400">Invoice</span>
							<span class="font-semibold text-gray-900 dark:text-gray-100">{{ $payment->invoice_code }}</span>
						</div>
						<div class="flex items-center justify-between">
							<span class="font-medium text-gray-500 dark:text-gray-400">Jumlah</span>
							<span class="text-primary-700 dark:text-primary-300 text-lg font-bold">Rp{{ number_format($payment->amount, 0, ',', '.') }}</span>
						</div>
						<div class="flex items-center justify-between">
							<span class="font-medium text-gray-500 dark:text-gray-400">Nama</span>
							<span class="text-gray-900 dark:text-gray-100">{{ $payment->participant->user->name }}</span>
						</div>
						<div class="flex items-center justify-between">
							<span class="font-medium text-gray-500 dark:text-gray-400">Email</span>
							<span class="text-gray-900 dark:text-gray-100">{{ $payment->participant->user->email }}</span>
						</div>
						<div class="flex items-center justify-between">
							<span class="font-medium text-gray-500 dark:text-gray-400">Conference</span>
							<span class="text-gray-900 dark:text-gray-100">{{ $payment->participant->conference->title }}</span>
						</div>
					</div>
					<div class="mt-4">
						<button id="pay-button"
							class="bg-primary-600 hover:bg-primary-700 flex w-full items-center justify-center gap-2 rounded-lg px-6 py-3 font-semibold text-white shadow-lg transition duration-150 ease-in-out">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a5 5 0 00-10 0v2M5 13h14l1 9H4l1-9zm2 0V7a3 3 0 016 0v6" />
							</svg>
							Bayar Sekarang
						</button>
					</div>
				</div>

			</div>
		</x-filament::card>

		<script type="text/javascript">
			document.getElementById('pay-button').addEventListener('click', function() {
				snap.pay('{{ $snapToken }}', {
					onSuccess: function(result) {
						alert('Pembayaran berhasil!');
						console.log(result);
						// Kirim ke backend jika perlu
					},
					onPending: function(result) {
						alert('Menunggu pembayaran...');
						console.log(result);
					},
					onError: function(result) {
						alert('Terjadi kesalahan!');
						console.log(result);
					},
					onClose: function() {
						alert('Anda menutup popup tanpa menyelesaikan pembayaran');
					}
				});
			});
		</script>
	</div>
</x-filament::page>
