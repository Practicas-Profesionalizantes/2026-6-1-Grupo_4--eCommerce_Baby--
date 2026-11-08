<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'Panel') | Kiddy Shop Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { background-color: #fdfaf7; background-image: radial-gradient(#ffe4e6 1px, transparent 1px); background-size: 20px 20px; }
    </style>
</head>
<body class="font-sans antialiased text-gray-800 flex min-h-screen">

    @include('ui-extras')
    
    <aside class="w-64 bg-pink-100 shadow-md flex flex-col justify-between hidden md:flex">
        <div>
            <div class="p-6 text-center border-b border-pink-200">
                <img src="/img/logo.png" alt="Kiddy Shop" class="h-12 mx-auto" onerror="this.style.display='none'">
                <h1 class="text-xl font-bold text-pink-900 mt-2">Kiddy Shop Admin</h1>
            </div>
            <nav class="p-4 space-y-2">
                <a href="/admin" class="block px-4 py-2 rounded-lg text-pink-900 hover:bg-pink-200">Inicio</a>
                <a href="/admin/productos" class="block px-4 py-2 rounded-lg text-pink-900 hover:bg-pink-200">Publicaciones</a>
                <a href="/admin/pedidos" class="block px-4 py-2 rounded-lg text-pink-900 hover:bg-pink-200 flex justify-between items-center">
                    Pedidos
                    <span id="badge-pedidos-menu" class="bg-pink-500 text-white text-xs font-bold px-2 py-1 rounded-full hidden">0</span>
                </a>
                <a href="/admin/problemas" class="block px-4 py-2 rounded-lg text-pink-900 hover:bg-pink-200 flex justify-between items-center">
                    Problemas del cliente
                    <span id="badge-reportes-menu" class="bg-pink-500 text-white text-xs font-bold px-2 py-1 rounded-full hidden">0</span>
                </a>
                <a href="/admin/facturas" class="block px-4 py-2 rounded-lg text-pink-900 hover:bg-pink-200 flex justify-between items-center">
                    Facturas
                    <span id="badge-facturas-menu" class="bg-pink-500 text-white text-xs font-bold px-2 py-1 rounded-full hidden">0</span>
                </a>
            </nav>
        </div>
        <div class="p-4 border-t border-pink-200 space-y-2">
            <a href="/" class="block px-4 py-2 text-sm text-pink-900 hover:underline">Volver a la tienda</a>
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-pink-900 hover:underline">Cerrar sesión</button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col">
        <header class="bg-white shadow-sm p-4 flex justify-between items-center md:hidden">
            <span class="font-bold text-pink-900">Kiddy Shop Admin</span>
            <a href="/admin/problemas" class="relative p-2 text-pink-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                <span id="badge-mobile" class="absolute top-0 right-0 bg-red-500 text-white text-[10px] font-bold px-1.5 rounded-full hidden">0</span>
            </a>
        </header>

        <main class="p-4 md:p-8 flex-1">
            @yield('contenido')
        </main>
    </div>

    <script>
        async function contar(url) {
            try {
                const respuesta = await fetch(url, { headers: { 'Accept': 'application/json' } });
                if (!respuesta.ok) return 0;
                const datos = await respuesta.json();
                return datos.total || 0;
            } catch (e) {
                return 0;
            }
        }

        function poner(id, cantidad) {
            const el = document.getElementById(id);
            if (!el) return;
            if (cantidad > 0) { el.textContent = cantidad; el.classList.remove('hidden'); }
            else { el.classList.add('hidden'); }
        }

        async function actualizarCampanita() {
            const [totalRep, totalFac, totalPed] = await Promise.all([
                contar('/api/admin/reportes/contador'),
                contar('/api/admin/facturas/contador'),
                contar('/api/admin/pedidos-aviso/contador')
            ]);

            poner('badge-reportes-menu', totalRep);
            poner('badge-facturas-menu', totalFac);
            poner('badge-pedidos-menu', totalPed);
            poner('badge-mobile', totalRep + totalPed);
        }

        window.actualizarCampanita = actualizarCampanita;
        document.addEventListener('DOMContentLoaded', actualizarCampanita);
    </script>
</body>
</html>
