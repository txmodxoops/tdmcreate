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
 * @version         $Id: UserSingle.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class UserSingle.
 */
class UserSingle extends TDMCreateFile
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
     * @return UserSingle
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
     * @private function getUserSingleHeader
     *
     * @param $moduleDirname
     *
     * @return string
     */
    private function getUserSingleHeader($moduleDirname, $table, $fields)
    {
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
        if ($table->getVar('table_category') == 1) {
            $ccFieldPid = $this->getCamelCase($fieldPid, false, true);
            $ret .= $this->xc->getXcXoopsRequest("{$ccFieldPid}", "{$fieldPid}", '0', 'Int');
        }
        $ccFieldId = $this->getCamelCase($fieldId, false, true);
        $ret .= $this->xc->getXcXoopsRequest("{$ccFieldId}", "{$fieldId}", '0', 'Int');
        $ret .= $this->uc->getUserTplMain($moduleDirname);
        $ret .= $this->phpcode->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'header', true);
        $ret .= $this->phpcode->getPhpCodeCommentLine('Define Stylesheet');
        $ret .= $this->xc->getXcAddStylesheet();

        return $ret;
    }

    /*
     * @public function getUserSingleBody     
     * @param $moduleDirname
     * @param $tableName
     * @param $language
     *
     * @return string
     */
    public function getUserSingleBody($moduleDirname, $tableName, $language)
    {
        $ret = <<<EOT

EOT;
        $ret .= $this->getSimpleString('$keywords = array();');

        return $ret;
    }

    /**
     * @private function getUserSingleFooter
     *
     * @param $moduleDirname
     * @param $tableName
     * @param $language
     *
     * @return string
     */
    private function getUserSingleFooter($moduleDirname, $tableName, $language)
    {
        $stuModuleDirname = strtoupper($moduleDirname);
        $stuTableName = strtoupper($tableName);
        $ret = $this->phpcode->getPhpCodeCommentLine('Breadcrumbs');
        $ret .= $this->uc->getUserBreadcrumbs($language, $stuTableName);
        $ret .= $this->phpcode->getPhpCodeCommentLine('Keywords');
        $ret .= $this->uc->getUserMetaKeywords($moduleDirname);
        $ret .= $this->phpcode->getPhpCodeUnset('keywords');
        $ret .= $this->phpcode->getPhpCodeCommentLine('Description');
        $ret .= $this->uc->getUserMetaDesc($moduleDirname, $language, $stuTableName);
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
