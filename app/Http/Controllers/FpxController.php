<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FpxController extends Controller
{
    public function handleCallback(Request $request)
{
    $fpx = $request->all();

    // Debug log
    file_put_contents(
        storage_path('logs/fpx_log.txt'),
        date('Y-m-d H:i:s') . " | " . print_r($fpx, true) . PHP_EOL,
        FILE_APPEND
    );

    // Extract fields
    $code           = $fpx['fpx_debitAuthCode'] ?? '';
    $txnId          = $fpx['fpx_fpxTxnId'] ?? '';
    $sellerOrder    = $fpx['fpx_sellerOrderNo'] ?? '';
    $sellerExOrder  = $fpx['fpx_sellerExOrderNo'] ?? '';
    $datetime       = $fpx['fpx_fpxTxnTime'] ?? '';
    $bank           = $fpx['fpx_buyerBankId'] ?? '';
    $amount         = $fpx['fpx_txnAmount'] ?? '';
    $buyerEmail     = $fpx['fpx_buyerEmail'] ?? '';
    $productDesc    = $fpx['fpx_productDesc'] ?? '';

    // Status mapping
    if ($code === '00') {
        $status = 'Payment Success';
    } elseif ($code === '99') {
        $status = 'Pending Authorization';
    } else {
        $status = 'Failed (' . $code . ')';
    }

    return view('indirect', compact(
        'status',
        'txnId',
        'sellerOrder',
        'amount',
        'bank',
        'buyerEmail',
        'productDesc',
        'datetime'
    ));
}
}
