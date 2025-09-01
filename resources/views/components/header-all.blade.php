<x-header {{ $attributes->merge(['class' => '']) }}>

    <x-header.item class="aspect-square">

        @if (isset($href) && !empty($href))
            <a class="flex aspect-square h-full items-center justify-center hover:bg-black/20" href="{{ $href }}"
                wire:navigate>
                <x-icons.left-arrow />
            </a>
        @endif
        <a class="flex aspect-square h-full items-center justify-center hover:bg-black/20" href="/" wire:navigate>
            <x-icons.home />
        </a>

    </x-header.item>
    <x-header.item>
        {{ $slot }}
    </x-header.item>
    <x-header.item>

        <div x-data="{ isOpen: false }" class="relative">
            <div class="me-1 flex aspect-square rounded-full p-1 hover:bg-black/20">
                <button type="button" @click="isOpen = !isOpen">
                    <x-icons.ellipsis-vertical />
                </button>
            </div>
            <div x-show="isOpen" @click.outside="isOpen = false"
                x-transition:enter="transition ease-out duration-100 transform"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75 transform"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                class="absolute right-0 z-10 me-2 mt-2 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                <div class="py-1" role="none">
                    <!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
                    {{-- <livewire:v1.auth.logout> --}}
                    {{-- <a href="/" wire:navigate
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                        role="menuitem" tabindex="-1" id="menu-item-0">Profile</a> --}}
                </div>
            </div>

    </x-header.item>
</x-header>
