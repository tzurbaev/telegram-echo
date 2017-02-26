@extends('layouts.auth')

@section('content')
<h1>{{ trans('auth.login.heading') }}</h1>
<form action="" class="ui form{{ $errors->count() > 0 ? ' error' : '' }}" method="POST">
  {{ csrf_field() }}

  <div class="field">
    <label for="inputEmail">{{ trans('auth.email') }}</label>
    <input id="inputEmail" type="email" name="email" placeholder="{{ trans('auth.email') }}" value="{{ old('email') }}" required autofocus>
    @if ($errors->has('email'))
      <div class="ui error message">
        <p>{{ $errors->first('email') }}</p>
      </div>
    @endif
  </div>

  <div class="field">
    <label for="inputPassword">{{ trans('auth.password') }}</label>
    <input type="password" id="inputPassword" name="password" placeholder="{{ trans('auth.password') }}" required>
    @if ($errors->has('password'))
      <div class="ui error message">
        <p>{{ $errors->first('password') }}</p>
      </div>
    @endif
  </div>

  <div class="field">
    <div class="ui checkbox">
      <input id="inputRemember" type="checkbox" name="remember" value="1" tabindex="0">
      <label for="inputRemember">Запомнить меня</label>
    </div>
  </div>

  <button type="submit" class="ui primary button">
    {{ trans('auth.login.submit') }}
  </button>
</form>

<div class="auth-section-links">
  <div class="ui grid">
    <div class="eight wide column">
      <p><a href="{{ route('password.request') }}">{{ trans('auth.login.forgot_password') }}</a></p>
    </div>
    <div class="eight wide column right aligned">
      <p><a href="{{ route('register') }}">{{ trans('auth.login.register') }}</a></p>
    </div>
  </div>
</div>
@endsection
