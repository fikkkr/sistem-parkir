@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Laporan Pendapatan Parkir</h1>
        
        <form action="{{ route('laporan.generate') }}" method="POST" class="bg-white shadow-md rounded-lg p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Mulai
                    </label>
                    <input 
                        type="date" 
                        id="tanggal_mulai" 
                        name="tanggal_mulai"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    >
                </div>
                
                <div>
                    <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Selesai
                    </label>
                    <input 
                        type="date" 
                        id="tanggal_selesai" 
                        name="tanggal_selesai"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    >
                </div>
            </div>
            
            <div class="flex justify-end">
                <button 
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md transition duration-200"
                >
                    Tampilkan Laporan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection