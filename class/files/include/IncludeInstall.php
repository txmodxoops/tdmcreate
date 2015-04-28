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
 * @version         $Id: IncludeInstall.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

class IncludeInstall extends TDMCreateFile
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
	*  @param mixed $tables
	*  @param string $filename
	*/
	public function write($module, $table, $tables, $filename) {    
		$this->setModule($module);
		$this->setTable($table);
		$this->setTables($tables);
		$this->setFileName($filename);		
	}
	/*
	*  @private function getInstallModuleFolder
	*  @param string $moduleDirname
	*/
	private function getInstallModuleFolder($moduleDirname) {    
		$ret = <<<EOT
//
defined('XOOPS_ROOT_PATH') or die('Restricted access');
// Copy base file
\$indexFile = XOOPS_UPLOAD_PATH.'/index.html';
\$blankFile = XOOPS_UPLOAD_PATH.'/blank.gif';
// Making of "uploads/{$moduleDirname}" folder
\${$moduleDirname} = XOOPS_UPLOAD_PATH.'/{$moduleDirname}';
if(!is_dir(\${$moduleDirname}))
	mkdir(\${$moduleDirname}, 0777);
	chmod(\${$moduleDirname}, 0777);
copy(\$indexFile, \${$moduleDirname}.'/index.html');\n
EOT;
		return $ret;
	}
	/*
	*  @private function getHeaderTableFolder
	*  @param string $moduleDirname
	*  @param string $tableName
	*/
	private function getInstallTableFolder($moduleDirname, $tableName) {    
		$ret = <<<EOT
// Making of {$tableName} uploads folder
\${$tableName} = \${$moduleDirname}.'/{$tableName}';
if(!is_dir(\${$tableName}))
	mkdir(\${$tableName}, 0777);
	chmod(\${$tableName}, 0777);
copy(\$indexFile, \${$tableName}.'/index.html');\n
EOT;
		return $ret;
	}
	/*
	*  @private function getInstallImagesFolder
	*  @param string $moduleDirname
	*/
	private function getInstallImagesFolder($moduleDirname) {    
		$ret = <<<EOT
// Making of images folder
\$images = \${$moduleDirname}.'/images';
if(!is_dir(\$images))
	mkdir(\$images, 0777);
	chmod(\$images, 0777);
copy(\$indexFile, \$images.'/index.html');
copy(\$blankFile, \$images.'/blank.gif');\n
EOT;
		return $ret;
	}
	/*
	*  @private function getInstallTableImagesFolder
	*  @param string $tableName
	*/
	private function getInstallTableImagesFolder($tableName) {    
		$ret = <<<EOT
// Making of "{$tableName}" images folder
\${$tableName} = \$images.'/{$tableName}';
if(!is_dir(\${$tableName}))
	mkdir(\${$tableName}, 0777);
	chmod(\${$tableName}, 0777);
copy(\$indexFile, \${$tableName}.'/index.html');
copy(\$blankFile, \${$tableName}.'/blank.gif');\n
EOT;
		return $ret;
	}
	/*
	*  @private function getInstallFilesFolder
	*  @param string $moduleDirname
	*/
	private function getInstallFilesFolder($moduleDirname) {    
		$ret = <<<EOT
// Making of files folder
\$files = \${$moduleDirname}.'/files';
if(!is_dir(\$files))
	mkdir(\$files, 0777);
	chmod(\$files, 0777);
copy(\$indexFile, \$files.'/index.html');\n
EOT;
		return $ret;
	}
	/*
	*  @private function getInstallTableFilesFolder
	*  @param string $tableName
	*/
	private function getInstallTableFilesFolder($tableName) {    
		$ret = <<<EOT
// Making of "{$tableName}" files folder
\${$tableName} = \$files.'/{$tableName}';
if(!is_dir(\${$tableName}))
	mkdir(\${$tableName}, 0777);
	chmod(\${$tableName}, 0777);
copy(\$indexFile, \${$tableName}.'/index.html');\n
EOT;
		return $ret;
	}
	/*
	*  @private function getInstallFooter
	*  @param null
	*/
	private function getInstallFooter() {    
		$ret = <<<EOT
// ---------- Install Footer ---------- //
EOT;
		return $ret;
	}
	/*
	*  @public function render
	*  @param null
	*/
	public function render() 
	{  		
		$module = $this->getModule();
		$moduleDirname = $module->getVar('mod_dirname');
		$table = $this->getTable();
		$tables = $this->getTables();
		$filename = $this->getFileName();
		$content = $this->getHeaderFilesComments($module, $filename);
		$content .= $this->getInstallModuleFolder($moduleDirname);				
		$fields = $this->getTableFields($table->getVar('table_id'));
		foreach(array_keys($fields) as $f) 
		{				
			$fieldElement = $fields[$f]->getVar('field_element');		
			// All fields elements selected
			switch( $fieldElement ) {
				case 11:
					$content .= $this->getInstallImagesFolder($moduleDirname);
					foreach(array_keys($tables) as $t) 
					{	
						$tableName = $tables[$t]->getVar('table_name');	
						$content .= $this->getInstallTableImagesFolder($tableName);			
					}
				break;
				case 12:
					$content .= $this->getInstallFilesFolder($moduleDirname);
					foreach(array_keys($tables) as $t) 
					{	
						$tableName = $tables[$t]->getVar('table_name');	
						$content .= $this->getInstallTableFilesFolder($tableName);			
					}
				break;
			}
		}					
		$content .= $this->getInstallFooter();
		//
		$this->tdmcfile->create($moduleDirname, 'include', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);
		return $this->tdmcfile->renderFile();
	}
}