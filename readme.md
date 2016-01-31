## An example of the application on the basis of PrettyForms

### Install and run

To run the sample application, first clone it to your computer:
```bash
git clone git@github.com:believer-ufa/prettyforms-laravel5-app.git
```

After application get cloned, install a `PHP SQLite` component, for example, by entering the following command:
```bash
sudo apt-get install -y php5-sqlite
```

Install all the dependencies the application through the Composer:
```bash
composer install
```

Create new empty database and run migrations
```bash
touch storage/database.sqlite
php artisan migrate
```

Finally, start the test application and start to study it:
```bash
php artisan serve
```


## Пример приложения на основе PrettyForms

### Установка и запуск

Для того, чтобы запустить тестовое приложение, первым делом склонируйте его на свой компьютер:
```bash
git clone git@github.com:believer-ufa/prettyforms-laravel5-app.git
```

После того, как приложение склонируется, установите на ваш комьпютер модуль `PHP SQLite`, например, через ввод следующей команды:
```bash
sudo apt-get install -y php5-sqlite
```

Установите все зависимости приложения через Composer:
```bash
composer install
```

Создайте пустую SQLite базу данных и примените миграции
```bash
touch storage/database.sqlite
php artisan migrate
```

И, наконец, запустите тестовое приложение и начинайте его изучение:
```bash
./artisan serve
```
