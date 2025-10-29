<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Received JSON</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 30px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        h2 { color: #333; }
    </style>
</head>
<body>

<h2>Request Headers</h2>
<table>
    <tr>
        <th>Header Name</th>
        <th>Value</th>
    </tr>
    @foreach ($headers as $key => $values)
        <tr>
            <td>{{ $key }}</td>
            <td>{{ implode(', ', $values) }}</td>
        </tr>
    @endforeach
</table>

<h2>JSON Payload</h2>
<table>
    <tr>
        <th>#</th>
        <th>Card ID</th>
        <th>Time</th>
    </tr>
    @foreach ($data as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item['card_id'] ?? '' }}</td>
            <td>{{ $item['time'] ?? '' }}</td>
        </tr>
    @endforeach
</table>

</body>
</html>