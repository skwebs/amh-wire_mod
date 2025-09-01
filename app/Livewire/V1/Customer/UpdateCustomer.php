<?php

namespace App\Livewire\V1\Customer;

use App\Models\Customer;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Attributes\Url;

class UpdateCustomer extends Component
{
    public $customer;
    public $user_id;
    public $name;
    public $type;
    public $billing_date;


    #[Url(as: 'm')]
    public $message;
    /**
     * Initializes the component with a customer object.
     *
     * @param  Customer  $customer  The customer object to initialize with.
     */
    public function mount(Customer $customer)
    {
        $this->customer = $customer;
        $this->user_id = auth()->guard()->user()->id;
        $this->name = $customer->name;
        $this->type = $customer->type;
        $this->billing_date = $customer->type == 'credit_card' ? $customer->billing_date : null;
    }

    public function updateCustomer()
    {
        $this->validate([
            'name' => 'required|min:0',
            'type' => 'required|in:cash,bank,credit_card,income,expense,other',
            'billing_date' => 'nullable|integer|min:1|max:28|required_if:type,credit_card',
        ], [
            'billing_date.required_if' => 'The billing date field is required when the type is Credit Card.',
        ]);

        $this->customer->update([
            'name' => $this->name,
            'type' => $this->type,
            'billing_date' => $this->type == 'credit_card' ? $this->billing_date : null,
        ]);

        session()->flash('message', 'Customer updated successfully.');

        return $this->redirect(route('customer.details', $this->customer->id), navigate: true);
    }

    #[Title('Update Customer')]
    public function render()
    {
        return view('livewire.v1.customer.update-customer');
    }
}
