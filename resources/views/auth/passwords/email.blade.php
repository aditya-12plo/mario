@extends('layouts.app')

@section('content')



<form class="form-signin" method="POST" action="{{ route('password.email') }}">
@csrf

<div class="panel periodic-login">
              <div class="panel-body text-center">
                  <p class="atomic-mass">{{ __('Reset Password') }}</p>

                  <i class="icons icon-arrow-down"></i>
                  @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="form-group form-animate-text @error('email') form-animate-error @enderror" style="margin-top:40px !important;">
                        <input id="email" type="email" class="form-text @error('email') error @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        <span class="bar"></span>
                        <label>{{ __('E-Mail Address') }}</label>
                        @error('email')
                            <em id="validate_firstname-error" class="error"><strong style="color:red;">{{ $message }}</strong></em>
                        @enderror
                       
                    </div>

                    

                
                  
                  <input type="submit" class="btn col-md-12" value=" {{ __('Send Password Reset Link') }}"/>
              </div>
                <div class="text-center" style="padding:5px;">
                    <a href="/">Login </a>
                </div>
          </div>


</form>


@endsection
