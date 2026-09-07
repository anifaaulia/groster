<!-- partial:partials/_sidebar.html -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item">
      <a class="nav-link" href="{{ route('dashboard.index') }}">
        <i class="mdi mdi-chart-line menu-icon"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>

    {{-- Admin Only --}}
    @role('admin')
    <li class="nav-item nav-category">Data Master</li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('institutions.index') }}">
        <i class="mdi mdi-domain menu-icon"></i>
        <span class="menu-title">Institusi</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('facilities.index') }}">
        <i class="mdi mdi-tools menu-icon"></i>
        <span class="menu-title">Fasilitas</span>
      </a>
    </li>
    @endrole

    {{-- Shared Menu --}}
    @role(['admin', 'pic', 'participant'])
    <li class="nav-item nav-category">Informasi Fasilitas</li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('rooms.index') }}">
        <i class="mdi mdi-home-outline menu-icon"></i>
        <span class="menu-title">Ruangan</span>
      </a>
    </li>
    @endrole

    {{-- Admin & PIC --}}
    @role(['admin', 'pic'])
    <li class="nav-item nav-category">Ruangan & Acara</li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('roombookings.index') }}">
        <i class="mdi mdi-calendar-clock menu-icon"></i>
        <span class="menu-title">Peminjaman Ruangan</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('events.index') }}">
        <i class="mdi mdi-calendar-text menu-icon"></i>
        <span class="menu-title">Acara</span>
      </a>
    </li>
    {{-- Kehadiran dikelola di dalam halaman detail Acara --}}
    @endrole

    {{-- Participant Only --}}
    @role('participant')
    <li class="nav-item nav-category">Menu Peserta</li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('user.events') }}">
        <i class="mdi mdi-calendar-star menu-icon"></i>
        <span class="menu-title">Acara Saya</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('user.profile') }}">
        <i class="mdi mdi-account-card menu-icon"></i>
        <span class="menu-title">QR Saya / Profil</span>
      </a>
    </li>
    @endrole

    {{-- Admin Only - Pengaturan --}}
    @role('admin')
    <li class="nav-item nav-category">Pengaturan</li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('users.index') }}">
        <i class="mdi mdi-account-multiple menu-icon"></i>
        <span class="menu-title">Pengguna</span>
      </a>
    </li>
    @endrole
  </ul>
</nav>
<!-- partial -->
