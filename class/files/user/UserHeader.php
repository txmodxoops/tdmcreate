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
 * @version         $Id: user_header.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class UserHeader.
 */
class UserHeader extends TDMCreateFile
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
     * @return UserHeader
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
     *  @param array $tables
     *  @param string $filename
     */
    public function write($module, $table, $tables, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setTables($tables);
        $this->setFileName($filename);
    }

    /**
     *  @private function getUserHeader
     *  @param $moduleDirname
     *
     *  @return string
     */
    private function getUserHeader($moduleDirname)
    {
        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $ucfModuleDirname = ucfirst($moduleDirname);
        $xc = TDMCreateXoopsCode::getInstance();
        $pc = TDMCreatePhpCode::getInstance();
        $uc = UserXoopsCode::getInstance();
        $ret = $pc->getPhpCodeIncludeDir('dirname(dirname(__DIR__))', 'mainfile');
        $ret .= $pc->getPhpCodeIncludeDir('__DIR__', 'include/common');
        $ret .= $xc->getXcEqualsOperator('$dirname', 'basename(__DIR__)');
        $language = $this->getLanguage($moduleDirname, 'MA');
        $ret .= $uc->getUserBreadcrumbsHeaderFile($moduleDirname, $language);

        $table = $this->getTable();
        $tables = $this->getTables();
        if (is_object($table) && '' != $table->getVar('table_name')) {
            $ret .= $xc->getXoopsHandlerInstance($moduleDirname);
        }
        if (is_array($tables)) {
            foreach (array_keys($tables) as $i) {
                $tableName = $tables[$i]->getVar('table_name');
                $ret .= $xc->getXoopsHandlerLine($moduleDirname, $tableName);
            }
        }
        $ret .= $pc->getPhpCodeCommentLine('Permission');
        $ret .= $pc->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/xoopsform/grouppermform', true);
        $ret .= $xc->getXcEqualsOperator('$gpermHandler', "xoops_getHandler('groupperm')", null, true);

        $condIf = $xc->getXcEqualsOperator('$groups ', '$xoopsUser->getGroups()', null, false, "\t");
        $condElse = $xc->getXcEqualsOperator('$groups ', 'XOOPS_GROUP_ANONYMOUS', null, false, "\t");

        $ret .= $pc->getPhpCodeConditions('is_object($xoopsUser)', '', '', $condIf, $condElse);
        $ret .= $pc->getPhpCodeCommentLine();
        $ret .= $xc->getXcEqualsOperator('$myts', 'MyTextSanitizer::getInstance()', null, true);
        $ret .= $pc->getPhpCodeCommentLine('Default Css Style');
        $ret .= $xc->getXcEqualsOperator('$style', "{$stuModuleDirname}_URL . '/assets/css/style.css'");
        $ret .= $pc->getPhpCodeConditions('!file_exists($style)', '', '', "\treturn false;\n");
        $ret .= $pc->getPhpCodeCommentLine('Smarty Default');
        $ret .= $xc->getXcGetInfo('sysPathIcon16', 'sysicons16');
        $ret .= $xc->getXcGetInfo('sysPathIcon32', 'sysicons32');
        $ret .= $xc->getXcGetInfo('pathModuleAdmin', 'dirmoduleadmin');
        $ret .= $xc->getXcGetInfo('modPathIcon16', 'modicons16');
        $ret .= $xc->getXcGetInfo('modPathIcon32', 'modicons16');
        $ret .= $pc->getPhpCodeCommentLine('Load Languages');
        $ret .= $xc->getXcLoadLanguage('main');
        $ret .= $xc->getXcLoadLanguage('modinfo');

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
        $moduleDirname = $module->getVar('mod_dirname');
        $filename = $this->getFileName();
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getUserHeader($moduleDirname);

        $this->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
