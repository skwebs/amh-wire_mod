<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Response;

class ExportData extends Controller
{
    public function getTransaction(Customer $customer)
    {
        return $customer->transactions;
    }

    public function exportToCsv(Customer $customer)
    {

        // dd($customer->name);
        $transactions = $customer->transactions;

        // Check if there are transactions to process
        if ($transactions->isEmpty()) {
            return Response::make('No transactions found for this customer.', 404);
        }

        // Determine date range for filename
        $dates = $transactions->pluck('datetime')->map(function ($datetime) {
            return strtotime($datetime);
        });
        $startDate = date('d-m-Y', $dates->min());
        $endDate = date('d-m-Y', $dates->max());
        $filename = "{$customer->name}_Ac_{$startDate}_to_{$endDate}.csv";

        // Define CSV headers
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        // Create a stream for CSV output
        $output = fopen('php://output', 'w');

        // Write CSV header
        fputcsv($output, ['Date', 'Dr', 'Cr', 'Discription']);

        // Process each transaction
        foreach ($transactions as $transaction) {
            $date = date('d M Y', strtotime($transaction->datetime));
            $debit = $transaction->type === 'debit' ? $transaction->amount : '';
            $credit = $transaction->type === 'credit' ? $transaction->amount : '';
            $particulars = $transaction->particulars ?? '';

            fputcsv($output, [$date, $debit, $credit, $particulars]);
        }

        // Close the output stream
        fclose($output);

        // Return the response with headers
        return Response::make('', 200, $headers);
    }
}
