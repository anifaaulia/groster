<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>GROSTER</title>
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
  <!-- Plugin css for this page -->



  <link rel="stylesheet" href="{{ asset('dist/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('dist/assets/js/select.dataTables.min.css') }}">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('dist/assets/css/style.css') }}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{ asset('dist/assets/images/') }}" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

    :root {
        --primary-color: #4da3ff;
        --primary-dark: #2c8ae8;
        --secondary-color: #6c757d;
        --text-dark: #1f2937;
        --light-bg: #f3f7fd;
        --white: #ffffff;
        --gradient-light: linear-gradient(135deg, #f0f7ff 0%, #ffffff 100%);
    }

    body {
        font-family: 'Outfit', sans-serif !important;
        background-color: var(--light-bg) !important;
    }

    .content-wrapper {
        background: var(--light-bg) !important;
    }

    /* Navbar styling */
    .navbar {
        background: #ffffff !important;
        border-bottom: 1px solid #eef2f7 !important;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02) !important;
    }
    
    .navbar .navbar-brand-wrapper {
        background: #ffffff !important;
        border-right: 1px solid #eef2f7 !important;
        box-shadow: none !important;
        overflow: visible !important;
        padding-left: 15px !important;
        padding-right: 10px !important;
        width: 200px !important;
    }

    /* Tampilkan logo lengkap di semua ukuran layar */
    .navbar .navbar-brand-wrapper .navbar-brand.brand-logo {
        display: flex !important;
    }
    .navbar .navbar-brand-wrapper .navbar-brand.brand-logo-mini {
        display: none !important;
    }

    /* Sync lebar menu wrapper dengan brand wrapper baru (200px) */
    .navbar .navbar-menu-wrapper {
        width: calc(100% - 200px) !important;
    }

    .navbar-menu-wrapper {
        background: #ffffff !important;
    }

    .welcome-text {
        font-family: 'Outfit', sans-serif !important;
        font-weight: 700 !important;
    }

    .welcome-text span {
        color: var(--primary-dark) !important;
    }

    /* Sidebar Premium Blue Theme Styling */
    .sidebar {
        background: #ffffff !important;
        border-right: 1px solid #eef2f7 !important;
        transition: all 0.3s ease !important;
    }
    
    .sidebar .nav {
        padding-top: 15px !important;
    }
    
    .sidebar .nav .nav-item {
        margin: 4px 15px !important;
    }
    
    .sidebar .nav .nav-item .nav-link {
        border-radius: 12px !important;
        padding: 12px 18px !important;
        height: auto !important;
        display: flex !important;
        align-items: center !important;
        background: transparent !important;
        color: #4b5563 !important;
        font-weight: 500 !important;
        border: 1px solid transparent !important;
        transition: all 0.3s ease !important;
    }
    
    .sidebar .nav .nav-item .nav-link i.menu-icon {
        font-size: 1.2rem !important;
        margin-right: 12px !important;
        color: #6b7280 !important;
        transition: all 0.3s ease !important;
    }
    
    .sidebar .nav .nav-item .nav-link:hover {
        background: #f0f7ff !important;
        color: var(--primary-dark) !important;
    }
    
    .sidebar .nav .nav-item .nav-link:hover i.menu-icon {
        color: var(--primary-dark) !important;
    }
    
    .sidebar .nav .nav-item.active .nav-link {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 15px rgba(77, 163, 255, 0.35) !important;
    }
    
    .sidebar .nav .nav-item.active .nav-link i.menu-icon {
        color: #ffffff !important;
    }
    
    .sidebar .nav .nav-category {
        margin: 20px 25px 10px 25px !important;
        font-size: 0.75rem !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        color: #6b7280 !important;
        letter-spacing: 1px !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }

    .sidebar .nav .nav-item .nav-link .menu-title {
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }

    /* Override buttons */
    .btn-primary {
        background-color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
        color: #ffffff !important;
        border-radius: 30px !important;
        padding: 10px 24px !important;
        font-weight: 600 !important;
        transition: all 0.3s ease !important;
    }
    
    .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
        background-color: var(--primary-dark) !important;
        border-color: var(--primary-dark) !important;
        box-shadow: 0 4px 15px rgba(44, 138, 232, 0.35) !important;
        transform: translateY(-2px);
    }

    .btn-info {
        background-color: #6366f1 !important; /* Beautiful Indigo */
        border-color: #6366f1 !important;
        color: #ffffff !important;
        border-radius: 30px !important;
        padding: 10px 24px !important;
        font-weight: 600 !important;
        transition: all 0.3s ease !important;
    }

    .btn-info:hover {
        background-color: #4f46e5 !important;
        border-color: #4f46e5 !important;
        box-shadow: 0 4px 15px rgba(79, 70, 229, 0.35) !important;
        transform: translateY(-2px);
    }

    .btn-success {
        background-color: #10b981 !important; /* Elegant Emerald */
        border-color: #10b981 !important;
        color: #ffffff !important;
        border-radius: 30px !important;
        padding: 10px 24px !important;
        font-weight: 600 !important;
        transition: all 0.3s ease !important;
    }

    .btn-success:hover {
        background-color: #059669 !important;
        border-color: #059669 !important;
        box-shadow: 0 4px 15px rgba(5, 150, 105, 0.35) !important;
        transform: translateY(-2px);
    }

    /* Cards Styling */
    .card-rounded {
        border-radius: 20px !important;
        border: 1px solid rgba(77, 163, 255, 0.08) !important;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.02) !important;
        transition: transform 0.3s ease, box-shadow 0.3s ease !important;
    }
    
    .card-rounded:hover {
        transform: translateY(-5px) !important;
        box-shadow: 0 12px 30px rgba(77, 163, 255, 0.08) !important;
        border-color: rgba(77, 163, 255, 0.2) !important;
    }

    .statistics-details div {
        background: #ffffff !important;
        padding: 1.5rem !important;
        border-radius: 20px !important;
        box-shadow: 0 2px 12px rgba(0,0,0,0.01) !important;
        flex: 1;
        margin: 0 6px;
        text-align: center;
        border: 1px solid rgba(77, 163, 255, 0.08) !important;
        transition: all 0.3s ease !important;
    }

    .statistics-details div:hover {
        transform: translateY(-3px) !important;
        box-shadow: 0 8px 25px rgba(77, 163, 255, 0.08) !important;
        border-color: rgba(77, 163, 255, 0.2) !important;
    }

    .statistics-title {
        font-size: 0.85rem !important;
        color: #6b7280 !important;
        font-weight: 600 !important;
        margin-bottom: 8px !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .rate-percentage {
        font-size: 1.75rem !important;
        font-weight: 800 !important;
        color: var(--text-dark) !important;
    }

    /* Badges */
    .badge-opacity-success { background: rgba(16, 185, 129, 0.1) !important; color: #10b981 !important; border: 1px solid rgba(16, 185, 129, 0.2) !important; }
    .badge-opacity-warning { background: rgba(245, 158, 11, 0.1) !important; color: #f59e0b !important; border: 1px solid rgba(245, 158, 11, 0.2) !important; }
    .badge-opacity-danger { background: rgba(239, 68, 68, 0.1) !important; color: #ef4444 !important; border: 1px solid rgba(239, 68, 68, 0.2) !important; }
    .badge-opacity-info { background: rgba(77, 163, 255, 0.1) !important; color: var(--primary-dark) !important; border: 1px solid rgba(77, 163, 255, 0.2) !important; }
    
    .table thead th {
        background-color: #f9fafb !important;
        color: #4b5563 !important;
        font-weight: 700 !important;
        text-transform: uppercase;
        font-size: 0.75rem !important;
        letter-spacing: 0.05em;
        border-top: none !important;
    }

    /* Gradients for participant cards */
    .bg-gradient-blue {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%) !important;
    }
    
    .bg-gradient-blue-dark {
        background: linear-gradient(135deg, #10b981 0%, #34d399 100%) !important;
    }

    .bg-translucent-white {
        background-color: rgba(255, 255, 255, 0.2) !important;
    }

    .premium-swal {
        border-radius: 20px !important;
        font-family: 'Outfit', sans-serif !important;
    }
    .swal2-popup {
        padding: 2.5rem 1.5rem !important;
    }
    .swal2-title {
        font-size: 1.25rem !important;
        font-weight: 700 !important;
        color: #1f2937 !important;
    }
    .swal2-html-container {
        font-size: 0.95rem !important;
        color: #4b5563 !important;
    }
  </style>
</head>

<body class="with-welcome-text">
  <div class="container-scroller">
    @include('partials.main.navbar')
    <div class="container-fluid page-body-wrapper">
      @include('partials.main.sidebar')
      <div class="main-panel">
        <main class="content-wrapper">
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false,
            padding: '2em',
            customClass: {
                popup: 'premium-swal'
            }
        });
    });
