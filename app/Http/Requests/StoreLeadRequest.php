<?php

namespace App\Http\Requests;

use App\Models\Lead;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'whatsapp' => preg_replace('/[\s\-().]/', '', (string) $this->input('whatsapp')),
            'email' => $this->filled('email') ? strtolower(trim((string) $this->input('email'))) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'organization' => ['nullable', 'string', 'max:150'],
            'email' => ['nullable', 'email:rfc', 'max:150'],
            'whatsapp' => ['required', 'regex:/^(\+62|62|0)8[0-9]{7,12}$/'],
            'service_type' => ['required', Rule::in(array_keys(Lead::SERVICE_TYPES))],
            'budget' => ['nullable', Rule::in(array_keys(Lead::BUDGETS))],
            'message' => ['required', 'string', 'min:15', 'max:3000'],
            'package_slug' => ['nullable', 'string', Rule::exists('packages', 'slug')],
            'consent' => ['accepted'],
            'source_page' => ['nullable', 'string', 'max:255'],
            'utm_source' => ['nullable', 'string', 'max:100'],
            'utm_medium' => ['nullable', 'string', 'max:100'],
            'utm_campaign' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama',
            'organization' => 'nama bisnis/institusi',
            'whatsapp' => 'nomor WhatsApp',
            'service_type' => 'jenis layanan',
            'budget' => 'estimasi anggaran',
            'message' => 'deskripsi kebutuhan',
            'consent' => 'persetujuan kebijakan privasi',
        ];
    }

    public function messages(): array
    {
        return [
            'whatsapp.regex' => 'Gunakan nomor WhatsApp Indonesia yang valid, misalnya 081234567890.',
            'consent.accepted' => 'Anda perlu menyetujui kebijakan privasi agar kami dapat menghubungi Anda.',
            'message.min' => 'Ceritakan kebutuhan Anda minimal :min karakter agar kami dapat memberi saran yang tepat.',
        ];
    }
}
