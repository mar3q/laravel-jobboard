<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ __('Moderacja ofert') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md bg-emerald-50 p-3 text-sm text-emerald-700">{{ session('status') }}</div>
            @endif

            @forelse ($jobs as $job)
                <article class="mb-4 rounded-lg border border-gray-200 bg-white p-5">
                    <header class="flex items-start justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $job->title }}</h3>
                            <p class="text-sm text-gray-600">{{ $job->company->name }} • {{ $job->seniority->name }} • {{ $job->contract_type->name }}</p>
                        </div>
                        <span class="text-xs text-gray-400">{{ $job->created_at->diffForHumans() }}</span>
                    </header>
                    <p class="mt-3 text-sm text-gray-700">{{ Str::limit($job->description, 280) }}</p>

                    <footer class="mt-4 flex items-center gap-3">
                        <form method="POST" action="{{ route('admin.moderation.approve', $job) }}">
                            @csrf
                            <button class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-500">Zatwierdź</button>
                        </form>
                        <form method="POST" action="{{ route('admin.moderation.reject', $job) }}" class="flex flex-1 items-center gap-2">
                            @csrf
                            <input type="text" name="reason" placeholder="Powód odrzucenia"
                                   class="flex-1 rounded-md border-gray-300 text-sm" required>
                            <button class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-500">Odrzuć</button>
                        </form>
                    </footer>
                </article>
            @empty
                <p class="rounded-lg border border-dashed border-gray-300 p-8 text-center text-gray-500">
                    Nic do moderacji.
                </p>
            @endforelse

            <div class="mt-6">{{ $jobs->links() }}</div>
        </div>
    </div>
</x-app-layout>
