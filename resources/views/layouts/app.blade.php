<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Daily Monitoring GS MTBU') | General Services MTBU</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }
        .font-mono-num {
            font-family: 'JetBrains Mono', monospace;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #0f172a;
        }
        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }
        /* Glassmorphism gradient accents */
        .glass-card {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glass-card:hover {
            border-color: rgba(59, 130, 246, 0.3);
        }
        .scrollbar-none::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-none {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-[#0b1120] text-slate-100 min-h-screen xl:h-screen xl:overflow-hidden selection:bg-blue-600 selection:text-white flex flex-col">
    <!-- Notifications / Flash messages -->
    @if(session('success'))
        <div id="flash-toast" class="fixed top-5 right-5 z-50 flex items-center gap-3 bg-emerald-500/20 border border-emerald-500/40 text-emerald-200 px-4 py-3 rounded-xl shadow-2xl backdrop-blur-md transition-all duration-300">
            <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
            <button onclick="document.getElementById('flash-toast').remove()" class="text-emerald-400 hover:text-white ml-2 text-lg leading-none">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div id="flash-toast-error" class="fixed top-5 right-5 z-50 flex items-center gap-3 bg-rose-500/20 border border-rose-500/40 text-rose-200 px-4 py-3 rounded-xl shadow-2xl backdrop-blur-md transition-all duration-300">
            <svg class="w-5 h-5 text-rose-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <div class="text-sm">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
            <button onclick="document.getElementById('flash-toast-error').remove()" class="text-rose-400 hover:text-white ml-2 text-lg leading-none">&times;</button>
        </div>
    @endif

    @yield('content')

    <script>
        // Auto-dismiss alerts after 5 seconds
        setTimeout(() => {
            const toast = document.getElementById('flash-toast');
            if (toast) toast.style.opacity = '0', setTimeout(() => toast.remove(), 300);
            const toastErr = document.getElementById('flash-toast-error');
            if (toastErr) toastErr.style.opacity = '0', setTimeout(() => toastErr.remove(), 300);
        }, 5000);
    </script>
    @stack('scripts')
</body>
</html>
