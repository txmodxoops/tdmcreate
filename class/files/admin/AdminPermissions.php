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
        $this->tdmcfile = TDMCreateFile::getInstance();
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
    public function write($module, $table, $tables, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setTables($tables);
        $this->setFileName($filename);
    }

    /*
    *  @private function getPermissionsHeader
    *  @param string $moduleDirname
    *  @param string $language
    */
    /**
     * @param $moduleDirname
     * @param $language
     *
     * @return string
     */
    private function getPermissionsHeader($moduleDirname, $language)
    {
        $table = $this->getTable();
        $tableName = $table->getVar('table_name');
        $ret = <<<PRM
\ninclude  __DIR__ . '/header.php';
include_once XOOPS_ROOT_PATH.'/class/xoopsform/grouppermform.php';
if( !empty(\$_POST['submit']) )
{
    redirect_header( XOOPS_URL.'/modules/'.\$xoopsModule->dirname().'/admin/permissions.php', 1, _MP_GPERMUPDATED );
}
\${$tableName}Handler =& \${$moduleDirname}->getHandler('{$tableName}');
// Check admin have access to this page
/*\$xoopsGroups = \$GLOBALS['xoopsUser']->getGroups(); //xoops_getModuleOption ( 'groups', \$thisDirname );//\${$moduleDirname}->getConfig('groups');
\$adminGroups = xoops_getModuleOption ( 'admin_groups', \$thisDirname );//\${$moduleDirname}->getConfig('admin_groups');
if (count ( array_intersect ( \$xoopsGroups, \$adminGroups ) ) == 0) {
    redirect_header ( 'index.php', 3, _NOPERM );
}*/
/*\$templateMain = '{$moduleDirname}_admin_permissions.tpl';
\$GLOBALS['xoopsTpl']->assign('navigation', \$adminMenu->addNavigation('permissions.php'));*/
echo \$adminMenu->addNavigation('permissions.php');

\$permission = XoopsRequest::getInt('permission', 1, 'POST');
\$selected = array(1, 2, 3, 4);
\$selected[\$permission-1] = ' selected';
xoops_load('XoopsFormLoader');
\$permTableForm = new XoopsSimpleForm('', 'fselperm', 'permissions.php', 'post');
\$formSelect = new XoopsFormSelect('', 'permission', \$permission);
\$formSelect->setExtra('onchange="document.fselperm.submit()"');
\$formSelect->addOption('1'.\$selected[0], {$language}GLOBAL);
\$formSelect->addOption('1'.\$selected[1], {$language}APPROVE);
\$formSelect->addOption('1'.\$selected[2], {$language}SUBMIT);
\$formSelect->addOption('1'.\$selected[3], {$language}VIEW);
\$permTableForm->addElement(\$formSelect);
\$permTableForm->display();\n\n
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
\$moduleId = \$xoopsModule->getVar('mid');
switch(\$permission)
{
    case 1:
        \$formTitle = {$language}GLOBAL;
        \$permName = '{$moduleDirname}_ac';
        \$permDesc = {$language}GLOBAL_DESC;
        \$globalPerms = array( '4' => {$language}GLOBAL_4, '8' => {$language}GLOBAL_8, '16' => {$language}GLOBAL_16 );
        break;
    case 2:
        \$formTitle = {$language}APPROVE;
        \$permName = '{$moduleDirname}_approve';
        \$permDesc = {$language}APPROVE_DESC;
        break;
    case 3:
        \$formTitle = {$language}SUBMIT;
        \$permName = '{$moduleDirname}_submit';
        \$permDesc = {$language}SUBMIT_DESC;
        break;
    case 4:
        \$formTitle = {$language}VIEW;
        \$permName = '{$moduleDirname}_view';
        \$permDesc = {$language}VIEW_DESC;
        break;
}\n
PRM;

        return $ret;
    }

    /*
    *  @private function getPermissionsBody
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $language
    */
    /**
     * @param $moduleDirname
     * @param $language
     *
     * @return string
     */
    private function getPermissionsBody($moduleDirname, $language)
    {
        $tables = $this->getTables();
        foreach (array_keys($tables) as $t) {
            $tableId = $tables[$t]->getVar('table_id');
            $tableMid = $tables[$t]->getVar('table_mid');
            if (1 == $tables[$t]->getVar('table_permissions')) {
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
\$permform = new XoopsGroupPermForm(\$formTitle, \$moduleId, \$permName, \$permDesc, 'admin/permissions.php');
if (1 == \$permission) {
    foreach (\$globalPerms as \$gPermId => \$gPermName) {
        \$permform->addItem(\$gPermId, \$gPermName);
    }
	echo \$permform->render();
	//\$GLOBALS['xoopsTpl']->assign('form', \$permform->render());
} else {
    \${$tableName}Count = \${$tableName}Handler->getCount{$ucfTableName}();
    \${$tableName}All = \${$tableName}Handler->getAll{$ucfTableName}(0, 0, '{$fieldMain}');
    foreach (array_keys(\${$tableName}All) as \$i) {
        \$permform->addItem(\${$tableName}All[\$i]->getVar('{$fieldId}'), \${$tableName}All[\$i]->getVar('{$fieldMain}'));
    }
    // Check if {$tableName} exist before rendering the form, and redirect if there aren't {$tableName}
    if (\${$tableName}Count > 0) {
		echo \$permform->render();
		//\$GLOBALS['xoopsTpl']->assign('form', \$permform->render());
    } else {
        redirect_header ( '{$tableName}.php?op=new', 3, {$language}NOPERMSSET );
        exit ();
    }
}
unset(\$permform);\n
PRM;

        return $ret;
    }

    /*
    *  @private function getPermissionsFooter
    *  @param null
    */
    /**
     * @return string
     */
    private function getPermissionsFooter()
    {
        $ret = <<<PRM
include  __DIR__ . '/footer.php';
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
        $content .= $this->getPermissionsHeader($moduleDirname, $language);
        $content .= $this->getPermissionsSwitch($moduleDirname, $language);
        $content .= $this->getPermissionsBody($moduleDirname, $language);
        $content .= $this->getPermissionsFooter();
        //
        $this->tdmcfile->create($moduleDirname, 'admin', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}
