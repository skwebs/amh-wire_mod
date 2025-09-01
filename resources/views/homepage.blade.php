<x-livewire-layout class="bg-blue-50 h-svh flex flex-col mx-auto w-96 ">

    <x-slot:header class="bg-red-300">
        <x-header>
            <x-header.item class="aspect-square">
                {{-- <a class="aspect-square h-full flex justify-center items-center hover:bg-black/20" href="/c">
                    <x-icons.left-arrow />
                </a> --}}
            </x-header.item>
            <x-header.item>
                Anshu Medical Hall
            </x-header.item>
            <x-header.item>
                <div class="hover:bg-black/20 p-1 rounded-full me-1">
                    <x-icons.ellipsis-vertical />
                </div>
            </x-header.item>
        </x-header>
    </x-slot:header>


    <main class="flex-grow p-5">
        <a href="{{ route('customers') }}" wire:navigate class="bg-blue-600 rounded text-white px-5 py-3">Customer
            List</a>
    </main>

    <x-slot:footer>
        footer
    </x-slot:footer>

</x-livewire-layout>
