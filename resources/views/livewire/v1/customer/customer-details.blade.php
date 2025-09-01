<x-wrapper-layout class="bg-blue-50">
    <x-slot:header class="bg-red-300">

        <x-header-all href="{{ route('customer.transactions', $customer->id) }}">
            <a wire:navigate href="{{ route('customer.update', $customer) }}" class="flex items-center justify-center">
                <div class="aspect-square h-full">
                    <x-icons.user-circle />
                </div>
                <div>
                    <div class="text-nowrap text-sm">{{ $customer->name }}</div>
                    <div class="text-nowrap text-center text-sm font-bold">
                        Customer Details
                    </div>
                </div>
            </a>

        </x-header-all>
    </x-slot:header>


    <main class="flex-grow overflow-y-auto bg-blue-50">
        <div class="p-5">
            <table class="w-full">
                <tr class="border">
                    <th class="p-2 text-left">Name</th>
                    <td>:</td>
                    <td>{{ $customer->name }} ({{ $customer->type }})</td>
                </tr>


                <tr class="border">
                    <th class="p-2 text-left">Billing Date</th>
                    <td>:</td>
                    <td>{{ $customer->billing_date ?? 'N/A' }}</td>
                </tr>
                <tr class="border">
                    <th class="p-2 text-left">Last Txn</th>
                    <td>:</td>
                    <td>
                        @if ($customer->latestTransaction)
                            {{ $customer->latestTransaction->datetime->diffForHumans() }} |
                            {{ $customer->latestTransaction->amount }} |
                            {{ $customer->latestTransaction->type == 'credit' ? 'Cr' : 'Dr' }}
                        @else
                            No
                        @endif
                    </td>
                </tr>
                <tr class="border">
                    <th class="p-2 text-left">Balance</th>
                    <td>:</td>
                    <td
                        class="{{ $customer->balance > 0 ? 'text-red-600' : ($customer->balance < 0 ? 'text-green-600' : '') }}">
                        {{ abs($customer->balance) }}
                        {{ $customer->balance > 0 ? 'Dr' : ($customer->balance < 0 ? 'Cr' : '') }}</td>
                </tr>
            </table>

            <div class="mt-5 flex gap-x-4">
                <button wire:confirm="Are you sure to delete?" wire:click="delete()" wire:loading.attr="disabled"
                    class="w-full rounded bg-red-700 px-4 py-1 text-center text-white disabled:cursor-not-allowed disabled:opacity-50">
                    <span wire:loading.remove>Delete</span>
                    <span wire:loading>Deleting...</span>
                </button>

                <a href="{{ route('customer.update', $customer) }}" wire:navigate
                    class="w-full rounded bg-blue-700 px-4 py-1 text-center text-white">Edit</a>
            </div>
            <div class="mt-5">
                @php
                    $url = route('customer.summary', [
                        'customer' => $customer,
                        'd' => strtotime($customer->created_at),
                    ]);
                    $msg = urlencode('Open link for statement: ' . $url);
                @endphp
                {{-- @dd($customer->created_at->format('d-m-Y H:i:s')); --}}
                <a class="w-full rounded bg-blue-700 px-4 py-1 text-white" href="sms:?body={{ $msg }}"
                    class="share-button sms-button">Share via
                    SMS</a>
                <a class="w-full rounded bg-blue-700 px-4 py-1 text-white"
                    href="https://api.whatsapp.com/send?text={{ $msg }}"
                    class="share-button whatsapp-button">Share via WhatsApp</a>

                <a href="{{ route('exportToCsv', $customer) }}"
                    class="mt-5 inline-block w-full rounded bg-blue-700 px-4 py-1 text-center text-white">Export CSV</a>
            </div>

        </div>
    </main>


    <x-slot:footer>
        <div class="flex w-full justify-around gap-4 border-t p-4">

            <button href="{{ route('customer.transactions', $customer) }}" wire:navigate
                class="flex-grow rounded bg-gray-500 px-4 py-1 text-white">Go Back</button>


        </div>
    </x-slot:footer>



</x-wrapper-layout>
