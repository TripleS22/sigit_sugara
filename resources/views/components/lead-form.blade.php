{{-- Consultation / quote request form (FR-04) with honeypot and time-trap spam checks. --}}
@props(['package' => null, 'serviceType' => null])
<form method="POST" action="{{ route('leads.store') }}" data-lead-form novalidate {{ $attributes->merge(['class' => 'space-y-5']) }}>
    @csrf
    <input type="hidden" name="form_started" value="{{ \Illuminate\Support\Facades\Crypt::encryptString((string) now()->timestamp) }}">
    <input type="hidden" name="source_page" value="{{ old('source_page', request()->headers->get('referer') ? parse_url(request()->headers->get('referer'), PHP_URL_PATH) : request()->getPathInfo()) }}">
    <input type="hidden" name="utm_source" value="{{ old('utm_source') }}">
    <input type="hidden" name="utm_medium" value="{{ old('utm_medium') }}">
    <input type="hidden" name="utm_campaign" value="{{ old('utm_campaign') }}">
    @if ($package)
        <input type="hidden" name="package_slug" value="{{ $package->slug }}">
    @endif

    {{-- Honeypot: hidden from people, filled by bots. --}}
    <div class="absolute -left-[9999px] h-px w-px overflow-hidden" aria-hidden="true">
        <label for="website">Website</label>
        <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
    </div>

    @if ($package)
        <p class="rounded-2xl bg-brand-50 px-4 py-3 text-sm text-brand-800">
            Paket dipilih: <strong>{{ $package->name }}</strong> ({{ $package->priceLabel() }})
        </p>
    @endif

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="name" class="form-label">Nama <span class="text-red-600" aria-hidden="true">*</span></label>
            <input id="name" name="name" type="text" class="form-input" value="{{ old('name') }}" required maxlength="100" autocomplete="name"
                @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
            @error('name')<p id="name-error" class="form-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="organization" class="form-label">Nama bisnis / institusi <span class="text-slate-400">(opsional)</span></label>
            <input id="organization" name="organization" type="text" class="form-input" value="{{ old('organization') }}" maxlength="150" autocomplete="organization">
            @error('organization')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="whatsapp" class="form-label">Nomor WhatsApp <span class="text-red-600" aria-hidden="true">*</span></label>
            <input id="whatsapp" name="whatsapp" type="tel" inputmode="tel" class="form-input" value="{{ old('whatsapp') }}" required placeholder="08xxxxxxxxxx" autocomplete="tel"
                @error('whatsapp') aria-invalid="true" aria-describedby="whatsapp-error" @enderror>
            @error('whatsapp')<p id="whatsapp-error" class="form-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="email" class="form-label">Email <span class="text-slate-400">(opsional)</span></label>
            <input id="email" name="email" type="email" class="form-input" value="{{ old('email') }}" maxlength="150" autocomplete="email">
            @error('email')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="service_type" class="form-label">Jenis layanan <span class="text-red-600" aria-hidden="true">*</span></label>
            <select id="service_type" name="service_type" class="form-input" required>
                <option value="">Pilih layanan…</option>
                @foreach (\App\Models\Lead::SERVICE_TYPES as $value => $label)
                    <option value="{{ $value }}" @selected(old('service_type', $serviceType) === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('service_type')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="budget" class="form-label">Estimasi anggaran</label>
            <select id="budget" name="budget" class="form-input">
                <option value="">Pilih kisaran…</option>
                @foreach (\App\Models\Lead::BUDGETS as $value => $label)
                    <option value="{{ $value }}" @selected(old('budget') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('budget')<p class="form-error">{{ $message }}</p>@enderror
        </div>
    </div>

    <div>
        <label for="message" class="form-label">Deskripsi kebutuhan <span class="text-red-600" aria-hidden="true">*</span></label>
        <textarea id="message" name="message" rows="5" class="form-input" required maxlength="3000"
            placeholder="Contoh: website company profile 6 halaman untuk usaha katering, perlu galeri menu dan tombol WhatsApp."
            @error('message') aria-invalid="true" aria-describedby="message-error" @enderror>{{ old('message') }}</textarea>
        @error('message')<p id="message-error" class="form-error">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="flex items-start gap-3 text-sm text-slate-600">
            <input type="checkbox" name="consent" value="1" class="mt-0.5 size-4 rounded border-slate-300 text-brand-600 focus:ring-brand-600" @checked(old('consent')) required>
            <span>Saya menyetujui <a href="{{ url('/kebijakan-privasi') }}" class="font-medium text-brand-700 underline" target="_blank">kebijakan privasi</a> dan bersedia dihubungi melalui WhatsApp atau email untuk membahas kebutuhan ini.</span>
        </label>
        @error('consent')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <button type="submit" class="btn-primary w-full sm:w-auto">
        Kirim permintaan konsultasi <x-icon name="arrow-right" class="size-4" />
    </button>
    <p class="text-xs text-slate-500">Kami membalas pada jam kerja: {{ \App\Models\Setting::get('business_hours') }}.</p>
</form>
