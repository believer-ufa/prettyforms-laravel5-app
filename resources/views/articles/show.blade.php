@extends('app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            {{ $article->title }}
            @if ($article->user_id == Auth::user()->id)
              <div style="margin-left: 5px;" class="btn btn-default btn-xs senddata really pull-right" data-link="/articles/delete/{{$article->id}}">Удалить</div>
              <a class="btn btn-default btn-xs pull-right" href="/articles/save/{{$article->id}}">Редактировать</a>
            @endif
        </div>

        <div class="panel-body">
            {!! $article->text !!}
            
            <small>
                {{ $article->user->name }}, {{ $article->created_at }}
            </small>
        </div>
    </div>
		
@endsection
