<x-wrapper-layout class="bg-blue-50">

    <x-slot:header>
        <x-header-all @class([
            'bg-green-700 ' => $transaction->type == 'credit',
            'bg-red-700 ' => $transaction->type == 'debit',
        ])
            href="{{ route('customer.transaction.details', ['customer' => $customer, 'transaction' => $transaction]) }}">
            Update Transaction Details
        </x-header-all>
    </x-slot:header>


    <main class="flex-grow overflow-y-auto bg-blue-50">
        <div class="p-5">
            <h2 @class([
                ' text-green-700 ' => $type == 'credit',
                ' text-red-700 ' => $type == 'debit',
            ])>
                {{ $customer->name }}</h2>

            <form class="flex flex-col gap-2" wire:submit="updateTransaction">
                <!-- Amount -->
                <div>
                    <x-input-label for="amount" :value="__('Amount')" />
                    <x-text-input wire:model="amount" id="amount" class="mt-1 block w-full" type="number"
                        step=".01" name="amount" required autofocus autocomplete="transaction-amount" />
                    <div class="min-h-4">
                        <x-input-error :messages="$errors->get('amount')" class="text-xs" />
                    </div>
                </div>

                <!-- Transaction Date time -->
                <div>
                    <x-input-label for="datetime" :value="__('Transaction DateTime')" />
                    <x-text-input wire:model="datetime" id="datetime" class="mt-1 block w-full" type="datetime-local"
                        name="datetime" required autocomplete="current-datetime" />
                    <div class="min-h-4">
                        <x-input-error :messages="$errors->get('datetime')" class="text-xs" />
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <x-input-label for="particulars" :value="__('Particulars')" />
                    <x-text-input wire:model="particulars" id="particulars" class="mt-1 block w-full" type="text"
                        step=".01" name="particulars" autofocus autocomplete="transaction-description" />
                    <div class="min-h-4">
                        <x-input-error :messages="$errors->get('particulars')" class="text-xs" />
                    </div>
                </div>

                <!-- Trasaction Type -->
                <div>
                    <div class="text-sm">Existing Txn type : <span
                            class="{{ $transaction->type == 'credit' ? ' text-green-700 ' : ' text-red-700  ' }} font-bold capitalize">{{ $transaction->type }}</span>
                    </div>
                    <x-input-label for="type" :value="__('Transaction Type')" />
                    <select id="type" wire:model.change="type"
                        class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-500 placeholder:text-gray-600 focus:ring-2 focus:ring-inset focus:ring-gray-600 sm:text-sm sm:leading-6">
                        <option value="debit" @selected($type == 'debit')>Debit</option>
                        <option value="credit" @selected($type == 'credit')>Crebit</option>
                    </select>
                    <div class="min-h-4">
                        <x-input-error :messages="$errors->get('type')" class="text-xs" />
                    </div>
                </div>
                <!-- /added above code -->

                <div class="mt-2 flex w-full justify-around gap-2">
                    <button type="submit" wire:target="updateTransaction" wire:loading.attr="disabled"
                        @class([
                            'w-full  text-white rounded-md px-3 py-2 font-semibold disabled:cursor-not-allowed disabled:opacity-50',
                            'bg-green-700 hover:bg-green-800 ' => $type == 'credit',
                            'bg-red-700 hover:bg-red-800 ' => $type == 'debit',
                        ])>
                        <span wire:loading.remove wire:target="updateTransaction">Update</span>
                        <span wire:loading wire:target="updateTransaction">Updating...</span>
                    </button>
                </div>
            </form>
        </div>

    </main>

    <x-slot:footer>
        <div class="flex w-full justify-around gap-4 border-t p-4">
            <a href="{{ route('customer.transaction.details', ['customer' => $customer, 'transaction' => $transaction]) }}"
                class="inline-block w-full rounded-md bg-gray-500 px-3 py-2 text-center font-semibold text-white hover:bg-gray-600"
                wire:navigate>Go
                Back</a>
        </div>
    </x-slot:footer>

</x-wrapper-layout>
