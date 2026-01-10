<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Kedai Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            background: #343a40;
            color: #fff;
            transition: all 0.3s;
            min-height: 100vh;
            z-index: 1000;
            position: sticky;
            top: 0;
        }

        #sidebar.active {
            margin-left: -250px;
        }

        .sidebar-header {
            padding: 20px;
            background: #212529;
            text-align: center;
        }

        .admin-profile {
            background: #e67e22;
            padding: 20px;
            color: white;
        }

        #sidebar ul.components {
            padding: 0;
        }

        #sidebar ul li a {
            padding: 15px 20px;
            display: block;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        #sidebar ul li a:hover,
        #sidebar ul li.active>a {
            color: #fff;
            background: #e67e22;
        }

        /* Container Detail Meja di Sidebar */
        #tableDetailSidebar {
            background: rgba(0, 0, 0, 0.2);
            margin: 15px;
            border-radius: 8px;
            border: 1px dashed rgba(255,255,255,0.2);
        }

        /* Content Styling */
        #content {
            width: 100%;
            transition: all 0.3s;
            min-height: 100vh;
        }

        .top-navbar {
            background: #fff;
            padding: 15px 20px;
            border-bottom: 1px solid #dee2e6;
        }

        @media (max-width: 768px) {
            #sidebar { margin-left: -250px; }
            #sidebar.active { margin-left: 0; }
        }
    </style>
</head>

<body>

    <div class="wrapper">
        <nav id="sidebar">
            <div class="sidebar-header">
                <h5 class="fw-bold mb-0">KEDAI ADMIN</h5>
            </div>

            <div class="admin-profile text-center">
                <h6 class="fw-bold mb-0">ADMIN RESTO</h6>
                <small>Control Panel</small>
            </div>

            <ul class="list-unstyled components">
                <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}"><i class="fas fa-chart-line me-2"></i> DASHBOARD</a>
                </li>
                <li class="{{ Request::is('admin/menus*') ? 'active' : '' }}">
                    <a href="{{ route('admin.menus.index') }}"><i class="fas fa-utensils me-2"></i> MANAJEMEN MENU</a>
                </li>
                <li class="{{ Request::is('admin/tables*') ? 'active' : '' }}">
                    <a href="{{ route('admin.tables.index') }}"><i class="fas fa-table me-2"></i> DAFTAR MEJA</a>
                </li>

                <li class="small text-muted ms-3 mt-3 mb-1 text-uppercase" style="font-size: 0.7rem;">Data Transaksi</li>

                <li class="{{ request('view') == 'all' ? 'active' : '' }}">
                    <a href="{{ route('admin.history', ['view' => 'all']) }}">
                        <i class="fas fa-receipt me-2"></i> SEMUA TRANSAKSI
                    </a>
                </li>

                <li class="{{ request('view') == 'summary' ? 'active' : '' }}">
                    <a href="{{ route('admin.history', ['view' => 'summary']) }}">
                        <i class="fas fa-file-invoice-dollar me-2"></i> KESIMPULAN OMZET
                    </a>
                </li>
            </ul>

            <div id="tableDetailSidebar" class="d-none p-3">
                <div id="sidebarContent">
                    </div>
            </div>
        </nav>

        <div id="content">
            <nav class="top-navbar shadow-sm d-flex align-items-center">
                <button type="button" id="sidebarCollapse" class="btn btn-dark btn-sm">
                    <i class="fas fa-bars"></i>
                </button>
                <span class="ms-3 fw-bold text-dark text-uppercase">Kedai Admin Resto</span>
            </nav>

            <div class="container-fluid py-4">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#sidebarCollapse').on('click', function() {
                $('#sidebar').toggleClass('active');
            });
        });
    </script>

</body>
</html>