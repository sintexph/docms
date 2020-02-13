@sintexemail
@slot('brand','Document Management System')
@slot('url','http://docms.sportscity.com.ph')
@slot('content')
<p>Hi {{ $receiver }},</p>

<p>
A new user registered an account to the system, you can activate the account from the <strong>account management page</strong>.
</p>

<br>
<ul style="list-style: none; margin-left: 0; padding-left: 0;">
	<li>Name: {{ $registered_user->name }}</li>
	<li>Email: <a href="mailto{{ $registered_user->email }}:">{{ $registered_user->email }}</a></li>
	<li>Position: {{ $registered_user->position }}</li>
</ul>

@endslot
@endsintexemail
