@props(['name', 'label', 'value' => null, 'type' => 'text', 'help' => null, 'required' => false, 'counter' => null])
@php($id = 'f-'.str_replace(['[', ']', '.'], '-', $name))
<div>
    <label for="{{ $id }}" class="form-label">{{ $label }} @if ($required)<span class="text-red-600">*</span>@endif</label>
    <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}" @required($required)
        @if ($counter) data-counter="#{{ $id }}-count" @endif
        {{ $attributes->merge(['class' => 'form-input']) }}>
    <div class="mt-1 flex justify-between gap-4 text-xs text-slate-500">
        <span>{{ $help }}</span>
        @if ($counter)<span id="{{ $id }}-count" aria-live="polite"></span>@endif
    </div>
    @error($name)<p class="form-error">{{ $message }}</p>@enderror
</div>
