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
 * @version         $Id: TemplatesUserCategories.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class TemplatesUserCategories.
 */
class TemplatesUserCategories extends TDMCreateHtmlSmartyCodes
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
     * @return TemplatesUserCategories
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
    *  @private function getTemplatesUserCategoriesHeader
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
    private function getTemplatesUserCategoriesHeader($moduleDirname)
    {
        return $this->htmlcode->getSmartyIncludeFile($moduleDirname, 'header').PHP_EOL;
    }

    /*
    *  @private function getTemplatesUserCategoriesTable
    *  @param string $language
    */
    /**
     * @param $language
     *
     * @return string
     */
    private function getTemplatesUserCategoriesTable($moduleDirname, $tableName, $tableSolename, $language)
    {
        $single = $this->htmlcode->getSmartySingleVar('table_type');
        $table = $this->getTemplatesAdminCategoriesTableThead($tableName, $language);
        $table .= $this->getTemplatesAdminCategoriesTableTBody($moduleDirname, $tableName, $tableSolename, $language);

        return $this->htmlcode->getHtmlTag('table', array('class' => 'table table-'.$single), $table).PHP_EOL;
    }

    /*
    *  @private function getTemplatesUserCategoriesThead
    *  @param string $language
    */
    /**
     * @param $language
     *
     * @return string
     */
    private function getTemplatesUserCategoriesTableThead($tableName, $language)
    {
        $stuTableName = strtoupper($tableName);
        $lang = $this->htmlcode->getSmartyConst($language, $stuTableName.'_TITLE');
        $single = $this->htmlcode->getSmartySingleVar('numb_col');
        $th = $this->htmlcode->getHtmlTag('th', array('class' => $single), $lang).PHP_EOL;
        $tr = $this->htmlcode->getHtmlTag('tr', array('class' => 'head'), $th).PHP_EOL;

        return $this->htmlcode->getHtmlTag('thead', array(), $tr).PHP_EOL;
    }

    /*
    *  @private function getTemplatesUserCategoriesTbody
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
    private function getTemplatesUserCategoriesTableTbody($moduleDirname, $tableName, $tableSolename, $language)
    {
        $single = $this->htmlcode->getSmartySingleVar('panel_type');
        $include = $this->htmlcode->getSmartyIncludeFileListForeach($moduleDirname, $tableName, $tableSolename);
        $div = $this->htmlcode->getHtmlTag('div', array('class' => 'panel panel-'.$single), $include);
        $cont = $this->htmlcode->getHtmlTag('td', array(), $div).PHP_EOL;
        $html = $this->htmlcode->getHtmlEmpty('</tr><tr>').PHP_EOL;
        $cont   .= $this->htmlcode->getSmartyConditions($tableSoleName.'.count', ' is div by ', '$divideby', $html).PHP_EOL;
        $foreach = $this->htmlcode->getSmartyForeach($tableSoleName, $tableName, $cont).PHP_EOL;
        $tr = $this->htmlcode->getHtmlTag('tr', array(), $foreach).PHP_EOL;

        return $this->htmlcode->getHtmlTag('tbody', array(), $tr).PHP_EOL;
    }

    /*
    *  @private function getTemplatesUserCategoriesTfoot
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
    private function getTemplatesUserCategoriesTfoot()
    {
        $td = $this->htmlcode->getHtmlTag('td', array(), '&nbsp;').PHP_EOL;
        $tr = $this->htmlcode->getHtmlTag('tr', array(), $td).PHP_EOL;

        return $this->htmlcode->getHtmlTag('tfoot', array(), $tr).PHP_EOL;
    }

    /*
    *  @private function getTemplatesUserCategories
    *  @param null
    */
    /**
     * @param null
     *
     * @return string
     */
    private function getTemplatesUserCategories($moduleDirname, $tableName, $tableSolename, $language)
    {
        $tab = $this->getTemplatesUserCategoriesTable($moduleDirname, $tableName, $tableSolename, $language).PHP_EOL;
        $div = $this->htmlcode->getHtmlTag('div', array('class' => 'table-responsive'), $tab).PHP_EOL;

        return $this->htmlcode->getSmartyConditions($tableName, ' gt ', '0', $div, false, true).PHP_EOL;
    }

    /*
    *  @private function getTemplatesUserCategoriesPanel
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
    private function getTemplatesUserCategoriesPanel($moduleDirname, $tableName, $tableSoleName, $language)
    {
        $stuTableName = strtoupper($tableName);

        $incl = $this->htmlcode->getSmartyIncludeFileListForeach($moduleDirname, $tableName, $tableSoleName).PHP_EOL;
        $html = $this->htmlcode->getHtmlEmpty('<br />').PHP_EOL;
        $incl .= $this->htmlcode->getSmartyConditions($tableSoleName.'.count', ' is div by ', '$numb_col', $html).PHP_EOL;
        $const = $this->htmlcode->getSmartyConst($language, $stuTableName.'_TITLE');
        $div = $this->htmlcode->getHtmlTag('div', array('class' => 'panel-heading'), $const).PHP_EOL;
        $cont = $this->htmlcode->getHtmlTag('div', array('class' => 'panel panel-body'), $incl).PHP_EOL;
        $div .= $this->htmlcode->getSmartyForeach($tableSoleName, $tableName, $cont).PHP_EOL;
        $div .= $this->htmlcode->getHtmlTag('div', array('class' => 'panel-footer'), '&nbsp;').PHP_EOL;
        $panelType = $this->htmlcode->getSmartySingleVar('panel_type');

        return $this->htmlcode->getHtmlTag('div', array('class' => 'panel panel-'.$panelType), $div).PHP_EOL;
    }

    /*
    *  @private function getTemplatesUserCategoriesFooter
    *  @param string $moduleDirname
    */
    /**
     * @param $moduleDirname
     *
     * @return string
     */
    private function getTemplatesUserCategoriesFooter($moduleDirname)
    {
        return $this->htmlcode->getSmartyIncludeFile($moduleDirname, 'footer');
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
        $table = $this->getTable();
        $moduleDirname = $module->getVar('mod_dirname');
        $tableName = $table->getVar('table_name');
        $tableSolename = $table->getVar('table_solename');
        $tableFieldname = $table->getVar('table_fieldname');
        $language = $this->getLanguage($moduleDirname, 'MA');
        $content = $this->getTemplatesUserCategoriesHeader($moduleDirname);
        $content .= $this->getTemplatesUserCategoriesPanel($moduleDirname, $tableName, $tableSolename, $language);
        $content .= $this->getTemplatesUserCategoriesFooter($moduleDirname);

        $this->tdmcfile->create($moduleDirname, 'templates', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}
