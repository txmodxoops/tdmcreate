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
 * @version         $Id: pages.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

class TemplatesAdminPermissions extends TDMCreateHtmlSmartyCodes
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
	public function write($module, $filename) {    
		$this->setModule($module);
		$this->setFileName($filename);
	}
	/*
	*  @private function getTemplatesAdminPagesHeader
	*  @param string $moduleDirname
	*/
	private function getTemplatesAdminPermissions($moduleDirname) {    
		$ret = <<<EOT
<{include file="db:{$moduleDirname}_admin_header.tpl"}>\n
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
		$language = $this->getLanguage($moduleDirname, 'AM');
		$content = $this->getTemplatesAdminPermissions($moduleDirname);
		//
		$this->tdmcfile->create($moduleDirname, 'templates/admin', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);
		return $this->tdmcfile->renderFile();
	}
}