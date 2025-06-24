<header class="marathon-header-fixed header-fixed">
	<a href="#" class="nav-btn">
		<span></span>
		<span></span>
		<span></span>
	</a>
	<div class="top-panel">
		<div class="container">
			<a href="" class="logo"><img src="{{ asset('assets/img/logo-white.svg') }}" alt="logo"></a>
			<ul class="social-list">
				<li><a target="_blank" href=""><i class="fab fa-facebook-f"></i></a></li>
				<li><a target="_blank" href=""><i class="fab fa-twitter"></i></a></li>
				<li><a target="_blank" href=""><i class="fab fa-instagram"></i></a></li>
				<li><a target="_blank" href=""><i class="fab fa-youtube"></i></a></li>
			</ul>
		</div>
	</div>
	<div class="header-nav">
		<div class="container">
			<div class="header-nav-cover">
				<nav class="nav-menu menu">
					<ul class="nav-list">
						<li><a href=".s-conference-slider">Conference</a></li>
						<li><a href="#about">about</a></li>
						<li><a href="#our-speaker">our speaker</a></li>
						<li><a href="#schedule">schedule</a></li>
						<li><a href="#pricing">pricing</a></li>
						<li><a href="#location">location</a></li>
						<li><a href="#sponsors">sponsors</a></li>

						{{-- <li class="dropdown">
							<a href="#">pages <i class="fa fa-angle-down" aria-hidden="true"></i></a>
							<ul>
								<li><a href="conference-team.html">Conference Team</a></li>
								<li><a href="dance-team.html">Dance Teame</a></li>
								<li><a href="blog.html">Blog</a></li>
								<li><a href="page-error.html">Page Error 404</a></li>
							</ul>
						</li> --}}
					</ul>
				</nav>
				<a href="{{ url('/participant') }}" class="btn btn-white"><span>Register Now</span></a>
			</div>
		</div>
	</div>
</header>
