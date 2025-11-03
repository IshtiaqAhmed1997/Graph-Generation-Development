<nav x-data="{ open: false, sidebarCollapsed: false }">
    <style>
        .sidebar {
            width: 250px;
            background: #ffffff;
            color: #1565c0;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            z-index: 1030;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;
            border-bottom: 1px solid #e3f2fd;
        }

        .sidebar-header .logo img {
            height: 40px;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed .sidebar-header .logo {
            display: none;
        }

        .sidebar-header .toggle-btn {
            background: #1565c0;
            border: none;
            color: #fff;
            border-radius: 6px;
            padding: 0.4rem 0.6rem;
            cursor: pointer;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .sidebar-header .toggle-btn:hover {
            background: #0d47a1;
        }

        .sidebar .nav-link {
            color: #1565c0;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.75rem 1.25rem;
            border-radius: 8px;
            margin: 0.25rem 0.75rem;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #e3f2fd;
            color: #0d47a1;
        }

        .sidebar.collapsed .nav-link span {
            display: none;
        }

        .sidebar .nav-link i {
            font-size: 1.2rem;
        }

        .content-wrapper {
            transition: margin-left 0.3s ease;
            margin-left: 250px;
            padding: 1rem;
            background-color: #f5f9ff;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .content-wrapper.collapsed {
            margin-left: 80px;
        }

        /* Mobile */
        @media (max-width: 768px) {
            .sidebar {
                left: -250px;
            }

            .sidebar.active {
                left: 0;
            }

            .content-wrapper {
                margin-left: 0 !important;
            }

            .sidebar-header .toggle-btn {
                background: #1565c0;
            }
        }

        /* Scrollbar Styling */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #bbdefb;
            border-radius: 3px;
        }
    </style>

    <!-- Sidebar -->
    <div :class="{'active': open, 'collapsed': sidebarCollapsed}" class="sidebar" id="sidebarMenu">

        <!-- Header Section (Logo + Button) -->
        <div class="sidebar-header">
            <div class="logo">
                <a href="{{ route('dashboard') }}">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/6/6e/Medical_Symbol_Blue_Cross.svg"
                        alt="Pharma Logo">
                </a>
            </div>

            <button class="toggle-btn" @click="
                if (window.innerWidth < 768) {
                    open = !open;
                } else {
                    sidebarCollapsed = !sidebarCollapsed;
                    document.querySelector('.content-wrapper').classList.toggle('collapsed');
                }
            ">
                <!-- Dynamic icon -->
                <i :class="{
                    'bi bi-list': !sidebarCollapsed && !open,
                    'bi bi-x': sidebarCollapsed || open
                }"></i>
            </button>
        </div>

        <ul class="nav flex-column mt-3">
            <li>
                <a href="{{ route('dashboard') }}"
                    class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
                </a>
            </li>

            <li>
                <a href="{{ route('upload.index') }}"
                    class="nav-link {{ request()->routeIs('upload.index') ? 'active' : '' }}">
                    <i class="bi bi-cloud-upload"></i> <span>Upload File</span>
                </a>
            </li>

            <li>
                <a href="{{ route('raw-records.index') }}"
                    class="nav-link {{ request()->routeIs('raw-records.index') ? 'active' : '' }}">
                    <i class="bi bi-collection"></i> <span>Raw Records</span>
                </a>
            </li>

            <li>
                <a href="{{ route('logs.index') }}"
                    class="nav-link {{ request()->routeIs('logs.index') ? 'active' : '' }}">
                    <i class="bi bi-journal-text"></i> <span>Logs</span>
                </a>
            </li>

            <li>
                <a href="{{ route('charts.index') }}"
                    class="nav-link {{ request()->routeIs('charts.index') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart-line"></i> <span>Charts</span>
                </a>
            </li>

            <!-- Settings Dropdown -->
            <li class="mt-3" x-data="{ openSettings: false }">
                <a href="#" class="nav-link d-flex justify-content-between align-items-center"
                    @click.prevent="openSettings = !openSettings">
                    <span><i class="bi bi-gear"></i> Settings</span>
                    <i :class="openSettings ? 'bi bi-chevron-up' : 'bi bi-chevron-down'"></i>
                </a>

                <div x-show="openSettings" x-transition class="ps-3 mt-1">
                    <a href="{{ route('profile.edit') }}" class="nav-link small py-1">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" class="nav-link small py-1 text-danger"
                            onclick="event.preventDefault(); this.closest('form').submit();">Log Out</a>
                    </form>
                </div>
            </li>
        </ul>
    </div>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</nav>