<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G-Roster - Manajemen Ruangan & Kehadiran Digital</title>

    <!-- Meta SEO -->
    <meta name="description"
        content="G-Roster: Platform manajemen ruangan dan kehadiran berbasis QR Code untuk Yayasan Gaeni Moentari Nusantara.">
    <meta name="keywords" content="manajemen ruangan, absensi digital, QR code, yayasan pendidikan, G-Roster">
    <meta name="author" content="G-Roster Team">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('/css/style.css') }}">

    <!-- Favicon (Emoji for now) -->
    <link rel="icon"
        href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📘</text></svg>">
</head>

<body id="home" data-bs-spy="scroll" data-bs-target="#mainNav" data-bs-offset="100">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="#">
                <span class="fs-4">📘</span> G-Roster
            </a>
            <button class="navbar-toggler shadow-none border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="bi bi-list fs-3"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto my-3 my-lg-0 gap-lg-3">
                    <li class="nav-item"><a class="nav-link" href="#home">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#features">Fitur</a></li>
                    <li class="nav-item"><a class="nav-link" href="#how-it-works">Cara Kerja</a></li>
                </ul>
                <a href="{{ route('login') }}" class="btn btn-primary ms-lg-3 rounded-pill px-4">Masuk</a>
            </div>
        </div>
    </nav>

    <main>
    <!-- Hero Section -->
    <header class="hero-section d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 hero-content fade-in-up">
                    <div class="badge bg-light text-primary border border-primary-subtle mb-3 px-3 py-2 rounded-pill">
                        <i class="bi bi-stars me-1"></i> Solusi Digital Yayasan
                    </div>
                    <h1 class="display-4 fw-bold mb-3 hero-title">Manajemen Ruangan & Kehadiran Digital
                    </h1>
                    <p class="lead mb-4 text-secondary">
                        G-Roster membantu <strong>Yayasan Gaeni Moentari Nusantara</strong> mengelola dan mengoptimalkan
                        ruangan serta absensi secara efisien, transparan, dan modern dengan teknologi QR Code.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg rounded-pill px-4 shadow-sm hover-up">Mulai Sekarang <i class="bi bi-arrow-right ms-2"></i></a>
                    </div>
                    <div class="mt-4 pt-3 d-flex align-items-center gap-4 text-muted small">
                        <span><i class="bi bi-check-circle-fill text-success me-1"></i> Terintegrasi</span>
                        <span><i class="bi bi-check-circle-fill text-success me-1"></i> Real-time</span>
                        <span><i class="bi bi-check-circle-fill text-success me-1"></i> Paperless</span>
                    </div>
                </div>
                <div class="col-lg-6 text-center fade-in-up" style="animation-delay: 0.2s;">
                    <!-- Abstract Illustration Placeholder using CSS/SVG -->
                    <div class="hero-image-placeholder position-relative">
                        <div class="absolute-blob blob-1"></div>
                        <div class="absolute-blob blob-2"></div>
                        <img src="https://placehold.co/600x400/e6f2ff/4da3ff?text=GEMA+Roster\n(G-Roster)"
                            alt="Ilustrasi G-Roster"
                            class="img-fluid rounded-4 shadow-lg position-relative z-index-1 border border-white border-5">

                        <!-- Floating Card 1 -->
                        <div class="floating-card card-1 p-3 bg-white rounded-4 shadow border border-light">
                            <div class="d-flex align-items-center gap-3">
                                <div class="icon-box bg-success bg-opacity-10 text-success rounded-circle p-2">
                                    <i class="bi bi-qr-code-scan fs-4"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fw-bold small">Absensi Berhasil</p>
                                    <p class="mb-0 text-muted extra-small">08:00 WIB</p>
                                </div>
                            </div>
                        </div>

                        <!-- Floating Card 2 -->
                        <div class="floating-card card-2 p-3 bg-white rounded-4 shadow border border-light">
                            <div class="d-flex align-items-center gap-3">
                                <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle p-2">
                                    <i class="bi bi-calendar-check fs-4"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fw-bold small">R. Rapat Utama</p>
                                    <p class="mb-0 text-muted extra-small">Terjadwal: Rapat Internal</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Features Section -->
    <section id="features" class="section-padding bg-light">
        <div class="container">
            <div class="text-center mb-5 fade-in-up">
                <span class="text-primary fw-bold text-uppercase tracking-wider">Fitur Utama</span>
                <h2 class="display-6 fw-bold mt-2">Solusi Lengkap Manajemen</h2>
                <div class="divider mx-auto mt-3"></div>
            </div>

            <div class="row g-4">
                <!-- Feature 1 -->
                <div class="col-md-6 col-lg-4 fade-in-up" style="animation-delay: 0.1s;">
                    <div class="card h-100 feature-card border-0 shadow-sm p-4 rounded-4">
                        <div
                            class="feature-icon mb-4 bg-info bg-opacity-10 text-primary rounded-3 d-inline-flex align-items-center justify-content-center">
                            <i class="bi bi-building fs-3"></i>
                        </div>
                        <h3 class="fw-bold mb-3">Manajemen Ruangan</h3>
                        <p class="text-muted mb-0">Kelola ketersediaan, fasilitas dan penggunaan ruangan secara terpusat
                            untuk
                            menghindari bentrok jadwal.</p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="col-md-6 col-lg-4 fade-in-up" style="animation-delay: 0.2s;">
                    <div class="card h-100 feature-card border-0 shadow-sm p-4 rounded-4">
                        <div
                            class="feature-icon mb-4 bg-info bg-opacity-10 text-info rounded-3 d-inline-flex align-items-center justify-content-center">
                            <i class="bi bi-calendar-week fs-3"></i>
                        </div>
                        <h3 class="fw-bold mb-3">Penjadwalan Kegiatan</h3>
                        <p class="text-muted mb-0">Atur jadwal kegiatan yayasan dengan mudah, lengkap dengan informasi
                            waktu, tempat, dan PIC.</p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="col-md-6 col-lg-4 fade-in-up" style="animation-delay: 0.3s;">
                    <div class="card h-100 feature-card border-0 shadow-sm p-4 rounded-4">
                        <div
                            class="feature-icon mb-4 bg-success bg-opacity-10 text-success rounded-3 d-inline-flex align-items-center justify-content-center">
                            <i class="bi bi-qr-code fs-3"></i>
                        </div>
                        <h3 class="fw-bold mb-3">Absensi QR Code</h3>
                        <p class="text-muted mb-0">Sistem absensi modern menggunakan QR Code unik untuk setiap
                            peserta/karyawan. Cepat dan akurat.</p>
                    </div>
                </div>

                <!-- Feature 4 -->
                <div class="col-md-6 col-lg-4 fade-in-up" style="animation-delay: 0.1s;">
                    <div class="card h-100 feature-card border-0 shadow-sm p-4 rounded-4">
                        <div
                            class="feature-icon mb-4 bg-warning bg-opacity-10 text-warning rounded-3 d-inline-flex align-items-center justify-content-center">
                            <i class="bi bi-graph-up-arrow fs-3"></i>
                        </div>
                        <h3 class="fw-bold mb-3">Monitoring Real-time</h3>
                        <p class="text-muted mb-0">Pantau kehadiran dan penggunaan ruangan secara langsung melalui
                            dashboard aplikasi.</p>
                    </div>
                </div>

                <!-- Feature 5 -->
                <div class="col-md-6 col-lg-4 fade-in-up" style="animation-delay: 0.2s;">
                    <div class="card h-100 feature-card border-0 shadow-sm p-4 rounded-4">
                        <div
                            class="feature-icon mb-4 bg-danger bg-opacity-10 text-danger rounded-3 d-inline-flex align-items-center justify-content-center">
                            <i class="bi bi-file-earmark-text fs-3"></i>
                        </div>
                        <h3 class="fw-bold mb-3">Rekap & Laporan</h3>
                        <p class="text-muted mb-0">Unduh data penggunaan ruangan dan rekap kehadiran dalam format yang
                            rapi dan terdokumentasi.</p>
                    </div>
                </div>

                <!-- Feature 6 -->
                <div class="col-md-6 col-lg-4 fade-in-up" style="animation-delay: 0.3s;">
                    <div class="card h-100 feature-card border-0 shadow-sm p-4 rounded-4">
                        <div
                            class="feature-icon mb-4 bg-secondary bg-opacity-10 text-secondary rounded-3 d-inline-flex align-items-center justify-content-center">
                            <i class="bi bi-shield-lock fs-3"></i>
                        </div>
                        <h3 class="fw-bold mb-3">Data Aman</h3>
                        <p class="text-muted mb-0">Penyimpanan data yang aman dan terpusat, mudah diakses kapan
                            saja oleh admin yang berwenang.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How it Works Section -->
    <section id="how-it-works" class="section-padding">
        <div class="container">
            <div class="text-center mb-5 fade-in-up">
                <span class="text-primary fw-bold text-uppercase tracking-wider">Cara Kerja</span>
                <h2 class="display-6 fw-bold mt-2">Alur Kerja Sistem</h2>
                <div class="divider mx-auto mt-3"></div>
            </div>

            <div class="row justify-content-center">
                <!-- Connecting Line (Desktop) -->
                <div class="d-none d-lg-block position-absolute top-50 start-0 w-100 translate-middle-y z-n1"
                    style="border-top: 2px dashed #e0e0e0; height: 1px;"></div>

                <!-- Step 1 -->
                <div class="col-md-6 col-lg-3 text-center step-item fade-in-up" style="animation-delay: 0.1s;">
                    <div
                        class="step-icon bg-white text-primary rounded-circle border border-2 border-primary d-inline-flex align-items-center justify-content-center shadow-sm mb-3">
                        <i class="bi bi-pc-display-horizontal fs-4"></i>
                    </div>
                    <h3 class="fw-bold">1. Atur Jadwal</h3>
                    <p class="text-muted small px-2">Admin mengatur ruangan dan jadwal berdasarkan pengajuan PIC.</p>
                </div>

                <!-- Step 2
                <div class="col-md-6 col-lg-3 text-center step-item fade-in-up" style="animation-delay: 0.2s;">
                    <div
                        class="step-icon bg-white text-primary rounded-circle border border-2 border-primary d-inline-flex align-items-center justify-content-center shadow-sm mb-3">
                        <i class="bi bi-qr-code fs-4"></i>
                    </div>
                    <h3 class="fw-bold">2. Generate QR</h3>
                    <p class="text-muted small px-2">Sistem otomatis membuat QR Code unik untuk sesi tersebut.</p>
                </div> -->

                <!-- Step 2 -->
                <div class="col-md-6 col-lg-3 text-center step-item fade-in-up" style="animation-delay: 0.3s;">
                    <div
                        class="step-icon bg-white text-primary rounded-circle border border-2 border-primary d-inline-flex align-items-center justify-content-center shadow-sm mb-3">
                        <i class="bi bi-phone fs-4"></i>
                    </div>
                    <h3 class="fw-bold">2. Scan Absensi</h3>
                    <p class="text-muted small px-2">Peserta memindai QR Code pada scanner.</p>
                </div>

                <!-- Step 3 -->
                <div class="col-md-6 col-lg-3 text-center step-item fade-in-up" style="animation-delay: 0.4s;">
                    <div
                        class="step-icon bg-white text-primary rounded-circle border border-2 border-primary d-inline-flex align-items-center justify-content-center shadow-sm mb-3">
                        <i class="bi bi-database-check fs-4"></i>
                    </div>
                    <h3 class="fw-bold">3. Data Tersimpan</h3>
                    <p class="text-muted small px-2">Kehadiran tercatat real-time di database admin.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Advantages Section -->
    <section class="section-padding bg-primary text-white">
        <div class="container text-center">
            <h2 class="fw-bold mb-5 fade-in-up">Mengapa Memilih G-Roster?</h2>
            <div class="row g-4 justify-content-center">
                <div class="col-6 col-md-3 fade-in-up">
                    <i class="bi bi-clock-history display-4 mb-3 d-block"></i>
                    <h3 class="fw-bold">Real-time</h3>
                    <p class="small opacity-75">Data terupdate detik itu juga.</p>
                </div>
                <div class="col-6 col-md-3 fade-in-up">
                    <i class="bi bi-emoji-smile display-4 mb-3 d-block"></i>
                    <h3 class="fw-bold">Mudah</h3>
                    <p class="small opacity-75">User interface yang intuitif.</p>
                </div>
                <div class="col-6 col-md-3 fade-in-up">
                    <i class="bi bi-paperclip display-4 mb-3 d-block"></i>
                    <h3 class="fw-bold">Paperless</h3>
                    <p class="small opacity-75">Ramah lingkungan, minim kertas.</p>
                </div>
                <div class="col-6 col-md-3 fade-in-up">
                    <i class="bi bi-shield-check display-4 mb-3 d-block"></i>
                    <h3 class="fw-bold">Terpusat</h3>
                    <p class="small opacity-75">Satu pintu untuk semua data.</p>
                </div>
            </div>
        </div>
    </section>
    </main>


    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row g-4 mb-5">
                <div class="col-lg-4">
                    <h4 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <span>📘</span> G-Roster
                    </h4>
                    <p class="text-white-50 small">
                        Platform manajemen modern untuk optimalisasi penggunaan ruangan dan pencatatan kehadiran yang
                        akurat, berdedikasi untuk kemajuan Yayasan Gaeni Moentari Nusantara.
                    </p>
                </div>
                <div class="col-6 col-lg-2 offset-lg-1">
                    <h5 class="fw-bold mb-3 text-primary">Navigasi</h5>
                    <ul class="list-unstyled text-white-50 small d-flex flex-column gap-2">
                        <li><a href="#home" class="text-reset text-decoration-none hover-link">Beranda</a></li>
                        <li><a href="#features" class="text-reset text-decoration-none hover-link">Fitur</a></li>
                        <li><a href="#preview" class="text-reset text-decoration-none hover-link">Preview</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2">
                    <h5 class="fw-bold mb-3 text-primary">Dukungan</h5>
                    <ul class="list-unstyled text-white-50 small d-flex flex-column gap-2">
                        <li><a href="#" class="text-reset text-decoration-none hover-link">Bantuan</a></li>
                        <li><a href="#" class="text-reset text-decoration-none hover-link">Privasi</a></li>
                        <li><a href="#" class="text-reset text-decoration-none hover-link">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h5 class="fw-bold mb-3 text-primary">Ikuti Kami</h5>
                    <div class="d-flex gap-3 social-links">
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle" aria-label="Instagram G-Roster"><i
                                class="bi bi-instagram"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle" aria-label="Facebook G-Roster"><i
                                class="bi bi-facebook"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle" aria-label="LinkedIn G-Roster"><i
                                class="bi bi-linkedin"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-top border-white border-opacity-10 pt-4 text-center">
                <p class="small text-white-50 mb-0">&copy; 2026 G-Roster by Yayasan Gaeni Moentari Nusantara. All rights
                    reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>

</html>