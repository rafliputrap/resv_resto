<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            overflow-x: hidden;
            background: #f4f7f6;
        }

        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }

        /* Sidebar Styling */
        #sidebar {
            min-width: 250px;
            max-width: 250px;
            background: #1a1a1a;
            /* Gue gelapin biar lebih mewah */
            color: #fff;
            transition: all 0.3s;
            min-height: 100vh;
            z-index: 1030;
            /* Ditinggiin biar gak ketutup elemen content */
            position: sticky;
            top: 0;
        }

        #sidebar.active {
            margin-left: -250px;
        }

        .sidebar-header {
            padding: 25px 20px;
            background: #000;
            text-align: center;
        }

        .admin-profile {
            background: #e67e22;
            /* Warna orange ciri khas lo */
            padding: 20px;
            color: white;
            border-bottom: 3px solid rgba(0, 0, 0, 0.1);
        }

        #sidebar ul.components {
            padding: 10px 0;
        }

        #sidebar ul li a {
            padding: 15px 25px;
            display: block;
            color: #adb5bd;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: 0.2s;
        }

        #sidebar ul li a:hover,
        #sidebar ul li.active>a {
            color: #fff;
            background: rgba(230, 126, 34, 0.1);
            /* Efek orange tipis */
            border-left: 4px solid #e67e22;
        }

        /* Content Styling */
        #content {
            width: 100%;
            transition: all 0.3s;
            min-height: 100vh;
            position: relative;
            z-index: 1;
        }

        .top-navbar {
            background: #fff;
            padding: 12px 25px;
            border-bottom: 1px solid #e0e0e0;
            height: 60px;
        }

        /* Fix buat modal supaya gak ketutup sidebar */
        .modal {
            z-index: 2000 !important;
        }

        .modal-backdrop {
            z-index: 1040 !important;
        }

        @media (max-width: 768px) {
            #sidebar {
                margin-left: -250px;
            }

            #sidebar.active {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

    <div class="wrapper">
        <nav id="sidebar">
            <div class="sidebar-header">
                <h5 class="fw-bold mb-0" style="letter-spacing: 2px;">Warehouse Cafe</h5>
            </div>

            <div class="admin-profile text-center">
                <h6 class="fw-bold mb-0">ADMIN RESTO</h6>
                <small class="opacity-75">Warehouse</small>
            </div>

            <ul class="list-unstyled components">
                <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}"><i class="fas fa-chart-line me-3"></i> DASHBOARD</a>
                </li>
                <li class="{{ Request::is('admin/menus*') ? 'active' : '' }}">
                    <a href="{{ route('admin.menus.index') }}"><i class="fas fa-utensils me-3"></i> MANAJEMEN MENU</a>
                </li>
                <li class="{{ Request::is('admin/tables*') ? 'active' : '' }}">
                    <a href="{{ route('admin.tables.index') }}"><i class="fas fa-table me-3"></i> DAFTAR MEJA</a>
                </li>

                <li class="small text-muted ms-4 mt-4 mb-2 text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">Laporan</li>

                <li class="{{ request('view') == 'all' ? 'active' : '' }}">
                    <a href="{{ route('admin.history', ['view' => 'all']) }}">
                        <i class="fas fa-receipt me-3"></i> SEMUA TRANSAKSI
                    </a>
                </li>

                <li class="{{ request('view') == 'summary' ? 'active' : '' }}">
                    <a href="{{ route('admin.history', ['view' => 'summary']) }}">
                        <i class="fas fa-file-invoice-dollar me-3"></i> KESIMPULAN OMZET
                    </a>
                </li>
            </ul>
        </nav>

        <div id="content">
            <nav class="top-navbar shadow-sm d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <button type="button" id="sidebarCollapse" class="btn btn-outline-dark btn-sm border-0">
                        <i class="fas fa-bars fa-lg"></i>
                    </button>
                    <span class="ms-3 fw-bold text-secondary small text-uppercase">Admin Resto</span>
                </div>

                <form action="{{ route('admin.logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-link text-danger text-decoration-none fw-bold small">
                        <i class="fas fa-power-off me-1"></i> LOGOUT
                    </button>
                </form>
            </nav>

            <div class="container-fluid py-4">
                {{-- Bagian buat nampilin Alert Success/Error --}}
                @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Script Toggle Sidebar
            $('#sidebarCollapse').on('click', function(e) {
                e.preventDefault();
                $('#sidebar').toggleClass('active');
            });

            // Auto-hide alert setelah 3 detik
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 3000);
        });
    </script>

</body>

</html>