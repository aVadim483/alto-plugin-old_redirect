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
 * Запрещаем напрямую через браузер обращение к этому файлу.
 */
if (!class_exists('Plugin')) {
    die('Hacking attempt!');
}

/**
 * @package plugin OldRedirect
 */
class PluginOldRedirect extends Plugin {

    protected $aInherits = [
        'action' => [
            'ActionError',
        ],
    ];

    /**
     * Активация плагина
     */
    public function Activate()
    {
        return true;
    }

    /**
     * Инициализация плагина
     */
    public function Init()
    {
        return true;
    }

}

// EOF