<?php namespace App\Http\Controllers;

use App\Article;
use Auth;
use Request;
use function PrettyFormsLaravel\param;

class Articles extends Controller {

	public function __construct()
	{
        $this->middleware('auth');
	}

    protected $_model_name = 'App\Article';
    protected $_form_params = [ 'order' ];

    public function getFormFields()
    {
        return [
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
    }

    /**
     * Возвращает тексты, которые будут использоваться в генерации форм и сообщениях для объекта.
     * Метод не обязательно создавать, по умолчанию класс будет использовать стандартные общие сообщения и заголовки
     */
    protected function getStrings($model)
    {
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
    protected function getValidationRules($model)
    {
        return [
            'title' => 'required',
            'text'  => 'required',
        ];
    }

    function getHomeLink($model)
    {
        if ($model->exists AND ! $model->trashed()) {
            return '/articles/show/' . $model->id;
        } else {
            return '/articles';
        }
    }

    public function getIndex()
    {
        $view = view('articles');
        $view->articles = Article::withTrashed()->orderBy('order')->paginate(15);
        return $view;
    }

    public function getShow()
    {
        $view = view('articles.show');
        $view->article = Article::findOrFail(param());
        return $view;
    }

    public function anySave()
	{
        $this->checkAccess();

        if (Request::wantsJson() AND Request::isMethod('post')) {
            return $this->save(param(), [
                'user_id' => Auth::user()->id
            ]);
        } else {
            return $this->generateForm(param());
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

    function postUp($id) {
        $this->checkAccess();
        return $this->upRecord($id);
    }

    function postDown($id) {
        $this->checkAccess();
        return $this->downRecord($id);
    }

    private function checkAccess() {
        $article_id = param();
        if ($article_id) {
            $article = Article::withTrashed()->findOrFail($article_id);
            if ($article->user_id != Auth::user()->id) {
                abort(403, 'Вы не имеете права редактировать чужую статью');
            }
        }
    }

}