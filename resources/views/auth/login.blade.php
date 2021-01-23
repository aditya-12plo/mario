@extends('layouts.app')

@section('content')


<form class="form-signin" method="POST" action="{{ route('login') }}">
@csrf

          <div class="panel periodic-login">
              <div class="panel-body text-center">
                  <p class="atomic-mass">BD</p>
                  <p class="element-name">System</p>

                  <i class="icons icon-arrow-down"></i>
                  
                    <div class="form-group form-animate-text @error('email') form-animate-error @enderror" style="margin-top:40px !important;">
                        <input id="email" type="email" class="form-text @error('email') error @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        <span class="bar"></span>
                        <label>Email</label>
                        @error('email')
                            <em id="validate_firstname-error" class="error"><strong style="color:red;">{{ $message }}</strong></em>
                        @enderror
                       
                    </div>

                    <div class="form-group form-animate-text @error('password') form-animate-error @enderror" style="margin-top:40px !important;">
                        <input id="password" type="password" class="form-text @error('password') error @enderror" name="password" required autocomplete="current-password">
                        <span class="bar"></span>
                        <label>Password</label>
                        @error('password')
                            <em id="validate_firstname-error" class="error"><strong style="color:red;">{{ $message }}</strong></em>
                        @enderror
                       
                    </div>

                
                  <label class="pull-left">
                  <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember Me') }}
                  </label>
                  <input type="submit" class="btn col-md-12" value="SignIn"/>
              </div>
                <div class="text-center" style="padding:5px;">
                    <a href="{{ route('password.request') }}">Forgot Password </a>
                </div>
          </div>
        </form>




@endsection
