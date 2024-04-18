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
