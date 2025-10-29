<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Data Received</title>
</head>
<body>
    <h1>Receive Confirmation</h1>

    @if(isset($payload) && is_array($payload))
        @foreach($payload as $item)
            <p><strong>Card ID:</strong> {{ $item['card_id'] ?? '-' }}</p>
            <p><strong>Time:</strong> {{ $item['time'] ?? '-' }}</p>
            <hr>
        @endforeach
    @else
        <p>No data received.</p>
    @endif

</body>
</html>
