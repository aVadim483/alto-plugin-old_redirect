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

class PluginOldRedirect_ModuleSelect extends Module {

    /** @var  PluginOldRedirect_ModuleCounter_MapperCounter */
    protected $oMapper;

    protected $aOpers = ['=', '>', '<', '>=', '<=', '<>', 'IN', 'NOT IN'];

    public function Init() {

        $this->oMapper = E::GetMapper(__CLASS__);
    }

    /**
     * ... с проверками против SQL-inject
     *
     * @param $aSelect
     * @param $aParams
     *
     * @return array
     */
    public function Select($aSelect, $aParams) {

        // проверка имени таблицы
        if (!empty($aSelect['from']) && preg_match('/\w+/', $aSelect['from'])) {
            $aSelect['from'] = '?_' . $aSelect['from'];
            if (!isset($aSelect['where'])) {
                $aSelect['where'] = [];
            } else {
                $aWhere = [];
                // если одномерный массив, то делаем его двумерным для унификации
                $xItem = reset($aSelect['where']);
                if (!is_array($xItem)) {
                    $aSelect['where'] = [$aSelect['where']];
                }
                foreach($aSelect['where'] as $aWhereCond) {
                    // проверка имени поля и набора логических операций
                    if (preg_match('/\w+/', $aWhereCond[0]) && in_array(strtoupper($aWhereCond[1]), $this->aOpers)) {
                        // если параметр задан явно (нет знака '?'), то значение уносим в список параметров
                        if ($aWhereCond[2][0] === '?') {
                            $sParam = $aWhereCond[2];
                        } else {
                            $sParamName = ':' . str_replace('.', '_', uniqid('', true));
                            $aParams[$sParamName] = $aWhereCond[2][0];
                            $sParam = '?' . $sParamName;
                        }
                        $aWhere[] = [
                            'field' => $aWhereCond[0],
                            'oper' => strtoupper($aWhereCond[1]),
                            'param' => $sParam,
                        ];
                    }
                }
                $aSelect['where'] = $aWhere;
            }
            return $this->oMapper->Select($aSelect, $aParams);
        }
        return [];
    }

}

// EOF