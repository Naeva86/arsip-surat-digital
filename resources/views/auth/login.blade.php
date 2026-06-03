<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip Surat Digital PERUMDA Tirta Danu Arta</title>
    <link rel="icon" type="image/png" href="{{ asset('images/database.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        @keyframes blob {
            0%, 100% { border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; }
            25% { border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%; }
            50% { border-radius: 50% 60% 30% 60% / 30% 50% 70% 50%; }
            75% { border-radius: 60% 30% 50% 40% / 60% 70% 30% 50%; }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            33% { transform: translateY(-15px); }
            66% { transform: translateY(5px); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        .dark-gradient {
            background: linear-gradient(-45deg, #0a0e27, #121640, #1a1054, #0d1b3e, #161b4a);
            background-size: 400% 400%;
            animation: gradientShift 20s ease infinite;
        }
        .blob-1 {
            animation: blob 8s ease-in-out infinite, float 12s ease-in-out infinite;
        }
        .blob-2 {
            animation: blob 10s ease-in-out infinite reverse, float 15s ease-in-out infinite reverse;
        }
        .blob-3 {
            animation: blob 12s ease-in-out infinite, float 18s ease-in-out infinite;
            animation-delay: -4s;
        }
        .left-animate { animation: slideInLeft 0.8s ease-out; }
        .card-animate { animation: scaleIn 0.5s ease-out 0.3s both; }
        .fade-up { animation: fadeInUp 0.5s ease-out both; }
        .fade-up-1 { animation-delay: 0.4s; }
        .fade-up-2 { animation-delay: 0.5s; }
        .fade-up-3 { animation-delay: 0.6s; }
        .fade-up-4 { animation-delay: 0.7s; }

        .bg-slideshow .slide {
            position: absolute; inset: 0;
            background-size: cover; background-position: center;
            opacity: 0; transition: opacity 2s ease-in-out;
        }
        .bg-slideshow .slide.active { opacity: 0.08; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes scaleIn { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }
        @keyframes popupShrink { from { width: 100%; } to { width: 0%; } }
    </style>
</head>
<body class="min-h-screen flex bg-[#0a0e27]">

    {{-- ═══════════════════════════════════════ --}}
    {{-- KIRI: Dark Background + Welcome         --}}
    {{-- ═══════════════════════════════════════ --}}
    <div class="hidden lg:flex lg:w-1/2 xl:w-[125%] relative overflow-hidden dark-gradient left-animate">

        {{-- Slideshow --}}
        <div class="bg-slideshow absolute inset-0">
            <div class="slide active" style="background-image: url('{{ asset('images/bg-login-1.jpg') }}')"></div>
            <div class="slide" style="background-image: url('{{ asset('images/bg-login-2.jpg') }}')"></div>
            <div class="slide" style="background-image: url('{{ asset('images/bg-login-3.jpg') }}')"></div>
        </div>

        {{-- Blobs --}}
        <div class="absolute inset-0 overflow-hidden">
            <div class="blob-1 absolute w-[500px] h-[500px] bg-blue-600/15 blur-[100px] top-1/4 -left-20"></div>
            <div class="blob-2 absolute w-[400px] h-[400px] bg-purple-600/20 blur-[100px] bottom-1/4 right-0"></div>
            <div class="blob-3 absolute w-[350px] h-[350px] bg-cyan-500/10 blur-[80px] top-10 right-1/3"></div>
        </div>

        {{-- Pattern --}}
        <div class="absolute inset-0 opacity-[0.02]"
             style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')"></div>

        {{-- Content --}}
        <div class="relative z-10 flex flex-col justify-between p-10 xl:p-14 w-full">

            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo-perumda.png') }}" alt="Logo" class="h-10 w-auto drop-shadow-lg">
                <div>
                    <p class="text-white/80 font-bold text-sm tracking-wide">PERUMDA</p>
                    <p class="text-blue-300/50 text-xs">TIRTA DANU ARTA</p>
                </div>
            </div>

            <div class="max-w-md">
                <h1 class="text-5xl xl:text-6xl font-bold text-white leading-tight mb-4">
                    SELAMAT<br>DATANG
                </h1>
                <p class="text-blue-200/50 text-sm leading-relaxed mb-8">
                    Sistem Informasi Arsip Surat Digital dengan Disposisi Berjenjang untuk PERUMDA Air Minum Tirta Danu Arta Kabupaten Bangli.
                </p>
            </div>

            <p class="text-white/20 text-xs">&copy; {{ date('Y') }} PERUMDA Air Minum Tirta Danu Arta, Kabupaten Bangli, Bali</p>
        </div>
    </div>

    {{-- ═══════════════════════════════════════ --}}
    {{-- KANAN: Form Login (Batik Air + Clean)   --}}
    {{-- ═══════════════════════════════════════ --}}
    {{-- ═══════════════════════════════════════ --}}
    {{-- KANAN: Form Login (Batik Biru Safir)     --}}
    {{-- ═══════════════════════════════════════ --}}
    <div class="w-full lg:w-1/2 xl:w-[45%] flex items-center justify-center bg-[#f4f7fc] relative overflow-hidden"
         style="background-image: url('data:image/svg+xml,%3Csvg xmlns=&quot;http://www.w3.org/2000/svg&quot; width=&quot;80&quot; height=&quot;80&quot; viewBox=&quot;0 0 80 80&quot;%3E%3Cg fill=&quot;%23203a8c&quot; fill-opacity=&quot;0.05&quot;%3E%3Cpath d=&quot;M0 0c10 0 15 5 15 15s-5 15-15 15V0zm80 0c-10 0-15 5-15 15s5 15 15 15V0zM0 80c10 0 15-5 15-15s-5-15-15-15v15zm80 0c-10 0-15-5-15-15s5-15 15-15v15zM40 40c0-10-5-15-15-15s-15 5-15 15 5 15 15 15 15-5 15-15zm0 0c0 10 5 15 15 15s15-5 15-15-5-15-15-15-15 5-15 15z&quot;/%3E%3C/g%3E%3C/svg%3E');">

        {{-- Mobile blobs (diubah ke biru safir tipis) --}}
        <div class="absolute inset-0 overflow-hidden lg:hidden">
            <div class="blob-1 absolute w-80 h-80 bg-[#203a8c]/5 blur-[80px] top-0 -left-20"></div>
        </div>

        <div class="relative z-10 w-full max-w-md px-6 py-8 card-animate">
            
            {{-- Card Putih Utama --}}
            <div class="bg-white rounded-2xl shadow-xl shadow-[#203a8c]/5 p-8 border border-gray-100/80">
                
                {{-- Logo Aplikasi --}}
                <div class="flex justify-center mb-4">
                    <div class="p-3 bg-white rounded-2xl shadow-md border border-gray-100">
                        <img src="{{ asset('images/database.png') }}" alt="Logo" class="h-12 w-12 object-contain">
                    </div>
                </div>

                {{-- Teks Penyambut --}}
                <div class="text-center mb-8 fade-up fade-up-1">
                    <h2 class="text-2xl font-bold text-[#203a8c] tracking-wide">ARSIP SURAT DIGITAL</h2>
                    <div class="text-center text-sm text-gray-400 mt-2 fade-up fade-up-4">
                        Masukkan Nama, NIP, atau Email dan Password
                    </div>
                </div>

                {{-- Notifikasi Error --}}
                @if($errors->any())
                <div id="login-toast" class="fixed top-6 right-6 z-50 max-w-sm" style="animation: slideInToast 0.5s ease-out;">
                    <div class="bg-white rounded-xl shadow-2xl border border-red-200 overflow-hidden">
                        <div class="flex items-start gap-3 p-4">
                            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800">Login Gagal</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $errors->first() }}</p>
                            </div>
                            <button onclick="document.getElementById('login-toast').remove()"
                                    class="text-gray-400 hover:text-gray-600 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        <div class="h-1 bg-red-500 toast-progress"></div>
                    </div>
                </div>
                @endif

                {{-- Form Elemen --}}
                <form method="POST" action="{{ route('login') }}" id="login-form" class="space-y-5">
                    @csrf

                    {{-- Input Email/Username --}}
                    <div class="fade-up fade-up-2">
                        <label class="block text-sm font-medium text-[#475569] mb-2">Nama / NIP / Email</label>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-[#203a8c]">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <input type="text" name="login" value="{{ old('login') }}" required autofocus
                                   placeholder="Masukkan email, NIP, atau nama"
                                   class="w-full border border-gray-200 rounded-xl pl-12 pr-4 py-3.5 text-gray-700 bg-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-[#203a8c] focus:border-transparent transition text-sm">
                        </div>
                    </div>

                    {{-- Input Password --}}
                    <div class="fade-up fade-up-3">
                        <label class="block text-sm font-medium text-[#475569] mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-[#203a8c]">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                            </div>
                            <input type="password" name="password" required id="password-input"
                                   placeholder="••••••••••••"
                                   class="w-full border border-gray-200 rounded-xl pl-12 pr-12 py-3.5 text-gray-700 bg-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-[#203a8c] focus:border-transparent transition text-sm">
                            
                            <button type="button" onclick="togglePassword()"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                                <svg id="eye-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg id="eye-closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center justify-between text-xs sm:text-sm fade-up fade-up-3 pt-1">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="remember"
                                   class="w-4 h-4 rounded border-gray-300 text-[#203a8c] focus:ring-[#203a8c] transition">
                            <span class="text-gray-500">Ingatkan saya</span>
                        </label>
                    </div>

                    {{-- Tombol Login --}}
                    <div class="fade-up fade-up-4 pt-4">
                        <button type="submit"
                                class="w-full bg-[#203a8c] hover:bg-[#162965] active:scale-[0.99] text-white text-base font-semibold py-3.5 rounded-xl transition-all duration-200 shadow-md shadow-[#203a8c]/20 flex items-center justify-center">
                            Login
                        </button>
                    </div>
                </form>

                {{-- Footer Copyright --}}
                <div class="text-center text-xs text-gray-400 mt-8 fade-up fade-up-4 leading-relaxed">
                    © 2026 PERUMDA Air Minum Tirta Danu Arta<br>Kabupaten Bangli, Bali
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════ --}}
    {{-- POPUP LOGIN RESULT                      --}}
    {{-- ═══════════════════════════════════════ --}}
    <div id="popup-login" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" style="animation: fadeIn 0.2s ease-out;">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm" style="animation: scaleIn 0.25s ease-out;">
            <div class="px-6 pt-8 pb-4 text-center">
                <div id="popup-icon" class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4"></div>
                <h3 id="popup-title" class="text-lg font-bold text-gray-800 mb-1"></h3>
                <p id="popup-message" class="text-sm text-gray-500"></p>
            </div>
            <div id="popup-progress-wrap" class="px-6 pb-6">
                <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                    <div id="popup-progress" class="h-full rounded-full transition-all" style="width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function togglePassword() {
        var input = document.getElementById('password-input');
        var eyeOpen = document.getElementById('eye-open');
        var eyeClosed = document.getElementById('eye-closed');
        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }

    var slides = document.querySelectorAll('.bg-slideshow .slide');
    var currentSlide = 0;
    if (slides.length > 1) {
        setInterval(function() {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add('active');
        }, 5000);
    }
    function showLoginPopup(type, title, message, duration, callback) {
    var popup    = document.getElementById('popup-login');
    var icon     = document.getElementById('popup-icon');
    var titleEl  = document.getElementById('popup-title');
    var msgEl    = document.getElementById('popup-message');
    var progress = document.getElementById('popup-progress');

    titleEl.textContent = title;
    msgEl.textContent   = message;

    if (type === 'success') {
        icon.className = 'w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 bg-green-100';
        icon.innerHTML = '<svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
        progress.className = 'h-full rounded-full';
        progress.style.background = '#22c55e';
    } else {
        icon.className = 'w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 bg-red-100';
        icon.innerHTML = '<svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
        progress.className = 'h-full rounded-full';
        progress.style.background = '#ef4444';
    }

    popup.classList.remove('hidden');

    // Animate progress bar
    progress.style.width = '100%';
    progress.style.transition = 'width ' + duration + 'ms linear';
    setTimeout(function() { progress.style.width = '0%'; }, 50);

    // Auto close
    setTimeout(function() {
        popup.style.transition = 'opacity 0.3s ease';
        popup.style.opacity = '0';
        setTimeout(function() {
            popup.classList.add('hidden');
            popup.style.opacity = '1';
            if (callback) callback();
        }, 300);
    }, duration);
    }

    // Login via AJAX
    document.getElementById('login-form').addEventListener('submit', function(e) {
        e.preventDefault();

        var form     = this;
        var formData = new FormData(form);
        var submitBtn = form.querySelector('button[type="submit"]');

        // Disable button
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            credentials: 'same-origin',
        })
        .then(function(response) {
            // Ubah respons ke format JSON terlebih dahulu
            return response.json().then(function(data) {
                // Jika status OK (200) -> Sukses
                if (response.ok) {
                    showLoginPopup('success', 'Login Berhasil!', 'Selamat datang, Anda akan dialihkan...', 2000, function() {
                        window.location.href = data.redirect || '{{ route("dashboard") }}';
                    });
                } 
                // Jika status Error (422) -> Login Gagal / Validasi / Nonaktif
                else if (response.status === 422) {
                    var msg = data.errors && data.errors.login ? data.errors.login[0] : 'Terjadi kesalahan pada input.';
                    showLoginPopup('error', 'Login Gagal!', msg, 3000, function() {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg> Login';
                    });
                } 
                // Jika status error lain (500, dll)
                else {
                    throw new Error('Server error');
                }
            });
        })
        .catch(function(err) {
            showLoginPopup('error', 'Terjadi Kesalahan', 'Tidak dapat terhubung ke server.', 3000, function() {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg> Login';
            });
        });
    });

    // Jika ada error dari server (non-AJAX redirect/fallback)
    @if($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        showLoginPopup('error', 'Login Gagal!', '{{ $errors->first() }}', 3000);
    });
    @endif
    </script>

</body>
</html>