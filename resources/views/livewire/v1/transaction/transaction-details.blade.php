<x-wrapper-layout class="bg-blue-50">

    <x-slot:header>
        <x-header-all @class([
            'bg-green-700 ' => $transaction->type == 'credit',
            'bg-red-700 ' => $transaction->type == 'debit',
        ]) href="{{ route('customer.transactions', $customer) }}">
            Transaction Details
        </x-header-all>

    </x-slot:header>


    <main @class([
        'flex-grow overflow-y-auto',
        'bg-red-50/20' => $transaction->type == 'debit',
        'bg-green-50/20' => $transaction->type == 'credit',
    ])>

        <div class="p-5">
            <table class="w-full">
                <tr class="border">
                    <th class="p-2 text-left">Txn Id</th>
                    <td>:</td>
                    <td>{{ $transaction->id }}</td>
                </tr>
                <tr class="border">
                    <th class="p-2 text-left">Txn Amount</th>
                    <td>:</td>
                    <td>{{ $transaction->amount }}</td>
                </tr>
                <tr class="border">
                    <th class="p-2 text-left">Txn Type</th>
                    <td>:</td>
                    <td @class([
                        'capitalize font-semibold ',
                        'text-green-600 ' => $transaction->type == 'credit',
                        'text-red-600 ' => $transaction->type == 'debit',
                    ])>
                        {{ $transaction->type }}</td>
                </tr>
                <tr class="border">
                    <th class="p-2 text-left">Txn Date</th>
                    <td>:</td>
                    <td>
                        <div class="py-1">
                            <span class="text-nowrap">
                                {{ date('d M Y-H:i', strtotime($transaction->datetime)) }}
                            </span>
                            <span class="text-nowrap text-xs font-semibold">
                                ({{ \Carbon\Carbon::parse($transaction->datetime)->diffForHumans() }})</span>
                        </div>
                    </td>
                </tr>
                <tr class="border">
                    <th class="text-nowrap p-2 text-left">Txn Remarks</th>
                    <td>:</td>
                    <td>{{ $transaction->particulars }}</td>
                </tr>
                <tr class="border">
                    <th class="p-2 text-left">Created At</th>
                    <td>:</td>
                    <td>
                        <div class="py-1">
                            <span class="text-nowrap">
                                {{ date('d M Y-H:i', strtotime($transaction->created_at)) }}
                            </span>
                            <span class="text-nowrap text-xs font-semibold">
                                ({{ \Carbon\Carbon::parse($transaction->created_at)->diffForHumans() }})</span>
                        </div>
                    </td>
                </tr>

            </table>
            <div class="flex w-full justify-around gap-4 border-t p-4">
                <button wire:confirm="Are you sure to delete?" wire:click="delete()" wire:loading.attr="disabled"
                    class="w-full rounded-md bg-red-700 px-3 py-2 font-semibold text-white hover:bg-red-800 disabled:cursor-not-allowed disabled:opacity-50">
                    <span wire:loading.remove>Delete</span>
                    <span wire:loading>Deleting...</span>
                </button>

                <button
                    href="{{ route('customer.transaction.update', ['customer' => $customer, 'transaction' => $transaction]) }}"
                    wire:navigate
                    class="w-full rounded-md bg-blue-700 px-3 py-2 font-semibold text-white hover:bg-blue-800">Edit</button>

            </div>
        </div>


    </main>

    <x-slot:footer>
        <div class="flex w-full justify-around gap-4 border-t p-4">

            <a href="{{ route('customer.transactions', $customer) }}" wire:navigate
                class="inline-block w-full rounded-md bg-gray-600 px-3 py-2 text-center font-semibold text-white hover:bg-gray-700">Go
                Back</a>
        </div>
    </x-slot:footer>

</x-wrapper-layout>
