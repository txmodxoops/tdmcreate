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
 * @version         $Id: user_print.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

class UserPrint extends TDMCreateFile
{	
	/*
	*  @public function constructor
	*  @param null
	*/
	public function __construct() {    
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
	*  @param string $filename
	*/
	public function write($module, $table, $filename) {    
		$this->setModule($module);
		$this->setTable($table);
		$this->setFileName($filename);		
	}
	/*
	*  @public function getUserPrint
	*  @param string $moduleDirname
	*  @param string $language
	*/
	public function getUserPrint($moduleDirname, $language) 
	{  
		$stu_mod_name = strtoupper($moduleDirname);
		$table = $this->getTable();
		$tableName = $table->getVar('table_name');
        $ucf_mod_name = ucfirst($moduleDirname);		
        $ucf_table_name = ucfirst($tableName);		
		$fields = $this->getTableFields($table->getVar('table_id'));
		foreach(array_keys($fields) as $f) 
		{
			$fieldName = $fields[$f]->getVar('field_name');
			$rp_field_name = $fieldName;
			if(strpos($fieldName, '_')) {       
				$str = strpos($fieldName, '_'); 
				if($str !== false){ 
					$rp_field_name = substr($fieldName, $str + 1, strlen($fieldName));
				} 		
			}
			$lp_field_name = substr($fieldName, 0, strpos($fieldName, '_'));	
			if(( $f == 0 ) && ($this->table->getVar('table_autoincrement') == 1)){
				$fpif = $fieldName;
			} else {				
				if ( $fields[$f]->getVar('field_main') == 1 ) {
					$fpmf = $fieldName; // fpmf = fields parameters main field
				}
			}
		}
		$stu_lp_field_name = strtoupper($lp_field_name);
		$ret = <<<EOT
\ninclude_once 'header.php';
{$lp_field_name} = isset(\$_GET['{$fpif}']) ? intval(\$_GET['{$fpif}']) : 0;
if ( empty({$fpif}) ) {
	redirect_header({$stu_mod_name}_URL . '/index.php', 2, {$language}NO{$stu_lp_field_name});
}
EOT;
		if( $fieldName == $lp_field_name.'_published' ) {
			$ret .= <<<EOT
// Verify that the article is published
{$lp_field_name} = new {$ucf_mod_name}{$ucf_table_name}({$fpif});
// Not yet published
if ( {$lp_field_name}->getVar('{$lp_field_name}_published') == 0 || {$lp_field_name}->getVar('{$lp_field_name}_published') > time() ) {
    redirect_header({$stu_mod_name}_URL . '/index.php', 2, {$language}NO{$stu_lp_field_name});
    exit();
}
EOT;
		}
		if( $fieldName == 'published' ) {
			$ret .= <<<EOT
// Verify that the article is published
{$lp_field_name} = new {$ucf_mod_name}{$ucf_table_name}({$fpif});
// Not yet published
if ( {$lp_field_name}->getVar('published') == 0 || {$lp_field_name}->getVar('published') > time() ) {
    redirect_header({$stu_mod_name}_URL . '/index.php', 2, {$language}NO{$stu_lp_field_name});
    exit();
}
EOT;
		}
		if( $fieldName == $lp_field_name.'_expired' ) {
			$ret .= <<<EOT
// Expired
if ( {$lp_field_name}->getVar('{$lp_field_name}_expired') != 0 && {$lp_field_name}->getVar('{$lp_field_name}_expired') < time() ) {
    redirect_header({$stu_mod_name}_URL . '/index.php', 2, {$language}NO{$stu_lp_field_name});
    exit();
}
EOT;
		}
		if( $fieldName == 'expired' ) {
			$ret .= <<<EOT
// Expired
if ( {$lp_field_name}->getVar('expired') != 0 && {$lp_field_name}->getVar('expired') < time() ) {
    redirect_header({$stu_mod_name}_URL . '/index.php', 2, {$language}NO{$stu_lp_field_name});
    exit();
}
EOT;
		}
		$ret .= <<<EOT

// Verify permissions
\$gperm_handler =& xoops_gethandler('groupperm');
if (is_object(\$xoopsUser)) {
    \$groups = \$xoopsUser->getGroups();
} else {
	\$groups = XOOPS_GROUP_ANONYMOUS;
}
if (!\$gperm_handler->checkRight('{$moduleDirname}_view', {$lp_field_name}->getVat('{$fpif}'), \$groups, \$xoopsModule->getVar('mid'))) {
	redirect_header({$stu_mod_name}_URL . '/index.php', 3, _NOPERM);
	exit();
}	
EOT;
		return $ret;
	}
	
	/*
	*  @public function render
	*  @param null
	*/
	public function render() {    
		$module = $this->getModule();		        		
		$filename = $this->getFileName();
		$moduleDirname = $module->getVar('mod_dirname');
		$language = $this->getLanguage($moduleDirname, 'MA');			
		$content = $this->getHeaderFilesComments($module, $filename);	
		$content .= $this->getUserPrint($moduleDirname, $language);
		$this->tdmcfile->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);
		return $this->tdmcfile->renderFile();
	}
}