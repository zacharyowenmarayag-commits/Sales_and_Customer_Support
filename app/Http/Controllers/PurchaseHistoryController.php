<?php

namespace App\Http\Controllers;

use App\Support\ArrayPaginator;
use App\Support\CrmStorage;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;


class PurchaseHistoryController extends Controller
{
    private function getPurchaseRows(?string $q = null)
    {
        $query = \App\Models\SalesOrder::with(['customer', 'items']);

        if ($q) {
            $query->whereHas('customer', function($query) use ($q) {
                $query->where('first_name', 'like', "%{$q}%")
                      ->orWhere('last_name', 'like', "%{$q}%");
            });
        }

        $orders = $query->orderBy('order_date', 'desc')->get();

        $rows = [];
        foreach ($orders as $order) {
            $custName = $order->customer 
                ? $order->customer->first_name . ' ' . $order->customer->last_name 
                : 'Unknown Customer';
            $rows[] = [
                'date' => $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('M j, Y') : '—',
                'customer' => $custName,
                'order_id' => $order->order_id,
                'amount' => '₱' . number_format($order->total_amount, 2),
            ];
        }

        return $rows;
    }



    public function index(Request $request)
    {
        $q = $request->query('q');

        return view('CRM.purchase-history', [
            'rows' => ArrayPaginator::make($this->getPurchaseRows($q), $request),
            'q' => $q,
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $q = $request->query('q');
        $rows = $this->getPurchaseRows($q);

        $filename = 'purchase-history-' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Date', 'Customer', 'Order ID', 'Amount']);

            foreach ($rows as $row) {
                fputcsv($out, [$row['date'], $row['customer'], $row['order_id'], $row['amount']]);
            }

            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }
}

