<?php

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * tdmcreate module.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 * @version         $Id: TemplatesUserPagesList.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class TemplatesUserPagesList.
 */
class TemplatesUserPagesList extends TDMCreateFile
{
    /*
    *  @public function constructor
    *  @param null
    */

    public function __construct()
    {
        parent::__construct();
    }

    /*
    *  @static function getInstance
    *  @param null
    */
    /**
     * @return TemplatesUserPagesList
     */
    public static function getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /*
    *  @public function write
    *  @param string $module
    *  @param string $table
    *  @param string $filename
    */
    /**
     * @param $module
     * @param $table
     */
    public function write($module, $table, $tables, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setTables($tables);
        $this->setFileName($filename);
    }

    /*
    *  @private function getTemplatesUserPagesListPanel
    *  @param string $moduleDirname
    *  @param string $table
    *  @param string $language
    */
    /**
     * @param $moduleDirname
     * @param $table
     * @param $language
     *
     * @return string
     */
    private function getTemplatesUserPagesListPanel($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $language)
    {
        $hc = TDMCreateHtmlSmartyCodes::getInstance();
        $fields = $this->getTableFields($tableMid, $tableId);
        $ret = '';
        $retNumb = '';
        foreach (array_keys($fields) as $f) {
            $fieldElement = $fields[$f]->getVar('field_element');
            if (1 == $fields[$f]->getVar('field_user')) {
                if (1 == $fields[$f]->getVar('field_thead')) {
                    switch ($fieldElement) {
                        default:
                        case 2:
                            $fieldName = $fields[$f]->getVar('field_name');
                            $rpFieldName = $this->getRightString($fieldName);
                            $doubleVar = $hc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $retNumb = $hc->getHtmlHNumb($doubleVar, '3', 'panel-title');
                            break;
                    }
                }
            }
        }
        $ret .= $hc->getHtmlDiv($retNumb, 'panel-heading').PHP_EOL;
        $retElem = '';
        foreach (array_keys($fields) as $f) {
            $fieldElement = $fields[$f]->getVar('field_element');
            if (1 == $fields[$f]->getVar('field_user')) {
                if (1 == $fields[$f]->getVar('field_tbody')) {
                    switch ($fieldElement) {
                        default:
                        case 3:
                        case 4:
                            $fieldName = $fields[$f]->getVar('field_name');
                            $rpFieldName = $this->getRightString($fieldName);
                            $doubleVar = $hc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $retElem .= $hc->getHtmlSpan($doubleVar, 'col-sm-9 justify').PHP_EOL;
                            break;
                        case 10:
                            $fieldName = $fields[$f]->getVar('field_name');
                            $rpFieldName = $this->getRightString($fieldName);
                            $singleVar = $hc->getSmartySingleVar('xoops_icons32_url');
                            $doubleVar = $hc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $img = $hc->getHtmlImage($singleVar.'/'.$doubleVar, "{$tableName}");
                            $retElem .= $hc->getHtmlSpan($img, 'col-sm-3').PHP_EOL;
                            unset($img);
                            break;
                        case 13:
                            $fieldName = $fields[$f]->getVar('field_name');
                            $rpFieldName = $this->getRightString($fieldName);
                            $singleVar = $hc->getSmartySingleVar($moduleDirname.'_upload_url');
                            $doubleVar = $hc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $img = $hc->getHtmlImage($singleVar."/images/{$tableName}/".$doubleVar, "{$tableName}");
                            $retElem .= $hc->getHtmlSpan($img, 'col-sm-3').PHP_EOL;
                            unset($img);
                            break;
                    }
                }
            }
        }
        $ret .= $hc->getHtmlDiv($retElem, 'panel-body').PHP_EOL;
        $retFoot = '';
        foreach (array_keys($fields) as $f) {
            if (1 == $fields[$f]->getVar('field_user')) {
                if (1 == $fields[$f]->getVar('field_tfoot')) {
                    $fieldName = $fields[$f]->getVar('field_name');
                    $rpFieldName = $this->getRightString($fieldName);
                    $langConst = strtoupper($tableSoleName).'_'.strtoupper($rpFieldName);
                    $lang = $hc->getSmartyConst($language, $langConst);
                    $doubleVar = $hc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                    $retFoot .= $hc->getHtmlSpan($lang.': '.$doubleVar, 'block-pie justify').PHP_EOL;
                }
            }
        }
        $ret .= $hc->getHtmlDiv($retFoot, 'panel-foot').PHP_EOL;

        return $ret;
    }

    /*
    *  @public function render
    *  @param string $filename
    */
    /**
     * @param $filename
     *
     * @return bool|string
     */
    public function render()
    {
        $module = $this->getModule();
        $table = $this->getTable();
        //$tables = $this->getTables();
        $tables = $this->getTableTables($module->getVar('mod_id'), 'table_order');
        $moduleDirname = $module->getVar('mod_dirname');
        $filename = $this->getFileName();
        $language = $this->getLanguage($moduleDirname, 'MA');
        $content = '';
        //foreach (array_keys($tables) as $t) {
            $tableId = $table/*s[$t]*/->getVar('table_id');
        $tableMid = $table/*s[$t]*/->getVar('table_mid');
        $tableName = $table/*s[$t]*/->getVar('table_name');
        $tableSoleName = $table/*s[$t]*/->getVar('table_solename');
        $tableCategory[] = $table/*s[$t]*/->getVar('table_category');
        $tableIndex = $table/*s[$t]*/->getVar('table_index');
        if (in_array(0, $tableCategory)) {
            $content .= $this->getTemplatesUserPagesListPanel($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $language);
        }
        //}
        //$content = $this->getTemplatesUserPagesListPanel($moduleDirname, $table);

        $this->create($moduleDirname, 'templates', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
