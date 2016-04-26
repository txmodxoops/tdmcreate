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
 * @version         $Id: UserRate.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class UserRate.
 */
class UserRate extends TDMCreateFile
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
     * @return UserRate
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

    /**
     * @private function getUserRateHeader
     *
     * @param $moduleDirname
     * @param $tableName
     *
     * @return string
     */
    public function getUserRateHeader($moduleDirname, $tableName)
    {
        $ret = $this->getInclude();
        $ret .= $this->xc->getXcXoopsRequest('op', 'op', 'form');
        $ret .= $this->xc->getXcXoopsRequest('lid', 'lid', '', 'Int');
        $ret .= $this->uc->getUserTplMain($moduleDirname, $tableName);
        $ret .= $this->phpcode->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'header', true);
        $ret .= $this->phpcode->getPhpCodeCommentLine('Define Stylesheet');
        $ret .= $this->xc->getXcAddStylesheet();

        return $ret;
    }

    /*
     *  @private function getUserRateSwitch
     *  @param $moduleDirname
     *  @param $tableId
     *  @param $tableMid
     *  @param $tableName
     *  @param $tableAutoincrement
     *  @param $language
     *
     * @return string
     */
    private function getUserRateSwitch($moduleDirname, $tableId, $tableMid, $tableName, $tableAutoincrement, $language)
    {
        $fields = $this->getTableFields($tableMid, $tableId);
        $cases = array('form' => array($this->getUserRateForm($tableName, $language)),
                    'save' => array($this->getUserRateSave($moduleDirname, $fields, $tableName, $tableAutoincrement, $language)), );

        return $this->xc->getXcSwitch('op', $cases, true);
    }

    /*
     * @public function getAdminPagesList
     * @param $tableName
     * @param $language
     *
     * @return string
     */
    public function getUserRateForm($tableName, $language)
    {
        $ret = $this->phpcode->getPhpCodeCommentLine('Mavigation');
        $ret .= $this->xc->getXcEqualsOperator('$navigation', "{$language}RATE");
        $ret .= $this->xc->getXcTplAssign('navigation', '$navigation');
        $ret .= $this->phpcode->getPhpCodeCommentLine('Title of page');
        $ret .= $this->xc->getXcEqualsOperator('$title', "{$language}RATE . '&nbsp;-&nbsp;'");
        $ret .= $this->xc->getXcEqualsOperator('$title.', "\$GLOBALS['xoopsModule']->name()");
        $ret .= $this->xc->getXcTplAssign('xoops_pagetitle', '$title');
        $ret .= $this->phpcode->getPhpCodeCommentLine('Description');
        $ret .= $this->uc->getUserAddMeta('description', $language, 'RATE');
        $ret .= $this->phpcode->getPhpCodeCommentLine('Form Create');
        $ret .= $this->xc->getXcObjHandlerCreate($tableName);
        $ret .= $this->xc->getXcGetForm('form', $tableName, 'Obj');
        $ret .= $this->xc->getXcTplAssign('form', '$form->render()');

        return $ret;
    }

    /*
    *  @public function getUserRateSave
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
    public function getUserRateSave($moduleDirname, $fields, $tableName, $tableAutoincrement, $language)
    {
        $ucfTableName = ucfirst($tableName);
        $ret = $this->phpcode->getPhpCodeCommentLine('Security Check');
        $xoopsSecurityCheck = $this->xc->getXcSecurityCheck();
        $securityError = $this->xc->getXcSecurityErrors();
        $implode = $this->phpcode->getPhpCodeImplode(',', $securityError);
        $redirectError = $this->xc->getXcRedirectHeader($tableName, '', '3', $implode);
        $ret .= $this->phpcode->getPhpCodeConditions($xoopsSecurityCheck, '', '', $redirectError, false, "\t");
        $ret .= $this->xc->getXcObjHandlerCreate($tableName);

        $ret .= $this->xc->getXcSaveElements($moduleDirname, $tableName, $tableAutoincrement, $fields);

        $ret .= $this->phpcode->getPhpCodeCommentLine('Insert Data');
        $insert = $this->xc->getXcInsert($tableName, $tableName, 'Obj', true);
        $confirmOk = $this->xc->getXcRedirectHeader('index', '', '2', "{$language}FORM_OK");
        $ret .= $this->phpcode->getPhpCodeConditions($insert, '', '', $confirmOk, false, "\t");

        $ret .= $this->phpcode->getPhpCodeCommentLine('Get Form Error');
        $ret .= $this->xc->getXcTplAssign('error', "\${$tableName}Obj->getHtmlErrors()");
        $ret .= $this->xc->getXcGetForm('form', $tableName, 'Obj');
        $ret .= $this->xc->getXcTplAssign('form', '$form->display()');

        return $ret;
    }

    /*
    *  @public function getUserRateFooter
    *  @param null
    */
    /**
     * @return string
     */
    public function getUserRateFooter($moduleDirname, $language)
    {
        $stuModuleDirname = strtoupper($moduleDirname);
        $ret = $this->phpcode->getPhpCodeCommentLine('Breadcrumbs');
        $ret .= $this->uc->getUserBreadcrumbs('RATE', $language);
        $ret .= $this->getInclude('footer');

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
        $content .= $this->getUserRateHeader($moduleDirname, $tableName);
        $content .= $this->getUserRateSwitch($moduleDirname, $tableId, $tableMid, $tableName, $tableAutoincrement, $language);
        $content .= $this->getUserRateFooter($moduleDirname, $language);

        $this->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
