@extends('app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            Пользователи
            <a class="btn btn-primary btn-xs" href="/users/save">новый</a>
        </div>

        <div class="panel-body">

            {!! $users->render() !!}
            <table class="table">
                <thead>
                    <tr>
                        <th>Имя</th>
                        <th>E-Mail</th>
                        <th>Управление</th>
                    </tr>
                </thead>
                @foreach ($users as $user)
                <tr <?php if ($user->trashed()) { ?> class="text-muted" <?php } ?>>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if ($user->trashed())
                            <div class="btn btn-default btn-xs senddata" href="/users/restore/{{$user->id}}">Восстановить бедолагу</div>
                            <div class="btn btn-default btn-xs senddata really" data-link="/users/force-delete/{{$user->id}}">Удалить полностью</div>
                        @else
                            <a class="btn btn-default btn-xs" href="/users/save/{{$user->id}}">Редактировать</a>
                            <a class="btn btn-default btn-xs" href="/users/password/set/{{$user->id}}">Новый пароль</a>
                            <div class="btn btn-default btn-xs senddata" data-link="/users/delete/{{$user->id}}">Удалить</div>
                        @endif
                    </td>
                </tr>
                @endforeach
            </table>
            {!! $users->render() !!}
        </div>
    </div>
		
@endsection
