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
 * @version         $Id: admin_permissions.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class AdminPermissions
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
    public function write($module, $tables, $filename)
    {
        $this->setModule($module);
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
     * @return string
     */
    private function getPermissionsHeader($moduleDirname, $language)
    {
        $ret = <<<PRM
<<<<<<< HEAD
\ninclude  __DIR__ . '/header.php';
=======
\ninclude_once 'header.php';
>>>>>>> origin/master
include_once XOOPS_ROOT_PATH.'/class/xoopsform/grouppermform.php';
if( !empty(\$_POST['submit']) )
{
    redirect_header( XOOPS_URL.'/modules/'.\$xoopsModule->dirname().'/admin/permissions.php', 1, _MP_GPERMUPDATED );
}
// Check admin have access to this page
/*\$group = \$xoopsUser->getGroups ();
\$groups = xoops_getModuleOption ( 'admin_groups', \$thisDirname );
if (count ( array_intersect ( \$group, \$groups ) ) <= 0) {
    redirect_header ( 'index.php', 3, _NOPERM );
}*/
\$template_main = '{$moduleDirname}_admin_permissions.tpl';
echo \$adminMenu->addNavigation('permissions.php');

\$permission = {$moduleDirname}_CleanVars(\$_REQUEST, 'permission', 1, 'int');
\$selected = array('', '', '', '');
\$selected[\$permission-1] = ' selected';
xoops_load('XoopsFormLoader');
\$permTableForm = new XoopsSimpleForm('', 'fselperm', 'permissions.php', 'get');
\$formSelect = new XoopsFormSelect('', 'permission', \$permission);
\$formSelect->setExtra('onchange="document.forms.fselperm.submit()"');
\$formSelect->addOption(\$selected[0], {$language}GLOBAL);
\$formSelect->addOption(\$selected[1], {$language}APPROVE);
\$formSelect->addOption(\$selected[2], {$language}SUBMIT);
\$formSelect->addOption(\$selected[3], {$language}VIEW);
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
     * @return string
     */
    private function getPermissionsSwitch($moduleDirname, $language)
    {
        $ret = <<<PRM
\$module_id = \$xoopsModule->getVar('mid');
switch(\$permission)
{
    case 1:
        \$formTitle = {$language}GLOBAL;
        \$permName = '{$moduleDirname}_ac';
        \$permDesc = {$language}GLOBAL_DESC;
        \$globalPerms = array(    '4' => {$language}GLOBAL_4,
                                '8' => {$language}GLOBAL_8,
                                '16' => {$language}GLOBAL_16 );
        break;
    case 2:
        \$formTitle = {$language}APPROVE;
        \$permName = '{$moduleDirname}_access';
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
     * @return string
     */
    private function getPermissionsBody($moduleDirname, $language)
    {
        $tables = $this->getTables();
        foreach (array_keys($tables) as $t) {
            $tableId = $tables[$t]->getVar('table_id');
            if (1 == $tables[$t]->getVar('table_permissions')) {
                $tableName = $tables[$t]->getVar('table_name');
            }
        }
        $fields = $this->getTableFields($tableId);
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (0 == $f) {
                $fpif = $fieldName;
            }
            if (1 == $fields[$f]->getVar('field_main')) {
                $fpmf = $fieldName;
            }
        }
        $ret = <<<PRM
\$permform = new XoopsGroupPermForm(\$formTitle, \$module_id, \$permName, \$permDesc, 'admin/permissions.php');
if (1 == \$permission) {
    foreach (\$globalPerms as \$perm_id => \$perm_name) {
        \$permform->addItem(\$perm_id, \$perm_name);
    }
    echo \$permform->render();
    echo '<br /><br />';
} else {
    \$criteria = new CriteriaCompo();
    \$criteria->setSort('{$fpmf}');
    \$criteria->setOrder('ASC');
    \${$tableName}_count = \${$tableName}Handler->getCount(\$criteria);
    \${$tableName}_arr = \${$tableName}Handler->getObjects(\$criteria);
    unset(\$criteria);
    foreach (array_keys(\${$tableName}_arr) as \$i) {
        \$permform->addItem(\${$tableName}_arr[\$i]->getVar('{$fpif}'), \${$tableName}_arr[\$i]->getVar('{$fpmf}'));
    }
    // Check if {$tableName} exist before rendering the form and redirect, if there aren't {$tableName}
    if (\${$tableName}_count > 0) {
        echo \$permform->render();
        echo '<br /><br />';
    } else {
        redirect_header ( '{$tableName}.php?op=new', 3, {$language}NOPERMSSET );
        exit ();
    }\n
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
}
unset(\$permform);
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
        $module        = $this->getModule();
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language      = $this->getLanguage($moduleDirname, 'AM');
        $content       = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getPermissionsHeader($moduleDirname, $language);
        $content .= $this->getPermissionsSwitch($moduleDirname, $language);
        $content .= $this->getPermissionsBody($moduleDirname, $language);
        $content .= $this->getPermissionsFooter();
        //
        $this->tdmcfile->create($moduleDirname, 'admin', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}
