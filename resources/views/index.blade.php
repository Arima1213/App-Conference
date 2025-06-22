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

	<!-- ============ SPEAKER & SCHEDULE ============ -->
	@include('utils.speakerSchedule')
	<!-- ========== SPEAKER & SCHEDULE END ========== -->

	<!--================= S-PRICING-TABLE =================-->
	@include('utils.pricing')
	<!--=============== S-PRICING-TABLE END ===============-->

	<!--================== S-BUY-TICKET ==================-->
	{{-- <section id="register" class="s-buy-ticket">
		<div class="container">
			<h2 class="title-conference"><span>Buy ticket</span></h2>
			<div class="row">
				<div class="col-md-6">
					<div class="buy-ticket-left">
						<h5>Od tempor incididunt ut labore et dolore magna</h5>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed od tempor incididunt ut labore et dolore magna aliqua.</p>
						<div class="ticket-contact-cover">
							<div class="ticket-contact-item">
								<h5>Event organizer</h5>
								<ul>
									<li><span>Name:</span>Eugene Scott</li>
									<li><span>Phone:</span><a href="tel:+343234345">+3 432 343 45</a></li>
									<li><span>Email:</span><a href="mailto:rovadex@gmail.com">rovadex@gmail.com</a></li>
								</ul>
							</div>
							<div class="ticket-contact-item">
								<h5>Support</h5>
								<ul>
									<li><span>Name:</span>Eugene Scott</li>
									<li><span>Phone:</span><a href="tel:+343234345">+3 432 343 45</a></li>
									<li><span>Email:</span><a href="mailto:rovadex@gmail.com">rovadex@gmail.com</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="buy-ticket-form">
						<form id='contactform' action="https://html.rovadex.com/html-giner/assets/php/contact.php" name="contactform">
							<h5>Information</h5>
							<ul class="form-cover">
								<li class="inp-cover inp-name"><input id="name" type="text" name="your-name" placeholder="Name"></li>
								<li class="inp-cover inp-email"><input id="email" type="email" name="your-email" placeholder="E-mail"></li>
								<li class="inp-cover inp-profession">
									<select class="nice-select">
										<option selected="selected" disabled>Profession</option>
										<option>Designer</option>
										<option>Developer</option>
										<option>QA</option>
									</select>
								</li>
								<li class="inp-cover inp-price">
									<select class="nice-select">
										<option selected="selected" disabled>Ticket Type</option>
										<option value="130$">Basic</option>
										<option value="140$">Ultimate</option>
										<option value="150$">Premium</option>
									</select>
								</li>
								<li class="pay-method">
									<h5>Payment method</h5>
									<div class="pay-item">
										<input type="radio" name="pay-1" checked value="credit card">
										<span></span>
										<p>credit card</p>
									</div>
									<div class="pay-item">
										<input type="radio" name="pay-1" value="payPal">
										<span></span>
										<p>payPal</p>
									</div>
								</li>
							</ul>
							<div class="checkbox-wrap">
								<div class="checkbox-cover">
									<input type="checkbox">
									<p>By using this form you agree with the storage and handling of your data by this website.</p>
								</div>
							</div>
							<div class="price-final">
								<span>price:</span>
								<div class="price-final-text">130$</div>
							</div>
							<div class="btn-form-cover">
								<button id="#submit" type="submit" class="btn"><span>Buy now</span></button>
							</div>
						</form>
						<div id="message"></div>
					</div>
				</div>
			</div>
		</div>
	</section> --}}
	<!--================ S-BUY-TICKET END ================-->

	<!--================ CONFERENCE NEWS ================-->
	{{-- <section id="news" class="s-conference-news" style="background-image: url(assets/img/bg-news.jpg);">
		<div class="conference-news-container">
			<h2 class="title-conference title-conference-white"><span>Our news</span></h2>
			<div class="conference-news-slider">
				<div class="conference-news-slide">
					<div class="conference-news-item">
						<div class="conference-post-thumbnail">
							<a href="single-blog.html"><img src="assets/img/post-1-home2.jpg" alt="img"></a>
						</div>
						<div class="date"><span>Oct</span>28,2019</div>
						<div class="conference-post-content">
							<h4><a href="single-blog.html">Business strategy</a></h4>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed</p>
							<div class="conference-post-meta"><i class="fas fa-user" aria-hidden="true"></i>By <a href="blog.html">Samson peters</a>
							</div>
							<a href="single-blog.html" class="btn"><span>Read More</span></a>
						</div>
					</div>
				</div>
				<div class="conference-news-slide">
					<div class="conference-news-item">
						<div class="conference-post-thumbnail">
							<a href="single-blog.html"><img src="assets/img/post-2-home2.jpg" alt="img"></a>
						</div>
						<div class="date"><span>Oct</span>21,2019</div>
						<div class="conference-post-content">
							<h4><a href="single-blog.html">Sed ut perspiciatis</a></h4>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed</p>
							<div class="conference-post-meta"><i class="fas fa-user" aria-hidden="true"></i>By <a href="blog.html">Samson peters</a>
							</div>
							<a href="single-blog.html" class="btn"><span>Read More</span></a>
						</div>
					</div>
				</div>
				<div class="conference-news-slide">
					<div class="conference-news-item">
						<div class="conference-post-thumbnail">
							<a href="single-blog.html"><img src="assets/img/post-3-home2.jpg" alt="img"></a>
						</div>
						<div class="date"><span>Oct</span>18,2019</div>
						<div class="conference-post-content">
							<h4><a href="single-blog.html">Architecto beatae</a></h4>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed</p>
							<div class="conference-post-meta"><i class="fas fa-user" aria-hidden="true"></i>By <a href="blog.html">Samson peters</a>
							</div>
							<a href="single-blog.html" class="btn"><span>Read More</span></a>
						</div>
					</div>
				</div>
				<div class="conference-news-slide">
					<div class="conference-news-item">
						<div class="conference-post-thumbnail">
							<a href="single-blog.html"><img src="assets/img/post-4-home2.jpg" alt="img"></a>
						</div>
						<div class="date"><span>Oct</span>12,2019</div>
						<div class="conference-post-content">
							<h4><a href="single-blog.html">Business strategy</a></h4>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed</p>
							<div class="conference-post-meta"><i class="fas fa-user" aria-hidden="true"></i>By <a href="blog.html">Samson peters</a>
							</div>
							<a href="single-blog.html" class="btn"><span>Read More</span></a>
						</div>
					</div>
				</div>
				<div class="conference-news-slide">
					<div class="conference-news-item">
						<div class="conference-post-thumbnail">
							<a href="single-blog.html"><img src="assets/img/post-2-home2.jpg" alt="img"></a>
						</div>
						<div class="date"><span>Oct</span>10,2019</div>
						<div class="conference-post-content">
							<h4><a href="single-blog.html">Sed ut perspiciatis</a></h4>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed</p>
							<div class="conference-post-meta"><i class="fas fa-user" aria-hidden="true"></i>By <a href="blog.html">Samson peters</a>
							</div>
							<a href="single-blog.html" class="btn"><span>Read More</span></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section> --}}
	<!--============== CONFERENCE NEWS END ==============-->

	<!-- =============== CONFERENCE-MAP =============== -->
	@include('utils.map')
	<!-- ============= CONFERENCE-MAP END ============= -->

	<!--=================== Sponsors ===================-->
	@include('utils.sponsor')
	<!--================= SPONSORS END =================-->

	<!--==================== FOOTER ====================-->
	@include('utils.footer')
	<!--================== FOOTER END ==================-->

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
