<aside class="left-sidebar with-vertical">
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="../main/index.html" class="text-nowrap logo-img">
            <img src="{{ asset ('assets=/images/logos/dark-logo.svg')}}" class="dark-logo" alt="Logo-Dark" />
            <img src="{{ asset ('assets=/images/logos/light-logo.svg')}}" class="light-logo" alt="Logo-light" />
          </a>
          <a href="javascript:void(0)" class="sidebartoggler ms-auto text-decoration-none fs-5 d-block d-xl-none">
            <i class="ti ti-x"></i>
          </a>
        </div>

        <nav class="sidebar-nav scroll-sidebar" data-simplebar>
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Home</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link {{ request()->routeIs('housekeeping') ? 'active' : '' }}" href="{{ route('housekeeping.dashboard')}}" id="get-url" aria-expanded="false">
                <span>
                  <i class="ti ti-aperture"></i>
                </span>
                <span class="hide-menu">Dashboard</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link {{ request()->routeIs('housekeeping.maintenance.*') ? 'active' : '' }}" href="{{ route('housekeeping.maintenance.index')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-shopping-cart"></i>
                </span>
                <span class="hide-menu">Maintenance</span>
              </a>
            </li>
             <li class="sidebar-item">
              <a class="sidebar-link {{ request()->routeIs('housekeeping.pengeluaran.*') ? 'active' : '' }}" href="{{ route('housekeeping.pengeluaran.index')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-shopping-cart"></i>
                </span>
                <span class="hide-menu">Pengeluaran</span>
              </a>
            </li>
          </ul>
        </nav>

        <!-- ---------------------------------- -->
        <!-- Start Vertical Layout Sidebar -->
        <!-- ---------------------------------- -->
      </div>
    </aside>