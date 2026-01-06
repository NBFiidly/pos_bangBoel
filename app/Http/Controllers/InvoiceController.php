<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Query orders dengan filter tanggal
        $orders = Order::with(['customer', 'orderdetails.product'])
            ->when($startDate, function ($query) use ($startDate) {
                return $query->whereDate('date', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                return $query->whereDate('date', '<=', $endDate);
            })
            ->orderBy('date', 'desc')
            ->get();

        // Hitung total penjualan
        $totalPenjualan = $orders->sum('total_price');

        // Generate PDF
        $pdf = Pdf::loadView('pdf.invoice-template', [
            'orders' => $orders,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalPenjualan' => $totalPenjualan,
        ]);

        $filename = 'Invoice_' . ($startDate ?? 'All') . '_to_' . ($endDate ?? 'All') . '.pdf';

        return $pdf->download($filename);
    }
}
