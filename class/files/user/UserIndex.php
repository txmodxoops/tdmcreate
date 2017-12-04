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
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 * @version         $Id: user_index.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class UserIndex.
 */
class UserIndex extends TDMCreateFile
{
    /**
    *  @public function constructor
    *  @param null
    */

    public function __construct()
    {
        parent::__construct();
    }

    /**
    *  @static function getInstance
    *  @param null
     * @return UserIndex
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
     * @param        $table
     * @param string $filename
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /**
     * @private function getTemplateHeaderFile
     * @param $moduleDirname
     *
     * @return string
     */
    private function getTemplateHeaderFile($moduleDirname)
    {
        $pc = TDMCreatePhpCode::getInstance();
        $xc = TDMCreateXoopsCode::getInstance();
        $uc = UserXoopsCode::getInstance();
        $cc = ClassXoopsCode::getInstance();
        $ret = $this->getInclude();
        $ret .= $uc->getUserTplMain($moduleDirname);
        $ret .= $pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'header', true);
        $ret .= $pc->getPhpCodeCommentLine('Define Stylesheet');
        $ret .= $xc->getXcAddStylesheet();
        $ret .= $pc->getPhpCodeArray('keywords');

        return $ret;
    }

    /**
     * @private  function getBodyCategoriesIndex
     * @param $moduleDirname
     * @param $tableMid
     * @param $tableId
     * @param $tableName
     * @param $tableSoleName
     * @param $tableFieldname
     * @return string
     */
    private function getBodyCategoriesIndex($moduleDirname, $tableMid, $tableId, $tableName, $tableSoleName, $tableFieldname)
    {
        $ucfTableName = ucfirst($tableName);
        // Fields
        $fields = $this->getTableFields($tableMid, $tableId);
        $fieldParentId = array();
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
        $pc = TDMCreatePhpCode::getInstance();
        $xc = TDMCreateXoopsCode::getInstance();
        $cc = ClassXoopsCode::getInstance();
        if (in_array(1, $fieldParentId)) {
            $ret .= $xc->getXcObjHandlerCount($tableName);
            $ret .= $pc->getPhpCodeCommentLine('If there are ', $tableName);
            $ret .= $this->getSimpleString('$count = 1;');

            $contentIf = $xc->getXcObjHandlerAll($tableName, '', 0, 0, "\t");
            $contentIf .= $pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/tree', true, false, 'include', "\t");
            //$contentIf .= $cc->getClassXoopsObjectTree('mytree', $tableName, $fieldId, $fieldParent, "\t");
            $contentIf .= $pc->getPhpCodeArray($tableName, "\t");
            $foreach = $xc->getXcGetValues($tableName, $tableSoleName.'Values', $tableFieldname, false, "\t");
            $foreach .= $pc->getPhpCodeArray('acount', array("'count'", '$count'));
            $foreach .= $pc->getPhpCodeArrayType($tableName, 'merge', $tableSoleName.'Values', '$acount');
            $foreach .= $this->getSimpleString('++$count;', "\t\t");
            $contentIf .= $pc->getPhpCodeForeach("{$tableName}All", true, false, $tableFieldname, $foreach, "\t");
            $contentIf .= $xc->getXcTplAssign($tableName, '$'.$tableName, true, "\t");
            $contentIf .= $pc->getPhpCodeUnset($tableName, "\t");
            $getConfig = $xc->getXcGetConfig($moduleDirname, 'numb_col');
            $contentIf .= $xc->getXcTplAssign('numb_col', $getConfig, true, "\t");
            $ret .= $pc->getPhpCodeConditions("\${$tableName}Count", ' > ', '0', $contentIf, false);
            $ret .= $pc->getPhpCodeUnset('count');
        }
        unset($fieldParentId);

        return $ret;
    }

    /**
     * @private function getBodyPagesIndex
     * @param $moduleDirname
     * @param $tableName
     * @param $tableSoleName
     * @param $tableFieldname
     * @param $language
     * @return string
     */
    private function getBodyPagesIndex($moduleDirname, $tableName, $tableSoleName, $tableFieldname, $language)
    {
        $pc = TDMCreatePhpCode::getInstance();
        $xc = TDMCreateXoopsCode::getInstance();
        $stuModuleDirname = strtoupper($moduleDirname);
        $ucfTableName = ucfirst($tableName);
        $stuModuleDirname = strtoupper($moduleDirname);
        $ucfTableName = ucfirst($tableName);
        $ret = $pc->getPhpCodeCommentLine();
        $ret .= $xc->getXcTplAssign('xoops_icons32_url', 'XOOPS_ICONS32_URL');
        $ret .= $xc->getXcTplAssign("{$moduleDirname}_url", "{$stuModuleDirname}_URL");
        $ret .= $pc->getPhpCodeCommentLine();
        $ret .= $xc->getXcObjHandlerCount($tableName);
        $ret .= $this->getSimpleString('$count = 1;');
        $condIf = $xc->getXcXoopsRequest('start', 'start', '0', 'Int', false, "\t");
        $userpager = $xc->getXcGetConfig($moduleDirname, 'userpager');
        $condIf .= $xc->getXcXoopsRequest('limit', 'limit', $userpager, 'Int', false, "\t");
        $condIf .= $xc->getXcObjHandlerAll($tableName, '', '$start', '$limit', "\t");
        $condIf .= $pc->getPhpCodeCommentLine('Get All', $ucfTableName, "\t");
        $condIf .= $pc->getPhpCodeArray($tableName);
        $foreach = $xc->getXcGetValues($tableName, $tableSoleName, 'i', false, "\t");
        $foreach .= $pc->getPhpCodeArray('acount', array("'count'", '$count'));
        $foreach .= $pc->getPhpCodeArrayType($tableName, 'merge', $tableSoleName, '$acount');
        $table = $this->getTable();
        // Fields
        $fields = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName; // fieldMain = fields parameters main field
            }
        }
        $foreach .= $xc->getXcGetVar('keywords[]', "{$tableName}All[\$i]", $fieldMain, false, "\t\t");
        $foreach .= $this->getSimpleString('++$count;', "\t\t");
        $condIf .= $pc->getPhpCodeForeach("{$tableName}All", true, false, 'i', $foreach, "\t");
        $condIf .= $xc->getXcTplAssign($tableName, '$'.$tableName, true, "\t");
        $condIf .= $pc->getPhpCodeUnset($tableName, "\t");
        $condIf .= $xc->getXcPageNav($tableName, "\t");
        $thereare = $pc->getPhpCodeSprintf("{$language}INDEX_THEREARE", "\${$tableName}Count");
        $condIf .= $xc->getXcTplAssign('lang_thereare', $thereare, true, "\t");
        $divideby = $xc->getXcGetConfig($moduleDirname, 'divideby');
        $condIf .= $xc->getXcTplAssign('divideby', $divideby, true, "\t");

        $ret .= $pc->getPhpCodeConditions("\${$tableName}Count", ' > ', '0', $condIf);
        $ret .= $pc->getPhpCodeUnset('count');
        $tableType = $xc->getXcGetConfig($moduleDirname, 'table_type');
        $ret .= $xc->getXcTplAssign('table_type', $tableType);

        return $ret;
    }

    /**
     * @private  function getUserPagesFooter
     * @param $moduleDirname
     * @param $language
     * @return string
     */
    private function getUserIndexFooter($moduleDirname, $language)
    {
        $pc = TDMCreatePhpCode::getInstance();
        $xc = TDMCreateXoopsCode::getInstance();
        $uc = UserXoopsCode::getInstance();
        $stuModuleDirname = strtoupper($moduleDirname);
        $ret = $pc->getPhpCodeCommentLine('Breadcrumbs');
        $ret .= $uc->getUserBreadcrumbs($language);
        $ret .= $pc->getPhpCodeCommentLine('Keywords');
        $ret .= $uc->getUserMetaKeywords($moduleDirname);
        $ret .= $pc->getPhpCodeUnset('keywords');
        $ret .= $pc->getPhpCodeCommentLine('Description');
        $ret .= $uc->getUserMetaDesc($moduleDirname, $language);
        $ret .= $xc->getXcTplAssign('xoops_mpageurl', "{$stuModuleDirname}_URL.'/index.php'");
        $ret .= $xc->getXcTplAssign('xoops_icons32_url', 'XOOPS_ICONS32_URL');
        $ret .= $xc->getXcTplAssign("{$moduleDirname}_upload_url", "{$stuModuleDirname}_UPLOAD_URL");
        $ret .= $this->getInclude('footer');

        return $ret;
    }

    /**
    *  @public function render
    *  @param null
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
            $tableCategory[] = $tables[$t]->getVar('table_category');
            $tableFieldname = $tables[$t]->getVar('table_fieldname');
            $tableIndex[] = $tables[$t]->getVar('table_index');
            if (in_array(1, $tableCategory) && in_array(1, $tableIndex)) {
                $content .= $this->getBodyCategoriesIndex($moduleDirname, $tableMid, $tableId, $tableName, $tableSoleName, $tableFieldname);
            }
            if (in_array(0, $tableCategory) && in_array(1, $tableIndex)) {
                $content .= $this->getBodyPagesIndex($moduleDirname, $tableName, $tableSoleName, $tableFieldname, $language);
            }
        }
        $content .= $this->getUserIndexFooter($moduleDirname, $language);

        $this->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
