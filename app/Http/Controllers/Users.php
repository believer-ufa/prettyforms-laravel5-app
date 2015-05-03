<?php namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class Users extends Controller {

	public function __construct()
	{
        $this->middleware('auth');
	}

    protected $_model_name = 'App\User';
    protected $fields = [
        'name'     => [
            'tag'        => 'input',
            'label'      => 'Имя',
            'attributes' => ['data-validation' => 'notempty'],
        ],
        'email'    => [
            'tag'        => 'input',
            'label'      => 'E-Mail',
            'attributes' => ['data-validation' => 'notempty'],
        ],
        'password'    => [
            'tag'        => 'input',
            'label'      => 'Пароль',
            'attributes' => ['data-validation' => 'notempty'],
        ],
        'its_man'    => [
            'tag'                   => 'select',
            'label'                 => 'Пол',
            'options' => [
                '1' => 'Мужчина',
                '0' => 'Женщина'
            ],
        ],
        'roles'    => [
            'tag'                   => 'checkbox-multi',
            'label'                 => 'Роли',
            'model'                 => 'App\Role',
            'display_as'            => 'name',
        ],
    ];

    protected $fields_edit = [
        'name'     => [
            'tag'        => 'input',
            'label'      => 'Имя',
            'attributes' => ['data-validation' => 'notempty'],
        ],
        'email'    => [
            'tag'        => 'input',
            'label'      => 'E-Mail',
            'attributes' => ['data-validation' => 'notempty'],
        ],
        'roles'    => [
            'tag'                   => 'checkbox-multi',
            'label'                 => 'Роли',
            'model'                 => 'App\Role',
            'display_as'            => 'name',
        ],
    ];

    /**
     * Возвращает тексты, которые будут использоваться в генерации форм и сообщениях для объекта.
     * Метод не обязательно создавать, по умолчанию класс будет использовать стандартные общие сообщения и заголовки
     */
    protected function getStrings($model) {
        return [
            'add'  => [
                'caption' => 'Новый пользователь',
                'success' => 'Пользователь успешно создан',
            ],
            'edit' => [
                'caption' => "Редактирование пользователя: {$model->name}",
                'success' => "Пользователь {$model->name} успешно обновлён",
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
            'name'     => 'required|max:45',
            'email'    => 'required|email|max:45|unique:users,email' . $except,
        ];
    }

    public function getIndex()
	{
        $view = view('users');
        $view->users = User::withTrashed()->paginate(15);
		return $view;
	}
    
    function anySave(Request $request) {
        if (\Request::wantsJson() AND \Request::isMethod('post'))
        {
            $user_id = pf_param();
            
            if ($user_id) {
                // При редактировании пользователя используем стандартную логику сохранения
                return $this->save($request, $user_id);
            } else {
                /**
                 * При создании нового пользователя сохраним его пароль в хешированном виде
                 */
                return $this->save($request, pf_param(), [], function($user) {
                    $user->password = bcrypt(\Input::get('password'));
                    $user->save();
                });
            }
            
        } else {
            return $this->generateForm(pf_param());
        }
    }
    
    function postDelete() {
        return $this->defaultDeleteLogic();
    }

    function postRestore() {
        return $this->defaultRestoreLogic();
    }

    function postForceDelete() {
        return $this->defaultForceDeleteLogic();
    }

}
