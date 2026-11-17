@props(['status'])

@php
    $normalized = strtolower(str_replace(' ', '_', $status));
    
    $styles = match($normalized) {
        'hadir', 'disetujui', 'selesai', 'final', 'aktif' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
        'menunggu', 'terlambat', 'draft', 'ditugaskan' => 'bg-amber-100 text-amber-700 border-amber-200',
        'izin', 'dispensasi', 'dinas' => 'bg-sky-100 text-sky-700 border-sky-200',
        'sakit' => 'bg-orange-100 text-orange-700 border-orange-200',
        'alpa', 'ditolak', 'belum_ditangani', 'kelas_kosong', 'nonaktif' => 'bg-rose-100 text-rose-700 border-rose-200',
        default => 'bg-slate-100 text-slate-700 border-slate-200',
    };

    $label = ucwords(str_replace('_', ' ', $status));
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border $styles"]) }}>
    {{ $label }}
</span>