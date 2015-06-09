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
 * @version         $Id: TemplatesUserHeader.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class TemplatesUserHeader
 */
class TemplatesUserHeader extends TDMCreateHtmlSmartyCodes
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
     * @return TemplatesUserHeader
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
    *  @param mixed $tables
    *  @param string $filename
    */
    /**
     * @param $module
     * @param $tables
     * @param $filename
     */
    public function write($module, $table, $tables, $filename)
    {
        $this->setModule($module);
		$this->setTable($table);
        $this->setTables($tables);
        $this->setFileName($filename);
    }
	
	/*
    *  @public function getTemplatesUserHeaderTop
    *  @param null
    */
    /**
     * @return bool|string
     */
    public function getTemplatesUserHeaderTop($moduleDirname)
    {       
        $ret = <<<EOT
<{includeq file="db:xmodules_breadcrumbs.tpl"}>
<{if \$adv != ''}>
    <div class="center"><{\$adv}></div>
<{/if}>\n
EOT;

		return $ret;
	}
	
	/*
    *  @private function getTemplatesUserHeaderStartTable
    *  @param string $table
    */
    /**
     * @param $table
     * @return string
     */
    private function getTemplatesUserHeaderStartTable($table)
    {
        $tableName = $table->getVar('table_name');
		$ret       = <<<EOT
<{if count(\${$tableName}) gt 0}>
<div class="table-responsive">   
	<table class="table table-<{\$table_type}>">\n
EOT;
        
        return $ret;
    }
	
	/*
    *  @private function getTemplatesUserHeaderThead
    *  @param string $language
    */
    /**
     * @param $language
     * @return string
     */
    private function getTemplatesUserHeaderThead($table, $language)
    {
        $tableName    = $table->getVar('table_name');
		$stuTableName = strtoupper($tableName);
		$ret = <<<EOT
		<thead>
			<tr>
				<th><{\$smarty.const.{$language}{$stuTableName}_TITLE}></th>
			</tr>
		</thead>\n
EOT;
        return $ret;
    }

    /*
    *  @private function getTemplatesUserHeaderTbody
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
    private function getTemplatesUserHeaderTbody($moduleDirname, $table)
    {
        $tableName      = $table->getVar('table_name');
		$tableFieldName = $table->getVar('table_fieldname');
        $ret       		= <<<EOT
		<tbody>
			<tr>
			<{foreach item={$tableFieldName} from=\${$tableName}}>
				<td>
					<{include file="db:{$moduleDirname}_{$tableName}_list.tpl" list=\${$tableFieldName}}>
				</td>
				<{if \${$tableFieldName}.count is div by \$divideby}>
				</tr><tr>
				<{/if}>
			<{/foreach}>
			</tr>
		</tbody>\n
EOT;

        return $ret;
    }

    /*
    *  @private function getTemplatesUserHeaderTfoot
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
    private function getTemplatesUserHeaderTfoot($table)
    {
        $tableName = $table->getVar('table_name');
        $ret       = <<<EOT
		<tfoot>
			<tr>
				<td class="pull-right"><{\$lang_thereare}></td>
			</tr>
		</tfoot>\n
EOT;
        
		return $ret;
    }

    /*
    *  @private function getTemplatesUserHeaderEndTable
    *  @param null
    */
    /**
     * @param null
     * @return string
     */
    private function getTemplatesUserHeaderEndTable()
    {
        $ret = <<<EOT
	</table>
</div>
<{/if}>\n
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
		$tables        = $this->getTables();
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language      = $this->getLanguage($moduleDirname, 'MA');
        $content       = $this->getTemplatesUserHeaderTop($moduleDirname);		
		/*if (1 == $table->getVar('table_category')) {
			$content .= $this->getTemplatesUserHeaderStartTable($table);
			$content .= $this->getTemplatesUserHeaderThead($table, $language);
			$content .= $this->getTemplatesUserHeaderTbody($moduleDirname, $table);
			$content .= $this->getTemplatesUserHeaderTfoot($table);
			$content .= $this->getTemplatesUserHeaderEndTable();
		}*/
        $this->tdmcfile->create($moduleDirname, 'templates', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}
