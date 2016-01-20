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
 * @version         $Id: AdminIndex.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class AdminIndex.
 */
class AdminIndex extends TDMCreateFile
{
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
        $this->xoopscode = TDMCreateXoopsCode::getInstance();
        $this->adminxoopscode = AdminXoopsCode::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return AdminIndex
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
    *  @param mixed $tables
    *  @param string $filename
    */
    /**
     * @param $module
     * @param $tables
     * @param $filename
     */
    public function write($module, $tables, $filename)
    {
        $this->setModule($module);
        $this->setTables($tables);
        $this->setFileName($filename);
    }

    /**
     * @private function render
     *
     * @param $module
     *
     * @return string
     */
    private function getAdminIndex($module)
    {
        $moduleDirname = $module->getVar('mod_dirname');
        $tables = $this->getTableTables($module->getVar('mod_id'), 'table_order');
        $language = $this->getLanguage($moduleDirname, 'AM');
        $languageThereAre = $this->getLanguage($moduleDirname, 'AM', 'THEREARE_');
        $ret = $this->getInclude();
        $ret .= $this->getCommentLine('Count elements');
        $tableName = null;
        foreach (array_keys($tables) as $i) {
            $tableName = $tables[$i]->getVar('table_name');
            $ucfTableName = ucfirst($tableName);
            $ret .= $this->xoopscode->getXoopsCodeEqualsOperator("\$count{$ucfTableName}", "\${$tableName}Handler->getCount()");
        }
        $ret .= $this->phpcode->getPhpCodeCommentLine('Template Index');
        $ret .= $this->adminxoopscode->getAdminTemplateMain("{$moduleDirname}", 'index');
        $ret .= $this->phpcode->getPhpCodeCommentLine('InfoBox Statistics');
        $ret .= $this->adminxoopscode->getXoopsCodeAddInfoBox($language.'STATISTICS');
        $ret .= $this->phpcode->getPhpCodeCommentLine('Info elements');
        foreach (array_keys($tables) as $i) {
            $tableName = $tables[$i]->getVar('table_name');
            $tableInstall[] = $tables[$i]->getVar('table_install');
            $stuTableName = $languageThereAre.strtoupper($tableName);
            $ucfTableName = ucfirst($tableName);
            $ret .= $this->adminxoopscode->getXoopsCodeAddInfoBoxLine($language.'STATISTICS', $stuTableName, "\$count{$ucfTableName}", true);
        }

        if ($tableName == null) {
            $ret .= $this->adminxoopscode->getXoopsCodeAddInfoBoxLine($language.'STATISTICS', 'No statistics', '0', true);
        }
        if (is_array($tables) && in_array(1, $tableInstall)) {
            $ret .= $this->phpcode->getPhpCodeCommentLine('Upload Folders');
            $ret .= $this->getSimpleString('$folder = array(');
            $stuModuleDirname = strtoupper($moduleDirname);
            $ret .= $this->getSimpleString("\t{$stuModuleDirname}_UPLOAD_PATH,");
            foreach (array_keys($tables) as $i) {
                $tableName = $tables[$i]->getVar('table_name');
                if (1 == $tables[$i]->getVar('table_install')) {
                    $ret .= $this->getSimpleString("\t{$stuModuleDirname}_UPLOAD_PATH . '/{$tableName}/',");
                }
            }
            $ret .= $this->getSimpleString(');');
            $ret .= $this->getCommentLine('Uploads Folders Created');
            $boxLine = $this->adminxoopscode->getXoopsCodeAddConfigBoxLine('$folder[$i]', 'folder');
            $boxLine .= "\t".$this->adminxoopscode->getXoopsCodeAddConfigBoxLine("array(\$folder[\$i], '777')", 'chmod');
            $ret .= $this->phpcode->getPhpCodeForeach('folder', true, false, 'i', $boxLine);
        }
        $ret .= $this->getCommentLine('Render Index');
        $ret .= $this->xoopscode->getXoopsCodeTplAssign('navigation', "\$adminMenu->addNavigation('index.php')");
        $ret .= $this->xoopscode->getXoopsCodeTplAssign('index', '$adminMenu->renderIndex()');

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
        $moduleDirname = $module->getVar('mod_dirname');
        $filename = $this->getFileName();
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getAdminIndex($module);

        $this->create($moduleDirname, 'admin', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
