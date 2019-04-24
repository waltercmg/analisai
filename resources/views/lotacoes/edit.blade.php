@extends('layouts.app')
@section('title', '| Editar Lotação')
@section('content')
<div class='col-lg-4 col-lg-offset-4'>
    <h1><i class='fa fa-pencil'></i> Editar Lotação: {{$lotacao->nome}}</h1>
    <hr>
    {{ Form::model($lotacao, array('route' => array('lotacoes.update', $lotacao->id), 'method' => 'PUT')) }}
    <div class="form-group">
        {{ Form::label('nome', 'Nome') }}
        {{ Form::text('nome', null, array('class' => 'form-control')) }}
        <br>
        
        {{ Form::label('lider_id', 'Líder') }}
        {!! Form::select('lider_id',$users, $lotacao->lider_id, ['class' => 'form-control', 'autofocus', 'placeholder' => 'Selecione']) !!}
        <br>

    </div>
    <br>
    {{ Form::submit('Edit', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}    
</div>

@endsection