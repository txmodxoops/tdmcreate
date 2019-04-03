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
 * @version         $Id: TemplatesUserCategories.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class TemplatesUserCategories.
 */
class TemplatesUserCategories extends TDMCreateFile
{
    /**
     *  @public function constructor
     *  @param null
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *  @static function getInstance
     *  @param null
     * @return TemplatesUserCategories
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
     *  @public function write
     *  @param string $module
     *  @param string $table
     *  @param string $filename
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /**
     *  @private function getTemplatesUserCategoriesHeader
     *  @param string $moduleDirname
     * @return string
     */
    private function getTemplatesUserCategoriesHeader($moduleDirname)
    {
        $hc = TDMCreateHtmlSmartyCodes::getInstance();

        return $hc->getSmartyIncludeFile($moduleDirname, 'header') . PHP_EOL;
    }

    /**
     *  @private function getTemplatesUserCategoriesTable
     *  @param string $language
     * @param $moduleDirname
     * @param $tableName
     * @param $tableSoleName
     * @return string
     */
    private function getTemplatesUserCategoriesTable($moduleDirname, $tableName, $tableSoleName, $language)
    {
        $hc = TDMCreateHtmlSmartyCodes::getInstance();
        $single = $hc->getSmartySingleVar('table_type');
        $table = $this->getTemplatesAdminPagesTableThead($tableName, $language);
        $table .= $this->getTemplatesAdminPagesTableTBody($moduleDirname, $tableName, $tableSoleName, $language);

        return $hc->getHtmlTable($table, 'table table-' . $single) . PHP_EOL;
    }

    /**
     *  @private function getTemplatesUserCategoriesThead
     *  @param string $language
     * @param $tableName
     * @return string
     */
    private function getTemplatesUserCategoriesThead($tableName, $language)
    {
        $hc = TDMCreateHtmlSmartyCodes::getInstance();
        $stuTableName = mb_strtoupper($tableName);
        $lang = $hc->getSmartyConst($language, $stuTableName . '_TITLE');
        $single = $hc->getSmartySingleVar('numb_col');
        $th = $hc->getHtmlTableHead($lang, '', $single) . PHP_EOL;
        $tr = $hc->getHtmlTableRow($th, 'head') . PHP_EOL;

        return $hc->getHtmlTableThead($tr) . PHP_EOL;
    }

    /**
     *  @private function getTemplatesUserCategoriesTbody
     *  @param string $moduleDirname
     *  @param string $language
     * @param $tableName
     * @param $tableSoleName
     * @return string
     */
    private function getTemplatesUserCategoriesTbody($moduleDirname, $tableName, $tableSoleName, $language)
    {
        $hc = TDMCreateHtmlSmartyCodes::getInstance();
        $single = $hc->getSmartySingleVar('panel_type');
        $include = $hc->getSmartyIncludeFileListForeach($moduleDirname, $tableName, $tableSoleName);
        $div = $hc->getHtmlDiv($include, 'panel panel-' . $single);
        $cont = $hc->getHtmlTableData($div) . PHP_EOL;
        $html = $hc->getHtmlEmpty('</tr><tr>') . PHP_EOL;
        $cont .= $hc->getSmartyConditions($tableSoleName . '.count', ' is div by ', '$divideby', $html) . PHP_EOL;
        $foreach = $hc->getSmartyForeach($tableSoleName, $tableName, $cont) . PHP_EOL;
        $tr = $hc->getHtmlTableRow($foreach) . PHP_EOL;

        return $hc->getHtmlTableTbody($tr) . PHP_EOL;
    }

    /**
     *  @private function getTemplatesUserCategoriesTfoot
     * @return string
     */
    private function getTemplatesUserCategoriesTfoot()
    {
        $hc = TDMCreateHtmlSmartyCodes::getInstance();
        $td = $hc->getHtmlTableData('&nbsp;') . PHP_EOL;
        $tr = $hc->getHtmlTableRow($td) . PHP_EOL;

        return $hc->getHtmlTableTfoot($tr) . PHP_EOL;
    }

