@extends('layouts.admin.admin')

@section('title', 'Detail Pengajuan Izin')
@section('page-title', 'Tinjauan Pengajuan Izin Guru')

@section('content')
    <div class="max-w-4xl space-y-6">
        <!-- Kartu Informasi Utama Pengajuan -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-slate-800">Rincian Pengajuan Izin</h2>
                    <p class="text-xs text-slate-500">Diajukan pada {{ $pengajuanIzin->created_at->translatedFormat('d F Y, H:i') }} WIB</p>
                </div>
                <a href="{{ route('admin.pengajuan-izin.index') }}" class="text-xs font-medium text-slate-500 hover:text-slate-800">
                    &larr; Kembali ke Daftar
                </a>
            </div>

            <div class="p-6 grid grid-cols-1 gap-5 sm:grid-cols-2 text-sm">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Nama Guru</p>
                    <p class="mt-1 font-semibold text-slate-800">{{ $pengajuanIzin->guru->name ?? '-' }}</p>
                    <p class="text-xs text-slate-500">{{ $pengajuanIzin->guru->nip ? 'NIP. ' . $pengajuanIzin->guru->nip : '' }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Status Verifikasi</p>
                    @php
                        $statusBadge = match($pengajuanIzin->status) {
                            'disetujui' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                            'ditolak'   => 'bg-rose-100 text-rose-700 border-rose-200',
                            default     => 'bg-amber-100 text-amber-800 border-amber-200',
                        };
                    @endphp
                    <div class="mt-1">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold capitalize border {{ $statusBadge }}">
                            {{ $pengajuanIzin->status }}
                        </span>
                    </div>
                </div>

                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Jenis Ketidakhadiran</p>
                    <p class="mt-1 font-medium text-slate-800 capitalize">{{ str_replace('_', ' ', $pengajuanIzin->jenis) }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tanggal Berlaku</p>
                    <p class="mt-1 font-medium text-slate-800">
                        @if($pengajuanIzin->tanggal_mulai->equalTo($pengajuanIzin->tanggal_selesai))
                            {{ $pengajuanIzin->tanggal_mulai->translatedFormat('l, d F Y') }}
                        @else
                            {{ $pengajuanIzin->tanggal_mulai->translatedFormat('d M Y') }} s.d. {{ $pengajuanIzin->tanggal_selesai->translatedFormat('d M Y') }}
                            ({{ $pengajuanIzin->tanggal_mulai->diffInDays($pengajuanIzin->tanggal_selesai) + 1 }} hari)
                        @endif
                    </p>
                </div>

                <div class="sm:col-span-2">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Alasan / Keterangan</p>
                    <div class="mt-1.5 p-3.5 bg-slate-50 border border-slate-200 rounded-lg text-xs sm:text-sm text-slate-700 leading-relaxed">
                        {{ $pengajuanIzin->alasan }}
                    </div>
                </div>

                @if($pengajuanIzin->pesan_tugas)
                    <div class="sm:col-span-2">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Instruksi Tugas untuk Kelas</p>
                        <div class="mt-1.5 p-3.5 bg-indigo-50/50 border border-indigo-100 rounded-lg text-xs sm:text-sm text-slate-700 leading-relaxed">
                            {{ $pengajuanIzin->pesan_tugas }}
                        </div>
                    </div>
                @endif

                @if($pengajuanIzin->lampiran)
                    <div class="sm:col-span-2">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Dokumen Lampiran</p>
                        <a href="{{ asset('storage/' . $pengajuanIzin->lampiran) }}" target="_blank"
                           class="mt-1.5 inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            <span>Lihat / Unduh Lampiran Surat</span>
                        </a>
                    </div>
                @endif

                @if($pengajuanIzin->status !== 'menunggu')
                    <div class="sm:col-span-2 pt-4 border-t border-slate-100">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Catatan Keputusan Admin</p>
                        <p class="mt-1 text-xs sm:text-sm text-slate-700">
                            {{ $pengajuanIzin->catatan_admin ?? 'Tidak ada catatan tambahan.' }}
                        </p>
                        <p class="mt-1 text-[11px] text-slate-400">
                            Diproses oleh {{ $pengajuanIzin->pemroses->name ?? 'Admin' }}
                            pada {{ $pengajuanIzin->diproses_pada?->translatedFormat('d M Y, H:i') }} WIB
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Daftar Jadwal Mengajar yang Terdampak -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="text-sm font-semibold text-slate-800">Jadwal Mengajar Terdampak ({{ count($jadwalTerdampak) }} Sesi)</h3>
                <p class="text-xs text-slate-500">Jika disetujui, sesi jadwal berikut otomatis diteruskan ke Petugas Piket sebagai kelas kosong</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-5 py-3">Tanggal</th>
                            <th class="px-5 py-3">Jam Ke-</th>
                            <th class="px-5 py-3">Kelas</th>
                            <th class="px-5 py-3">Mata Pelajaran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                        @forelse($jadwalTerdampak as $item)
                            <tr>
                                <td class="px-5 py-3 font-medium text-slate-800 capitalize">
                                    {{ $item['hari'] }}, {{ $item['tanggal']->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-5 py-3 font-mono text-xs text-slate-600">
                                    Jam {{ $item['jadwal']->jamPelajaran->jam_ke ?? '-' }}
                                </td>
                                <td class="px-5 py-3 font-semibold text-indigo-700">
                                    {{ $item['jadwal']->kelas->nama ?? '-' }}
                                </td>
                                <td class="px-5 py-3 text-slate-700">
                                    {{ $item['jadwal']->mataPelajaran->nama ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-6 text-center text-xs text-slate-400">
                                    Tidak ada jadwal mengajar pada rentang tanggal izin ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Panel Keputusan Admin (Muncul jika status masih menunggu) -->
        @if($pengajuanIzin->status === 'menunggu')
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Form Setujui -->
                <form action="{{ route('admin.pengajuan-izin.setujui', $pengajuanIzin) }}" method="POST"
                      class="bg-white border border-emerald-200 rounded-xl p-5 shadow-xs space-y-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <h3 class="text-sm font-semibold text-emerald-800">Setujui Pengajuan Izin</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Presensi guru akan dicatat dan jadwal kelas kosong otomatis dibuat.</p>
                    </div>
                    <div>
                        <label for="catatan_setuju" class="block text-xs font-medium text-slate-700">Catatan Admin (Opsional)</label>
                        <textarea id="catatan_setuju" name="catatan_admin" rows="2"
                                  placeholder="Contoh: Silakan, tugas sudah diteruskan ke guru piket."
                                  class="mt-1 w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:outline-none focus:border-emerald-500"></textarea>
                    </div>
                    <button type="submit"
                            class="w-full py-2.5 text-xs font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors cursor-pointer">
                        Setujui Pengajuan Izin
                    </button>
                </form>

                <!-- Form Tolak -->
                <form action="{{ route('admin.pengajuan-izin.tolak', $pengajuanIzin) }}" method="POST"
                      class="bg-white border border-rose-200 rounded-xl p-5 shadow-xs space-y-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <h3 class="text-sm font-semibold text-rose-800">Tolak Pengajuan Izin</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Berikan alasan penolakan agar guru mendapatkan kejelasan.</p>
                    </div>
                    <div>
                        <label for="catatan_tolak" class="block text-xs font-medium text-slate-700">
                            Alasan Penolakan <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="catatan_tolak" name="catatan_admin" rows="2" required
                                  placeholder="Contoh: Lampiran surat keterangan belum diunggah."
                                  class="mt-1 w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:outline-none focus:border-rose-500"></textarea>
                        @error('catatan_admin') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit"
                            class="w-full py-2.5 text-xs font-semibold text-white bg-rose-600 rounded-lg hover:bg-rose-700 transition-colors cursor-pointer">
                        Tolak Pengajuan Izin
                    </button>
                </form>
            </div>
        @endif
    </div>
@endsection