<x-filament::mail>
	# Pembayaran Berhasil

	Halo {{ $payment->participant->user->name }},

	Pembayaran untuk konferensi **{{ $payment->participant->conference->title }}** dengan invoice **{{ $payment->invoice_code }}** telah berhasil.

	Jumlah: **Rp{{ number_format($payment->amount, 0, ',', '.') }}**

	Terima kasih atas partisipasi Anda.

	<x-filament::button :url="url('/participant')">
		Lihat Status Peserta
	</x-filament::button>

	Salam,<br>
	{{ config('app.name') }}
</x-filament::mail>
