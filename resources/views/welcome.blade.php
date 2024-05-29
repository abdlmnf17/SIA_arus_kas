<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Fonts -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=nunito:400,600&display=swap" rel="stylesheet">
    <!-- Styles -->
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background: #201f1e;

        }

        .navbar {
            background-color: #3498db;
        }

        .navbar-brand {
            color: #ffffff;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .navbar-nav .nav-link {
            color: #ffffff;
        }

        .welcome-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;

        }

        .welcome-card {
            max-width: 400px;
            width: 100%;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 8px rgba(88, 88, 88, 0.968);
            background-color: #fafafa;
        }

        .welcome-logo {
            width: 100px;
            height: 100px;
            margin-bottom: 20px;
        }

        .welcome-links a {

            margin-top: 10px;
            text-align: center;
            align-content: center;
            align-items: center;
            color: #ffffff;
            font-weight: bold;

        }
    </style>
</head>

<body>


    <div class="welcome-container">

        <div class="welcome-card">
            <div class="text-center">
                <img src="https://www.svgrepo.com/show/500071/hospital.svg" alt="Logo" class="welcome-logo">
                <p class="text-center text-dark">{{ config('app.name', 'Laravel') }}<br />

            </div>


            <p class="text-center text-dark">Sistem Informasi Akuntansi Arus Kas (SIA_AK) berbasis Laravel adalah aplikasi web yang dirancang untuk membantu perusahaan dalam mengelola dan menganalisis arus kas perusahaan.<br />
            </p>





            <div class="welcome-links text-center">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/home') }}" class="btn btn-primary">Masuk</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">Masuk</a>

                    @endauth
                @endif
            </div>


        </div><br/>
        <br/>


        <p class="text-center text-white">Laravel versi {{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }}) <br />
        </p>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
