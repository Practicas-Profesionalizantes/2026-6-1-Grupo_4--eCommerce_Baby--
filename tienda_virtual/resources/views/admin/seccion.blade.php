@extends('admin.layout')

@section('titulo', $titulo)

@section('contenido')
    <h1 class="font-serif text-4xl mb-4">{{ $titulo }}</h1>
    <p class="text-lg">{{ $texto }}</p>
@endsection