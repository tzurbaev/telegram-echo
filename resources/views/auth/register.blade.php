@extends('layouts.auth')

@section('content')
<h1>{{ trans('auth.register.heading') }}</h1>
<form action="" class="ui form{{ $errors->count() > 0 ? ' error' : '' }}" method="POST">
  {{ csrf_field() }}

  <div class="field">
    <label for="inputName">{{ trans('auth.name') }}</label>
    <input id="inputName" type="text" name="name" placeholder="{{ trans('auth.name') }}" value="{{ old('name') }}" required autofocus>
    @if ($errors->has('name'))
      <div class="ui error message">
        <p>{{ $errors->first('name') }}</p>
      </div>
    @endif
  </div>

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
    <label for="inputConfirmPassword">{{ trans('auth.password_confirmation') }}</label>
    <input type="password" id="inputConfirmPassword" name="password_confirmation" placeholder="{{ trans('auth.password_confirmation') }}" required>
  </div>

  <button type="submit" class="ui primary button">
    {{ trans('auth.register.submit') }}
  </button>
</form>

<div class="auth-section-links">
  <p><a href="{{ route('password.request') }}">{{ trans('auth.register.login') }}</a></p>
</div>
@endsection
