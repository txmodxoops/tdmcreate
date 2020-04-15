<?php

namespace XoopsModules\Tdmcreate\Files\User;

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
 * Class UserSubmit.
 */
class UserSubmit extends Files\CreateFile
{
    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @static function getInstance
     * @param null
     * @return UserSubmit
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
     * @param string $module
     * @param mixed  $table
     * @param string $filename
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /**
     * @public function getUserSubmitHeader
     * @param $moduleDirname
     *
     * @param $tablePermissions
     * @return string
     */
    public function getUserSubmitHeader($moduleDirname, $tablePermissions)
    {
        $xc     = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $pc     = Tdmcreate\Files\CreatePhpCode::getInstance();
        $uc     = UserXoopsCode::getInstance();
        $t      = "\t";
        $ret    = $pc->getPhpCodeUseNamespace(['Xmf', 'Request'], '', '');
        $ret    .= $pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname], '', '');
        $ret    .= $pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname, 'Constants']);
        $ret    .= $this->getInclude();
        $ret    .= $xc->getXcLoadLanguage('admin', '', $moduleDirname);
        $ret    .= $pc->getPhpCodeCommentLine('It recovered the value of argument op in URL$');
        $ret    .= $xc->getXcXoopsRequest('op', 'op', 'form');
        $ret    .= $pc->getPhpCodeCommentLine('Template');
        $ret    .= $uc->getUserTplMain($moduleDirname, 'submit');
        $ret    .= $pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'header', true);
        $ret    .= $xc->getXcAddStylesheet();
        if (1 == $tablePermissions) {
            $ret    .= $xc->getXoopsHandlerLine('permissions');
            $ret    .= $xc->getXcEqualsOperator('$permSubmit', '$permissionsHandler->getPermGlobalSubmit()');
            $ret    .= $pc->getPhpCodeCommentLine('Redirection if not permissions');
            $condIf = $xc->getXcRedirectHeader('index', '', '2', '_NOPERM', true, $t);
            $condIf .= $this->getSimpleString('exit();', $t);
            $ret .= $pc->getPhpCodeConditions('$permSubmit', ' === ', 'false', $condIf, false);
        }

        return $ret;
    }

    /**
     * @public function getAdminPagesList
     * @param        $tableName
     * @param        $language
     * @param string $t
     * @return string
     */
    public function getUserSubmitForm($tableName, $language, $t = '')
    {
        $xc  = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $pc  = Tdmcreate\Files\CreatePhpCode::getInstance();
        $uc  = UserXoopsCode::getInstance();
        $ret = $pc->getPhpCodeCommentLine('Navigation');
        $ret .= $xc->getXcEqualsOperator('$navigation', "{$language}SUBMIT_PROPOSER", '', false, $t . "\t");
        $ret .= $xc->getXcTplAssign('navigation', '$navigation', true, $t . "\t");
        $ret .= $pc->getPhpCodeCommentLine('Title of page', null, $t . "\t");
        $ret .= $xc->getXcEqualsOperator('$title', "{$language}SUBMIT_PROPOSER . '&nbsp;-&nbsp;'", '', false, $t . "\t");
        $ret .= $xc->getXcEqualsOperator('$title', "\$GLOBALS['xoopsModule']->name()", '.', false, $t . "\t");
        $ret .= $xc->getXcTplAssign('xoops_pagetitle', '$title', true, $t . "\t");
        $ret .= $pc->getPhpCodeCommentLine('Description', null, $t . "\t");
        $ret .= $uc->getUserAddMeta('description', $language, 'SUBMIT_PROPOSER', $t . "\t");
        $ret .= $pc->getPhpCodeCommentLine('Form Create', null, $t . "\t");
        $ret .= $xc->getXcObjHandlerCreate($tableName, $t . "\t");
        $ret .= $xc->getXcGetForm('form', $tableName, 'Obj', $t . "\t");
        $ret .= $xc->getXcTplAssign('form', '$form->render()', true, $t . "\t");

        return $ret;
    }

    /**
     * @public function getUserSubmitSave
     * @param string $moduleDirname
     * @param        $fields
     * @param string $tableName
     * @param        $tableSoleName
     * @param        $tableSubmit
     * @param $tablePermissions
     * @param        $language
     * @param string $t
     * @return string
     */
    public function getUserSubmitSave($moduleDirname, $fields, $tableName, $tableSoleName, $tableSubmit, $tablePermissions, $language, $t = '')
    {
        $xc                 = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $pc                 = Tdmcreate\Files\CreatePhpCode::getInstance();
        $ret                = $pc->getPhpCodeCommentLine('Security Check');
        $xoopsSecurityCheck = $xc->getXcSecurityCheck();
        $securityError      = $xc->getXcSecurityErrors();
        $implode            = $pc->getPhpCodeImplode(',', $securityError);
        $redirectError      = $xc->getXcRedirectHeader($tableName, '', '3', $implode, true, $t . "\t");
        $ret                .= $pc->getPhpCodeConditions('!' . $xoopsSecurityCheck, '', '', $redirectError, false, $t);
        $ret                .= $xc->getXcObjHandlerCreate($tableName, $t);
        if (in_array(1, $tableSubmit)) {
            $ret .= $xc->getXcSaveElements($moduleDirname, $tableName, $tableSoleName, $fields, $t);
        }
        $ret       .= $pc->getPhpCodeCommentLine('Insert Data', null, $t);
        $insert    = $xc->getXcInsert($tableName, $tableName, 'Obj', 'Handler');
        $countUploader = 0;
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (0 == $f) {
                $fieldId = $fieldName;
                $ccFieldId = $this->getCamelCase($fieldId, false, true);
            }
            if ($fields[$f]->getVar('field_type') >= 10 && $fields[$f]->getVar('field_type') <= 14) {
                $countUploader++;
            }
        }

        if (1 == $tablePermissions) {
            $ucfTableName  = ucfirst($tableName);
            $ucfFieldId    = $this->getCamelCase($fieldId, true);
            $contentInsert = $xc->getXcEqualsOperator("\$new{$ucfFieldId}", "\${$tableName}Obj->getNewInsertedId{$ucfTableName}()", null, false, $t . "\t");
            $contentInsert .= $pc->getPhpCodeTernaryOperator('permId', "isset(\$_REQUEST['{$fieldId}'])", "\${$ccFieldId}", "\$new{$ucfFieldId}", $t . "\t");
            $contentInsert .= $xc->getXcEqualsOperator('$grouppermHandler', "xoops_getHandler('groupperm')", null, false, $t . "\t");
            $contentInsert .= $xc->getXcEqualsOperator('$mid', "\$GLOBALS['xoopsModule']->getVar('mid')", null, false, $t . "\t");
            $contentInsert .= $this->getPermissionsSave($moduleDirname, 'view_' . $tableName);
            $contentInsert .= $this->getPermissionsSave($moduleDirname, 'submit_' . $tableName);
            $contentInsert .= $this->getPermissionsSave($moduleDirname, 'approve_' . $tableName);
        }

        if ($countUploader > 0) {
            $errIf = $xc->getXcRedirectHeader("'{$tableName}.php?op=edit&{$fieldId}=' . \${$ccFieldId}", '', '5', '$uploaderErrors', false, $t . "\t\t");
            $errElse = $xc->getXcRedirectHeader($tableName, '?op=list', '2', "{$language}FORM_OK", true, $t . "\t\t");
            $confirmOk = $pc->getPhpCodeConditions("''", ' !== ', '$uploaderErrors', $errIf, $errElse, $t . "\t");
        } else {
            $confirmOk = $xc->getXcRedirectHeader('index', '', '2', "{$language}FORM_OK", true, $t . "\t");
        }
        $contentInsert .= $confirmOk;
        $ret       .= $pc->getPhpCodeConditions($insert, '', '', $contentInsert, false, $t);

        $ret .= $pc->getPhpCodeCommentLine('Get Form Error', null, $t);
        $ret .= $xc->getXcTplAssign('error', "\${$tableName}Obj->getHtmlErrors()", true, $t);
        $ret .= $xc->getXcGetForm('form', $tableName, 'Obj', $t);
        $ret .= $xc->getXcTplAssign('form', '$form->display()', true, $t);

        return $ret;
    }

    /**
     * @public function getUserSubmitFooter
     * @param $language
     * @return string
     */
    public function getUserSubmitFooter($language)
    {
        $pc               = Tdmcreate\Files\CreatePhpCode::getInstance();
        $uc               = UserXoopsCode::getInstance();
        $ret              = $pc->getPhpCodeCommentLine('Breadcrumbs');
        $ret              .= $uc->getUserBreadcrumbs($language, 'SUBMIT');
        $ret              .= $this->getInclude('footer');

        return $ret;
    }

    /**
     * @private function getUserSubmitSwitch
     * @param $moduleDirname
     * @param $tableId
     * @param $tableMid
     * @param $tableName
     * @param $tableSoleName
     * @param $tableSubmit
     * @param $tablePermissions
     * @param $language
     * @param $t
     * @return string
     */
    private function getUserSubmitSwitch($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $tableSubmit, $tablePermissions, $language, $t)
    {
        $xc     = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $fields = $this->getTableFields($tableMid, $tableId);
        $cases  = [
            'form' => [$this->getUserSubmitForm($tableName, $language, $t)],
            'save' => [$this->getUserSubmitSave($moduleDirname, $fields, $tableName, $tableSoleName, $tableSubmit, $tablePermissions, $language, $t . "\t")],
        ];

        return $xc->getXcSwitch('op', $cases, true, false);
    }

    /**
     * @private function getPermissionsSave
     * @param $moduleDirname
     * @param string $perm
     *
     * @return string
     */
    private function getPermissionsSave($moduleDirname, $perm = 'view')
    {
        $pc = Tdmcreate\Files\CreatePhpCode::getInstance();
        $xc = Tdmcreate\Files\CreateXoopsCode::getInstance();

        $ret     = $pc->getPhpCodeCommentLine('Permission to', $perm, "\t\t\t");
        $ret .= $xc->getXcDeleteRight('grouppermHandler', "{$moduleDirname}_{$perm}", '$mid', '$permId', false, "\t\t\t");
        $content = $xc->getXcAddRight('grouppermHandler', "{$moduleDirname}_{$perm}", '$permId', '$onegroupId', '$mid', false, "\t");
        $foreach = $pc->getPhpCodeForeach("_POST['groups_{$perm}']", false, false, 'onegroupId', $content, "\t\t\t\t");
        $ret     .= $pc->getPhpCodeConditions("isset(\$_POST['groups_{$perm}'])", null, null, $foreach, false, "\t\t\t");

        return $ret;
    }

    /**
     * @public function render
     * @param null
     * @return bool|string
     */
    public function render()
    {
        $module             = $this->getModule();
        $filename           = $this->getFileName();
        $moduleDirname      = $module->getVar('mod_dirname');
        $tables             = $this->getTableTables($module->getVar('mod_id'));
        $tableSoleName      = '';
        $tableSubmit        = [];
        foreach (array_keys($tables) as $t) {
            $tableId              = $tables[$t]->getVar('table_id');
            $tableMid             = $tables[$t]->getVar('table_mid');
            $tableName            = $tables[$t]->getVar('table_name');
            $tableSoleName        = $tables[$t]->getVar('table_solename');
            $tableSubmit[]        = $tables[$t]->getVar('table_submit');
            $tablePermissions     = $tables[$t]->getVar('table_permissions');
        }
        $language = $this->getLanguage($moduleDirname, 'MA');
        $content  = $this->getHeaderFilesComments($module, $filename);
        $content  .= $this->getUserSubmitHeader($moduleDirname, $tablePermissions);
        $content  .= $this->getUserSubmitSwitch($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $tableSubmit, $tablePermissions, $language, "\t");
        $content  .= $this->getUserSubmitFooter($language);

        $this->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
