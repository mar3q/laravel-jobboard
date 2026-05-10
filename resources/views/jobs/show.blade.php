<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ $job->title }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <a href="{{ route('jobs.index') }}" class="text-sm text-indigo-600 hover:underline">← Wszystkie oferty</a>

            <article class="mt-4 rounded-lg border border-gray-200 bg-white p-8">
                <header class="border-b border-gray-100 pb-6">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $job->title }}</h1>
                    <p class="mt-1 text-lg text-gray-700">{{ $job->company->name }}</p>

                    <div class="mt-4 flex flex-wrap gap-2 text-sm">
                        <span class="rounded bg-gray-100 px-3 py-1">{{ $job->seniority->name }}</span>
                        <span class="rounded bg-gray-100 px-3 py-1">{{ $job->contract_type->name }}</span>
                        @if ($job->remote)
                            <span class="rounded bg-emerald-100 px-3 py-1 text-emerald-700">Remote</span>
                        @endif
                        @if ($job->location_city)
                            <span class="rounded bg-gray-100 px-3 py-1">📍 {{ $job->location_city }}</span>
                        @endif
                        @if ($job->salary)
                            <span class="rounded bg-indigo-50 px-3 py-1 font-medium text-indigo-700">{{ $job->salary->format() }}</span>
                        @endif
                    </div>
                </header>

                <section class="py-6">
                    <h2 class="text-lg font-semibold text-gray-900">Opis</h2>
                    <div class="mt-2 whitespace-pre-line text-gray-700">{{ $job->description }}</div>

                    @if ($job->requirements)
                        <h2 class="mt-6 text-lg font-semibold text-gray-900">Wymagania</h2>
                        <div class="mt-2 whitespace-pre-line text-gray-700">{{ $job->requirements }}</div>
                    @endif

                    @if ($job->benefits)
                        <h2 class="mt-6 text-lg font-semibold text-gray-900">Benefity</h2>
                        <div class="mt-2 whitespace-pre-line text-gray-700">{{ $job->benefits }}</div>
                    @endif
                </section>

                @if ($job->tags->isNotEmpty())
                    <div class="border-t border-gray-100 py-4">
                        @foreach ($job->tags as $tag)
                            <a href="{{ route('jobs.index', ['tag' => $tag->slug]) }}"
                               class="mr-2 inline-block rounded bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 hover:bg-indigo-100">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

                <footer class="border-t border-gray-100 pt-6">
                    @auth
                        @can('apply', $job)
                            <a href="{{ route('applications.create', $job) }}" class="inline-flex items-center rounded-md bg-indigo-600 px-6 py-3 text-sm font-medium text-white hover:bg-indigo-500">
                                Aplikuj na to stanowisko
                            </a>
                        @else
                            <p class="text-sm text-gray-500">Aby aplikować, zaloguj się jako kandydat.</p>
                        @endcan
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-6 py-3 text-sm font-medium text-white hover:bg-indigo-500">
                            Zaloguj się aby aplikować
                        </a>
                    @endauth
                </footer>
            </article>
        </div>
    </div>

    @push('head')
        <meta property="og:title" content="{{ $job->title }} — {{ $job->company->name }}">
        <meta property="og:description" content="{{ Str::limit(strip_tags($job->description), 180) }}">
        <script type="application/ld+json">
        @json([
            '@context' => 'https://schema.org/',
            '@type' => 'JobPosting',
            'title' => $job->title,
            'description' => $job->description,
            'datePosted' => optional($job->published_at)->toIso8601String(),
            'validThrough' => optional($job->expires_at)->toIso8601String(),
            'employmentType' => $job->contract_type->name,
            'hiringOrganization' => ['@type' => 'Organization', 'name' => $job->company->name],
            'jobLocation' => [
                '@type' => 'Place',
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => $job->location_city,
                    'addressCountry' => $job->location_country,
                ],
            ],
        ])
        </script>
    @endpush
</x-app-layout>
