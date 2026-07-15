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
        return CrmStorage::purchaseHistoryRows($q);
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

