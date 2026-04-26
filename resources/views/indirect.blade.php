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

    @if($status === 'Payment Success')
    <a href="{{ $redirectUrl }}">
        <button>Continue</button>
    </a>
@else
    <p>Payment failed. Please try again.</p>
@endif
</body>
</html>