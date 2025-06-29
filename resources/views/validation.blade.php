<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Validasi Kehadiran</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
	<style>
		.card-body {
			font-family: 'Courier New', monospace;
		}
	</style>

	<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light container py-5">
		<div class="w-100 mx-2" style="max-width: 480px;">
			<div class="card border-dark border shadow-sm" style="border-style: dashed;">
				<div class="card-body text-center">
					<img src="{{ asset('images/logo-besar.png') }}" alt="Logo" class="mb-3" style="width: 60px;">

					<h5 class="fw-bold">{{ $participant->conference->title ?? 'Conference' }}</h5>
					<p class="text-muted mb-2" style="font-size: 0.9rem;">
						{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
					</p>

					<hr class="my-2">

					<div class="mb-3 text-start" style="font-size: 0.95rem;">
						<strong>Nama:</strong> {{ $participant->user->name }} <br>
						<strong>Universitas:</strong> {{ $participant->educationalInstitution->nama_pt ?? '-' }} <br>
						<strong>NIK:</strong> {{ $participant->nik ?? '-' }} <br>
						<strong>Paper:</strong> {{ $participant->paper_title ?? '-' }} <br>
						<strong>Status:</strong> {!! $participant->status === 'arrived' ? '<span class="text-success">Hadir</span>' : '<span class="text-danger">Belum Hadir</span>' !!}
					</div>

					@if (auth()->check() && filament()->getPanel()->getId() === 'manage')
						@if ($participant->status !== 'arrived')
							<form action="{{ route('participant.qr.validate', $participant->id) }}" method="POST">
								@csrf
								<button type="submit" class="btn btn-success w-100 fw-semibold rounded-pill py-2">
									<i class="bi bi-check-circle me-2"></i> Tandai Hadir Sekarang
								</button>
							</form>
						@else
							<div class="alert alert-success mt-3">Peserta sudah hadir.</div>
						@endif
					@else
						<div class="alert alert-warning mt-3">
							Login ke panel <strong>manage</strong> untuk validasi kehadiran.
							<br>
							<a href="{{ url('/manage/login') }}" class="btn btn-primary btn-sm rounded-pill mt-2 px-3">
								<i class="bi bi-box-arrow-in-right me-1"></i> Login Manage
							</a>
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>

	@if (session('success'))
		<script>
			Swal.fire({
				icon: 'success',
				title: 'Berhasil',
				text: '{{ session('success') }}',
			});
		</script>
	@endif

	@if (session('error'))
		<script>
			Swal.fire({
				icon: 'error',
				title: 'Gagal',
				text: '{{ session('error') }}',
			});
		</script>
	@endif
</body>

</html>
