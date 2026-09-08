<x-filament-panels::page>
    <form wire:submit.prevent="generateLaporan" class="space-y-6">
        {{ $this->form }}

        <div class="flex flex-wrap gap-3">
            <x-filament::button
                type="submit"
                icon="heroicon-m-funnel"
                wire:loading.attr="disabled"
                wire:target="generateLaporan"
            >
                Tampilkan Laporan
            </x-filament::button>

            <x-filament::button
                type="button"
                wire:click="exportToPDF"
                color="danger"
                icon="heroicon-m-document-arrow-down"
                wire:loading.attr="disabled"
                wire:target="exportToPDF"
            >
                Export (PDF)
            </x-filament::button>
        </div>
    </form>

    @if($summaryData)
        {{-- Summary cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 my-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Rentang Tanggal</h3>
                <p class="text-2xl font-bold text-blue-600">
                    {{ $summaryData['tanggal_mulai'] }} <span class="text-sm text-gray-500">-</span> {{ $summaryData['tanggal_selesai'] }}
                </p>
            </div>
            
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Transaksi</h3>
                <p class="text-3xl font-bold text-green-600">{{ number_format($summaryData['total_transaksi'], 0, ',', '.') }}</p>
            </div>
            
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Pendapatan</h3>
                <p class="text-3xl font-bold text-purple-600">Rp {{ number_format($summaryData['total_pendapatan'], 2, ',', '.') }}</p>
            </div>
            
        </div>

        {{-- Transaction table --}}
        <div class="mt-6">
            {{ $this->table }}
        </div>
    @endif
</x-filament-panels::page>