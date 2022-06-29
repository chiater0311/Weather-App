<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="https://kbda-vn.s3.ap-southeast-1.amazonaws.com/manual/icons8-fog-96.png">
        <title>Weather App</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        
        <!-- Styles -->
        <link href="{{ asset('/css/app.css') }}" rel="stylesheet" />

        <!-- Scripts -->
        <script src="/js/app.js" defer></script>
    </head>
    <body class="bg-[#565555]">
        <div id="app" class="flex justify-center pt-14">
        </div>
    </body>
</html>
