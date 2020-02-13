@sintexemail

@slot('content')

<h2>Hello {{$user->name}}!</h2>
<p>You are receiving this email because we received a password reset request for your account.</p>

<p><a href="{{$actionUrl}}">Click here to reset your password</a></p>

<p>
<strong>Username: </strong> {{$user->username}}<br>
<strong>Password: </strong> <i>Follow the reset link</i>
</p>

<p>This password reset link will expire in 60 minutes.<br>
    If you did not request a password reset, no further action is required.</p>

<p>
    <small>If you’re having trouble clicking the "Reset Password" button, copy and paste the URL below into your web
        browser: <a href="{{$actionUrl}}">{{$actionUrl}}</a></small>
</p>
@endslot

@slot('url',config('app.url'))
@slot('brand','Document Management System')

@endsintexemail
