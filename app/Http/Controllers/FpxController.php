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

    // Build redirect query
    $query = http_build_query([
        'status'        => $status,
        'txnId'         => $txnId,
        'sellerOrder'   => $sellerOrder,
        'sellerExOrder' => $sellerExOrder,
        'datetime'      => $datetime,
        'bank'          => $bank,
        'amount'        => $amount,
        'buyerEmail'    => $buyerEmail,
        'productDesc'   => $productDesc
    ]);

    $redirectUrl = "https://pwa.komeps.co.uk/#/indirect?$query";
    $directUrl = "https://pwa.komeps.co.uk/#/direct?$query";

    return view('indirect', compact(
        'status',
        'txnId',
        'sellerOrder',
        'sellerExOrder',
        'amount',
        'bank',
        'buyerEmail',
        'productDesc',
        'datetime',
        'redirectUrl',
        'directUrl'

    ));
}

public function handleDirect(Request $request)
{
    $fpx = $request->all();

    // Debug log (optional but recommended)
    file_put_contents(
        storage_path('logs/fpx_direct_log.txt'),
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

    // Build query
    $query = http_build_query([
        'status'        => $status,
        'txnId'         => $txnId,
        'sellerOrder'   => $sellerOrder,
        'sellerExOrder' => $sellerExOrder,
        'datetime'      => $datetime,
        'bank'          => $bank,
        'amount'        => $amount,
        'buyerEmail'    => $buyerEmail,
        'productDesc'   => $productDesc
    ]);

    $redirectUrl = "https://pwa.komeps.co.uk/#/direct?$query";

    // ✅ Redirect immediately (no Blade)
    return redirect()->away($redirectUrl);
}
}
