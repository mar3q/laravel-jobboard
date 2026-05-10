<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Oferty pracy') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <form method="GET" action="{{ route('jobs.index') }}" class="mb-8 grid grid-cols-1 gap-4 rounded-lg border border-gray-200 bg-white p-4 md:grid-cols-6">
                <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Słowa kluczowe..."
                       class="md:col-span-2 rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <input type="text" name="city" value="{{ $filters['city'] ?? '' }}" placeholder="Miasto"
                       class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

                <select name="seniority" class="rounded-md border-gray-300 text-sm shadow-sm">
                    <option value="">— Seniority —</option>
                    @foreach ($seniorities as $s)
                        <option value="{{ $s->value }}" @selected(($filters['seniority'] ?? null) === $s->value)>{{ $s->name }}</option>
                    @endforeach
                </select>

                <select name="contract" class="rounded-md border-gray-300 text-sm shadow-sm">
                    <option value="">— Forma —</option>
                    @foreach ($contractTypes as $c)
                        <option value="{{ $c->value }}" @selected(($filters['contract'] ?? null) === $c->value)>{{ $c->name }}</option>
                    @endforeach
                </select>

                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input type="checkbox" name="remote" value="1" @checked(! empty($filters['remote'])) class="rounded text-indigo-600">
                    Tylko remote
                </label>

                <div class="md:col-span-6 flex justify-end gap-2">
                    <a href="{{ route('jobs.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Wyczyść</a>
                    <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">Filtruj</button>
                </div>
            </form>

            <p class="mb-4 text-sm text-gray-600">Znaleziono <strong>{{ $jobs->total() }}</strong> ofert.</p>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($jobs as $job)
                    <x-job-card :job="$job" />
                @empty
                    <p class="col-span-full rounded-lg border border-dashed border-gray-300 p-8 text-center text-gray-500">
                        Brak ofert pasujących do kryteriów.
                    </p>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $jobs->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
