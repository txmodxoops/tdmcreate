<?php

namespace XoopsModules\Tdmcreate\Files\Admin;

use XoopsModules\Tdmcreate;
use XoopsModules\Tdmcreate\Files;

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
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 */

/**
 * Class AdminPermissions.
 */
class AdminPermissions extends Files\CreateFile
{
    /**
     * @public function constructor
     *
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @static function getInstance
     *
     * @param null
     *
     * @return AdminPermissions
     */
    public static function getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * @public function write
     *
     * @param string $module
     * @param mixed  $tables
     * @param string $filename
     *
     * @return null
     */
    public function write($module, $tables, $filename)
    {
        $this->setModule($module);
        $this->setTables($tables);
        $this->setFileName($filename);
        return null;
    }

    /**
     * @private function getPermissionsHeader
     *
     * @param $module
     * @param $language
     *
     * @return string
     */
    private function getPermissionsHeader($module, $language)
    {
        $pc            = Tdmcreate\Files\CreatePhpCode::getInstance();
        $xc            = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $cc            = Tdmcreate\Files\Classes\ClassXoopsCode::getInstance();
        $axc           = Tdmcreate\Files\Admin\AdminXoopsCode::getInstance();
        $moduleDirname = $module->getVar('mod_dirname');
        $tables        = $this->getTableTables($module->getVar('mod_id'));
        $tableNames    = [];
        foreach (array_keys($tables) as $t) {
            if (1 == $tables[$t]->getVar('table_permissions')) {
                $tableNames[] = $tables[$t]->getVar('table_name');
            }
        }
        $ret           = $pc->getPhpCodeUseNamespace(['Xmf', 'Request'], '', '');
        $ret           .= $pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname], '', '');
        $ret           .= $pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname, 'Constants']);
        $ret           .= $this->getInclude('header');
        $ret           .= $pc->getBlankLine();
        $ret           .= $pc->getPhpCodeCommentLine('Template Index');
        $ret           .= $axc->getAdminTemplateMain($moduleDirname, 'permissions');
        $ret           .= $xc->getXcTplAssign('navigation', "\$adminObject->displayNavigation('permissions.php')");
        $ret           .= $pc->getBlankLine();
        $ret           .= $xc->getXcXoopsRequest('op', 'op', 'global');
        $ret           .= $pc->getBlankLine();
        $ret           .= $pc->getPhpCodeCommentLine('Get Form');
        $ret           .= $pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/xoopsform/grouppermform', true);
        $ret           .= $xc->getXcLoad('XoopsFormLoader');
        $optionsSelect['global'] = "{$language}PERMISSIONS_GLOBAL";
        foreach ($tableNames as $tableName) {
            $ucfTablename = ucfirst($tableName);
            $optionsSelect["approve_{$tableName}"] = "{$language}PERMISSIONS_APPROVE . ' {$ucfTablename}'";
            $optionsSelect["submit_{$tableName}"] = "{$language}PERMISSIONS_SUBMIT . ' {$ucfTablename}'";
            $optionsSelect["view_{$tableName}"] = "{$language}PERMISSIONS_VIEW . ' {$ucfTablename}'";
        }
        $formSelect    = $xc->getXoopsFormSelectExtraOptions('formSelect', '\'\'', 'op', $optionsSelect, 'onchange="document.fselperm.submit()"');
        $ret           .= $cc->getXoopsSimpleForm('permTableForm', 'formSelect', $formSelect, '\'\'', 'fselperm', 'permissions');

        return $ret;
    }

    /**
     * @private function getPermissionsSwitch
     * @param $module
     * @param $language
     *
     * @return string
     */
    private function getPermissionsSwitch($module, $language)
    {
        $pc    = Tdmcreate\Files\CreatePhpCode::getInstance();

        $moduleDirname = $module->getVar('mod_dirname');
        $tables        = $this->getTableTables($module->getVar('mod_id'));

        $cases['global']= [
                "\$formTitle = {$language}PERMISSIONS_GLOBAL;",
                "\$permName = '{$moduleDirname}_ac';",
                "\$permDesc = {$language}PERMISSIONS_GLOBAL_DESC;",
                "\$globalPerms = array( '4' => {$language}PERMISSIONS_GLOBAL_4, '8' => {$language}PERMISSIONS_GLOBAL_8, '16' => {$language}PERMISSIONS_GLOBAL_16 );",
                ];
        foreach (array_keys($tables) as $t) {
            if (1 == $tables[$t]->getVar('table_permissions')) {
                $tableName = $tables[$t]->getVar('table_name');
                $ucfTablename = ucfirst($tableName);
                $cases["approve_{$tableName}"] = [
                    "\$formTitle = {$language}PERMISSIONS_APPROVE;",
                    "\$permName = '{$moduleDirname}_approve_{$tableName}';",
                    "\$permDesc = {$language}PERMISSIONS_APPROVE_DESC . ' {$ucfTablename}';",
                    "\$handler = \$helper->getHandler('{$tableName}');",
                ];
                $cases["submit_{$tableName}"] = [
                    "\$formTitle = {$language}PERMISSIONS_SUBMIT;",
                    "\$permName = '{$moduleDirname}_submit_{$tableName}';",
                    "\$permDesc = {$language}PERMISSIONS_SUBMIT_DESC . ' {$ucfTablename}';",
                    "\$handler = \$helper->getHandler('{$tableName}');",
                ];
                $cases["view_{$tableName}"] = [
                    "\$formTitle = {$language}PERMISSIONS_VIEW;",
                    "\$permName = '{$moduleDirname}_view_{$tableName}';",
                    "\$permDesc = {$language}PERMISSIONS_VIEW_DESC . ' {$ucfTablename}';",
                    "\$handler = \$helper->getHandler('{$tableName}');",
                ];
            }
        }
        $contentSwitch = $pc->getPhpCodeCaseSwitch($cases, true, false, "\t");

        return $pc->getPhpCodeSwitch('op', $contentSwitch);
    }

    /**
     * @private function getPermissionsBody
     *
     * @param string $module
     * @param string $language
     *
     * @return string
     */
    private function getPermissionsBody($module, $language)
    {
        $pc       = Tdmcreate\Files\CreatePhpCode::getInstance();
        $xc       = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $tables   = $this->getTableTables($module->getVar('mod_id'));

        $ret      = $xc->getXcGetVar('moduleId', 'xoopsModule', 'mid');
        $ret      .= $xc->getXcGroupPermForm('permform', '$formTitle', '$moduleId', '$permName', '$permDesc', "'admin/permissions.php'");
        $ret      .= $xc->getXcEqualsOperator('$permFound', 'false');
        $foreach1 = $xc->getXcAddItem('permform', '$gPermId', '$gPermName', "\t");
        $if1      = $pc->getPhpCodeForeach('globalPerms', false, 'gPermId', 'gPermName', $foreach1, "\t");
        $if1      .= $xc->getXcTplAssign('form', '$permform->render()', true, "\t");
        $if1      .= $xc->getXcEqualsOperator('$permFound', 'true', null, false, "\t");
        $ret      .= $pc->getPhpCodeConditions('$op', ' === ', "'global'", $if1, false);

        foreach (array_keys($tables) as $t) {
            if (1 == $tables[$t]->getVar('table_permissions')) {
                $tableId   = $tables[$t]->getVar('table_id');
                $tableMid  = $tables[$t]->getVar('table_mid');
                $tableName = $tables[$t]->getVar('table_name');
                $fields    = $this->getTableFields($tableMid, $tableId);
                $fieldId   = 'id';
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
                $if_count   = $xc->getXcObjHandlerAll($tableName, $fieldMain, 0, 0, "\t\t");
                $getVar1    = $xc->getXcGetVar('', "{$tableName}All[\$i]", $fieldId, true);
                $getVar2    = $xc->getXcGetVar('', "{$tableName}All[\$i]", $fieldMain, true);
                $fe_content = $xc->getXcAddItem('permform', $getVar1, $getVar2, "\t");
                $if_table   = $xc->getXcObjHandlerCount($tableName, "\t");
                $if_count   .= $pc->getPhpCodeForeach("{$tableName}All", true, false, 'i', $fe_content, "\t\t");
                $if_count   .= $xc->getXcTplAssign('form', '$permform->render()', true, "\t\t");
                $if_table   .= $pc->getPhpCodeConditions("\${$tableName}Count", ' > ', '0', $if_count, false, "\t");
                $if_table   .= $xc->getXcEqualsOperator('$permFound', 'true', null, false, "\t");
                $cond       = "\$op === 'approve_{$tableName}' || \$op === 'submit_{$tableName}' || \$op === 'view_{$tableName}'";
                $ret        .= $pc->getPhpCodeConditions($cond, '', '', $if_table, false);
            }
        }

        $ret .= $pc->getPhpCodeUnset('permform');

        $elseInter = $xc->getXcRedirectHeader("'permissions.php'", '', '3', "{$language}NO_PERMISSIONS_SET", false, "\t");
        $elseInter .= $this->getSimpleString("exit();", "\t");
        $ret .= $pc->getPhpCodeConditions('$permFound', ' !== ', 'true', $elseInter, false);

        return $ret;
    }

    /**
     * @public function render
     *
     * @param null
     *
     * @return bool|string
     */
    public function render()
    {
        $module        = $this->getModule();
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language      = $this->getLanguage($moduleDirname, 'AM');
        $content       = $this->getHeaderFilesComments($module, $filename);
        $content       .= $this->getPermissionsHeader($module, $language);
        $content       .= $this->getPermissionsSwitch($module, $language);
        $content       .= $this->getPermissionsBody($module, $language);
        $content       .= $this->getInclude('footer');

        $this->create($moduleDirname, 'admin', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
