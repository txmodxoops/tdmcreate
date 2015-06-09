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
 * @version         $Id: templates_index.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class TemplatesUserIndex
 */
class TemplatesUserIndex extends TDMCreateHtmlSmartyCodes
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
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return TemplatesUserIndex
     */
    public static function &getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * @public function write
     *  
     * @param $module
	 * @param $table
     * @param $filename
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
		$this->setTable($table);
        $this->setFileName($filename);
    }

    /*
    *  @public function getTemplateUserIndexHeader
    *  @param $moduleDirname
    */
    /**
     * @return bool|string
     */
    public function getTemplateUserIndexHeader($moduleDirname)
    {
        $ret = <<<EOT
<{include file="db:{$moduleDirname}_header.tpl"}>\n
EOT;

        return $ret;
    }
	
	/*
    *  @public function getTemplatesUserIndexBodyDefault
    *  @param null
    */
    /**
     * @return bool|string
     */
    public function getTemplatesUserIndexBodyDefault($module, $table, $language)
    {       
        $moduleDirname = $module->getVar('mod_dirname');
		$tableName     = $table->getVar('table_name');
		$ret = <<<EOT
<{if count(\${$tableName}) == 0}>
<table class="table table-<{\$table_type}>">
    <thead>
        <tr class="center">
            <th><{\$smarty.const.{$language}TITLE}>  -  <{\$smarty.const.{$language}DESC}></th>
        </tr>
    </thead>
    <tbody>
        <tr class="center">
            <td class="bold pad5">
                <ul class="menu">
                    <li><a href="<{\${$moduleDirname}_url}>"><{\$smarty.const.{$language}INDEX}></a></li>\n
EOT;
        $tables = $this->getTableTables($module->getVar('mod_id'), 'table_order ASC');
		foreach (array_keys($tables) as $i) {
            $tableName    = $tables[$i]->getVar('table_name');
            $stuTableName = strtoupper($tableName);
            $ret .= <<<EOT
                    <li> | </li>
                    <li><a href="<{\${$moduleDirname}_url}>/{$tableName}.php"><{\$smarty.const.{$language}{$stuTableName}}></a></li>\n
EOT;
        }
        $ret .= <<<EOT
                </ul>
				<div class="justify pad5"><{\$smarty.const.{$language}INDEX_DESC}></div>
            </td>
        </tr>
    </tbody>
    <tfoot>
    <{if \$adv != ''}>
        <tr class="center"><td class="center bold pad5"><{\$adv}></td></tr>
    <{else}>
        <tr class="center"><td class="center bold pad5">&nbsp;</td></tr>
    <{/if}>
    </tfoot>
</table>
<{/if}>\n
EOT;

        return $ret;
    }
	
	/*
    *  @public function getTemplateUserIndexCategories
    *  @param null
    */
    /**
     * @return bool|string
     */
    public function getTemplateUserIndexCategories($moduleDirname, $tableName, $tableSoleName, $language)
    {
		$stuTableName   = strtoupper($tableName);
		$ret = <<<EOT
<{if count(\${$tableName}) gt 0}>
<div class="table-responsive">
    <table class="table table-<{\$type}>">
		<thead>
			<tr>
				<th><{\$smarty.const.{$language}{$stuTableName}_TITLE}></th>
			</tr>
		</thead>
		<tbody>
			<tr>
			<{foreach item={$tableSoleName} from=\${$tableName}}>
				<td>
					<{include file="db:{$moduleDirname}_{$tableName}_list.tpl" list=\${$tableSoleName}}>
				</td>
				<{if \${$tableSoleName}.count is div by \$divideby}>
				</tr><tr>
				<{/if}>
			<{/foreach}>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td class="{$tableSoleName}-thereare"><{\$lang_thereare}></td>
			</tr>
		</tfoot>
	</table>
</div>
<{/if}>\n
EOT;
       
        return $ret;
    }
	
	/*
    *  @public function getTemplateUserIndexTable
    *  @param null
    */
    /**
     * @return bool|string
     */
    public function getTemplateUserIndexTable($moduleDirname, $tableName, $language)
    {
		$ret = <<<EOT
<{if count(\${$tableName}) gt 0}>
	<!-- Start Show new {$tableName} in index -->
	<div class="{$moduleDirname}-linetitle"><{\$smarty.const.{$language}INDEX_LATEST_LIST}></div>
	<table class="table table-bordered">
		<tr>
			<!-- Start new link loop -->
			<{section name=i loop=\${$tableName}}>
				<td class="col_width<{\$numb_col}> top center">
					<{include file="db:{$moduleDirname}_{$tableName}_list.tpl" list=\${$tableName}[i]}>
				</td>
	<{if \${$tableName}[i].count is div by \$divideby}>
		</tr><tr>
	<{/if}>
			<{/section}>
	<!-- End new link loop -->
		</tr>
	</table>
<!-- End Show new files in index -->
<{/if}>\n
EOT;
       
        return $ret;
    }
	
	/*
    *  @public function getTemplateUserIndexFooter
    *  @param $moduleDirname
    */
    /**
     * @return bool|string
     */
    public function getTemplateUserIndexFooter($moduleDirname)
    {
        $ret = <<<EOT
<{include file="db:{$moduleDirname}_footer.tpl"}>
EOT;

        return $ret;
    }
	
	/*
    *  @public function render
    *  @param null
    */
    /**
     * @return bool|string
     */
    public function render()
    {
        $module        = $this->getModule();
		$table         = $this->getTable();
		$tables        = $this->getTableTables($module->getVar('mod_id'));
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language      = $this->getLanguage($moduleDirname, 'MA');
        $content       = $this->getTemplateUserIndexHeader($moduleDirname);
		$content .= $this->getTemplatesUserIndexBodyDefault($module, $table, $language);
		foreach(array_keys($tables) as $t) {
			$tableName      = $tables[$t]->getVar('table_name');
			$tableSoleName  = $tables[$t]->getVar('table_solename');
			$tableCategory  = $tables[$t]->getVar('table_category');
			$tableFieldname = $tables[$t]->getVar('table_fieldname');
			$tableIndex     = $tables[$t]->getVar('table_index');
			if((1 == $tableCategory) && (1 == $tableIndex)) {
				$content .= $this->getTemplateUserIndexCategories($moduleDirname, $tableName, $tableSoleName, $language);
			}
			if((0 == $tableCategory) && (1 == $tableIndex)) {
				$content .= $this->getTemplateUserIndexTable($moduleDirname, $tableName, $language);
			}
		}  
		$content .= $this->getTemplateUserIndexFooter($moduleDirname);
		
        $this->tdmcfile->create($moduleDirname, 'templates', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}
