<!DOCTYPE html>
<html>
<head>
    <title>School Login</title>
</head>
<body>

<h2>School / Teacher Login</h2>

<form method="POST" action="{{ route('school.login.submit') }}">
    @csrf

    <label>Email:</label>
    <input type="email" name="email" required>

    <label>Password:</label>
    <input type="password" name="password" required>

    <button type="submit">Login</button>
</form>

</body>
</html>
