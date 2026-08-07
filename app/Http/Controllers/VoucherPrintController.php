<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class VoucherPrintController extends Controller
{
    public function batch(string $batchId): Response
    {
        $vouchers = Voucher::where('business_id', auth()->user()->business_id)
            ->where('batch_id', $batchId)
            ->with('plan:id,name,price')
            ->get();

        abort_if($vouchers->isEmpty(), 404);

        $pdf = Pdf::loadView('pdf.vouchers', [
            'vouchers' => $vouchers,
            'business' => auth()->user()->business,
        ]);

        return $pdf->stream("vouchers-{$batchId}.pdf");
    }
}
