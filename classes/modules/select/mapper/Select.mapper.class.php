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

class PluginOldRedirect_ModuleSelect_MapperSelect extends Mapper {

    public function Select($aSelect, $aParams)
    {
        $sWhere = '1=1';
        foreach($aSelect['where'] as $aWhereCond) {
            $sWhere .= ' AND (' . $this->oDb->escape($aWhereCond['field'], true) . ' ' . $aWhereCond['oper'] . ' ' . $aWhereCond['param'] . ')';
        }
        $sql = "SELECT * FROM " . $aSelect['from'] . " WHERE " . $sWhere . " LIMIT 1";
        $aResult = $this->oDb->sqlQuery($sql, $aParams);

        return $aResult ? reset($aResult) : [];
    }

}

// EOF