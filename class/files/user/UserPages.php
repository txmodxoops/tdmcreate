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
 * @version         $Id: pages.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class UserPages.
 */
class UserPages extends TDMCreateFile
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
    * @var string
    */
    private $tdmcfile = null;

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
        $this->tdmcfile = TDMCreateFile::getInstance();
        $this->phpcode = TDMCreatePhpCode::getInstance();
        $this->xc = TDMCreateXoopsCode::getInstance();
        $this->uc = UserXoopsCode::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return UserPages
     */
    public static function &getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * @public function write
     *
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
     *
     * @param $moduleDirname
     * @param $tableName
     *
     * @return string
     */
    private function getUserPagesHeader($moduleDirname, $tableName)
    {
        $ret = $this->getInclude();
        $ret .= $this->uc->getUserTplMain($moduleDirname, $tableName);
        $ret .= $this->phpcode->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'header', true);
        $ret .= $this->xc->getXcXoopsRequest('start', 'start', '0', 'Int');
        $userpager = $this->xc->getXcGetConfig($moduleDirname, 'userpager');
        $ret .= $this->xc->getXcXoopsRequest('limit', 'limit', $userpager, 'Int');
        $ret .= $this->phpcode->getPhpCodeCommentLine('Define Stylesheet');
        $ret .= $this->xc->getXcAddStylesheet();

        return $ret;
    }

    /**
     * @private function getUserPages
     *
     * @param $moduleDirname
     * @param $tableName
     *
     * @return string
     */
    private function getUserPages($moduleDirname, $tableName, $tableSoleName)
    {
        $stuModuleDirname = strtoupper($moduleDirname);
        $ucfTableName = ucfirst($tableName);
        $ret = $this->phpcode->getPhpCodeCommentLine();
        $ret .= $this->xc->getXcTplAssign('xoops_icons32_url', 'XOOPS_ICONS32_URL');
        $ret .= $this->xc->getXcTplAssign("{$moduleDirname}_url", "{$stuModuleDirname}_URL");
        $ret .= $this->phpcode->getPhpCodeCommentLine();
        $ret .= $this->xc->getXcObjHandlerCount($tableName);
        $ret .= $this->xc->getXcObjHandlerAll($tableName, '', '$start', '$limit');
        $ret .= $this->phpcode->getPhpCodeArray('keywords', null, false, '');
        $condIf = $this->phpcode->getPhpCodeCommentLine('Get All', $ucfTableName, "\t");
        $foreach = $this->xc->getXcGetValues($tableName, $tableSoleName, 'i', false, "\t");
        $foreach .= $this->xc->getXcXoopsTplAppend($tableName, "\${$tableSoleName}", "\t\t");
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
        $condIf .= $this->phpcode->getPhpCodeForeach("{$tableName}All", true, false, 'i', $foreach, "\t");
        $condIf .= $this->xc->getXcPageNav($tableName, "\t");
        $tableType = $this->xc->getXcGetConfig($moduleDirname, 'table_type');
        $condIf .= $this->xc->getXcTplAssign('type', $tableType, true, "\t");
        $divideby = $this->xc->getXcGetConfig($moduleDirname, 'divideby');
        $condIf .= $this->xc->getXcTplAssign('divideby', $divideby, true, "\t");
        $numbCol = $this->xc->getXcGetConfig($moduleDirname, 'numb_col');
        $condIf .= $this->xc->getXcTplAssign('numb_col', $numbCol, true, "\t");

        $ret .= $this->phpcode->getPhpCodeConditions("\${$tableName}Count", ' > ', '0', $condIf);

        return $ret;
    }

    /**
     * @private function getUserPagesFooter
     *
     * @param $moduleDirname
     * @param $tableName
     * @param $tableSoleName
     * @param $language
     *
     * @return string
     */
    private function getUserPagesFooter($moduleDirname, $tableName, $tableSoleName, $language)
    {
        $stuModuleDirname = strtoupper($moduleDirname);
        $stuTableName = strtoupper($tableName);
        $stuTableSoleName = strtoupper($tableSoleName);
        $ret = $this->phpcode->getPhpCodeCommentLine('Breadcrumbs');
        $ret .= $this->uc->getUserBreadcrumbs($language, $stuTableName);
        $ret .= $this->phpcode->getPhpCodeCommentLine('Keywords');
        $ret .= $this->uc->getUserMetaKeywords($moduleDirname);
        $ret .= $this->phpcode->getPhpCodeUnset('keywords');
        $ret .= $this->phpcode->getPhpCodeCommentLine('Description');
        $ret .= $this->uc->getUserMetaDesc($moduleDirname, $language, $stuTableSoleName);
        $ret .= $this->xc->getXcTplAssign('xoops_mpageurl', "{$stuModuleDirname}_URL.'/{$tableName}.php'");
        $ret .= $this->xc->getXcTplAssign("{$moduleDirname}_upload_url", "{$stuModuleDirname}_UPLOAD_URL");
        $ret .= $this->getInclude('footer');

        return $ret;
    }

    /*
    *  @public function render
    *  @param null
    */
    /**
     * @param $filename
     *
     * @return bool|string
     */
    public function renderFile()
    {
        $module = $this->getModule();
        $table = $this->getTable();
        $tableName = $table->getVar('table_name');
        $tableSoleName = $table->getVar('table_solename');
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language = $this->getLanguage($moduleDirname, 'MA');
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getUserPagesHeader($moduleDirname, $tableName);
        $content .= $this->getUserPages($moduleDirname, $tableName, $tableSoleName);
        $content .= $this->getUserPagesFooter($moduleDirname, $tableName, $tableSoleName, $language);
        //
        $this->tdmcfile->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}
