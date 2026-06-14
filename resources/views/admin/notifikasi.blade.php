@extends('layouts.app')

@section('title', 'Notifikasi - Admin Portal')

@section('content')
@include('layouts.admin-sidebar')
<main class="flex-1 md:ml-64 pt-8 pb-20 px-4 md:px-8 min-h-screen">
    <div class="rounded-3xl bg-surface-container p-6 md:p-8 shadow-sm border border-outline-variant max-w-4xl mx-auto">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-8 border-b border-outline-variant/30 pb-6">
            <div>
                <h1 class="text-2xl font-semibold text-on-surface">Notifikasi</h1>
                <p class="mt-2 text-sm text-on-surface-variant">Pusat pemberitahuan dan aktivitas sistem terkini.</p>
            </div>
            <button class="text-sm font-semibold text-primary hover:bg-primary/10 px-4 py-2 rounded-xl transition-colors border border-primary/20">
                Tandai semua dibaca
            </button>
        </div>

        <div class="space-y-4">
            @forelse($notifications as $notif)
                @php
                    $bgClass = $notif['read'] ? 'bg-white opacity-75 hover:opacity-100' : 'bg-primary-container/5 border-l-4 border-l-primary shadow-sm';
                    $iconColor = match($notif['type']) {
                        'info' => 'text-blue-500 bg-blue-50',
                        'success' => 'text-green-600 bg-green-50',
                        'warning' => 'text-amber-500 bg-amber-50',
                        'error' => 'text-red-500 bg-red-50',
                        default => 'text-primary bg-primary/10'
                    };
                @endphp
                <div class="p-5 rounded-2xl border border-outline-variant/50 {{ $bgClass }} flex gap-4 transition-all">
                    <div class="shrink-0">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center {{ $iconColor }}">
                            <span class="material-symbols-outlined text-2xl" style="{{ !$notif['read'] ? 'font-variation-settings:\'FILL\' 1' : '' }}">{{ $notif['icon'] }}</span>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-1 gap-4">
                            <h3 class="text-base font-bold {{ $notif['read'] ? 'text-on-surface-variant' : 'text-on-surface' }}">{{ $notif['title'] }}</h3>
                            <span class="text-xs font-semibold text-on-surface-variant/70 whitespace-nowrap">{{ $notif['time'] }}</span>
                        </div>
                        <p class="text-sm text-on-surface-variant leading-relaxed">{{ $notif['message'] }}</p>
                    </div>
                    @if(!$notif['read'])
                        <div class="flex items-center pl-2">
                            <div class="w-3 h-3 bg-primary rounded-full shadow-[0_0_8px_rgba(0,74,198,0.5)]"></div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-20 opacity-50">
                    <span class="material-symbols-outlined text-[80px] mb-4">notifications_off</span>
                    <h2 class="text-xl font-bold">Tidak Ada Notifikasi</h2>
                    <p class="text-sm mt-2">Semua aktivitas sistem sudah Anda baca.</p>
                </div>
            @endforelse
        </div>
    </div>
</main>
@endsection
