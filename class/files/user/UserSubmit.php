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
 * @version         $Id: UserSubmit.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class UserSubmit.
 */
class UserSubmit extends TDMCreateFile
{
    /*
    * @var mixed
    */
    private $usercode = null;

    /*
    * @var mixed
    */
    private $phpcode = null;

    /*
    * @var string
    */
    private $xoopscode = null;

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
     * @return UserSubmit
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
    *  @param mixed $table
    *  @param string $filename
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
     * @public function getUserSubmitHeader    
     * @param $moduleDirname
     *
     * @return string
     */
    public function getUserSubmitHeader($moduleDirname)
    {
        $ret = $this->getInclude();
        $ret .= $this->xoopscode->getXoopsCodeLoadLanguage('admin');
        $ret .= $this->getCommentLine('It recovered the value of argument op in URL$');
        $ret .= $this->xoopscode->getXoopsCodeXoopsRequest('op', 'op', 'form');
        $ret .= $this->getCommentLine('Template');
        $ret .= $this->usercode->getUserTplMain($moduleDirname, 'submit');
        $ret .= $this->phpcode->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'header', true);
        $ret .= $this->xoopscode->getXoopsCodeAddStylesheet();
        $ret .= "\$permSubmit = (\$gpermHandler->checkRight('{$moduleDirname}_ac', 4, \$groups, \$GLOBALS['xoopsModule']->getVar('mid'))) ? true : false;\n";
        $ret .= $this->getCommentLine('Redirection if not permissions');
        $condIf = $this->xoopscode->getXoopsCodeRedirectHeader('index', '', '2', '_NOPERM');
        $condIf .= $this->getSimpleString('exit();');

        $ret .= $this->phpcode->getPhpCodeConditions('$permSubmit', ' == ', 'false', $condIf, false);

        return $ret;
    }

    /*
     * @public function getAdminPagesList
     * @param $tableName
     * @param $language
     *
     * @return string
     */
    public function getUserSubmitForm($tableName, $language)
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
    *  @public function getUserSubmitSave
    *  @param string $moduleDirname
    *  @param string $tableName
    */
    /**
     * @param $moduleDirname
     * @param $table_id
     * @param $tableName
     *
     * @return string
     */
    public function getUserSubmitSave($moduleDirname, $fields, $tableName, $language)
    {
        $ucfTableName = ucfirst($tableName);
        $ret = $this->phpcode->getPhpCodeCommentLine('Security Check');
        $xoopsSecurityCheck = $this->xoopscode->getXoopsCodeSecurityCheck();
        $securityError = $this->xoopscode->getXoopsCodeSecurityErrors();
        $implode = $this->phpcode->getPhpCodeImplode(',', $securityError);
        $redirectError = $this->xoopscode->getXoopsCodeRedirectHeader($tableName, '', '3', $implode);
        $ret .= $this->phpcode->getPhpCodeConditions($xoopsSecurityCheck, '', '', $redirectError, false, "\t");
        $ret .= $this->xoopscode->getXoopsCodeObjHandlerCreate($tableName);

        $ret .= $this->xoopscode->getXoopsCodeSaveElements($moduleDirname, $tableName, $fields);

        $ret .= $this->getCommentLine('Insert Data');
        $insert = $this->xoopscode->getXoopsCodeInsert($tableName, $tableName, 'Obj', true);
        $confirmOk = $this->xoopscode->getXoopsCodeRedirectHeader('index', '', '2', "{$language}FORM_OK");
        $ret .= $this->phpcode->getPhpCodeConditions($insert, '', '', $confirmOk, false, "\t");

        $ret .= $this->getCommentLine('Get Form Error');
        $ret .= $this->xoopscode->getXoopsCodeTplAssign('error', "\${$tableName}Obj->getHtmlErrors()");
        $ret .= $this->xoopscode->getXoopsCodeGetForm('form', $tableName, 'Obj');
        $ret .= $this->xoopscode->getXoopsCodeTplAssign('form', '$form->display()');

        return $ret;
    }

    /*
    *  @public function getUserSubmitFooter
    *  @param null
    */
    /**
     * @return string
     */
    public function getUserSubmitFooter($moduleDirname, $language)
    {
        $stuModuleDirname = strtoupper($moduleDirname);
        $ret = $this->getCommentLine('Breadcrumbs');
        $ret .= $this->usercode->getUserBreadcrumbs($language, 'SUBMIT');
        $ret .= $this->getInclude('footer');

        return $ret;
    }

    /*
     *  @private function getUserSubmitSwitch
     *  @param $moduleDirname
     *  @param $tableId
     *  @param $tableMid
     *  @param $tableName
     *  @param $tableSoleName
     *  @param $language
     *
     * @return string
     */
    private function getUserSubmitSwitch($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $language)
    {
        $fields = $this->getTableFields($tableMid, $tableId);
        $cases = array('form' => array($this->getUserSubmitForm($tableName, $language)),
                    'save' => array($this->getUserSubmitSave($moduleDirname, $fields, $tableName, $language)), );

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
        $tableCategory = $table->getVar('table_category');
        $tableSoleName = $table->getVar('table_solename');
        $language = $this->getLanguage($moduleDirname, 'MA');
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getUserSubmitHeader($moduleDirname);
        $content .= $this->getUserSubmitSwitch($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $language);
        $content .= $this->getUserSubmitFooter($moduleDirname, $language);

        $this->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
