 @sintexlayouttop


 @slot('page_title','Document Management Password Reset')
 @slot('navigation')
 @endslot
 @slot('nav_brand')
 <a href="/" class="navbar-brand">
     <img src="{{ asset('img/brand-icon.png') }}" class="brand-logo">
     <b>Document Management</b> System
 </a>
 @endslot


 @slot('content')



 <div class="box box-solid">
     <div class="box-header">
         <h3 class="box-title">{{ __('Reset Password') }}</h3>
     </div>

     <div class="box-body">
         @if (session('status'))
         <div class="alert alert-success" role="alert">
             {{ session('status') }}
         </div>
         @endif

         <form method="POST" action="{{ route('password.email') }}">
             @csrf

             <div class="form-group">
                 <label for="email" class="control-label">{{ __('Please provide your company valid E-Mail Address') }}</label>
                 <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                     value="{{ old('email') }}" required autocomplete="email" autofocus>

                 @error('email')
                 <span class="invalid-feedback" role="alert">
                     <strong>{{ $message }}</strong>
                 </span>
                 @enderror
             </div>

             <button type="submit" class="btn btn-primary">
                 {{ __('Send Password Reset Link') }}
             </button>
         </form>
     </div>
 </div>


 @endslot





 @slot('footer')

 @include('layouts.footer')

 @endslot

 @endsintexlayouttop
