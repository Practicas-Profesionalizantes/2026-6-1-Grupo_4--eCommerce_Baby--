@extends('errors.layout')

@section('titulo', 'Algo salió mal')
@section('codigo', '500')
@section('encabezado', 'Algo salió mal de nuestro lado')
@section('mensaje', 'No fue tu culpa. Probá de nuevo en unos minutos y, si sigue pasando, escribinos desde Soporte.')

@section('botones')
    <button type="button" onclick="window.location.reload()" class="boton-principal inline-flex items-center rounded-full px-7 py-3.5 text-[10px] font-bold tracking-widest uppercase">Reintentar</button>
    <a href="/" class="boton-borde inline-flex items-center rounded-full px-7 py-3.5 text-[10px] font-bold tracking-widest uppercase">Volver al inicio</a>
@endsection
