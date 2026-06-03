{{-- resources/views/layouts/admin-sidebar.blade.php --}}
<aside class="hidden md:flex flex-col h-screen w-64 bg-surface-container border-r border-outline-variant fixed left-0 top-0 z-40 overflow-y-auto">
    <div class="text-headline-md font-bold text-primary px-4 py-6">Admin Portal</div>

    <div class="flex items-center gap-3 px-4 py-3 mx-2 mb-4 bg-surface-container-low rounded-xl">
        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
            <span class="material-symbols-outlined text-primary">account_circle</span>
        </div>
        <div>
            <p class="text-label-lg font-bold text-on-surface">{{ auth()->user()->name }}</p>
            <div class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
                <p class="text-[10px] font-medium text-secondary uppercase tracking-wider">System Active</p>
            </div>
        </div>
    </div>

    <nav class="flex-1 py-2 space-y-1">
        @php
            $currentRoute = request()->route()->getName();
            $navItems = [
                ['route' => 'admin.dashboard', 'icon' => 'dashboard',            'label' => 'Dashboard'],
                ['route' => 'admin.antrean',   'icon' => 'group',                'label' => 'Data Antrean'],
                ['route' => 'admin.jadwal',    'icon' => 'event_note',           'label' => 'Jadwal Layanan'],
                ['route' => 'admin.monitor',   'icon' => 'monitoring',           'label' => 'Monitor Antrean'],
                ['route' => 'admin.dashboard', 'icon' => 'notifications',        'label' => 'Notifikasi'],
                ['route' => 'admin.dashboard', 'icon' => 'settings',             'label' => 'Pengaturan'],
            ];
        @endphp
        @foreach($navItems as $item)
            @php $isActive = str_starts_with($currentRoute, $item['route']); @endphp
            <a href="{{ route($item['route']) }}"
               class="{{ $isActive ? 'bg-primary-container text-on-primary-container shadow-sm' : 'text-on-surface-variant hover:bg-surface-variant/50 hover:translate-x-1' }} mx-2 flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200">
                <span class="material-symbols-outlined" style="{{ $isActive ? 'font-variation-settings: FILL 1' : '' }}">{{ $item['icon'] }}</span>
                <span class="text-label-lg">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="p-4 border-t border-outline-variant/30">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-error-container text-on-error-container py-2.5 rounded-xl text-label-lg font-bold hover:brightness-95 transition-all">
                <span class="material-symbols-outlined text-sm">logout</span> Keluar
            </button>
        </form>
    </div>
</aside>
