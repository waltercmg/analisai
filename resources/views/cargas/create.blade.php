@extends('layouts.app')

@section('title', '| Criar nova Lotacao')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

        <h1>Nova Carga</h1>
        <hr>
        {{ Form::open(array('id' => 'form','route' => 'cargas.store', 'enctype' => 'multipart/form-data')) }}

        <div class="form-group">
            {{ Form::label('data_extracao', 'Data da extração') }}
            {{ Form::date('data_extracao', null, array('class' => 'form-control')) }}
            <br>
            
            {{ Form::label('arquivo', 'Arquivo') }}
            {{ Form::file('arquivo', null, array('class' => 'form-control')) }}
            <br>

            <tbody border=1>
                @foreach ($lotacoes as $lotacao)
                <tr>
                    <td><?php print $lotacao;?></td>
                </tr>
                @endforeach
            </tbody>
            @if (count($lotacoes)>0)
                {{ Form::submit('Carregar', array('class' => 'btn btn-success btn-lg btn-block')) }}
            @endif
            {{ Form::close() }}
        </div>
        </div>
    </div>


<script type="text/javascript">
    document.getElementById("arquivo").onchange = function() {
        document.getElementById("form").action="{{ url('/cargas/avaliar_arquivo') }}";
        document.getElementById("form").submit();
    };
    
    
</script>
@endsection