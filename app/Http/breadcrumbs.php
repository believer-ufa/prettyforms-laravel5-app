<?php

use Illuminate\Database\Eloquent\Model;

Breadcrumbs::register('home', function($breadcrumbs)
{
    $breadcrumbs->push('Главная', route('home'));
});

$breadcrumbs_routes = [
	'users' => [
		'parent'       => 'home',
        'model'        => 'App\User',
		'title'        => 'Пользователи',
		'title_create' => 'Новый пользователь',
		'title_edit'   => function($user) { return "Редактирование пользователя: {$user->name}"; },
	],
	'users.password' => [
		'parent'       => 'users',
        'model'        => 'App\User',
		'title'        => function($user) { return "Установка пароля пользователю: {$user->name}"; },
	],
	'roles' => [
		'parent'       => 'home',
        'model'        => 'App\Role',
		'title'        => 'Роли',
		'title_create' => 'Новая роль',
		'title_edit'   => function($role) { return "Редактирование роли: {$role->name}"; },
	],
	'articles' => [
		'parent'       => 'home',
        'model'        => 'App\Article',
		'title'        => 'Статьи',
		'title_create' => 'Новая статья',
		'title_edit'   => function($article) { return "Редактирование статьи: {$article->title}"; },
	],
	'articles.show' => [
		'parent'       => 'articles',
        'model'        => 'App\Article',
        'title'        => function($article) { return $article->title; },
	],
];

// Список скрытых разделов с хлебными крошками, которые не надо отображать
$breadcrumbs_hidden = [ 'home' ];

$params = App::make('params');
//$params->breadcrumbs_routes = $breadcrumbs_routes;
$params->breadcrumbs_hidden = $breadcrumbs_hidden;

// Сгенерируем крошки для объектов на сайте. Генерация обработчиков крошек производится
// через обработку ассоциативного массива $breadcrumbs_routes
foreach($breadcrumbs_routes as $route_name => $route_info) {

	// Главная страница объекта
	Breadcrumbs::register($route_name, function($breadcrumbs, $parent_object = null) use ($route_name, $route_info, $breadcrumbs_routes) {

        
        // Если родительский объект - это не модель, значит попытаемся сделать его моделью
        if (!is_null($parent_object) AND !$parent_object instanceof Model) {
            if (!isset($route_info['model'])) {
                throw new ErrorException("Пожалуйста, укажите модель, с которой должна работать генерация крошек в правиле {$route_name}.");
            }
            $parent_object = $route_info['model']::find($parent_object);
        }
        
        $title_str = is_callable($route_info['title'])
            ? $route_info['title']($parent_object)
            : $route_info['title'];
        

        if ($parent_object instanceof Model) {
            
            if (isset($route_info['top_relation'])) {
                $top_relation = $route_info['top_relation'];
                $breadcrumbs->parent($route_info['parent'], $parent_object->$top_relation);
            } else {
                // Если родительский роут нужадется в модели, передадим ему её
                // Иначе - ничего передавать не будем, чтобы не засорять ссылку ненужной информацией
                if (    isset($breadcrumbs_routes[$route_info['parent']])
                    AND isset($breadcrumbs_routes[$route_info['parent']]['model']))
                {
                    $breadcrumbs->parent($route_info['parent'], $parent_object);
                } else {
                    $breadcrumbs->parent($route_info['parent']);
                }
            }

            $breadcrumbs->push($title_str, route($route_name,$parent_object->id));
        } else {
            $breadcrumbs->parent($route_info['parent']);
            $breadcrumbs->push($title_str, route($route_name));
        }

	});

    // Страница создания / изменения объекта
    if (isset($route_info['title_create']) AND isset($route_info['title_edit'])) {
        Breadcrumbs::register("{$route_name}.save", function($breadcrumbs, $model = null) use ($route_name, $route_info) {

            $parent_object = null;
            if (isset($route_info['model'])) {
                if (is_numeric($model)) {
                    $parent_object = $route_info['model']::find($model);
                } elseif (is_object($model) AND $model->exists) {
                    if (isset($route_info['parent_relation'])) {
                        $parent_relation = $route_info['parent_relation'];
                        $parent_object = $model->$parent_relation;
                    } else {
                        $parent_object = null;
                    }
                } else {
                    // Если родительский объект не был передан, значит мы хотим загрузить
                    // страницу создания объекта, следовательно, номер родительского
                    // объекта уже был передан в URL и по правилам должен идти вторым номером
                    // поэтому создадим родительскую модель на основе второго параметра в запросе
                    $parent_object = $route_info['model']::find(pf_param('two'));
                }

                $breadcrumbs->parent($route_name, $parent_object);

            } else {
                $breadcrumbs->parent($route_name, $model);
            }


            if (is_null($parent_object)) {
                $breadcrumbs->push($route_info['title_create'], route("{$route_name}.save"));
            } else {
                $edit_str = is_callable($route_info['title_edit'])
                    ? $route_info['title_edit']($parent_object)
                    : $route_info['title_edit'];
                $breadcrumbs->push($edit_str, route("{$route_name}.save",$parent_object->id));
            }
        });
    }

}
