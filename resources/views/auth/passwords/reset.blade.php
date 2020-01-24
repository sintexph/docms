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
         <form method="POST" action="{{ route('password.update') }}">
             @csrf

             <input type="hidden" name="token" value="{{ $token }}">

             <div class="form-group">
                 <label for="email" class="control-label">{{ __('E-Mail Address') }}</label>

                 <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                     value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                 @error('email')
                 <span class="invalid-feedback" role="alert">
                     <strong>{{ $message }}</strong>
                 </span>
                 @enderror
             </div>

             <div class="form-group">
                 <label for="password" class="control-label">{{ __('Password') }}</label>

                 <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                     name="password" required autocomplete="new-password">

                 @error('password')
                 <span class="invalid-feedback" role="alert">
                     <strong>{{ $message }}</strong>
                 </span>
                 @enderror
             </div>

             <div class="form-group">
                 <label for="password-confirm"
                     class="control-label">{{ __('Confirm Password') }}</label>

                <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                         required autocomplete="new-password">
             </div>

           <button type="submit" class="btn btn-primary">
                         {{ __('Reset Password') }}
                     </button>
         </form>
     </div>
 </div>

 @endslot





 @slot('footer')

 @include('layouts.footer')

 @endslot

 @endsintexlayouttop
