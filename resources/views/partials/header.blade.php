<nav class="bg-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <h1 class="text-xl font-bold text-gray-800">SISTEM PARKIR</h1>
                </div>
                <div class="hidden md:ml-6 md:flex md:space-x-8">
                    <a href="{{ route('laporan.index') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                        Laporan
                    </a>
                </div>
            </div>
            <div class="flex items-center">
                <div class="text-sm text-gray-600">
                    {{ auth()->user()->name }}
                </div>
                <a href="{{ route('logout') }}" class="ml-4 text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                    Logout
                </a>
            </div>
        </div>
    </div>
</nav>