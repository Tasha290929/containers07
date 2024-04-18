<?php

require_once __DIR__ . '/modules/database.php';
require_once __DIR__ . '/modules/page.php';

require_once __DIR__ . '/config.php';

$db = new Database($config["db"]["path"]);

$page = new Page(__DIR__ . '/templates/index.tpl');

// Получаем идентификатор страницы из параметра запроса
$pageId = isset($_GET['page']) ? $_GET['page'] : 1; // Если параметр не указан, используем значение по умолчанию

// Получаем данные страницы из базы данных
$data = $db->Read("page", $pageId);

// Отображаем страницу
echo $page->Render($data);

?>
