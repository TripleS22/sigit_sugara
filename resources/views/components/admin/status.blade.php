@props(['on', 'yes' => 'Terbit', 'no' => 'Draft'])
<span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $on ? 'bg-green-100 text-green-800' : 'bg-slate-200 text-slate-700' }}">{{ $on ? $yes : $no }}</span>
