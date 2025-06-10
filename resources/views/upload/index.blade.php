<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upload File') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
            @endif

            @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('upload.store') }}" method="POST" enctype="multipart/form-data"
                class="bg-white p-6 rounded shadow">
                @csrf
                <div class="mb-4">
                    <label for="file" class="block font-medium text-sm text-gray-700">Select File</label>
                    <input type="file" name="file" id="file" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                </div>

                <div class="mt-6">
                    <x-primary-button>
                        Upload
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
