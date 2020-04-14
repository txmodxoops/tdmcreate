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
 * Class UserBroken.
 */
class UserBroken extends Files\CreateFile
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
     * @return UserBroken
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
     * @param       $module
     * @param mixed $table
     * @param       $filename
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /**
     * @public function getUserBrokenHeader
     * @param $moduleDirname
     * @param $fields
     * @return string
     */
    public function getUserBrokenHeader($moduleDirname, $fields)
    {
        $xc        = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $pc        = Tdmcreate\Files\CreatePhpCode::getInstance();
        $uc        = UserXoopsCode::getInstance();
        $fieldId   = $xc->getXcSaveFieldId($fields);
        $ccFieldId = $this->getCamelCase($fieldId, false, true);
        $ret       = $pc->getPhpCodeUseNamespace(['Xmf', 'Request'], '', '');
        $ret       .= $pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname], '', '');
        $ret       .= $pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname, 'Constants']);
        $ret       .= $this->getInclude();
        $ret       .= $xc->getXcXoopsRequest('op', 'op', 'list');
        $ret       .= $xc->getXcXoopsRequest($ccFieldId, $fieldId, '', 'Int');
        $ret       .= $pc->getPhpCodeCommentLine('Template');
        $ret       .= $uc->getUserTplMain($moduleDirname, 'broken');
        $ret       .= $pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'header', true);
        $ret       .= $xc->getXcAddStylesheet();
        $ret       .= $pc->getPhpCodeCommentLine('Redirection if not permissions');
        $condIf    = $xc->getXcRedirectHeader('index', '', '2', '_NOPERM', true, "\t");
        $condIf    .= $this->getSimpleString('exit();', "\t");
        $ret       .= $pc->getPhpCodeConditions('$permSubmit', ' === ', 'false', $condIf);

        return $ret;
    }

    /**
     * @public function getAdminPagesList
     * @param $tableName
     * @param $language
     * @return string
     */
    public function getUserBrokenForm($tableName, $language)
    {
        $xc  = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $pc  = Tdmcreate\Files\CreatePhpCode::getInstance();
        $uc  = UserXoopsCode::getInstance();
        $ret = $pc->getPhpCodeCommentLine('Navigation');
        $ret .= $xc->getXcEqualsOperator('$navigation', "{$language}SUBMIT_PROPOSER", null, false, "\t\t");
        $ret .= $xc->getXcTplAssign('navigation', '$navigation', true, "\t\t");
        $ret .= $pc->getPhpCodeCommentLine('Title of page', null, "\t\t");
        $ret .= $xc->getXcEqualsOperator('$title', "{$language}SUBMIT_PROPOSER . '&nbsp;-&nbsp;'", null, false, "\t\t");
        $ret .= $xc->getXcEqualsOperator('$title', "\$GLOBALS['xoopsModule']->name()", '.', false, "\t\t");
        $ret .= $xc->getXcTplAssign('xoops_pagetitle', '$title', true, "\t\t");
        $ret .= $pc->getPhpCodeCommentLine('Description', null, "\t\t");
        $ret .= $uc->getUserAddMeta('description', $language, 'SUBMIT_PROPOSER', "\t\t");
        $ret .= $pc->getPhpCodeCommentLine('Form Create', null, "\t\t");
        $ret .= $xc->getXcObjHandlerCreate($tableName, "\t\t");
        $ret .= $xc->getXcGetForm('form', $tableName, 'Obj', "\t\t");
        $ret .= $xc->getXcTplAssign('form', '$form->render()', true, "\t\t");

        return $ret;
    }

    /**
     * @public function getUserBrokenSave
     * @param $moduleDirname
     * @param $fields
     * @param $tableName
     * @param $tableSoleName
     * @param $language
     * @return string
     */
    public function getUserBrokenSave($moduleDirname, $fields, $tableName, $tableSoleName, $language)
    {
        $xc                 = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $pc                 = Tdmcreate\Files\CreatePhpCode::getInstance();
        $ret                = $pc->getPhpCodeCommentLine('Security Check');
        $xoopsSecurityCheck = $xc->getXcSecurityCheck();
        $securityError      = $xc->getXcSecurityErrors();
        $implode            = $pc->getPhpCodeImplode(',', $securityError);
        $redirectError      = $xc->getXcRedirectHeader($tableName, '', '3', $implode, true, "\t\t\t");
        $ret                .= $pc->getPhpCodeConditions($xoopsSecurityCheck, '', '', $redirectError, false, "\t\t");
        $ret                .= $xc->getXcObjHandlerCreate($tableName, "\t\t");

        $ret .= $this->getSimpleString('$error = false;', "\t\t");
        $ret .= $this->getSimpleString("\$errorMessage = '';", "\t\t");
        $ret .= $pc->getPhpCodeCommentLine('Test first the validation', null, "\t\t");
        $ret .= $xc->getXcLoad('captcha', "\t\t");
        $ret .= $xc->getXcXoopsCaptcha("\t\t");

        $ret .= $pc->getPhpCodeConditions('!$xoopsCaptcha->verify()', '', '', "\t\t\t\$errorMessage .= \$xoopsCaptcha->getMessage().'<br>';\n\t\t\t\$error = true;\n", false, "\t\t");

        $ret .= $xc->getXcSaveElements($moduleDirname, $tableName, $tableSoleName, $fields, "\t\t");

        $condElse      = $pc->getPhpCodeCommentLine('Insert Data', null, "\t\t\t");
        $insert        = $xc->getXcInsert($tableName, $tableName, 'Obj', true);
        $redirctHeader = $xc->getXcRedirectHeader('index', '', '2', "{$language}FORM_OK", true, "\t\t\t\t");
        $condElse      .= $pc->getPhpCodeConditions($insert, '', '', $redirctHeader, false, "\t\t\t");
        $assigne       = $xc->getXcTplAssign('error_message', '$errorMessage', true, "\t\t\t");
        $ret           .= $pc->getPhpCodeConditions('$error', ' === ', 'true', $assigne, $condElse, "\t\t");

        $ret .= $pc->getPhpCodeCommentLine('Get Form Error', null, "\t\t");
        $ret .= $xc->getXcTplAssign('error', "\${$tableName}Obj->getHtmlErrors()", true, "\t\t");
        $ret .= $xc->getXcGetForm('form', $tableName, 'Obj', "\t\t");
        $ret .= $xc->getXcTplAssign('form', '$form->display()', true, "\t\t");

        return $ret;
    }

    /**
     * @private function getUserSubmitSwitch
     * @param $moduleDirname
     * @param $tableName
     * @param $tableSoleName
     * @param $language
     * @return bool|string
     */
    private function getUserBrokenSwitch($moduleDirname, $tableName, $tableSoleName, $language)
    {
        $xc       = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $table    = $this->getTable();
        $tableId  = $table->getVar('table_id');
        $tableMid = $table->getVar('table_mid');
        $fields   = $this->getTableFields($tableMid, $tableId);
        $cases    = [
            'form' => [$this->getUserBrokenForm($tableName, $language)],
            'save' => [$this->getUserBrokenSave($moduleDirname, $fields, $tableName, $tableSoleName, $language)],
        ];

        return $xc->getXcSwitch('op', $cases, true, false);
    }

    /**
     * @public function render
     * @param null
     * @return bool|string
     */
    public function render()
    {
        $module             = $this->getModule();
        $table              = $this->getTable();
        $filename           = $this->getFileName();
        $moduleDirname      = $module->getVar('mod_dirname');
        $tableId            = $table->getVar('table_id');
        $tableMid           = $table->getVar('table_mid');
        $tableName          = $table->getVar('table_name');
        $tableSoleName      = $table->getVar('table_solename');
        $fields             = $this->getTableFields($tableMid, $tableId);
        $language           = $this->getLanguage($moduleDirname, 'MA');
        $content            = $this->getHeaderFilesComments($module, $filename);
        $content            .= $this->getUserBrokenHeader($moduleDirname, $fields);
        $content            .= $this->getUserBrokenSwitch($moduleDirname, $tableName, $tableSoleName, $language);
        $content            .= $this->getInclude('footer');

        $this->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
