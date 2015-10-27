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
 * @version         $Id: AdminPermissions.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class AdminPermissions.
 */
class AdminPermissions extends TDMCreateFile
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
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return AdminPermissions
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
    /**
     * @param $module
     * @param $tables
     * @param $filename
     */
    public function write($module, $tables, $filename)
    {
        $this->setModule($module);
        $this->setTables($tables);
        $this->setFileName($filename);
    }

    /*
    *  @private function getPermissionsHeader
    *  @param string $module
    *  @param string $language
    */
    /**
     * @param $module
     * @param $language
     *
     * @return string
     */
    private function getPermissionsHeader($module, $language)
    {
        $moduleDirname = $module->getVar('mod_dirname');
        $tables = $this->getTableTables($module->getVar('mod_id'));
        foreach (array_keys($tables) as $t) {
            if (1 == $tables[$t]->getVar('table_permissions')) {
                $tableName = $tables[$t]->getVar('table_name');
            }
        }
        $ret = $this->getInclude('header');
        $ret .= <<<PRM
include_once XOOPS_ROOT_PATH.'/class/xoopsform/grouppermform.php';

\${$tableName}Handler =& \${$moduleDirname}->getHandler('{$tableName}');
// Check admin have access to this page
\$templateMain = '{$moduleDirname}_admin_permissions.tpl';
\$GLOBALS['xoopsTpl']->assign('navigation', \$adminMenu->addNavigation('permissions.php'));
/*echo \$adminMenu->addNavigation('permissions.php');*/

\$op = XoopsRequest::getString('op', 'global');
xoops_load('XoopsFormLoader');
\$permTableForm = new XoopsSimpleForm('', 'fselperm', 'permissions.php', 'post');
\$formSelect = new XoopsFormSelect('', 'op', \$op);
\$formSelect->setExtra('onchange="document.fselperm.submit()"');
\$formSelect->addOption('global', {$language}PERMISSIONS_GLOBAL);
\$formSelect->addOption('approve', {$language}PERMISSIONS_APPROVE);
\$formSelect->addOption('submit', {$language}PERMISSIONS_SUBMIT);
\$formSelect->addOption('view', {$language}PERMISSIONS_VIEW);
\$permTableForm->addElement(\$formSelect);
\$permTableForm->display();\n
PRM;

        return $ret;
    }

    /*
    *  @private function getPermissionsSwitch
    *  @param string $moduleDirname
    *  @param string $language
    */
    /**
     * @param $moduleDirname
     * @param $language
     *
     * @return string
     */
    private function getPermissionsSwitch($moduleDirname, $language)
    {
        $ret = <<<PRM
// Switch op case global, approve, submit, view.
switch(\$op)
{
    case 'global':
	default:
        \$formTitle = {$language}PERMISSIONS_GLOBAL;
        \$permName = '{$moduleDirname}_ac';
        \$permDesc = {$language}PERMISSIONS_GLOBAL_DESC;
        \$globalPerms = array( '4' => {$language}PERMISSIONS_GLOBAL_4, '8' => {$language}PERMISSIONS_GLOBAL_8, '16' => {$language}PERMISSIONS_GLOBAL_16 );
        break;
    case 'approve':
        \$formTitle = {$language}PERMISSIONS_APPROVE;
        \$permName = '{$moduleDirname}_approve';
        \$permDesc = {$language}PERMISSIONS_APPROVE_DESC;
        break;
    case 'submit':
        \$formTitle = {$language}PERMISSIONS_SUBMIT;
        \$permName = '{$moduleDirname}_submit';
        \$permDesc = {$language}PERMISSIONS_SUBMIT_DESC;
        break;
    case 'view':
        \$formTitle = {$language}PERMISSIONS_VIEW;
        \$permName = '{$moduleDirname}_view';
        \$permDesc = {$language}PERMISSIONS_VIEW_DESC;
        break;
}\n
PRM;

        return $ret;
    }

    /*
    *  @private function getPermissionsBody
    *  @param string $module
    *  @param string $language
    */
    /**
     * @param $module
     * @param $language
     *
     * @return string
     */
    private function getPermissionsBody($module, $language)
    {
        $tables = $this->getTableTables($module->getVar('mod_id'));
        foreach (array_keys($tables) as $t) {
            if (1 == $tables[$t]->getVar('table_permissions')) {
                $tableId = $tables[$t]->getVar('table_id');
                $tableMid = $tables[$t]->getVar('table_mid');
                $tableName = $tables[$t]->getVar('table_name');
            }
        }
        $ucfTableName = ucfirst($tableName);
        $fields = $this->getTableFields($tableMid, $tableId);
        $fieldId = 'id';
        $fieldMain = 'title';
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (0 == $f) {
                $fieldId = $fieldName;
            }
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName;
            }
        }
        $ret = <<<PRM
\$moduleId = \$xoopsModule->getVar('mid');
\$permform = new XoopsGroupPermForm(\$formTitle, \$moduleId, \$permName, \$permDesc, 'admin/permissions.php');
if (\$op == 'global') {
    foreach (\$globalPerms as \$gPermId => \$gPermName) {
        \$permform->addItem(\$gPermId, \$gPermName);
    }
	\$GLOBALS['xoopsTpl']->assign('form', \$permform->render());
} else {
    \${$tableName}Count = \${$tableName}Handler->getCount{$ucfTableName}();
    \${$tableName}All = \${$tableName}Handler->getAll{$ucfTableName}(0, 0, '{$fieldMain}');
    foreach (array_keys(\${$tableName}All) as \$i) {
        \$permform->addItem(\${$tableName}All[\$i]->getVar('{$fieldId}'), \${$tableName}All[\$i]->getVar('{$fieldMain}'));
    }
    // Check if {$tableName} exist before rendering the form, and redirect if there aren't {$tableName}
    if (\${$tableName}Count > 0) {
		\$GLOBALS['xoopsTpl']->assign('form', \$permform->render());
    } else {
        redirect_header ( '{$tableName}.php?op=new', 3, {$language}NO_PERMISSIONS_SET );
        exit();
    }
}
unset(\$permform);\n
PRM;

        return $ret;
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
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language = $this->getLanguage($moduleDirname, 'AM');
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getPermissionsHeader($module, $language);
        $content .= $this->getPermissionsSwitch($moduleDirname, $language);
        $content .= $this->getPermissionsBody($module, $language);
        $content .= $this->getInclude('footer');
        //
        $this->create($moduleDirname, 'admin', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
