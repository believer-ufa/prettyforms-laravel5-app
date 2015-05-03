<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Laravel</title>

	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">

	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
    
    <style>
        h3 {
            margin: 30px 0;
            text-align: center;
        }
    </style>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">PrettyForms для Laravel 5</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/') }}">Главная</a></li>
                    @if (Auth::check())
                        <li><a href="{{ url('/articles/save') }}">Написать статью</a></li>
                    @endif
				</ul>

				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<li><a href="{{ url('/auth/login') }}">Авторизация</a></li>
						<li><a href="{{ url('/auth/register') }}">Регистрация</a></li>
					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('/auth/logout') }}">Выход</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>

	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                
                @if ($messages = pf_display_messages())
                    {!! $messages !!}
                @endif

                <?php
                $breadcrumbs_hidden = App::make('params')->breadcrumbs_hidden;
                $route_name = Route::getCurrentRoute()->getName();
                
                // Если текущий роут не находится в списке скрытых крошек, попробуем отобразить крошки
                if ($route_name AND ! in_array($route_name, $breadcrumbs_hidden)) {
                    echo Breadcrumbs::renderIfExists();
                } ?>                

                @yield('content')
            </div>
        </div>
    </div>
    
	<!-- Scripts -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
    
	<link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/0.5.0/sweet-alert.css" rel="stylesheet">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/0.5.0/sweet-alert.min.js"></script>

    <link href="{{ asset('/bower/prettyforms/prettyforms.css') }}" rel="stylesheet">
	<script src="{{ asset('/bower/prettyforms/prettyforms.js') }}"></script>
    
    <script>
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': '<?= csrf_token() ?>'
          }
        });
    </script>


</body>
</html>
