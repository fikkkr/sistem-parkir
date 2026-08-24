<?php

namespace App\Http\Controllers;

use App\Models\PintuKeluar;
use Illuminate\Http\Request;

class PintuKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PintuKeluar $pintuKeluar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PintuKeluar $pintuKeluar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PintuKeluar $pintuKeluar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PintuKeluar $pintuKeluar)
    {
        //
    }

    /**
     * Display the payment receipt for printing.
     */
    public function struk(PintuKeluar $pintuKeluar)
    {
        $keluar = $pintuKeluar->load(['pintuMasuk.masterTarif']);

        return view('pintu-keluar.struk', ['keluar' => $keluar]);
    }
}
