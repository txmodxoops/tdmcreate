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
 * @version         $Id: AdminIndex.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class AdminIndex.
 */
class AdminIndex extends TDMCreateFile
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
    * @return AdminIndex
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
    *  @param mixed $tables
    *  @param string $filename
    */
    public function write($module, $tables, $filename)
    {
        $this->setModule($module);
        $this->setTables($tables);
        $this->setFileName($filename);
    }

    /**
     * @private function render
     * @param $module
     *
     * @return string
     */
    private function getAdminIndex($module)
    {
        $pc = TDMCreatePhpCode::getInstance();
        $xc = TDMCreateXoopsCode::getInstance();
        $axc = AdminXoopsCode::getInstance();
        $moduleDirname = $module->getVar('mod_dirname');
        $tables = $this->getTableTables($module->getVar('mod_id'), 'table_order');
        $language = $this->getLanguage($moduleDirname, 'AM');
        $languageThereAre = $this->getLanguage($moduleDirname, 'AM', 'THEREARE_');
        $ret = $this->getInclude();
        $ret .= $pc->getPhpCodeCommentLine('Count elements');
        $tableName = null;
        foreach (array_keys($tables) as $i) {
            $tableName = $tables[$i]->getVar('table_name');
            $ucfTableName = ucfirst($tableName);
            $ret .= $xc->getXcEqualsOperator("\$count{$ucfTableName}", "\${$tableName}Handler->getCount()");
        }
        $ret .= $pc->getPhpCodeCommentLine('Template Index');
        $ret .= $axc->getAdminTemplateMain("{$moduleDirname}", 'index');
        $ret .= $pc->getPhpCodeCommentLine('InfoBox Statistics');
        $ret .= $axc->getAxcAddInfoBox($language.'STATISTICS');
        $ret .= $pc->getPhpCodeCommentLine('Info elements');
        foreach (array_keys($tables) as $i) {
            $tableName = $tables[$i]->getVar('table_name');
            $tableInstall[] = $tables[$i]->getVar('table_install');
            $stuTableName = $languageThereAre.strtoupper($tableName);
            $ucfTableName = ucfirst($tableName);
            $ret .= $axc->getAxcAddInfoBoxLine($language.'STATISTICS', $stuTableName, "\$count{$ucfTableName}");
        }

        if ($tableName == null) {
            $ret .= $axc->getAxcAddInfoBoxLine($language.'STATISTICS', 'No statistics', '0');
        }
        if (is_array($tables) && in_array(1, $tableInstall)) {
            $ret .= $pc->getPhpCodeCommentLine('Upload Folders');
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
            $ret .= $pc->getPhpCodeCommentLine('Uploads Folders Created');
            $boxLine = $axc->getAxcAddConfigBoxLine('$folder[$i]', 'folder', '', "\t");
            $boxLine .= $axc->getAxcAddConfigBoxLine("array(\$folder[\$i], '777')", 'chmod', '', "\t");
            $ret .= $pc->getPhpCodeForeach('folder', true, false, 'i', $boxLine, '').PHP_EOL;
        }
        $ret .= $pc->getPhpCodeCommentLine('Render Index');
        $ret .= $xc->getXcTplAssign('navigation', "\$adminMenu->addNavigation('index.php')");
        $ret .= $xc->getXcTplAssign('index', '$adminMenu->renderIndex()');

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
        $moduleDirname = $module->getVar('mod_dirname');
        $filename = $this->getFileName();
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getAdminIndex($module);

        $this->create($moduleDirname, 'admin', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
