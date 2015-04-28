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
 * @version         $Id: LanguageMain.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');
require_once 'LanguageDefines.php';
class LanguageMain extends TDMCreateFile
{	
	/*
	* @var mixed
	*/
	private $defines = null;
	/*
	*  @public function constructor
	*  @param null
	*/
	public function __construct() {  
		parent::__construct();
		$this->tdmcfile = TDMCreateFile::getInstance();
		$this->defines = LanguageDefines::getInstance();
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
	*  @param mixed $tables
	*  @param string $filename
	*/
	public function write($module, $tables, $filename) {    
		$this->setModule($module);
		$this->setFileName($filename);
		$this->setTables($tables);
	}
	/*
	*  @private function geLanguagetMain
	*  @param string $module
	*  @param string $language
	*/
	private function geLanguagetMain($module, $language) 
	{    
		$tables = $this->getTables();
		$ret = $this->defines->getAboveHeadDefines('Main');
		$ret .= $this->defines->getDefine($language, 'INDEX', "Home");
		$ret .= $this->defines->getDefine($language, 'TITLE', "{$module->getVar('mod_name')}");
		$ret .= $this->defines->getDefine($language, 'DESC', "{$module->getVar('mod_description')}");
		$ret .= $this->defines->getDefine($language, 'INDEX_DESC', "Welcome to the homepage of your new module!<br /> 
As you can see, you've created a page with a list of links at the top to navigate between the pages of your module. This description is only visible on the homepage of this module, the other pages you will see the content you created when you built this module with the module TDMCreate, and after creating new content in admin of this module. In order to expand this module with other resources, just add the code you need to extend the functionality of the same. The files are grouped by type, from the header to the footer to see how divided the source code.");		
		$ret .= $this->defines->getAboveHeadDefines('Contents');		
		foreach (array_keys($tables) as $i) 
		{
			$tableName = $tables[$i]->getVar('table_name');
			$stuTableName = strtoupper($tableName);
			$ucfTableName = UcFirstAndToLower($tableName);
			$ret .= $this->defines->getAboveDefines($ucfTableName);
			$ret .= $this->defines->getDefine($language, $stuTableName, $ucfTableName);
			$ret .= $this->defines->getDefine($language, "{$stuTableName}_DESC", "{$ucfTableName} description");			
			$ret .= $this->defines->getAboveDefines("Caption of {$ucfTableName}");
			$fields = $this->getTableFields($tables[$i]->getVar('table_id'));
			foreach (array_keys($fields) as $f) 
			{	
				$fieldName = $fields[$f]->getVar('field_name');                
				$stuFieldName = strtoupper($fieldName);
				//
				$rpFieldName = $this->tdmcfile->getRightString($fieldName);
				$lpFieldName = substr($fieldName, 0, strpos($fieldName, '_'));
				//
				$fieldNameDesc = ucfirst($rpFieldName);
				//
				$ret .= $this->defines->getDefine($language, $stuFieldName, $fieldNameDesc);				
			}
		}
		return $ret;
	}
	/*
	*  @private function geLanguagetMainFooter
	*  @param string $language
	*/
	private function geLanguagetMainFooter($language) 
	{    
		$ret = $this->defines->getAboveDefines('Admin link');
		$ret .= $this->defines->getDefine($language, 'ADMIN', "Admin");
		$ret .= $this->defines->getBelowDefines('End');
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
		$content .= $this->geLanguagetMain($module, $language);
		$content .= $this->geLanguagetMainFooter($language);
		//
		$this->tdmcfile->create($moduleDirname, 'language/english', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);
		return $this->tdmcfile->renderFile();
	}
}