<?php

require_once __DIR__ . '/testframework.php';

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../modules/database.php';
require_once __DIR__ . '/../modules/page.php';

$testFramework = new TestFramework();

// Тестирование методов класса Database

// Тест 1: Проверка соединения с базой данных
function testDbConnection() {
    global $config;
    $db = new Database($config["db"]["path"]);
    if ($db) {
        return true;
    } else {
        return false;
    }
}

// Тест 2: Проверка метода Count
function testDbCount() {
    global $config;
    $db = new Database($config["db"]["path"]);
    $count = $db->Count("page");
    // Предполагаем, что есть хотя бы одна запись в таблице "page"
    if ($count >= 1) {
        return true;
    } else {
        return false;
    }
}

// Тест 3: Проверка метода Create
function testDbCreate() {
    global $config;
    $db = new Database($config["db"]["path"]);
    $data = array("title" => "Test Title", "content" => "Test Content");
    $id = $db->Create("page", $data);
    if ($id > 0) {
        // Успешно создана запись
        // Удаление созданной записи после теста
        $db->Delete("page", $id);
        return true;
    } else {
        return false;
    }
}

// Тест 4: Проверка метода Read
function testDbRead() {
    global $config;
    $db = new Database($config["db"]["path"]);
    // Предполагаем, что есть хотя бы одна запись в таблице "page"
    $data = $db->Read("page", 1);
    if ($data !== null) {
        return true;
    } else {
        return false;
    }
}

// Добавление тестов для класса Database
$testFramework->add('Database connection', 'testDbConnection');
$testFramework->add('Table count', 'testDbCount');
$testFramework->add('Data create', 'testDbCreate');
$testFramework->add('Data read', 'testDbRead');

// Тестирование методов класса Page

// Тест 5: Проверка создания экземпляра класса Page
function testPageInstance() {
    global $config;
    $page = new Page(__DIR__ . '/../templates/index.tpl');
    if ($page) {
        return true;
    } else {
        return false;
    }
}

// Тест 6: Проверка метода Render класса Page
function testPageRender() {
    global $config;
    $page = new Page(__DIR__ . '/../templates/index.tpl');
    $data = array("title" => "Test Page", "content" => "This is a test page");
    $output = $page->Render($data);
    // Проверка, что вывод не пустой
    if (!empty($output)) {
        return true;
    } else {
        return false;
    }
}

// Добавление тестов для класса Page
$testFramework->add('Page instance', 'testPageInstance');
$testFramework->add('Page render', 'testPageRender');

// Запуск тестов
$testFramework->run();

echo $testFramework->getResult();

