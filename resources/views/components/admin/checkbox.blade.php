@props(['name', 'label', 'checked' => false, 'help' => null])
<div>
    <label class="flex items-start gap-3 text-sm">
        <input type="checkbox" name="{{ $name }}" value="1" class="mt-0.5 size-4 rounded border-slate-300" @checked(old($name, $checked))>
        <span>
            <span class="font-medium text-slate-800">{{ $label }}</span>
            @if ($help)<span class="block text-xs text-slate-500">{{ $help }}</span>@endif
        </span>
    </label>
    @error($name)<p class="form-error">{{ $message }}</p>@enderror
</div>
