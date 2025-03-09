<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function __invoke(Transaction $order)
{
    // Pastikan $order berisi data yang sesuai

    // return view('pdf', ['record' => $order]);

    return Pdf::loadView('pdf', ['record' => $order])
    ->stream('order.pdf')
    // ->download($order->booking_transaction_id . '.pdf')
    ;
        
}


}
