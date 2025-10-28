<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receive</title>
</head>
<body>
    @if(request()->has('nama'))
        <h1>Hello {{ request('nama') }}</h1>
    @else
        <h1>Hello</h1>
        
        
    @endif
</body>
</html>
