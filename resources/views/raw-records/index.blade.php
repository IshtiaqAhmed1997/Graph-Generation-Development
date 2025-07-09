<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Raw Records</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Client</th>
                        <th class="px-4 py-2">Provider</th>
                        <th class="px-4 py-2">Date of Service</th>
                        <th class="px-4 py-2">Target</th>
                        <th class="px-4 py-2">Accuracy</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                    <tr>
                        <td class="border px-4 py-2">{{ $record->client_name }}</td>
                        <td class="border px-4 py-2">{{ $record->provider_name }}</td>
                        <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($record->date_of_service)->format('d M, Y') }}
</td>
                        <td class="border px-4 py-2">{{ $record->target_text }}</td>
                        <td class="border px-4 py-2">{{ $record->accuracy }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">No records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $records->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
