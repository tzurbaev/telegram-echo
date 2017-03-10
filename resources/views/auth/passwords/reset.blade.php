@extends('layouts.auth')

@section('content')
<h1>{{ trans('auth.reset.reset.heading') }}</h1>

@if (session('status'))
  <div class="ui success message">
    <p>{{ session('status') }}</p>
  </div>
@endif

<form action="{{ route('password.request') }}" method="POST" class="ui form{{ $errors->count() > 0 ? ' error' : '' }}">
  {{ csrf_field() }}
  <input type="hidden" name="token" value="{{ $token }}">

  <div class="field{{ $errors->has('email') ? ' error' : '' }}">
    <label for="inputEmail">{{ trans('auth.email') }}</label>
    <input type="email" placeholder="{{ trans('auth.email') }}" value="{{ $email or old('email') }}" name="email" required autofocus id="inputEmail">
    @if ($errors->has('email'))
      <div class="ui error message">
        <p>{{ $errors->first('email') }}</p>
      </div>
    @endif
  </div>

  <div class="field{{ $errors->has('password') ? ' error' : '' }}">
      <label for="inputPassword">{{ trans('auth.password') }}</label>
      <input type="password" name="password" required>
  </div>
  @if ($errors->has('password'))
    <div class="ui error message">
        <p>{{ $errors->first('password') }}</p>
    </div>
  @endif

  <div class="field{{ $errors->has('password_confirmation') ? ' error' : '' }}">
      <label for="inputPassword">{{ trans('auth.password_confirmation') }}</label>
      <input type="password" name="password_confirmation" required>
  </div>
  @if ($errors->has('password_confirmation'))
    <div class="ui error message">
        <p>{{ $errors->first('password_confirmation') }}</p>
    </div>
  @endif

  <button type="submit" class="ui primary button">
    {{ trans('auth.reset.reset.submit') }}
  </button>
</form>
@endsection
