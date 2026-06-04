<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Arsip Surat Digital PERUMDA Air Minum Tirta Danu Arta</title>
    <link rel="icon" type="image/png" href="{{ asset('images/database.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/database.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Scrollbar bersih untuk sidebar & konten */
        ::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.15);
            border-radius: 99px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.3);
        }
        /* Scrollbar untuk area konten (terang) */
        .content-scroll::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.1);
        }
        .content-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(0,0,0,0.2);
        }
        /* Sembunyikan scrollbar horizontal yang tidak sengaja muncul */
        body {
            overflow: hidden;
        }
        /* Fade in saat load */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        @keyframes pulseOnce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }

        /* Page content fade in */
       .animate-content {
            animation: fadeInUp 0.4s ease-out both;
        }
        .animate-content:nth-child(2) { animation-delay: 0.05s; }
        .animate-content:nth-child(3) { animation-delay: 0.1s; }
        .animate-content:nth-child(4) { animation-delay: 0.15s; }
        .animate-content:nth-child(5) { animation-delay: 0.2s; }
        
        /* Tabel row hover */
        tbody tr {
            transition: all 0.2s ease;
        }

        /* Card hover lift */
        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        /* Button press */
        button, a.inline-flex, [type="submit"] {
            transition: all 0.15s ease;
        }
        button:active, a.inline-flex:active, [type="submit"]:active {
            transform: scale(0.97);
        }

        /* Sidebar nav items */
        nav a {
            transition: all 0.2s ease;
        }

        /* Modal backdrop */
        .modal-backdrop {
            animation: fadeIn 0.2s ease-out;
        }
        .modal-content {
            animation: scaleIn 0.25s ease-out;
        }

        /* Badge pulse */
        .badge-pulse {
            animation: pulseOnce 0.5s ease;
        }

        /* Input focus glow */
        input:focus, select:focus, textarea:focus {
            transition: all 0.2s ease;
        }

        /* Flash message */
        .flash-message {
            animation: slideInRight 0.4s ease-out;
        }

        /* Stat card stagger */
        .stat-card { animation: fadeInUp 0.4s ease-out both; }
        .stat-card:nth-child(1) { animation-delay: 0s; }
        .stat-card:nth-child(2) { animation-delay: 0.05s; }
        .stat-card:nth-child(3) { animation-delay: 0.1s; }
        .stat-card:nth-child(4) { animation-delay: 0.15s; }
        .stat-card:nth-child(5) { animation-delay: 0.2s; }
        .stat-card:nth-child(6) { animation-delay: 0.25s; }
        .stat-card:nth-child(7) { animation-delay: 0.3s; }
        .stat-card:nth-child(8) { animation-delay: 0.35s; }

        /* Tooltip */
        [title] {
            position: relative;
        }

        /* Smooth scroll */
        .content-scroll {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 font-sans antialiased h-full">
    <script>
        const html = document.documentElement;
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            html.classList.add('dark');
        }
        window.toggleDarkMode = function() {
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        };

        // ── Audio ──────────────────────────────────────────────────
        let audioCtx  = null;
        let prevCount = -1;

        function playBeep() {
            try {
                if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const osc  = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.type            = 'sine';
                osc.frequency.value = 880;
                gain.gain.setValueAtTime(0.3, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.4);
                osc.start(audioCtx.currentTime);
                osc.stop(audioCtx.currentTime + 0.4);
            } catch(e) {}
        }

        function playNotifSound() {
            playBeep();
            setTimeout(() => playBeep(), 300);
        }

        // ── Dropdown Toggle ────────────────────────────────────────
        function toggleNotif() {
            document.getElementById('notif-dropdown').classList.toggle('hidden');
        }

        function tutupDropdown() {
            document.getElementById('notif-dropdown').classList.add('hidden');
        }

        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('notif-wrapper');
            if (wrapper && !wrapper.contains(e.target)) {
                tutupDropdown();
            }
        });

        // ── Render item dropdown ───────────────────────────────────
        function renderDropdownItem(item) {
            return `
                <a href="${item.url}"
                class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 border-b border-gray-100 dark:border-gray-700 last:border-0 transition">
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 rounded-full bg-blue-500 mt-1.5 flex-shrink-0"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 dark:text-white truncate">${item.perihal}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Dari: ${item.dari}</p>
                            <p class="text-xs text-gray-400 mt-0.5">${item.waktu}</p>
                        </div>
                    </div>
                </a>
            `;
        }

        // ── Polling badge ──────────────────────────────────────────
        function fetchNotifikasi() {
            fetch('{{ route("notifikasi.data") }}', {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                credentials: 'same-origin',
            })
            .then(r => r.ok ? r.json() : Promise.reject(r.status))
            .then(data => {
                const badge = document.getElementById('notif-badge');
                const list  = document.getElementById('notif-list');
                const count = data.count ?? 0;

                // Badge
                if (count > 0) {
                    badge.textContent = count > 9 ? '9+' : count;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }

                // Suara & browser notif kalau ada baru
                if (prevCount !== -1 && count > prevCount) {
                    playNotifSound();
                    if ('Notification' in window && Notification.permission === 'granted' && data.items[0]) {
                        const n = data.items[0];
                        const notif = new Notification('📋 Disposisi Baru', {
                            body: `${n.perihal}\nDari: ${n.dari}`,
                            icon: '/favicon.ico',
                            tag:  'disposisi-' + n.id,
                        });
                        notif.onclick = () => { window.focus(); window.location.href = n.url; };
                    }
                }
                prevCount = count;

                // Render dropdown
                if (!data.items || data.items.length === 0) {
                    list.innerHTML = `
                        <div class="px-4 py-8 text-center text-gray-400">
                            <svg class="w-8 h-8 mx-auto mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <p class="text-sm">Tidak ada notifikasi baru</p>
                        </div>`;
                } else {
                    list.innerHTML = data.items.map(renderDropdownItem).join('');
                }
            })
            .catch(err => console.warn('Notifikasi error:', err));
        }

        // ── Modal Riwayat ──────────────────────────────────────────
        let activeTab = 'belum';

        function bukaRiwayatNotif() {
            const modal   = document.getElementById('modal-notifikasi');
            const loading = document.getElementById('notif-modal-loading');
            const content = document.getElementById('notif-modal-content');

            modal.classList.remove('hidden');
            loading.classList.remove('hidden');
            content.classList.add('hidden');

            fetch('{{ route("notifikasi.riwayat") }}', {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                credentials: 'same-origin',
            })
            .then(r => r.ok ? r.json() : Promise.reject(r.status))
            .then(data => {
                // Isi tiap tab
                renderTabList('list-belum',   data.belum_dibaca, 'belum');
                renderTabList('list-diproses', data.diproses,    'diproses');
                renderTabList('list-selesai',  data.selesai,     'selesai');

                // Update count badge tab
                document.getElementById('tab-belum-count').textContent    = data.belum_dibaca.length || '';
                document.getElementById('tab-diproses-count').textContent = data.diproses.length || '';
                document.getElementById('tab-selesai-count').textContent  = data.selesai.length || '';

                loading.classList.add('hidden');
                content.classList.remove('hidden');

                // Reset ke tab belum dibaca
                switchTab('belum');
            })
            .catch(err => {
                console.warn('Riwayat error:', err);
                loading.innerHTML = '<p class="text-center text-red-400 text-sm py-8">Gagal memuat data</p>';
            });
        }

        function tutupRiwayatNotif() {
            document.getElementById('modal-notifikasi').classList.add('hidden');
        }

        // Tutup klik backdrop
        document.getElementById('modal-notifikasi')?.addEventListener('click', function(e) {
            if (e.target === this) tutupRiwayatNotif();
        });

        function switchTab(tab) {
            activeTab = tab;

            // Reset semua tab
            ['belum', 'diproses', 'selesai'].forEach(t => {
                const btn   = document.getElementById('tab-' + t);
                const panel = document.getElementById('panel-' + t);

                btn.classList.remove('text-blue-600', 'border-blue-600');
                btn.classList.add('text-gray-500', 'dark:text-gray-400', 'border-transparent');
                panel.classList.add('hidden');
            });

            // Aktifkan tab yang dipilih
            const activeBtn   = document.getElementById('tab-' + tab);
            const activePanel = document.getElementById('panel-' + tab);

            activeBtn.classList.add('text-blue-600', 'border-blue-600');
            activeBtn.classList.remove('text-gray-500', 'dark:text-gray-400', 'border-transparent');
            activePanel.classList.remove('hidden');
        }

        function statusLabel(status) {
            const map = {
                menunggu:   { text: 'Menunggu',   cls: 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' },
                dibaca:     { text: 'Dibaca',     cls: 'bg-blue-100 text-blue-700' },
                diproses:   { text: 'Diproses',   cls: 'bg-yellow-100 text-yellow-700' },
                diteruskan: { text: 'Diteruskan', cls: 'bg-purple-100 text-purple-700' },
                selesai:    { text: 'Selesai',    cls: 'bg-green-100 text-green-700' },
            };
            const s = map[status] || map.menunggu;
            return `<span class="text-xs px-2 py-0.5 rounded-full font-medium ${s.cls}">${s.text}</span>`;
        }

        function renderTabList(elId, items, type) {
            const el = document.getElementById(elId);

            if (!items || items.length === 0) {
                const emptyMsg = {
                    belum:    'Tidak ada disposisi yang belum dibaca',
                    diproses: 'Tidak ada disposisi yang sedang diproses',
                    selesai:  'Tidak ada disposisi yang sudah selesai',
                };
                el.innerHTML = `
                    <div class="px-4 py-10 text-center text-gray-400">
                        <svg class="w-8 h-8 mx-auto mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-sm">${emptyMsg[type] || 'Tidak ada data'}</p>
                    </div>`;
                return;
            }

            el.innerHTML = items.map(item => `
                <a href="${item.url}"
                class="block px-5 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700 last:border-0 transition">
                    <div class="flex items-start justify-between gap-3 mb-1.5">
                        <p class="text-sm font-medium text-gray-800 dark:text-white leading-snug flex-1">
                            ${item.perihal}
                        </p>
                        ${statusLabel(item.status)}
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                        📋 ${item.no_surat}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2 line-clamp-2">
                        ${item.isi}
                    </p>
                    <div class="flex items-center justify-between text-xs text-gray-400">
                        <span>Dari: <span class="font-medium text-gray-600 dark:text-gray-300">${item.dari}</span></span>
                        <span>${item.waktu_full}</span>
                    </div>
                    ${item.dibaca_at ? `<p class="text-xs text-blue-500 mt-1">👁 Dibaca: ${item.dibaca_at}</p>` : ''}
                    ${item.selesai_at ? `<p class="text-xs text-green-500 mt-1">✅ Selesai: ${item.selesai_at}</p>` : ''}
                </a>
            `).join('');
        }

        // ── Init ───────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function() {
            // Minta izin notifikasi saat klik bell pertama kali
            document.getElementById('notif-wrapper')?.addEventListener('click', function onFirstClick() {
                if ('Notification' in window && Notification.permission === 'default') {
                    Notification.requestPermission();
                }
                this.removeEventListener('click', onFirstClick);
            }, { once: true });

            fetchNotifikasi();
            setInterval(fetchNotifikasi, 30000);
        });
    </script>

    <div class="flex h-screen">

        {{-- SIDEBAR --}}
        <aside class="w-64 bg-blue-900 dark:bg-gray-800 text-white flex flex-col flex-shrink-0 overflow-hidden">

            {{-- Logo --}}
            <div class="px-5 py-4 border-b border-blue-800/60 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg overflow-hidden bg-white/10 flex items-center justify-center flex-shrink-0 p-1">
                        <img src="{{ asset('images/logo-perumda.png') }}" alt="Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h1 class="text-base font-bold leading-tight text-white">Arsip Digital</h1>
                        <p class="text-xs text-blue-300 mt-0.5">PERUMDA Tirta Danu Arta</p>
                    </div>
                </div>
            </div>

            {{-- User Info dengan Sambutan --}}
            <div class="px-5 py-5 border-b border-blue-800/60 flex-shrink-0">
                <div class="flex items-center justify-between mb-2">
                    <p id="greeting-text" class="text-sm font-medium text-blue-200"></p>
                    <span id="live-clock" class="text-xs text-blue-400/80 font-mono bg-blue-800/50 px-2 py-0.5 rounded-md"></span>
                </div>
                <p class="text-base font-bold text-white truncate">{{ auth()->user()->name }}</p>
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-xs bg-blue-700/60 px-2.5 py-0.5 rounded-full text-blue-200">
                        {{ ucfirst(auth()->user()->role) }}
                    </span>
                    @if(auth()->user()->jabatan)
                    <span class="text-xs text-blue-400/60 truncate">
                        {{ auth()->user()->jabatan->nama_jabatan }}
                    </span>
                    @endif
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-4 py-4 space-y-0.5 overflow-y-auto min-h-0">

                {{-- Dashboard — Semua --}}
                <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                        {{ request()->routeIs('dashboard') ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-800/70 hover:text-white' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>

                {{-- Arsip Surat — Staff & Admin --}}
                @if(in_array(auth()->user()->role, ['staff', 'admin']))
                <p class="text-xs text-blue-400/80 uppercase tracking-wider px-3 pt-4 pb-1 font-medium">Arsip Surat</p>

                <a href="{{ route('surat-masuk.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                        {{ request()->routeIs('surat-masuk.*') ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-800/70 hover:text-white' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    Surat Masuk
                    @if(($badgeSuratBaru ?? 0) > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full leading-none">{{ $badgeSuratBaru }}</span>
                    @endif
                </a>

                <a href="{{ route('surat-keluar.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                        {{ request()->routeIs('surat-keluar.*') ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-800/70 hover:text-white' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Surat Keluar
                </a>
                @endif

                {{-- Kotak Disposisi — Direktur, Kabag, Kasubag, Admin --}}
                @if(in_array(auth()->user()->role, ['direktur', 'kabag', 'kasubbag', 'admin']))
                <p class="text-xs text-blue-400/80 uppercase tracking-wider px-3 pt-4 pb-1 font-medium">Disposisi</p>

                <a href="{{ route('disposisi.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                        {{ request()->routeIs('disposisi.*') ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-800/70 hover:text-white' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Kotak Disposisi
                    @if(($badgeDisposisi ?? 0) > 0)
                    <span class="ml-auto bg-yellow-400 text-yellow-900 text-xs px-1.5 py-0.5 rounded-full leading-none">{{ $badgeDisposisi }}</span>
                    @endif
                </a>
                @endif

                {{-- Lemari Arsip Surat — Semua role --}}
                <p class="text-xs text-blue-400/80 uppercase tracking-wider px-3 pt-4 pb-1 font-medium">Arsip</p>

                    <a href="{{ route('arsip.index') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                            {{ request()->routeIs('arsip.*') ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-800/70 hover:text-white' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                        </svg>
                        Lemari Arsip Surat
                    </a>

                    {{-- Laporan — Staff, Direktur, Admin --}}
                    @if(in_array(auth()->user()->role, ['staff', 'direktur', 'admin']))
                    <p class="text-xs text-blue-400/80 uppercase tracking-wider px-3 pt-4 pb-1 font-medium">Laporan</p>

                    <a href="{{ route('laporan.index') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                            {{ request()->routeIs('laporan.*') ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-800/70 hover:text-white' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Laporan
                    </a>
                    @endif

                    {{-- Master Data — Admin only (kecuali Pengguna) --}}
                    @if(auth()->user()->role === 'admin')
                    <p class="text-xs text-blue-400/80 uppercase tracking-wider px-3 pt-4 pb-1 font-medium">Master Data</p>

                    <a href="{{ route('master.bagian.index') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                            {{ request()->routeIs('master.bagian.*') ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-800/70 hover:text-white' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Bagian
                    </a>

                    <a href="{{ route('master.jabatan.index') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                            {{ request()->routeIs('master.jabatan.*') ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-800/70 hover:text-white' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Jabatan
                    </a>

                    <a href="{{ route('master.kategori.index') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                            {{ request()->routeIs('master.kategori.*') ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-800/70 hover:text-white' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Kategori Surat
                    </a>
                    @endif

                    {{-- Pengguna — Admin & Direktur --}}
                    @if(in_array(auth()->user()->role, ['admin', 'direktur']))
                        @if(auth()->user()->role === 'direktur')
                        <p class="text-xs text-blue-400/80 uppercase tracking-wider px-3 pt-4 pb-1 font-medium">Data</p>
                        @endif
                    <a href="{{ route('master.user.index') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                            {{ request()->routeIs('master.user.*') ? 'bg-blue-700 text-white' : 'text-blue-200 hover:bg-blue-800/70 hover:text-white' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Pengguna
                    </a>
                    @endif
                </nav>

            {{-- Logout --}}
            <div class="px-4 py-4 border-t border-blue-800/60 flex-shrink-0">
                <form id="logout-form" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="button" onclick="konfirmasiLogout()"
                            class="flex items-center gap-3 w-full px-3 py-2 rounded-lg text-sm text-blue-200 hover:bg-blue-800/70 hover:text-white transition">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>

        </aside>

        {{-- MAIN CONTENT --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

            {{-- Topbar --}}
            <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex items-center justify-between flex-shrink-0">
                <h2 class="text-base font-semibold text-gray-700 dark:text-white truncate">@yield('title', 'Dashboard')</h2>

                <div class="flex items-center gap-2 flex-shrink-0 ml-4">

                    <div class="flex items-center gap-2 flex-shrink-0 ml-4">

                    {{-- Notifikasi Bell --}}
                    <div class="relative" id="notif-wrapper">
                        <button onclick="toggleNotif()"
                                class="relative p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span id="notif-badge"
                                class="hidden absolute -top-1 -right-1 min-w-[18px] h-[18px] bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold px-0.5 leading-none">
                                0
                            </span>
                        </button>

                        {{-- Dropdown kecil --}}
                        <div id="notif-dropdown"
                            class="hidden absolute right-0 top-11 w-80 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 z-50 overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                                <p class="font-semibold text-sm text-gray-800 dark:text-white">Notifikasi Masuk</p>
                                {{-- Ganti link ke fungsi popup --}}
                                <button onclick="tutupDropdown(); bukaRiwayatNotif()"
                                        class="text-xs text-blue-600 hover:underline">
                                    Lihat Semua
                                </button>
                            </div>
                            <div id="notif-list" class="max-h-72 overflow-y-auto">
                                <div class="px-4 py-8 text-center text-gray-400">
                                    <p class="text-sm">Memuat...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Dark Mode Toggle --}}
                    <button onclick="toggleDarkMode()"
                            class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400 transition">
                        <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </button>

                    <span class="text-xs text-gray-400 dark:text-gray-500 hidden sm:block whitespace-nowrap">
                        {{ now()->isoFormat('dddd, D MMMM Y') }}
                    </span>

                     {{-- Foto profil + dropdown --}}
                        <div class="relative hidden sm:block" id="profil-wrapper">
                            <button onclick="toggleProfilMenu()"
                                    class="w-9 h-9 rounded-lg overflow-hidden border-2 border-gray-200 dark:border-gray-600 hover:border-blue-400 dark:hover:border-blue-500 transition flex-shrink-0 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                @if(auth()->user()->foto)
                                <img src="{{ Storage::url(auth()->user()->foto) }}" alt="Foto" class="w-full h-full object-cover">
                                @else
                                <div class="w-full h-full bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center">
                                    <span class="text-xs font-bold text-blue-600">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                </div>
                                @endif
                            </button>

                            {{-- Dropdown Menu --}}
                            <div id="profil-dropdown"
                                class="hidden absolute right-0 top-12 w-64 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 z-50 overflow-hidden">

                                {{-- Info user --}}
                                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg overflow-hidden flex-shrink-0 border border-gray-200 dark:border-gray-600">
                                            @if(auth()->user()->foto)
                                            <img src="{{ Storage::url(auth()->user()->foto) }}" alt="Foto" class="w-full h-full object-cover">
                                            @else
                                            <div class="w-full h-full bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center">
                                                <span class="text-sm font-bold text-blue-600">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-gray-800 dark:text-white truncate">{{ auth()->user()->name }}</p>
                                            <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Menu items --}}
                                <div class="py-1">
                                    <a href="{{ route('profil.edit') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        Edit Profil
                                    </a>

                                    <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

                                    <form id="logout-form-topbar" method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="button" onclick="konfirmasiLogout()"
                                            class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Keluar
                                    </button>
                                </form>
                                </div>
                            </div>
                        </div>
                </div>
            </header>

            {{-- Flash Message --}}
            @if(session('success') || session('error'))
            <div class="px-6 pt-4 flex-shrink-0">
                @if(session('success'))
                    <div class="flash-message bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="flash-message bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif
            </div>
            @endif

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto content-scroll p-6 min-h-0">
                @yield('content')
            </main>

        </div>
    </div>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- MODAL RIWAYAT NOTIFIKASI                    --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div id="modal-notifikasi"
        class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4 modal-backdrop">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-xl max-h-[85vh] flex flex-col modal-content">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b dark:border-gray-700 flex-shrink-0">
                <div>
                    <h4 class="font-semibold text-gray-800 dark:text-white">Riwayat Notifikasi</h4>
                    <p class="text-xs text-gray-400 mt-0.5">Semua disposisi yang diterima</p>
                </div>
                <button onclick="tutupRiwayatNotif()"
                        class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-400 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Tab --}}
            <div class="flex border-b dark:border-gray-700 flex-shrink-0">
                <button onclick="switchTab('belum')"
                        id="tab-belum"
                        class="tab-btn flex-1 py-3 text-sm font-medium text-blue-600 border-b-2 border-blue-600 transition">
                    Belum Dibaca
                    <span id="tab-belum-count"
                        class="ml-1.5 bg-blue-100 text-blue-600 text-xs px-1.5 py-0.5 rounded-full"></span>
                </button>
                <button onclick="switchTab('diproses')"
                        id="tab-diproses"
                        class="tab-btn flex-1 py-3 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 border-b-2 border-transparent transition">
                    Diproses
                    <span id="tab-diproses-count"
                        class="ml-1.5 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs px-1.5 py-0.5 rounded-full"></span>
                </button>
                <button onclick="switchTab('selesai')"
                        id="tab-selesai"
                        class="tab-btn flex-1 py-3 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 border-b-2 border-transparent transition">
                    Selesai
                    <span id="tab-selesai-count"
                        class="ml-1.5 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs px-1.5 py-0.5 rounded-full"></span>
                </button>
            </div>

            {{-- Loading --}}
            <div id="notif-modal-loading" class="flex-1 flex items-center justify-center py-12">
                <div class="text-center text-gray-400">
                    <div class="animate-spin w-7 h-7 border-2 border-blue-500 border-t-transparent rounded-full mx-auto mb-3"></div>
                    <p class="text-sm">Memuat riwayat...</p>
                </div>
            </div>

            {{-- Konten Tab --}}
            <div id="notif-modal-content" class="hidden flex-1 overflow-y-auto">

                {{-- Tab: Belum Dibaca --}}
                <div id="panel-belum" class="tab-panel">
                    <div id="list-belum"></div>
                </div>

                {{-- Tab: Diproses --}}
                <div id="panel-diproses" class="tab-panel hidden">
                    <div id="list-diproses"></div>
                </div>

                {{-- Tab: Selesai --}}
                <div id="panel-selesai" class="tab-panel hidden">
                    <div id="list-selesai"></div>
                </div>

            </div>

        </div>
    </div>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- MODAL KONFIRMASI GLOBAL                     --}}
    {{-- ═══════════════════════════════════════════ --}}
        <div id="modal-konfirmasi"
            class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-[100] p-4 modal-backdrop">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md modal-content">

                {{-- Header dengan ikon --}}
                <div class="px-6 pt-6 pb-2 text-center">
                    <div id="konfirm-icon-wrap" class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-4">
                        {{-- Icon di-inject via JS --}}
                    </div>
                    <h3 id="konfirm-judul" class="text-base font-semibold text-gray-800 dark:text-white mb-1"></h3>
                    <p id="konfirm-pesan" class="text-sm text-gray-500 dark:text-gray-400"></p>
                    <p id="konfirm-detail" class="hidden text-xs text-gray-400 dark:text-gray-500 mt-2 italic"></p>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 mt-2 flex gap-2 border-t dark:border-gray-700">
                    <button onclick="tutupKonfirmasi()" type="button"
                            class="flex-1 text-sm font-medium bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white text-gray-700 px-4 py-2.5 rounded-lg transition">
                        Batal
                    </button>
                    <button id="konfirm-ok" type="button"
                            class="flex-1 text-sm font-medium text-white px-4 py-2.5 rounded-lg transition">
                        Konfirmasi
                    </button>
                </div>

            </div>
        </div>
        

        {{-- Script Konfirmasi --}}
        <script>
            const KONFIRM_CONFIG = {
                simpan: {
                    iconBg: 'bg-blue-100 dark:bg-blue-900/40',
                    iconColor: 'text-blue-600',
                    btnColor: 'bg-blue-600 hover:bg-blue-700',
                    icon: `<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>`,
                },
                edit: {
                    iconBg: 'bg-yellow-100 dark:bg-yellow-900/40',
                    iconColor: 'text-yellow-600',
                    btnColor: 'bg-yellow-500 hover:bg-yellow-600',
                    icon: `<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>`,
                },
                hapus: {
                    iconBg: 'bg-red-100 dark:bg-red-900/40',
                    iconColor: 'text-red-600',
                    btnColor: 'bg-red-600 hover:bg-red-700',
                    icon: `<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>`,
                },
            };

            let onKonfirmOk = null;

            function tampilKonfirmasi({ tipe = 'simpan', judul, pesan, detail = null, labelOk = 'Konfirmasi', onOk }) {
                const cfg     = KONFIRM_CONFIG[tipe] || KONFIRM_CONFIG.simpan;
                const modal   = document.getElementById('modal-konfirmasi');
                const iconWrap = document.getElementById('konfirm-icon-wrap');
                const btnOk   = document.getElementById('konfirm-ok');

                // Reset class & set baru
                iconWrap.className = 'w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-4 ' + cfg.iconBg + ' ' + cfg.iconColor;
                iconWrap.innerHTML = cfg.icon;

                btnOk.className = 'flex-1 text-sm font-medium text-white px-4 py-2.5 rounded-lg transition ' + cfg.btnColor;
                btnOk.textContent = labelOk;

                document.getElementById('konfirm-judul').textContent = judul;
                document.getElementById('konfirm-pesan').textContent = pesan;

                const detailEl = document.getElementById('konfirm-detail');
                if (detail) {
                    detailEl.textContent = detail;
                    detailEl.classList.remove('hidden');
                } else {
                    detailEl.classList.add('hidden');
                }

                onKonfirmOk = onOk;
                modal.classList.remove('hidden');
            }

            function tutupKonfirmasi() {
                document.getElementById('modal-konfirmasi').classList.add('hidden');
                onKonfirmOk = null;
            }

            document.getElementById('konfirm-ok').addEventListener('click', function() {
                if (typeof onKonfirmOk === 'function') {
                    const cb = onKonfirmOk;
                    tutupKonfirmasi();
                    cb();
                }
            });

            // Tutup klik backdrop
            document.getElementById('modal-konfirmasi').addEventListener('click', function(e) {
                if (e.target === this) tutupKonfirmasi();
            });

            // ── Auto-intercept semua form dengan attribute data-konfirmasi ──
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('form[data-konfirmasi]').forEach(function(form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        tampilKonfirmasi({
                            tipe:    form.dataset.tipe    || 'simpan',
                            judul:   form.dataset.judul   || 'Konfirmasi',
                            pesan:   form.dataset.pesan   || 'Apakah Anda yakin?',
                            detail:  form.dataset.detail  || null,
                            labelOk: form.dataset.labelOk || 'Ya, Lanjutkan',
                            onOk:    function() { form.submit(); },
                        });
                    });
                });
            });

            function toggleProfilMenu() {
                document.getElementById('profil-dropdown').classList.toggle('hidden');
            }

            document.addEventListener('click', function(e) {
                var wrapper = document.getElementById('profil-wrapper');
                if (wrapper && !wrapper.contains(e.target)) {
                    document.getElementById('profil-dropdown').classList.add('hidden');
                }
            });

            // ── Sambutan & Jam ────────────────────────────────────────
            function updateGreeting() {
                var hour = new Date().getHours();
                var greeting = '';
                var emoji = '';

                if (hour >= 5 && hour < 11) {
                    greeting = 'Selamat Pagi';
                    emoji = '🌅';
                } else if (hour >= 11 && hour < 15) {
                    greeting = 'Selamat Siang';
                    emoji = '☀️';
                } else if (hour >= 15 && hour < 18) {
                    greeting = 'Selamat Sore';
                    emoji = '🌇';
                } else {
                    greeting = 'Selamat Malam';
                    emoji = '🌙';
                }

                var el = document.getElementById('greeting-text');
                if (el) el.textContent = emoji + ' ' + greeting + '!';
            }

            function updateClock() {
                var now = new Date();
                var h = String(now.getHours()).padStart(2, '0');
                var m = String(now.getMinutes()).padStart(2, '0');
                var s = String(now.getSeconds()).padStart(2, '0');
                var el = document.getElementById('live-clock');
                if (el) el.textContent = h + ':' + m + ':' + s;
            }

            updateGreeting();
            updateClock();
            setInterval(updateClock, 1000);
            setInterval(updateGreeting, 60000);
            function konfirmasiLogout() {
                tampilKonfirmasi({
                    tipe: 'hapus',
                    judul: 'Keluar dari Sistem?',
                    pesan: 'Anda akan keluar dari akun dan harus login kembali.',
                    labelOk: 'Ya, Keluar',
                    onOk: function() {
                        document.getElementById('logout-form').submit();
                    }
                });
            }
            // Auto dismiss flash setelah 5 detik
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.flash-message').forEach(function(el) {
                    setTimeout(function() {
                        el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                        el.style.opacity = '0';
                        el.style.transform = 'translateX(20px)';
                        setTimeout(function() { el.remove(); }, 500);
                    }, 5000);
                });
            });
        </script>
</body>
</html>