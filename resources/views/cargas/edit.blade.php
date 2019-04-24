@extends('layouts.app')
@section('title', '| Editar Lotação')
@section('content')
<div class='col-lg-4 col-lg-offset-4'>
    <h1><i class='fa fa-pencil'></i> Edit: {{$carga->id}}</h1>
    <hr>
    {{ Form::model($carga, array('route' => array('cargas.update', $carga->id), 'method' => 'PUT')) }}
    <div class="form-group">
        {{ Form::label('data_extracao', 'Data da extração') }}
        {{ Form::date('data_extracao', App\Http\Controllers\UtilController::converteDataEmTexto($carga->data_extracao), array('class' => 'form-control')) }}
        <br>
    </div>
    <br>
    {{ Form::submit('Edit', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}    
</div>

@endsection