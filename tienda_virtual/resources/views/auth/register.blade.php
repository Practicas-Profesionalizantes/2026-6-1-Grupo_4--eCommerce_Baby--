@extends('layouts.app')

@section('titulo', 'Crear cuenta')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center p-6 relative">
    <div class="relative w-full max-w-md bg-white/90 backdrop-blur-sm border border-[#f0dfc8] rounded-[2.5rem] p-8 sm:p-10 shadow-[0_20px_50px_rgba(0,0,0,0.05)]">
        
        <div class="text-center mb-8">
            <h2 class="font-serif text-3xl tracking-wide text-[#4c3300]">Creá tu cuenta</h2>
            <p class="text-[10px] font-bold tracking-widest text-[#d98c8c] uppercase mt-2">Sumate a Kiddy Shop</p>
        </div>

        <form method="POST" action="{{ route('register.post') }}" class="flex flex-col gap-4">
            @csrf

            <div class="flex flex-col gap-1.5">
                <label for="name" class="text-[10px] font-bold tracking-widest text-[#7a5c3e] uppercase pl-2">Nombre completo</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                    class="w-full border border-[#f0dfc8] bg-[#faf0e6] rounded-full px-5 py-3 text-sm text-[#4c3300] focus:outline-none focus:border-[#d98c8c] focus:ring-2 focus:ring-[#d98c8c]/20 transition-all">
                @error('name') <span class="text-xs text-red-500 pl-2">{{ $message }}</span> @enderror
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="email" class="text-[10px] font-bold tracking-widest text-[#7a5c3e] uppercase pl-2">Correo electrónico</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    class="w-full border border-[#f0dfc8] bg-[#faf0e6] rounded-full px-5 py-3 text-sm text-[#4c3300] focus:outline-none focus:border-[#d98c8c] focus:ring-2 focus:ring-[#d98c8c]/20 transition-all">
                @error('email') <span class="text-xs text-red-500 pl-2">{{ $message }}</span> @enderror
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="password" class="text-[10px] font-bold tracking-widest text-[#7a5c3e] uppercase pl-2">Contraseña</label>
                <div class="relative">
                    <input id="password" type="password" name="password" required style="padding-right: 3rem;"
                        class="w-full border border-[#f0dfc8] bg-[#faf0e6] rounded-full px-5 py-3 text-sm text-[#4c3300] focus:outline-none focus:border-[#d98c8c] focus:ring-2 focus:ring-[#d98c8c]/20 transition-all">
                    <button type="button" class="kiddy-ojo" data-ver-clave="password" aria-label="Mostrar contraseña" aria-pressed="false">
                        <svg class="ojo-abierto" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <svg class="ojo-cerrado" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                    </button>
                </div>
                @error('password') <span class="text-xs text-red-500 pl-2">{{ $message }}</span> @enderror
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="password_confirmation" class="text-[10px] font-bold tracking-widest text-[#7a5c3e] uppercase pl-2">Confirmar contraseña</label>
                <div class="relative">
                    <input id="password_confirmation" type="password" name="password_confirmation" required style="padding-right: 3rem;"
                        class="w-full border border-[#f0dfc8] bg-[#faf0e6] rounded-full px-5 py-3 text-sm text-[#4c3300] focus:outline-none focus:border-[#d98c8c] focus:ring-2 focus:ring-[#d98c8c]/20 transition-all">
                    <button type="button" class="kiddy-ojo" data-ver-clave="password_confirmation" aria-label="Mostrar contraseña" aria-pressed="false">
                        <svg class="ojo-abierto" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <svg class="ojo-cerrado" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full bg-[#4c3300] hover:bg-[#3a2600] text-[#faf0e6] text-xs font-bold tracking-widest uppercase px-8 py-4 rounded-full transition-all duration-300 mt-4 active:scale-95 shadow-lg hover:shadow-xl">
                Crear mi cuenta
            </button>

            <div class="text-center mt-2">
                <a href="{{ route('login') }}" class="text-xs text-[#7a5c3e] hover:text-[#d98c8c] transition-colors">
                    ¿Ya tenés cuenta? <span class="font-bold underline underline-offset-4">Iniciá sesión</span>
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
