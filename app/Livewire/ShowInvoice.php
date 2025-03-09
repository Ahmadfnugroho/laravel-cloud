<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;

class ShowInvoice extends Component
{
    public $transaction;

    // Gunakan method mount untuk menerima data saat komponen dipasang
    public function mount(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function render()
    {
        return view('livewire.show-invoice');
    }
}
