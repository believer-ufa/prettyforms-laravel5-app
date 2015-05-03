<?php namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;

class Articles extends Controller {

	public function __construct()
	{
        $this->middleware('auth');
	}

    protected $_model_name = 'App\Article';
    protected $fields = [
        'title'     => [
            'tag'        => 'input',
            'label'      => 'Название',
            'attributes' => ['data-validation' => 'notempty'],
        ],
        'description'     => [
            'tag'        => 'input',
            'label'      => 'Описание',
            'desc'       => 'Краткое описание статьи',
        ],
        'keywords'     => [
            'tag'        => 'input',
            'label'      => 'Ключевые слова',
            'desc'       => 'Ключевые слова, хараектирующие статью',
        ],
        'text'    => [
            'tag'        => 'editor',
            'label'      => 'Текст статьи',
        ],
        'user_id'    => [ 'tag' => 'hidden' ],
    ];

    /**
     * Возвращает тексты, которые будут использоваться в генерации форм и сообщениях для объекта.
     * Метод не обязательно создавать, по умолчанию класс будет использовать стандартные общие сообщения и заголовки
     */
    protected function getStrings($model) {
        return [
            'add'  => [
                'caption' => 'Новая статья',
                'success' => 'Статья успешно создана',
            ],
            'edit' => [
                'caption' => "Редактирование статьи: {$model->title}",
                'success' => "Статья {$model->title} успешно обновлена",
            ],
        ];
    }
    
    /**
     * Правила валидации для текущей модели
     * @param object $model Модель, с которой мы работаем
     * @return array
     */
    protected function getValidationRules($model) {
        return [
            'title' => 'required',
            'text'  => 'required',
        ];
    }
    
    function getHomeLink($model) {
        if ($model->exists AND ! $model->trashed()) {
            return '/articles/show/' . $model->id;
        } else {
            return '/articles';
        }
    }
    
    public function getIndex() {
        $view = view('articles');
        $view->articles = Article::withTrashed()->paginate(15);
        return $view;
    }
    
    public function getShow() {
        $view = view('articles.show');
        $view->article = Article::findOrFail(pf_param());
        return $view;
    }
    
    public function anySave(Request $request)
	{
        $this->checkAccess();
        
        if (\Request::wantsJson() AND \Request::isMethod('post')) {
            return $this->save($request, pf_param(), [
                'user_id' => \Auth::user()->id
            ]);
        } else {
            return $this->generateForm(pf_param());
        }
	}
    
    public function postDelete() {
        $this->checkAccess();
        return $this->defaultDeleteLogic();
    }
    
    function postRestore() {
        $this->checkAccess();
        return $this->defaultRestoreLogic();
    }

    function postForceDelete() {
        $this->checkAccess();
        return $this->defaultForceDeleteLogic();
    }
    
    private function checkAccess() {
        $article_id = pf_param();
        if ($article_id) {
            $article = Article::withTrashed()->findOrFail($article_id);
            if ($article->user_id != \Auth::user()->id) {
                abort(403, 'Вы не имеете права редактировать чужую статью');
            }
        }
    }
    
}