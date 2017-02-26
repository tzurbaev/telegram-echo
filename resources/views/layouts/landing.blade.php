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
<body class="landing-page">
  <div class="ui large top fixed hidden menu">
    <div class="ui container">
      <div class="right menu">
        <div class="item">
          <a href="{{ route('login') }}" class="ui button">
            {{ trans('landing.menu.login') }}
          </a>
        </div>
        <div class="item">
          <a href="{{ route('register') }}" class="ui primary button">
            {{ trans('landing.menu.register') }}
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="ui vertical inverted sidebar menu">
    <a href="{{ route('login') }}" class="ui button">
      {{ trans('landing.menu.login') }}
    </a>
    <a href="{{ route('register') }}" class="ui primary button">
      {{ trans('landing.menu.register') }}
    </a>
  </div>

  <div class="pusher">
    <div class="ui inverted vertical masthead center aligned segment">
      <div class="ui container">
        <div class="ui large secondary inverted pointing menu">
          <a href="javascript:;" class="toc item">
            <i class="sidebar icon"></i>
          </a>
          <div class="right item">
            <a href="{{ route('login') }}" class="ui inverted button">
              {{ trans('landing.menu.login') }}
            </a>
            <a href="{{ route('register') }}" class="ui inverted button">
              {{ trans('landing.menu.register') }}
            </a>
          </div>
        </div>
      </div>

      <div id="app">
        @yield('content')
      </div>
    </div>
  </div>

  <script src="{{ asset('js/vendor.js') }}"></script>
  <script src="{{ mix('js/app.js') }}"></script>
  <script>
    $(function () {
      $('.masthead').visibility({
        once: false,
        onBottomPassed: function () {
          $('.fixed.menu').transition('fade in')
        },
        onBottomReverse: function () {
          $('.fixed.menu').transition('fade out')
        }
      })

      $('.ui.sidebar').sidebar('attach events', '.toc.item')
    })
  </script>
</body>
</html>
