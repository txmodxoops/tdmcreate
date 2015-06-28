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
 * @version         $Id: TemplatesUserBroken.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class TemplatesUserBroken.
 */
class TemplatesUserBroken extends TDMCreateHtmlSmartyCodes
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
     * @return TemplatesUserBroken
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
    *  @private function getTemplatesUserBrokenFileHeader
    *  @param string $moduleDirname
    */
    /**
     * @param $moduleDirname
     *
     * @return string
     */
    private function getTemplatesUserBrokenFileHeader($moduleDirname)
    {
        return $this->htmlcode->getSmartyIncludeFile($moduleDirname, 'header');
    }

    /*
    *  @private function getTemplatesUserBrokenTableHead
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
    private function getTemplatesUserBrokenTableHead($tableMid, $tableId, $tableAutoincrement, $language)
    {
        $th = '';
        $fields = $this->getTableFields($tableMid, $tableId);
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $stuFieldName = strtoupper($fieldName);
            if ((1 == $tableAutoincrement) || (1 == $fields[$f]->getVar('field_user'))) {
                $const = $this->htmlcode->getSmartyConst($language, $stuFieldName);
                $th .= $this->htmlcode->getHtmlTag('th', array('class' => 'center'), $const).PHP_EOL;
            }
        }
        $tr = $this->htmlcode->getHtmlTag('tr', array('class' => 'head'), $th).PHP_EOL;

        return $this->htmlcode->getHtmlTag('thead', array('class' => 'outer'), $tr).PHP_EOL;
    }

    /*
    *  @private function getTemplatesUserBrokenBody
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
    private function getTemplatesUserBrokenBody($moduleDirname, $tableMid, $tableId, $tableName, $tableSolename, $tableAutoincrement, $language)
    {
        $ret = '';
        $fields = $this->getTableFields($tableMid, $tableId);
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            $rpFieldName = $this->tdmcfile->getRightString($fieldName);
            if ((1 == $tableAutoincrement) || (1 == $fields[$f]->getVar('field_user'))) {
                switch ($fieldElement) {
                    case 9:
                        $span = $this->htmlcode->getHtmlSpan("<{\${$moduleDirname}_upload_url}>/images/{$tableName}/<{\${$tableSolename}.{$rpFieldName}}>", "#<{\${$tableSolename}.{$rpFieldName}}>").PHP_EOL;
                        $ret .= $this->htmlcode->getHtmlTableData($span, 'center').PHP_EOL;
                        break;
                    case 10:
                        $img = $this->htmlcode->getHtmlImage("<{xoModuleIcons32}><{\${$tableSolename}.{$rpFieldName}}>", $tableName);
                        $ret .= $this->htmlcode->getHtmlTableData($img, 'center').PHP_EOL;
                        break;
                    case 13:
                        $img = $this->htmlcode->getHtmlImage("<{\${$moduleDirname}_upload_url}>/images/{$tableName}/<{\${$tableSolename}.{$rpFieldName}}>", $tableName);
                        $ret .= $this->htmlcode->getHtmlTableData($img, 'center').PHP_EOL;
                        break;
                    default:
                        $ret .= $this->htmlcode->getHtmlTableData("<{\${$tableSolename}.{$rpFieldName}}>", 'center').PHP_EOL;
                        break;
                }
            }
        }
        $row = $this->htmlcode->getHtmlTableRow($ret, '<{cycle values="odd, even"}>').PHP_EOL;
        $foreach = $this->htmlcode->getSmartyForeach($tableSolename, $tableName, $row).PHP_EOL;

        return $this->htmlcode->getHtmlTableTbody($foreach).PHP_EOL;
    }

    /*
    *  @private function getTemplatesUserBrokenFileFooter
    *  @param string $moduleDirname
    */
    /**
     * @param $moduleDirname
     *
     * @return string
     */
    private function getTemplatesUserBrokenFileFooter($moduleDirname)
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
        $tableId = $table->getVar('table_id');
        $tableMid = $table->getVar('table_mid');
        $tableName = $table->getVar('table_name');
        $tableSolename = $table->getVar('table_solename');
        $tableAutoincrement = $table->getVar('table_autoincrement');
        $language = $this->getLanguage($moduleDirname, 'MA');
        $content = $this->getTemplatesUserBrokenFileHeader($moduleDirname).PHP_EOL;
        $contentTable = $this->getTemplatesUserBrokenTableHead($tableMid, $tableId, $tableAutoincrement, $language);
        $contentTable .= $this->getTemplatesUserBrokenBody($moduleDirname, $tableMid, $tableId, $tableName, $tableSolename, $tableAutoincrement, $language);
        $content .= $this->htmlcode->getHtmlTable($contentTable, 'table table-bordered').PHP_EOL;
        $content .= $this->getTemplatesUserBrokenFileFooter($moduleDirname);
        //
        $this->tdmcfile->create($moduleDirname, 'templates', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}
