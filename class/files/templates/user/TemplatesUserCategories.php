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
 * tdmcreate module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         tdmcreate
 * @since           2.5.0
 * @author          Txmod Xoops http://www.txmodxoops.org
 * @version         $Id: TemplatesUserCategories.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class TemplatesUserCategories
 */
class TemplatesUserCategories extends TDMCreateHtmlSmartyCodes
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
     * @return TemplatesUserCategories
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
    *  @private function getTemplatesUserCategoriesHeader
    *  @param string $moduleDirname
    *  @param string $table
    *  @param string $language
    */
    /**
     * @param $moduleDirname
     * @param $table
     * @param $language
     * @return string
     */
    private function getTemplatesUserCategoriesHeader($moduleDirname)
    {
        $ret    = <<<EOT
<{include file="db:{$moduleDirname}_header.tpl"}>\n
EOT;

        return $ret;
    }

    /*
    *  @private function getTemplatesUserCategoriesStartTable
    *  @param string $language
    */
    /**
     * @param $language
     * @return string
     */
    private function getTemplatesUserCategoriesStartTable($table)
    {
        $tableName = $table->getVar('table_name');
		$ret = <<<EOT
<{if count(\${$tableName}) gt 0}>
<div class="table-responsive">
    <table class="table table-<{\$table_type}>">\n
EOT;
        
        return $ret;
    }
	
	/*
    *  @private function getTemplatesUserCategoriesThead
    *  @param string $language
    */
    /**
     * @param $language
     * @return string
     */
    private function getTemplatesUserCategoriesThead($table, $language)
    {
        $tableName    = $table->getVar('table_name');
		$stuTableName = strtoupper($tableName);
		$ret = <<<EOT
		<thead>
			<tr>
				<th colspan="<{\$colSpanHead}>"><{\$smarty.const.{$language}{$stuTableName}_TITLE}></th>
			</tr>
		</thead>\n
EOT;
        return $ret;
    }

    /*
    *  @private function getTemplatesUserCategoriesTbody
    *  @param string $moduleDirname
    *  @param string $table
    *  @param string $language
    */
    /**
     * @param $moduleDirname
     * @param $table
     * @param $language
     * @return string
     */
    private function getTemplatesUserCategoriesTbody($moduleDirname, $tableName, $tableSolename, $language)
    {
        $ret = <<<EOT
		<tbody>
			<tr>
			<{foreach item={$tableSolename} from=\${$tableName}}>
				<td>
					<div class="panel panel-<{\$panel_type}>">
						<{include file="db:{$moduleDirname}_{$tableName}_list.tpl" {$tableSolename}=\${$tableSolename}}>
					</div>
				</td>
				<{if \${$tableSolename}.count eq \$divideby}>
				</tr><tr>
				<{/if}>
			<{/foreach}>
			</tr>
		</tbody>\n
EOT;

        return $ret;
    }

    /*
    *  @private function getTemplatesUserCategoriesTfoot
    *  @param string $moduleDirname
    *  @param string $table
    *  @param string $language
    */
    /**
     * @param $moduleDirname
     * @param $table
     * @param $language
     * @return string
     */
    private function getTemplatesUserCategoriesTfoot($table, $language)
    {
        $tableName = $table->getVar('table_name');
        $ret       = <<<EOT
		<tfoot>
			<tr>
				<td>&nbsp;</td>
			</tr>
		</tfoot>\n
EOT;
        
		return $ret;
    }

    /*
    *  @private function getTemplatesUserCategoriesEndTable
    *  @param null
    */
    /**
     * @param null
     * @return string
     */
    private function getTemplatesUserCategoriesEndTable()
    {
        $ret = <<<EOT
	</table>
</div>
<{/if}>\n
EOT;

        return $ret;
    }
	
	/*
    *  @private function getTemplatesUserCategoriesPanel
    *  @param string $moduleDirname
    *  @param string $table
    *  @param string $language
    */
    /**
     * @param $moduleDirname
     * @param $table
     * @param $language
     * @return string
     */
    private function getTemplatesUserCategoriesPanel($moduleDirname, $tableName, $tableSolename, $language)
    {
        $stuTableName = strtoupper($tableName);
		$ret          = <<<EOT
<div class="panel panel-<{\$panel_type}>">	
	<div class="panel-heading"><{\$smarty.const.{$language}{$stuTableName}_TITLE}></div>	
		<{foreach item={$tableSolename} from=\${$tableName}}>
			<div class="panel panel-body">
				<{include file="db:{$moduleDirname}_{$tableName}_list.tpl" {$tableSolename}=\${$tableSolename}}>							
				<{if \${$tableSolename}.count is div by \$numb_col}>
					<br />
				<{/if}>
			</div>
		<{/foreach}>
</div>\n
EOT;

        return $ret;
    }

    /*
    *  @private function getTemplatesUserCategoriesFooter
    *  @param string $moduleDirname
    */
    /**
     * @param $moduleDirname
     * @return string
     */
    private function getTemplatesUserCategoriesFooter($moduleDirname)
    {
        $ret = <<<EOT
<{include file="db:{$moduleDirname}_footer.tpl"}>
EOT;

        return $ret;
    }

    /*
    *  @public function renderFile
    *  @param string $filename
    */
    /**
     * @param $filename
     * @return bool|string
     */
    public function renderFile($filename)
    {
        $module         = $this->getModule();
        $table          = $this->getTable();
        $moduleDirname  = $module->getVar('mod_dirname');
		$tableName      = $table->getVar('table_name');
        $tableSolename  = $table->getVar('table_solename');
		$tableFieldname = $table->getVar('table_fieldname');
        $language       = $this->getLanguage($moduleDirname, 'MA');
        $content        = $this->getTemplatesUserCategoriesHeader($moduleDirname);
		$content .= $this->getTemplatesUserCategoriesPanel($moduleDirname, $tableName, $tableSolename, $language);
		/*$content .= $this->getTemplatesUserCategoriesStartTable($table);
		$content .= $this->getTemplatesUserCategoriesThead($table, $language);
        $content .= $this->getTemplatesUserCategoriesTbody($moduleDirname, $tableName, $tableSolename, $language);
		$content .= $this->getTemplatesUserCategoriesTfoot($table, $language);
		$content .= $this->getTemplatesUserCategoriesEndTable();*/
		$content .= $this->getTemplatesUserCategoriesFooter($moduleDirname);
        //
        $this->tdmcfile->create($moduleDirname, 'templates', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}
