<div class="space-y-6 text-center">
    <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-900">
        <div class="flex min-h-48 items-center justify-center [&>svg]:h-auto [&>svg]:max-w-full">
            {!! $barcode !!}
        </div>
    </div>

    <dl class="grid gap-3 text-left sm:grid-cols-3">
        <div>
            <dt class="text-sm text-gray-500">Kode Karcis</dt>
            <dd class="font-medium text-gray-950 dark:text-white">{{ $record->kode_karcis }}</dd>
        </div>
        <div>
            <dt class="text-sm text-gray-500">Plat Nomor</dt>
            <dd class="font-medium text-gray-950 dark:text-white">{{ $record->plat_nomor }}</dd>
        </div>
        <div>
            <dt class="text-sm text-gray-500">Jam Masuk</dt>
            <dd class="font-medium text-gray-950 dark:text-white">
                @if ($record->waktu_masuk)
                    {{ \Carbon\Carbon::parse($record->waktu_masuk)->format('d M Y, H:i:s') }}
                @else
                    <span class="text-gray-400">-</span>
                @endif
            </dd>
        </div>
    </dl>

    <button
        type="button"
        onclick="window.print()"
        class="inline-flex items-center rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-500"
    >
        Cetak Barcode
    </button>
</div>
