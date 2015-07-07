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
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class IncludeInstall.
 */
class IncludeInstall extends TDMCreateFile
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
        $this->tdmcfile = TDMCreateFile::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return IncludeInstall
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
     * @param $table
     * @param $tables
     * @param $filename
     */
    public function write($module, $filename)
    {
        $this->setModule($module);
        $this->setFileName($filename);
    }

    /*
    *  @private function getInstallModuleFolder
    *  @param string $moduleDirname
    */
    /**
     * @param $moduleDirname
     *
     * @return string
     */
    private function getInstallModuleFolder($moduleDirname)
    {
        $ret = <<<EOT
//
defined('XOOPS_ROOT_PATH') or die('Restricted access');
// Copy base file
\$indexFile = XOOPS_UPLOAD_PATH.'/index.html';
\$blankFile = XOOPS_UPLOAD_PATH.'/blank.gif';
// Making of "uploads/{$moduleDirname}" folder
\${$moduleDirname} = XOOPS_UPLOAD_PATH.'/{$moduleDirname}';
if(!is_dir(\${$moduleDirname}))
    mkdir(\${$moduleDirname}, 0777);
    chmod(\${$moduleDirname}, 0777);
copy(\$indexFile, \${$moduleDirname}.'/index.html');\n
EOT;

        return $ret;
    }

    /*
    *  @private function getHeaderTableFolder
    *  @param string $moduleDirname
    *  @param string $tableName
    */
    /**
     * @param $moduleDirname
     * @param $tableName
     *
     * @return string
     */
    private function getInstallTableFolder($moduleDirname, $tableName)
    {
        $ret = <<<EOT
// Making of {$tableName} uploads folder
\${$tableName} = \${$moduleDirname}.'/{$tableName}';
if(!is_dir(\${$tableName}))
    mkdir(\${$tableName}, 0777);
    chmod(\${$tableName}, 0777);
copy(\$indexFile, \${$tableName}.'/index.html');\n
EOT;

        return $ret;
    }

    /*
    *  @private function getInstallImagesFolder
    *  @param string $moduleDirname
    */
    /**
     * @param $moduleDirname
     *
     * @return string
     */
    private function getInstallImagesFolder($moduleDirname)
    {
        $ret = <<<EOT
// Making of images folder
\$images = \${$moduleDirname}.'/images';
if(!is_dir(\$images))
    mkdir(\$images, 0777);
    chmod(\$images, 0777);
copy(\$indexFile, \$images.'/index.html');
copy(\$blankFile, \$images.'/blank.gif');\n
EOT;

        return $ret;
    }

    /*
    *  @private function getInstallImagesShotsFolder
    *  @param $tableName
    */
    /**
     * @param $tableName
     *
     * @return string
     */
    private function getInstallImagesShotsFolder($tableName)
    {
        $ret = <<<EOT
// Making of "{$tableName}" images folder
\$shots = \$images.'/shots';
if(!is_dir(\${$tableName}))
    mkdir(\$shots, 0777);
    chmod(\$shots, 0777);
copy(\$indexFile, \$shots.'/index.html');
copy(\$blankFile, \$shots.'/blank.gif');\n
EOT;

        return $ret;
    }

    /*
    *  @private function getInstallTableImagesFolder
    *  @param string $tableName
    */
    /**
     * @param $tableName
     *
     * @return string
     */
    private function getInstallTableImagesFolder($tableName)
    {
        $ret = <<<EOT
// Making of "{$tableName}" images folder
\${$tableName} = \$images.'/{$tableName}';
if(!is_dir(\${$tableName}))
    mkdir(\${$tableName}, 0777);
    chmod(\${$tableName}, 0777);
copy(\$indexFile, \${$tableName}.'/index.html');
copy(\$blankFile, \${$tableName}.'/blank.gif');\n
EOT;

        return $ret;
    }

    /*
    *  @private function getInstallFilesFolder
    *  @param string $moduleDirname
    */
    /**
     * @param $moduleDirname
     *
     * @return string
     */
    private function getInstallFilesFolder($moduleDirname)
    {
        $ret = <<<EOT
// Making of files folder
\$files = \${$moduleDirname}.'/files';
if(!is_dir(\$files))
    mkdir(\$files, 0777);
    chmod(\$files, 0777);
copy(\$indexFile, \$files.'/index.html');\n
EOT;

        return $ret;
    }

    /*
    *  @private function getInstallTableFilesFolder
    *  @param string $tableName
    */
    /**
     * @param $tableName
     *
     * @return string
     */
    private function getInstallTableFilesFolder($tableName)
    {
        $ret = <<<EOT
// Making of "{$tableName}" files folder
\${$tableName} = \$files.'/{$tableName}';
if(!is_dir(\${$tableName}))
    mkdir(\${$tableName}, 0777);
    chmod(\${$tableName}, 0777);
copy(\$indexFile, \${$tableName}.'/index.html');\n
EOT;

        return $ret;
    }

    /*
    *  @private function getInstallFooter
    *  @param null
    */
    /**
     * @return string
     */
    private function getInstallFooter()
    {
        $ret = <<<EOT
// ---------- Install Footer ---------- //
EOT;

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
        $tables = $this->getTableTables($module->getVar('mod_id'));
        $filename = $this->getFileName();
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getInstallModuleFolder($moduleDirname);

        foreach (array_keys($tables) as $t) {
            $tableId = $tables[$t]->getVar('table_id');
            $tableMid = $tables[$t]->getVar('table_mid');
            $tableName = $tables[$t]->getVar('table_name');
            $tableInstall[] = $tables[$t]->getVar('table_install');
            if (in_array(1, $tableInstall)) {
                $content .= $this->getInstallTableFolder($moduleDirname, $tableName);
            }
        }
        $fields = $this->getTableFields($tableMid, $tableId);
        foreach (array_keys($fields) as $f) {
            $fieldElement = $fields[$f]->getVar('field_element');
            // All fields elements selected
            switch ($fieldElement) {
                case 10:
                case 11:
                    $content .= $this->getInstallImagesShotsFolder($tableName);
                    break;
                case 13:
                    $content .= $this->getInstallImagesFolder($moduleDirname);
                    foreach (array_keys($tables) as $t) {
                        $tableName = $tables[$t]->getVar('table_name');
                        $tableInstall[] = $tables[$t]->getVar('table_install');
                        if (in_array(1, $tableInstall)) {
                            $content .= $this->getInstallTableImagesFolder($tableName);
                        }
                    }
                    break;
                case 12:
                case 14:
                    $content .= $this->getInstallFilesFolder($moduleDirname);
                    foreach (array_keys($tables) as $t) {
                        $tableName = $tables[$t]->getVar('table_name');
                        $tableInstall[] = $tables[$t]->getVar('table_install');
                        if (in_array(1, $tableInstall)) {
                            $content .= $this->getInstallTableFilesFolder($tableName);
                        }
                    }
                    break;
            }
        }
        $content .= $this->getInstallFooter();

        $this->tdmcfile->create($moduleDirname, 'include', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}
