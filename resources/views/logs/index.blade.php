<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Upload Logs</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <form method="GET" class="mb-4">
                <div class="flex flex-wrap gap-4">
                    <input type="text" name="filename" placeholder="Filename" value="{{ request('filename') }}"
                        class="border rounded px-3 py-1" />

                    <select name="status" class="border rounded px-3 py-1">
                        <option value="">-- Status --</option>
                        <option value="processed" {{ request('status') === 'processed' ? 'selected' : '' }}>Processed
                        </option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>

                    <input type="text" name="uploaded_by" placeholder="Uploaded by" value="{{ request('uploaded_by') }}"
                        class="border rounded px-3 py-1" />

                    <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded">Filter</button>
                </div>
            </form>

            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="px-4 py-2">File Name</th>
                        <th class="px-4 py-2">Uploaded By</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td class="border px-4 py-2">{{ $log->filename }}</td>
                            <td class="border px-4 py-2">{{ $log->user->name ?? 'N/A' }}</td>
                            <td class="border px-4 py-2">{{ $log->is_processed ? 'Processed' : 'Pending' }}</td>
                            <td class="border px-4 py-2">{{ $log->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">No logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</x-app-layout>