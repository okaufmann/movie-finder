<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Home') | {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="{{ vite('css/app.css') }}" rel="stylesheet">
    @livewireStyles
</head>
<body class="font-sans antialiased min-h-screen bg-blue-50">
<div class="px-5">
    {{ $slot }}
</div>

<script src="{{ vite('js/app.js') }}"></script>
@livewireScripts
</body>
</html>
