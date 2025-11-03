<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#1565c0] leading-tight">
            {{ __('Upload File') }}
        </h2>
    </x-slot>

    <style>
        /* 🌿 Pharma Upload Page Styles */
        .upload-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 3rem 1rem;
            background: #f5f9ff;
        }

        .upload-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            padding: 2.5rem;
            max-width: 600px;
            width: 100%;
            transition: 0.3s ease;
        }

        .upload-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .upload-icon {
            text-align: center;
            font-size: 50px;
            color: #1565c0;
            margin-bottom: 1rem;
        }

        .form-label {
            font-weight: 500;
            color: #1565c0;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #cfd8dc;
            transition: 0.3s;
        }

        .form-control:focus {
            border-color: #42a5f5;
            box-shadow: 0 0 0 0.25rem rgba(66, 165, 245, 0.25);
        }

        .btn-primary {
            background-color: #1565c0;
            border: none;
            border-radius: 8px;
            width: 100%;
            padding: 0.75rem;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background-color: #0d47a1;
            box-shadow: 0 4px 12px rgba(13, 71, 161, 0.3);
        }

        .alert {
            border-radius: 10px;
        }

        .drag-area {
            border: 2px dashed #90caf9;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            background-color: #f0f7ff;
            transition: all 0.3s ease;
        }

        .drag-area.dragover {
            background-color: #e3f2fd;
            border-color: #1565c0;
        }

        .drag-area input {
            display: none;
        }

        .drag-area p {
            color: #1565c0;
            font-weight: 500;
        }
    </style>

    <div class="upload-wrapper">
        <div class="upload-card">
            <!-- Success / Error Messages -->
            @if(session('success'))
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Upload Icon -->
            <div class="upload-icon">
                <i class="bi bi-cloud-arrow-up"></i>
            </div>

            <!-- Upload Form -->
            <form action="{{ route('upload.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf
                <div class="mb-4">
                    <label for="file" class="form-label">Select File</label>

                    <!-- Drag & Drop Area -->
                    <div class="drag-area" id="dragArea">
                        <p>Drag & drop your file here or click to select</p>
                        <input type="file" name="file" id="file" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-upload me-2"></i> Upload File
                </button>
            </form>
        </div>
    </div>

    <!-- JS for drag-drop functionality -->
    <script>
        const dragArea = document.getElementById('dragArea');
        const fileInput = document.getElementById('file');

        dragArea.addEventListener('click', () => fileInput.click());

        dragArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            dragArea.classList.add('dragover');
        });

        dragArea.addEventListener('dragleave', () => {
            dragArea.classList.remove('dragover');
        });

        dragArea.addEventListener('drop', (e) => {
            e.preventDefault();
            dragArea.classList.remove('dragover');
            fileInput.files = e.dataTransfer.files;
        });
    </script>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</x-app-layout>