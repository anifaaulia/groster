<!-- partial:partials/_navbar.html -->
<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
    <div class="me-3">
      <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
        <span class="icon-menu"></span>
      </button>
    </div>
    <div>
      <a class="navbar-brand brand-logo fw-bold d-flex align-items-center gap-2 text-decoration-none" href="{{ route('dashboard.index') }}" style="color: var(--primary-dark); font-family: 'Outfit', sans-serif;">
        <span class="fs-4">📘</span> <span class="fw-bold fs-4" style="letter-spacing: 0.5px; color: #2c8ae8;">G-Roster</span>
      </a>
      <a class="navbar-brand brand-logo-mini fw-bold text-decoration-none" href="{{ route('dashboard.index') }}" style="color: var(--primary-dark);">
        <span class="fs-4">📘</span>
      </a>
    </div>
  </div>
  <div class="navbar-menu-wrapper d-flex align-items-top">
    <ul class="navbar-nav">
      <li class="nav-item fw-semibold d-none d-lg-block ms-0">
        <h1 class="welcome-text">Halo, <span class="text-black fw-bold">{{ Auth::check() ? Auth::user()->name : 'Pengguna' }}</span></h1>
        <h2 class="welcome-sub-text">Selamat datang di G-Roster</h2>
      </li>
    </ul>
    <ul class="navbar-nav ms-auto">
      <li class="nav-item dropdown d-block user-dropdown">
        <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
          <img class="img-xs rounded-circle" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=515151&color=fff"
            alt="Profile image"> </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
          <div class="dropdown-header text-center">
            <img class="img-md rounded-circle" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=515151&color=fff"
              alt="Profile image">
            <p class="mb-1 mt-3 fw-semibold">{{ Auth::check() ? Auth::user()->name : 'User' }}</p>
            <p class="fw-light text-muted mb-0">{{ Auth::check() ? Auth::user()->email : '' }}</p>
          </div>
          <form method="POST" action="{{ route('logout') }}" id="logout-form">
            @csrf
            <a class="dropdown-item" href="javascript:void(0);" onclick="document.getElementById('logout-form').submit();">
              <i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Sign Out
            </a>
          </form>
        </div>
      </li>
    </ul>
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
      data-bs-toggle="offcanvas">
      <span class="mdi mdi-menu"></span>
    </button>
  </div>
</nav>
<!-- partial -->