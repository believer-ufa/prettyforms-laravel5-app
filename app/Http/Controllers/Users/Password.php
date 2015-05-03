<?php

namespace App\Http\Controllers\Users;

use Input;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Форма изменения пароля пользователя
 * */
class Password extends Controller {

    protected $_model_name = 'App\User';
    protected $fields = [
        'password' => [
            'tag'        => 'input',
            'label'      => 'Новый пароль',
            'attributes' => ['data-validation' => 'notempty'],
        ],
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Возвращает тексты, которые будут использоваться в генерации форм и сообщений для объекта
     * метод не обязательно создавать, по умолчанию класс будет использовать стандартные общие сообщения и заголовки
     */
    protected function getStrings($model)
    {
        return [
            'edit' => [
                'caption' => 'Установка нового пароля',
                'success' => 'Пароль был успешно изменён',
            ],
        ];
    }
    
    /**
     * Правила валидации для текущего метода
     * @param object $model Модель, с которой мы работаем
     * @return array
     */
    protected function getValidationRules($model) {
        return [
            'password' => 'required|min:6',
        ];
    }

    /**
     * После успешного сохранения, удаления и создания модели,
     * пользователь будет перенаправлен на URL, сгенерированный
     * данным методом
     */
    protected function getHomeLink($model) {
        return '/users';
    }

    function anySet(Request $request) {
        if (\Request::wantsJson() AND \Request::isMethod('post')) {
            $password = bcrypt(Input::get('password'));
            return $this->save($request, pf_param(), [ 'password' => $password]);
        } else {
            return $this->generateForm(pf_param(), [ 'password' => '']);
        }
    }

}
