@extends('layouts.auth')

@section('content')
<h1>{{ trans('auth.reset.email.heading') }}</h1>

@if (session('status'))
  <div class="ui success message">
    <p>{{ session('status') }}</p>
  </div>
@endif

<form action="{{ route('password.email') }}" method="POST" class="ui form{{ $errors->count() > 0 ? ' error' : '' }}">
  {{ csrf_field() }}

  <div class="field{{ $errors->has('email') ? ' error' : '' }}">
    <label for="inputEmail">{{ trans('auth.email') }}</label>
    <input type="email" placeholder="{{ trans('auth.email') }}" value="{{ old('email') }}" name="email" required autofocus id="inputEmail">
    @if ($errors->has('email'))
      <div class="ui error message">
        <p>{{ $errors->first('email') }}</p>
      </div>
    @endif
  </div>

  <button type="submit" class="ui primary button">
    {{ trans('auth.reset.email.submit') }}
  </button>
</form>
@endsection
