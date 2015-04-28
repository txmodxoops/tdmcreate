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
 * @version         $Id: admin_index.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

class AdminIndex extends TDMCreateFile
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
	*  @param mixed $tables
	*  @param string $filename
	*/
	public function write($module, $tables, $filename) {    
		$this->setModule($module);
		$this->setTables($tables);
		$this->setFileName($filename);		
	}
	/*
	*  @public function render
	*  @param null
	*/
	public function render() {    
		$module = $this->getModule();
		$tables = $this->getTables();
		$filename = $this->getFileName();        
		$moduleDirname = $module->getVar('mod_dirname');
		$language = $this->getLanguage($moduleDirname, 'AM');
		$language_thereare = $this->getLanguage($moduleDirname, 'AM', 'THEREARE_');
		$content = $this->getHeaderFilesComments($module, $filename);
		$content .= <<<EOT
include_once 'header.php';
// Count elements\n
EOT;
		$tableName = null;
		if(is_array($tables)) {	
			foreach (array_keys($tables) as $i)
			{
				$tableName = $tables[$i]->getVar('table_name');
				$content .= <<<EOT
\$count_{$tableName} = \${$tableName}Handler->getCount();\n
EOT;
			}
		}
		$content .= <<<EOT
// Template Index
\$template_main = '{$moduleDirname}_admin_index.tpl';\n
EOT;
		if(is_array($tables)) {
			$content .= <<<EOT
// InfoBox Statistics
\$adminMenu->addInfoBox({$language}STATISTICS);
// Info elements\n
EOT;
			foreach (array_keys($tables) as $i)
			{
				$tableName = $tables[$i]->getVar('table_name');
				$stuTableName = $language_thereare.strtoupper($tableName);
				$content .= <<<EOT
\$adminMenu->addInfoBoxLine({$language}STATISTICS, '<label>'.{$stuTableName}.'</label>', \$count_{$tableName});\n
EOT;
			}
		} 
		if($tableName == null) {
			$content .= <<<EOT
\$adminMenu->addInfoBoxLine({$language}STATISTICS, '<label>No statistics</label>', 0);\n
EOT;
		}
		$content .= <<<EOT
// Render Index
echo \$adminMenu->addNavigation('index.php');
echo \$adminMenu->renderIndex();
include_once 'footer.php';
EOT;
		$this->tdmcfile->create($moduleDirname, 'admin', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);
		return $this->tdmcfile->renderFile();
	}
}