<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {!! SEOTools::generate() !!}
        <!-- Fonts -->
        <!-- Favicon -->
        <!-- Styles -->
        <!-- Scripts -->
    </head>
    <body>
        {{ $content }}
    </body>
</html>
