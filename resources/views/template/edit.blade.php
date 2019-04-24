@extends('layouts.app')
@section('title', '| Atualizar Template')
@section('content')
<div class='col-lg-4 col-lg-offset-4' style='border=1'>
    <h1><i class='fa fa-pencil'></i>Atualizar Template</h1>
    <hr>
    {{ Form::open(array('id'=>'form','url' => '/template/update', 'method' => 'post')) }}
    <div class="form-group" style="width: 100%;">
        {!! Form::label('conteudo','Template') !!}
        {{ Form::textarea('conteudo', $conteudo, ['class' => 'form-control','rows' => 20, 'cols' => 50]) }}
    <br>
    </div>
    <br>
    {{ Form::button('Visualizar', array('class' => 'btn btn-primary', 'onclick'=>'visualizar()')) }}
    {{ Form::submit('Salvar', array('class' => 'btn btn-primary')) }}
    {{ Form::close() }}    
</div>
<script type="text/javascript">
    function visualizar(){
        //alert(document.getElementById('conteudo').value);
        var myWindow = window.open("", "MsgWindow", "width=800,height=800,scrollbars=yes");
        myWindow.document.write(document.getElementById('conteudo').value);
    }
</script>

@endsection