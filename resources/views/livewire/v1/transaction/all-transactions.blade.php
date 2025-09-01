<x-wrapper-layout class="bg-blue-50">
    <x-slot:header>
        <x-header-all href="{{ route('customers') }}" class="bg-gray-700">
            <div class="text-center text-sm font-bold">All Transactions</div>
        </x-header-all>
        <div class="flex w-full justify-center gap-2 py-2">
            <button wire:click="setFilter('last_30_days')" @class([
                'px-4 py-1 rounded text-sm',
                'bg-blue-600 text-white' => $filter === 'last_30_days',
                'bg-gray-200 text-gray-600' => $filter !== 'last_30_days',
            ])>
                Last 30 Days
            </button>
            <button wire:click="setFilter('current_month')" @class([
                'px-4 py-1 rounded text-sm',
                'bg-blue-600 text-white' => $filter === 'current_month',
                'bg-gray-200 text-gray-600' => $filter !== 'current_month',
            ])>
                Current Month
            </button>
        </div>
        <div class="flex w-full flex-col border-b p-2 py-1 text-xs font-semibold">
            <div class="flex w-full">
                <div class="flex-[2]">DateTime</div>
                <div class="flex-1 text-center">Customer</div>
                <div class="flex-1 text-center">
                    <span class="text-red-600">Dr</span>/<span class="text-green-600">Cr</span>
                </div>
            </div>
            <div class="w-full text-gray-400">Description</div>
        </div>
    </x-slot:header>

    <main class="flex-grow overflow-y-auto bg-blue-50">
        <div class="relative flex grow flex-col gap-y-2 overflow-y-auto overflow-x-hidden bg-gray-100 p-2">
            @if ($transactions)
                @foreach ($transactions as $date => $groupedTransaction)
                    <span @class([
                        'inline-block rounded text-center text-xs bg-white w-fit px-2 py-1 mx-auto',
                        'text-red-600' => date('w', strtotime($date)) == 0,
                    ])>
                        {{ date('D, d M Y', strtotime($date)) }}
                    </span>
                    @foreach ($groupedTransaction as $transaction)
                        <div
                            class="group/transaction w-full overflow-hidden rounded bg-white text-xs shadow transition-all duration-100 hover:bg-gray-50">
                            <a class="flex h-full w-full rounded"
                                href="{{ route('customer.transaction.details', ['customer' => $transaction->customer, 'transaction' => $transaction]) }}"
                                wire:navigate>
                                <div class="flex w-full flex-col p-2">
                                    <div class="flex gap-4">
                                        <div class="flex flex-[2] flex-col justify-around">
                                            <div class="text-gray-700">
                                                {{ \Carbon\Carbon::parse($transaction->datetime)->format('d M Y - h:i A') }}
                                            </div>
                                        </div>
                                        <div class="flex flex-1 items-center justify-center text-gray-600">
                                            {{ $transaction->customer->name }}
                                        </div>
                                        <div @class([
                                            'flex-1 px-2 flex items-center justify-end font-semibold text-right',
                                            'text-green-600' => $transaction->type === 'credit',
                                            'text-red-600' => $transaction->type === 'debit',
                                        ])>
                                            ₹ {{ number_format($transaction->amount, 2) }}
                                        </div>
                                    </div>
                                    <div class="text-gray-400">
                                        {{ $transaction->particulars ?: ucfirst($transaction->type) . 'ed ₹ ' . number_format($transaction->amount, 2) }}
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @endforeach
            @else
                <div class="text-center text-sm font-semibold text-gray-400">
                    No transactions found. <a wire:navigate href="{{ route('customers') }}" class="text-blue-600">View
                        customers</a>.
                </div>
            @endif
        </div>
    </main>

    <x-slot:footer>
        <div class="flex w-full justify-center gap-4 border-t bg-white p-4">
            <a href="{{ route('customers') }}" wire:navigate
                class="flex-grow rounded bg-blue-600 px-4 py-2 text-center text-white">View Customers</a>
        </div>
    </x-slot:footer>
</x-wrapper-layout>
