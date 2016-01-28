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
 * @version         $Id: user_index.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class UserIndex.
 */
class UserIndex extends TDMCreateFile
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
        $this->phpcode = TDMCreatePhpCode::getInstance();
        $this->xoopscode = TDMCreateXoopsCode::getInstance();
        $this->usercode = UserXoopsCode::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return UserIndex
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
    *  @param string $filename
    */
    /**
     * @param $module
     * @param $filename
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /**
     * @private function getTemplateHeaderFile
     *
     * @param $moduleDirname
     *
     * @return string
     */
    private function getTemplateHeaderFile($moduleDirname)
    {
        $ret = $this->getInclude();
        $ret .= $this->usercode->getUserTplMain($moduleDirname);
        $ret .= $this->phpcode->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'header', true);
        $ret .= $this->getCommentLine('Define Stylesheet');
        $ret .= $this->xoopscode->getXoopsCodeAddStylesheet();

        return $ret;
    }

    /**
     * @private function getBodyCategoriesIndex
     *
     * @param $moduleDirname
     * @param $language
     *
     * @return string
     */
    private function getBodyCategoriesIndex($moduleDirname, $tableMid, $tableId, $tableName, $tableSoleName, $tableFieldname)
    {
        $ucfTableName = ucfirst($tableName);
        // Fields
        $fields = $this->getTableFields($tableMid, $tableId);

        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (0 == $f) {
                $fieldId = $fieldName; // fieldMain = fields parameters main field
            }
            $fieldParentId[] = $fields[$f]->getVar('field_parent');
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName; // fieldMain = fields parameters main field
            }
            if (1 == $fields[$f]->getVar('field_parent')) {
                $fieldParent = $fieldName; // fieldMain = fields parameters main field
            }
        }
        $ret = '';
        if (in_array(1, $fieldParentId)) {
            $ret .= $this->xoopscode->getXoopsCodeObjHandlerCount($tableName);
            $ret .= $this->getCommentLine('If there are ', $tableName);
            $ret .= $this->getSimpleString('$count = 1;');

            $contentIf = $this->xoopscode->getXoopsCodeObjHandlerAll($tableName);
            $contentIf .= $this->phpcode->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/tree', true);
            $contentIf .= $this->xoopscode->getXoopsCodeObjectTree($tableName, $fieldId, $fieldParent);

            $foreach = $this->xoopscode->getXoopsCodeGetValues($tableName, $tableSoleName, $tableFieldname);
            $foreach .= $this->phpcode->getPhpCodeArray('acount', 'count', '$count');
            $foreach .= $this->phpcode->getPhpCodeArrayMerge($tableSoleName, "\${$tableSoleName}", '$acount');
            $foreach .= $this->xoopscode->getXoopsCodeXoopsTplAppend($tableName, "\${$tableSoleName}");
            $foreach .= $this->phpcode->getPhpCodeUnset($tableSoleName);
            $contentIf .= $this->phpcode->getPhpCodeForeach("\${$tableName}All", true, false, $tableFieldname, $foreach, "\t");
            $getConfig = $this->xoopscode->getXoopsCodeGetConfig($moduleDirname, 'numb_col');
            $contentIf .= $this->xoopscode->getXoopsCodeTplAssign('numb_col', $getConfig);
            $ret .= $this->phpcode->getPhpCodeConditions("\${$tableName}Count", ' > ', '0', $contentIf, false);
            $ret .= $this->phpcode->getPhpCodeUnset('count');
        }

        return $ret;
    }

    /**
     * @private function getBodyPagesIndex
     *
     * @param $moduleDirname
     * @param $language
     *
     * @return string
     */
    private function getBodyPagesIndex($moduleDirname, $tableName, $tableSoleName, $tableFieldname, $language)
    {
        $stuModuleDirname = strtoupper($moduleDirname);
        $ucfTableName = ucfirst($tableName);
        $stuModuleDirname = strtoupper($moduleDirname);
        $ucfTableName = ucfirst($tableName);
        $ret = $this->getCommentLine();
        $ret .= $this->xoopscode->getXoopsCodeTplAssign('xoops_icons32_url', 'XOOPS_ICONS32_URL');
        $ret .= $this->xoopscode->getXoopsCodeTplAssign("{$moduleDirname}_upload_url", "{$stuModuleDirname}_UPLOAD_URL");
        $ret .= $this->getCommentLine();
        $ret .= $this->xoopscode->getXoopsCodeObjHandlerCount($tableName);
        $ret .= $this->getSimpleString('$count = 1;');
        $condIf = $this->xoopscode->getXoopsCodeXoopsRequest('start', 'start', '0', 'Int');
        $userpager = $this->xoopscode->getXoopsCodeGetConfig($moduleDirname, 'userpager');
        $condIf .= $this->xoopscode->getXoopsCodeXoopsRequest('limit', 'limit', $userpager, 'Int');
        $condIf .= $this->xoopscode->getXoopsCodeObjHandlerAll($tableName, '', '$start', '$limit');
        $condIf .= $this->getCommentLine('Get All', $ucfTableName);
        $foreach = $this->xoopscode->getXoopsCodeGetValues($tableName, $tableFieldname);
        $foreach .= $this->phpcode->getPhpCodeArray('acount', "'count'", '$count');
        $foreach .= $this->phpcode->getPhpCodeArrayMerge('acount', "\${$tableSoleName}", '$acount');
        $foreach .= $this->xoopscode->getXoopsCodeXoopsTplAppend($tableName, "\${$tableSoleName}");
        $table = $this->getTable();
        // Fields
        $fields = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName; // fieldMain = fields parameters main field
            }
        }
        $foreach .= $this->xoopscode->getXoopsCodeGetVar('keywords[]', "{$tableName}All[\$i]", $fieldMain);
        $foreach .= $this->phpcode->getPhpCodeUnset($tableSoleName);
        $foreach .= $this->getSimpleString('++$count;');
        $condIf .= $this->phpcode->getPhpCodeForeach("{$tableName}All", true, false, 'i', $foreach, "\t");
        $condIf .= $this->xoopscode->getXoopsCodePageNav($tableName);
        $thereare = $this->phpcode->getPhpCodeSprintf("{$language}INDEX_THEREARE", "\${$tableName}Count");
        $condIf .= $this->xoopscode->getXoopsCodeTplAssign('lang_thereare', $thereare);
        $divideby = $this->xoopscode->getXoopsCodeGetConfig($moduleDirname, 'divideby');
        $condIf .= $this->xoopscode->getXoopsCodeTplAssign('divideby', $divideby);

        $ret .= $this->phpcode->getPhpCodeConditions("\${$tableName}Count", ' > ', '0', $condIf);
        $ret .= $this->phpcode->getPhpCodeUnset('count');

        return $ret;
    }

    /**
     * @private function getUserPagesFooter
     *
     * @param $moduleDirname
     * @param $tableName
     * @param $language
     *
     * @return string
     */
    private function getUserIndexFooter($moduleDirname, $tableName, $language)
    {
        $stuModuleDirname = strtoupper($moduleDirname);
        $stuTableName = strtoupper($tableName);
        $ret = $this->getCommentLine('Breadcrumbs');
        $ret .= $this->usercode->getUserBreadcrumbs("{$stuTableName}", $language);
        $ret .= $this->getCommentLine('Keywords');
        $ret .= $this->usercode->getUserMetaKeywords($moduleDirname);
        $ret .= $this->phpcode->getPhpCodeUnset('keywords');
        $ret .= $this->getCommentLine('Description');
        $ret .= $this->usercode->getUserMetaDesc($moduleDirname, 'DESC', $language);
        $ret .= $this->xoopscode->getXoopsCodeTplAssign('xoops_mpageurl', "{$stuModuleDirname}_URL.'/index.php'");
        $ret .= $this->xoopscode->getXoopsCodeTplAssign('xoops_icons32_url', 'XOOPS_ICONS32_URL');
        $ret .= $this->xoopscode->getXoopsCodeTplAssign("{$moduleDirname}_upload_url", "{$stuModuleDirname}_UPLOAD_URL");
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
        $tables = $this->getTableTables($module->getVar('mod_id'), 'table_order');
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language = $this->getLanguage($moduleDirname, 'MA');
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getTemplateHeaderFile($moduleDirname);
        foreach (array_keys($tables) as $t) {
            $tableId = $tables[$t]->getVar('table_id');
            $tableMid = $tables[$t]->getVar('table_mid');
            $tableName = $tables[$t]->getVar('table_name');
            $tableSoleName = $tables[$t]->getVar('table_solename');
            $tableCategory = $tables[$t]->getVar('table_category');
            $tableFieldname = $tables[$t]->getVar('table_fieldname');
            $tableIndex = $tables[$t]->getVar('table_index');
            if ((1 == $tableCategory) && (1 == $tableIndex)) {
                $content .= $this->getBodyCategoriesIndex($moduleDirname, $tableMid, $tableId, $tableName, $tableSoleName, $tableFieldname);
            }
            if ((0 == $tableCategory) && (1 == $tableIndex)) {
                $content .= $this->getBodyPagesIndex($moduleDirname, $tableName, $tableSoleName, $tableFieldname, $language);
            }
        }
        $content .= $this->getUserIndexFooter($moduleDirname, $tableName, $language);
        //
        $this->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
