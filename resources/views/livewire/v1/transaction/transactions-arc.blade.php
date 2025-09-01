<x-wrapper-layout class="bg-blue-50">

    <x-slot:header>
        @php
            $cBal = $this->calculateBalance();
        @endphp
        <x-header-all href="{{ route('customers') }}"
            class="{{ $cBal > 0 ? 'bg-red-700' : ($cBal < 0 ? 'bg-green-700' : '') }}">
            <a wire:navigate href="{{ route('customer.details', $customer) }}" class="flex items-center justify-center">
                <div class="aspect-square h-full">
                    <x-icons.user-circle />
                </div>
                <div>
                    <div class="text-nowrap text-sm">{{ $customer->name }}</div>
                    <div class="text-nowrap text-center text-sm font-bold">
                        Bal: ₹
                        {{ number_format(abs($this->calculateBalance()), 2) }}
                        <span class="{{ $cBal > 0 ? 'text-red-200' : ($cBal < 0 ? 'text-green-300' : '') }}">
                            {{ $cBal > 0 ? 'Dr' : ($cBal < 0 ? 'Cr' : '') }}
                        </span>
                    </div>
                </div>
            </a>

        </x-header-all>
        <div class="flex w-full border-b p-2 py-1 font-semibold">
            <div wire:click="sortBy('date')" class="flex-[2] py-2">Date</div>
            <div class="flex-1 py-2 text-center text-red-700">Given</div>
            <div class="flex-1 py-2 text-center text-green-600">Taken</div>
            {{-- <div class="w-16 py-2 text-gray-600 text-nowrap">Balance</div> --}}
        </div>
    </x-slot:header>


    <main class="flex-grow overflow-y-auto bg-blue-50">

        <div id="transactions-table-body" x-data x-init="$nextTick(() => {
            const el = document.getElementById('transactions-table-body');
            el.scrollTop = el.scrollHeight;
        })"
            @transactionsUpdated.window="$nextTick(() => { const el = document.getElementById('transactions-table-body'); el.scrollTop = el.scrollHeight; })"
            class="flex grow flex-col gap-y-2 overflow-y-auto overflow-x-hidden bg-gray-100 p-2">

            @php
                $balance = $this->calculateBalance();

            @endphp

            @foreach ($transactions as $date => $groupedTransaction)
                <span
                    class="mx-auto inline-block w-fit rounded bg-white px-2 py-1 text-center text-xs">{{ date('d M Y', strtotime($date)) }}</span>
                @foreach ($groupedTransaction as $transaction)
                    <div
                        class="group/customer relative w-full overflow-hidden rounded bg-white text-xs shadow transition-all duration-100 hover:bg-gray-50">
                        <a class="relative flex h-full w-full rounded"
                            href="{{ route('customer.transaction.details', ['customer' => $customer, 'transaction' => $transaction]) }}"
                            wire:navigate>

                            <div class="flex flex-[2] flex-col justify-around px-2 py-2">
                                <div class="text-gray-700">{{ date('d-m-Y', strtotime($transaction->date)) }}</div>
                                <div>
                                    <span class="bg-amber-50 text-amber-600">
                                        <span
                                            class="{{ $balance > 0 ? 'bg-red-50 text-red-600' : ($balance < 0 ? 'bg-green-50 text-green-600' : '') }} w-fit px-1">
                                            Bal. ₹ {{ number_format(abs($balance), 2) }}
                                        </span>
                                    </span>
                                </div>
                            </div>
                            <div
                                class="flex flex-1 items-center justify-end bg-red-50/50 px-2 text-right font-semibold text-red-600">
                                {{ $transaction->type === 'debit' ? '₹ ' . number_format($transaction->amount, 2) : '' }}

                            </div>
                            <div
                                class="flex flex-1 items-center justify-end bg-green-50/50 px-2 text-right font-semibold text-green-600">
                                {{ $transaction->type === 'credit' ? '₹ ' . number_format($transaction->amount, 2) : '' }}

                            </div>

                            {{-- <div
                                class="w-24 px-2 text-gray-600 text-nowrap flex items-center justify-end font-semibold  text-right">
                                {{ number_format(abs($balance), 2) }}

                                <span
                                    class="{{ $balance > 0 ? 'text-red-600' : ($balance < 0 ? 'text-green-600' : '') }}">
                                    <span
                                        class="ml-1 w-4 inline-block">{{ $balance > 0 ? 'Dr' : ($balance < 0 ? 'Cr' : '') }}</span>
                                </span>
                            </div> --}}

                        </a>
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
        <div class="flex w-full justify-around gap-4 border-t bg-white p-4">
            <button href="{{ route('customer.transaction.create', ['customer' => $customer, 'type' => 'd']) }}"
                wire:navigate class="flex-grow rounded bg-red-600 px-4 py-2 text-white">You
                Gave ₹</button>
            <button href="{{ route('customer.transaction.create', ['customer' => $customer, 'type' => 'c']) }}"
                wire:navigate class="flex-grow rounded bg-green-700 px-4 py-2 text-white">You
                Got ₹</button>
        </div>
    </x-slot:footer>

</x-wrapper-layout>
