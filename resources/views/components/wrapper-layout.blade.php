@props(['header', 'footer'])

<div {{ $attributes->merge(['class' => 'h-svh mx-auto w-full md:w-96 bg-gray-50 relative flex flex-col']) }}>
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
