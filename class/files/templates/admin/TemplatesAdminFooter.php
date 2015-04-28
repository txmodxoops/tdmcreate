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
 * @version         $Id: 1.91 TemplatesAdminFooter.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

class TemplatesAdminFooter extends TDMCreateFile
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
	*  @param string $module
	*  @param string $filename
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
		$filename = $this->getFileName();
		$moduleName = $module->getVar('mod_name');
		$moduleDirname = $module->getVar('mod_dirname');
		$support_name = $module->getVar('mod_support_name');
		$support_url = $module->getVar('mod_support_url');
		$language = $this->getLanguage($moduleDirname, 'AM');		
		$content = <<<EOT
<div class='center'>
    <a href='http://www.xoops.org' title='Visit XOOPS' target='_blank'><img src='<{xoModuleIcons32 xoopsmicrobutton.gif}>' alt='XOOPS' /></a>
</div>
<div class='center smallsmall italic pad5'><strong>{$moduleName}</strong> <{\$smarty.const.{$language}MAINTAINEDBY}>
            <a href='{$support_url}' title='Visit {$support_name}' class='tooltip' rel='external'>{$support_name}</a>
</div>
EOT;
		$this->tdmcfile->create($moduleDirname, 'templates/admin', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);
		return $this->tdmcfile->renderFile();
	}
}