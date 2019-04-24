@extends('layouts.app')
@section('content')

<div class="col-lg-10 col-lg-offset-1">
    <h1><i class="fa fa-users"></i> Lotações <a href="{{ route('users.index') }}" class="btn btn-default pull-right">Roles</a>
    <a href="{{ route('users.index') }}" class="btn btn-default pull-right">Permissions</a></h1>
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Líder</th>
                    <th>Data Criação</th>
                    <th>Operações</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($lotacoes as $lotacao)
                <tr>

                    <td>{{ $lotacao->nome }}</td>
                    <td>{{ $lotacao->lider->name }}</td>
                    <td>{{ $lotacao->created_at->format('F d, Y h:ia') }}</td>
                    <td>
                    <a href="{{ route('lotacoes.edit', $lotacao->id) }}" class="btn btn-info pull-left" style="margin-right: 3px;">Editar</a>

                    {!! Form::open(['method' => 'DELETE', 'route' => ['lotacoes.destroy', $lotacao->id] ]) !!}
                    {!! Form::submit('Deletar', ['class' => 'btn btn-danger']) !!}
                    {!! Form::close() !!}

                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    <a href="{{ route('lotacoes.create') }}" class="btn btn-success">Adicionar Lotação</a>

</div>
@endsection