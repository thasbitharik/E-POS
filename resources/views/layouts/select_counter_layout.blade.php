<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>E POS - Select Counter</title>
    <link rel="stylesheet" href="{{ asset('assets/css/counterpage.css') }}">
    <link rel='shortcut icon' type='image/x-icon' href="{{ asset('assets/img/favicon.png') }}" />
    @livewireStyles
</head>

<body>
    <div class="loader"></div>
    <div>
        {{ $slot }}
    </div>

    @livewireScripts
    <script>
        window.addEventListener("load", function() {
            var loader = document.querySelector(".loader");
            loader.style.transition = "opacity 0.5s ease";
            loader.style.opacity = "0";
            setTimeout(function() {
                loader.style.display = "none";
            }, 500);
        });
    </script>
</body>

</html>