@extends('layouts.app')

@section('content')
<div class="py-16 px-6 sm:px-12">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row gap-8">

            <aside class="w-full md:w-1/3">
                <div class="bg-[#f2a6a6] rounded-[2.5rem] p-8 shadow-xl shadow-[#f2a6a6]/20 relative overflow-hidden">
                    <div class="flex flex-col items-center mt-2 mb-8 relative z-10">
                        <div class="w-24 h-24 rounded-full bg-white/40 flex items-center justify-center text-[#4c3300] mb-4 shadow-inner border-4 border-white/60">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                        </div>
                        <h2 class="text-lg font-bold text-[#4c3300] text-center">{{ Auth::user()->name ?? 'Invitado' }}</h2>
                    </div>

                    <nav class="flex flex-col gap-2 relative z-10">
                        <a href="{{ route('cuenta.index') }}" class="group relative flex items-center gap-3 px-5 py-4 text-[#4c3300]/80 rounded-full text-sm font-medium transition-all duration-300 hover:bg-white/60 hover:text-[#4c3300]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            <span>Volver a Mis Datos</span>
                        </a>
                        <a href="{{ route('cuenta.apariencia') }}" class="group relative flex items-center gap-3 px-5 py-4 bg-white text-[#4c3300] rounded-full text-sm font-bold shadow-md">
                            <span class="absolute -left-1 top-1/2 -translate-y-1/2 w-2 h-2 bg-[#f2a6a6] rounded-full animate-pulse"></span>
                            <svg class="w-4 h-4 text-[#d98c8c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                            <span>Apariencia</span>
                        </a>
                        <a href="{{ route('cuenta.tipografia') }}" class="group relative flex items-center gap-3 px-5 py-4 text-[#4c3300]/80 rounded-full text-sm font-medium transition-all duration-300 hover:bg-white/60 hover:text-[#4c3300]">
                            <svg class="w-4 h-4 transition-all duration-300 group-hover:scale-110 group-hover:text-[#d98c8c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                            <span>Tipografía</span>
                        </a>
                        <a href="{{ route('cuenta.soporte') }}" class="group relative flex items-center gap-3 px-5 py-4 text-[#4c3300]/80 rounded-full text-sm font-medium transition-all duration-300 hover:bg-white/60 hover:text-[#4c3300]">
                            <svg class="w-4 h-4 transition-all duration-300 group-hover:scale-110 group-hover:text-[#d98c8c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <span>Soporte</span>
                        </a>
                    </nav>
                </div>
            </aside>

            <main class="w-full md:w-2/3">
                <div class="bg-white rounded-[2.5rem] p-8 sm:p-10 shadow-xl shadow-[#4c3300]/5 border border-[#f0dfc8] relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-[#faebd7] via-[#f2a6a6] to-[#d98c8c]"></div>

                    <div class="flex items-center gap-4 mb-8 mt-2">
                        <a href="{{ route('cuenta.index') }}" class="w-8 h-8 bg-[#faf0e6] rounded-full flex items-center justify-center text-[#d98c8c] hover:bg-[#f2a6a6] hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </a>
                        <h3 class="font-serif text-2xl text-[#4c3300]">Apariencia</h3>
                    </div>

                    <div class="flex flex-col gap-6">
                        <h4 class="text-xs font-bold tracking-widest text-[#7a5c3e] uppercase">Tema</h4>

                        <div class="flex flex-col gap-4">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="tema" value="predeterminado" onchange="cambiarTema('predeterminado')" class="w-5 h-5 text-[#f2a6a6] focus:ring-[#f2a6a6] border-gray-300">
                                <span class="w-5 h-5 rounded-full border border-black/10 shrink-0" style="background: linear-gradient(135deg, #e7d3b8 50%, #c99a68 50%);"></span>
                                <span class="text-sm text-[#4c3300] group-hover:text-[#d98c8c] transition-colors">Predeterminado</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="tema" value="claro" onchange="cambiarTema('claro')" class="w-5 h-5 text-[#f2a6a6] focus:ring-[#f2a6a6] border-gray-300">
                                <span class="w-5 h-5 rounded-full border border-black/10 shrink-0" style="background: linear-gradient(135deg, #fffbf7 50%, #f2a6a6 50%);"></span>
                                <span class="text-sm text-[#4c3300] group-hover:text-[#d98c8c] transition-colors">Claro</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="tema" value="oscuro" onchange="cambiarTema('oscuro')" class="w-5 h-5 text-[#f2a6a6] focus:ring-[#f2a6a6] border-gray-300">
                                <span class="w-5 h-5 rounded-full border border-black/10 shrink-0" style="background: linear-gradient(135deg, #0a0807 50%, #e6a1a1 50%);"></span>
                                <span class="text-sm text-[#4c3300] group-hover:text-[#d98c8c] transition-colors">Oscuro</span>
                            </label>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

<script>
    function cambiarTema(tema) {
        if (window.aplicarTema) window.aplicarTema(tema);
    }
    document.addEventListener('DOMContentLoaded', () => {
        const actual = window.temaGuardado ? window.temaGuardado() : 'predeterminado';
        const radio = document.querySelector('input[name="tema"][value="' + actual + '"]');
        if (radio) radio.checked = true;
    });
</script>
@endsection