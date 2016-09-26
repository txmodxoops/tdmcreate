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
 * @version         $Id: IncludeInstall.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class IncludeInstall.
 */
class IncludeInstall extends TDMCreateFile
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
     * @return IncludeInstall
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
     * @public function write
     * @param string $module
     * @param        $table
     * @param mixed  $tables
     * @param string $filename
     */
    public function write($module, $table, $tables, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setTables($tables);
        $this->setFileName($filename);
    }

    /**
     * @private function getInstallDirectory
     *
     * @param $dirname
     *
     * @return string
     */
    private function getInstallDirectory($dirname)
    {
        $this->pc = TDMCreatePhpCode::getInstance();
        $contentIf = $this->pc->getPhpCodeMkdir("{$dirname}", '0777', "\t");
        $contentIf .= $this->pc->getPhpCodeChmod("{$dirname}", '0777', "\t");
        $ret = $this->pc->getPhpCodeConditions("!is_dir(\${$dirname})", '', '', $contentIf);
        $ret .= $this->pc->getPhpCodeCopy('$indexFile', "\${$dirname}.'/index.html'");

        return $ret;
    }

    /**
     *  @private function getInstallModuleFolder
     *
     *  @param $moduleDirname
     *
     * @return string
     */
    private function getInstallModuleFolder($moduleDirname)
    {
        $this->pc = TDMCreatePhpCode::getInstance();
        $this->xc = TDMCreateXoopsCode::getInstance();
        $ret = $this->pc->getPhpCodeCommentLine('Copy base file');
        $ret .= $this->xc->getXcEqualsOperator('$indexFile', "XOOPS_UPLOAD_PATH.'/index.html'");
        $ret .= $this->xc->getXcEqualsOperator('$blankFile', "XOOPS_UPLOAD_PATH.'/blank.gif'");
        $ret .= $this->pc->getPhpCodeCommentLine("Making of uploads/{$moduleDirname} folder");
        $ret .= $this->xc->getXcEqualsOperator("\${$moduleDirname}", "XOOPS_UPLOAD_PATH.'/{$moduleDirname}'");
        $ret .= $this->getInstallDirectory($moduleDirname);

        return $ret;
    }

    /**
     *  @private function getHeaderTableFolder
     *
     *  @param $moduleDirname
     *  @param $tableName
     *
     * @return string
     */
    private function getInstallTableFolder($moduleDirname, $tableName)
    {
        $this->pc = TDMCreatePhpCode::getInstance();
        $this->xc = TDMCreateXoopsCode::getInstance();
        $ret = $this->pc->getPhpCodeCommentLine("Making of {$tableName} uploads folder");
        $ret .= $this->xc->getXcEqualsOperator("\${$tableName}", "\${$moduleDirname}.'/{$tableName}'");
        $ret .= $this->getInstallDirectory($tableName);

        return $ret;
    }

    /**
    *  @private function getInstallImagesFolder
    *  @param string $moduleDirname
     *
     * @return string
     */
    private function getInstallImagesFolder($moduleDirname)
    {
        $this->pc = TDMCreatePhpCode::getInstance();
        $this->xc = TDMCreateXoopsCode::getInstance();
        $ret = $this->pc->getPhpCodeCommentLine('Making of images folder');
        $ret .= $this->xc->getXcEqualsOperator('$images', "\${$moduleDirname}.'/images'");
        $ret .= $this->getInstallDirectory('images');
        $ret .= $this->pc->getPhpCodeCopy('$blankFile', "\$images.'/blank.gif'");

        return $ret;
    }

    /**
     * @private function getInstallImagesShotsFolder
     *
     * @param $moduleDirname
     *
     * @return string
     */
    private function getInstallImagesShotsFolder($moduleDirname)
    {
        $this->pc = TDMCreatePhpCode::getInstance();
        $this->xc = TDMCreateXoopsCode::getInstance();
        $ret = $this->pc->getPhpCodeCommentLine('Making of shots folder');
        $ret .= $this->xc->getXcEqualsOperator('$shots', "\${$moduleDirname}.'/shots'");
        $ret .= $this->getInstallDirectory('shots');
        $ret .= $this->pc->getPhpCodeCopy('$blankFile', "\$shots.'/blank.gif'");

        return $ret;
    }

    /**
     *  @private function getInstallTableImagesFolder
     *
     *  @param $tableName
     *
     * @return string
     */
    private function getInstallTableImagesFolder($tableName)
    {
        $this->pc = TDMCreatePhpCode::getInstance();
        $this->xc = TDMCreateXoopsCode::getInstance();
        $ret = $this->pc->getPhpCodeCommentLine("Making of images/{$tableName} folder");
        $ret .= $this->xc->getXcEqualsOperator("\${$tableName}", "\$images.'/{$tableName}'");
        $ret .= $this->getInstallDirectory($tableName);
        $ret .= $this->pc->getPhpCodeCopy('$blankFile', "\${$tableName}.'/blank.gif'");

        return $ret;
    }

    /**
     *  @private function getInstallFilesFolder
     *
     *  @param $moduleDirname
     *
     * @return string
     */
    private function getInstallFilesFolder($moduleDirname)
    {
        $this->pc = TDMCreatePhpCode::getInstance();
        $this->xc = TDMCreateXoopsCode::getInstance();
        $ret = $this->pc->getPhpCodeCommentLine('Making of files folder');
        $ret .= $this->xc->getXcEqualsOperator('$files', "\${$moduleDirname}.'/files'");
        $ret .= $this->getInstallDirectory('files');

        return $ret;
    }

    /**
    *  @private function getInstallTableFilesFolder
    *  @param string $tableName
     *
     * @return string
     */
    private function getInstallTableFilesFolder($tableName)
    {
        $this->pc = TDMCreatePhpCode::getInstance();
        $this->xc = TDMCreateXoopsCode::getInstance();
        $ret = $this->pc->getPhpCodeCommentLine("Making of {$tableName} files folder");
        $ret .= $this->xc->getXcEqualsOperator("\${$tableName}", "\$files.'/{$tableName}'");
        $ret .= $this->getInstallDirectory($tableName);

        return $ret;
    }

    /**
    *  @private function getInstallFooter
    *  @param null
     * @return string
     */
    private function getInstallFooter()
    {
        return $this->getDashComment('Install Footer');
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
        $table = $this->getTable();
        $tables = $this->getTables();
        $filename = $this->getFileName();
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getInstallModuleFolder($moduleDirname);
        foreach (array_keys($tables) as $t) {
            $tableId = $tables[$t]->getVar('table_id');
            $tableMid = $tables[$t]->getVar('table_mid');
            $tableName = $tables[$t]->getVar('table_name');
            $tableInstall = $tables[$t]->getVar('table_install');
            if (1 == $tableInstall) {
                $content .= $this->getInstallTableFolder($moduleDirname, $tableName);
                $fields = $this->getTableFields($tableMid, $tableId);
                foreach (array_keys($fields) as $f) {
                    $fieldElement = $fields[$f]->getVar('field_element');
                    // All fields elements selected
                    switch ($fieldElement) {
                        case 13:
                            $content .= $this->getInstallImagesFolder($moduleDirname);
                            $content .= $this->getInstallTableImagesFolder($tableName);
                            break;
                        case 11:
                            $content .= $this->getInstallImagesShotsFolder($moduleDirname);
                            break;
                        case 12:
                        case 14:
                            $content .= $this->getInstallFilesFolder($moduleDirname);
                            $content .= $this->getInstallTableFilesFolder($tableName);
                            break;
                    }
                }
            }
        }
        $content .= $this->getInstallFooter();

        $this->create($moduleDirname, 'include', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
