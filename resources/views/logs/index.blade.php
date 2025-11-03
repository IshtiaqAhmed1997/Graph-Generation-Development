<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#1565c0] leading-tight">
            {{ __('Upload Logs') }}
        </h2>
    </x-slot>

    <style>
        /* 🌿 Pharma Upload Logs Styles */
        .logs-section {
            background-color: #f5f9ff;
            min-height: calc(100vh - 120px);
            padding: 2rem 1rem;
        }

        .logs-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
            transition: all 0.3s ease;
        }

        .logs-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .table th {
            background-color: #1565c0;
            color: #fff;
            text-align: center;
            vertical-align: middle;
        }

        .table td {
            text-align: center;
            vertical-align: middle;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f0f7ff;
        }

        .filter-form .form-control,
        .filter-form .form-select {
            border-radius: 8px;
            border: 1px solid #cfd8dc;
        }

        .btn-primary {
            background-color: #1565c0;
            border: none;
            border-radius: 8px;
        }

        .btn-primary:hover {
            background-color: #0d47a1;
        }

        .badge {
            font-size: 0.85rem;
        }

        .pagination {
            justify-content: center;
        }

        .no-logs {
            text-align: center;
            padding: 2rem 0;
            color: #6c757d;
            font-size: 1.1rem;
        }
    </style>

    <div class="logs-section">
        <div class="container-fluid px-0">
            <div class="logs-card">

                <!-- Filter Form -->
                <form method="GET" class="filter-form mb-4">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-3 col-sm-6">
                            <input type="text" name="filename" placeholder="Filename" value="{{ request('filename') }}"
                                class="form-control" />
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <select name="status" class="form-select">
                                <option value="">-- Status --</option>
                                <option value="processed" {{ request('status') === 'processed' ? 'selected' : '' }}>
                                    Processed</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending
                                </option>
                            </select>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <input type="text" name="uploaded_by" placeholder="Uploaded by"
                                value="{{ request('uploaded_by') }}" class="form-control" />
                        </div>

                        <div class="col-md-3 col-sm-6 d-flex justify-content-sm-end justify-content-start">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-funnel me-1"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Logs Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>File Name</th>
                                <th>Uploaded By</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                                <tr>
                                    <td>{{ $log->filename }}</td>
                                    <td>{{ $log->user->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($log->is_processed)
                                            <span class="badge bg-success px-3 py-2">Processed</span>
                                        @else
                                            <span class="badge bg-warning text-dark px-3 py-2">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ $log->created_at->format('d M, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="no-logs">
                                        <i class="bi bi-exclamation-circle me-2"></i> No logs found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $logs->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</x-app-layout>