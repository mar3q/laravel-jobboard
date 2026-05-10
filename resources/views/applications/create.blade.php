<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Aplikuj') }} — {{ $job->title }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-lg border border-gray-200 bg-white p-8">
                <p class="mb-6 text-sm text-gray-600">
                    Aplikujesz na ofertę <strong>{{ $job->title }}</strong> w <strong>{{ $job->company->name }}</strong>.
                </p>

                <form method="POST" action="{{ route('applications.store', $job) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="contact_email" :value="__('Email kontaktowy')" />
                        <x-text-input id="contact_email" name="contact_email" type="email" class="mt-1 block w-full"
                                      :value="old('contact_email', auth()->user()->email)" required />
                        <x-input-error :messages="$errors->get('contact_email')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="contact_phone" :value="__('Telefon (opcjonalnie)')" />
                        <x-text-input id="contact_phone" name="contact_phone" type="text" class="mt-1 block w-full"
                                      :value="old('contact_phone')" />
                        <x-input-error :messages="$errors->get('contact_phone')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="cover_letter" :value="__('List motywacyjny (opcjonalnie)')" />
                        <textarea id="cover_letter" name="cover_letter" rows="6"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('cover_letter') }}</textarea>
                        <x-input-error :messages="$errors->get('cover_letter')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="cv" :value="__('CV (PDF, max 5MB)')" />
                        <input id="cv" name="cv" type="file" accept="application/pdf"
                               class="mt-1 block w-full text-sm text-gray-700">
                        <x-input-error :messages="$errors->get('cv')" class="mt-2" />
                    </div>

                    @if ($errors->has('job'))
                        <div class="rounded-md bg-red-50 p-4 text-sm text-red-700">{{ $errors->first('job') }}</div>
                    @endif

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('jobs.show', $job) }}" class="text-sm text-gray-600 hover:underline">Anuluj</a>
                        <x-primary-button>{{ __('Wyślij aplikację') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
