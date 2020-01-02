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
 * @version         $Id: pages.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class UserPages.
 */
class UserPages extends Files\CreateFile
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
     * @return UserPages
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
     * @private function getUserPagesHeader
     * @param $moduleDirname
     * @param $tableName
     * @return string
     */
    private function getUserPagesHeader($moduleDirname, $tableName)
    {
        $pc        = Tdmcreate\Files\CreatePhpCode::getInstance();
        $xc        = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $uc        = UserXoopsCode::getInstance();
        $ret       = $this->getInclude();
        $ret       .= $uc->getUserTplMain($moduleDirname, $tableName);
        $ret       .= $pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'header', true);
        $ret       .= $xc->getXcXoopsRequest('start', 'start', '0', 'Int');
        $userpager = $xc->getXcGetConfig($moduleDirname, 'userpager');
        $ret       .= $xc->getXcXoopsRequest('limit', 'limit', $userpager, 'Int');
        $ret       .= $pc->getPhpCodeCommentLine('Define Stylesheet');
        $ret       .= $xc->getXcAddStylesheet();

        return $ret;
    }

    /**
     * @private function getUserPages
     * @param $moduleDirname
     * @param $tableName
     * @param $tableSoleName
     * @return string
     */
    private function getUserPages($moduleDirname, $tableName, $tableSoleName)
    {
        $pc               = Tdmcreate\Files\CreatePhpCode::getInstance();
        $xc               = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $ucfTableName     = ucfirst($tableName);
        $t                = "\t";
        $ret              = $pc->getPhpCodeCommentLine();
        $ret              .= $xc->getXcTplAssign('xoops_icons32_url', 'XOOPS_ICONS32_URL');
        $ret              .= $xc->getXcTplAssign("{$moduleDirname}_url", "{$stuModuleDirname}_URL");
        $ret              .= $pc->getPhpCodeCommentLine();
        $ret              .= $xc->getXcObjHandlerCount($tableName);
        $ret              .= $xc->getXcObjHandlerAll($tableName, '', '$start', '$limit');
        $ret              .= $pc->getPhpCodeArray('keywords', null, false, '');
        $condIf           = $pc->getPhpCodeArray($tableName, null, false, $t);
        $condIf           .= $pc->getPhpCodeCommentLine('Get All', $ucfTableName, $t);
        $foreach          = $xc->getXcGetValues($tableName, $tableName . '[]', 'i', false, $t);

        $table = $this->getTable();
        // Fields
        $fields = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName; // fieldMain = fields parameters main field
            }
        }
        $foreach   .= $xc->getXcGetVar('keywords[]', "{$tableName}All[\$i]", $fieldMain, false, $t . "\t");
        $condIf    .= $pc->getPhpCodeForeach("{$tableName}All", true, false, 'i', $foreach, $t);
        $condIf    .= $xc->getXcTplAssign($tableName, "\${$tableName}", true, $t);
        $condIf    .= $pc->getPhpCodeUnset($tableName, $t);
        $condIf    .= $xc->getXcPageNav($tableName, $t);
        $tableType = $xc->getXcGetConfig($moduleDirname, 'table_type');
        $condIf    .= $xc->getXcTplAssign('type', $tableType, true, $t);
        $divideby  = $xc->getXcGetConfig($moduleDirname, 'divideby');
        $condIf    .= $xc->getXcTplAssign('divideby', $divideby, true, $t);
        $numbCol   = $xc->getXcGetConfig($moduleDirname, 'numb_col');
        $condIf    .= $xc->getXcTplAssign('numb_col', $numbCol, true, $t);

        $ret .= $pc->getPhpCodeConditions("\${$tableName}Count", ' > ', '0', $condIf);

        return $ret;
    }

    /**
     * @private function getUserPagesFooter
     * @param $moduleDirname
     * @param $tableName
     * @param $tableSoleName
     * @param $language
     *
     * @return string
     */
    private function getUserPagesFooter($moduleDirname, $tableName, $tableSoleName, $language)
    {
        $pc               = Tdmcreate\Files\CreatePhpCode::getInstance();
        $xc               = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $uc               = UserXoopsCode::getInstance();
        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $stuTableName     = mb_strtoupper($tableName);
        $stuTableSoleName = mb_strtoupper($tableSoleName);
        $ret              = $pc->getPhpCodeCommentLine('Breadcrumbs');
        $ret              .= $uc->getUserBreadcrumbs($language, $stuTableName);
        $ret              .= $pc->getPhpCodeCommentLine('Keywords');
        $ret              .= $uc->getUserMetaKeywords($moduleDirname);
        $ret              .= $pc->getPhpCodeUnset('keywords');
        $ret              .= $pc->getPhpCodeCommentLine('Description');
        $ret              .= $uc->getUserMetaDesc($moduleDirname, $language, $stuTableName);
        $ret              .= $xc->getXcTplAssign('xoops_mpageurl', "{$stuModuleDirname}_URL.'/{$tableName}.php'");
        $ret              .= $xc->getXcTplAssign("{$moduleDirname}_upload_url", "{$stuModuleDirname}_UPLOAD_URL");
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
        $module        = $this->getModule();
        $table         = $this->getTable();
        $tableName     = $table->getVar('table_name');
        $tableSoleName = $table->getVar('table_solename');
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language      = $this->getLanguage($moduleDirname, 'MA');
        $content       = $this->getHeaderFilesComments($module, $filename);
        $content       .= $this->getUserPagesHeader($moduleDirname, $tableName);
        $content       .= $this->getUserPages($moduleDirname, $tableName, $tableSoleName);
        $content       .= $this->getUserPagesFooter($moduleDirname, $tableName, $tableSoleName, $language);

        $this->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
