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
 * @version         $Id: admin_menu.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

class AdminMenu extends TDMCreateFile
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
	*  @param object $table
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
	*  @private function getAdminMenuHeader
	*  @param null
	*/
	private function getAdminMenuHeader() {    
		$ret = <<<EOT
\$dirname = basename( dirname( dirname( __FILE__ ) ) ) ;
\$module_handler =& xoops_gethandler('module');
\$xoopsModule =& XoopsModule::getByDirname(\$dirname);
\$moduleInfo =& \$module_handler->get(\$xoopsModule->getVar('mid'));
\$sysPathIcon32 = \$moduleInfo->getInfo('sysicons32');\n
EOT;
		return $ret; 
	}
	/*
	*  @private function getAdminMenuDashboard
	*  @param string $language
	*  @param integer $menu
	*/
	private function getAdminMenuDashboard($language, $menu) {    
		$ret = <<<EOT
\$i = 1;
\$adminmenu[\$i]['title'] = {$language}{$menu};
\$adminmenu[\$i]['link'] = 'admin/index.php';
\$adminmenu[\$i]['icon'] = \$sysPathIcon32.'/dashboard.png';
\$i++;\n
EOT;
		return $ret; 
	}
	/*
	*  @private function getAdminMenuImagesPath
	*  @param array $tables
	*  @param integer $t
	*/
	private function getAdminMenuImagesPath($tables, $t) {    
		$fields = $this->getTableFields($tables[$t]->getVar('table_id'));
		foreach (array_keys($fields) as $f) 
		{
			$fieldElement = $fields[$f]->getVar('field_element');
			switch( $fieldElement ) {				
				case 11:
					$ret = <<<EOT
\$adminmenu[\$i]['icon'] = 'assets/icons/32/{$tables[$t]->getVar('table_image')}';\n
EOT;
				break;
				default:
					$ret = <<<EOT
\$adminmenu[\$i]['icon'] = \$sysPathIcon32.'/{$tables[$t]->getVar('table_image')}';\n
EOT;
				break;
			}
		}
		return $ret; 
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
		$language = $this->getLanguage($moduleDirname, 'MI', 'ADMENU');
		$menu = 1;
		$content = $this->getHeaderFilesComments($module, $filename);
		$content .= $this->getAdminMenuHeader();
		$content .= $this->getAdminMenuDashboard($language, $menu);
		foreach (array_keys($tables) as $t)
		{		
			$tablePermissions = $tables[$t]->getVar('table_permissions');
			if ( $tables[$t]->getVar('table_admin') == 1 ) 
			{   
			    $menu++;				
				$content .= <<<EOT
\$adminmenu[\$i]['title'] = {$language}{$menu};
\$adminmenu[\$i]['link'] = 'admin/{$tables[$t]->getVar('table_name')}.php';
\$adminmenu[\$i]['icon'] = 'assets/icons/32/{$tables[$t]->getVar('table_image')}';\n
EOT;
				//$content .= $this->getAdminMenuImagesPath($tables, $t);
				$content .= <<<EOT
\$i++;\n
EOT;
			}
		}
		if (is_object($table) && $table->getVar('table_permissions') == 1) {	
			$menu++;
			$content .= <<<EOT
\$adminmenu[\$i]['title'] = {$language}{$menu};
\$adminmenu[\$i]['link'] = 'admin/permissions.php';
\$adminmenu[\$i]['icon'] = \$sysPathIcon32.'/permissions.png';
\$i++;\n
EOT;
		}
		$menu++;
		$content .= <<<EOT
\$adminmenu[\$i]['title'] = {$language}{$menu};
\$adminmenu[\$i]['link']  = 'admin/about.php';
\$adminmenu[\$i]['icon'] = \$sysPathIcon32.'/about.png';
unset( \$i );
EOT;
		unset( $menu );
		
		$this->tdmcfile->create($moduleDirname, 'admin', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);
		return $this->tdmcfile->renderFile();
	}
}