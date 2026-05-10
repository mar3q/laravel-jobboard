@props(['job'])

<a href="{{ route('jobs.show', $job) }}"
   class="block rounded-lg border border-gray-200 bg-white p-5 transition hover:border-indigo-400 hover:shadow-sm">
    <div class="flex items-start justify-between gap-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">{{ $job->title }}</h3>
            <p class="text-sm text-gray-600">{{ $job->company->name }}</p>
        </div>
        @if ($job->remote)
            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-700">Remote</span>
        @endif
    </div>

    <div class="mt-3 flex flex-wrap gap-2 text-xs text-gray-600">
        <span class="rounded bg-gray-100 px-2 py-1">{{ $job->seniority->name }}</span>
        <span class="rounded bg-gray-100 px-2 py-1">{{ $job->contract_type->name }}</span>
        @if ($job->location_city)
            <span class="rounded bg-gray-100 px-2 py-1">📍 {{ $job->location_city }}</span>
        @endif
        @if ($job->salary)
            <span class="rounded bg-indigo-50 px-2 py-1 font-medium text-indigo-700">{{ $job->salary->format() }}</span>
        @endif
    </div>

    @if ($job->published_at)
        <p class="mt-3 text-xs text-gray-400">Opublikowano {{ $job->published_at->diffForHumans() }}</p>
    @endif
</a>
