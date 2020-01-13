@sintexlayouttop

@slot('page_title')
Error
@endslot



@slot('nav_brand')
<a href="/" class="navbar-brand">
    <img src="{{ asset('img/brand-icon.png') }}" class="brand-logo">
    <b>Helpdesk</b> Philippines
</a>
@endslot

@slot('content')

<div class="error-page">
    <h2 class="headline text-yellow">{{$exception->getStatusCode()}}</h2>
    <div class="error-content">
        <h3>{{$exception->getMessage()}}</h3>
        <p>
            Meanwhile, you may <a href="/">return to home</a> or if you find this as a problem, you can create a ticket <a href="http://it-helpdesk.sportscity.com.ph/user#/create">here</a>.
        </p>
    </div>
</div>

@endslot


@slot('start_script')
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/css/logo.css">
@endslot

@slot('end_script')

@endslot


@slot('footer')

@include('layouts.footer')

@endslot

@endsintexlayouttop
