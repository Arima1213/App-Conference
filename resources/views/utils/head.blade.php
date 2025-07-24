{{-- filepath: c:\Users\ari\Downloads\conference-app\resources\views\utils\head.blade.php --}}

<!-- =================== SEO META TAGS =================== -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<!-- Primary Meta Tags -->
<title>PPPKMI Conference 2025 - Perhimpunan Profesi Perekam Medis dan Informasi Kesehatan Indonesia</title>
<meta name="title" content="PPPKMI Conference 2025 - Perhimpunan Profesi Perekam Medis dan Informasi Kesehatan Indonesia">
<meta name="description"
	content="Join the premier medical records and health information management conference in Indonesia. Network with professionals, attend expert sessions, and advance your career in healthcare information management.">
<meta name="keywords"
	content="PPPKMI, medical records, health information management, healthcare conference, Indonesia, medical professionals, health data, HIM conference">
<meta name="author" content="PPPKMI - Perhimpunan Profesi Perekam Medis dan Informasi Kesehatan Indonesia">
<meta name="robots" content="index, follow">
<meta name="language" content="Indonesian">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="PPPKMI Conference 2025 - Perhimpunan Profesi Perekam Medis dan Informasi Kesehatan Indonesia">
<meta property="og:description"
	content="Join the premier medical records and health information management conference in Indonesia. Network with professionals, attend expert sessions, and advance your career in healthcare information management.">
<meta property="og:image" content="{{ asset('assets/img/logo-pppkmi-ultimate.svg') }}">
<meta property="og:site_name" content="PPPKMI Conference">
<meta property="og:locale" content="id_ID">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ url()->current() }}">
<meta property="twitter:title" content="PPPKMI Conference 2025 - Perhimpunan Profesi Perekam Medis dan Informasi Kesehatan Indonesia">
<meta property="twitter:description"
	content="Join the premier medical records and health information management conference in Indonesia. Network with professionals, attend expert sessions, and advance your career in healthcare information management.">
<meta property="twitter:image" content="{{ asset('assets/img/logo-pppkmi-ultimate.svg') }}">

<!-- Canonical URL -->
<link rel="canonical" href="{{ url()->current() }}">

<!-- Favicon and Icons -->
<link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}">
<link rel="icon" type="image/svg+xml" href="{{ asset('assets/img/logo-pppkmi-ultimate.svg') }}">
<link rel="apple-touch-icon" href="{{ asset('assets/img/logo-pppkmi-ultimate.svg') }}">

<!-- =================== STRUCTURED DATA =================== -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Event",
  "name": "PPPKMI Conference 2025",
  "description": "Annual conference for medical records and health information management professionals in Indonesia",
  "startDate": "2025-08-15",
  "endDate": "2025-08-17",
  "eventStatus": "https://schema.org/EventScheduled",
  "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
  "location": {
    "@type": "Place",
    "name": "Indonesia Convention Center",
    "address": {
      "@type": "PostalAddress",
      "addressCountry": "ID",
      "addressLocality": "Jakarta"
    }
  },
  "organizer": {
    "@type": "Organization",
    "name": "PPPKMI",
    "description": "Perhimpunan Profesi Perekam Medis dan Informasi Kesehatan Indonesia",
    "url": "{{ url('/') }}",
    "logo": "{{ asset('assets/img/logo-pppkmi-ultimate.svg') }}"
  },
  "offers": {
    "@type": "Offer",
    "url": "{{ url('/participant') }}",
    "price": "500000",
    "priceCurrency": "IDR",
    "availability": "https://schema.org/InStock"
  }
}
</script>

<!-- =================== STYLE =================== -->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/slick.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-grid.css') }}">
<link href="https://use.fontawesome.com/releases/v5.10.1/css/all.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/nice-select.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
{{-- Hapus baris ini jika tidak benar-benar dibutuhkan --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> --}}
