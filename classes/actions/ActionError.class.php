<?php

class PluginOldRedirect_ActionError extends PluginOldRedirect_Inherits_ActionError {

    private function _ruleFind($aRuleSection, $aParams, $aMatches)
    {
        if (isset($aRuleSection['params'])) {
            foreach((array)$aRuleSection['params'] as $sName => $sValue) {
                if (strpos($sValue, '*') !== false) {
                    $sValue = str_replace('*', '{1}', $sValue);
                }
                if (preg_match_all('/\{(\d+)\}/', $sValue, $aM)) {
                    foreach($aM[0] as $i => $sMatch0) {
                        $sReplace = (isset($aMatches[$aM[1][$i]]) ? $aMatches[$aM[1][$i]] : '');
                        $sValue = str_replace($sMatch0, $sReplace, $sValue);
                    }
                }
                $aParams[$sName] = $sValue;
            }
        }
        return $aParams;
    }

    /**
     * @param $aRuleSection
     * @param $aParams
     *
     * @return array
     */
    private function _ruleSelect($aRuleSection, $aParams)
    {
        $aResult = E::Module('PluginOldRedirect\Select')->Select($aRuleSection, $aParams);
        if ($aResult) {
            $aResult = array_merge($aParams, $aResult);
        }
        return $aResult;
    }

    /**
     * @param $aRuleSection
     * @param $aParams
     */
    private function _ruleRedirect($aRuleSection, $aParams)
    {
        $sRedirect = $aRuleSection['url'];
        $aReplace = [];
        foreach($aParams as $sName => $sValue) {
            $aReplace['%%' . $sName . '%%'] = $sValue;
        }
        $sRedirect = str_replace(array_keys($aReplace), array_values($aReplace), $sRedirect);
        // если выполнены все замены, то делаем редирект
        if (!preg_match('/%%\w+%%/', $sRedirect)) {
            R::Location($sRedirect);
        }
    }

    /**
     * @return mixed
     */
    public function Init()
    {
        $sRequestPath = R::RealUrl(true);
        foreach ((array)C::Get('plugin.old_redirect.rules') as $sPath => $aRule) {
            if ($sPath[0] === '[' && substr($sPath, -1) === ']') {
                $bFound = preg_match(substr($sPath, 1, strlen($sPath) - 2), $sRequestPath, $aMatches);
            } else {
                $bFound = F::StrMatch($sPath, $sRequestPath, true, $aMatches);
            }
            if ($bFound) {
                $aParams = [];
                foreach((array)$aRule as $sSection => $aRuleSection) {
                    switch ($sSection) {
                        case 'find':
                            $aParams = $this->_ruleFind($aRuleSection, $aParams, $aMatches);
                            break;
                        case 'select':
                            $aParams = $this->_ruleSelect($aRuleSection, $aParams);
                            break;
                        case 'redirect':
                            $this->_ruleRedirect($aRuleSection, $aParams);
                            break;
                        default:
                            break;
                    }
                }
            }
        }

        return parent::Init();
    }
}

// EOF