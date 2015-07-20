<!DOCTYPE html>
<html>
  <head>
    <title>GuildQ Exercise - David Gunter</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
        width: 100%;
      }
    </style>

        @yield('js-includes')

  </head>
  <body>
    <strong>{{ $errors->first('csvFile') }}</strong>
    @yield('content')

  </body>
</html>