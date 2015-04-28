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
 * @version         $Id: admin_header.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

class AdminHeader extends TDMCreateFile
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
	*  @param array $tables
	*  @param string $filename
	*/
	public function write($module, $table, $tables, $filename) {    
		$this->setModule($module);		
		$this->setTable($table);
		$this->setTables($tables);
		$this->setFileName($filename);
	}
	/*
	*  @public function render
	*  @param null
	*/
	public function render() {    
		$module = $this->getModule();
		$table = $this->getTable();
		$tables = $this->getTables();
		$filename = $this->getFileName();
		$moduleDirname = $module->getVar('mod_dirname');		
		$ucfModuleDirname = ucfirst($moduleDirname);		
		$language = $this->getLanguage('AM');
		$content = $this->getHeaderFilesComments($module, $filename);
		$content .= <<<EOT
\nrequire_once dirname(dirname(dirname(dirname(__FILE__)))). '/include/cp_header.php';
\$thisPath = dirname(dirname(__FILE__));
include_once \$thisPath.'/include/common.php';
\$sysPathIcon16 = '../' . \$xoopsModule->getInfo('sysicons16');
\$sysPathIcon32 = '../' . \$xoopsModule->getInfo('sysicons32');
\$pathModuleAdmin = \$GLOBALS['xoopsModule']->getInfo('dirmoduleadmin');
//
\$modPathIcon16 = \$xoopsModule->getInfo('modicons16');
\$modPathIcon32 = \$xoopsModule->getInfo('modicons32');\n
EOT;
		if (is_object($table)) {
			if ( $table->getVar('table_name') != '' ) {
				$content .= <<<EOT
// Get instance of module
\${$moduleDirname} = {$ucfModuleDirname}Helper::getInstance();\n
EOT;
			}			
			foreach (array_keys($tables) as $t)
			{
				$tableName = $tables[$t]->getVar('table_name');
				$content .= <<<EOT
// {$tableName}
\${$tableName}Handler =& \${$moduleDirname}->getHandler('{$tableName}');\n
EOT;
			}
		}
		$content .=<<<EOT
//
\$myts =& MyTextSanitizer::getInstance();
if (!isset(\$xoopsTpl) || !is_object(\$xoopsTpl)) {
	include_once(XOOPS_ROOT_PATH."/class/template.php");
	\$xoopsTpl = new XoopsTpl();
}
// System icons path
\$xoopsTpl->assign('sysPathIcon16', \$sysPathIcon16);
\$xoopsTpl->assign('sysPathIcon32', \$sysPathIcon32);
// Local icons path
\$xoopsTpl->assign('modPathIcon16', \$modPathIcon16);
\$xoopsTpl->assign('modPathIcon32', \$modPathIcon32);

//Load languages
xoops_loadLanguage('admin');
xoops_loadLanguage('modinfo');
// Local admin menu class
if ( file_exists(\$GLOBALS['xoops']->path(\$pathModuleAdmin.'/moduleadmin.php'))){
	include_once \$GLOBALS['xoops']->path(\$pathModuleAdmin.'/moduleadmin.php');
}else{
	redirect_header("../../../admin.php", 5, _AM_MODULEADMIN_MISSING, false);
}
xoops_cp_header();
\$adminMenu = new ModuleAdmin();	
EOT;
		$this->tdmcfile->create($moduleDirname, 'admin', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);
		return $this->tdmcfile->renderFile();
	}
}