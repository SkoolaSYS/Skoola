<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receive POST</title>
</head>
<body>
    @if(!empty($nama))
        <h1>Hello {{ $nama }}</h1>
    @else
        <h1>Hello world</h1>
    @endif
</body>
</html>
