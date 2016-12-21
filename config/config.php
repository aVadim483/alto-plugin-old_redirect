<?php
/*---------------------------------------------------------------------------
 * @Project: Alto CMS
 * @Project URI: http://altocms.com
 * @PluginId: counters
 * @PluginName: Counters
 * @Description: Counters for topics views
 * @Copyright: Alto CMS Team
 * @License: GNU GPL v2 & MIT
 *----------------------------------------------------------------------------
 */

/**
 * @package plugin OldRedirect
 */

// Описание правил редиректа
$config['rules'] = [
    // Запросы вида site.com/section/foo/bar будут перенаправлены на site.com/blog/foo/bar
    // простая маска для обработки запрашиваемого пути
    'section/*' => [
        // выделение из пути нужных параметров
        'find' => [
            // в параметр 'blog_url' записывется часть из маски под "звездочкой"
            'params' => ['blog_url' => '*'],
        ],
        // редирект на нужный адрес
        'redirect' => [
            'url' => 'blog/%%blog_url%%/'
        ],
    ],
    // Запросы вида site.com/blabla.html перенаправляются на site.com/123.html
    // маска с регулярным выражением (обязательно в квадратных скобках)
    '[~([^\/]+)\.html$~]' => [
        // выделение нужных параметров
        'find' => [
            // '{1}' - первая подмаска результата регулярного выражения
            'params' => [':topic_url' => '{1}'],
        ],
        // поиск в базе (берется всегда одна запись и результат объединяется с параметрами)
        'select' => [
            'from' => 'topic',
            'where' => ['topic_url', '=', '?:topic_url'],
        ],
        // куда делаем на редирект
        'redirect' => [
            'url' => '/%%topic_id%%.html'
        ],
    ],
];

// EOF