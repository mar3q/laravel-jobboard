<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ __('Admin Dashboard') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                @php($cards = [
                    'Użytkownicy' => $stats['users'],
                    'Firmy' => $stats['companies'],
                    'Ofert (total)' => $stats['jobs_total'],
                    'Opublikowanych' => $stats['jobs_published'],
                    'Do moderacji' => $stats['jobs_pending'],
                    'Aplikacji (total)' => $stats['applications'],
                    'Aplikacji dziś' => $stats['applications_today'],
                ])
                @foreach ($cards as $label => $value)
                    <div class="rounded-lg border border-gray-200 bg-white p-5">
                        <div class="text-xs uppercase tracking-wide text-gray-500">{{ $label }}</div>
                        <div class="mt-2 text-2xl font-bold text-gray-900">{{ $value }}</div>
                    </div>
                @endforeach
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900">Ostatnie aktywności</h3>
                    <a href="{{ route('admin.moderation.index') }}" class="text-xs text-indigo-600 hover:underline">Moderacja →</a>
                </div>
                <ul class="mt-4 divide-y divide-gray-100 text-sm">
                    @forelse ($recentActivity as $activity)
                        <li class="flex items-center justify-between py-2">
                            <div>
                                <span class="font-medium text-gray-700">{{ $activity->description }}</span>
                                @if ($activity->subject_type)
                                    <span class="text-xs text-gray-400">— {{ class_basename($activity->subject_type) }}#{{ $activity->subject_id }}</span>
                                @endif
                            </div>
                            <span class="text-xs text-gray-400">{{ $activity->created_at->diffForHumans() }}</span>
                        </li>
                    @empty
                        <li class="py-2 text-gray-500">Brak zarejestrowanej aktywności.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
