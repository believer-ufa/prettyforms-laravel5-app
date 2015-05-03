@extends('app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            Роли
            <a class="btn btn-primary btn-xs" href="/roles/save">новая роль</a>
        </div>

        <div class="panel-body">

            {!! $roles->render() !!}
            <table class="table">
                <thead>
                    <tr>
                        <th>Наименование</th>
                        <th>Пользователи</th>
                        <th>Управление</th>
                    </tr>
                </thead>
                @foreach ($roles as $role)
                <tr>
                    <td>{{ $role->name }}</td>
                    <td>
                        @forelse ($role->users()->get() as $person) 
                            <span class="label label-primary"> {{ $person->name }} </span>&nbsp;
                        @empty
                         Отсутствуют
                        @endforelse
                    </td>
                    <td>
                        <a class="btn btn-default btn-xs" href="/roles/save/{{$role->id}}">Редактировать</a>
                        <div class="btn btn-default btn-xs senddata really" data-link="/roles/delete/{{$role->id}}">Удалить</div>
                    </td>
                </tr>
                @endforeach
            </table>
            {!! $roles->render() !!}
        </div>
    </div>
		
@endsection
