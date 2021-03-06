PrettyForms
===========

Небольшая библиотека, благодаря которой можно легко сделать валидацию формы на клиентской и серверной сторонах. Изначально настроена для работы с [Twitter Bootstrap](http://getbootstrap.com).

Зависимости: jQuery.

[Скринкаст работы библиотеки](demo.gif)

####Статьи на хабрахабре:
1. [PrettyForms — простая клиент-серверная валидация форм](http://habrahabr.ru/post/243637/)
2. [Динамичное веб-приложение на основе Laravel, PrettyForms и Backbone.js](http://habrahabr.ru/post/243925/)

## Установка
Вы можете скачать zip-архив с библиотекой, либо установить её через bower:
```shell
bower install prettyforms --save
```

## Алгоритм работы:
1. Пользователь заполняет поля и нажимает кнопку отправки формы. Библиотека проводит валидацию всех данных, и если всё нормально, она собирает все данные формы и отправляет POST-запрос на сервер и ожидает от него JSON-ответ в специальном формате.
2. Сервер, получив запрос, проводит дополнительную валидацию данных уже на своей стороне. Если возникли ошибки при серверной валидации, он возвращает клиенту специальным образом сформированный JSON-ответ, содержащий команду для отображения ошибок серверной валидации с информацией о полях и содержащихся в них ошибках.
3. Если данные успешно прошли валидацию и на сервере, сервер производит необходимые операции и возвращает JSON-ответ с командами, описывающими действия, которые клиентская машина должна выполнить после успешной обработки операции.

То есть, сервер всегда отвечает определённым набором команд для браузера, а браузер просто исполняет данные команды на клиенской машине. Таков алгоритм работы библиотеки.

## Пример использования

Подключите JS-файл `prettyforms.js` на страницу сайта и добавьте атрибут валидации `data-validation=""` со списком правил валидации ко всем полям вашей формы. После добавления правил, поля формы автоматически станут валидироваться библиотекой PrettyForms. Если данные окажутся невалидными, библиотека не даст форме отправиться на сервер. Это минимальный функционал библиотеки, без связки с сервером.

Для того чтобы подключить библиотеку для работы с сервером, добавьте к вашей стандартной кнопке отправки формы класс `senddata`, благодаря которому клики по кнопке будут перехвачены и обработаны библиотекой. Теперь библиотека не только будет производить клиентскую валидацию, но и станет отвечать за отправку данных на сервер и обработку ответа.

Пример формы для Bootstrap-фреймворка с атрибутами валидации:

```html
<form class="form-horizontal" role="form" method="POST" action="/register">
    <h1 class="form-signin-heading">Регистрация</h1>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
        <div class="col-sm-10">
            <input type="email"
                   class="form-control"
                   id="inputEmail3"
                   name="email"
                   data-validation="notempty;isemail"
                   placeholder="Введите ваш email">
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword3" class="col-sm-2 control-label">Пароль</label>
        <div class="col-sm-10">
            <input
                type="password"
                class="form-control"
                id="inputPassword3"
                name="password"
                data-validation="notempty;minlength:6"
                placeholder="Ваш пароль">
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword4" class="col-sm-2 control-label">Повторите пароль</label>
        <div class="col-sm-10">
            <input type="password"
                   class="form-control"
                   id="inputPassword4"
                   name="password_retry"
                   data-validation="notempty;passretry"
                   placeholder="Повторите пароль, вдруг ошиблись при вводе? Мы проверим это.">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <div class="btn btn-default senddata">Зарегистрироваться</div>
        </div>
    </div>
</form>
```

## Валидаторы полей

Правила валидации разделяются знаком `;`, параметры для правил передаются через знак `:`.
Пример корректного списка правил, содержащего два правила, одно из них с параметром: `"notempty;minlength:6"`

| наименование  | Описание      | Параметр|
| ------------- | ------------- |---------|
| notempty  | Поле не может быть пустым. Если это набор radio-инпутов, то один из них должен быть помечен.  | - |
| minlength  | Не менее {%} символов  | кол-во символов |
| maxlength  | Не более {%} символов  | кол-во символов |
| hasdomain  | Адрес должен начинаться с верного домена ({%})  | домен |
| isnumeric  | Поле может содержать только цифры  | - |
| isemail  | Должен быть введен корректный E-Mail  | - |
| isdate  | Поле должно содержать дату  | - |
| isphone  | Поле должно содержать номер телефона  | - |
| minint  | Минимальное вводимое число {%}  | число |
| maxint  | Максимальное вводимое число {%}  | число |
| intonly  | Можно ввести только число  | число |
| passretry  | Должно быть равно полю с паролем  | наименование поля с паролем, по-умолчанию "password" |
| checked  | Проверить, что на checkbox-элементе стоит галочка. Используется для необходимости согласиться с условиями лицензии, например.  | - |

#### Добавление своего валидатора
Вы можете легко добавить свои собственные валидаторы, используя подобный пример:
```javascript
$(window).load(function(){
  PrettyForms.Validator.setValidator('needempty', 'Поле должно быть пустым!', function(element, value, param){
      // needempty - название валидатора
      // второй параметр - сообщение об ошибке
      // третий - это сама функция валидации, в которую передаются три параметра: jQuery-элемент, значение элемента и параметр валидатора, если он был передан в свойствах валидации
      return value === '';
  });
});
```

#### Дополнительные атрибуты валидации
Библиотека также позволяет добавлять к полям некоторые дополнительные атрибуты, которые регулируют поведение проверки поля.

| атрибут       | Описание      | Обязательно?|
| ------------- | ------------- |-------------|
| data-dontsend="true"  | Отключает проверку данного поля и его отправку на сервер | Нет |


## API

Вы можете использовать некоторые методы библиотеки в своих собственных приложениях.

`PrettyForms.Validator.validate(element)`
> Провести валидацию элемента формы. Параметр: jQuery-элемент.

`PrettyForms.getInputData(elements_container)`
> Собрать данные из указанного контейнера, попутно проверив всех их валидатором. Если валидация провалилась, возвращает false вместо данных. Параметр: jQuery-элемент, из которого будут собраны все поля.

`PrettyForms.getInputsList(elements_container)`
> Вытащить все инпуты из указанного контейнера. Параметр: jQuery-селектор.

`PrettyForms.sendData(url, mass, input_container_for_clear, input)`
> Отправить данные на указанный URL и обработать ответ. Параметры:
> - URL, на который будут отправлены данные
> - Данные, которые будут отправлены на URL
> - jQuery-элемент - контейнер для очистки инпутов, либо false
> - jQuery-элемент - кнопка, на которую был совершён клик. Она будет сделана неактивной на время запроса.

## Расширенная настройка

PrettyForms изначально заточена под сайты, созданные на основе Twitter Bootstrap, но вы легко можете заменить её шаблоны с сообщениями об ошибках на свои собственные, переопределив три переменных в объекте "PrettyForms.templates".

Во время процесса валидации, библиотека автоматически создаст контейнеры для сообщений об ошибках, если они отсутствуют на форме. Для каждого поля, сразу же под ним, будет создан контейнер, на основе шаблона, расположенного в `PrettyForms.templates.element_validations_container`. На момент написания этой wiki, шаблон имеет следующий вид:
```html
<div style="display:none;margin-top:10px" id="validation-error-{%}" class="alert alert-danger" role="alert"></div>
```
В этот контейнер будут помещены сообщения об ошибках, если они возникнут во время проверки полей. Вы можете разместить данные поля самостоятельно внутри своей формы, в тех местах, в которых пожелаете, если автоматическая генерация вам не подошла по каким-либо причинам. Просто добавьте подобные контейнеры для каждого поля на форме, с атрибутом `id="validation-error-{название_поля}"`. Например, если у вас есть на странице `<input name="email" />`, то для него можно создать в любом месте контейнер ошибок: `<div style="display:none;" id="validation-error-email" class="alert alert-danger" role="alert"></div>`. Теперь библиотека найдёт ваш контейнер и поместит сообщения об ошибках в него.

Также, библиотека автоматически сгенерирует контейнер для общих сообщений об ошибках валидации. Он будет размещен сразу же перед кнопкой отправки формы, его шаблон берется из переменной `PrettyForms.templates.form_validation_messages`. На момент написания вики, шаблон имеет следующий вид:
```html
<div style="margin-bottom:10px" class="validation-errors alert alert-danger"></div>
```
Вы также можете разместить этот контейнер вручную в том месте формы, в котором вам будет наиболее удобно. Чтобы библиотека нашла ваш контейнер общих ошибок, добавьте ему класс `.validation-errors`.

## Атрибуты кнопки отправки формы
Кнопка, нажатие на которую генерирует отправку формы, может также иметь несколько дополнительных атрибутов, объясняющих, куда должны быть отправлены данные, из какого DOM-элемента они должны быть собраны, и некоторые другие свойства поведения формы. Если атрибуты не были указаны, данные будут взяты из вашей формы.

| атрибут       | Описание | Обязательно? |
| ------------- | ---------|--------------|
| data-input  | jQuery-селектор элемента, из которого будут собраны данные для отправки на сервер  | Нет, если не указано, то будет сделана попытка вытащить данные из формы, в которой лежит кнопка. Если формы не было найдено, будет отправлен запрос без данных. |
| href или data-link  | Адрес, на который будут отправлены данные  | Нет, по умолчанию данные будут взяты из атрибута action формы, если же и там пусто, то они будут отправлены на текущий URL страницы |
| data-clearinputs="true" | Очистить поля формы после успешного выполнения запроса?  | Нет |
| class="... really"  | Позволяет задать вопрос перед отправкой данных. Если к сайту подключён плагин [SweetAlert](https://github.com/t4t5/sweetalert), он будет задействован. | Нет |
| data-really-text=""  | Текст вопроса, по умолчанию берется из `PrettyForms.messages.really` | Нет |
| data-really-text-btn=""  | Текст кнопки, нажатие на которую вызовет выполнение действия. По-умолчанию, текст берется из `PrettyForms.messages.really_agree` | Нет |

## Валидация на сервере
Валидацией на сервере должен заниматься тот фреймворк, с которым вы работаете. Библиотека ожидает в качестве ответа объект, ключи которого - это названия команд, а значения - это параметры команды. Так, команда отображения ошибок валидации называется `validation_errors`, а в качестве её параметров должен быть указан уже другой объект, ключами которого являются названия полей, а в качестве их значений должны содержаться одномерные массивы с текстами ошибок валидации, которые были не пройдены.

Говоря другими словами, логика должна быть примерно следующая: если валидация полей не прошла, то сервер возвращает JSON-ответ с командой отображения ошибок. Если же валидация прошла успешно, сервер возвращает JSON-ответ с другими командами, например, редиректит пользователя на страницу сообщения об успешной регистрации. Пример подготовки ответа на PHP:
```php
// Валидируем данные, сохраняем результат валидации в $validation_success

if ($validation_success === false) {
    // Данные невалидны
    $json_response = [
        'validation_errors' => [
            'field_name' => array('error_message_1','error_message_2'),
            'second_field_name' => array('error_message')
            // и так далее
        ]
    ]);
} else {

    // Здесь пишем тот код, который нужно выполнить на сервере..

    // И подготавливаем клиенту ответ с командами:
    $json_response = [
        'redirect' => '/registration_success'
    ];
}

// Возвращает ответ клиенту
echo json_encode($json_response);
```

## Laravel 5

Для Laravel был создан специальный компонент, который сильно расширяет его возможности и использует для клиентской валидации данную библиотеку. Более подробно вы можете почитать об этом на [странице данного компонента](https://github.com/believer-ufa/prettyforms-laravel).

## Обработчики команд с сервера
Изначально библиотека поддерживает следующие команды, которые можно отправлять ей с сервера:

| команда       | Описание | Параметры |
| ------------- | ---------|--------------|
| `validation_errors`  | Вывести ошибки валидации  | Объект, где ключи - названия полей, значения - массивы с ошибками. Либо просто строка, тогда она будет выведена рядом с кнопкной отправки формы  |
| `redirect`  | Произвести редирект пользователя на другую страницу  | URL страницы |
| `refresh` | Обновить текущую страницу  | Нет |
| `nothing`  | Ничего не делать | Нет |
| `success`  | Вывести сообщение об успехе | `{ title: 'Заголовок', text: 'Текст сообщения'  }` |
| `warning`  | Вывести сообщение об опасности | `{ title: 'Заголовок', text: 'Текст сообщения'  }` |
| `info`  | Вывести информативное сообщение | `{ title: 'Заголовок', text: 'Текст сообщения'  }` |
| `error`  | Вывести сообщение об ошибке | `{ title: 'Заголовок', text: 'Текст сообщения'  }` |

Для команд `success`, `warning`, `info` и `error` действительно следующее: если к вашему сайту будет подключён плагин [SweetAlert](https://github.com/t4t5/sweetalert), то он будет задействован при их выполнении.

Пример добавления собственного обработчика события:
```javascript
PrettyForms.Commands.registerHandler('command_name', function (data) {
  // делаем здесь всё, что хочем.
  // data - это объект с данными, которые отправил нам сервер
});
```

## Формат протокола общения клиента с сервером
Клиентская библиотека ожидает от сервера ответ в следующем формате:
```javascript
{
  command_name_1 : "params",
  command_name_2 : "params"
}
```

Для отображения ошибок валидации, необходимо послать клиенту следующий ответ:
```javascript
{
  validation_errors: {
    form_field_1: ["текст ошибки №1", "текст ошибки №2"],
    form_field_2: ["текст ошибки №1", "текст ошибки №2", "текст ошибки №2"]
  }
}
```

## Известные проблемы

В данный момент, одна из проблем - это невозможность собирать инпуты с теми типами, которые были описаны [в стандартах HTML5](http://www.w3schools.com/html/html_form_input_types.asp), хотя их поддержку можно легко добавить в библиотеку, но в данный момент у меня просто не возникало данной необходимости.

Одна из частых проблем - это трудности с получением содержимого из тех полей, к которым применён какой-то дополнительный плагин, вроде Chosen или CKEDitor. Конкретно для двух этих плагинов в библиотеке уже встроена поддержка, и она корректно получает значения из полей, связанных с данными плагинами, но в мире существуют тысячи других плагинов, с которыми она может работать некорректно. Следует учитывать это при использовании библиотеки.

