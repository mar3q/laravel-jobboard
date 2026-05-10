<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ __('Aplikacja') }} #{{ $application->id }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-md bg-emerald-50 p-3 text-sm text-emerald-700">{{ session('status') }}</div>
            @endif

            <article class="rounded-lg border border-gray-200 bg-white p-6">
                <header class="border-b border-gray-100 pb-4">
                    <h3 class="text-lg font-semibold">{{ $application->user->name }}</h3>
                    <p class="text-sm text-gray-600">{{ $application->contact_email }}{{ $application->contact_phone ? ' • '.$application->contact_phone : '' }}</p>
                    <p class="mt-2 text-xs text-gray-500">
                        Aplikacja na: <a href="{{ route('jobs.show', $application->job) }}" class="text-indigo-600 hover:underline">{{ $application->job->title }}</a>
                    </p>
                </header>

                @if ($application->cover_letter)
                    <section class="py-4">
                        <h4 class="text-sm font-semibold text-gray-900">List motywacyjny</h4>
                        <p class="mt-2 whitespace-pre-line text-sm text-gray-700">{{ $application->cover_letter }}</p>
                    </section>
                @endif

                @if ($cvUrl)
                    <section class="border-t border-gray-100 py-4">
                        <a href="{{ $cvUrl }}" class="text-sm text-indigo-600 hover:underline">⬇ Pobierz CV (PDF)</a>
                        <p class="text-xs text-gray-400">Link wygasa za 15 minut.</p>
                    </section>
                @endif

                <section class="border-t border-gray-100 pt-4">
                    <form method="POST" action="{{ route('employer.applications.status', $application) }}" class="flex items-end gap-3">
                        @csrf @method('PATCH')
                        <div>
                            <x-input-label for="status" :value="__('Zmień status')" />
                            <select id="status" name="status" class="mt-1 rounded-md border-gray-300 text-sm">
                                @foreach ($statuses as $s)
                                    <option value="{{ $s->value }}" @selected($application->status === $s)>{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-1">
                            <x-input-label for="note" :value="__('Notatka (opcjonalnie)')" />
                            <x-text-input id="note" name="note" class="mt-1 block w-full" />
                        </div>
                        <x-primary-button>Zapisz</x-primary-button>
                    </form>
                </section>
            </article>

            @if ($application->statusChanges->isNotEmpty())
                <article class="rounded-lg border border-gray-200 bg-white p-6">
                    <h4 class="text-sm font-semibold text-gray-900">Historia statusów</h4>
                    <ul class="mt-2 space-y-2 text-sm">
                        @foreach ($application->statusChanges->sortByDesc('created_at') as $change)
                            <li class="flex items-center gap-2 text-gray-600">
                                <span class="text-xs text-gray-400">{{ $change->created_at->format('Y-m-d H:i') }}</span>
                                <span>{{ $change->from_status?->name ?? '—' }} → <strong>{{ $change->to_status->name }}</strong></span>
                                @if ($change->changedBy)
                                    <span class="text-xs">przez {{ $change->changedBy->name }}</span>
                                @endif
                                @if ($change->note)
                                    <span class="text-xs italic">„{{ $change->note }}”</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </article>
            @endif
        </div>
    </div>
</x-app-layout>
