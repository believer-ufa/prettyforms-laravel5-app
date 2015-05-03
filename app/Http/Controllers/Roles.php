<?php namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;

class Roles extends Controller {

	public function __construct()
	{
        $this->middleware('auth');
	}

    protected $_model_name = 'App\Role';
    protected $fields = [
        'name'     => [
            'tag'        => 'input',
            'label'      => 'Имя',
            'attributes' => ['data-validation' => 'notempty'],
        ],
        'users'    => [
            'tag'                   => 'search-multi',
            'label'                 => 'Пользователи',
            'model'                 => 'App\User',
            'ajax_url'              => '/search/users',
            'ajax_param_name'       => 'name',
            'ajax_data_type'        => 'json',
            'display_as'            => 'name',
            'desc'                  => 'Укажите пользователей, которые будут связаны с данной ролью',
        ],
        'description'    => [
            'tag'        => 'editor',
            'label'      => 'Описание',
        ],
    ];

    /**
     * Возвращает тексты, которые будут использоваться в генерации форм и сообщениях для объекта.
     * Метод не обязательно создавать, по умолчанию класс будет использовать стандартные общие сообщения и заголовки
     */
    protected function getStrings($model) {
        return [
            'add'  => [
                'caption' => 'Новая роль',
                'success' => 'Роль успешно создана',
            ],
            'edit' => [
                'caption' => "Редактирование роли: {$model->name}",
                'success' => "Роль {$model->name} успешно обновлена",
            ],
        ];
    }
    
    /**
     * Правила валидации для текущей модели
     * @param object $model Модель, с которой мы работаем
     * @return array
     */
    protected function getValidationRules($model) {
        $except = $model->exists ? ",{$model->id}" : '';
        return [
            'name'           => 'required|max:45|unique:roles,name' . $except,
        ];
    }

    public function getIndex()
	{
        $view = view('roles');
        $view->roles = Role::paginate(15);
		return $view;
	}
    
    function anySave(Request $request) {
        return $this->defaultSaveLogic($request);
    }
    
    function postDelete() {
        return $this->defaultDeleteLogic();
    }

}