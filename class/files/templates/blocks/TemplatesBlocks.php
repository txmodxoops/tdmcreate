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
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 * @version         $Id: TemplatesBlocks.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class TemplatesBlocks.
 */
class TemplatesBlocks extends TDMCreateFile
{
    /**
     * @public function constructor
     * @param null
     */

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @static function getInstance
     * @param null
     * @return TemplatesBlocks
     */
    public static function getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * @public function write
     * @param string $module
     * @param string $table
     * @param string $filename
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /**
     * @private  function getTemplatesBlocksTableThead
     * @param        $tableId
     * @param        $tableMid
     * @param string $language
     * @return string
     */
    private function getTemplatesBlocksTableThead($tableId, $tableMid, $language)
    {
        $hc     = TDMCreateHtmlSmartyCodes::getInstance();
        $th     = '';
        $fields = $this->getTableFields($tableMid, $tableId);
        foreach (array_keys($fields) as $f) {
            $fieldName    = $fields[$f]->getVar('field_name');
            $stuFieldName = strtoupper($fieldName);
            $lang         = $hc->getSmartyConst($language, $stuFieldName);
            $th .= $hc->getHtmlTableHead($lang, 'center') . PHP_EOL;
        }
        $tr = $hc->getHtmlTableRow($th, 'head') . PHP_EOL;

        return $hc->getHtmlTableThead($tr) . PHP_EOL;
    }

    /**
     * @private  function getTemplatesBlocksTableTbody
     * @param string $moduleDirname
     * @param        $tableId
     * @param        $tableMid
     * @param        $tableName
     * @param        $tableSoleName
     * @param        $tableAutoincrement
     * @param string $language
     * @return string
     */
    private function getTemplatesBlocksTableTbody($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $tableAutoincrement, $language)
    {
        $hc = TDMCreateHtmlSmartyCodes::getInstance();
        $td = '';
        if (1 == $tableAutoincrement) {
            $double = $hc->getSmartyDoubleVar($tableSoleName, 'id');
            $td .= $hc->getHtmlTag('td', ['class' => 'center'], $double) . PHP_EOL;
        }
        $fields = $this->getTableFields($tableMid, $tableId);
        foreach (array_keys($fields) as $f) {
            $fieldName    = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            $rpFieldName  = $this->getRightString($fieldName);
            if (0 == $f) {
                $fieldId = $fieldName;
            }
            if (1 == $fields[$f]->getVar('field_inlist')) {
                switch ($fieldElement) {
                    case 9:
                        // This is to be reviewed, as it was initially to style = "backgroung-color: #"
                        // Now with HTML5 is not supported inline style in the parameters of the HTML tag
                        // Old code was <span style="background-color: #<{\$list.{$rpFieldName}}>;">...
                        $double = $hc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                        $span   = $hc->getHtmlTag('span', [], $double);
                        $td .= $hc->getHtmlTag('td', ['class' => 'center'], $span) . PHP_EOL;
                        /*$ret .= <<<EOT
                    <td class="center"><span style="background-color: #<{\$list.{$rpFieldName}}>;">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>\n
EOT;*/
                        break;
                    case 10:
                        $src = $hc->getSmartyNoSimbol('xoModuleIcons32');
                        $src .= $hc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                        $img = $hc->getHtmlTag('img', ['src' => $src, 'alt' => $tableName], '', true);
                        $td .= $hc->getHtmlTag('td', ['class' => 'center'], $img) . PHP_EOL;
                        break;
                    case 13:
                        $single = $hc->getSmartySingleVar($moduleDirname . '_upload_url');
                        $double = $hc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                        $img    = $hc->getHtmlTag('img', ['src' => $single . "/images/{$tableName}/" . $double, 'alt' => $tableName], '', true);
                        $td .= $hc->getHtmlTag('td', ['class' => 'center'], $img) . PHP_EOL;
                        break;
                    default:
                        if (0 != $f) {
                            $double = $hc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $td .= $hc->getHtmlTag('td', ['class' => 'center'], $double) . PHP_EOL;
                        }
                        break;
                }
            }
        }
        $lang   = $hc->getSmartyConst('', '_EDIT');
        $double = $hc->getSmartyDoubleVar($tableSoleName, 'id');
        $src    = $hc->getSmartyNoSimbol('xoModuleIcons32 edit.png');
        $img    = $hc->getHtmlTag('img', ['src' => $src, 'alt' => $tableName], '', true);
        $anchor = $hc->getHtmlTag('a', ['href' => $tableName . ".php?op=edit&amp;{$fieldId}=" . $double, 'title' => $lang], $img) . PHP_EOL;
        $lang   = $hc->getSmartyConst('', '_DELETE');
        $double = $hc->getSmartyDoubleVar($tableSoleName, 'id');
        $src    = $hc->getSmartyNoSimbol('xoModuleIcons32 delete.png');
        $img    = $hc->getHtmlTag('img', ['src' => $src . $double, 'alt' => $tableName], '', true);
        $anchor .= $hc->getHtmlTag('a', ['href' => $tableName . ".php?op=delete&amp;{$fieldId}=" . $double, 'title' => $lang], $img) . PHP_EOL;
        $td .= $hc->getHtmlTag('td', ['class' => 'center'], "\n" . $anchor) . PHP_EOL;
        $cycle   = $hc->getSmartyNoSimbol('cycle values="odd, even"');
        $tr      = $hc->getHtmlTag('tr', ['class' => $cycle], $td) . PHP_EOL;
        $foreach = $hc->getSmartyForeach($tableSoleName, $tableName . '_list', $tr) . PHP_EOL;
        $tbody   = $hc->getHtmlTag('tbody', [], $foreach) . PHP_EOL;

        return $hc->getSmartyConditions($tableName . '_count', '', '', $tbody) . PHP_EOL;
    }

