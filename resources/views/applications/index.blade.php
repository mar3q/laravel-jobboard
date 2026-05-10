<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ __('Moje aplikacje') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md bg-emerald-50 p-3 text-sm text-emerald-700">{{ session('status') }}</div>
            @endif

            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-4 py-3">Oferta</th>
                            <th class="px-4 py-3">Firma</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Data</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($applications as $app)
                            <tr>
                                <td class="px-4 py-3">
                                    <a href="{{ route('jobs.show', $app->job) }}" class="font-medium text-indigo-600 hover:underline">
                                        {{ $app->job->title }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-gray-700">{{ $app->job->company->name }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full bg-{{ $app->status->color() }}-100 px-2 py-1 text-xs font-medium text-{{ $app->status->color() }}-800">
                                        {{ $app->status->name }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-500">{{ $app->created_at->diffForHumans() }}</td>
                                <td class="px-4 py-3 text-right">
                                    @can('withdraw', $app)
                                        <form method="POST" action="{{ route('applications.withdraw', $app) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="text-xs text-red-600 hover:underline">Wycofaj</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">Brak aplikacji.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">{{ $applications->links() }}</div>
        </div>
    </div>
</x-app-layout>
