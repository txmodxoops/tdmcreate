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
     * @param $fieldId
     * @return string
     */
    private function getUserPagesHeader($moduleDirname, $tableName, $fieldId)
    {
        $pc        = Tdmcreate\Files\CreatePhpCode::getInstance();
        $xc        = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $uxc       = UserXoopsCode::getInstance();
        $ccFieldId = $this->getCamelCase($fieldId, false, true);
        $ret       = $pc->getPhpCodeUseNamespace(['Xmf', 'Request'], '', '');
        $ret       .= $pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname], '', '');
        $ret       .= $pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname, 'Constants']);
        $ret       .= $this->getInclude();
        $ret       .= $uxc->getUserTplMain($moduleDirname, $tableName);
        $ret       .= $pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'header', true);
        $ret       .= $pc->getPhpCodeBlankLine();
        $ret       .= $xc->getXcXoopsRequest('op', 'op', 'list', 'String');
        $ret       .= $xc->getXcXoopsRequest($ccFieldId, $fieldId, '0', 'Int');
        $ret       .= $xc->getXcXoopsRequest('start', 'start', '0', 'Int');
        $userpager = $xc->getXcGetConfig('userpager');
        $ret       .= $xc->getXcXoopsRequest('limit', 'limit', $userpager, 'Int');
        $ret       .= $pc->getPhpCodeBlankLine();
        $ret       .= $pc->getPhpCodeCommentLine('Define Stylesheet');
        $ret       .= $xc->getXcXoThemeAddStylesheet();

        return $ret;
    }

    /**
     * @private function getUserPages
     * @param $moduleDirname
     * @param $tableName
     * @param $fieldId
     * @param $fieldMain
     * @return string
     */
    private function getUserPages($moduleDirname, $tableName, $fieldId, $fieldMain)
    {
        $pc               = Tdmcreate\Files\CreatePhpCode::getInstance();
        $xc               = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $ucfTableName     = ucfirst($tableName);
        $ccFieldId        = $this->getCamelCase($fieldId, false, true);

        $t         = "\t";
        $ret       = $pc->getPhpCodeBlankLine();
        $ret       .= $xc->getXcXoopsTplAssign('xoops_icons32_url', 'XOOPS_ICONS32_URL');
        $ret       .= $xc->getXcXoopsTplAssign("{$moduleDirname}_url", "{$stuModuleDirname}_URL");
        $ret       .= $pc->getPhpCodeBlankLine();
		$critName  = 'cr' . $ucfTableName;
        $ret       .= $xc->getXcCriteriaCompo($critName);
        $crit      = $xc->getXcCriteria('', "'{$fieldId}'", "\${$ccFieldId}",'',true);
        $contIf    = $xc->getXcCriteriaAdd($critName, $crit, "\t");
        $ret       .= $pc->getPhpCodeConditions("\${$ccFieldId}", ' > ', '0', $contIf);
        $ret       .= $xc->getXcHandlerCountClear($tableName . 'Count', $tableName, '$' . $critName);
        $ret       .= $xc->getXcXoopsTplAssign($tableName . 'Count', "\${$tableName}Count");
        $ret       .= $xc->getXcCriteriaSetStart($critName, '$start');
        $ret       .= $xc->getXcCriteriaSetLimit($critName, '$limit');
        $ret       .= $xc->getXcHandlerAllClear($tableName . 'All', $tableName, '$' . $critName);
        $ret       .= $pc->getPhpCodeArray('keywords', null, false, '');
        $condIf    = $pc->getPhpCodeArray($tableName, null, false, $t);
        $condIf    .= $pc->getPhpCodeCommentLine('Get All', $ucfTableName, $t);
        $foreach   = $xc->getXcGetValues($tableName, $tableName . '[]', 'i', false, $t . "\t");
        $foreach   .= $xc->getXcGetVar('keywords[]', "{$tableName}All[\$i]", $fieldMain, false, $t . "\t");
        $condIf    .= $pc->getPhpCodeForeach("{$tableName}All", true, false, 'i', $foreach, $t);
        $condIf    .= $xc->getXcXoopsTplAssign($tableName, "\${$tableName}", true, $t);
        $condIf    .= $pc->getPhpCodeUnset($tableName, $t);
        $condIf    .= $xc->getXcPageNav($tableName, $t);
        $tableType = $xc->getXcGetConfig('table_type');
        $condIf    .= $xc->getXcXoopsTplAssign('type', $tableType, true, $t);
        $divideby  = $xc->getXcGetConfig('divideby');
        $condIf    .= $xc->getXcXoopsTplAssign('divideby', $divideby, true, $t);
        $numbCol   = $xc->getXcGetConfig('numb_col');
        $condIf    .= $xc->getXcXoopsTplAssign('numb_col', $numbCol, true, $t);

        $ret       .= $pc->getPhpCodeConditions("\${$tableName}Count", ' > ', '0', $condIf);

        return $ret;
    }

    /**
     * @private function getUserPagesFooter
     * @param $moduleDirname
     * @param $tableName
     * @param $language
     *
     * @return string
     */
    private function getUserPagesFooter($moduleDirname, $tableName, $language)
    {
        $pc               = Tdmcreate\Files\CreatePhpCode::getInstance();
        $xc               = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $uxc              = UserXoopsCode::getInstance();
        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $stuTableName     = mb_strtoupper($tableName);
        $ret              = $pc->getPhpCodeBlankLine();
        $ret              .= $pc->getPhpCodeCommentLine('Breadcrumbs');
        $ret              .= $uxc->getUserBreadcrumbs($language, $stuTableName);
        $ret              .= $pc->getPhpCodeBlankLine();
        $ret              .= $pc->getPhpCodeCommentLine('Keywords');
        $ret              .= $uxc->getUserMetaKeywords($moduleDirname);
        $ret              .= $pc->getPhpCodeUnset('keywords');
        $ret              .= $pc->getPhpCodeBlankLine();
        $ret              .= $pc->getPhpCodeCommentLine('Description');
        $ret              .= $uxc->getUserMetaDesc($moduleDirname, $language, $stuTableName);
        $ret              .= $xc->getXcXoopsTplAssign('xoops_mpageurl', "{$stuModuleDirname}_URL.'/{$tableName}.php'");
        $ret              .= $xc->getXcXoopsTplAssign("{$moduleDirname}_upload_url", "{$stuModuleDirname}_UPLOAD_URL");
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
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language      = $this->getLanguage($moduleDirname, 'MA');
        // Fields
        $fieldId   = '';
        $fieldMain = '';
        $fields    = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (0 == $f) {
                $fieldId = $fieldName;
            }
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName; // fieldMain = fields parameters main field
            }
        }
        $content       = $this->getHeaderFilesComments($module);
        $content       .= $this->getUserPagesHeader($moduleDirname, $tableName, $fieldId);
        $content       .= $this->getUserPages($moduleDirname, $tableName, $fieldId, $fieldMain);
        $content       .= $this->getUserPagesFooter($moduleDirname, $tableName, $language);

        $this->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
