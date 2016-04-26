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
    private $uc = null;

    /*
    * @var string
    */
    private $xc = null;

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
        $this->xc = TDMCreateXoopsCode::getInstance();
        $this->phpcode = TDMCreatePhpCode::getInstance();
        $this->uc = UserXoopsCode::getInstance();
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
        $ret .= $this->xc->getXcLoadLanguage('admin');
        $ret .= $this->phpcode->getPhpCodeCommentLine('It recovered the value of argument op in URL$');
        $ret .= $this->xc->getXcXoopsRequest('op', 'op', 'form');
        $ret .= $this->phpcode->getPhpCodeCommentLine('Template');
        $ret .= $this->uc->getUserTplMain($moduleDirname, 'submit');
        $ret .= $this->phpcode->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'header', true);
        $ret .= $this->xc->getXcAddStylesheet();
        $ret .= "\$permSubmit = \$gpermHandler->checkRight('{$moduleDirname}_ac', 4, \$groups, \$GLOBALS['xoopsModule']->getVar('mid')) ? true : false;\n";
        $ret .= $this->phpcode->getPhpCodeCommentLine('Redirection if not permissions');
        $condIf = $this->xc->getXcRedirectHeader('index', '', '2', '_NOPERM', true, "\t");
        $condIf .= $this->getSimpleString('exit();', "\t");

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
        $ret = $this->phpcode->getPhpCodeCommentLine('Mavigation');
        $ret .= $this->xc->getXcEqualsOperator('$navigation', "{$language}SUBMIT_PROPOSER");
        $ret .= $this->xc->getXcTplAssign('navigation', '$navigation');
        $ret .= $this->phpcode->getPhpCodeCommentLine('Title of page');
        $ret .= $this->xc->getXcEqualsOperator('$title', "{$language}SUBMIT_PROPOSER . '&nbsp;-&nbsp;'");
        $ret .= $this->xc->getXcEqualsOperator('$title', "\$GLOBALS['xoopsModule']->name()", '.');
        $ret .= $this->xc->getXcTplAssign('xoops_pagetitle', '$title');
        $ret .= $this->phpcode->getPhpCodeCommentLine('Description');
        $ret .= $this->uc->getUserAddMeta('description', $language, 'SUBMIT_PROPOSER');
        $ret .= $this->phpcode->getPhpCodeCommentLine('Form Create');
        $ret .= $this->xc->getXcObjHandlerCreate($tableName);
        $ret .= $this->xc->getXcGetForm('form', $tableName, 'Obj');
        $ret .= $this->xc->getXcTplAssign('form', '$form->render()');

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
    public function getUserSubmitSave($moduleDirname, $fields, $tableName, $tableAutoincrement, $language)
    {
        $ucfTableName = ucfirst($tableName);
        $ret = $this->phpcode->getPhpCodeCommentLine('Security Check');
        $xoopsSecurityCheck = $this->xc->getXcSecurityCheck();
        $securityError = $this->xc->getXcSecurityErrors();
        $implode = $this->phpcode->getPhpCodeImplode(',', $securityError);
        $redirectError = $this->xc->getXcRedirectHeader($tableName, '', '3', $implode, true, "\t\t");
        $ret .= $this->phpcode->getPhpCodeConditions('!'.$xoopsSecurityCheck, '', '', $redirectError, false, "\t");
        $ret .= $this->xc->getXcObjHandlerCreate($tableName);

        $ret .= $this->xc->getXcSaveElements($moduleDirname, $tableName, $tableAutoincrement, $fields, "\t");

        $ret .= $this->phpcode->getPhpCodeCommentLine('Insert Data');
        $insert = $this->xc->getXcInsert($tableName, $tableName, 'Obj', 'Handler');
        $confirmOk = $this->xc->getXcRedirectHeader('index', '', '2', "{$language}FORM_OK", true, "\t\t");
        $ret .= $this->phpcode->getPhpCodeConditions($insert, '', '', $confirmOk, false, "\t");

        $ret .= $this->phpcode->getPhpCodeCommentLine('Get Form Error');
        $ret .= $this->xc->getXcTplAssign('error', "\${$tableName}Obj->getHtmlErrors()");
        $ret .= $this->xc->getXcGetForm('form', $tableName, 'Obj');
        $ret .= $this->xc->getXcTplAssign('form', '$form->display()');

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
        $ret = $this->phpcode->getPhpCodeCommentLine('Breadcrumbs');
        $ret .= $this->uc->getUserBreadcrumbs($language, 'SUBMIT');
        $ret .= $this->getInclude('footer');

        return $ret;
    }

    /*
     *  @private function getUserSubmitSwitch
     *  @param $moduleDirname
     *  @param $tableId
     *  @param $tableMid
     *  @param $tableName
     *  @param $tableAutoincrement
     *  @param $language
     *
     * @return string
     */
    private function getUserSubmitSwitch($moduleDirname, $tableId, $tableMid, $tableName, $tableAutoincrement, $language)
    {
        $fields = $this->getTableFields($tableMid, $tableId);
        $cases = array('form' => array($this->getUserSubmitForm($tableName, $language)),
                    'save' => array($this->getUserSubmitSave($moduleDirname, $fields, $tableName, $tableAutoincrement, $language)), );

        return $this->xc->getXcSwitch('op', $cases, true);
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
        $tableAutoincrement = $table->getVar('table_autoincrement');
        $language = $this->getLanguage($moduleDirname, 'MA');
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getUserSubmitHeader($moduleDirname);
        $content .= $this->getUserSubmitSwitch($moduleDirname, $tableId, $tableMid, $tableName, $tableAutoincrement, $language);
        $content .= $this->getUserSubmitFooter($moduleDirname, $language);

        $this->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
