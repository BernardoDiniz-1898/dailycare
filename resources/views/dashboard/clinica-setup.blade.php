@extends('layouts.app')

@section('titulo', 'Painel da Clinica')

@section('conteudo')
<div class="max-w-lg mx-auto text-center py-16">
    <h1 class="text-3xl font-bold mb-4">Complete o Perfil da Clinica</h1>
    <p class="text-gray-600 mb-8">Para comecar a receber agendamentos, cadastre as informacoes da sua clinica.</p>
    <a href="{{ route('clinica.perfil.create') }}" class="bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 inline-block">
        Cadastrar Clinica
    </a>
</div>
@endsection
