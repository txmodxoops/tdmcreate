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
defined('XOOPS_ROOT_PATH') or die('Restricted access');
/**
 * Class TemplatesAdminPages.
 */
class TemplatesAdminPages extends TDMCreateHtmlSmartyCodes
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
     * @return TemplatesAdminPages
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
    public function write($module, $table)
    {
        $this->setModule($module);
        $this->setTable($table);
    }

    /*
    *  @private function getTemplatesAdminPagesHeader
    *  @param string $moduleDirname
    *  @return string
    */
    private function getTemplatesAdminPagesHeader($moduleDirname)
    {
        $ret = $this->htmlcode->getHtmlComment('Header').PHP_EOL;
        $ret .= $this->htmlcode->getSmartyIncludeFile($moduleDirname, 'header', true).PHP_EOL;

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
        $th = '';
        $langHeadId = strtoupper($tableSoleName).'_ID';
        if (1 == $tableAutoincrement) {
            $lang = $this->htmlcode->getSmartyConst($language, $langHeadId);
            $th  .= $this->htmlcode->getHtmlTag('th', array('class' => 'center'), $lang).PHP_EOL;
        }
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $rpFieldName = $this->tdmcfile->getRightString($fieldName);
            $langFieldName = strtoupper($tableSoleName).'_'.strtoupper($rpFieldName);
            if (1 == $fields[$f]->getVar('field_inlist')) {
                $lang = $this->htmlcode->getSmartyConst($language, $langFieldName);
                $th  .= $this->htmlcode->getHtmlTag('th', array('class' => 'center'), $lang).PHP_EOL;
            }
        }

        $lang = $this->htmlcode->getSmartyConst($language, 'FORM_ACTION');
        $th  .= $this->htmlcode->getHtmlTag('th', array('class' => 'center width5'), $lang).PHP_EOL;
        $tr   = $this->htmlcode->getHtmlTag('tr', array('class' => 'head'), $th).PHP_EOL;
        $ret  = $this->htmlcode->getHtmlTag('thead', array(), $tr).PHP_EOL;

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
        $td = '';
        if (1 == $tableAutoincrement) {
            $double = $this->htmlcode->getSmartyDoubleVar($tableSoleName, 'id');
            $td    .= $this->htmlcode->getHtmlTableData($double, 'center').PHP_EOL;
        }
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
                        $img    = $this->htmlcode->getHtmlTag('img', array('src' => $single."/images/{$tableName}/".$double, 'alt' => $tableName), '', false);
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
        $td     .= $this->htmlcode->getHtmlTag('td', array('class' => 'center  width5'), "\n".$anchor).PHP_EOL;
        $cycle = $this->htmlcode->getSmartyNoSimbol('cycle values="odd, even"');
        $tr = $this->htmlcode->getHtmlTag('tr', array('class' => $cycle), $td).PHP_EOL;
        $foreach = $this->htmlcode->getSmartyForeach($tableSoleName, $tableName.'_list', $tr).PHP_EOL;
        $tbody = $this->htmlcode->getHtmlTag('tbody', array(), $foreach).PHP_EOL;

        return $this->htmlcode->getSmartyConditions($tableName.'_count', '', '', $tbody).PHP_EOL;
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
        $tbody = $this->getTemplatesAdminPagesTableThead($tableSoleName, $tableAutoincrement, $fields, $language);
        $tbody .= $this->getTemplatesAdminPagesTableTBody($moduleDirname, $tableName, $tableSoleName, $tableAutoincrement, $fields);

        return $this->htmlcode->getHtmlTable($tbody, 'table table-bordered').PHP_EOL;
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
        $htmlTable = $this->getTemplatesAdminPagesTable($moduleDirname, $tableName, $tableSoleName, $tableAutoincrement, $fields, $language);
        $htmlTable .= $this->htmlcode->getHtmlTag('div', array('class' => 'clear'), '&nbsp;').PHP_EOL;
        $single = $this->htmlcode->getSmartySingleVar('pagenav');
        $div = $this->htmlcode->getHtmlTag('div', array('class' => 'xo-pagenav floatright'), $single);
        $div       .= $this->htmlcode->getHtmlTag('div', array('class' => 'clear spacer'), '').PHP_EOL;
        $htmlTable .= $this->htmlcode->getSmartyConditions('pagenav', '', '', $div).PHP_EOL;
        $ifList = $this->htmlcode->getSmartyConditions($tableName.'_list', '', '', $htmlTable).PHP_EOL;
        $single = $this->htmlcode->getSmartySingleVar('form');
        $divComm = $this->htmlcode->getHtmlComment('Display navigation').PHP_EOL;
        //$divComm .= $this->htmlcode->getHtmlTag('div', array('class' => 'errorMsg'), $single).PHP_EOL;
        $ifList .= $this->htmlcode->getSmartyConditions('form', '', '', $single).PHP_EOL;
        $single = $this->htmlcode->getSmartySingleVar('error');
        $strong = $this->htmlcode->getHtmlTag('strong', array(), $single).PHP_EOL;
        $div = $this->htmlcode->getHtmlTag('div', array('class' => 'errorMsg'), $strong).PHP_EOL;
        $ifList .= $this->htmlcode->getSmartyConditions('error', '', '', $div).PHP_EOL;

        return $ifList;
    }

    /*
    *  @private function getTemplatesAdminPagesFooter
    *  @param string $moduleDirname
    *  @return string
    */
    private function getTemplatesAdminPagesFooter($moduleDirname)
    {
        $ret = $this->htmlcode->getHtmlTag('br', array(), '', false).PHP_EOL;
        $ret .= $this->htmlcode->getHtmlComment('Footer').PHP_EOL;
        $ret .= $this->htmlcode->getSmartyIncludeFile($moduleDirname, 'footer', true);

        return $ret;
    }

    /*
    *  @public function render
    *  @param $filename
    *  @return bool|string
    */
    public function renderFile($filename)
    {
        $module = $this->getModule();
        $table = $this->getTable();
        $moduleDirname = $module->getVar('mod_dirname');
        $language = $this->getLanguage($moduleDirname, 'AM');
        $fields = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'), 'field_order');
        $content = $this->getTemplatesAdminPagesHeader($moduleDirname);
        $content .= $this->getTemplatesAdminPages($moduleDirname, $table->getVar('table_name'), $table->getVar('table_solename'), $table->getVar('table_autoincrement'), $fields, $language);
        $content .= $this->getTemplatesAdminPagesFooter($moduleDirname);
        //
        $this->tdmcfile->create($moduleDirname, 'templates/admin', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}
