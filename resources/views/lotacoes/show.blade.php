@extends('layouts.app')
@section('title', '| Visualizar Lotação')
@section('content')

<div class="container">

    <h1>{{ $lotacao->nome }}</h1>
    <hr>
    <p class="lead">{{ $lotacao->lider->name }} </p>
    <hr>
    {!! Form::open(['method' => 'DELETE', 'route' => ['lotacoes.destroy', $lotacao->id] ]) !!}
    <a href="{{ url()->previous() }}" class="btn btn-primary">Voltar</a>
    <a href="{{ route('lotacoes.edit', $lotacao->id) }}" class="btn btn-info" role="button">Editar</a>
    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}

</div>
@endsection