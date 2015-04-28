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
 * @version         $Id: TemplatesBlocks.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

class TemplatesBlocks extends TDMCreateFile
{	
	/*
	*  @public function constructor
	*  @param null
	*/
	public function __construct() { 
		parent::__construct();
		$this->tdmcfile = TDMCreateFile::getInstance();		
	}
	/*
	*  @static function &getInstance
	*  @param null
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
	public function write($module, $table) {    
		$this->setModule($module);
		$this->setTable($table);
	}
	/*
	*  @private function getTemplatesBlocksHeader
	*  @param string $moduleDirname
	*  @param string $table
	*  @param string $language
	*/
	private function getTemplatesBlocksHeader($moduleDirname, $table, $language) {    
		$tableName = $table->getVar('table_name');
		$ret = <<<EOT
<table class="{$tableName} width100">
	<thead>
		<tr class="head">\n
EOT;
		$fields = $this->getTableFields($table->getVar('table_id'));
		foreach(array_keys($fields) as $f) 
		{
			$fieldName = $fields[$f]->getVar('field_name');			
			$lang_stu_field_name = $language.strtoupper($fieldName);
			$ret .= <<<EOT
			<th class="center"><{\$smarty.const.{$lang_stu_field_name}}></th>\n
EOT;
		}
		$ret .= <<<EOT
		</tr>
	</thead>\n	
EOT;
		return $ret;
	}
	/*
	*  @private function getTemplatesBlocksBody
	*  @param string $moduleDirname
	*  @param string $table
	*  @param string $language
	*/
	private function getTemplatesBlocksBody($moduleDirname, $table, $language) 
	{    
		$tableName = $table->getVar('table_name');
		$ret = <<<EOT
	<tbody>
		<{foreach item=list from=\${$tableName}}>	
			<tr class="<{cycle values='odd, even'}>">\n
EOT;
		$fields = $this->getTableFields($table->getVar('table_id'));
		foreach(array_keys($fields) as $f) 
		{
			$fieldName = $fields[$f]->getVar('field_name');
			$fieldElement = $fields[$f]->getVar('field_element');
			$rpFieldName = $this->tdmcfile->getRightString($fieldName);
			switch( $fieldElement ) {			    
				case 9:			
					$ret .= <<<EOT
				<td class="center"><span style="background-color: #<{\$list.{$rpFieldName}}>;">\t\t</span></td>\n		
EOT;
				break;
				case 10:
					$ret .= <<<EOT
				<td class="center">
					<img src="<{xoModuleIcons32}><{\$list.{$rpFieldName}}>" alt="{$tableName}">
				</td>\n
EOT;
				break;
				case 13:
					$ret .= <<<EOT
				<td class="center">
					<img src="<{\${$moduleDirname}_upload_url}>/images/{$tableName}/<{\$list.{$rpFieldName}}>" alt="{$tableName}">
				</td>\n
EOT;
				break;
				default:
					$ret .= <<<EOT
				<td class="center"><{\$list.{$rpFieldName}}></td>\n
EOT;
				break;
			}			
		}
		$ret .= <<<EOT
			</tr>
		<{/foreach}>
    </tbody>
</table>
EOT;
		return $ret;
	}
	/*
	*  @private function getTemplatesBlocksBodyFieldnameEmpty
	*  @param string $moduleDirname
	*  @param string $table
	*  @param string $language
	*/
	private function getTemplatesBlocksBodyFieldnameEmpty($moduleDirname, $table, $language) 
	{ 		
		$tableName = $table->getVar('table_name');		
		$ret = <<<EOT
	<tbody>
		<{foreach item=list from=\${$tableName}}>	
			<tr class="<{cycle values='odd, even'}>">\n
EOT;
		$fields = $this->getTableFields($table->getVar('table_id'));
		foreach(array_keys($fields) as $f) 
		{
			$fieldName = $fields[$f]->getVar('field_name');
			$fieldElement = $fields[$f]->getVar('field_element');			
			switch( $fieldElement ) {			    
				case 9:			
					$ret .= <<<EOT
				<td class="center"><span style="background-color: #<{\$list.{$fieldName}}>;">\t\t</span></td>\n	
EOT;
				break;
				case 10:
					$ret .= <<<EOT
				<td class="center">
					<img src="<{xoModuleIcons32}><{\$list.{$fieldName}}>" alt="{$tableName}">
				</td>\n
EOT;
				break;
				case 13:
					$ret .= <<<EOT
				<td class="center">
					<img src="<{\${$moduleDirname}_upload_url}>/images/{$tableName}/<{\$list.{$fieldName}}>" alt="{$tableName}">
				</td>\n
EOT;
				break;
				default:
					$ret .= <<<EOT
				<td class="center"><{\$list.{$fieldName}}></td>\n
EOT;
				break;
			}			
		}
		$ret .= <<<EOT
			</tr>
		<{/foreach}>
    </tbody>
</table>
EOT;
		return $ret;
	}	
	/*
	*  @public function renderFile
	*  @param string $filename
	*/
	public function renderFile($filename) {    
		$module = $this->getModule();		
		$table = $this->getTable();
		$moduleDirname = $module->getVar('mod_dirname');
		$tableName = $table->getVar('table_name');
		$tableFieldname = $table->getVar('table_fieldname');
        $language = $this->getLanguage($moduleDirname, 'MB');		
		$content = $this->getTemplatesBlocksHeader($moduleDirname, $table, $language);
		// Verify if table_fieldname is not empty
		if(!empty($tableFieldname)) {
			$content .= $this->getTemplatesBlocksBody($moduleDirname, $table, $language);
		} else {
			$content .= $this->getTemplatesBlocksBodyFieldnameEmpty($moduleDirname, $table, $language);
		}
		//$content .= $this->getTemplatesBlocksFooter($moduleDirname);
		//
		$this->tdmcfile->create($moduleDirname, 'templates/blocks', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);
		return $this->tdmcfile->renderFile();
	}
}