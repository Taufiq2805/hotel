<header class="topbar">
  <div class="with-vertical">
    <nav class="navbar navbar-expand-lg p-0">
      <ul class="navbar-nav">
        <li class="nav-item nav-icon-hover-bg rounded-circle ms-n2">
          <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
            <i class="ti ti-menu-2"></i>
          </a>
        </li>
      </ul>

      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav align-items-center">

          {{-- Tombol Mode Gelap / Terang --}}
          <li class="nav-item nav-icon-hover-bg rounded-circle">
            <a class="nav-link moon dark-layout" href="javascript:void(0)">
              <i class="ti ti-moon"></i>
            </a>
            <a class="nav-link sun light-layout" href="javascript:void(0)">
              <i class="ti ti-sun"></i>
            </a>
          </li>

          {{-- Notifikasi Sederhana --}}
          <li class="nav-item nav-icon-hover-bg rounded-circle dropdown">
            <a class="nav-link position-relative" href="#" id="notifDropdown" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="ti ti-bell-ringing"></i>
              <div class="notification bg-primary rounded-circle"></div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notifDropdown">
              <li><h6 class="dropdown-header">Notifikasi</h6></li>
              <li><a class="dropdown-item" href="#">Belum ada notifikasi baru</a></li>
            </ul>
          </li>

          {{-- Dropdown Profil --}}
          <li class="nav-item dropdown">
            <a class="nav-link pe-0" href="#" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
              <div class="d-flex align-items-center">
                <div class="user-profile-img">
                  <img src="{{ asset('assets=/images/profile/user-1.jpg') }}" class="rounded-circle" width="35" height="35" alt="User" />
                </div>
              </div>
            </a>

            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
              <li class="px-3 py-2 text-center border-bottom">
                <img src="{{ asset('assets=/images/profile/user-1.jpg') }}" class="rounded-circle mb-2" width="70" height="70" alt="User" />
                <h6 class="mb-0 fw-semibold">{{ Auth::user()->name ?? 'User' }}</h6>
                <small class="text-muted">{{ Auth::user()->role ?? '' }}</small>
              </li>
              <li>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <i class="ti ti-user me-2"></i> Profil Saya
                </a>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <a class="dropdown-item d-flex align-items-center text-danger" href="{{ route('logout') }}"
                  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  <i class="ti ti-logout me-2"></i> Keluar
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                  @csrf
                </form>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </div>
</header>
