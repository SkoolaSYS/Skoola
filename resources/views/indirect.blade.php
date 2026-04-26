<!DOCTYPE html>
<html>
<head>
    <title>Indirect Page FPX</title>
</head>
<body>
    <h1>{{ $status }}</h1>

    <p>Transaction ID: {{ $txnId }}</p>
    <p>Order No: {{ $sellerOrder }}</p>
    <p>Amount: RM {{ $amount }}</p>
    <p>Bank: {{ $bank }}</p>
    <p>Email: {{ $buyerEmail }}</p>
    <p>Description: {{ $productDesc }}</p>
    <p>Date: {{ $datetime }}</p>
</body>
</html>