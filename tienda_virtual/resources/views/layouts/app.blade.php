<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@hasSection('titulo')@yield('titulo') | Kiddy Shop @else Kiddy Shop — Alta Costura Infantil @endif</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">

    @include('tema')

    <script>
        if (localStorage.getItem('font') === 'serif') {
            document.documentElement.style.setProperty('--font-sans', "'Playfair Display', serif");
        }
        if (localStorage.getItem('fontSize')) {
            document.documentElement.style.fontSize = localStorage.getItem('fontSize') + 'px';
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes twinkle { 0%, 100% { opacity: 0.1; } 50% { opacity: 0.4; } }
        .animate-twinkle { animation: twinkle 5s ease-in-out infinite; }
    </style>
</head>
<body class="antialiased font-sans flex flex-col min-h-screen relative overflow-x-hidden" style="background-color: var(--bg-color); color: var(--text-main);">

    <div id="fondo-claro" class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-gradient-to-b from-[#fffbf7] to-[#fdf5ee]"></div>
        <div class="absolute inset-0 opacity-[0.08]" style="background-image: url('data:image/svg+xml,%3Csvg width=\'40\' height=\'40\' viewBox=\'0 0 40 40\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M20 2l2 6 6 2-6 2-2 6-2-6-6-2 6-2z\' fill=\'%234c3300\' fill-opacity=\'1\' fill-rule=\'evenodd\'/%3E%3C/svg%3E'); background-size: 50px 50px;"></div>
        <div class="absolute inset-0 overflow-hidden">
            <svg class="absolute top-[10%] left-[-5%] w-[300px] text-[#f2a6a6]/20 animate-float" fill="currentColor" viewBox="0 0 24 24"><path d="M17.5 19c2.5 0 4.5-2 4.5-4.5 0-2.3-1.7-4.2-4-4.5-.5-3.4-3.4-6-7-6-3.9 0-7 3.1-7 7 0 .2 0 .3.1.5C2.1 12.1 1 13.9 1 16c0 3.3 2.7 6 6 6h10.5z"/></svg>
            <svg class="absolute bottom-[10%] right-[-5%] w-[400px] text-[#faebd7] animate-float-delayed" fill="currentColor" viewBox="0 0 24 24"><path d="M17.5 19c2.5 0 4.5-2 4.5-4.5 0-2.3-1.7-4.2-4-4.5-.5-3.4-3.4-6-7-6-3.9 0-7 3.1-7 7 0 .2 0 .3.1.5C2.1 12.1 1 13.9 1 16c0 3.3 2.7 6 6 6h10.5z"/></svg>
        </div>
    </div>

    <div id="fondo-marron" class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0" style="background: linear-gradient(to bottom, #f1e3cd, #e2ccae);"></div>
        <div class="absolute inset-0" style="opacity: 0.07; background-image: url('data:image/svg+xml,%3Csvg width=\'40\' height=\'40\' viewBox=\'0 0 40 40\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M20 2l2 6 6 2-6 2-2 6-2-6-6-2 6-2z\' fill=\'%234c3300\' fill-opacity=\'1\' fill-rule=\'evenodd\'/%3E%3C/svg%3E'); background-size: 50px 50px;"></div>
        <div class="absolute inset-0 overflow-hidden">
            <svg class="absolute top-[10%] left-[-5%] w-[300px] animate-float" style="color: rgba(201, 154, 104, 0.35);" fill="currentColor" viewBox="0 0 24 24"><path d="M17.5 19c2.5 0 4.5-2 4.5-4.5 0-2.3-1.7-4.2-4-4.5-.5-3.4-3.4-6-7-6-3.9 0-7 3.1-7 7 0 .2 0 .3.1.5C2.1 12.1 1 13.9 1 16c0 3.3 2.7 6 6 6h10.5z"/></svg>
            <svg class="absolute bottom-[10%] right-[-5%] w-[400px] animate-float-delayed" style="color: rgba(247, 236, 220, 0.75);" fill="currentColor" viewBox="0 0 24 24"><path d="M17.5 19c2.5 0 4.5-2 4.5-4.5 0-2.3-1.7-4.2-4-4.5-.5-3.4-3.4-6-7-6-3.9 0-7 3.1-7 7 0 .2 0 .3.1.5C2.1 12.1 1 13.9 1 16c0 3.3 2.7 6 6 6h10.5z"/></svg>
        </div>
    </div>

    <div id="fondo-oscuro" class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-gradient-to-b from-[#050403] to-[#0a0807]"></div>
        <div class="absolute inset-0 opacity-[0.15]" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M30 5l2 8 8 2-8 2-2 8-2-8-8-2 8-2z\' fill=\'%23faf0e6\' fill-opacity=\'1\' fill-rule=\'evenodd\'/%3E%3C/svg%3E'); background-size: 80px 80px;"></div>
        <div class="absolute inset-0 overflow-hidden">
            <svg class="absolute top-[15%] right-[10%] w-[200px] text-[#4a3232]/40 animate-twinkle" fill="currentColor" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path></svg>
            <svg class="absolute bottom-[20%] left-[5%] w-[120px] text-[#3a2e28]/40 animate-twinkle" style="animation-delay: 2s;" fill="currentColor" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path></svg>
        </div>
    </div>

    <div class="relative z-10 flex flex-col min-h-screen">
        @include('header-footer')
        <main class="flex-grow">
            @yield('content')
        </main>
    </div>

    <style>
        @keyframes float {
            0% { transform: translateX(0px) translateY(0px); }
            50% { transform: translateX(30px) translateY(-10px); }
            100% { transform: translateX(0px) translateY(0px); }
        }
        .animate-float { animation: float 8s ease-in-out infinite; }
        .animate-float-delayed { animation: float 10s ease-in-out infinite reverse; }
    </style>
</body>
</html>