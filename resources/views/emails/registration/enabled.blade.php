@sintexemail
@slot('brand','Document Management System')
@slot('url',config('app.url'))
@slot('content')
<p>Hi {{ $receiver }},</p>

<p>You account is now <strong>activated</strong>, you can now login to the system using the link below.</p>
<a href="{{ config('app.url') }}">{{ config('app.url') }}</a>
 
@endslot
@endsintexemail
