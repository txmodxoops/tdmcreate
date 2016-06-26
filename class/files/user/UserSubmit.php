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
    *  @public function constructor
    *  @param null
    */
    /**
     *
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
        $xc = TDMCreateXoopsCode::getInstance();
        $pc = TDMCreatePhpCode::getInstance();
        $uc = UserXoopsCode::getInstance();
        $t = "\t";
        $ret = $this->getInclude();
        $ret .= $xc->getXcLoadLanguage('admin');
        $ret .= $pc->getPhpCodeCommentLine('It recovered the value of argument op in URL$');
        $ret .= $xc->getXcXoopsRequest('op', 'op', 'form');
        $ret .= $pc->getPhpCodeCommentLine('Template');
        $ret .= $uc->getUserTplMain($moduleDirname, 'submit');
        $ret .= $pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'header', true);
        $ret .= $xc->getXcAddStylesheet();
        $ret .= "\$permSubmit = \$gpermHandler->checkRight('{$moduleDirname}_ac', 4, \$groups, \$GLOBALS['xoopsModule']->getVar('mid')) ? true : false;\n";
        $ret .= $pc->getPhpCodeCommentLine('Redirection if not permissions');
        $condIf = $xc->getXcRedirectHeader('index', '', '2', '_NOPERM', true, $t);
        $condIf .= $this->getSimpleString('exit();', $t);

        $ret .= $pc->getPhpCodeConditions('$permSubmit', ' == ', 'false', $condIf, false);

        return $ret;
    }

    /*
     * @public function getAdminPagesList
     * @param $tableName
     * @param $language
     *
     * @return string
     */
    public function getUserSubmitForm($tableName, $language, $t = '')
    {
        $xc = TDMCreateXoopsCode::getInstance();
        $pc = TDMCreatePhpCode::getInstance();
        $uc = UserXoopsCode::getInstance();
        $ret = $pc->getPhpCodeCommentLine('Navigation', null, $t."\t");
        $ret .= $xc->getXcEqualsOperator('$navigation', "{$language}SUBMIT_PROPOSER", '', false, $t."\t");
        $ret .= $xc->getXcTplAssign('navigation', '$navigation', true, $t."\t");
        $ret .= $pc->getPhpCodeCommentLine('Title of page', null, $t."\t");
        $ret .= $xc->getXcEqualsOperator('$title', "{$language}SUBMIT_PROPOSER . '&nbsp;-&nbsp;'", '', false, $t."\t");
        $ret .= $xc->getXcEqualsOperator('$title', "\$GLOBALS['xoopsModule']->name()", '.', false, $t."\t");
        $ret .= $xc->getXcTplAssign('xoops_pagetitle', '$title', true, $t."\t");
        $ret .= $pc->getPhpCodeCommentLine('Description', null, $t."\t");
        $ret .= $uc->getUserAddMeta('description', $language, 'SUBMIT_PROPOSER', $t."\t");
        $ret .= $pc->getPhpCodeCommentLine('Form Create', null, $t."\t");
        $ret .= $xc->getXcObjHandlerCreate($tableName, $t."\t");
        $ret .= $xc->getXcGetForm('form', $tableName, 'Obj', $t."\t");
        $ret .= $xc->getXcTplAssign('form', '$form->render()', true, $t."\t");

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
    public function getUserSubmitSave($moduleDirname, $fields, $tableName, $tableSolename, $tableSubmit, $tableAutoincrement, $language, $t = '')
    {
        $xc = TDMCreateXoopsCode::getInstance();
        $pc = TDMCreatePhpCode::getInstance();
        $ucfTableName = ucfirst($tableName);
        $ret = $pc->getPhpCodeCommentLine('Security Check', null, $t);
        $xoopsSecurityCheck = $xc->getXcSecurityCheck();
        $securityError = $xc->getXcSecurityErrors();
        $implode = $pc->getPhpCodeImplode(',', $securityError);
        $redirectError = $xc->getXcRedirectHeader($tableName, '', '3', $implode, true, $t."\t");
        $ret .= $pc->getPhpCodeConditions('!'.$xoopsSecurityCheck, '', '', $redirectError, false, $t);
        $ret .= $xc->getXcObjHandlerCreate($tableName, $t);
        $autoincrement = in_array(1, $tableAutoincrement) ? $tableAutoincrement : 0;
        if (in_array(1, $tableSubmit)) {
            $ret .= $xc->getXcSaveElements($moduleDirname, $tableName, $tableSolename, $autoincrement, $fields, $t);
        }
        $ret .= $pc->getPhpCodeCommentLine('Insert Data', null, $t);
        $insert = $xc->getXcInsert($tableName, $tableName, 'Obj', 'Handler');
        $confirmOk = $xc->getXcRedirectHeader('index', '', '2', "{$language}FORM_OK", true, $t."\t");
        $ret .= $pc->getPhpCodeConditions($insert, '', '', $confirmOk, false, $t);

        $ret .= $pc->getPhpCodeCommentLine('Get Form Error', null, $t);
        $ret .= $xc->getXcTplAssign('error', "\${$tableName}Obj->getHtmlErrors()");
        $ret .= $xc->getXcGetForm('form', $tableName, 'Obj');
        $ret .= $xc->getXcTplAssign('form', '$form->display()');

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
        $pc = TDMCreatePhpCode::getInstance();
        $uc = UserXoopsCode::getInstance();
        $stuModuleDirname = strtoupper($moduleDirname);
        $ret = $pc->getPhpCodeCommentLine('Breadcrumbs');
        $ret .= $uc->getUserBreadcrumbs($language, 'SUBMIT');
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
    private function getUserSubmitSwitch($moduleDirname, $tableId, $tableMid, $tableName, $tableSolename, $tableSubmit, $tableAutoincrement, $language, $t)
    {
        $xc = TDMCreateXoopsCode::getInstance();
        $fields = $this->getTableFields($tableMid, $tableId);
        $cases = array('form' => array($this->getUserSubmitForm($tableName, $language, $t)),
                    'save' => array($this->getUserSubmitSave($moduleDirname, $fields, $tableName, $tableSolename, $tableSubmit, $tableAutoincrement, $language, $t)), );

        return $xc->getXcSwitch('op', $cases, true, false, $t);
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
        $tables = $this->getTableTables($module->getVar('mod_id'));
        $tableSubmit = array();
        $tableAutoincrement = array();
        foreach (array_keys($tables) as $t) {
            $tableId = $tables[$t]->getVar('table_id');
            $tableMid = $tables[$t]->getVar('table_mid');
            $tableName = $tables[$t]->getVar('table_name');
            $tableSolename = $tables[$t]->getVar('table_solename');
            $tableSubmit[] = $tables[$t]->getVar('table_submit');
            $tableAutoincrement[] = $tables[$t]->getVar('table_autoincrement');
        }
        $language = $this->getLanguage($moduleDirname, 'MA');
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getUserSubmitHeader($moduleDirname);
        $content .= $this->getUserSubmitSwitch($moduleDirname, $tableId, $tableMid, $tableName, $tableSolename, $tableSubmit, $tableAutoincrement, $language, "\t");
        $content .= $this->getUserSubmitFooter($moduleDirname, $language);

        $this->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
