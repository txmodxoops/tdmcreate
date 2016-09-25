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

/**
 * Class UserBroken.
 */
class UserBroken extends TDMCreateFile
{
    /*
     *  @public function constructor
     *  @param null
     */

    public function __construct()
    {
        parent::__construct();
    }

    /*
     *  @static function getInstance
     *  @param null
     */
    /**
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
        $xc = TDMCreateXoopsCode::getInstance();
        $pc = TDMCreatePhpCode::getInstance();
        $uc = UserXoopsCode::getInstance();
        $fieldId = $xc->getXcSaveFieldId($fields);
        $ccFieldId = $this->getCamelCase($fieldId, false, true);
        $ret = $this->getInclude();
        $ret .= $xc->getXcXoopsRequest('op', 'op', 'list');
        $ret .= $xc->getXcXoopsRequest("{$ccFieldId}", "{$fieldId}", '', 'Int');
        $ret .= $pc->getPhpCodeCommentLine('Template');
        $ret .= $uc->getUserTplMain($moduleDirname, 'broken');
        $ret .= $pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'header', true);
        $ret .= $xc->getXcAddStylesheet();
        $ret .= $pc->getPhpCodeCommentLine('Redirection if not permissions');
        $condIf = $xc->getXcRedirectHeader('index', '', '2', '_NOPERM');
        $condIf .= $this->getSimpleString('exit();');
        $ret .= $pc->getPhpCodeConditions('$permSubmit', ' == ', 'false', $condIf);

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
        $xc = TDMCreateXoopsCode::getInstance();
        $pc = TDMCreatePhpCode::getInstance();
        $uc = UserXoopsCode::getInstance();
        $ret = $pc->getPhpCodeCommentLine('Mavigation');
        $ret .= $xc->getXcEqualsOperator('$navigation', "{$language}SUBMIT_PROPOSER");
        $ret .= $xc->getXcTplAssign('navigation', '$navigation');
        $ret .= $pc->getPhpCodeCommentLine('Title of page');
        $ret .= $xc->getXcEqualsOperator('$title', "{$language}SUBMIT_PROPOSER . '&nbsp;-&nbsp;'");
        $ret .= $xc->getXcEqualsOperator('$title.', "\$GLOBALS['xoopsModule']->name()");
        $ret .= $xc->getXcTplAssign('xoops_pagetitle', '$title');
        $ret .= $pc->getPhpCodeCommentLine('Description');
        $ret .= $uc->getUserAddMeta('description', $language, 'SUBMIT_PROPOSER');
        $ret .= $pc->getPhpCodeCommentLine('Form Create');
        $ret .= $xc->getXcObjHandlerCreate($tableName);
        $ret .= $xc->getXcGetForm('form', $tableName, 'Obj');
        $ret .= $xc->getXcTplAssign('form', '$form->render()');

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
    public function getUserBrokenSave($moduleDirname, $fields, $tableName, $tableSolename, $tableAutoincrement, $language)
    {
        $xc = TDMCreateXoopsCode::getInstance();
        $pc = TDMCreatePhpCode::getInstance();
        $fieldId = $xc->getXcSaveFieldId($fields);
        $ucfTableName = ucfirst($tableName);
        $ret = $pc->getPhpCodeCommentLine('Security Check');
        $xoopsSecurityCheck = $xc->getXcSecurityCheck();
        $securityError = $xc->getXcSecurityErrors();
        $implode = $pc->getPhpCodeImplode(',', $securityError);
        $redirectError = $xc->getXcRedirectHeader($tableName, '', '3', $implode);
        $ret .= $pc->getPhpCodeConditions($xoopsSecurityCheck, '', '', $redirectError, false, "\t");
        $ret .= $xc->getXcObjHandlerCreate($tableName);

        $ret .= $this->getSimpleString('$error = false;');
        $ret .= $this->getSimpleString("\$errorMessage = '';");
        $ret .= $pc->getPhpCodeCommentLine('Test first the validation');
        $ret .= $xc->getXcLoad('captcha');
        $ret .= $xc->getXcXoopsCaptcha();

        $ret .= $pc->getPhpCodeConditions('!$xoopsCaptcha->verify()', '', '', "\$errorMessage .= \$xoopsCaptcha->getMessage().'<br>';\n\$error = true;\n", false, "\t");

        $ret .= $xc->getXcSaveElements($moduleDirname, $tableName, $tableSolename, $tableAutoincrement, $fields);

        $condElse = $pc->getPhpCodeCommentLine('Insert Data');
        $insert = $xc->getXcInsert($tableName, $tableName, 'Obj', true);
        $redirctHeader = $xc->getXcRedirectHeader('index', '', '2', "{$language}FORM_OK");
        $condElse .= $pc->getPhpCodeConditions($insert, '', '', $redirctHeader, false, "\t\t");
        $assigne = $xc->getXcTplAssign('error_message', '$errorMessage');
        $ret .= $pc->getPhpCodeConditions('$error', ' == ', 'true', $assigne, $condElse, "\t");

        $ret .= $pc->getPhpCodeCommentLine('Get Form Error');
        $ret .= $xc->getXcTplAssign('error', "\${$tableName}Obj->getHtmlErrors()");
        $ret .= $xc->getXcGetForm('form', $tableName, 'Obj');
        $ret .= $xc->getXcTplAssign('form', '$form->display()');

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
    private function getUserBrokenSwitch($moduleDirname, $tableName, $tableSolename, $tableAutoincrement, $language)
    {
        $xc = TDMCreateXoopsCode::getInstance();
        $table = $this->getTable();
        $tableId = $table->getVar('table_id');
        $tableMid = $table->getVar('table_mid');
        $fields = $this->getTableFields($tableMid, $tableId);
        $cases = array('form' => array($this->getUserBrokenForm($tableName, $language)),
                    'save' => array($this->getUserBrokenSave($moduleDirname, $fields, $tableName, $tableSolename, $tableAutoincrement, $language)), );

        return $xc->getXcSwitch('op', $cases, true);
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
        $tableSolename = $table->getVar('table_solename');
        $tableAutoincrement = $table->getVar('table_autoincrement');
        $fields = $this->getTableFields($tableMid, $tableId);
        $language = $this->getLanguage($moduleDirname, 'MA');
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getUserBrokenHeader($moduleDirname, $fields);
        $content .= $this->getUserBrokenSwitch($moduleDirname, $tableName, $tableSolename, $tableAutoincrement, $language);
        $content .= $this->getInclude('footer');

        $this->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
