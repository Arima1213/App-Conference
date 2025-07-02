<!DOCTYPE html>
<html lang="zxx">

<head>
	<meta charset="UTF-8">
	<title>Conference</title>
	<!-- =================== META =================== -->
	<meta name="keywords" content="">
	<meta name="description" content="">
	<meta name="format-detection" content="telephone=no">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	@include('utils.head')
</head>

<body id="conference-page" style="background-image: url(assets/img/conference_bg.svg);">
	@if ($isHaveConference)
		<!-- =============== PRELOADER =============== -->
		@include('utils.preloader')
		<!-- ============== PRELOADER END ============== -->
		<!-- ================= HEADER ================= -->
		@include('utils.navbar')
		<!-- =============== HEADER END =============== -->

		<!-- =========== S-CONFERENCE-banner =========== -->
		@include('utils.banner')
		<!-- ========= S-CONFERENCE-banner END ========= -->

		<!-- =========== S-CONFERENCE-COUNTER =========== -->
		@include('utils.counter')
		<!-- ========= S-CONFERENCE-COUNTER END ========= -->

		<!-- ============ S-KEYNOTE-SPEAKER ============ -->
		@include('utils.keynoteSpeaker')
		<!-- ========== S-KEYNOTE-SPEAKER END ========== -->

		<!-- ============ IMPORTANT DATE ============ -->
		@include('utils.importantDate')
		<!-- ========== IMPORTANT DATE END ========== -->

		<!-- ============ SPEAKER & SCHEDULE ============ -->
		@include('utils.speakerSchedule')
		<!-- ========== SPEAKER & SCHEDULE END ========== -->

		<!--================= S-PRICING-TABLE =================-->
		@include('utils.pricing')
		<!--=============== S-PRICING-TABLE END ===============-->

		<!-- =============== CONFERENCE-MAP =============== -->
		@include('utils.map')
		<!-- ============= CONFERENCE-MAP END ============= -->

		<!--=================== Sponsors ===================-->
		@include('utils.sponsor')
		<!--================= SPONSORS END =================-->

		<!--==================== FOOTER ====================-->
		@include('utils.footer')
		<!--================== FOOTER END ==================-->
	@else
		<div class="container my-5 text-center">
			<h2>Tidak ada data conference tersedia.</h2>
		</div>
	@endif

	<!--=================== TO TOP ===================-->
	<a class="to-top" href="#home">
		<i class="fa fa-angle-double-up" aria-hidden="true"></i>
	</a>
	<!--================= TO TOP END =================-->

	<!--=================== SCRIPT	===================-->
	@include('utils.script')
	<!--================= SCRIPT END ==================-->
</body>

</html>
