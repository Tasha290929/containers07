<?php

require_once __DIR__ . '/testframework.php';

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../modules/database.php';
require_once __DIR__ . '/../modules/page.php';

$testFramework = new TestFramework();

// Тестирование методов класса Database

// Тест для проверки соединения с базой данных
function testDbConnection() {
    global $config;
    $db = new Database($config["db"]["path"]);
    return assertExpression($db instanceof Database, "Database connection test passed.", "Database connection test failed.");
}

// Тест для проверки метода Count
function testDbCount() {
    global $config;
    $db = new Database($config["db"]["path"]);
    $count = $db->Count("page");
    return assertExpression($count === 3, "Database count test passed.", "Database count test failed.");
}

// Тест для проверки метода Create
function testDbCreate() {
    global $config;
    $db = new Database($config["db"]["path"]);
    $data = array("title" => "New Page", "content" => "New Content");
    $id = $db->Create("page", $data);
    $createdData = $db->Read("page", $id);
    return assertExpression($createdData["title"] === $data["title"] && $createdData["content"] === $data["content"], "Database create test passed.", "Database create test failed.");
}

// Тест для проверки метода Read
function testDbRead() {
    global $config;
    $db = new Database($config["db"]["path"]);
    $data = $db->Read("page", 1);
    return assertExpression($data["title"] === "Page 1" && $data["content"] === "Content 1", "Database read test passed.", "Database read test failed.");
}

// Тест для проверки метода Delete
function testDbDelete() {
    global $config;
    $db = new Database($config["db"]["path"]);
    $db->Delete("page", 4); // Предполагается, что это созданный в предыдущем тесте id
    $count = $db->Count("page");
    return assertExpression($count === 3, "Database delete test passed.", "Database delete test failed.");
}

// Тест для проверки отображения страницы с данными
function testPageRender() {
    global $config;
    $page = new Page(__DIR__ . '/../templates/index.tpl');
    $data = array("title" => "Test Page", "content" => "Test Content");
    $renderedPage = $page->Render($data);
    // Здесь можно добавить проверки содержимого страницы, если необходимо
    return assertExpression(strlen($renderedPage) > 0, "Page render test passed.", "Page render test failed.");
}

// Добавляем тесты в объект TestFramework
$testFramework->add('Database connection', 'testDbConnection');
$testFramework->add('Database count', 'testDbCount');
$testFramework->add('Database create', 'testDbCreate');
$testFramework->add('Database read', 'testDbRead');
$testFramework->add('Database delete', 'testDbDelete');
$testFramework->add('Page render', 'testPageRender');