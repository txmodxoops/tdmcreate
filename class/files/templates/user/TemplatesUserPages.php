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
 * @version         $Id: TemplatesUserPages.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class TemplatesUserPages.
 */
class TemplatesUserPages extends TDMCreateFile
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
    }

    /*
    *  @static function getInstance
    *  @param null
    */
    /**
     * @return TemplatesUserPages
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
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /*
    *  @private function getTemplatesUserPagesHeader
    *  @param string $moduleDirname
    */
    /**
     * @param $moduleDirname
     *
     * @return string
     */
    private function getTemplatesUserPagesHeader($moduleDirname)
    {
        $hc = TDMCreateHtmlSmartyCodes::getInstance();

        return $hc->getSmartyIncludeFile($moduleDirname, 'header').PHP_EOL;
    }

    /*
    *  @private function getTemplatesUserPagesTable
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $fields
    *  @param string $language
    *  @return string
    */
    private function getTemplatesUserPagesTable($moduleDirname, $tableName, $tableSolename, $language)
    {
        $hc = TDMCreateHtmlSmartyCodes::getInstance();
        $tbody = $this->getTemplatesUserPagesTableThead($tableName, $language);
        $tbody .= $this->getTemplatesUserPagesTableTbody($moduleDirname, $tableName, $tableSolename, $language);
        $tbody .= $this->getTemplatesUserPagesTableTfoot();
        $single = $hc->getSmartySingleVar('table_type');

        return $hc->getHtmlTable($tbody, 'table table-'.$single).PHP_EOL;
    }

    /*
    *  @private function getTemplatesUserPagesThead
    *  @param string $language
    */
    /**
     * @param $language
     *
     * @return string
     */
    private function getTemplatesUserPagesTableThead($tableName, $language)
    {
        $hc = TDMCreateHtmlSmartyCodes::getInstance();
        $stuTableName = strtoupper($tableName);
        $single = $hc->getSmartySingleVar('divideby');
        $lang = $hc->getSmartyConst($language, $stuTableName.'_TITLE');
        $th = $hc->getHtmlTableHead($lang, '', $single).PHP_EOL;
        $tr = $hc->getHtmlTableRow($th, 'head').PHP_EOL;

        return $hc->getHtmlTableThead($tr).PHP_EOL;
    }

    /*
    *  @private function getTemplatesUserPagesTbody
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
    private function getTemplatesUserPagesTableTbody($moduleDirname, $tableName, $tableSolename, $language)
    {
        $hc = TDMCreateHtmlSmartyCodes::getInstance();
        $single = $hc->getSmartySingleVar('panel_type');
        $include = $hc->getSmartyIncludeFileListForeach($moduleDirname, $tableName, $tableSolename);
        $div = $hc->getHtmlDiv($include, 'panel panel-'.$single);
        $cont = $hc->getHtmlTableData($div).PHP_EOL;
        $html = $hc->getHtmlEmpty('</tr><tr>').PHP_EOL;
        $cont   .= $hc->getSmartyConditions($tableSolename.'.count', ' is div by ', '$divideby', $html).PHP_EOL;
        $foreach = $hc->getSmartyForeach($tableSolename, $tableName, $cont).PHP_EOL;
        $tr = $hc->getHtmlTableRow($foreach).PHP_EOL;

        return $hc->getHtmlTableTbody($tr).PHP_EOL;
    }

    /*
    *  @private function getTemplatesUserPagesTfoot
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
    private function getTemplatesUserPagesTableTfoot()
    {
        $hc = TDMCreateHtmlSmartyCodes::getInstance();
        $td = $hc->getHtmlTableData('&nbsp;').PHP_EOL;
        $tr = $hc->getHtmlTableRow($td).PHP_EOL;

        return $hc->getHtmlTableTfoot($tr).PHP_EOL;
    }

    /*
    *  @private function getTemplatesUserPages
    *  @param string $language
    */
    /**
     * @param $language
     *
     * @return string
     */
    private function getTemplatesUserPages($moduleDirname, $tableName, $tableSolename, $language)
    {
        $hc = TDMCreateHtmlSmartyCodes::getInstance();
        $table = $this->getTemplatesUserPagesTable($moduleDirname, $tableName, $tableSolename, $language).PHP_EOL;
        $div = $hc->getHtmlDiv($table, 'table-responsive').PHP_EOL;

        return $hc->getSmartyConditions($tableName, ' > ', '0', $div, false, true, true).PHP_EOL;
    }

    /*
    *  @private function getTemplatesUserPagesFooter
    *  @param string $moduleDirname
    */
    /**
     * @param $moduleDirname
     *
     * @return string
     */
    private function getTemplatesUserPagesFooter($moduleDirname)
    {
        $hc = TDMCreateHtmlSmartyCodes::getInstance();

        return $hc->getSmartyIncludeFile($moduleDirname, 'footer');
    }

    /*
    *  @public function render
    *  @param null
    */
    /**
     * @param null
     *
     * @return bool|string
     */
    public function render()
    {
        $module = $this->getModule();
        $table = $this->getTable();
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $tableName = $table->getVar('table_name');
        $tableSolename = $table->getVar('table_solename');
        $language = $this->getLanguage($moduleDirname, 'MA');
        $content = $this->getTemplatesUserPagesHeader($moduleDirname);
        $content .= $this->getTemplatesUserPages($moduleDirname, $tableName, $tableSolename, $language);
        $content .= $this->getTemplatesUserPagesFooter($moduleDirname);
        //
        $this->create($moduleDirname, 'templates', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
