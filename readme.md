# Лабораторная рвбота №8 Непрерывная интеграция с помощью Github Actions

## Цель работы

В рамках данной работы студенты научатся настраивать непрерывную интеграцию с помощью Github Actions.

## Задание

Создать Web приложение, написать тесты для него и настроить непрерывную интеграцию с помощью Github Actions на базе контейнеров.

### Выполнение
Создайте репозиторий containers07 и скопируйте его себе на компьютер.

В директории containers07 создайте директорию ./site. В директории ./site будет располагаться Web приложение на базе PHP.

### Создание Web приложения
Создайте в директории ./site Web приложение на базе PHP со следующей структурой:
```
site
├── modules/
│   ├── database.php
│   └── page.php
├── templates/
│   └── index.tpl
├── styles/
│   └── style.css
├── config.php
└── index.php
```
Файл `modules/database.php` содержит класс Database для работы с базой данных. Для работы с базой данных используйте SQLite. Класс должен содержать методы:

`__construct($path)` - конструктор класса, принимает путь к файлу базы данных SQLite;
`Execute($sql)` - выполняет SQL запрос;
`Fetch($sql)` - выполняет SQL запрос и возвращает результат в виде ассоциативного массива.
`Create($table, $data)` - создает запись в таблице $table с данными из ассоциативного массива $data и возвращает идентификатор созданной записи;
`Read($table, $id)` - возвращает запись из таблицы $table по идентификатору $id;
`Update($table, $id, $data)` - обновляет запись в таблице $table по идентификатору $id данными из ассоциативного массива $data;
`Delete($table, $id)` - удаляет запись из таблицы $table по идентификатору $id.
`Count($table)` - возвращает количество записей в таблице $table.
Файл `modules/page.php` содержит класс Page для работы с страницами. Класс должен содержать методы:

`__construct($template)` - конструктор класса, принимает путь к шаблону страницы;
`Render($data)` - отображает страницу, подставляя в шаблон данные из ассоциативного массива $data.
Файл `templates/index.tpl` содержит шаблон страницы.

Файл `styles/style.css` содержит стили для страницы.

Файл `index.php` содержит код для отображения страницы. Примерный код для файла index.php:

```php
<?php

require_once __DIR__ . '/modules/database.php';
require_once __DIR__ . '/modules/page.php';

require_once __DIR__ . '/config.php';

$db = new Database($config["db"]["path"]);

$page = new Page(__DIR__ . '/templates/index.tpl');

// bad idea, not recommended
$pageId = $_GET['page'];

$data = $db->Read("page", $pageId);

echo $page->Render($data);
```
Файл config.php содержит настройки для подключения к базе данных.

### Подготовка SQL файла для базы данных
Создайте в корневом каталоге директорию ./sql. В созданной директории создайте файл schema.sql со следующим содержимым:

``` sql
CREATE TABLE page (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT,
    content TEXT
);

INSERT INTO page (title, content) VALUES ('Page 1', 'Content 1');
INSERT INTO page (title, content) VALUES ('Page 2', 'Content 2');
INSERT INTO page (title, content) VALUES ('Page 3', 'Content 3');
```

### Создание тестов

Создайте в корневом каталоге директорию ./tests. В созданном каталоге создайте файл testframework.php со следующим содержимым:

```php
<?php

function message($type, $message) {
    $time = date('Y-m-d H:i:s');
    echo "{$time} [{$type}] {$message}" . PHP_EOL;
}

function info($message) {
    message('INFO', $message);
}

function error($message) {
    message('ERROR', $message);
}

function assertExpression($expression, $pass = 'Pass', $fail = 'Fail'): bool {
    if ($expression) {
        info($pass);
        return true;
    }
    error($fail);
    return false;
}

class TestFramework {
    private $tests = [];
    private $success = 0;

    public function add($name, $test) {
        $this->tests[$name] = $test;
    }

    public function run() {
        foreach ($this->tests as $name => $test) {
            info("Running test {$name}");
            if ($test()) {
                $this->success++;
            }
            info("End test {$name}");
        }
    }

    public function getResult() {
        return "{$this->success} / " . count($this->tests);
    }
}
```

Создайте в директории ./tests файл test.php со следующим содержимым:

```php
<?php

require_once __DIR__ . '/testframework.php';

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../modules/database.php';
require_once __DIR__ . '/../modules/page.php';

$testFramework = new TestFramework();

// test 1: check database connection
function testDbConnection() {
    global $config;
    // ...
}

// test 2: test count method
function testDbCount() {
    global $config;
    // ...
}

// test 3: test create method
function testDbCreate() {
    global $config;
    // ...
}

// test 4: test read method
function testDbRead() {
    global $config;
    // ...
}

// add tests
$tests->add('Database connection', 'testDbConnection');
$tests->add('table count', 'testDbCount');
$tests->add('data create', 'testDbCreate');
// ...

// run tests
$tests->run();

echo $tests->getResult();
```

Добавьте в файл `./tests/test.php` тесты для всех методов класса Database, а также для методов класса Page.

Настройка Github Actions
Создайте в корневом каталоге репозитория файл `.github/workflows/main.yml` со следующим содержимым:

```yml
name: CI

on:
  push:
    branches:
      - main

jobs:
  build:
    runs-on: ubuntu-latest
    steps:
      - name: Checkout
        uses: actions/checkout@v4
      - name: Build the Docker image
        run: docker build -t containers07 .
      - name: Create `container`
        run: docker create --name container --volume database:/var/www/db containers07
      - name: Copy tests to the container
        run: docker cp ./tests container:/var/www/html
      - name: Up the container
        run: docker start container
      - name: Run tests
        run: docker exec container php /var/www/html/tests/tests.php
      - name: Stop the container
        run: docker stop container
      - name: Remove the container
        run: docker rm container
```

### Запуск и тестирование

Отправьте изменения в репозиторий и убедитесь, что тесты проходят успешно. Для этого перейдите во вкладку Actions в репозитории и дождитесь окончания выполнения задачи.

![test](./img/chrome_bxYapg4PkV.png)


### Ответьте на вопросы:

* Что такое непрерывная интеграция?

  Непрерывная интеграция — это метод в разработке программ, когда программисты часто объединяют свои изменения в одну общую версию и автоматически проверяют, как они работают вместе. Это помогает быстрее находить ошибки и исправлять проблемы совместимости.

* Для чего нужны юнит-тесты? Как часто их нужно запускать?
  
  Юнит-тесты нужны для проверки отдельных компонентов кода на корректность их работы. Они помогают обнаруживать ошибки на ранних этапах разработки и обеспечивают уверенность в работоспособности отдельных частей программы. Юнит-тесты следует запускать как можно чаще — предпочтительно после каждого изменения в коде.

* Что нужно изменить в файле .github/workflows/main.yml для того, чтобы тесты запускались при каждом создании запроса на слияние (Pull Request)?

  ```yaml
  on:
    pull_request:
      branches:
        - main
  ```

* Что нужно добавить в файл .github/workflows/main.yml для того, чтобы удалять созданные образы после выполнения тестов?

  ```yaml
      - name: Remove the container
        run: docker rm container
  ```