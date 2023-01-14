<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="rgb(225 29 72)">
    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body {{ $attributes->merge(['class' => 'flex w-screen h-screen']) }}>
    {{ $slot }}
    @vite('resources/js/app.js')
    @stack('js')
    @livewireScripts
    <script type="module">
        Alpine.start();
    </script>
</body>
</html>
