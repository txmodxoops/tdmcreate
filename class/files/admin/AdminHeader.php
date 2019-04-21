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
 * @version         $Id: admin_header.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class AdminHeader.
 */
class AdminHeader extends TDMCreateFile
{
    /**
     * @var mixed
     */
    private $xc = null;

    /**
     *  @public function constructor
     *  @param null
     */
    public function __construct()
    {
        parent::__construct();
        $this->xc = TDMCreateXoopsCode::getInstance();
        $this->phpcode = TDMCreatePhpCode::getInstance();
    }

    /**
     *  @static function getInstance
     *  @param null
     * @return AdminHeader
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
     * @private function getAdminPagesHeader
     * @param $moduleDirname
     *
     * @return string
     */
    private function getAdminHeader($moduleDirname)
    {
        $ucfModuleDirname = ucfirst($moduleDirname);
        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $table = $this->getTable();
        $tables = $this->getTables();
        $ret = $this->phpcode->getPhpCodeIncludeDir('dirname(dirname(dirname(__DIR__)))', 'include/cp_header');
        $ret .= $this->phpcode->getPhpCodeIncludeDir('dirname(__DIR__)', 'include/common', true);
        $sysicons16 = $this->xc->getXcGetInfo('', 'sysicons16', true);
        $sysicons32 = $this->xc->getXcGetInfo('', 'sysicons32', true);
        $dirmoduleadmin = $this->xc->getXcGetInfo('', 'dirmoduleadmin', true);
        $modicons16 = $this->xc->getXcGetInfo('', 'modicons16', true);
        $modicons32 = $this->xc->getXcGetInfo('', 'modicons32', true);
        $ret .= $this->xc->getXcEqualsOperator('$sysPathIcon16 ', "'../' . {$sysicons16}");
        $ret .= $this->xc->getXcEqualsOperator('$sysPathIcon32 ', "'../' . {$sysicons32}");
        $ret .= $this->xc->getXcEqualsOperator('$pathModuleAdmin ', (string)$dirmoduleadmin);
        $ret .= $this->xc->getXcEqualsOperator('$modPathIcon16 ', (string)$modicons16);
        $ret .= $this->xc->getXcEqualsOperator('$modPathIcon32 ', (string)$modicons32);
        if (is_object($table) && '' != $table->getVar('table_name')) {
            $ret .= $this->phpcode->getPhpCodeCommentLine('Get instance of module');
            $ret .= $this->xc->getXcEqualsOperator("\${$moduleDirname}", "{$ucfModuleDirname}Helper::getInstance()");
        }
        if (is_array($tables)) {
            foreach (array_keys($tables) as $i) {
                $tableName = $tables[$i]->getVar('table_name');
                $ret .= $this->xc->getXcEqualsOperator("\${$tableName}Handler", "\${$moduleDirname}->getHandler('{$tableName}')", null, true);
            }
        }
        $ret .= $this->xc->getXcEqualsOperator('$myts', 'MyTextSanitizer::getInstance()', null, false);
        $ret .= $this->phpcode->getPhpCodeCommentLine();
        $template = $this->phpcode->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/template', true, false, 'include', "\t");
        $template .= $this->xc->getXcEqualsOperator('$xoopsTpl', 'new XoopsTpl()', null, false, "\t");
        $ret .= $this->phpcode->getPhpCodeConditions('!isset($xoopsTpl)', ' || ', '!is_object($xoopsTpl)', $template, false);
        $ret .= $this->phpcode->getPhpCodeCommentLine('System icons path');
        $ret .= $this->xc->getXcTplAssign('sysPathIcon16', '$sysPathIcon16');
        $ret .= $this->xc->getXcTplAssign('sysPathIcon32', '$sysPathIcon32');
        $ret .= $this->xc->getXcTplAssign('modPathIcon16', '$modPathIcon16');
        $ret .= $this->xc->getXcTplAssign('modPathIcon32', '$modPathIcon32');
        $ret .= $this->phpcode->getPhpCodeCommentLine('Load languages');
        $ret .= $this->xc->getXcLoadLanguage('admin');
        $ret .= $this->xc->getXcLoadLanguage('modinfo');
        $ret .= $this->phpcode->getPhpCodeCommentLine('Local admin menu class');
        $xoopsPathCond = $this->xc->getXcPath('$pathModuleAdmin', 'moduleadmin', true);
        $fileExists = $this->phpcode->getPhpCodeFileExists((string)$xoopsPathCond);
        $moduleadmin = $this->phpcode->getPhpCodeIncludeDir((string)$xoopsPathCond, '', true, true, 'include', "\t");
        $redirectHeader = $this->xc->getXcRedirectHeader('../../../admin.php', '', '5', '_AM_MODULEADMIN_MISSING', true, "\t");

        $ret .= $this->phpcode->getPhpCodeConditions((string)$fileExists, '', '', $moduleadmin, $redirectHeader);
        $ret .= $this->xc->getXcCPHeader();
        $ret .= $this->xc->getXcEqualsOperator('$adminObject', '\Xmf\Module\Admin::getInstance()');
        $ret .= $this->getSimpleString("\$style = {$stuModuleDirname}_URL . '/assets/css/admin/style.css';");

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
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getAdminHeader($moduleDirname);

        $this->create($moduleDirname, 'admin', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
