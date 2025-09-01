<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Page Title' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

    @props(['header', 'footer'])

    <div {{ $attributes->merge(['class' => '']) }}>
        @if (isset($header))
            <div {{ $header->attributes->merge(['class' => '']) }}>
                {{ $header }}
            </div>
        @endif

        {{ $slot }}

        @if (isset($footer))
            <div {{ $footer->attributes->merge(['class' => '']) }}>
                {{ $footer }}
            </div>
        @endif
    </div>
</body>

</html>
