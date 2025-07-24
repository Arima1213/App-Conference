<footer>
	<div class="container">
		<div class="row">
			<div class="footer-cont col-12 col-sm-6 col-lg-4">
				<a href="{{ url('/') }}" class="logo">
					<img src="{{ asset('assets/img/logo-pppkmi-ultimate.svg') }}" alt="PPPKMI Logo" class="footer-logo">
				</a>
				<p>Perhimpunan Profesi Perekam Medis dan Informasi Kesehatan Indonesia</p>
				<ul class="footer-contacts">
					<li class="footer-phone">
						<i aria-hidden="true" class="fas fa-phone"></i>
						<a href="tel:+622123456789">+62 21 2345 6789</a>
					</li>
					<li class="footer-email">
						<i aria-hidden="true" class="fas fa-envelope"></i>
						<a href="mailto:info@pppkmi.org">info@pppkmi.org</a>
					</li>
					<li class="footer-address">
						<i aria-hidden="true" class="fas fa-map-marker-alt"></i>
						<span>Jakarta, Indonesia</span>
					</li>
				</ul>
				<div class="footer-copyright">
					<a target="_blank" href="{{ url('/') }}">PPPKMI</a> © {{ date('Y') }}. All Rights Reserved.
				</div>
			</div>
			<div class="footer-item-link col-12 col-sm-6 col-lg-4">
				<div class="footer-link">
					<h5>Conference</h5>
					<ul class="footer-list">
						<li><a href="#about">About Conference</a></li>
						<li><a href="#schedule">Schedule</a></li>
						<li><a href="#speakers">Speakers</a></li>
						<li><a href="#sponsors">Partners</a></li>
						<li><a href="{{ route('filament.participant.auth.register') }}">Registration</a></li>
						<li><a href="#venue">Venue</a></li>
					</ul>
				</div>
				<div class="footer-link">
					<h5>Quick Links</h5>
					<ul class="footer-list">
						<li><a href="{{ route('filament.participant.auth.login') }}">Login</a></li>
						<li><a href="{{ route('filament.participant.pages.payment-page') }}">Payment</a></li>
						<li><a href="#faq">FAQ</a></li>
						<li><a href="#contact">Contact</a></li>
					</ul>
				</div>
			</div>
			<div class="footer-subscribe col-12 col-sm-6 col-lg-4">
				<h5>Stay Connected</h5>
				<p class="footer-subscribe-desc">Get the latest updates about our conference and medical records industry insights.</p>

				<div class="footer-social-links" style="margin-bottom: 20px;">
					<h6>Follow Us</h6>
					<div class="social-icons" style="display: flex; gap: 15px; margin-top: 10px;">
						<a target="_blank" href="https://www.facebook.com/pppkmi" class="social-link" style="color: #4267B2;">
							<i class="fab fa-facebook-f" style="font-size: 20px;"></i>
						</a>
						<a target="_blank" href="https://twitter.com/pppkmi" class="social-link" style="color: #1DA1F2;">
							<i class="fab fa-twitter" style="font-size: 20px;"></i>
						</a>
						<a target="_blank" href="https://www.instagram.com/pppkmi" class="social-link" style="color: #E4405F;">
							<i class="fab fa-instagram" style="font-size: 20px;"></i>
						</a>
						<a target="_blank" href="https://www.linkedin.com/company/pppkmi" class="social-link" style="color: #0077B5;">
							<i class="fab fa-linkedin-in" style="font-size: 20px;"></i>
						</a>
						<a target="_blank" href="https://www.youtube.com/c/pppkmi" class="social-link" style="color: #FF0000;">
							<i class="fab fa-youtube" style="font-size: 20px;"></i>
						</a>
					</div>
				</div>

				<form class="subscribe-form" action="#" method="POST" style="margin-top: 20px;">
					@csrf
					<input class="inp-form" type="email" name="subscribe" placeholder="Enter your email address" required>
					<button class="btn-form" type="submit"><i class="fas fa-paper-plane"></i></button>
				</form>
				<p style="font-size: 12px; margin-top: 10px; color: #888;">
					By subscribing you agree to our <a href="#privacy" target="_blank" style="color: #fff; text-decoration: underline;">Privacy Policy</a> and
					<a href="#terms" target="_blank" style="color: #fff; text-decoration: underline;">Terms of Service</a>
				</p>
			</div>
		</div>

		<!-- Footer Bottom -->
		<div class="row footer-bottom">
			<div class="col-12 text-center">
				<p class="footer-bottom-text">
					Powered by <strong>PPPKMI Conference System</strong> |
					<a href="#support" class="footer-bottom-link">Technical Support</a> |
					<a href="#sitemap" class="footer-bottom-link">Sitemap</a>
				</p>
			</div>
		</div>
	</div>
</footer>
