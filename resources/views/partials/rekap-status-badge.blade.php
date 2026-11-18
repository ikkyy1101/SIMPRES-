@php
    $badgeClass = match ($status) {
        'terverifikasi' => 'success',
        'menunggu' => 'warning text-dark',
        'perlu_perbaikan' => 'danger',
        'ditolak' => 'secondary',
        default => 'secondary',
    };
    $label = \App\Models\Prestasi::STATUS[$status]
        ?? (is_string($status) ? ucfirst(str_replace('_', ' ', $status)) : 'Menunggu');
@endphp
<span class="badge bg-{{ $badgeClass }} px-2 py-1 rounded-pill" style="font-size: 11px;">
    {{ $label }}
</span>
