<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Northwind customer, product, order, and reporting management system">
    <title>@yield('title', 'Dashboard') | Northwind Operations</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body data-page="@yield('page')">
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <aside class="app-sidebar" aria-label="Main navigation">
        <a class="brand" href="{{ route('dashboard') }}">
            <span class="brand-mark" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 20V10" />
                    <path d="M10 20V4" />
                    <path d="M16 20v-7" />
                    <path d="M22 20H2" />
                    <path d="m4 7 6-4 6 7 5-5" />
                </svg>
            </span>
            <span>
                <span class="brand-name">Northwind</span>
                <span class="brand-subtitle">Operations Portal</span>
            </span>
        </a>

        <div class="sidebar-label">Workspace</div>
        <nav class="sidebar-nav">
            <a class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <x-nav-icon name="dashboard" /><span>Dashboard</span>
            </a>
            <a class="sidebar-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}">
                <x-nav-icon name="customers" /><span>Customers</span>
            </a>
            <a class="sidebar-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                <x-nav-icon name="products" /><span>Products</span>
            </a>
            <a class="sidebar-link {{ request()->routeIs('orders.*') ? 'active' : '' }}" href="{{ route('orders.index') }}">
                <x-nav-icon name="orders" /><span>Customer Orders</span>
            </a>
        </nav>

        <div class="sidebar-label">Reports</div>
        <nav class="sidebar-nav">
            <a class="sidebar-link {{ request()->routeIs('reports.sales*') ? 'active' : '' }}" href="{{ route('reports.sales') }}">
                <x-nav-icon name="sales" /><span>Total Sales</span>
            </a>
            <a class="sidebar-link {{ request()->routeIs('reports.inventory') ? 'active' : '' }}" href="{{ route('reports.inventory') }}">
                <x-nav-icon name="inventory" /><span>Inventory</span>
            </a>
        </nav>
    </aside>

    <main class="app-main">
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light mobile-menu-button" id="sidebarToggle" type="button" aria-label="Open menu">Menu</button>
                <div>
                    <p class="topbar-title">@yield('topbar', 'Northwind Management')</p>
                    <span class="topbar-date">Live database workspace</span>
                </div>
            </div>
            <span class="status-pill success"><span class="connection-dot" aria-hidden="true"></span> Database connected</span>
        </header>

        <div class="page-content">
            @yield('content')
        </div>
    </main>

    <div class="toast-container position-fixed top-0 end-0 p-3" id="toastContainer" style="z-index: 1090"></div>
</body>
</html>
