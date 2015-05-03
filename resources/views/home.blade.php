@extends('app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Главная страница</div>

        <div class="panel-body">
            @if (Auth::check())
              Добро пожаловать на тестовую платформу PrettyForms :)

              <br><br>
              <ul>
                  <li> <a href="/users">Управление пользователями</a> </li>
                  <li> <a href="/roles">Управление ролями</a> </li>
                  <li> <a href="/articles">Статьи</a> </li>
              </ul>
            @else
              Пожалуйста, авторизуйтесь перед началом работы
            @endif
        </div>
    </div>
@endsection
