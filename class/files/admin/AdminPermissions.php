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
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 * @version         $Id: AdminPermissions.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class AdminPermissions.
 */
class AdminPermissions extends TDMCreateFile
{
    /**
     *  @public function constructor
     *
     *  @param null
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *  @static function getInstance
     *
     *  @param null
     *
     *  @return AdminPermissions
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
     *  @public function write
     *
     *  @param string $module
     *  @param mixed $tables
     *  @param string $filename
     *
     *  @return string
     */
    public function write($module, $tables, $filename)
    {
        $this->setModule($module);
        $this->setTables($tables);
        $this->setFileName($filename);
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
        $pc = TDMCreatePhpCode::getInstance();
        $xc = TDMCreateXoopsCode::getInstance();
        $cc = ClassXoopsCode::getInstance();
        $axc = AdminXoopsCode::getInstance();
        $moduleDirname = $module->getVar('mod_dirname');
        $tables = $this->getTableTables($module->getVar('mod_id'));
        foreach (array_keys($tables) as $t) {
            if (1 == $tables[$t]->getVar('table_permissions')) {
                $tableName = $tables[$t]->getVar('table_name');
            }
        }
        $ret = $this->getInclude('header');
        $ret .= $pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/xoopsform/grouppermform', true);
        $ret .= $xc->getXoopsHandlerLine($moduleDirname, $tableName);
        $ret .= $pc->getPhpCodeCommentLine('Check admin have access to this page');
        $ret .= $axc->getAdminTemplateMain($moduleDirname, 'permissions');
        $ret .= $xc->getXcTplAssign('navigation', "\$adminMenu->addNavigation('permissions.php')");
        $ret .= $xc->getXcXoopsRequest('op', 'op', 'global');
        $ret .= $xc->getXcLoad('XoopsFormLoader');
        $optionsSelect = array('global' => "{$language}PERMISSIONS_GLOBAL", 'approve' => "{$language}PERMISSIONS_APPROVE",
                                'submit' => "{$language}PERMISSIONS_SUBMIT", 'view' => "{$language}PERMISSIONS_VIEW", );
        $formSelect = $xc->getXoopsFormSelectExtraOptions('formSelect', '\'\'', 'op', $optionsSelect, 'onchange="document.fselperm.submit()"');
        $ret .= $cc->getXoopsSimpleForm('permTableForm', 'formSelect', $formSelect, '\'\'', 'fselperm', 'permissions');

        return $ret;
    }

    /**
     *  @private function getPermissionsSwitch
     *  @param $moduleDirname
     *  @param $language
     *
     *  @return string
     */
    private function getPermissionsSwitch($moduleDirname, $language)
    {
        $pc = TDMCreatePhpCode::getInstance();
        $cases = array('global' => array("\$formTitle = {$language}PERMISSIONS_GLOBAL;",
                                        "\$permName = '{$moduleDirname}_ac';",
                                        "\$permDesc = {$language}PERMISSIONS_GLOBAL_DESC;",
                                        "\$globalPerms = array( '4' => {$language}PERMISSIONS_GLOBAL_4, '8' => {$language}PERMISSIONS_GLOBAL_8, '16' => {$language}PERMISSIONS_GLOBAL_16 );", ),
                        'approve' => array("\$formTitle = {$language}PERMISSIONS_APPROVE;",
                                        "\$permName = '{$moduleDirname}_approve';",
                                        "\$permDesc = {$language}PERMISSIONS_APPROVE_DESC;", ),
                        'submit' => array("\$formTitle = {$language}PERMISSIONS_SUBMIT;",
                                        "\$permName = '{$moduleDirname}_submit';",
                                        "\$permDesc = {$language}PERMISSIONS_SUBMIT_DESC;", ),
                        'view' => array("\$formTitle = {$language}PERMISSIONS_VIEW;",
                                        "\$permName = '{$moduleDirname}_view';",
                                        "\$permDesc = {$language}PERMISSIONS_VIEW_DESC;", ), );

        $contentSwitch = $pc->getPhpCodeCaseSwitch($cases, true, false, "\t");

        return $pc->getPhpCodeSwitch('op', $contentSwitch);
    }

    /**
     *  @private function getPermissionsBody
     *
     *  @param string $module
     *  @param string $language
     *
     *  @return string
     */
    private function getPermissionsBody($module, $language)
    {
        $pc = TDMCreatePhpCode::getInstance();
        $xc = TDMCreateXoopsCode::getInstance();
        $tables = $this->getTableTables($module->getVar('mod_id'));
        $tableName = '';
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

        $ret = $xc->getXcGetVar('moduleId', 'xoopsModule', 'mid');
        $ret .= $xc->getXcGroupPermForm('permform', '$formTitle', '$moduleId', '$permName', '$permDesc', "'admin/permissions.php'");
        $foreach1 = $xc->getXcAddItem('permform', '$gPermId', '$gPermName', "\t");
        $if1 = $pc->getPhpCodeForeach('globalPerms', false, 'gPermId', 'gPermName', $foreach1, "\t");
        $if1 .= $xc->getXcTplAssign('form', '$permform->render()', true, "\t");
        $else = $xc->getXcObjHandlerCount($tableName, "\t");
        $if2 = $xc->getXcObjHandlerAll($tableName, $fieldMain, 0, 0, "\t\t");
        $getVar1 = $xc->getXcGetVar('', "{$tableName}All[\$i]", $fieldId, true);
        $getVar2 = $xc->getXcGetVar('', "{$tableName}All[\$i]", $fieldMain, true);
        $foreach2 = $xc->getXcAddItem('permform', $getVar1, $getVar2, "\t")."\r";
        $if2 .= $pc->getPhpCodeForeach("{$tableName}All", true, false, 'i', $foreach2, "\t\t");
        $if2 .= $xc->getXcTplAssign('form', '$permform->render()', true, "\t\t");
        $elseInter = $xc->getXcRedirectHeader($tableName, '?op=new', '3', "{$language}NO_PERMISSIONS_SET", true, "\t\t");
        $elseInter .= $this->getSimpleString("\t\texit();");
        $else .= $pc->getPhpCodeConditions("\${$tableName}Count", ' > ', '0', $if2, $elseInter, "\t");

        $ret .= $pc->getPhpCodeConditions('$op', ' == ', "'global'", $if1, $else);
        $ret .= $pc->getPhpCodeUnset('permform');

        return $ret;
    }

    /**
     *  @public function render
     *
     *  @param null
     *
     *  @return bool|string
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

        $this->create($moduleDirname, 'admin', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
