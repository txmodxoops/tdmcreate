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
 * @version         $Id: TemplatesUserCategoriesList.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class TemplatesUserCategoriesList.
 */
class TemplatesUserCategoriesList extends TDMCreateHtmlSmartyCodes
{
    /*
    *  @public function constructor
    *  @param null
    */
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->tdmcfile = TDMCreateFile::getInstance();
        $this->htmlcode = TDMCreateHtmlSmartyCodes::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return TemplatesUserCategoriesList
     */
    public static function &getInstance()
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
    public function write($module, $table)
    {
        $this->setModule($module);
        $this->setTable($table);
    }

    /*
    *  @private function getTemplatesUserCategoriesListTable
    *  @param string $language
    */
    /**
     * @param $language
     *
     * @return string
     */
    private function getTemplatesUserCategoriesListTable($moduleDirname, $tableName, $tableSolename, $language)
    {
        $single = $this->htmlcode->getSmartySingleVar('table_type');
        $table = $this->getTemplatesUserCategoriesListTableThead($tableName, $language);
        $table .= $this->getTemplatesUserCategoriesListTableTBody($moduleDirname, $tableName, $tableSolename, $language).PHP_EOL;

        return $this->htmlcode->getHtmlTag('table', array('class' => 'table table-'.$single), $table).PHP_EOL;
    }

    /*
    *  @private function getTemplatesUserCategoriesListTableThead
    *  @param string $language
    */
    /**
     * @param $language
     *
     * @return string
     */
    private function getTemplatesUserCategoriesListTableThead($tableName, $language)
    {
        $stuTableName = strtoupper($tableName);
        $lang = $this->htmlcode->getSmartyConst($language, $stuTableName);
        $single = $this->htmlcode->getSmartySingleVar('numb_col');
        $th = $this->htmlcode->getHtmlTag('th', array('colspan' => $single), $lang).PHP_EOL;
        $tr = $this->htmlcode->getHtmlTag('tr', array('class' => 'head'), $th).PHP_EOL;

        return $this->htmlcode->getHtmlTag('thead', array(), $tr).PHP_EOL;
    }

    /*
    *  @private function getTemplatesUserCategoriesListTableTbody
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
    private function getTemplatesUserCategoriesListTableTbody($moduleDirname, $tableName, $tableSolename, $language)
    {
        $single = $this->htmlcode->getSmartySingleVar('panel_type');
        $include = $this->htmlcode->getSmartyIncludeFileListForeach($moduleDirname, $tableName, $tableSolename);
        $div = $this->htmlcode->getHtmlTag('div', array('class' => 'panel panel-'.$single), $include);
        $cont = $this->htmlcode->getHtmlTag('td', array(), $div).PHP_EOL;
        $html = $this->htmlcode->getHtmlEmpty('</tr><tr>').PHP_EOL;
        $cont   .= $this->htmlcode->getSmartyConditions($tableSolename.'.count', ' is div by ', '$divideby', $html).PHP_EOL;
        $foreach = $this->htmlcode->getSmartyForeach($tableSolename, $tableName, $cont).PHP_EOL;
        $tr = $this->htmlcode->getHtmlTag('tr', array(), $foreach).PHP_EOL;

        return $this->htmlcode->getHtmlTag('tbody', array(), $tr).PHP_EOL;
    }

    /*
    *  @private function getTemplatesUserCategoriesListTfoot
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
    private function getTemplatesUserCategoriesListTableTfoot()
    {
        $td = $this->htmlcode->getHtmlTag('td', array(), '&nbsp;').PHP_EOL;
        $tr = $this->htmlcode->getHtmlTag('tr', array(), $td).PHP_EOL;

        return $this->htmlcode->getHtmlTag('tfoot', array(), $tr).PHP_EOL;
    }

    /*
    *  @private function getTemplatesUserCategoriesListPanel
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
    private function getTemplatesUserCategoriesListPanel($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $language)
    {
        $fields = $this->getTableFields($tableMid, $tableId);
        $ret = '';
        $retElem = '';
        foreach (array_keys($fields) as $f) {
            $fieldElement = $fields[$f]->getVar('field_element');
            if (1 == $fields[$f]->getVar('field_user')) {
                if (1 == $fields[$f]->getVar('field_tbody')) {
                    switch ($fieldElement) {
                        default:
                        case 2:
                            $fieldName = $fields[$f]->getVar('field_name');
                            $rpFieldName = $this->tdmcfile->getRightString($fieldName);
                            $doubleVar = $this->htmlcode->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $retElem .= $this->htmlcode->getHtmlTag('span', array('class' => 'col-sm-2'), $doubleVar).PHP_EOL;
                            break;
                        case 10:
                            $fieldName = $fields[$f]->getVar('field_name');
                            $rpFieldName = $this->tdmcfile->getRightString($fieldName);
                            $singleVar = $this->htmlcode->getSmartySingleVar('xoops_icons32_url');
                            $doubleVar = $this->htmlcode->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $img = $this->htmlcode->getHtmlTag('img', array('src' => $singleVar.'/'.$doubleVar, 'alt' => "{$tableName}"), '', false);
                            $retElem .= $this->htmlcode->getHtmlTag('span', array('class' => 'col-sm-3'), $img).PHP_EOL;
                            unset($img);
                            break;
                        case 13:
                            $fieldName = $fields[$f]->getVar('field_name');
                            $rpFieldName = $this->tdmcfile->getRightString($fieldName);
                            $singleVar = $this->htmlcode->getSmartySingleVar($moduleDirname.'_upload_url');
                            $doubleVar = $this->htmlcode->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $img = $this->htmlcode->getHtmlTag('img', array('src' => $singleVar."/images/{$tableName}/".$doubleVar, 'alt' => "{$tableName}"), '', false);
                            $retElem .= $this->htmlcode->getHtmlTag('span', array('class' => 'col-sm-3'), $img).PHP_EOL;
                            unset($img);
                            break;
                        case 3:
                        case 4:
                            $fieldName = $fields[$f]->getVar('field_name');
                            $rpFieldName = $this->tdmcfile->getRightString($fieldName);
                            $doubleVar = $this->htmlcode->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $retElem .= $this->htmlcode->getHtmlTag('span', array('class' => 'col-sm-3 justify'), $doubleVar).PHP_EOL;
                            break;
                    }
                }
            }
        }

        return $this->htmlcode->getHtmlTag('div', array('class' => 'panel-body'), $retElem).PHP_EOL;
    }

    /*
    *  @public function renderFile
    *  @param string $filename
    */
    /**
     * @param $filename
     *
     * @return bool|string
     */
    public function renderFile($filename)
    {
        $module = $this->getModule();
        $tables = $this->getTableTables($module->getVar('mod_id'), 'table_order');
        $moduleDirname = $module->getVar('mod_dirname');
        $language = $this->getLanguage($moduleDirname, 'MA');
        $content = '';
        foreach (array_keys($tables) as $t) {
            $tableId = $tables[$t]->getVar('table_id');
            $tableMid = $tables[$t]->getVar('table_mid');
            $tableName = $tables[$t]->getVar('table_name');
            $tableSoleName = $tables[$t]->getVar('table_solename');
            $tableCategory = $tables[$t]->getVar('table_category');
            $tableFieldname = $tables[$t]->getVar('table_fieldname');
            $tableIndex = $tables[$t]->getVar('table_index');
            if (1 == $tableCategory) {
                $content .= $this->getTemplatesUserCategoriesListPanel($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $language);
            }
        }

        $this->tdmcfile->create($moduleDirname, 'templates', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}
