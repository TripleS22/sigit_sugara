@props(['name', 'label', 'value' => null, 'rows' => 4, 'help' => null, 'required' => false, 'counter' => null, 'mono' => false])
@php($id = 'f-'.$name)
<div>
    <label for="{{ $id }}" class="form-label">{{ $label }} @if ($required)<span class="text-red-600">*</span>@endif</label>
    <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $rows }}" @required($required)
        @if ($counter) data-counter="#{{ $id }}-count" @endif
        {{ $attributes->merge(['class' => 'form-input'.($mono ? ' font-mono text-sm' : '')]) }}>{{ old($name, $value) }}</textarea>
    <div class="mt-1 flex justify-between gap-4 text-xs text-slate-500">
        <span>{{ $help }}</span>
        @if ($counter)<span id="{{ $id }}-count" aria-live="polite"></span>@endif
    </div>
    @error($name)<p class="form-error">{{ $message }}</p>@enderror
</div>
