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
