@extends('layouts.app')
@section('content')

<div class="col-lg-10 col-lg-offset-1">
    <h1><i class="fa fa-cubes"></i>Cargas <a href="{{ route('users.index') }}" class="btn btn-default pull-right">Roles</a>
    <a href="{{ route('users.index') }}" class="btn btn-default pull-right">Permissions</a></h1>
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>Código</th>
                    <th>Usuário</th>
                    <th>Data da Extração</th>
                    <th>Data da Carga</th>
                    <th>Operações</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($cargas as $carga)
                <tr>

                    <td>{{ $carga->id }}</td>
                    <td>{{ $carga->usuario->name }}</td>
                    <td>{{ App\Http\Controllers\UtilController::converteDataEmTexto($carga->data_extracao) }}</td>
                    <td>{{ $carga->created_at->format('F d, Y h:ia') }}</td>
                    <td>
                    
                    {!! Form::open(['method' => 'DELETE', 'route' => ['cargas.destroy', $carga->id] ]) !!}
                    <a href="{{ route('cargas.edit', $carga->id) }}" class="btn btn-info pull-left" style="margin-right: 3px;">Editar</a>
                    {!! Form::submit('Deletar', ['class' => 'btn btn-danger pull-left']) !!}
                    {!! Form::close() !!}

                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    <a href="{{ route('cargas.create') }}" class="btn btn-success">Nova Carga</a>

</div>
@endsection