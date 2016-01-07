@extends('app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            Статьи
            <a class="btn btn-primary btn-xs" href="/articles/save">новая статья</a>
        </div>

        <div class="panel-body">

            {!! $articles->render() !!}
            <table class="table">
                <thead>
                    <tr>
                        <th>Наименование</th>
                        <th>Владелец</th>
                        <th>Сортировка</th>
                        <th>Управление</th>
                    </tr>
                </thead>
                @foreach ($articles as $article)
                <tr <?php if ($article->trashed()) { ?> class="text-muted" <?php } ?>>
                    <td><a  href="{{action('Articles@getShow',$article->id) }}">{{ $article->title }}</a></td>
                    <td>{{ $article->user->name }}</td>
                    <td>
                        {{ $article->order }}
                        <div class="btn btn-default btn-xs senddata" data-link="/articles/up/{{$article->id}}">Вверх</div>
                        <div class="btn btn-default btn-xs senddata" data-link="/articles/down/{{$article->id}}">Вниз</div>
                    </td>
                    <td>
                        @if ($article->trashed())
                            <div class="btn btn-default btn-xs senddata" href="/articles/restore/{{$article->id}}">Восстановить статью</div>
                            <div class="btn btn-default btn-xs senddata really" data-link="/articles/force-delete/{{$article->id}}">Удалить полностью</div>
                        @else
                            <a class="btn btn-default btn-xs" href="/articles/save/{{$article->id}}">Редактировать</a>
                            <div class="btn btn-default btn-xs senddata really" data-link="/articles/delete/{{$article->id}}">Удалить</div>
                        @endif
                    </td>
                </tr>
                @endforeach
            </table>
            {!! $articles->render() !!}
        </div>
    </div>
		
@endsection
