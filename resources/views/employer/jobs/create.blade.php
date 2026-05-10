<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ __('Nowa oferta') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('employer.jobs.store') }}" class="rounded-lg border border-gray-200 bg-white p-8">
                @include('employer.jobs._form', ['submitLabel' => __('Utwórz ofertę')])
            </form>
        </div>
    </div>
</x-app-layout>
