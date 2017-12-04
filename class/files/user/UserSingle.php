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
 * @version         $Id: UserSingle.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class UserSingle.
 */
class UserSingle extends TDMCreateFile
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
     * @return UserSingle
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
    *  @public function write
    *  @param string $module
    *  @param mixed $table
    *  @param string $filename
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /**
     * @private function getUserSingleHeader
     * @param $moduleDirname
     * @param $table
     * @param $fields
     * @return string
     */
    private function getUserSingleHeader($moduleDirname, $table, $fields)
    {
        $xc = TDMCreateXoopsCode::getInstance();
        $pc = TDMCreatePhpCode::getInstance();
        $uc = UserXoopsCode::getInstance();
        $ret = $this->getInclude();
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (0 == $f) {
                $fieldId = $fieldName;
            }
            if (1 == $fields[$f]->getVar('field_parent')) {
                $fieldPid = $fieldName;
            }
        }
        if (1 == $table->getVar('table_category')) {
            $ccFieldPid = $this->getCamelCase($fieldPid, false, true);
            $ret .= $xc->getXcXoopsRequest("{$ccFieldPid}", "{$fieldPid}", '0', 'Int');
        }
        $ccFieldId = $this->getCamelCase($fieldId, false, true);
        $ret .= $xc->getXcXoopsRequest("{$ccFieldId}", "{$fieldId}", '0', 'Int');
        $ret .= $uc->getUserTplMain($moduleDirname, 'single');
        $ret .= $pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'header', true);
        $ret .= $pc->getPhpCodeCommentLine('Define Stylesheet');
        $ret .= $xc->getXcAddStylesheet();

        return $ret;
    }

    /**
     * @public function getUserSingleBody
     * @param $moduleDirname
     * @param $tableName
     * @param $language
     *
     * @return string
     */
    public function getUserSingleBody($moduleDirname, $tableName, $language)
    {
        $ret = <<<'EOT'

EOT;
        $ret .= $this->getSimpleString('$keywords = array();');

        return $ret;
    }

    /**
     * @private function getUserSingleFooter
     * @param $moduleDirname
     * @param $tableName
     * @param $language
     *
     * @return string
     */
    private function getUserSingleFooter($moduleDirname, $tableName, $language)
    {
        $xc = TDMCreateXoopsCode::getInstance();
        $pc = TDMCreatePhpCode::getInstance();
        $uc = UserXoopsCode::getInstance();
        $stuModuleDirname = strtoupper($moduleDirname);
        $stuTableName = strtoupper($tableName);
        $ret = $pc->getPhpCodeCommentLine('Breadcrumbs');
        $ret .= $uc->getUserBreadcrumbs($language, $stuTableName);
        $ret .= $pc->getPhpCodeCommentLine('Keywords');
        $ret .= $uc->getUserMetaKeywords($moduleDirname);
        $ret .= $pc->getPhpCodeUnset('keywords');
        $ret .= $pc->getPhpCodeCommentLine('Description');
        $ret .= $uc->getUserMetaDesc($moduleDirname, $language, $stuTableName);
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
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $tableId = $table->getVar('table_id');
        $tableMid = $table->getVar('table_mid');
        $tableName = $table->getVar('table_name');
        $fields = $this->getTableFields($tableMid, $tableId);
        $language = $this->getLanguage($moduleDirname, 'MA');
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getUserSingleHeader($moduleDirname, $table, $fields);
        $content .= $this->getUserSingleBody($module, $tableName, $language);
        $content .= $this->getUserSingleFooter($moduleDirname, $tableName, $language);
        $this->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
