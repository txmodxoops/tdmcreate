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
 * @version         $Id: IncludeUpdate.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

class IncludeUpdate extends TDMCreateFile
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
	*  @private function getIncludeUpdateModule
	*  @param string $moduleDirname
	*  @param mixed $moduleVersion
	*/
	private function getIncludeUpdateModule($moduleDirname, $moduleVersion) 
	{    
		$ret = <<<EOT
/**
 * @param      \$module
 * @param null \$prev_version
 *
 * @return bool|null
 */
function xoops_module_update_{$moduleDirname}(&\$module, \$prev_version = null)
{
    // irmtfan bug fix: solve templates duplicate issue
    \$ret = null;
    if (\$prev_version < {$moduleVersion}) {
        \$ret = update_system_v{$moduleVersion}(\$module);
    }
    \$errors = \$module->getErrors();
    if (!empty(\$errors)) {
        print_r(\$errors);
    }

    return \$ret;
    // irmtfan bug fix: solve templates duplicate issue
}
EOT;
		return $ret;
	}
	
	/*
	*  @private function getIncludeUpdateVersion
	*  @param string $moduleDirname
	*  @param mixed $moduleVersion
	*/
	private function getIncludeUpdateVersion($moduleDirname, $moduleVersion) 
	{    
		$ret = <<<EOT
// irmtfan bug fix: solve templates duplicate issue
/**
 * @param \$module
 *
 * @return bool
 */
function update_{$moduleDirname}_v{$moduleVersion}(&\$module)
{
    global \$xoopsDB;
    \$result = \$xoopsDB->query(
        "SELECT t1.tpl_id FROM " . \$xoopsDB->prefix('tplfile') . " t1, " . \$xoopsDB->prefix('tplfile')
        . " t2 WHERE t1.tpl_refid = t2.tpl_refid AND t1.tpl_module = t2.tpl_module AND t1.tpl_tplset=t2.tpl_tplset AND t1.tpl_file = t2.tpl_file AND t1.tpl_type = t2.tpl_type AND t1.tpl_id > t2.tpl_id"
    );
    \$tplids = array();
    while (list(\$tplid) = \$xoopsDB->fetchRow(\$result)) {
        \$tplids[] = \$tplid;
    }
    if (count(\$tplids) > 0) {
        \$tplfile_handler =& xoops_gethandler('tplfile');
        \$duplicate_files = \$tplfile_handler->getObjects(
            new Criteria('tpl_id', "(" . implode(',', \$tplids) . ")", "IN")
        );

        if (count(\$duplicate_files) > 0) {
            foreach (array_keys(\$duplicate_files) as \$i) {
                \$tplfile_handler->delete(\$duplicate_files[\$i]);
            }
        }
    }
    \$sql = "SHOW INDEX FROM " . \$xoopsDB->prefix('tplfile') . " WHERE KEY_NAME = 'tpl_refid_module_set_file_type'";
    if (!\$result = \$xoopsDB->queryF(\$sql)) {
        xoops_error(\$this->db->error() . '<br />' . \$sql);

        return false;
    }
    \$ret = array();
    while (\$myrow = \$xoopsDB->fetchArray(\$result)) {
        \$ret[] = \$myrow;
    }
    if (!empty(\$ret)) {
        \$module->setErrors(
            "'tpl_refid_module_set_file_type' unique index is exist. Note: check 'tplfile' table to be sure this index is UNIQUE because XOOPS CORE need it."
        );

        return true;
    }
    \$sql = "ALTER TABLE " . \$xoopsDB->prefix('tplfile')
        . " ADD UNIQUE tpl_refid_module_set_file_type ( tpl_refid, tpl_module, tpl_tplset, tpl_file, tpl_type )";
    if (!\$result = \$xoopsDB->queryF(\$sql)) {
        xoops_error(\$xoopsDB->error() . '<br />' . \$sql);
        \$module->setErrors(
            "'tpl_refid_module_set_file_type' unique index is not added to 'tplfile' table. Warning: do not use XOOPS until you add this unique index."
        );

        return false;
    }

    return true;
}
// irmtfan bug fix: solve templates duplicate issue
EOT;
		return $ret;
	}
	
	/*
	*  @public function renderFile
	*  @param null
	*/
	public function renderFile() {    
		$module = $this->getModule();
		$filename = $this->getFileName();
		$moduleDirname = $module->getVar('mod_dirname'); 
        $moduleVersion = str_replace('.', '', $module->getVar('mod_version'));		
		$content = $this->getHeaderFilesComments($module, $filename);
		$content .= $this->getIncludeUpdateModule($moduleDirname, $moduleVersion);
		$content .= $this->getIncludeUpdateVersion($moduleDirname, $moduleVersion);
		//
		$this->tdmcfile->create($moduleDirname, 'include', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);
		return $this->tdmcfile->renderFile();
	}
}