</script>
@endif

@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: "{{ session('error') }}",
            padding: '2em',
            customClass: {
                popup: 'premium-swal'
            }
        });
    });
</script>
@endif

@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'warning',
            title: 'Terjadi Kesalahan',
            html: `
                <ul class="text-start mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `,
            padding: '2em',
            customClass: {
                popup: 'premium-swal'
            }
        });
    });
</script>
@endif

          @yield('content')
        </main>
      </div>
    </div>
  </div>
  <!-- plugins:js -->
  <script src="{{ asset('dist/assets/vendors/js/vendor.bundle.base.js') }}"></script>
  <script src="{{ asset('dist/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="{{ asset('dist/assets/vendors/chart.js/chart.umd.js') }}"></script>
  <script src="{{ asset('dist/assets/vendors/progressbar.js/progressbar.min.js') }}"></script>
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="{{ asset('dist/assets/js/off-canvas.js') }}"></script>
  <script src="{{ asset('dist/assets/js/template.js') }}"></script>
  <script src="{{ asset('dist/assets/js/settings.js') }}"></script>
  <script src="{{ asset('dist/assets/js/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('dist/assets/js/todolist.js') }}"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="{{ asset('dist/assets/js/jquery.cookie.js') }}" type="text/javascript"></script>
  <script src="{{ asset('dist/assets/js/dashboard.js') }}"></script>
  
  <script>
    // Detect and set user timezone
    document.addEventListener('DOMContentLoaded', function() {
        const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        const currentCookie = document.cookie.split('; ').find(row => row.startsWith('user_timezone='));
        
        if (!currentCookie || currentCookie.split('=')[1] !== timezone) {
            document.cookie = "user_timezone=" + timezone + "; path=/; max-age=31536000; SameSite=Lax";
            // Refresh if first time or changed to ensure server-side consistency
            if (!currentCookie) {
                location.reload();
            }
        }
    });

    // Global SweetAlert Delete Confirmation
    document.addEventListener('click', function(e) {
        let target = e.target.closest('[onclick*="return confirm"]');
        if (target) {
            e.preventDefault();
            e.stopPropagation();
            
            let form = target.closest('form');
            let message = target.getAttribute('onclick').match(/'([^']+)'/)[1] || "Yakin ingin menghapus?";
            
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus Sekarang',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'premium-swal'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Temporarily remove onclick to avoid recursion and submit
                    let oldOnclick = target.getAttribute('onclick');
                    target.setAttribute('onclick', '');
                    form.submit();
                }
            });
        }
    });
  </script>
  <!-- <script src="{{ asset('dist/assets/js/Chart.roundedBarCharts.js') }}"></script> -->
  <!-- End custom js for this page-->

</body>

</html>