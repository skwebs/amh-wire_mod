<div {{ $attributes->merge(['class' => 'h-full flex justify-center items-center ']) }}>
    {{-- {{ $slot->isEmpty() ? 'empty.' : $slot }} --}}
    {{ $slot }}
</div>
