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
    * @var string
    */
    private $tdmcfile = null;

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
    public function write($module, $table)
    {
        $this->setModule($module);
        $this->setTable($table);
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
        return $this->htmlcode->getSmartyIncludeFile($moduleDirname, 'header').PHP_EOL;
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
        $tbody = $this->getTemplatesUserPagesTableThead($tableName, $language);
        $tbody .= $this->getTemplatesUserPagesTableTbody($moduleDirname, $tableName, $tableSolename, $language);
        $tbody .= $this->getTemplatesUserPagesTableTfoot();
        $single = $this->htmlcode->getSmartySingleVar('table_type');

        return $this->htmlcode->getHtmlTable($tbody, 'table table-'.$single).PHP_EOL;
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
        $stuTableName = strtoupper($tableName);
        $single = $this->htmlcode->getSmartySingleVar('divideby');
        $lang = $this->htmlcode->getSmartyConst($language, $stuTableName.'_TITLE');
        $th = $this->htmlcode->getHtmlTableHead($lang, '', $single).PHP_EOL;
        $tr = $this->htmlcode->getHtmlTableRow($th, 'head').PHP_EOL;

        return $this->htmlcode->getHtmlTableThead($tr).PHP_EOL;
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
        $single = $this->htmlcode->getSmartySingleVar('panel_type');
        $include = $this->htmlcode->getSmartyIncludeFileListForeach($moduleDirname, $tableName, $tableSolename);
        $div = $this->htmlcode->getHtmlDiv($include, 'panel panel-'.$single);
        $cont = $this->htmlcode->getHtmlTableData($div).PHP_EOL;
        $html = $this->htmlcode->getHtmlEmpty('</tr><tr>').PHP_EOL;
        $cont   .= $this->htmlcode->getSmartyConditions($tableSolename.'.count', ' is div by ', '$divideby', $html).PHP_EOL;
        $foreach = $this->htmlcode->getSmartyForeach($tableSolename, $tableName, $cont).PHP_EOL;
        $tr = $this->htmlcode->getHtmlTableRow($foreach).PHP_EOL;

        return $this->htmlcode->getHtmlTableTbody($tr).PHP_EOL;
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
        $td = $this->htmlcode->getHtmlTableData('&nbsp;').PHP_EOL;
        $tr = $this->htmlcode->getHtmlTableRow($td).PHP_EOL;

        return $this->htmlcode->getHtmlTableTfoot($tr).PHP_EOL;
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
        $table = $this->getTemplatesUserPagesTable($moduleDirname, $tableName, $tableSolename, $language).PHP_EOL;
        $div = $this->htmlcode->getHtmlDiv($table, 'table-responsive').PHP_EOL;

        return $this->htmlcode->getSmartyConditions($tableName, ' gt ', '0', $div, false, true).PHP_EOL;
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
        $language = $this->getLanguage($moduleDirname, 'MA');
        $content = $this->getTemplatesUserPagesHeader($moduleDirname);
        $content .= $this->getTemplatesUserPages($moduleDirname, $tableName, $tableSolename, $language);
        $content .= $this->getTemplatesUserPagesFooter($moduleDirname);
        //
        $this->tdmcfile->create($moduleDirname, 'templates', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}
