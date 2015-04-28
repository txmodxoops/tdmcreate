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
 * @version         $Id: blocks_pages.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

class BlocksFiles extends TDMCreateFile
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
	*  @param mixed $table
	*/
	public function write($module, $table) {    
		$this->setModule($module);
		$this->setTable($table);
	}
	/*
	*  @public function getBlocksShow
	*  @param null
	*/
	public function getBlocksShow($moduleDirname, $tableName, $tableFieldname, $tableCategory, $fields, $fpif) {		
		$stuModuleDirname = strtoupper($moduleDirname);
		$ucfModuleDirname = ucfirst($moduleDirname);
		$ret = <<<EOT
include_once XOOPS_ROOT_PATH.'/modules/{$moduleDirname}/include/common.php';
// Function show block
function b_{$moduleDirname}_{$tableName}_show(\$options) 
{
	include_once XOOPS_ROOT_PATH.'/modules/{$moduleDirname}/class/{$tableName}.php';
	\$myts =& MyTextSanitizer::getInstance();
    \$GLOBALS['xoopsTpl']->assign('{$moduleDirname}_upload_url', {$stuModuleDirname}_UPLOAD_URL);
	\${$tableFieldname} = array();
	\$type_block = \$options[0];
	\$nb_{$tableName} = \$options[1];
	\$lenght_title = \$options[2];
	\${$moduleDirname} = {$ucfModuleDirname}Helper::getInstance();
	\${$tableName}Handler =& \${$moduleDirname}->getHandler('{$tableName}');
	\$criteria = new CriteriaCompo();
	array_shift(\$options);
	array_shift(\$options);
	array_shift(\$options);\n
EOT;
		if ( $tableCategory == 1 ) {
			$ret .= <<<EOT
	if (!(count(\$options) == 1 && \$options[0] == 0)) {
		\$criteria->add(new Criteria('{$tableFieldname}_category', {$moduleDirname}_block_addCatSelect(\$options), 'IN'));
	}\n
EOT;
		}			
		$ret .= <<<EOT
	if (\$type_block) 
	{
		\$criteria->add(new Criteria('{$fpif}', 0, '!='));
		\$criteria->setSort('{$fpif}');
		\$criteria->setOrder('ASC');
	}

	\$criteria->setLimit(\$nb_{$tableName});
	\${$tableName}_arr = \${$tableName}Handler->getAll(\$criteria);
	foreach (array_keys(\${$tableName}_arr) as \$i) 
	{\n
EOT;
		foreach(array_keys($fields) as $f) 
		{	    
			$fieldName = $fields[$f]->getVar('field_name');
			// Verify if table_fieldname is not empty
			$lpFieldName = !empty($tableFieldname) ? substr($fieldName, 0, strpos($fieldName, '_')) : $tableName;
			$rpFieldName = $this->tdmcfile->getRightString($fieldName);
			if( $fields[$f]->getVar('field_block') == 1 ) {				
				$ret .= <<<EOT
		\${$lpFieldName}['{$rpFieldName}'] = \${$tableName}_arr[\$i]->getVar('{$fieldName}');\n
EOT;
			}
		}
		$ret .= <<<EOT
	}
	return \${$tableFieldname};
}\n\n
EOT;
		return $ret;
	}
	/*
	*  @public function getBlocksEdit
	*  @param string $moduleDirname
	*  @param string $tableName
	*  @param string $fpif
	*  @param string $fpmf
	*  @param string $language
	*/
	public function getBlocksEdit($moduleDirname, $tableName, $fpif, $fpmf, $language) {    
		$stuModuleDirname = strtoupper($moduleDirname);
		$ucfModuleDirname = ucfirst($moduleDirname);
		$ret = <<<EOT
// Function edit block
function b_{$moduleDirname}_{$tableName}_edit(\$options) 
{	
    include_once XOOPS_ROOT_PATH.'/modules/{$moduleDirname}/class/{$tableName}.php';		
	\${$moduleDirname} = {$ucfModuleDirname}Helper::getInstance();
	\${$tableName}Handler =& \${$moduleDirname}->getHandler('{$tableName}');
	\$GLOBALS['xoopsTpl']->assign('{$moduleDirname}_upload_url', {$stuModuleDirname}_UPLOAD_URL);	
	\$form = {$language}DISPLAY;
	\$form .= "<input type='hidden' name='options[0]' value='".\$options[0]."' />";
	\$form .= "<input name='options[1]' size='5' maxlength='255' value='".\$options[1]."' type='text' />&nbsp;<br />";
	\$form .= {$language}TITLELENGTH." : <input name='options[2]' size='5' maxlength='255' value='".\$options[2]."' type='text' /><br /><br />";	
	array_shift(\$options);
	array_shift(\$options);
	array_shift(\$options);
	\$criteria = new CriteriaCompo();
	\$criteria->add(new Criteria('{$fpif}', 0, '!='));
	\$criteria->setSort('{$fpif}');
	\$criteria->setOrder('ASC');
	\${$tableName}_arr = \${$tableName}Handler->getAll(\$criteria);
	unset(\$criteria);
	\$form .= {$language}CATTODISPLAY."<br /><select name='options[]' multiple='multiple' size='5'>";
	\$form .= "<option value='0' " . (array_search(0, \$options) === false ? "" : "selected='selected'") . ">" .{$language}ALLCAT . "</option>";
	foreach (array_keys(\${$tableName}_arr) as \$i) {
		\${$fpif} = \${$tableName}_arr[\$i]->getVar('{$fpif}');
		\$form .= "<option value='" . \${$fpif} . "' " . (array_search(\${$fpif}, \$options) === false ? "" : "selected='selected'") . ">".\${$tableName}_arr[\$i]->getVar('{$fpmf}')."</option>";
	}
	\$form .= "</select>";
	return \$form;
}	
EOT;
		return $ret;
	}		
	/*
	*  @public function renderFile
	*  @param null
	*/
	public function renderFile($filename)
	{  		
		$module = $this->getModule();		
		$table = $this->getTable();
		$moduleDirname = $module->getVar('mod_dirname');
		$tableName = $table->getVar('table_name');
		$tableFieldname = $table->getVar('table_fieldname');
		$tableCategory = $table->getVar('table_category');
		$language = $this->getLanguage($moduleDirname, 'MB');
		$fields = $this->getTableFields($table->getVar('table_id'));
		foreach(array_keys($fields) as $f) 
		{
			if($f == 0) {
				$fpif = $fields[$f]->getVar('field_name');
			}
			if($fields[$f]->getVar('field_main') == 1) {
				$fpmf = $fields[$f]->getVar('field_name');
			}
		}		
		$content = $this->getHeaderFilesComments($module, $filename);
		$content .= $this->getBlocksShow($moduleDirname, $tableName, $tableFieldname, $tableCategory, $fields, $fpif);
		$content .= $this->getBlocksEdit($moduleDirname, $tableName, $fpif, $fpmf, $language);
		//
		$this->tdmcfile->create($moduleDirname, 'blocks', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);
		return $this->tdmcfile->renderFile();
	}
}