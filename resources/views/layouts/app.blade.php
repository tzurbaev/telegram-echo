<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>{{ config('app.name') }}</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <link rel="stylesheet" href="{{ asset('css/vendor.css') }}">
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
  <script>
    window.Application = {
      csrfToken: '{{ csrf_token() }}',
      state: {!! json_encode($applicationState) !!},
    }
  </script>
</head>
<body class="dashboard-page">
  <div class="ui container" id="app">
    <div class="ui grid">
      <div class="four wide column">
        @yield('sidebar')
      </div>
      <div class="twelve wide column">
        @yield('content')
      </div>
    </div>
    <div class="hidden">
      <logout-form csrf-token="{{ csrf_token() }}" logout-url="{{ route('logout') }}"></logout-form>
    </div>
  </div>
  <script src="{{ asset('js/vendor.js') }}"></script>
  <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
