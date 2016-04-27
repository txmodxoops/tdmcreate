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
 * @version         $Id: user_header.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class UserHeader.
 */
class UserHeader extends TDMCreateFile
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
    *  @static function getInstance
    *  @param null
    */
    /**
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

    /*
    *  @public function write
    *  @param string $module
    *  @param mixed $table
    *  @param array $tables
    *  @param string $filename
    */
    /**
     * @param $module
     * @param $table
     * @param $tables
     * @param $filename
     */
    public function write($module, $table, $tables, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setTables($tables);
        $this->setFileName($filename);
    }

    /*
    *  @private function getUserHeader
    *  @param $moduleDirname
    *
    *  @return string
    */
    private function getUserHeader($moduleDirname)
    {
        $stuModuleDirname = strtoupper($moduleDirname);
        $ucfModuleDirname = ucfirst($moduleDirname);

        $ret = $this->phpcode->getPhpCodeIncludeDir('dirname(dirname(__DIR__))', 'mainfile');
        $ret .= $this->phpcode->getPhpCodeIncludeDir('__DIR__', 'include/common');
        $ret .= $this->xc->getXcEqualsOperator('$dirname ', 'basename(__DIR__)');
        $ret .= $this->uc->getUserBreadcrumbsHeaderFile($moduleDirname);

        $table = $this->getTable();
        $tables = $this->getTables();
        if (is_object($table) && $table->getVar('table_name') != '') {
            $ret .= $this->xc->getXoopsHandlerInstance($moduleDirname);
        }
        if (is_array($tables)) {
            foreach (array_keys($tables) as $i) {
                $tableName = $tables[$i]->getVar('table_name');
                $ret .= $this->xc->getXoopsHandlerLine($moduleDirname, $tableName);
            }
        }
        $ret .= $this->phpcode->getPhpCodeCommentLine('Permission');
        $ret .= $this->phpcode->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/xoopsform/grouppermform', true);
        $ret .= $this->xc->getXcEqualsOperator('$gpermHandler', "xoops_gethandler('groupperm')", null, true);

        $condIf = $this->xc->getXcEqualsOperator('$groups ', '$xoopsUser->getGroups()', null, false, "\t");
        $condElse = $this->xc->getXcEqualsOperator('$groups ', 'XOOPS_GROUP_ANONYMOUS', null, false, "\t");

        $ret .= $this->phpcode->getPhpCodeConditions('is_object($xoopsUser)', '', '', $condIf, $condElse);
        $ret .= $this->phpcode->getPhpCodeCommentLine();
        $ret .= $this->xc->getXcEqualsOperator('$myts', 'MyTextSanitizer::getInstance()', null, true);
        $ret .= $this->phpcode->getPhpCodeCommentLine('Default Css Style');
        $ret .= $this->xc->getXcEqualsOperator('$style', "{$stuModuleDirname}_URL . '/assets/css/style.css'");
        $ret .= $this->phpcode->getPhpCodeConditions('!file_exists($style)', '', '', "\treturn false;\n");
        $ret .= $this->phpcode->getPhpCodeCommentLine('Smarty Default');
        $ret .= $this->xc->getXcGetInfo('sysPathIcon16', 'sysicons16');
        $ret .= $this->xc->getXcGetInfo('sysPathIcon32', 'sysicons32');
        $ret .= $this->xc->getXcGetInfo('pathModuleAdmin', 'dirmoduleadmin');
        $ret .= $this->xc->getXcGetInfo('modPathIcon16', 'modicons16');
        $ret .= $this->xc->getXcGetInfo('modPathIcon32', 'modicons16');
        $ret .= $this->phpcode->getPhpCodeCommentLine('Load Languages');
        $ret .= $this->xc->getXcLoadLanguage('main');
        $ret .= $this->xc->getXcLoadLanguage('modinfo');

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
        $moduleDirname = $module->getVar('mod_dirname');
        $filename = $this->getFileName();
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getUserHeader($moduleDirname);

        $this->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
