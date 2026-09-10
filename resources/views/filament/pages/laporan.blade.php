<x-filament-panels::page>
    <form wire:submit="generateLaporan" class="space-y-6">
        {{ $this->form }}

        <div class="flex">
            <x-filament::button
                type="submit"
                icon="heroicon-m-funnel"
                wire:loading.attr="disabled"
                wire:target="generateLaporan"
            >
                Tampilkan Laporan
            </x-filament::button>
        </div>
    </form>

    <div class="mt-6">
        {{ $this->table }}
    </div>
</x-filament-panels::page>