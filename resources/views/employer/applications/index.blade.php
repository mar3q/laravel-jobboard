<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Aplikacje') }} — {{ $job->title }}
        </h2>
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
                            <th class="px-4 py-3">Kandydat</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Data</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($applications as $a)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900">{{ $a->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $a->contact_email }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full bg-{{ $a->status->color() }}-100 px-2 py-1 text-xs text-{{ $a->status->color() }}-800">
                                        {{ $a->status->name }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-500">{{ $a->created_at->diffForHumans() }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('employer.applications.show', $a) }}" class="text-xs text-indigo-600 hover:underline">Szczegóły</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Brak aplikacji.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">{{ $applications->links() }}</div>
        </div>
    </div>
</x-app-layout>
