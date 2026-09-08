<?php

namespace App\Http\Controllers;

use App\Models\PintuMasuk;
use App\Models\PintuKeluar;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('laporan.index');
    }

    /**
     * Generate report data based on date range
     */
    public function generateReport(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $tanggal_mulai = $request->tanggal_mulai;
        $tanggal_selesai = $request->tanggal_selesai;

        // Get completed transactions (SELESAI) within date range
        $transaksiSelesai = PintuMasuk::with(['pintuKeluar.user'])
            ->where('status', 'SELESAI')
            ->whereBetween('waktu_keluar', [$tanggal_mulai, $tanggal_selesai])
            ->orderBy('waktu_keluar', 'desc')
            ->get();

        // Calculate totals
        $totalTransaksi = $transaksiSelesai->count();
        $totalPendapatan = $transaksiSelesai->sum('total_bayar');

        return view('laporan.show', compact('tanggal_mulai', 'tanggal_selesai', 'transaksiSelesai', 'totalTransaksi', 'totalPendapatan'));
    }

    /**
     * Export report to PDF
     */
    public function exportPDF(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $tanggal_mulai = $request->tanggal_mulai;
        $tanggal_selesai = $request->tanggal_selesai;

        // Get completed transactions (SELESAI) within date range
        $transaksiSelesai = PintuMasuk::with(['pintuKeluar.user'])
            ->where('status', 'SELESAI')
            ->whereBetween('waktu_keluar', [$tanggal_mulai, $tanggal_selesai])
            ->orderBy('waktu_keluar', 'desc')
            ->get();

        // Calculate totals
        $totalTransaksi = $transaksiSelesai->count();
        $totalPendapatan = $transaksiSelesai->sum('total_bayar');

        // Generate PDF
        $pdf = Pdf::loadView('laporan.pdf', compact('tanggal_mulai', 'tanggal_selesai', 'transaksiSelesai', 'totalTransaksi', 'totalPendapatan'));
        
        return $pdf->download('laporan-pendapatan-' . $tanggal_mulai . '-to-' . $tanggal_selesai . '.pdf');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Laporan $laporan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Laporan $laporan)
    {
        //
    }
}
