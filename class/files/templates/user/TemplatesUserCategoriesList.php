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
 * @version         $Id: TemplatesUserCategoriesList.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class TemplatesUserCategoriesList.
 */
class TemplatesUserCategoriesList extends TDMCreateHtmlSmartyCodes
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
     * @return TemplatesUserCategoriesList
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
    *  @private function getTemplatesUserCategoriesListHeader
    *  @param string $language
    */
    /**
     * @param $language
     *
     * @return string
     */
    private function getTemplatesUserCategoriesListStartTable()
    {
        $ret = <<<EOT
<div class="table-responsive">
    <table class="table table-<{\$type}>">\n
EOT;

        return $ret;
    }

    /*
    *  @private function getTemplatesUserCategoriesListThead
    *  @param string $language
    */
    /**
     * @param $language
     *
     * @return string
     */
    private function getTemplatesUserCategoriesListThead($table, $language)
    {
        $ret = <<<EOT
		<thead>
			<tr>\n
EOT;
        $fields = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        foreach (array_keys($fields) as $f) {
            if (1 == $fields[$f]->getVar('field_user')) {
                if (1 == $fields[$f]->getVar('field_thead')) {
                    $fieldName = $fields[$f]->getVar('field_name');
                    $rpFieldName = $this->tdmcfile->getRightString($fieldName);
                    $ret .= <<<EOT
				<th><{\$list.{$rpFieldName}}></th>\n
EOT;
                }
            }
        }
        $ret .= <<<EOT
			</tr>
		</thead>\n
EOT;

        return $ret;
    }

    /*
    *  @private function getTemplatesUserCategoriesListTbody
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
    private function getTemplatesUserCategoriesListTbody($moduleDirname, $table, $language)
    {
        $tableName = $table->getVar('table_name');
        $ret = <<<EOT
		<tbody>
			<tr>\n
EOT;
        $fields = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        foreach (array_keys($fields) as $f) {
            $fieldElement = $fields[$f]->getVar('field_element');
            if (1 == $fields[$f]->getVar('field_user')) {
                if (1 == $fields[$f]->getVar('field_tbody')) {
                    switch ($fieldElement) {
                        default:
                        case 10:
                            $fieldName = $fields[$f]->getVar('field_name');
                            $rpFieldName = $this->tdmcfile->getRightString($fieldName);
                            $ret .= <<<EOT
				<td class="center pad5"><img src="<{\$xoops_icons32_url}>/<{\$list.{$rpFieldName}}>" alt="{$tableName}" /></td>\n
EOT;
                            break;
                        case 13:
                            $fieldName = $fields[$f]->getVar('field_name');
                            $rpFieldName = $this->tdmcfile->getRightString($fieldName);
                            $ret .= <<<EOT
				<td class="center pad5"><img src="<{\${$moduleDirname}_upload_url}>/images/{$tableName}/<{\$list.{$rpFieldName}}>" alt="{$tableName}" /></td>\n
EOT;
                            break;
                        case 2:
                        case 3:
                        case 4:
                            $fieldName = $fields[$f]->getVar('field_name');
                            $rpFieldName = $this->tdmcfile->getRightString($fieldName);
                            $ret .= <<<EOT
				<td class="justify pad5"><{\$list.{$rpFieldName}}></td>\n
EOT;
                            break;
                    }
                }
            }
        }
        $ret .= <<<EOT
			</tr>
		</tbody>\n
EOT;

        return $ret;
    }

    /*
    *  @private function getTemplatesUserCategoriesListTfoot
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
    private function getTemplatesUserCategoriesListTfoot($table, $language)
    {
        $tableName = $table->getVar('table_name');
        $fields = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        $ret = <<<EOT
		<tfoot>
			<tr>\n
EOT;

        foreach (array_keys($fields) as $f) {
            if (1 == $fields[$f]->getVar('field_user')) {
                if (1 == $fields[$f]->getVar('field_tfoot')) {
                    $fieldName = $fields[$f]->getVar('field_name');
                    $rpFieldName = $this->tdmcfile->getRightString($fieldName);
                    $ret .= <<<EOT
				<td class="center"><{\$list.{$rpFieldName}}></td>\n
EOT;
                }
            }
        }
        $ret .= <<<EOT
			</tr>
		</tfoot>\n
EOT;

        return $ret;
    }

    /*
    *  @private function getTemplatesUserCategoriesListEndTable
    *  @param null
    */
    /**
     * @param null
     *
     * @return string
     */
    private function getTemplatesUserCategoriesListEndTable()
    {
        $ret = <<<EOT
	</table>
</div>\n
EOT;

        return $ret;
    }

    /*
    *  @private function getTemplatesUserCategoriesListPanel
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
    private function getTemplatesUserCategoriesListPanel($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $language)
    {
        $fields = $this->getTableFields($tableMid, $tableId);
        $ret = '';
        $retElem = '';
        foreach (array_keys($fields) as $f) {
            $fieldElement = $fields[$f]->getVar('field_element');
            if (1 == $fields[$f]->getVar('field_user')) {
                if (1 == $fields[$f]->getVar('field_tbody')) {
                    switch ($fieldElement) {
                        default:
                        case 2:
                            $fieldName = $fields[$f]->getVar('field_name');
                            $rpFieldName = $this->tdmcfile->getRightString($fieldName);
                            $doubleVar = $this->htmlcode->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $retElem .= $this->htmlcode->getHtmlSpan($doubleVar, 'col-sm-2').PHP_EOL;
                            break;
                        case 10:
                            $fieldName = $fields[$f]->getVar('field_name');
                            $rpFieldName = $this->tdmcfile->getRightString($fieldName);
                            $singleVar = $this->htmlcode->getSmartySingleVar('xoops_icons32_url');
                            $doubleVar = $this->htmlcode->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $img = $this->htmlcode->getHtmlImage($singleVar.'/'.$doubleVar, "{$tableName}");
                            $retElem .= $this->htmlcode->getHtmlSpan($img, 'col-sm-3').PHP_EOL;
                            unset($img);
                            break;
                        case 13:
                            $fieldName = $fields[$f]->getVar('field_name');
                            $rpFieldName = $this->tdmcfile->getRightString($fieldName);
                            $singleVar = $this->htmlcode->getSmartySingleVar($moduleDirname.'_upload_url');
                            $doubleVar = $this->htmlcode->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $img = $this->htmlcode->getHtmlImage($singleVar."/images/{$tableName}/".$doubleVar, "{$tableName}");
                            $retElem .= $this->htmlcode->getHtmlSpan($img, 'col-sm-3').PHP_EOL;
                            unset($img);
                            break;
                        case 3:
                        case 4:
                            $fieldName = $fields[$f]->getVar('field_name');
                            $rpFieldName = $this->tdmcfile->getRightString($fieldName);
                            $doubleVar = $this->htmlcode->getSmartyDoubleVar($tableSoleName, $rpFieldName);
                            $retElem .= $this->htmlcode->getHtmlSpan($doubleVar, 'col-sm-3 justify').PHP_EOL;
                            break;
                    }
                }
            }
        }
        $ret .= $this->htmlcode->getHtmlDiv($retElem, 'panel-body').PHP_EOL;

        return $ret;
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
        $tables = $this->getTableTables($module->getVar('mod_id'), 'table_order');
        $moduleDirname = $module->getVar('mod_dirname');
        //$tableFieldname = $table->getVar('table_fieldname');
        $language = $this->getLanguage($moduleDirname, 'MA');
        $content = '';
        foreach (array_keys($tables) as $t) {
            $tableId = $tables[$t]->getVar('table_id');
            $tableMid = $tables[$t]->getVar('table_mid');
            $tableName = $tables[$t]->getVar('table_name');
            $tableSoleName = $tables[$t]->getVar('table_solename');
            $tableCategory = $tables[$t]->getVar('table_category');
            $tableFieldname = $tables[$t]->getVar('table_fieldname');
            $tableIndex = $tables[$t]->getVar('table_index');
            if (1 == $tableCategory) {
                $content .= $this->getTemplatesUserCategoriesListPanel($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $language);
            }
        }
        /*$content        = $this->getTemplatesUserCategoriesListStartTable();
        $content .= $this->getTemplatesUserCategoriesListThead($table, $language);
        $content .= $this->getTemplatesUserCategoriesListTbody($moduleDirname, $table, $language);
        $content .= $this->getTemplatesUserCategoriesListTfoot($table, $language);
        $content .= $this->getTemplatesUserCategoriesListEndTable();*/
        //$content = $this->getTemplatesUserCategoriesListPanel($moduleDirname, $table);
        //
        $this->tdmcfile->create($moduleDirname, 'templates', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}
