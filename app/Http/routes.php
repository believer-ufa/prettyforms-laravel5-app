<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'Home@index');
Route::get('/home', [ 'uses' => 'Home@index', 'as' => 'home' ]);

// Установка нового пароля для пользователя
Route::controller('users/password', 'Users\Password', [ 'anySet' => 'users.password' ]);
Route::controller('users', 'Users', [ 'getIndex' => 'users', 'anySave' => 'users.save' ]);
Route::controller('roles', 'Roles', [ 'getIndex' => 'roles', 'anySave' => 'roles.save' ]);
Route::controller('articles', 'Articles', [
    'getIndex' => 'articles',
    'getShow' => 'articles.show',
    'anySave' => 'articles.save'
]);

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

// Поиск по пользователям в базе данных
Route::get('search/users', function() {
    $users = [];
    $query = Input::get('name');
    $persons = App\User::where('name','like',"%{$query}%")->orWhere('email','like',"%{$query}%")->get();
    foreach ($persons as $person) {
        $users[] = [
            'id'   => $person->id,
            'text' => $person->name,
        ];
    }
    return $users;
});

