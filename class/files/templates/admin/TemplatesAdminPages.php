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
 * @version         $Id: TemplatesAdminPages.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class TemplatesAdminPages.
 */
class TemplatesAdminPages extends TDMCreateFile
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
     * @return TemplatesAdminPages
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
    */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /*
    *  @private function getTemplatesAdminPagesHeader
    *  @param string $moduleDirname
    *  @return string
    */
    private function getTemplatesAdminPagesHeader($moduleDirname)
    {
        $hc = TDMCreateHtmlSmartyCodes::getInstance();
        $ret = $hc->getHtmlComment('Header').PHP_EOL;
        $ret .= $hc->getSmartyIncludeFile($moduleDirname, 'header', true);

        return $ret;
    }

    /*
    *  @private function getTemplatesAdminPagesTableThead
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $fields
    *  @param string $language
    *  @return string
    */
    private function getTemplatesAdminPagesTableThead($tableSoleName, $tableAutoincrement, $fields, $language)
    {
        $hc = TDMCreateHtmlSmartyCodes::getInstance();
        $th = '';
        $langHeadId = strtoupper($tableSoleName).'_ID';
        if (1 == $tableAutoincrement) {
            $lang = $hc->getSmartyConst($language, $langHeadId);
            $th  .= $hc->getHtmlTag('th', array('class' => 'center'), $lang, false, false, "\t\t\t");
        }
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $rpFieldName = $this->getRightString($fieldName);
            $langFieldName = strtoupper($tableSoleName).'_'.strtoupper($rpFieldName);
            if (1 == $fields[$f]->getVar('field_inlist')) {
                $lang = $hc->getSmartyConst($language, $langFieldName);
                $th  .= $hc->getHtmlTag('th', array('class' => 'center'), $lang, false, false, "\t\t\t");
            }
        }

        $lang = $hc->getSmartyConst($language, 'FORM_ACTION');
        $th  .= $hc->getHtmlTag('th', array('class' => 'center width5'), $lang, false, false, "\t\t\t");
        $tr = $hc->getHtmlTag('tr', array('class' => 'head'), $th).PHP_EOL;
        $ret = $hc->getHtmlTag('thead', array(), $tr);

        return $ret;
    }

    /*
    *  @private function getTemplatesAdminPagesTableTBody
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $fields
    *  @param string $language
    *  @return string
    */
    private function getTemplatesAdminPagesTableTBody($moduleDirname, $tableName, $tableSoleName, $tableAutoincrement, $fields)
    {
        $hc = TDMCreateHtmlSmartyCodes::getInstance();
        $td = '';
        if (1 == $tableAutoincrement) {
            $double = $hc->getSmartyDoubleVar($tableSoleName, 'id');
            $td    .= $hc->getHtmlTableData($double, 'center');
        }
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            $rpFieldName = $this->getRightString($fieldName);
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
                        $span = $hc->getHtmlTag('span', array(), $double);
                        $td .= $hc->getHtmlTag('td', array('class' => 'center'), $span);
                        /*$ret .= <<<EOT
                    <td class="center"><span style="background-color: #<{\$list.{$rpFieldName}}>;">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>\n
EOT;*/
                        break;
                    case 10:
                        $src = $hc->getSmartyNoSimbol('xoModuleIcons32');
                        $src .= $hc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                        $img = $hc->getHtmlTag('img', array('src' => $src, 'alt' => $tableName), '', true, false);
                        $td  .= $hc->getHtmlTag('td', array('class' => 'center'), "\n\t".$img);
                        break;
                    case 13:
                        $single = $hc->getSmartySingleVar($moduleDirname.'_upload_url');
                        $double = $hc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                        $img = $hc->getHtmlTag('img', array('src' => $single."/images/{$tableName}/".$double, 'alt' => $tableName, 'style' => 'max-width:100px'), '', true, false);
                        $td    .= $hc->getHtmlTag('td', array('class' => 'center'), $img, false, false, "\t\t");
                        break;
                    default:
                        if (0 != $f) {
                            $double = $hc->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $td    .= $hc->getHtmlTag('td', array('class' => 'center'), $double);
                        }
                        break;
                }
            }
        }
        $lang = $hc->getSmartyConst('', '_EDIT');
        $double = $hc->getSmartyDoubleVar($tableSoleName, 'id');
        $src = $hc->getSmartyNoSimbol('xoModuleIcons16 edit.png');
        $img = $hc->getHtmlTag('img', array('src' => $src, 'alt' => $tableName), '', true, false);
        $anchor = $hc->getHtmlTag('a', array('href' => $tableName.".php?op=edit&amp;{$fieldId}=".$double, 'title' => $lang), "\n\t".$img);
        $lang = $hc->getSmartyConst('', '_DELETE');
        $double = $hc->getSmartyDoubleVar($tableSoleName, 'id');
        $src = $hc->getSmartyNoSimbol('xoModuleIcons16 delete.png');
        $img = $hc->getHtmlTag('img', array('src' => $src, 'alt' => $tableName), '', true, false);
        $anchor .= $hc->getHtmlTag('a', array('href' => $tableName.".php?op=delete&amp;{$fieldId}=".$double, 'title' => $lang), "\n\t".$img);
        $td     .= $hc->getHtmlTag('td', array('class' => 'center  width5'), "\n".$anchor);
        $cycle = $hc->getSmartyNoSimbol('cycle values=\'odd, even\'');
        $tr = $hc->getHtmlTag('tr', array('class' => $cycle), $td);
        $foreach = $hc->getSmartyForeach($tableSoleName, $tableName.'_list', $tr);
        $tbody = $hc->getHtmlTag('tbody', array(), $foreach);

        return $hc->getSmartyConditions($tableName.'_count', '', '', $tbody);
    }

    /*
    *  @private function getTemplatesAdminPagesTable
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $fields
    *  @param string $language
    *  @return string
    */
    private function getTemplatesAdminPagesTable($moduleDirname, $tableName, $tableSoleName, $tableAutoincrement, $fields, $language)
    {
        $hc = TDMCreateHtmlSmartyCodes::getInstance();
        $tbody = $this->getTemplatesAdminPagesTableThead($tableSoleName, $tableAutoincrement, $fields, $language);
        $tbody .= $this->getTemplatesAdminPagesTableTBody($moduleDirname, $tableName, $tableSoleName, $tableAutoincrement, $fields);

        return $hc->getHtmlTable($tbody, 'table table-bordered');
    }

    /*
    *  @private function getTemplatesAdminPages
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $fields
    *  @param string $language
    *  @return string
    */
    private function getTemplatesAdminPages($moduleDirname, $tableName, $tableSoleName, $tableAutoincrement, $fields, $language)
    {
        $hc = TDMCreateHtmlSmartyCodes::getInstance();
        $htmlTable = $this->getTemplatesAdminPagesTable($moduleDirname, $tableName, $tableSoleName, $tableAutoincrement, $fields, $language);
        $htmlTable .= $hc->getHtmlTag('div', array('class' => 'clear'), '&nbsp;');
        $single = $hc->getSmartySingleVar('pagenav');
        $div = $hc->getHtmlTag('div', array('class' => 'xo-pagenav floatright'), $single);
        $div       .= $hc->getHtmlTag('div', array('class' => 'clear spacer'), '');
        $htmlTable .= $hc->getSmartyConditions('pagenav', '', '', $div);
        $ifList = $hc->getSmartyConditions($tableName.'_list', '', '', $htmlTable);
        $single = $hc->getSmartySingleVar('form');
        $divComm = $hc->getHtmlComment('Display navigation');
        //$divComm .= $hc->getHtmlTag('div', array('class' => 'errorMsg'), $single);
        $ifList .= $hc->getSmartyConditions('form', '', '', $single);
        $single = $hc->getSmartySingleVar('error');
        $strong = $hc->getHtmlTag('strong', array(), $single);
        $div = $hc->getHtmlTag('div', array('class' => 'errorMsg'), $strong);
        $ifList .= $hc->getSmartyConditions('error', '', '', $div);

        return $ifList;
    }

    /*
    *  @private function getTemplatesAdminPagesFooter
    *  @param string $moduleDirname
    *  @return string
    */
    private function getTemplatesAdminPagesFooter($moduleDirname)
    {
        $hc = TDMCreateHtmlSmartyCodes::getInstance();
        $ret = $hc->getHtmlTag('br /', array(), '', false);
        $ret .= $hc->getHtmlComment('Footer');
        $ret .= $hc->getSmartyIncludeFile($moduleDirname, 'footer', true);

        return $ret;
    }

    /*
    *  @public function render
    *  @param null
    *  @return bool|string
    */
    public function render()
    {
        $module = $this->getModule();
        $table = $this->getTable();
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language = $this->getLanguage($moduleDirname, 'AM');
        $fields = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'), 'field_order');
        $content = $this->getTemplatesAdminPagesHeader($moduleDirname);
        $content .= $this->getTemplatesAdminPages($moduleDirname, $table->getVar('table_name'), $table->getVar('table_solename'), $table->getVar('table_autoincrement'), $fields, $language);
        $content .= $this->getTemplatesAdminPagesFooter($moduleDirname);

        $this->create($moduleDirname, 'templates/admin', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
