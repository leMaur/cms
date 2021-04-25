<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {!! app('seotools.metatags')->generate() !!}

        @if((bool) config('cms.seo.opengraph.enable'))
            {!! app('seotools.opengraph')->generate() !!}
        @endif

        @if((bool) config('cms.seo.twitter.enable'))
            {!! app('seotools.twitter')->generate() !!}
        @endif

        @if((bool) config('cms.seo.schema_org.enable'))
            {{-- @TODO: add schema org --}}
        @endif

        <!-- Fonts -->
        <!-- Favicon -->
        <!-- Styles -->
        <!-- Scripts -->
    </head>
    <body>
    {!! $content !!}
    </body>
</html>
