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
 * @version         $Id: TemplatesUserPages.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class TemplatesUserPages
 */
class TemplatesUserPages extends TDMCreateFile
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
     * @return TemplatesUserPages
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
    *  @private function getTemplatesUserPagesHeader
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
    private function getTemplatesUserPagesHeader($moduleDirname, $table, $language)
    {
        $ret    = <<<EOT
<{include file="db:{$moduleDirname}_header.tpl"}>\n
EOT;

        return $ret;
    }

    /*
    *  @private function getTemplatesUserPagesStartTable
    *  @param string $language
    */
    /**
     * @param $language
     * @return string
     */
    private function getTemplatesUserPagesStartTable($table)
    {
        $tableName = $table->getVar('table_name');
		$ret = <<<EOT
<{if count(\${$tableName}) gt 0}>
<div class="table-responsive">
    <table class="table table-<{\$type}>">\n
EOT;
        
        return $ret;
    }
	
	/*
    *  @private function getTemplatesUserPagesThead
    *  @param string $language
    */
    /**
     * @param $language
     * @return string
     */
    private function getTemplatesUserPagesThead($table, $language)
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
    *  @private function getTemplatesUserPagesTbody
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
    private function getTemplatesUserPagesTbody($moduleDirname, $table, $language)
    {
        $tableName     = $table->getVar('table_name');
		$tableSoleName = $table->getVar('table_solename');
        $ret       	   = <<<EOT
		<tbody>
			<tr>
			<{foreach item={$tableSoleName} from=\${$tableName}}>
				<td>
					<div class="panel panel-default">
						<{include file="db:{$moduleDirname}_{$tableName}_list.tpl" list=\${$tableSoleName}}>
					</div>
				</td>
				<{if \${$tableSoleName}.count eq \$divideby}>
				</tr><tr>
				<{/if}>
			<{/foreach}>
			</tr>
		</tbody>\n
EOT;

        return $ret;
    }

    /*
    *  @private function getTemplatesUserPagesTfoot
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
    private function getTemplatesUserPagesTfoot($table, $language)
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
    *  @private function getTemplatesUserPagesEndTable
    *  @param null
    */
    /**
     * @param null
     * @return string
     */
    private function getTemplatesUserPagesEndTable()
    {
        $ret = <<<EOT
	</table>
</div>
<{/if}>\n
EOT;

        return $ret;
    }

    /*
    *  @private function getTemplatesUserPagesFooter
    *  @param string $moduleDirname
    */
    /**
     * @param $moduleDirname
     * @return string
     */
    private function getTemplatesUserPagesFooter($moduleDirname)
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
        $tableFieldname = $table->getVar('table_fieldname');
        $language       = $this->getLanguage($moduleDirname, 'MA');
        $content        = $this->getTemplatesUserPagesHeader($moduleDirname, $table, $language);
		$content .= $this->getTemplatesUserPagesStartTable($table);
		$content .= $this->getTemplatesUserPagesThead($table, $language);
        // Verify if table_fieldname is not empty
        if (!empty($tableFieldname)) {
            $content .= $this->getTemplatesUserPagesTbody($moduleDirname, $table, $language);
        } else {
            $content .= $this->getTemplatesUserPagesTbody($moduleDirname, $table, $language);
        }
        $content .= $this->getTemplatesUserPagesTfoot($table, $language);
		$content .= $this->getTemplatesUserPagesEndTable();
		$content .= $this->getTemplatesUserPagesFooter($moduleDirname);
        //
        $this->tdmcfile->create($moduleDirname, 'templates', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}
