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
 * @version         $Id: admin_header.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class AdminHeader.
 */
class AdminHeader extends TDMCreateFile
{
    /*
    * @var mixed
    */
    private $phpcode = null;

    /*
    * @var mixed
    */
    private $xoopscode = null;

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
        $this->xoopscode = TDMCreateXoopsCode::getInstance();
        $this->phpcode = TDMCreatePhpCode::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return AdminHeader
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
    *  @param array $tables
    *  @param string $filename
    */
    /**
     * @param $module
     * @param $table
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
     * @private function getAdminPagesHeader
     * @param $moduleDirname
     *
     * @return string
     */
    private function getAdminHeader($moduleDirname)
    {
        $ucfModuleDirname = ucfirst($moduleDirname);
        $table = $this->getTable();
        $tables = $this->getTables();
        $ret = $this->phpcode->getPhpCodeIncludeDir('dirname(dirname(dirname(__DIR__)))', 'include/cp_header');
        $ret .= $this->phpcode->getPhpCodeIncludeDir('dirname(__DIR__)', 'include/common', true);
        $sysicons16 = $this->xoopscode->getXoopsCodeGetInfo('', 'sysicons16', true);
        $sysicons32 = $this->xoopscode->getXoopsCodeGetInfo('', 'sysicons32', true);
        $dirmoduleadmin = $this->xoopscode->getXoopsCodeGetInfo('', 'dirmoduleadmin', true);
        $modicons16 = $this->xoopscode->getXoopsCodeGetInfo('', 'modicons16', true);
        $modicons32 = $this->xoopscode->getXoopsCodeGetInfo('', 'modicons32', true);
        $ret .= $this->xoopscode->getXoopsCodeEqualsOperator('$sysPathIcon16 ', "'../' . {$sysicons16}");
        $ret .= $this->xoopscode->getXoopsCodeEqualsOperator('$sysPathIcon32 ', "'../' . {$sysicons32}");
        $ret .= $this->xoopscode->getXoopsCodeEqualsOperator('$pathModuleAdmin ', "{$dirmoduleadmin}");
        $ret .= $this->xoopscode->getXoopsCodeEqualsOperator('$modPathIcon16 ', "{$modicons16}");
        $ret .= $this->xoopscode->getXoopsCodeEqualsOperator('$modPathIcon32 ', "{$modicons32}");
        if (is_object($table) && $table->getVar('table_name') != '') {
            $ret .= $this->phpcode->getPhpCodeCommentLine('Get instance of module');
            $ret .= $this->xoopscode->getXoopsCodeEqualsOperator("\${$moduleDirname} ", "{$ucfModuleDirname}Helper::getInstance()");
        }
        if (is_array($tables)) {
            foreach (array_keys($tables) as $i) {
                $tableName = $tables[$i]->getVar('table_name');
                $ret .= $this->xoopscode->getXoopsCodeEqualsOperator("\${$tableName}Handler ", "\${$moduleDirname}->getHandler('{$tableName}')", true);
            }
        }
        $ret .= $this->xoopscode->getXoopsCodeEqualsOperator('$myts', 'MyTextSanitizer::getInstance()', true);
        $ret .= $this->phpcode->getPhpCodeCommentLine();
        $template = $this->phpcode->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/template', true);
        $template .= "\t".$this->xoopscode->getXoopsCodeEqualsOperator('$xoopsTpl', 'new XoopsTpl()');
        $ret .= $this->phpcode->getPhpCodeConditions('!isset($xoopsTpl)', ' || ', '!is_object($xoopsTpl)', $template, false);
        $ret .= $this->phpcode->getPhpCodeCommentLine('System icons path');
        $ret .= $this->xoopscode->getXoopsCodeTplAssign('sysPathIcon16', '$sysPathIcon16');
        $ret .= $this->xoopscode->getXoopsCodeTplAssign('sysPathIcon32', '$sysPathIcon32');
        $ret .= $this->xoopscode->getXoopsCodeTplAssign('modPathIcon16', '$modPathIcon16');
        $ret .= $this->xoopscode->getXoopsCodeTplAssign('modPathIcon32', '$modPathIcon32');
        $ret .= $this->phpcode->getPhpCodeCommentLine('Load languages');
        $ret .= $this->xoopscode->getXoopsCodeLoadLanguage('admin');
        $ret .= $this->xoopscode->getXoopsCodeLoadLanguage('modinfo');
        $ret .= $this->phpcode->getPhpCodeCommentLine('Local admin menu class');
        $xoopsPathCond = $this->xoopscode->getXoopsCodePath('$pathModuleAdmin', 'moduleadmin', true);
        $fileExists = $this->phpcode->getPhpCodeFileExists("{$xoopsPathCond}");
        $moduleadmin = $this->phpcode->getPhpCodeIncludeDir("{$xoopsPathCond}", '', true, true);
        $redirectHeader = $this->xoopscode->getXoopsCodeRedirectHeader('../../../admin.php', '', '5', '_AM_MODULEADMIN_MISSING');

        $ret .= $this->phpcode->getPhpCodeConditions("{$fileExists}", '', '', $moduleadmin, $redirectHeader);
        $ret .= $this->xoopscode->getXoopsCodeCPHeader();
        $ret .= $this->xoopscode->getXoopsCodeEqualsOperator('$adminMenu', 'new ModuleAdmin()');

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
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getAdminHeader($moduleDirname);

        $this->create($moduleDirname, 'admin', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
