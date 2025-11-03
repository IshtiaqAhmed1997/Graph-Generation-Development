<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#1565c0] leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f9ff;
            font-family: 'Poppins', sans-serif;
        }

        .dashboard-container {
            padding: 2rem 0;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            transition: 0.3s ease;
            background: #fff;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            color: #1565c0;
            font-weight: 600;
        }

        .card-text {
            color: #555;
        }

        .welcome-box {
            background: linear-gradient(135deg, #e3f2fd, #ffffff);
            border-left: 5px solid #1565c0;
            border-radius: 10px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            color: #1565c0;
            font-weight: 500;
        }

        .btn-primary {
            background-color: #1565c0;
            border: none;
            border-radius: 8px;
        }

        .btn-primary:hover {
            background-color: #0d47a1;
        }

        .stats-row {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .card-body .display-6 {
            font-size: 2.5rem;
        }

        @media (max-width: 768px) {
            .stats-row {
                flex-direction: column;
            }
        }
    </style>

    <div class="container dashboard-container">

        <!-- Welcome Banner -->
        <div class="welcome-box">
            👋 Welcome back, <strong>{{ Auth::user()->name }}</strong>!
            <span class="ms-2">You’re successfully logged in to the Pharma Portal Dashboard.</span>
        </div>

        <!-- Dashboard Cards -->
        <div class="stats-row">
            <!-- Card 1 -->
            <div class="card flex-fill p-3">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Medicines</h5>
                    <p class="display-6 text-primary fw-bold">128</p>
                    <p class="card-text">Number of medicines available in stock.</p>
                    <button class="btn btn-primary btn-sm">View Details</button>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="card flex-fill p-3">
                <div class="card-body text-center">
                    <h5 class="card-title">New Orders</h5>
                    <p class="display-6 text-primary fw-bold">32</p>
                    <p class="card-text">Pending orders awaiting approval.</p>
                    <button class="btn btn-primary btn-sm">Manage Orders</button>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="card flex-fill p-3">
                <div class="card-body text-center">
                    <h5 class="card-title">Suppliers</h5>
                    <p class="display-6 text-primary fw-bold">14</p>
                    <p class="card-text">Trusted suppliers connected to the system.</p>
                    <button class="btn btn-primary btn-sm">View Suppliers</button>
                </div>
            </div>
        </div>

        <!-- Reports Section -->
        <div class="card mt-5 p-4">
            <h5 class="card-title mb-3">Reports & Insights</h5>
            <p class="text-muted">Generate and download recent activity reports, medicine logs, and supplier details.
            </p>
            <button class="btn btn-primary">Generate Report</button>
        </div>

        <!-- Profile Section -->
        <div class="card mt-5 p-4">
            <h5 class="card-title mb-3">Profile Settings</h5>
            <form>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="name" class="form-control" value="{{ Auth::user()->name }}">
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" class="form-control" value="{{ Auth::user()->email }}">
                    </div>
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-primary mt-3">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</x-app-layout>