    /**
     * @private  function getTemplatesBlocksTfoot
     * @return string
     */
    private function getTemplatesBlocksTableTfoot()
    {
        $hc = TDMCreateHtmlSmartyCodes::getInstance();
        $td = $hc->getHtmlTag('td', [], '&nbsp;') . PHP_EOL;
        $tr = $hc->getHtmlTag('tr', [], $td) . PHP_EOL;

        return $hc->getHtmlTag('tfoot', [], $tr) . PHP_EOL;
    }

    /**
     * @private  function getTemplatesBlocksTable
     * @param string $moduleDirname
     * @param        $tableId
     * @param        $tableMid
     * @param string $tableName
     * @param        $tableSoleName
     * @param        $tableAutoincrement
     * @param string $language
     * @return string
     */
    private function getTemplatesBlocksTable($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $tableAutoincrement, $language)
    {
        $hc    = TDMCreateHtmlSmartyCodes::getInstance();
        $tbody = $this->getTemplatesBlocksTableThead($tableId, $tableMid, $language);
        $tbody .= $this->getTemplatesBlocksTableTbody($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $tableAutoincrement, $language);
        $tbody .= $this->getTemplatesBlocksTableTfoot();
        $single = $hc->getSmartySingleVar('table_type');

        return $hc->getHtmlTable($tbody, 'table table-' . $single) . PHP_EOL;
    }

    /**
     * @public function render
     * @param null
     *
     * @return bool|string
     */
    public function render()
    {
        $module             = $this->getModule();
        $table              = $this->getTable();
        $filename           = $this->getFileName();
        $moduleDirname      = $module->getVar('mod_dirname');
        $tableId            = $table->getVar('table_id');
        $tableMid           = $table->getVar('table_mid');
        $tableName          = $table->getVar('table_name');
        $tableSoleName      = $table->getVar('table_solename');
        $tableAutoincrement = $table->getVar('table_autoincrement');
        $language           = $this->getLanguage($moduleDirname, 'MB');
        $content            = $this->getTemplatesBlocksTable($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $tableAutoincrement, $language);

        $this->create($moduleDirname, 'templates/blocks', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
