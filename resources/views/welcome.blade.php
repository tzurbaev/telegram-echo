@extends('layouts.landing')

@section('content')
<div class="ui text container">
  <h1 class="ui inverted header">
    {{ config('app.name') }}
  </h1>
  <h2>{{ trans('landing.subtitle') }}</h2>
  <div class="auth-buttons">
    <a href="{{ route('login') }}" class="ui huge default button">
      {{ trans('landing.menu.login') }}
    </a>
    <a href="{{ route('register') }}" class="ui huge primary button">
      {{ trans('landing.menu.register') }}
    </a>
  </div>
</div>
@endsection
