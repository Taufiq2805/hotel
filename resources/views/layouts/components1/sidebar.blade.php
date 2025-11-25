<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2  bg-white my-2" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand px-4 py-3 m-0" href=" https://demos.creative-tim.com/material-dashboard/pages/dashboard " target="_blank">
        <img src="{{ asset('assets-/img/logo.png')}}" class="navbar-brand-img" width="26" height="26" alt="main_logo">
        <span class="ms-1 text-sm text-dark">Grand Luxury</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
       <li class="menu-item">
    <a class="nav-link {{ request()->routeIs('resepsionis.dashboard') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" 
       href="{{ route('resepsionis.dashboard') }}">
        <i class="material-symbols-rounded opacity-5">dashboard</i>
        <span class="nav-link-text ms-1">Dashboard</span>
    </a>
</li>

<li class="menu-item">
    <a class="nav-link {{ request()->routeIs('resepsionis.reservasi.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" 
       href="{{ route('resepsionis.reservasi.index') }}">
        <i class="material-symbols-rounded opacity-5">table_view</i>
        <span class="nav-link-text ms-1">Reservasi</span>
    </a>
</li>

<li class="menu-item">
    <a class="nav-link {{ request()->routeIs('resepsionis.informasi.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" 
       href="{{ route('resepsionis.informasi.index') }}">
        <i class="material-symbols-rounded opacity-5">info</i>
        <span class="nav-link-text ms-1">Informasi</span>
    </a>
</li>
      </ul>
    </div>
  </aside>