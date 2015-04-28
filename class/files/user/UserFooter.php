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
 * @version         $Id: user_footer.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

class UserFooter extends TDMCreateFile
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
	*  @param string $filename
	*/
	public function write($module, $filename) {    
		$this->setModule($module);
		$this->setFileName($filename);
	}
	/*
	*  @public function render
	*  @param null
	*/
	public function render() {    
		$module = $this->getModule();
		$moduleDirname = $module->getVar('mod_dirname');
		$stu_mod_name = strtoupper($moduleDirname);
		$filename = $this->getFileName();			
		$content = $this->getHeaderFilesComments($module, $filename);
		$content .= <<<EOT
\$GLOBALS['xoopsTpl']->assign('sysPathIcon32', \$sysPathIcon32);
\$GLOBALS['xoopsTpl']->assign('{$moduleDirname}_url', {$stu_mod_name}_URL);
\$GLOBALS['xoopsTpl']->assign('adv', xoops_getModuleOption('advertise', \$dirname));
//
\$GLOBALS['xoopsTpl']->assign('bookmarks', xoops_getModuleOption('bookmarks', \$dirname));
\$GLOBALS['xoopsTpl']->assign('fbcomments', xoops_getModuleOption('fbcomments', \$dirname)); 
//
\$GLOBALS['xoopsTpl']->assign('admin', {$stu_mod_name}_ADMIN);
\$GLOBALS['xoopsTpl']->assign('copyright', \$copyright);
// User footer
include_once XOOPS_ROOT_PATH.'/footer.php';	
EOT;
		$this->tdmcfile->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);
		return $this->tdmcfile->renderFile();
	}
}