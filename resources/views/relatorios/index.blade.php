@extends('layouts.app')
@section('content')

<div class="col-lg-10 col-lg-offset-1">
    <h1><i class="fa fa-users"></i> Relatórios <a href="{{ route('users.index') }}" class="btn btn-default pull-right">Roles</a>
    <a href="{{ route('users.index') }}" class="btn btn-default pull-right">Permissions</a></h1>
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>Lotação</th>
                    <th>Carga</th>
                    <th>Data Extração</th>
                    <th>Operações</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($lotacoes as $lotacao)
                    @foreach ($lotacao->relatorios as $relatorio)
                    <tr>
                        <td>{{ $lotacao->nome }}</td>
                        <td>{{ $relatorio->carga_id }}</td>
                        <td>{{ App\Http\Controllers\UtilController::converteDataEmTexto($relatorio->carga->data_extracao) }}</td>
                        <td>
                        @if($relatorio->analise == '')
                            <a href="{{ route('relatorios.edit', $relatorio->id) }}" class="btn btn-info pull-left" style="margin-right: 3px;">Analisar</a>
                        @else
                            <a href="{{ route('relatorios.edit', $relatorio->id) }}" class="btn btn-success pull-left" style="margin-right: 3px;">Ver Analise</a>
                        @endif
                        </td>
                    </tr>
                    @endforeach
                @endforeach
            </tbody>

        </table>
    </div>
</div>
@endsection