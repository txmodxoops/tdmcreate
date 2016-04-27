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
    /**
    * @var mixed
    */
    private $uc = null;

    /**
    * @var string
    */
    private $xc = null;

    /**
     *  @public function constructor
     *  @param null
     */

    public function __construct()
    {
        parent::__construct();
        $this->xc = TDMCreateXoopsCode::getInstance();
        $this->phpcode = TDMCreatePhpCode::getInstance();
        $this->uc = UserXoopsCode::getInstance();
    }

    /**
     *  @static function &getInstance
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

    /**
     *  @public function write
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

    /**
     *  @public function getUserBrokenHeader
     * @param $moduleDirname
     * @param $fields
     * @return string
     */
    public function getUserBrokenHeader($moduleDirname, $fields)
    {
        $fieldId = $this->xc->getXcSaveFieldId($fields);
        $ccFieldId = $this->getCamelCase($fieldId, false, true);
        $ret = $this->getInclude();
        $ret .= $this->xc->getXcXoopsRequest('op', 'op', 'list');
        $ret .= $this->xc->getXcXoopsRequest("{$ccFieldId}", "{$fieldId}", '', 'Int');
        $ret .= $this->phpcode->getPhpCodeCommentLine('Template');
        $ret .= $this->uc->getUserTplMain($moduleDirname, 'broken');
        $ret .= $this->phpcode->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'header', true);
        $ret .= $this->xc->getXcAddStylesheet();
        $ret .= $this->phpcode->getPhpCodeCommentLine('Redirection if not permissions');
        $condIf = $this->xc->getXcRedirectHeader('index', '', '2', '_NOPERM');
        $condIf .= $this->getSimpleString('exit();');
        $ret .= $this->phpcode->getPhpCodeConditions('$permSubmit', ' == ', 'false', $condIf);

        return $ret;
    }

    /**
     *  @public function getAdminPagesList
     * @param $tableName
     * @param $language
     * @return string
     */
    public function getUserBrokenForm($tableName, $language)
    {
        $ret = $this->phpcode->getPhpCodeCommentLine('Mavigation');
        $ret .= $this->xc->getXcEqualsOperator('$navigation', "{$language}SUBMIT_PROPOSER");
        $ret .= $this->xc->getXcTplAssign('navigation', '$navigation');
        $ret .= $this->phpcode->getPhpCodeCommentLine('Title of page');
        $ret .= $this->xc->getXcEqualsOperator('$title', "{$language}SUBMIT_PROPOSER . '&nbsp;-&nbsp;'");
        $ret .= $this->xc->getXcEqualsOperator('$title.', "\$GLOBALS['xoopsModule']->name()");
        $ret .= $this->xc->getXcTplAssign('xoops_pagetitle', '$title');
        $ret .= $this->phpcode->getPhpCodeCommentLine('Description');
        $ret .= $this->uc->getUserAddMeta('description', $language, 'SUBMIT_PROPOSER');
        $ret .= $this->phpcode->getPhpCodeCommentLine('Form Create');
        $ret .= $this->xc->getXcObjHandlerCreate($tableName);
        $ret .= $this->xc->getXcGetForm('form', $tableName, 'Obj');
        $ret .= $this->xc->getXcTplAssign('form', '$form->render()');

        return $ret;
    }

    /**
     *  @public function getUserBrokenSave
     * @param $moduleDirname
     * @param $fields
     * @param $tableName
     * @param $tableAutoincrement
     * @param $language
     * @return string
     */
    public function getUserBrokenSave($moduleDirname, $fields, $tableName, $tableAutoincrement, $language)
    {
        $fieldId = $this->xc->getXcSaveFieldId($fields);
        $ucfTableName = ucfirst($tableName);
        $ret = $this->phpcode->getPhpCodeCommentLine('Security Check');
        $xoopsSecurityCheck = $this->xc->getXcSecurityCheck();
        $securityError = $this->xc->getXcSecurityErrors();
        $implode = $this->phpcode->getPhpCodeImplode(',', $securityError);
        $redirectError = $this->xc->getXcRedirectHeader($tableName, '', '3', $implode);
        $ret .= $this->phpcode->getPhpCodeConditions($xoopsSecurityCheck, '', '', $redirectError, false, "\t");
        $ret .= $this->xc->getXcObjHandlerCreate($tableName);

        $ret .= $this->getSimpleString('$error = false;');
        $ret .= $this->getSimpleString("\$errorMessage = '';");
        $ret .= $this->phpcode->getPhpCodeCommentLine('Test first the validation');
        $ret .= $this->xc->getXcLoad('captcha');
        $ret .= $this->xc->getXcXoopsCaptcha();

        $ret .= $this->phpcode->getPhpCodeConditions('!$xoopsCaptcha->verify()', '', '', "\$errorMessage .= \$xoopsCaptcha->getMessage().'<br>';\n\$error = true;\n", false, "\t");

        $ret .= $this->xc->getXcSaveElements($moduleDirname, $tableName, $tableAutoincrement, $fields);

        $condElse = $this->phpcode->getPhpCodeCommentLine('Insert Data');
        $insert = $this->xc->getXcInsert($tableName, $tableName, 'Obj', true);
        $redirctHeader = $this->xc->getXcRedirectHeader('index', '', '2', "{$language}FORM_OK");
        $condElse .= $this->phpcode->getPhpCodeConditions($insert, '', '', $redirctHeader, false, "\t\t");
        $assigne = $this->xc->getXcTplAssign('error_message', '$errorMessage');
        $ret .= $this->phpcode->getPhpCodeConditions('$error', ' == ', 'true', $assigne, $condElse, "\t");

        $ret .= $this->phpcode->getPhpCodeCommentLine('Get Form Error');
        $ret .= $this->xc->getXcTplAssign('error', "\${$tableName}Obj->getHtmlErrors()");
        $ret .= $this->xc->getXcGetForm('form', $tableName, 'Obj');
        $ret .= $this->xc->getXcTplAssign('form', '$form->display()');

        return $ret;
    }

    /**
      *  @private function getUserSubmitSwitch
     * @param $moduleDirname
     * @param $tableName
     * @param $tableAutoincrement
     * @param $language
     * @return string
     */
    private function getUserBrokenSwitch($moduleDirname, $tableName, $tableAutoincrement, $language)
    {
        $table = $this->getTable();
        $tableId = $table->getVar('table_id');
        $tableMid = $table->getVar('table_mid');
        $fields = $this->getTableFields($tableMid, $tableId);
        $cases = array('form' => array($this->getUserBrokenForm($tableName, $language)),
                    'save' => array($this->getUserBrokenSave($moduleDirname, $fields, $tableName, $tableAutoincrement, $language)), );

        return $this->xc->getXcSwitch('op', $cases, true);
    }

    /**
     *  @public function render
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
        $tableAutoincrement = $table->getVar('table_autoincrement');
        $fields = $this->getTableFields($tableMid, $tableId);
        $language = $this->getLanguage($moduleDirname, 'MA');
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getUserBrokenHeader($moduleDirname, $fields);
        $content .= $this->getUserBrokenSwitch($moduleDirname, $tableName, $tableAutoincrement, $language);
        $content .= $this->getInclude('footer');

        $this->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
