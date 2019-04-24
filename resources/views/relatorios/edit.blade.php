@extends('layouts.app')
@section('title', '| Analisar Relatorio')
@section('content')
<div class='col-lg-4 col-lg-offset-4' style='border=1'>
    <h1><i class='fa fa-pencil'></i> Analisar Relatorio: {{$relatorio->lotacao->nome}} - 
                                    {{App\Http\Controllers\UtilController::converteDataEmTexto($relatorio->carga->data_extracao)}}</h1>
    <hr>
    {{ Form::model($relatorio, array('route' => array('relatorios.update', $relatorio->id), 'method' => 'PUT')) }}
    <div class="form-group" style="width: 100%;">
        {!! Form::label('analise','Análise Crítica') !!}
        {{ Form::textarea('analise', $relatorio->analise, ['class' => 'form-control','rows' => 10, 'cols' => 20]) }}
        {!! Form::label('acoes','Acoes para melhoria') !!}
        {{ Form::textarea('acoes', $relatorio->acoes, ['class' => 'form-control','rows' => 10, 'cols' => 20]) }}
    <br>
    </div>
    <br>
    {{ Form::submit('Salvar', array('class' => 'btn btn-primary')) }}
    {{ Form::close() }}    
</div>
<div class='row'>
        <div class="col-sm-4" style="width: 100%;">
            <br><br>
            <?php print $relatorio->conteudo;?>
        </div>
    </div>

@endsection