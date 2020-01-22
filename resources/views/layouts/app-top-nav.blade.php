@sintexlayouttop

@slot('page_title')
DMS - @yield('title','Document Management System')
@endslot

@slot('header_tools')
@yield('header_tools')
@endslot


@slot('nav_brand')



<a href="/" class="navbar-brand">
    <img src="{{ asset('img/brand-icon.png') }}" class="brand-logo">
    <b>Document Management System</b>
</a>

@endslot


@slot('header_title')
@yield('header_title','DMS')
@endslot
@slot('header_title_sm')
@yield('header_title_sm','System information')
@endslot

@slot('navigation')


<div class="collapse navbar-collapse pull-left" id="navbar-collapse">
    <ul class="nav navbar-nav">
        <li class="@yield('nav-1')"><a href="/">Home <span class="sr-only">(current)</span></a></li>
        <li><a href="/?guide=true">Guide</a></li> 
    </ul>


    <ul class="site-visit">
        <li>
            <i class="fa fa-pie-chart" title="Total Visitors" aria-hidden="true"></i>
            <a href="javascript:void(0);" title="Total Visitors">Total Visitors</a>
            <span class="total-visit" title="Total Visitors">{{ SiteVisitHelper::total_visit() }}</span>
        </li>
        <li>
            <i title="Weekly Visitor" class="fa fa-user" aria-hidden="true"></i>
            <a href="javascript:void(0);" title="Weekly Visitor">Weekly Visitor</a>
            <span class="weekly-visit" title="Weekly Visitor">{{ SiteVisitHelper::weekdays_total() }}</span>
        </li>
        <li>
            <i title="Monthly Visitor" class="fa fa-user" aria-hidden="true"></i>
            <a href="javascript:void(0);" title="Monthly Visitor">Monthly Visitor</a>
            <span title="Monthly Visitor" class="monthly-visit">{{ SiteVisitHelper::days_of_month_total() }}</span>
        </li>
    </ul>


</div>


<div class="navbar-custom-menu">
    <ul class="nav navbar-nav">
        @guest
        <li><a href="{{ route('login') }}">Login</a></li>
        @endguest
        @auth

        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                <img src="{{ asset('img/brand-icon.png') }}" class="nav-logo">
                {{ Auth::user()->name }}
            </a>
            <ul class="dropdown-menu">
                <li><a href="{{ route('manage.documents') }}">Manage Documents</a>
                    <span class="glyphicon glyphicon-file"></span>
                </li>
                <li><a href="{{ route('drafts') }}">Drafts</a>
                    <span class="glyphicon glyphicon-edit"></span>
                </li>
                <li><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign
                        Out</a>
                    <span class="glyphicon glyphicon-log-out"></span>
                </li>
            </ul>
        </li>
        <form id="logout-form" action="{{ route('logout') }}" method="post" style="display: none;">
            {{ csrf_field() }}
        </form>
        @endauth
    </ul>
</div>



@endslot

@slot('content')


@yield('content')
@endslot

@slot('breadcrumbs')

@yield('breadcrumbs')

@endslot


@slot('start_script')
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/css/logo.css">
<link rel="stylesheet" href="{{ asset('css/document.css') }}">

@yield('top_script')
@endslot

@slot('end_script')
@yield('bottom_script')
@endslot


@slot('footer')

@include('layouts.footer')

@endslot

@endsintexlayouttop
