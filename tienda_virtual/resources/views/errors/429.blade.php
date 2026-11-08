@extends('errors.layout')

@section('titulo', 'Demasiados intentos')
@section('codigo', '429')
@section('encabezado', 'Demasiados intentos')
@section('mensaje', 'Hiciste muchas solicitudes en poco tiempo. Esperá un minuto y volvé a intentar.')

@section('botones')
    <button type="button" onclick="window.location.reload()" class="boton-principal inline-flex items-center rounded-full px-7 py-3.5 text-[10px] font-bold tracking-widest uppercase">Reintentar</button>
    <a href="/" class="boton-borde inline-flex items-center rounded-full px-7 py-3.5 text-[10px] font-bold tracking-widest uppercase">Volver al inicio</a>
@endsection
