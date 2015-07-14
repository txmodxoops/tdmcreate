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
 * tdmcreate module.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 * @version         $Id: user_header.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class UserHeader.
 */
class UserHeader extends TDMCreateFile
{
    /*
    *  @public function constructor
    *  @param null
    */
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->tdmcfile = TDMCreateFile::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return UserHeader
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
    /**
     * @param $module
     * @param $table
     * @param $tables
     * @param $filename
     */
    public function write($module, $table, $tables, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setTables($tables);
        $this->setFileName($filename);
    }

    /*
    *  @public function render
    *  @param null
    */
    /**
     * @return bool|string
     */
    public function render()
    {
        $module = $this->getModule();
        $table = $this->getTable();
        $tables = $this->getTables();
        $moduleDirname = $module->getVar('mod_dirname');
        $filename = $this->getFileName();
        $stuModuleDirname = strtoupper($moduleDirname);
        $ucfModuleDirname = ucfirst($moduleDirname);
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= <<<EOT
include dirname(dirname(__DIR__)) . '/mainfile.php';
include __DIR__ . '/include/common.php';
\$dirname = basename(__DIR__);
// Breadcrumbs
\$xoBreadcrumbs   = array();
\$xoBreadcrumbs[] = array('title' => \$GLOBALS['xoopsModule']->getVar('name'), 'link' => {$stuModuleDirname}_URL . '/');\n
EOT;
        if (is_object($table) && $table->getVar('table_name') != '') {
            $content .= <<<EOT
// Get instance of module
\${$moduleDirname} = {$ucfModuleDirname}Helper::getInstance();\n
EOT;
        }
        if (is_array($tables)) {
            foreach (array_keys($tables) as $i) {
                $tableName = $tables[$i]->getVar('table_name');
                $content .= <<<EOT
\${$tableName}Handler =& \${$moduleDirname}->getHandler('{$tableName}');\n
EOT;
            }
        }
        $content .= <<<EOT
// Permission
include_once XOOPS_ROOT_PATH.'/class/xoopsform/grouppermform.php';
\$gperm_handler =& xoops_gethandler('groupperm');
if (is_object(\$xoopsUser)) {
    \$groups = \$xoopsUser->getGroups();
} else {
    \$groups = XOOPS_GROUP_ANONYMOUS;
}
//
\$myts =& MyTextSanitizer::getInstance();
if(!file_exists(\$style = {$stuModuleDirname}_URL . '/assets/css/style.css')) { return false; }
//
\$sysPathIcon16   = \$GLOBALS['xoopsModule']->getInfo('sysicons16');
\$sysPathIcon32   = \$GLOBALS['xoopsModule']->getInfo('sysicons32');
\$pathModuleAdmin = \$GLOBALS['xoopsModule']->getInfo('dirmoduleadmin');
//
\$modPathIcon16 = \$xoopsModule->getInfo('modicons16');
\$modPathIcon32 = \$xoopsModule->getInfo('modicons32');
//
xoops_loadLanguage('modinfo', \$dirname);
xoops_loadLanguage('main', \$dirname);
EOT;
        $this->tdmcfile->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}
