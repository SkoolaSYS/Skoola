<!DOCTYPE html>
<html lang="en">
<head>
    <base href="../../../"/>
    <title>Log In</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="assets/plugins/global/plugins.bundle.css" />
    <link rel="stylesheet" href="assets/css/style.bundle.css" />
</head>
<body>
    <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12">
        <div class="bg-body d-flex flex-column flex-center rounded-4 w-md-600px p-10">
            <form class="form w-100" action="{{ route('school.login.submit') }}" method="POST">
                @csrf
                <h1 class="text-dark fw-bolder mb-3">School / Teacher Login</h1>

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                <div class="fv-row mb-8">
                    <input type="email" placeholder="Email" name="email" class="form-control bg-transparent" required autofocus autocomplete="username"/>
                </div>

                <div class="fv-row mb-3">
                    <input type="password" placeholder="Password" name="password" class="form-control bg-transparent" required autocomplete="current-password"/>
                </div>

                <div class="d-grid mb-10">
                    <button type="submit" class="btn btn-primary">Log In</button>
                </div>
            </form>
        </div>
    </div>
    <script src="assets/plugins/global/plugins.bundle.js"></script>
    <script src="assets/js/scripts.bundle.js"></script>
</body>
</html>
