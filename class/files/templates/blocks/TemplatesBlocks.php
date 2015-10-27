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
 * @version         $Id: TemplatesBlocks.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class TemplatesBlocks.
 */
class TemplatesBlocks extends TDMCreateHtmlSmartyCodes
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
     * @return TemplatesBlocks
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
    *  @private function getTemplatesBlocksTableThead
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
    private function getTemplatesBlocksTableThead($tableId, $tableMid, $language)
    {
        $th = '';
        $fields = $this->getTableFields($tableMid, $tableId);
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $stuFieldName = $language.strtoupper($fieldName);
            $lang = $this->htmlcode->getSmartyConst($language, $stuFieldName);
            $th    .= $this->htmlcode->getHtmlTableHead($lang, 'center').PHP_EOL;
        }
        $tr = $this->htmlcode->getHtmlTableRow($th, 'head').PHP_EOL;

        return $this->htmlcode->getHtmlTableThead($tr).PHP_EOL;
    }

    /*
    *  @private function getTemplatesBlocksTableTbody
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
    private function getTemplatesBlocksTableTbody($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $tableAutoincrement, $language)
    {
        $td = '';
        if (1 == $tableAutoincrement) {
            $double = $this->htmlcode->getSmartyDoubleVar($tableSoleName, 'id');
            $td    .= $this->htmlcode->getHtmlTag('td', array('class' => 'center'), $double).PHP_EOL;
        }
        $fields = $this->getTableFields($tableMid, $tableId);
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            $rpFieldName = $this->tdmcfile->getRightString($fieldName);
            if (0 == $f) {
                $fieldId = $fieldName;
            }
            if (1 == $fields[$f]->getVar('field_inlist')) {
                switch ($fieldElement) {
                    case 9:
                        // This is to be reviewed, as it was initially to style = "backgroung-color: #"
                        // Now with HTML5 is not supported inline style in the parameters of the HTML tag
                        // Old code was <span style="background-color: #<{\$list.{$rpFieldName}}>;">...
                        $double = $this->htmlcode->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                        $span = $this->htmlcode->getHtmlTag('span', array(), $double);
                        $td .= $this->htmlcode->getHtmlTag('td', array('class' => 'center'), $span).PHP_EOL;
                        /*$ret .= <<<EOT
                    <td class="center"><span style="background-color: #<{\$list.{$rpFieldName}}>;">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>\n
EOT;*/
                        break;
                    case 10:
                        $src = $this->htmlcode->getSmartyNoSimbol('xoModuleIcons32');
                        $src .= $this->htmlcode->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                        $img = $this->htmlcode->getHtmlTag('img', array('src' => $src, 'alt' => $tableName), '', false);
                        $td  .= $this->htmlcode->getHtmlTag('td', array('class' => 'center'), $img).PHP_EOL;
                        break;
                    case 13:
                        $single = $this->htmlcode->getSmartySingleVar($moduleDirname.'_upload_url');
                        $double = $this->htmlcode->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                        $img = $this->htmlcode->getHtmlTag('img', array('src' => $single."/images/{$tableName}/".$double, 'alt' => $tableName), '', false);
                        $td    .= $this->htmlcode->getHtmlTag('td', array('class' => 'center'), $img).PHP_EOL;
                        break;
                    default:
                        if (0 != $f) {
                            $double = $this->htmlcode->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $td    .= $this->htmlcode->getHtmlTag('td', array('class' => 'center'), $double).PHP_EOL;
                        }
                        break;
                }
            }
        }
        $lang = $this->htmlcode->getSmartyConst('', '_EDIT');
        $double = $this->htmlcode->getSmartyDoubleVar($tableSoleName, 'id');
        $src = $this->htmlcode->getSmartyNoSimbol('xoModuleIcons32 edit.png');
        $img = $this->htmlcode->getHtmlTag('img', array('src' => $src, 'alt' => $tableName), '', false);
        $anchor = $this->htmlcode->getHtmlTag('a', array('href' => $tableName.".php?op=edit&amp;{$fieldId}=".$double, 'title' => $lang), $img).PHP_EOL;
        $lang = $this->htmlcode->getSmartyConst('', '_DELETE');
        $double = $this->htmlcode->getSmartyDoubleVar($tableSoleName, 'id');
        $src = $this->htmlcode->getSmartyNoSimbol('xoModuleIcons32 delete.png');
        $img = $this->htmlcode->getHtmlTag('img', array('src' => $src.$double, 'alt' => $tableName), '', false);
        $anchor .= $this->htmlcode->getHtmlTag('a', array('href' => $tableName.".php?op=delete&amp;{$fieldId}=".$double, 'title' => $lang), $img).PHP_EOL;
        $td     .= $this->htmlcode->getHtmlTag('td', array('class' => 'center'), "\n".$anchor).PHP_EOL;
        $cycle = $this->htmlcode->getSmartyNoSimbol('cycle values="odd, even"');
        $tr = $this->htmlcode->getHtmlTag('tr', array('class' => $cycle), $td).PHP_EOL;
        $foreach = $this->htmlcode->getSmartyForeach($tableSoleName, $tableName.'_list', $tr).PHP_EOL;
        $tbody = $this->htmlcode->getHtmlTag('tbody', array(), $foreach).PHP_EOL;

        return $this->htmlcode->getSmartyConditions($tableName.'_count', '', '', $tbody).PHP_EOL;
    }

    /*
    *  @private function getTemplatesBlocksTfoot
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
    private function getTemplatesBlocksTableTfoot()
    {
        $td = $this->htmlcode->getHtmlTag('td', array(), '&nbsp;').PHP_EOL;
        $tr = $this->htmlcode->getHtmlTag('tr', array(), $td).PHP_EOL;

        return $this->htmlcode->getHtmlTag('tfoot', array(), $tr).PHP_EOL;
    }

    /*
    *  @private function getTemplatesBlocksTable
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $fields
    *  @param string $language
    *  @return string
    */
    private function getTemplatesBlocksTable($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $tableAutoincrement, $language)
    {
        $tbody = $this->getTemplatesBlocksTableThead($tableId, $tableMid, $language);
        $tbody .= $this->getTemplatesBlocksTableTbody($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $tableAutoincrement, $language);
        $tbody .= $this->getTemplatesBlocksTableTfoot();
        $single = $this->htmlcode->getSmartySingleVar('table_type');

        return $this->htmlcode->getHtmlTable($tbody, 'table table-'.$single).PHP_EOL;
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
        $tableSoleName = $table->getVar('table_solename');
        $tableAutoincrement = $table->getVar('table_autoincrement');
        $language = $this->getLanguage($moduleDirname, 'MB');
        $content = $this->getTemplatesBlocksTable($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $tableAutoincrement, $language);
        //
        $this->tdmcfile->create($moduleDirname, 'templates/blocks', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}
