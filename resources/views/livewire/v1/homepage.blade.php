<x-wrapper-layout class="bg-blue-50">
    <x-slot:header class="bg-blue-600">
        <x-header-all>
            {{ $user->name ?? 'Guest' }}
        </x-header-all>
    </x-slot:header>

    <main class="flex-grow overflow-y-auto p-3">
        <div class="grid grid-cols-1 gap-3">
            <div
                class="{{ $cashBalance < 0 ? 'bg-red-600' : 'bg-green-600' }} flex items-center rounded-md px-2 py-2 text-sm text-white">
                <x-icons.cash class="h-5 w-5" />
                <span class="ml-1">Cash Balance: ₹{{ number_format($cashBalance, 2, '.', ',') }}</span>
            </div>
            <div
                class="{{ $banksBalance < 0 ? 'bg-red-600' : 'bg-green-600' }} flex items-center rounded-md px-2 py-2 text-sm text-white">
                <x-icons.bank class="h-5 w-5" />
                <span class="ml-2">Bank Balance: ₹{{ number_format($banksBalance, 2, '.', ',') }}</span>
            </div>
            <div class="flex flex-col">
                <div
                    class="{{ $creditCardsExpenses < 0 ? 'bg-red-600' : 'bg-green-600' }} flex items-center rounded-t-md px-2 py-1 text-sm text-white">
                    <x-icons.credit-card class="mr-2 h-5 w-5" />
                    <span class="ml-2">
                        Credit Card Expenses:
                        ₹{{ number_format(abs($creditCardsExpenses), 2, '.', ',') }}
                    </span>
                </div>

                <div
                    class="{{ $creditCardsExpenses < 0 ? 'bg-red-100' : 'bg-green-100' }} flex items-center rounded-b-md border border-red-600 px-2 text-sm text-white">
                    <span class="mr-1 text-red-600">{{ number_format($totalPreviousDebit, 2) }}</span>
                    <span class="mr-1 text-green-600"> - {{ number_format($totalCurrentCredit, 2) }}</span>
                    <span class="mr-1 text-amber-600"> =
                        {{ number_format($totalPreviousDebit - $totalCurrentCredit, 2) }}</span>
                    <span class="mr-1 text-red-600">+ {{ number_format($totalCurrentDebit, 2) }}</span>
                    <span class="text-red-600">
                        = {{ number_format($totalPreviousDebit - $totalCurrentCredit + $totalCurrentDebit, 2) }}
                    </span>
                </div>

            </div>

            <div
                class="{{ $otherBalance >= 0 ? 'bg-red-600' : 'bg-green-600' }} flex items-center rounded-md px-2 py-2 text-sm text-white">
                <x-icons.other class="mr-2 h-5 w-5" />
                <span class="ml-2">Other Balance: ₹{{ number_format($otherBalance, 2, '.', ',') }}
                    |<strong> {{ $otherBalance < 0 ? 'To be receive' : 'To be paid' }}</strong> </span>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="mt-4">
            <div class="mb-2 flex justify-between text-base font-semibold">
                <h2>Recent Transactions</h2>
                <a href="{{ route('transactions') }}" wire:navigate class="block justify-end text-sm text-blue-600">View
                    All Transactions</a>
            </div>
            @if ($transactions->isEmpty())
                <p class="text-sm text-gray-600">No transactions found.</p>
            @else
                <ul class="space-y-1">
                    @foreach ($transactions as $transaction)
                        <li
                            class="{{ $transaction->type == 'credit' ? 'text-green-600' : 'text-red-600' }} truncate rounded-md bg-white px-2 py-1 text-sm shadow">
                            {{ $transaction->type == 'credit' ? '+' : '-' }}
                            ₹{{ number_format($transaction->amount, 2, '.', ',') }}
                            <span class="text-xs text-gray-500">
                                [{{ $transaction->created_at->format('d-m H:i') }}]
                                [{{ $transaction->customer->name }}]
                                {{ $transaction->particulars ?? '' }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </main>

    <x-slot:footer>
        <div class="flex gap-2 p-4">
            <button wire:click="logout"
                class="rounded bg-red-600 px-4 py-3 text-sm text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
                aria-label="Log out">
                Logout
            </button>
            <a href="{{ route('customers') }}" wire:navigate
                class="flex flex-1 items-center justify-between rounded bg-blue-600 px-4 py-3 text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                aria-label="View customer list">
                View Customers <x-icons.arrow-right class="h-5 w-5" />
            </a>
        </div>
    </x-slot:footer>
</x-wrapper-layout>