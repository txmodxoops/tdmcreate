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

/**
 * Class UserIndex.
 */
class UserIndex extends TDMCreateFile
{
    /*
    * @var mixed
    */
    private $uc = null;

    /*
    * @var string
    */
    private $cc = null;

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
        $this->phpcode = TDMCreatePhpCode::getInstance();
        $this->xc = TDMCreateXoopsCode::getInstance();
        $this->uc = UserXoopsCode::getInstance();
        $this->cc = ClassXoopsCode::getInstance();
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
        $ret .= $this->uc->getUserTplMain($moduleDirname);
        $ret .= $this->phpcode->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'header', true);
        $ret .= $this->phpcode->getPhpCodeCommentLine('Define Stylesheet');
        $ret .= $this->xc->getXcAddStylesheet();

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
        if (in_array(1, $fieldParentId)) {
            $ret .= $this->xc->getXcObjHandlerCount($tableName);
            $ret .= $this->phpcode->getPhpCodeCommentLine('If there are ', $tableName);
            $ret .= $this->getSimpleString('$count = 1;');

            $contentIf = $this->xc->getXcObjHandlerAll($tableName, '', 0, 0, "\t");
            $contentIf .= $this->phpcode->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/tree', true, false, 'include', "\t");
            $contentIf .= $this->cc->getClassXoopsObjectTree('mytree', $tableName, $fieldId, $fieldParent, "\t");
            $foreach = $this->xc->getXcGetValues($tableName, $tableSoleName, $tableFieldname, false, "\t");
            $foreach .= $this->phpcode->getPhpCodeArray($tableName);
            $foreach .= $this->phpcode->getPhpCodeArray('acount', array("'count'", '$count'));
            $foreach .= $this->phpcode->getPhpCodeArrayType($tableName, 'merge', $tableSoleName, '$acount');
            $foreach .= $this->xc->getXcXoopsTplAppend($tableName, "\${$tableName}", "\t\t");
            $foreach .= $this->phpcode->getPhpCodeUnset($tableName, "\t\t");
            $contentIf .= $this->phpcode->getPhpCodeForeach("{$tableName}All", true, false, $tableFieldname, $foreach, "\t");
            $getConfig = $this->xc->getXcGetConfig($moduleDirname, 'numb_col');
            $contentIf .= $this->xc->getXcTplAssign('numb_col', $getConfig, true, "\t");
            $ret .= $this->phpcode->getPhpCodeConditions("\${$tableName}Count", ' > ', '0', $contentIf, false);
            $ret .= $this->phpcode->getPhpCodeUnset('count');
        }
        unset($fieldParentId);

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
        $ret = $this->phpcode->getPhpCodeCommentLine();
        $ret .= $this->xc->getXcTplAssign('xoops_icons32_url', 'XOOPS_ICONS32_URL');
        $ret .= $this->xc->getXcTplAssign("{$moduleDirname}_url", "{$stuModuleDirname}_URL");
        $ret .= $this->phpcode->getPhpCodeCommentLine();
        $ret .= $this->xc->getXcObjHandlerCount($tableName);
        $ret .= $this->getSimpleString('$count = 1;');
        $condIf = $this->xc->getXcXoopsRequest('start', 'start', '0', 'Int', false, "\t");
        $userpager = $this->xc->getXcGetConfig($moduleDirname, 'userpager');
        $condIf .= $this->xc->getXcXoopsRequest('limit', 'limit', $userpager, 'Int', false, "\t");
        $condIf .= $this->xc->getXcObjHandlerAll($tableName, '', '$start', '$limit', "\t");
        $condIf .= $this->phpcode->getPhpCodeCommentLine('Get All', $ucfTableName, "\t");
        $foreach = $this->xc->getXcGetValues($tableName, $tableFieldname, 'i', false, "\t");
        $foreach .= $this->phpcode->getPhpCodeArray($tableName);
        $foreach .= $this->phpcode->getPhpCodeArray('acount', array("'count'", '$count'));
        $foreach .= $this->phpcode->getPhpCodeArrayType($tableName, 'merge', $tableSoleName, '$acount');
        $foreach .= $this->xc->getXcXoopsTplAppend($tableName, "\${$tableName}", "\t\t");
        $table = $this->getTable();
        // Fields
        $fields = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName; // fieldMain = fields parameters main field
            }
        }
        $foreach .= $this->xc->getXcGetVar('keywords[]', "{$tableName}All[\$i]", $fieldMain, false, "\t\t");
        $foreach .= $this->phpcode->getPhpCodeUnset($tableName, "\t\t");
        $foreach .= $this->getSimpleString('++$count;', "\t\t");
        $condIf .= $this->phpcode->getPhpCodeForeach("{$tableName}All", true, false, 'i', $foreach, "\t");
        $condIf .= $this->xc->getXcPageNav($tableName, "\t");
        $thereare = $this->phpcode->getPhpCodeSprintf("{$language}INDEX_THEREARE", "\${$tableName}Count");
        $condIf .= $this->xc->getXcTplAssign('lang_thereare', $thereare, true, "\t");
        $divideby = $this->xc->getXcGetConfig($moduleDirname, 'divideby');
        $condIf .= $this->xc->getXcTplAssign('divideby', $divideby, true, "\t");

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
    private function getUserIndexFooter($moduleDirname, $language)
    {
        $stuModuleDirname = strtoupper($moduleDirname);
        $ret = $this->phpcode->getPhpCodeCommentLine('Breadcrumbs');
        $ret .= $this->uc->getUserBreadcrumbs($language);
        $ret .= $this->phpcode->getPhpCodeCommentLine('Keywords');
        $ret .= $this->uc->getUserMetaKeywords($moduleDirname);
        $ret .= $this->phpcode->getPhpCodeUnset('keywords');
        $ret .= $this->phpcode->getPhpCodeCommentLine('Description');
        $ret .= $this->uc->getUserMetaDesc($moduleDirname, $language);
        $ret .= $this->xc->getXcTplAssign('xoops_mpageurl', "{$stuModuleDirname}_URL.'/index.php'");
        $ret .= $this->xc->getXcTplAssign('xoops_icons32_url', 'XOOPS_ICONS32_URL');
        $ret .= $this->xc->getXcTplAssign("{$moduleDirname}_upload_url", "{$stuModuleDirname}_UPLOAD_URL");
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
        //
        $this->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
