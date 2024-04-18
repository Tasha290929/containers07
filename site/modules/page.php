<?php

class Page {
    private $template;

    public function __construct($template) {
        $this->template = $template;
    }

    public function Render($data) {
        // Загрузить шаблон
        $templateContent = file_get_contents($this->template);

        // Заменить метки в шаблоне данными из $data
        foreach ($data as $key => $value) {
            $templateContent = str_replace("{{$key}}", $value, $templateContent);
        }

        // Вывести отрендеренный шаблон
        echo $templateContent;
    }
}

?>
