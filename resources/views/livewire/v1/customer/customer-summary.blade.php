<x-wrapper-layout class="bg-blue-50">
    @if ($validated)
        <x-slot:header>
            @php
                $balance = $customer->balance;
            @endphp
            <x-header-all @class(['bg-red-700' => $balance > 0, 'bg-green-700' => $balance < 0])>

                <span class="flex items-center justify-center">
                    <div class="aspect-square h-full">
                        <x-icons.user-circle />
                    </div>
                    <div>
                        <div class="text-nowrap text-sm">{{ $customer->name }}</div>
                        <div class="text-nowrap text-center text-sm font-bold">
                            Bal: ₹
                            {{ number_format(abs($balance), 2) }}
                            <span @class([
                                'text-red-200' => $balance > 0,
                                'text-green-200' => $balance < 0,
                            ])>
                                {{ $balance > 0 ? 'Dr' : ($balance < 0 ? 'Cr' : '') }}
                            </span>
                        </div>
                    </div>
                </span>

            </x-header-all>

            <div class="flex w-full flex-col border-b p-2 py-1 text-xs font-semibold">
                <div class="flex w-full">
                    <div wire:click="sortBy('date')" class="flex-[2]">Date</div>
                    <div class="flex-1 text-center">
                        <span class="text-red-600">Dues</span>/<span class="text-green-600">Paid</span>
                    </div>
                    <div class="w-24 text-nowrap text-gray-600">Balance</div>
                </div>
                <div class="w-full text-gray-400">Description</div>
            </div>
        </x-slot:header>


        <main class="flex-grow overflow-y-auto bg-blue-50">

            <div id="transactions-table-body" x-data x-init="$nextTick(() => {
                const el = document.getElementById('transactions-table-body');
                el.scrollTop = el.scrollHeight;
            })"
                @transactionsUpdated.window="$nextTick(() => { const el = document.getElementById('transactions-table-body'); el.scrollTop = el.scrollHeight; })"
                class="flex grow flex-col gap-y-2 overflow-y-auto overflow-x-hidden bg-gray-100 p-2">


                @foreach ($transactions as $date => $groupedTransaction)
                    <span
                        class="mx-auto inline-block w-fit rounded bg-white px-2 py-1 text-center text-xs">{{ date('d M Y', strtotime($date)) }}</span>
                    @foreach ($groupedTransaction as $transaction)
                        <div
                            class="group/customer relative w-full overflow-hidden rounded bg-white text-xs shadow transition-all duration-100 hover:bg-gray-50">
                            <span class="relative flex h-full w-full rounded">

                                <div class="flex w-full flex-col p-2">

                                    <div class="flex gap-4">
                                        <div class="flex flex-[2] flex-col justify-around">
                                            <div class="text-gray-700">
                                                {{ date('d-m-Y', strtotime($transaction->datetime)) }}
                                            </div>
                                        </div>
                                        <div @class([
                                            'flex-1 px-2 flex items-center justify-end font-semibold   text-right',
                                            'text-green-600' => $transaction->type === 'credit',
                                            'text-red-600' => $transaction->type === 'debit',
                                        ])>
                                            {{ '₹ ' . number_format($transaction->amount, 2) }}
                                        </div>
                                        <div
                                            class="flex w-24 items-center justify-end text-nowrap px-2 text-right font-semibold text-gray-600">
                                            ₹ {{ number_format(abs($balance), 2) }}
                                            <span @class([
                                                'text-red-600' => $balance > 0,
                                                'text-green-600' => $balance < 0,
                                            ])>
                                                <span
                                                    class="ml-1 inline-block w-4">{{ $balance > 0 ? 'Dr' : ($balance < 0 ? 'Cr' : '') }}</span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="text-gray-400">
                                        {{ $transaction->particulars ?? ucfirst($transaction->type) . 'ed ₹ ' . $transaction->amount }}
                                    </div>

                                </div>
                            </span>
                        </div>
                        @php
                            if ($transaction->type === 'credit') {
                                $balance += $transaction->amount;
                            } else {
                                $balance -= $transaction->amount;
                            }
                        @endphp
                    @endforeach
                @endforeach

            </div>

        </main>

        <x-slot:footer>
            <div class="flex w-full justify-around border-t bg-white p-1 text-gray-600">
                This is online generated statement.
            </div>
        </x-slot:footer>
    @else
        <div class="">
            <div class="bg-red-600 p-3 text-white">Invalid link</div>
        </div>
    @endif

</x-wrapper-layout>
