@csrf
<div class="space-y-5">
    <div>
        <x-input-label for="company_id" :value="__('Firma')" />
        <select id="company_id" name="company_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            @foreach ($companies as $company)
                <option value="{{ $company->id }}" @selected(old('company_id', $job->company_id ?? null) == $company->id)>{{ $company->name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('company_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="title" :value="__('Tytuł oferty')" />
        <x-text-input id="title" name="title" class="mt-1 block w-full" :value="old('title', $job->title ?? '')" required />
        <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="description" :value="__('Opis')" />
        <textarea id="description" name="description" rows="6"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $job->description ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <x-input-label for="seniority" :value="__('Seniority')" />
            <select id="seniority" name="seniority" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @foreach ($seniorities as $s)
                    <option value="{{ $s->value }}" @selected(old('seniority', $job->seniority?->value ?? null) === $s->value)>{{ $s->name }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('seniority')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="contract_type" :value="__('Forma współpracy')" />
            <select id="contract_type" name="contract_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @foreach ($contractTypes as $c)
                    <option value="{{ $c->value }}" @selected(old('contract_type', $job->contract_type?->value ?? null) === $c->value)>{{ $c->name }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('contract_type')" class="mt-2" />
        </div>
    </div>

    <div class="grid grid-cols-3 gap-4">
        <div>
            <x-input-label for="salary_min" :value="__('Pensja od')" />
            <x-text-input id="salary_min" name="salary_min" type="number" class="mt-1 block w-full" :value="old('salary_min', $job->salary_min ?? '')" />
            <x-input-error :messages="$errors->get('salary_min')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="salary_max" :value="__('Pensja do')" />
            <x-text-input id="salary_max" name="salary_max" type="number" class="mt-1 block w-full" :value="old('salary_max', $job->salary_max ?? '')" />
            <x-input-error :messages="$errors->get('salary_max')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="salary_currency" :value="__('Waluta')" />
            <select id="salary_currency" name="salary_currency" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @foreach (['PLN','EUR','USD','GBP'] as $cur)
                    <option value="{{ $cur }}" @selected(old('salary_currency', $job->salary_currency ?? 'PLN') === $cur)>{{ $cur }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <x-input-label for="location_city" :value="__('Miasto')" />
            <x-text-input id="location_city" name="location_city" class="mt-1 block w-full" :value="old('location_city', $job->location_city ?? '')" />
        </div>
        <div>
            <x-input-label for="location_country" :value="__('Kraj (kod 2-literowy)')" />
            <x-text-input id="location_country" name="location_country" class="mt-1 block w-full" :value="old('location_country', $job->location_country ?? 'PL')" />
        </div>
    </div>

    <div class="flex gap-6">
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="remote" value="1" @checked(old('remote', $job->remote ?? false)) class="rounded">
            Remote
        </label>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="hybrid" value="1" @checked(old('hybrid', $job->hybrid ?? false)) class="rounded">
            Hybrid
        </label>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <x-input-label for="status" :value="__('Status')" />
            <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @foreach ($statuses as $s)
                    <option value="{{ $s->value }}" @selected(old('status', $job->status?->value ?? null) === $s->value)>{{ $s->name }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="expires_at" :value="__('Wygasa')" />
            <x-text-input id="expires_at" name="expires_at" type="date" class="mt-1 block w-full"
                          :value="old('expires_at', optional($job->expires_at ?? null)->format('Y-m-d'))" />
        </div>
    </div>

    <div class="flex justify-end gap-3 border-t border-gray-100 pt-6">
        <a href="{{ route('employer.jobs.index') }}" class="text-sm text-gray-600 hover:underline">Anuluj</a>
        <x-primary-button>{{ $submitLabel ?? __('Zapisz') }}</x-primary-button>
    </div>
</div>
