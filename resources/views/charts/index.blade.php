<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">All Charts Overview</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="mb-6">

            <label for="clientSelect" class="block font-medium mb-1">Select Client:</label>
            <select id="clientSelect" class="form-select px-4 py-2 border rounded w-1/3">
                @foreach($clients as $client)
                    <option value="{{ $client }}">{{ $client }}</option>
                @endforeach
            </select>
        </div>

       <div class="mb-6 flex justify-end space-x-3">
    <a id="downloadPdfBtn"
       href="{{ route('charts.pdf', ['client_name' => $clients[0] ?? 'Default']) }}"
       target="_blank"
       class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded hover:bg-red-700 transition">
        <i class="fas fa-file-pdf mr-2"></i> Download PDF Report
    </a>

    <a id="downloadZipBtn"
       href="#"
       target="_blank"
       class="inline-flex items-center px-4 py-2 border border-blue-600 text-blue-600 text-sm font-medium rounded hover:bg-blue-600 hover:text-white transition">
        <i class="fas fa-file-archive mr-2"></i> Download All Charts as ZIP
    </a>
</div>


        @include('charts.partials.goals')
        @include('charts.partials.behavior')
        @include('charts.partials.programs')
        @include('charts.partials.progress')
    </div>
    <script>
        const clientUploadId = {!! json_encode($latestUploadId) !!};
    </script>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
    <script src="{{ asset('js/charts.js') }}"></script>

</x-app-layout>
