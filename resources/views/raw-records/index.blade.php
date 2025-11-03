<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#1565c0] leading-tight">Raw Records</h2>
    </x-slot>

    <style>
        /* 🌿 Pharma Dashboard Table Styles */
        .records-section {
            background-color: #f5f9ff;
            min-height: calc(100vh - 120px);
            padding: 2rem 1rem;
        }

        .records-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
            transition: all 0.3s ease;
        }

        .records-card:hover {
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
            vertical-align: middle;
            text-align: center;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f0f7ff;
        }

        .pagination {
            justify-content: center;
        }

        .page-link {
            color: #1565c0;
        }

        .page-link:hover {
            background-color: #e3f2fd;
            color: #0d47a1;
        }

        .no-records {
            text-align: center;
            padding: 2rem 0;
            color: #6c757d;
            font-size: 1.1rem;
        }

        /* 🌿 Modal UI Enhancements */
        .modal-content {
            background: #ffffff;
        }

        .form-label {
            font-size: 0.95rem;
        }

        .form-control,
        textarea.form-control {
            transition: all 0.2s ease;
        }

        .form-control:focus,
        textarea.form-control:focus {
            border-color: #1565c0 !important;
            box-shadow: 0 0 0 0.2rem rgba(21, 101, 192, 0.15);
        }

        .btn-primary {
            background-color: #1565c0;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0d47a1;
            transform: translateY(-1px);
        }

        .btn-outline-secondary:hover {
            background-color: #f0f4ff;
            border-color: #1565c0;
            color: #1565c0;
        }

        .modal.fade .modal-dialog {
            transform: scale(0.95);
            transition: transform 0.2s ease-out;
        }

        .modal.show .modal-dialog {
            transform: scale(1);
        }
    </style>

df    <div class="records-section">
        <div class="container-fluid px-0">
            <div class="records-card">

                <!-- Table Title / Optional Filters -->
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                    <h5 class="text-primary fw-semibold mb-0">
                        <i class="bi bi-database-check me-2"></i>Raw Record Details
                    </h5>

                    <div class="d-flex mt-2 mt-sm-0">
                        <form class="d-flex" method="GET" action="">
                            <input type="text" name="search" class="form-control form-control-sm me-2"
                                placeholder="Search client or provider...">
                            <button class="btn btn-sm btn-outline-primary me-2" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>

                        <!-- Add Record Button -->
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addRecordModal">
                            <i class="bi bi-plus-circle me-1"></i> Add Record
                        </button>
                    </div>
                </div>

                <!-- Records Table -->
                 
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Provider</th>
                                <th>Date</th>
                                <th>Target</th>
                                <th>Accuracy</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($records as $record)
                                <tr>
                                    <td>{{ $record->client_name }}</td>
                                    <td>{{ $record->provider_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($record->date_of_service)->format('d M, Y') }}</td>
                                    <td>{{ Str::limit($record->target_text, 50) }}</td>
                                    <td>
                                        <span class="badge bg-success px-3 py-2">
                                            {{ $record->accuracy }}%
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="no-records">
                                        <i class="bi bi-exclamation-circle me-2"></i> No records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $records->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>
    </div>

    <!-- 🌟 Add Record Modal -->
    <div class="modal fade" id="addRecordModal" tabindex="-1" aria-labelledby="addRecordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                <!-- Header -->
                <div class="modal-header border-0 bg-gradient text-white p-4"
                    style="background: linear-gradient(135deg, #1565c0, #1e88e5);">
                    <h5 class="modal-title fw-semibold d-flex align-items-center" id="addRecordModalLabel">
                        <i class="bi bi-plus-circle me-2 fs-4"></i> Add New Record
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Form -->
                <form method="POST" action="#">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary">Client Name</label>
                                <input type="text" name="client_name"
                                    class="form-control form-control-lg border-0 shadow-sm"
                                    placeholder="Enter client name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary">Provider Name</label>
                                <input type="text" name="provider_name"
                                    class="form-control form-control-lg border-0 shadow-sm"
                                    placeholder="Enter provider name" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary">Date of Service</label>
                                <input type="date" name="date_of_service"
                                    class="form-control form-control-lg border-0 shadow-sm" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary">Accuracy (%)</label>
                                <input type="number" name="accuracy"
                                    class="form-control form-control-lg border-0 shadow-sm" min="0" max="100"
                                    placeholder="e.g. 95" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-medium text-secondary">Target Text</label>
                                <textarea name="target_text" class="form-control border-0 shadow-sm" rows="3"
                                    placeholder="Enter target details..." required></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer border-0 p-4 d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-3"
                            data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Cancel
                        </button>
                        <button type="submit"
                            class="btn btn-primary px-4 py-2 rounded-3 shadow-sm d-flex align-items-center">
                            <i class="bi bi-save me-2"></i> Save Record
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap Icons & Modal Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const addRecordModal = document.getElementById('addRecordModal');

            // When the modal is fully hidden, reset all form fields
            addRecordModal.addEventListener('hidden.bs.modal', function () {
                const form = addRecordModal.querySelector('form');
                form.reset();
            });
        });
    </script>
</x-app-layout>