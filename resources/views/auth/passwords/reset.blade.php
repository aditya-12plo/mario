@extends('layouts.app')
@section('content')


<form class="form-signin" method="POST" action="{{ route('password.update') }}">
                        @csrf

    <input type="hidden" name="token" value="{{ $token }}">
          <div class="panel periodic-login">
              <div class="panel-body text-center">
                  <p class="atomic-mass">{{ __('Reset Password') }}</p>

                  <i class="icons icon-arrow-down"></i>
                  
                    <div class="form-group form-animate-text @error('email') form-animate-error @enderror" style="margin-top:40px !important;">
                        <input id="email" type="email" class="form-text @error('email') error @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                        <span class="bar"></span>
                        <label>Email</label>
                        @error('email')
                            <em class="error"><strong style="color:red;">{{ $message }}</strong></em>
                        @enderror
                       
                    </div>

                    <div class="form-group form-animate-text @error('password') form-animate-error @enderror" style="margin-top:40px !important;">
                        <input id="password" type="password" class="form-text @error('password') error @enderror" name="password" required autocomplete="new-password">
                        <span class="bar"></span>
                        <label>Password</label>
                        @error('password')
                            <em class="error"><strong style="color:red;">{{ $message }}</strong></em>
                        @enderror
                       
                    </div>

                    <div class="form-group form-animate-text" style="margin-top:40px !important;">
                        <input id="password-confirm" type="password" class="form-text" name="password_confirmation" required autocomplete="new-password">
                        <span class="bar"></span>
                        <label>{{ __('Confirm Password') }}</label>
                        @error('password_confirmation')
                            <em class="error"><strong style="color:red;">{{ $message }}</strong></em>
                        @enderror
                    </div>

                  <input type="submit" class="btn col-md-12" value=" {{ __('Reset Password') }}"/>
              </div>
                <div class="text-center" style="padding:5px;">
                    <a href="/">Login </a>
                </div>
          </div>
        </form>





@endsection
