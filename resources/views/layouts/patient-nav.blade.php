{{-- resources/views/layouts/patient-nav.blade.php --}}
<header class="sticky top-0 z-50 bg-surface/80 backdrop-blur-md border-b border-outline-variant/30 shadow-sm">
    <div class="flex justify-between items-center w-full px-4 md:px-16 py-4 max-w-7xl mx-auto">
        <div class="text-xl font-bold text-primary">Puskesmas Digital</div>
        <nav class="hidden md:flex items-center gap-8">
            @php
                $navItems = [
                    ['route' => 'patient.home',          'label' => 'Home'],
                    ['route' => 'patient.ambil-antrean', 'label' => 'Ambil Antrean'],
                    ['route' => 'patient.jadwal',        'label' => 'Jadwal Saya'],
                ];
            @endphp
            @foreach($navItems as $item)
                <a href="{{ route($item['route']) }}"
                   class="{{ request()->routeIs($item['route']) ? 'text-primary border-b-2 border-primary font-bold pb-1' : 'text-on-surface-variant hover:text-primary' }} text-sm font-semibold transition-colors">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>
        <div class="flex items-center gap-4">
            <span class="text-sm text-on-surface-variant hidden md:block">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="p-2 rounded-full hover:bg-surface-variant transition-colors">
                    <span class="material-symbols-outlined text-primary">logout</span>
                </button>
            </form>
        </div>
    </div>
</header>

<!-- Mobile Bottom Nav -->
<nav class="fixed bottom-0 left-0 w-full z-50 flex justify-around items-center px-4 pb-4 pt-2 md:hidden bg-surface/90 backdrop-blur-lg shadow-[0_-4px_12px_rgba(0,0,0,0.05)] rounded-t-xl">
    @php
        $mobileNav = [
            ['route' => 'patient.home',          'icon' => 'home',                'label' => 'Beranda'],
            ['route' => 'patient.ambil-antrean', 'icon' => 'confirmation_number', 'label' => 'Antrean'],
            ['route' => 'patient.jadwal',        'icon' => 'calendar_today',      'label' => 'Jadwal'],
        ];
    @endphp
    @foreach($mobileNav as $item)
        <a href="{{ route($item['route']) }}"
           class="{{ request()->routeIs($item['route']) ? 'bg-primary-container text-on-primary-container rounded-xl px-3 py-1' : 'text-on-surface-variant' }} flex flex-col items-center justify-center">
            <span class="material-symbols-outlined">{{ $item['icon'] }}</span>
            <span class="text-[11px] font-medium">{{ $item['label'] }}</span>
        </a>
    @endforeach
</nav>
