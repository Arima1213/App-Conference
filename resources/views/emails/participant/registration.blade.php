<x-mail::message>
	# Welcome to {{ $conference->title }}! 🎉

	Dear **{{ $user->name }}**,

	Thank you for registering for **{{ $conference->title }}**! Your registration has been successfully processed.

	## Registration Details

	<x-mail::panel>
		**Participant Code:** {{ $participant->participant_code }}
		**Conference:** {{ $conference->title }}
		**Institution:** {{ $participant->educationalInstitution->nama_pt ?? 'Not specified' }}
		**Phone:** {{ $participant->phone }}
		**Status:** {{ ucfirst($participant->status) }}
	</x-mail::panel>

	## What's Next?

	1. **Complete Your Payment** - Please proceed to complete your conference payment to secure your spot.
	2. **Check Your Dashboard** - Access your participant dashboard for important updates and information.
	3. **Stay Updated** - We'll send you important conference updates via email.

	<x-mail::button :url="$paymentUrl">
		Complete Payment Now
	</x-mail::button>

	## Conference Information

	@if ($conference->start_date && $conference->end_date)
		**Date:** {{ $conference->start_date->format('d M Y') }} - {{ $conference->end_date->format('d M Y') }}
	@endif
	@if ($conference->location)
		**Location:** {{ $conference->location }}
	@endif

	## Need Help?

	If you have any questions or need assistance, please don't hesitate to contact our support team.

	Best regards,
	**{{ config('app.name') }} Team**

	---

	*This is an automated message. Please do not reply to this email.*

</x-mail::message>
