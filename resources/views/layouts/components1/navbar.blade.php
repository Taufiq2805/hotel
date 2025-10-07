<nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
    <div class="container-fluid py-1 px-3">

        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="#">Pages</a></li>
                <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Dashboard</li>
            </ol>
        </nav>

        {{-- Navbar Right --}}
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">

            {{-- Search Bar --}}
            <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                <div class="input-group input-group-outline">
                    <label class="form-label">Type here...</label>
                    <input type="text" class="form-control">
                </div>
            </div>

            {{-- Navbar Right Icons --}}
            <ul class="navbar-nav d-flex align-items-center justify-content-end">

                {{-- Example Button --}}
                <li class="nav-item d-flex align-items-center">
                    <a class="btn btn-outline-primary btn-sm mb-0 me-3" target="_blank" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                       
                </li>

                {{-- Notifications Icon --}}
                <li class="nav-item dropdown pe-3 d-flex align-items-center">
                    <a href="#" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="material-symbols-rounded">notifications</i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                        {{-- Notifikasi item contoh --}}
                        <li class="mb-2">
                            <a class="dropdown-item border-radius-md" href="#">
                                <div class="d-flex py-1">
                                    <div class="my-auto">
                                        <img src="{{ asset('assets/img/team-2.jpg') }}" class="avatar avatar-sm me-3">
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            <span class="font-weight-bold">New message</span> from Laur
                                        </h6>
                                        <p class="text-xs text-secondary mb-0">
                                            <i class="fa fa-clock me-1"></i> 13 minutes ago
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        {{-- Tambahkan item lainnya di sini --}}
                    </ul>
                </li>

                {{-- Settings Icon --}}
                <li class="nav-item px-3 d-flex align-items-center">
                    <a href="#" class="nav-link text-body p-0">
                        <i class="material-symbols-rounded fixed-plugin-button-nav">settings</i>
                    </a>
                </li>
                </ul>
              </li>
            </ul>
        </div>
    </div>
</nav>
