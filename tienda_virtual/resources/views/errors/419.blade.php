@extends('errors.layout')

@section('titulo', 'La sesión venció')
@section('codigo', '419')
@section('encabezado', 'Tu sesión venció')
@section('mensaje', 'Pasó mucho tiempo sin actividad y, por seguridad, cerramos el formulario. Volvé a empezar y listo.')

@section('botones')
    <a href="/login" class="boton-principal inline-flex items-center rounded-full px-7 py-3.5 text-[10px] font-bold tracking-widest uppercase">Iniciar sesión</a>
    <a href="/" class="boton-borde inline-flex items-center rounded-full px-7 py-3.5 text-[10px] font-bold tracking-widest uppercase">Volver al inicio</a>
@endsection
