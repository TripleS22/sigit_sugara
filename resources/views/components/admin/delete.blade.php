@props(['action', 'label' => 'Hapus', 'confirm' => 'Hapus data ini? Tindakan ini tidak dapat dibatalkan.'])
<form method="POST" action="{{ $action }}" data-confirm="{{ $confirm }}" {{ $attributes }}>
    @csrf
    @method('DELETE')
    <button type="submit" class="text-sm font-medium text-red-700 hover:underline">{{ $label }}</button>
</form>
