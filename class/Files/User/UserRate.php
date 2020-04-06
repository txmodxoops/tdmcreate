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
 * Class UserRate.
 */
class UserRate extends Files\CreateFile
{
    /**
     * @public function constructor
     *
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
        $this->xc      = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $this->phpcode = Tdmcreate\Files\CreatePhpCode::getInstance();
        $this->uc      = UserXoopsCode::getInstance();
    }

    /**
     * @static function getInstance
     *
     * @param null
     *
     * @return UserRate
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

    /**
     * @private function getUserRateSwitch
     * @param $moduleDirname
     * @param $tableId
     * @param $tableMid
     * @param $tableName
     * @param $tableSoleName
     * @param $tableAutoincrement
     * @param $language
     * @return string
     */
    private function getUserRateSwitch($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $tableAutoincrement, $language)
    {
        $fields = $this->getTableFields($tableMid, $tableId);
        $cases  = [
            'form' => [$this->getUserRateForm($tableName, $language)],
            'save' => [$this->getUserRateSave($moduleDirname, $fields, $tableName, $tableSoleName, $tableAutoincrement, $language)],
        ];

        return $this->xc->getXcSwitch('op', $cases, true, false, "\t");
    }

    /**
     * @public function getAdminPagesList
     * @param $tableName
     * @param $language
     *
     * @return string
     */
    public function getUserRateForm($tableName, $language)
    {
        $ret = $this->phpcode->getPhpCodeCommentLine('Navigation');
        $ret .= $this->xc->getXcEqualsOperator('$navigation', "{$language}RATE", null, false, "\t\t");
        $ret .= $this->xc->getXcTplAssign('navigation', '$navigation', true, "\t\t");
        $ret .= $this->phpcode->getPhpCodeCommentLine('Title of page', null, "\t\t");
        $ret .= $this->xc->getXcEqualsOperator('$title', "{$language}RATE . '&nbsp;-&nbsp;'", null, false, "\t\t");
        $ret .= $this->xc->getXcEqualsOperator('$title', "\$GLOBALS['xoopsModule']->name()", '.', false, "\t\t");
        $ret .= $this->xc->getXcTplAssign('xoops_pagetitle', '$title', true, "\t\t");
        $ret .= $this->phpcode->getPhpCodeCommentLine('Description', null, "\t\t");
        $ret .= $this->uc->getUserAddMeta('description', $language, 'RATE', "\t\t");
        $ret .= $this->phpcode->getPhpCodeCommentLine('Form Create', null, "\t\t");
        $ret .= $this->xc->getXcObjHandlerCreate($tableName, "\t\t");
        $ret .= $this->xc->getXcGetForm('form', $tableName, 'Obj', "\t\t");
        $ret .= $this->xc->getXcTplAssign('form', '$form->render()', true, "\t\t");

        return $ret;
    }

    /**
     * @public function getUserRateSave
     * @param $moduleDirname
     * @param $fields
     * @param $tableName
     * @param $tableSoleName
     * @param $tableAutoincrement
     * @param $language
     * @return string
     */
    public function getUserRateSave($moduleDirname, $fields, $tableName, $tableSoleName, $tableAutoincrement, $language)
    {
        $ucfTableName       = ucfirst($tableName);
        $ret                = $this->phpcode->getPhpCodeCommentLine('Security Check');
        $xoopsSecurityCheck = $this->xc->getXcSecurityCheck();
        $securityError      = $this->xc->getXcSecurityErrors();
        $implode            = $this->phpcode->getPhpCodeImplode(',', $securityError);
        $redirectError      = $this->xc->getXcRedirectHeader($tableName, '', '3', $implode, true, "\t\t\t");
        $ret                .= $this->phpcode->getPhpCodeConditions($xoopsSecurityCheck, '', '', $redirectError, false, "\t\t");
        $ret                .= $this->xc->getXcObjHandlerCreate($tableName, "\t\t");

        $ret .= $this->xc->getXcSaveElements($moduleDirname, $tableName, $tableSoleName, $fields, "\t\t");

        $ret       .= $this->phpcode->getPhpCodeCommentLine('Insert Data', null, "\t\t");
        $insert    = $this->xc->getXcInsert($tableName, $tableName, 'Obj', true);
        $confirmOk = $this->xc->getXcRedirectHeader('index', '', '2', "{$language}FORM_OK", true, "\t\t\t");
        $ret       .= $this->phpcode->getPhpCodeConditions($insert, '', '', $confirmOk, false, "\t\t");

        $ret .= $this->phpcode->getPhpCodeCommentLine('Get Form Error', null, "\t\t");
        $ret .= $this->xc->getXcTplAssign('error', "\${$tableName}Obj->getHtmlErrors()", true, "\t\t");
        $ret .= $this->xc->getXcGetForm('form', $tableName, 'Obj', "\t\t");
        $ret .= $this->xc->getXcTplAssign('form', '$form->display()', true, "\t\t");

        return $ret;
    }

    /**
     * @public function getUserRateFooter
     * @param $moduleDirname
     * @param $language
     * @return string
     */
    public function getUserRateFooter($moduleDirname, $language)
    {
        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $ret              = $this->phpcode->getPhpCodeCommentLine('Breadcrumbs');
        $ret              .= $this->uc->getUserBreadcrumbs('RATE', $language);
        $ret              .= $this->getInclude('footer');

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
        $table              = $this->getTable();
        $filename           = $this->getFileName();
        $moduleDirname      = $module->getVar('mod_dirname');
        $tableId            = $table->getVar('table_id');
        $tableMid           = $table->getVar('table_mid');
        $tableName          = $table->getVar('table_name');
        $tableSoleName      = $table->getVar('table_solename');
        $tableAutoincrement = $table->getVar('table_autoincrement');
        $fields             = $this->getTableFields($tableMid, $tableId);
        $language           = $this->getLanguage($moduleDirname, 'MA');
        $content            = $this->getHeaderFilesComments($module, $filename);
        $content            .= $this->getUserRateHeader($moduleDirname, $tableName);
        $content            .= $this->getUserRateSwitch($moduleDirname, $tableId, $tableMid, $tableName, $tableSoleName, $tableAutoincrement, $language);
        $content            .= $this->getUserRateFooter($moduleDirname, $language);

        $this->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
