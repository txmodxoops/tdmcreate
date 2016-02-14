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

/**
 * Class AdminPermissions.
 */
class AdminPermissions extends TDMCreateFile
{
    /*
    * @var mixed
    */
    private $xoopscode = null;

    /*
    * @var mixed
    */
    private $adminxoopscode = null;

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
        $this->phpcode = TDMCreatePhpCode::getInstance();
        $this->xoopscode = TDMCreateXoopsCode::getInstance();
        $this->adminxoopscode = AdminXoopsCode::getInstance();
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
     * @private function getPermissionsHeader    
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
        $ret .= $this->phpcode->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/xoopsform/grouppermform', true);
        $ret .= $this->xoopscode->getXoopsHandlerLine($moduleDirname, $tableName);
        $ret .= $this->getCommentLine('Check admin have access to this page');
        $ret .= $this->adminxoopscode->getAdminTemplateMain($moduleDirname, 'permissions');
        $ret .= $this->xoopscode->getXoopsCodeTplAssign('navigation', "\$adminMenu->addNavigation('permissions.php')");
        $ret .= $this->xoopscode->getXoopsCodeXoopsRequest('op', 'op', 'global');
        $ret .= $this->xoopscode->getXoopsCodeLoad('XoopsFormLoader');
        $optionsSelect = array('global' => "{$language}PERMISSIONS_GLOBAL", 'approve' => "{$language}PERMISSIONS_APPROVE",
                                'submit' => "{$language}PERMISSIONS_SUBMIT", 'view' => "{$language}PERMISSIONS_VIEW", );
        $formSelect = $this->xoopscode->getXoopsFormSelect('formSelect', '\'\'', 'op', $optionsSelect, 'onchange="document.fselperm.submit()"');
        $ret .= $this->xoopscode->getXoopsSimpleForm('permTableForm', 'formSelect', $formSelect, '\'\'', 'fselperm', 'permissions');

        return $ret;
    }

    /*
     *  @private function getPermissionsSwitch
     *  @param $moduleDirname
     *  @param $language
     *
     * @return string
     */
    private function getPermissionsSwitch($moduleDirname, $language)
    {
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

        $contentSwitch = $this->phpcode->getPhpCodeCaseSwitch($cases, true, false, "\t");

        return $this->phpcode->getPhpCodeSwitch('op', $contentSwitch);
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

        $ret = $this->xoopscode->getXoopsCodeGetVar('moduleId', 'xoopsModule', 'mid');
        $ret .= $this->xoopscode->getXoopsCodeGroupPermForm('permform', '$formTitle', '$moduleId', '$permName', '$permDesc', "'admin/permissions.php'");
        $foreach1 = $this->xoopscode->getXoopsCodeAddItem('permform', '$gPermId', '$gPermName');
        $if1 = $this->phpcode->getPhpCodeForeach('globalPerms', false, 'gPermId', 'gPermName', $foreach1, "\t");
        $if1 .= $this->xoopscode->getXoopsCodeTplAssign('form', '$permform->render()');
        $else = $this->xoopscode->getXoopsCodeObjHandlerCount($tableName);
        $else .= $this->xoopscode->getXoopsCodeObjHandlerAll($tableName, $fieldMain);
        $getVar1 = $this->xoopscode->getXoopsCodeGetVar('', "{$tableName}All[\$i]", $fieldId, true);
        $getVar2 = $this->xoopscode->getXoopsCodeGetVar('', "{$tableName}All[\$i]", $fieldMain, true);
        $foreach2 = $this->xoopscode->getXoopsCodeAddItem('permform', $getVar1, $getVar2)."\r";
        $else .=  $this->phpcode->getPhpCodeForeach("{$tableName}All", true, false, 'i', $foreach2, "\t");
        $if2 = $this->xoopscode->getXoopsCodeTplAssign('form', '$permform->render()');
        $elseInter = $this->xoopscode->getXoopsCodeRedirectHeader($tableName.'.php', '?op=new', '3', "{$language}NO_PERMISSIONS_SET");
        $elseInter .= $this->getSimpleString("\texit();");
        $else .= $this->phpcode->getPhpCodeConditions("\${$tableName}Count", ' > ', '0', $if2, $elseInter, "\t");

        $ret .= $this->phpcode->getPhpCodeConditions('$op', ' == ', "'global'", $if1, $else);
        $ret .= $this->phpcode->getPhpCodeUnset('permform');

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
