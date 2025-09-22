<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage/'.$enterprise->logo_miniatura) }}">
    <title>Login {{ $enterprise->nombre_comercial }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <link href="{{ asset('css/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <style>
        .bg-login-image {
            background: url("{{ asset('storage/'.$enterprise->logo_principal) }}");
            background-position: center;
            background-size: cover;
        }
    </style>
    <script>
        const API_URL       = "{{ url('/') }}";
        const CSRF_TOKEN    = "{{ csrf_token() }}";
    </script>
    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script src="{{ asset('js/login.js') }}"></script>
</head>
<body class="bg-gradient-primary">
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">
                                            <b>
                                                {{ $enterprise->nombre_comercial }}<br>
                                                {{ $enterprise->frase }}
                                            </b>
                                        </h1>
                                        <h2 class="h4 text-gray-900 mb-4">Bienvenido</h2>
                                    </div>
                                    <div class="alert alert-success" id="successMessage" style="display: none;">
                                        {{ session('message') }}
                                    </div>
                                    <div id="message"></div>
                                    <form id="loginForm" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" id="username" name="username" placeholder="Ingrese usuario">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Ingrese contraseña">
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block" id="loginButton">
                                            Login
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>