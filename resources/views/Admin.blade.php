<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Virtual fashion')</title>
    <link rel="icon" href="https://res.cloudinary.com/dwg2nov2v/image/upload/v1733588039/back_blanc_uqujoo.jpg" type="image/png">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Autres feuilles de style -->
    <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
    <style>
        /* Personnalisation des couleurs et styles */
        .main-sidebar {
            background-color: #072642; /* Une teinte de bleu */
        }

        .main-sidebar .nav-link {
            color: #ffffff; /* Couleur blanche pour le texte */
        }

        .main-sidebar .nav-link:hover {
            background-color: #1e5181; /* Une teinte de bleu plus foncé au survol */
        }

        .main-sidebar .nav-icon {
            color: #ffffff; /* Couleur blanche pour les icônes */
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
        </ul>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </nav>

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="#" class="brand-link">
            <img src="https://res.cloudinary.com/dwg2nov2v/image/upload/v1733588039/back_blanc_uqujoo.jpg" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">Virtual Fashion</span>
        </a>
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-header">Actions</li>
                    <!-- Affichage des liens en fonction du rôle -->
                    @if(Auth::user()->role == 1) <!-- Si administrateur -->
                        <li class="nav-item">
                            <a href="{{ route('categories.index') }}" class="nav-link">
                                <i class="fas fa-tshirt"></i>
                                <p>Category</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('produits.index') }}" class="nav-link">
                                <i class="fas fa-box-open"></i>
                                <p>Product</p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('dashboard.index') }}" class="nav-link">
                                <i class="fas fa-chart-line"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
    <a href="{{ route('livraison.index') }}" class="nav-link">
        <i class="fas fa-truck"></i>
        <p>Deliveries</p>
    </a>
</li>
                    @else <!-- Si client -->
                        <li class="nav-item">
                            <a href="{{ route('client.produits') }}" class="nav-link">
                                <i class="fas fa-store"></i>
                                <p>Shop</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cart.index') }}" class="nav-link">
                                <i class="fas fa-shopping-cart"></i>
                                <p>Shopping Cart</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('commandes.index') }}" class="nav-link">
                                <i class="fas fa-file"></i>
                                <p>Command List</p>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <section class="content">
            @yield('content')
        </section>
    </div>

    <footer class="main-footer">
        <strong>Copyright &copy; 2020-2024 <a href="https://adminlte.io">Virtual Fashion</a>.</strong>
        All rights reserved.
    </footer>

</div>

<!-- Scripts -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('dist/js/adminlte.js') }}"></script>
</body>
</html>
