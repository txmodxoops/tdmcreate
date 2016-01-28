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
 * @version         $Id: UserBroken.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class UserBroken.
 */
class UserBroken extends TDMCreateFile
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
        $this->xoopscode = TDMCreateXoopsCode::getInstance();
        $this->phpcode = TDMCreatePhpCode::getInstance();
        $this->usercode = UserXoopsCode::getInstance();
    }

    /*
     *  @static function &getInstance
     *  @param null
     */
    /**
     * @return UserBroken
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
     *  @param $module
     *  @param mixed $table
     *  @param $filename
     */
    /**
     * @param $module
     * @param $table
     * @param $filename
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /*
     *  @public function getUserBrokenHeader
     *  @param null
     */
    /**
     * @param $moduleDirname
     *
     * @return
     */
    public function getUserBrokenHeader($moduleDirname, $fields)
    {
        $fieldId = $this->xoopscode->getXoopsCodeSaveFieldId($fields);
        $ccFieldId = $this->getCamelCase($fieldId, false, true);
        $ret = $this->getInclude();
        $ret .= $this->xoopscode->getXoopsCodeXoopsRequest('op', 'op', 'list');
        $ret .= $this->xoopscode->getXoopsCodeXoopsRequest("{$ccFieldId}", "{$fieldId}", '', 'Int');
        $ret .= $this->getCommentLine('Template');
        $ret .= $this->usercode->getUserTplMain($moduleDirname, 'broken');
        $ret .= $this->phpcode->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'header', true);
        $ret .= $this->xoopscode->getXoopsCodeAddStylesheet();
        $ret .= $this->getCommentLine('Redirection if not permissions');
        $condIf = $this->xoopscode->getXoopsCodeRedirectHeader('index', '', '2', '_NOPERM');
        $condIf .= $this->getSimpleString('exit();');
        $ret .= $this->phpcode->getPhpCodeConditions('$permSubmit', ' == ', 'false', $condIf, false);

        return $ret;
    }

    /*
     *  @public function getAdminPagesList
      *  @param $tableName
      *  @param $language     
      *
      *  @return
      */
    public function getUserBrokenForm($tableName, $language)
    {
        $ret = $this->getCommentLine('Mavigation');
        $ret .= $this->xoopscode->getXoopsCodeEqualsOperator('$navigation', "{$language}SUBMIT_PROPOSER");
        $ret .= $this->xoopscode->getXoopsCodeTplAssign('navigation', '$navigation');
        $ret .= $this->getCommentLine('Title of page');
        $ret .= $this->xoopscode->getXoopsCodeEqualsOperator('$title', "{$language}SUBMIT_PROPOSER . '&nbsp;-&nbsp;'");
        $ret .= $this->xoopscode->getXoopsCodeEqualsOperator('$title.', "\$GLOBALS['xoopsModule']->name()");
        $ret .= $this->xoopscode->getXoopsCodeTplAssign('xoops_pagetitle', '$title');
        $ret .= $this->getCommentLine('Description');
        $ret .= $this->usercode->getUserAddMeta('description', $language, 'SUBMIT_PROPOSER');
        $ret .= $this->getCommentLine('Form Create');
        $ret .= $this->xoopscode->getXoopsCodeObjHandlerCreate($tableName);
        $ret .= $this->xoopscode->getXoopsCodeGetForm('form', $tableName, 'Obj');
        $ret .= $this->xoopscode->getXoopsCodeTplAssign('form', '$form->render()');

        return $ret;
    }

    /*
     *  @public function getUserBrokenSave
     *  @param $moduleDirname
     *  @param $tableName
     */
    /**
     * @param $moduleDirname
     * @param $table_id
     * @param $tableName
     *
     * @return
     */
    public function getUserBrokenSave($moduleDirname, $fields, $tableName, $language)
    {
        $fieldId = $this->xoopscode->getXoopsCodeSaveFieldId($fields);
        $ucfTableName = ucfirst($tableName);
        $ret = $this->phpcode->getPhpCodeCommentLine('Security Check');
        $xoopsSecurityCheck = $this->xoopscode->getXoopsCodeSecurityCheck();
        $securityError = $this->xoopscode->getXoopsCodeSecurityErrors();
        $implode = $this->phpcode->getPhpCodeImplode(',', $securityError);
        $redirectError = $this->xoopscode->getXoopsCodeRedirectHeader($tableName, '', '3', $implode);
        $ret .= $this->phpcode->getPhpCodeConditions($xoopsSecurityCheck, '', '', $redirectError, false, "\t");
        $ret .= $this->xoopscode->getXoopsCodeObjHandlerCreate($tableName);

        $ret .= $this->getSimpleString('$error = false;');
        $ret .= $this->getSimpleString("\$errorMessage = '';");
        $ret .= $this->getCommentLine('Test first the validation');
        $ret .= $this->xoopscode->getXoopsCodeLoad('captcha');
        $ret .= $this->xoopscode->getXoopsCodeXoopsCaptcha();

        $ret .= $this->phpcode->getPhpCodeConditions('!$xoopsCaptcha->verify()', '', '', "\$errorMessage .= \$xoopsCaptcha->getMessage().'<br>';\n\$error = true;\n", false, "\t");

        $ret .= $this->xoopscode->getXoopsCodeSaveElements($moduleDirname, $tableName, $fields);

        $condElse = $this->getCommentLine('Insert Data');
        $insert = $this->xoopscode->getXoopsCodeInsert($tableName, $tableName, 'Obj', true);
        $redirctHeader = $this->xoopscode->getXoopsCodeRedirectHeader('index', '', '2', "{$language}FORM_OK");
        $condElse .= $this->phpcode->getPhpCodeConditions($insert, '', '', $redirctHeader, false, "\t\t");
        $assigne = $this->xoopscode->getXoopsCodeTplAssign('error_message', '$errorMessage');
        $ret .= $this->phpcode->getPhpCodeConditions('$error', ' == ', 'true', $assigne, $condElse, "\t");

        $ret .= $this->getCommentLine('Get Form Error');
        $ret .= $this->xoopscode->getXoopsCodeTplAssign('error', "\${$tableName}Obj->getHtmlErrors()");
        $ret .= $this->xoopscode->getXoopsCodeGetForm('form', $tableName, 'Obj');
        $ret .= $this->xoopscode->getXoopsCodeTplAssign('form', '$form->display()');

        return $ret;
    }

    /*
      *  @private function getUserSubmitSwitch
      *  @param $moduleDirname
     *  @param $tableName
     *  @param $tableSoleName
     *  @param $language
      *
      * @return
      */
    private function getUserBrokenSwitch($moduleDirname, $tableName, $language)
    {
        $table = $this->getTable();
        $tableId = $table->getVar('table_id');
        $tableMid = $table->getVar('table_mid');
        $fields = $this->getTableFields($tableMid, $tableId);
        $cases = array('form' => array($this->getUserBrokenForm($tableName, $language)),
                    'save' => array($this->getUserBrokenSave($moduleDirname, $fields, $tableName, $language)), );

        return $this->xoopscode->getXoopsCodeSwitch('op', $cases, true);
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
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $tableId = $table->getVar('table_id');
        $tableMid = $table->getVar('table_mid');
        $tableName = $table->getVar('table_name');
        $fields = $this->getTableFields($tableMid, $tableId);
        $language = $this->getLanguage($moduleDirname, 'MA');
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getUserBrokenHeader($moduleDirname, $fields);
        $content .= $this->getUserBrokenSwitch($moduleDirname, $tableName, $language);
        $content .= $this->getInclude('footer');

        $this->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
