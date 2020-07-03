<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <link rel="stylesheet" href="{{asset('css/app.css')}}">
        <!-- this is public folder inside css/app.css include using asset -->
    </head>
    <body>
            <div id="app">
                <app />
            </div>
            <script src="{{asset('js/app.js')}}"></script>
            <!-- this is public folder inside js/app.js include using asset -->
    </body>
</html>
