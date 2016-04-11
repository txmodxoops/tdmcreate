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

/**
 * Class TemplatesUserBroken.
 */
class TemplatesUserBroken extends TDMCreateFile
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
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
		$this->setFileName($filename);
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
        $htmlcode = TDMCreateHtmlSmartyCodes::getInstance();
		return $htmlcode->getSmartyIncludeFile($moduleDirname, 'header');
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
		$htmlcode = TDMCreateHtmlSmartyCodes::getInstance();
        $fields = $this->getTableFields($tableMid, $tableId);
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $stuFieldName = strtoupper($fieldName);
            if ((1 == $tableAutoincrement) || (1 == $fields[$f]->getVar('field_user'))) {
                $const = $htmlcode->getSmartyConst($language, $stuFieldName);
                $th .= $htmlcode->getHtmlTag('th', array('class' => 'center'), $const).PHP_EOL;
            }
        }
        $tr = $htmlcode->getHtmlTag('tr', array('class' => 'head'), $th).PHP_EOL;

        return $htmlcode->getHtmlTag('thead', array('class' => 'outer'), $tr).PHP_EOL;
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
		$htmlcode = TDMCreateHtmlSmartyCodes::getInstance();
		$fields = $this->getTableFields($tableMid, $tableId);
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            $rpFieldName = $this->getRightString($fieldName);
            if ((1 == $tableAutoincrement) || (1 == $fields[$f]->getVar('field_user'))) {
                switch ($fieldElement) {
                    case 9:
                        $span = $htmlcode->getHtmlSpan("<{\${$moduleDirname}_upload_url}>/images/{$tableName}/<{\${$tableSolename}.{$rpFieldName}}>", "#<{\${$tableSolename}.{$rpFieldName}}>").PHP_EOL;
                        $ret .= $htmlcode->getHtmlTableData($span, 'center').PHP_EOL;
                        break;
                    case 10:
                        $img = $htmlcode->getHtmlImage("<{xoModuleIcons32}><{\${$tableSolename}.{$rpFieldName}}>", $tableName);
                        $ret .= $htmlcode->getHtmlTableData($img, 'center').PHP_EOL;
                        break;
                    case 13:
                        $img = $htmlcode->getHtmlImage("<{\${$moduleDirname}_upload_url}>/images/{$tableName}/<{\${$tableSolename}.{$rpFieldName}}>", $tableName);
                        $ret .= $htmlcode->getHtmlTableData($img, 'center').PHP_EOL;
                        break;
                    default:
                        $ret .= $htmlcode->getHtmlTableData("<{\${$tableSolename}.{$rpFieldName}}>", 'center').PHP_EOL;
                        break;
                }
            }
        }
        $row = $htmlcode->getHtmlTableRow($ret, '<{cycle values="odd, even"}>').PHP_EOL;
        $foreach = $htmlcode->getSmartyForeach($tableSolename, $tableName, $row).PHP_EOL;

        return $htmlcode->getHtmlTableTbody($foreach).PHP_EOL;
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
        $htmlcode = TDMCreateHtmlSmartyCodes::getInstance();
		return $htmlcode->getSmartyIncludeFile($moduleDirname, 'footer');
    }

    /*
    *  @public function renderFile
    *  @param string $filename
    */
    /**
     * @param null
     *
     * @return bool|string
     */
    public function render()
    {
        $htmlcode = TDMCreateHtmlSmartyCodes::getInstance();
		$module = $this->getModule();
        $table = $this->getTable();
        $moduleDirname = $module->getVar('mod_dirname');
		$filename = $this->getFileName();
        $tableId = $table->getVar('table_id');
        $tableMid = $table->getVar('table_mid');
        $tableName = $table->getVar('table_name');
        $tableSolename = $table->getVar('table_solename');
        $tableAutoincrement = $table->getVar('table_autoincrement');
        $language = $this->getLanguage($moduleDirname, 'MA');
        $content = $this->getTemplatesUserBrokenFileHeader($moduleDirname).PHP_EOL;
        $contentTable = $this->getTemplatesUserBrokenTableHead($tableMid, $tableId, $tableAutoincrement, $language);
        $contentTable .= $this->getTemplatesUserBrokenBody($moduleDirname, $tableMid, $tableId, $tableName, $tableSolename, $tableAutoincrement, $language);
        $content .= $htmlcode->getHtmlTable($contentTable, 'table table-bordered').PHP_EOL;
        $content .= $this->getTemplatesUserBrokenFileFooter($moduleDirname);
        //
        $this->create($moduleDirname, 'templates', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
