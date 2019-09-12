<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <link href="{{ asset('css/app.css')  }}" rel="stylesheet">
</head>
<body>
<div id="app">

    {keyword1}用户你好！您的验证码是{keyword2}

    <welcome-component></welcome-component>
</div>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>