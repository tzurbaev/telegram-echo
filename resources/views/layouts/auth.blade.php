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
      state: {},
    }
  </script>
</head>
<body class="auth-page">
  <div class="ui grid">
    <div class="six wide column"></div>
    <div class="four wide column">
      <div class="auth-section">
        @yield('content')
      </div>
    </div>
    <div class="six wide column"></div>
  </div>
</body>
</html>