    /**
     *  @private function getTemplatesUserCategories
     * @param $moduleDirname
     * @param $tableName
     * @param $tableSoleName
     * @param $language
     * @return string
     */
    private function getTemplatesUserCategories($moduleDirname, $tableName, $tableSoleName, $language)
    {
        $hc = TDMCreateHtmlSmartyCodes::getInstance();
        $tab = $this->getTemplatesUserCategoriesTable($moduleDirname, $tableName, $tableSoleName, $language) . PHP_EOL;
        $div = $hc->getHtmlDiv($tab, 'table-responsive') . PHP_EOL;

        return $hc->getSmartyConditions($tableName, ' gt ', '0', $div, false, true) . PHP_EOL;
    }

    /**
     *  @private function getTemplatesUserCategoriesPanel
     *  @param string $moduleDirname
     *  @param string $tableName
     * @param $tableSoleName
     * @param $language
     * @return string
     */
    private function getTemplatesUserCategoriesPanel($moduleDirname, $tableName, $tableSoleName, $language)
    {
        $stuTableName = mb_strtoupper($tableName);
        /*$ret = <<<EOT
<div class="panel panel-<{\$panel_type}>">
    <div class="panel-heading"><{\$smarty.const.{$language}{$stuTableName}_TITLE}></div>
        <{foreach item={$tableSoleName} from=\${$tableName}}>
            <div class="panel panel-body">
                <{include file="db:{$moduleDirname}_{$tableName}_list.tpl" {$tableSoleName}=\${$tableSoleName}}>
                <{if \${$tableSoleName}.count is div by \$numb_col}>
                    <br>
                <{/if}>
            </div>
        <{/foreach}>
</div>\n
EOT;*/
        $hc = TDMCreateHtmlSmartyCodes::getInstance();
        $incl = $hc->getSmartyIncludeFileListForeach($moduleDirname, $tableName, $tableSoleName) . PHP_EOL;
        $html = $hc->getHtmlEmpty('<br>') . PHP_EOL;
        $incl .= $hc->getSmartyConditions($tableSoleName . '.count', ' is div by ', '$numb_col', $html) . PHP_EOL;
        $const = $hc->getSmartyConst($language, $stuTableName . '_TITLE');
        $div = $hc->getHtmlDiv($const, 'panel-heading') . PHP_EOL;
        $cont = $hc->getHtmlDiv($incl, 'panel panel-body') . PHP_EOL;
        $div .= $hc->getSmartyForeach($tableSoleName, $tableName, $cont) . PHP_EOL;
        $panelType = $hc->getSmartySingleVar('panel_type');

        return $hc->getHtmlDiv($div, 'panel panel-' . $panelType) . PHP_EOL;
    }

    /**
     *  @private function getTemplatesUserCategoriesFooter
     *  @param string $moduleDirname
     *
     * @return string
     */
    private function getTemplatesUserCategoriesFooter($moduleDirname)
    {
        $hc = TDMCreateHtmlSmartyCodes::getInstance();

        return $hc->getSmartyIncludeFile($moduleDirname, 'footer');
    }

    /**
     *  @public function render
     * @return bool|string
     */
    public function render()
    {
        $module = $this->getModule();
        $table = $this->getTable();
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $tableName = $table->getVar('table_name');
        $tableSoleName = $table->getVar('table_solename');
        $tableFieldname = $table->getVar('table_fieldname');
        $language = $this->getLanguage($moduleDirname, 'MA');
        $content = $this->getTemplatesUserCategoriesHeader($moduleDirname);
        $content .= $this->getTemplatesUserCategoriesPanel($moduleDirname, $tableName, $tableSoleName, $language);
        /*
        $content .= $this->getTemplatesUserCategories($moduleDirname, $tableName, $tableSoleName, $language);*/
        $content .= $this->getTemplatesUserCategoriesFooter($moduleDirname);

        $this->create($moduleDirname, 'templates', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
