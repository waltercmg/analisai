@extends('layouts.app')

@section('title', '| Criar nova Lotacao')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

        <h1>Nova Lotacao</h1>
        <hr>
        {{ Form::open(array('route' => 'lotacoes.store')) }}

        <div class="form-group">
            {{ Form::label('nome', 'Nome') }}
            {{ Form::text('nome', null, array('class' => 'form-control')) }}
            <br>

            {{ Form::label('lider_id', 'Lider') }}
            {!! Form::select('lider_id',$users, null, ['class' => 'form-control', 'autofocus', 'placeholder' => 'Selecione']) !!}
            <br>

            {{ Form::submit('Criar Lotacao', array('class' => 'btn btn-success btn-lg btn-block')) }}
            {{ Form::close() }}
        </div>
        </div>
    </div>

@endsection