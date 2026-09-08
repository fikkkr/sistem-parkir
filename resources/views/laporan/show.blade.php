@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Laporan Pendapatan Parkir</h1>
            <form action="{{ route('laporan.export-pdf') }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="tanggal_mulai" value="{{ $tanggal_mulai }}">
                <input type="hidden" name="tanggal_selesai" value="{{ $tanggal_selesai }}">
                <button 
                    type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-md transition duration-200 flex items-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export PDF
                </button>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Rentang Tanggal</h3>
                <p class="text-2xl font-bold text-blue-600">
                    {{ \Carbon\Carbon::parse($tanggal_mulai)->translatedFormat('d F Y') }} 
                    <span class="text-sm text-gray-500">-</span> 
                    {{ \Carbon\Carbon::parse($tanggal_selesai)->translatedFormat('d F Y') }}
                </p>
            </div>
            
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Transaksi</h3>
                <p class="text-3xl font-bold text-green-600">{{ number_format($totalTransaksi, 0, ',', '.') }}</p>
            </div>
            
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Pendapatan</h3>
                <p class="text-3xl font-bold text-purple-600">Rp {{ number_format($totalPendapatan, 2, ',', '.') }}</p>
            </div>
        </div>

        <!-- Transaction Table -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Detail Transaksi</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Karcis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plat Nomor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Masuk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Keluar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Bayar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Petugas</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($transaksiSelesai as $transaksi)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $transaksi->kode_karcis }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaksi->plat_nomor }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($transaksi->waktu_masuk)->translatedFormat('d F Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($transaksi->waktu_keluar)->translatedFormat('d F Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaksi->durasi_jam }} jam</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                                    Rp {{ number_format($transaksi->total_bayar, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transaksi->pintuKeluar?->user?->name ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    Tidak ada transaksi dalam rentang tanggal yang dipilih
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Back to Form -->
        <div class="mt-8 text-center">
            <a href="{{ route('laporan.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Form Laporan
            </a>
        </div>
    </div>
</div>
@endsection