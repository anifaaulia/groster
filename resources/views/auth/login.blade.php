<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>G-Roster Login</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('dist/assets/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/assets/vendors/typicons/typicons.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/assets/vendors/simple-line-icons/css/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <!-- endinject -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('dist/assets/css/style.css') }}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{ asset('dist/assets/images/favicon.png') }}" />
    
    <!-- Google Fonts Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Outfit', sans-serif !important;
            background: linear-gradient(135deg, #f0f7ff 0%, #ffffff 100%) !important;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        .container-scroller {
            background: transparent !important;
        }

        .auth {
            background: transparent !important;
            min-height: 100vh;
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Decorative Blobs */
        .auth-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
            opacity: 0.5;
        }

        .auth-blob-1 {
            width: 300px;
            height: 300px;
            background: #ffc107; /* Yellow blob */
            top: 10%;
            right: 15%;
        }

        .auth-blob-2 {
            width: 400px;
            height: 400px;
            background: #4da3ff; /* Blue blob */
            bottom: 5%;
            left: 10%;
            opacity: 0.35;
        }

        /* Glassmorphism Auth Card */
        .auth-card {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.6) !important;
            border-radius: 24px !important;
            box-shadow: 0 15px 35px rgba(77, 163, 255, 0.08) !important;
            transition: all 0.3s ease;
            position: relative;
            z-index: 2;
        }

        .auth-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 45px rgba(77, 163, 255, 0.12) !important;
            border-color: rgba(77, 163, 255, 0.3) !important;
        }

        .brand-logo-text {
            font-size: 2.2rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: #2c8ae8;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-family: 'Outfit', sans-serif;
        }

        .brand-logo-text span {
            background: -webkit-linear-gradient(45deg, #2c8ae8, #4da3ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Form Styling */
        .form-group {
            margin-bottom: 20px !important;
        }

        .form-control {
            border-radius: 14px !important;
            background-color: #f8fafc !important;
            border: 1.5px solid #e2e8f0 !important;
            padding: 14px 20px !important;
            font-family: 'Outfit', sans-serif;
            font-size: 0.95rem !important;
            color: #1e293b !important;
            height: auto !important;
            transition: all 0.3s ease !important;
        }

        .form-control:focus {
            background-color: #ffffff !important;
            border-color: #4da3ff !important;
            box-shadow: 0 0 0 4px rgba(77, 163, 255, 0.15) !important;
        }

        .auth-btn {
            background: linear-gradient(135deg, #4da3ff 0%, #2c8ae8 100%) !important;
            border: none !important;
            border-radius: 14px !important;
            padding: 14px 20px !important;
            font-weight: 600 !important;
            font-size: 1rem !important;
            letter-spacing: 0.5px;
            color: #ffffff !important;
            box-shadow: 0 4px 15px rgba(77, 163, 255, 0.3) !important;
            transition: all 0.3s ease !important;
        }

        .auth-btn:hover {
            background: linear-gradient(135deg, #2c8ae8 0%, #1c72cb 100%) !important;
            box-shadow: 0 6px 20px rgba(44, 138, 232, 0.4) !important;
            transform: translateY(-2px);
        }

        .auth-link {
            color: #2c8ae8 !important;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .auth-link:hover {
            color: #1c72cb !important;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <!-- Decorative Background Blobs -->
    <div class="auth-blob auth-blob-1"></div>
    <div class="auth-blob auth-blob-2"></div>

    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <main class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-card text-left py-5 px-4 px-sm-5 rounded shadow">
                            <div class="brand-logo mb-3 text-center">
                                <div class="brand-logo-text">
                                    <span>📘</span> <span>G-Roster</span>
                                </div>
                            </div>
                            <h1 class="font-weight-light text-center text-secondary mb-4" style="font-size: 1.1rem; font-family: 'Outfit', sans-serif;">Silakan masuk untuk melanjutkan ke Dashboard</h1>

                            @if ($errors->any())
                                <div class="alert alert-danger mt-3" style="border-radius: 12px;">
                                    <ul class="mb-0 small">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="alert alert-success mt-3" style="border-radius: 12px;">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <form class="pt-2" action="{{ url('/login') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control" id="email"
                                        placeholder="Alamat Email" value="{{ old('email') }}" required autofocus>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control"
                                        id="password" placeholder="Kata Sandi" required>
                                </div>
                                <div class="mt-4">
                                    <button type="submit"
                                        class="btn btn-block auth-btn text-white w-100">MASUK</button>
                                </div>
                                <div class="text-center mt-4 font-weight-light small" style="font-family: 'Outfit', sans-serif; color: #64748b;">
                                    Belum punya akun? <a href="#" class="auth-link">Hubungi Administrator</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="{{ asset('dist/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('dist/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <!-- endinject -->
    <!-- inject:js -->
    <script src="{{ asset('dist/assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('dist/assets/js/template.js') }}"></script>
    <script src="{{ asset('dist/assets/js/settings.js') }}"></script>
    <script src="{{ asset('dist/assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('dist/assets/js/todolist.js') }}"></script>
    <!-- endinject -->
</body>

</html>