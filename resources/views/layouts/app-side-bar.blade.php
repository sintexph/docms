@sintexlayoutside




@slot('page_title')
DMS - @yield('title','Document Management System')
@endslot




@slot('header_title')
@yield('header_title','DMS')
@endslot
@slot('header_title_sm')
@yield('header_title_sm','System information')
@endslot

 

@slot('breadcrumbs')

@yield('breadcrumbs')

@endslot



@slot('nav_brand')
<span class="logo-mini"><b>D</b>MS</span>
<span class="logo-lg"><b>Document</b> Management</span>
@endslot

@slot('navigation')


<div  class="navbar-custom-menu app-profile">
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
              
                <li>
                    <a href="{{ route('drafts') }}">Drafts</a>
                    <span class="glyphicon glyphicon-edit pull-right"></span>
                </li>
                <li><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign
                        Out</a><span class="glyphicon glyphicon-log-out pull-right"></span></li>
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

@slot('sidebar')



<div class="user-panel">
    <div class="pull-left image">
        <img src="{{ asset('img/brand-icon.png') }}" class="img-circle" alt="User Image">
    </div>
    <div class="pull-left info">
        <p>{{ Auth::user()->name }}</p>
        <a href="#"><i class="fa fa-circle text-success"></i> {{ auth()->user()->position }}</a>
    </div>
</div>


<ul class="sidebar-menu" data-widget="tree">

    <li class="header">MAIN NAVIGATION</li>

    @auth
    <li class="@yield('nav-2')">
        <a href="{{ route('manage.documents') }}">
            <i class="fa fa-files-o" aria-hidden="true"></i> <span>Manage Documents</span>
        </a>
    </li>


    <li class="@yield('nav-8')">
        <a href="{{ route('drafts') }}">
            <i class="fa fa-pencil-square-o" aria-hidden="true"></i> <span> Drafts</span>
        </a>
    </li>

    <li class="@yield('nav-6')">
        <a href="{{ route('manage.documents.create') }}">
            <i class="fa fa-file" aria-hidden="true"></i> <span>Create Document</span>
        </a>
    </li>

    @if(auth()->user()->perm_reviewer==true)

    <li class="@yield('nav-3')">
        <a href="{{ route('for_review') }}">
            <i class="fa fa-eye" aria-hidden="true"></i> <span>Reviews</span>
        </a>
    </li>
    @endif

    @if(auth()->user()->perm_approver==true)

    <li class="@yield('nav-4')">
        <a href="{{ route('for_approval') }}">
            <i class="fa fa-thumbs-up" aria-hidden="true"></i> <span>Approvals</span>
        </a>
    </li>

    @endif


    @if(auth()->user()->perm_administrator==true)

    <li class="@yield('nav-7')">
        <a href="{{ route('accounts') }}">
            <i class="fa fa-users" aria-hidden="true"></i> <span>Manage Accounts</span>
        </a>
    </li>


    <li class="@yield('nav-9')">
        <a href="{{ route('trashed') }}">
            <i class="fa fa-trash" aria-hidden="true"></i> <span>Trashed Documents</span>
        </a>
    </li>



    <li class="treeview @yield('nav-5')">
        <a href="#">
            <i class="fa fa-cog" aria-hidden="true"></i> <span>Manage Data</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li class="@yield('nav-5-1')">
                <a href="{{ route('systems') }}"><i class="fa fa-circle-o"></i> Systems</a>
            </li>
            <li class="@yield('nav-5-2')">
                <a href="{{ route('sections') }}"><i class="fa fa-circle-o"></i> Sections</a>
            </li>
            <li class="@yield('nav-5-3')">
                <a href="{{ route('categories') }}"><i class="fa fa-circle-o"></i> Categories</a>
            </li>
        </ul>
    </li>


    <li class="@yield('nav-10')">
        <a href="{{ route('traffic') }}">
            <i class="fa fa-line-chart" aria-hidden="true"></i> <span>Traffic Monitoring</span>

        </a>
    </li>



    @endif


    @endauth




</ul>

@endslot




@slot('start_script')
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/loading-modal/css/jquery.loadingModal.min.css">
<link rel="stylesheet" href="http://cdn.sportscity.com.ph/css/logo.css">
<link rel="stylesheet" href="{{ asset('css/document.css') }}">


@yield('top_script')
@endslot

@slot('end_script')
<script src="http://cdn.sportscity.com.ph/loading-modal/js/jquery.loadingModal.js"></script>
@yield('bottom_script')
@endslot


@slot('footer')

@include('layouts.footer')

@endslot



@endsintexlayoutside
