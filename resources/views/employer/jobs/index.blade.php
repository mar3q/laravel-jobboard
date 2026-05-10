<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ __('Moje oferty') }}</h2>
            <a href="{{ route('employer.jobs.create') }}" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">
                + Nowa oferta
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md bg-emerald-50 p-3 text-sm text-emerald-700">{{ session('status') }}</div>
            @endif

            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-4 py-3">Tytuł</th>
                            <th class="px-4 py-3">Firma</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Aplikacje</th>
                            <th class="px-4 py-3">Wygasa</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($jobs as $job)
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $job->title }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $job->company->name }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded bg-gray-100 px-2 py-1 text-xs">{{ $job->status->name }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('employer.jobs.applications', $job) }}" class="text-indigo-600 hover:underline">
                                        {{ $job->applications_count }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-gray-500">{{ optional($job->expires_at)->format('Y-m-d') ?? '—' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('employer.jobs.edit', $job) }}" class="text-xs text-indigo-600 hover:underline">Edytuj</a>
                                    <form method="POST" action="{{ route('employer.jobs.destroy', $job) }}" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('Usunąć?')" class="ml-2 text-xs text-red-600 hover:underline">Usuń</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">Brak ofert.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">{{ $jobs->links() }}</div>
        </div>
    </div>
</x-app-layout>
