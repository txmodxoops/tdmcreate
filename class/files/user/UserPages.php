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
defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class UserPages.
 */
class UserPages extends TDMCreateFile
{
    /*
    * @var mixed
    */
    private $usercode = null;

    /*
    * @var mixed
    */
    private $phpcode = null;

    /*
    * @var string
    */
    private $xoopscode;

    /*
    * @var string
    */
    private $tdmcfile;

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
        $this->xoopscode = TDMCreateXoopsCode::getInstance();
        $this->usercode = UserXoopsCode::getInstance();
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
        $ret .= $this->usercode->getUserTplMain($moduleDirname, $tableName);
        $ret .= $this->phpcode->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'header', true);
        $ret .= $this->xoopscode->getXoopsCodeXoopsRequest('start', 'start', '0', 'Int');
        $userpager = $this->xoopscode->getXoopsCodeGetConfig($moduleDirname, 'userpager');
        $ret .= $this->xoopscode->getXoopsCodeXoopsRequest('limit', 'limit', $userpager, 'Int');
        $ret .= $this->getCommentLine('Define Stylesheet');
        $ret .= $this->xoopscode->getXoopsCodeAddStylesheet();

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
        $ret = $this->getCommentLine();
        $ret .= $this->xoopscode->getXoopsCodeTplAssign('xoops_icons32_url', 'XOOPS_ICONS32_URL');
        $ret .= $this->xoopscode->getXoopsCodeTplAssign("{$moduleDirname}_upload_url", "{$stuModuleDirname}_UPLOAD_URL");
        $ret .= $this->getCommentLine();
        $ret .= $this->xoopscode->getXoopsCodeObjHandlerCount($tableName);
        $ret .= $this->xoopscode->getXoopsCodeObjHandlerAll($tableName, '', '$start', '$limit');
        $ret .= $this->getSimpleString('$keywords = array();');
        $condIf = $this->getCommentLine('Get All', $ucfTableName);
        $foreach = $this->xoopscode->getXoopsCodeGetValues($tableName, $tableSoleName);
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
        $condIf .= $this->phpcode->getPhpCodeForeach("\${$tableName}All", true, false, 'i', $foreach, "\t");
        $condIf .= $this->xoopscode->getXoopsCodePageNav($tableName);
        $tableType = $this->xoopscode->getXoopsCodeGetConfig($moduleDirname, 'table_type');
        $condIf .= $this->xoopscode->getXoopsCodeTplAssign('type', $tableType);
        $divideby = $this->xoopscode->getXoopsCodeGetConfig($moduleDirname, 'divideby');
        $condIf .= $this->xoopscode->getXoopsCodeTplAssign('divideby', $divideby);
        $numbCol = $this->xoopscode->getXoopsCodeGetConfig($moduleDirname, 'numb_col');
        $condIf .= $this->xoopscode->getXoopsCodeTplAssign('numb_col', $numbCol);

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
        $ret = $this->getCommentLine('Breadcrumbs');
        $ret .= $this->usercode->getUserBreadcrumbs("{$stuTableName}", $language);
        $ret .= $this->getCommentLine('Keywords');
        $ret .= $this->usercode->getUserMetaKeywords($moduleDirname);
        $ret .= $this->phpcode->getPhpCodeUnset('keywords');
        $ret .= $this->getCommentLine('Description');
        $ret .= $this->usercode->getUserMetaDesc($moduleDirname, $stuTableSoleName, $language);
        $ret .= $this->xoopscode->getXoopsCodeTplAssign('xoops_mpageurl', "{$stuModuleDirname}_URL.'/{$tableName}.php'");
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
