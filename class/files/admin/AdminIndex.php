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
 * @version         $Id: admin_index.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class AdminIndex.
 */
class AdminIndex extends AdminPhpCode
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
        $this->adminphpcode = AdminPhpCode::getInstance();
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
        $tables = $this->getTableTables($module->getVar('mod_id'), 'table_order');
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language = $this->getLanguage($moduleDirname, 'AM');
        $languageThereAre = $this->getLanguage($moduleDirname, 'AM', 'THEREARE_');
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->adminphpcode->getAdminIncludeDir('header');
        $content .= $this->getCommentLine('Count elements');
        $tableName = null;
        if (is_array($tables)) {
            foreach (array_keys($tables) as $i) {
                $tableName = $tables[$i]->getVar('table_name');
                $ucfTableName = ucfirst($tableName);
                $content .= <<<EOT
//\${$tableName}Handler =& \${$moduleDirname}->getHandler('{$tableName}');
\$count{$ucfTableName} = \${$tableName}Handler->getCount();\n
EOT;
            }
        }
        $content .= <<<EOT
// Template Index
\$templateMain = '{$moduleDirname}_admin_index.tpl';\n
EOT;
        if (is_array($tables)) {
            $content .= <<<EOT
// InfoBox Statistics
\$adminMenu->addInfoBox({$language}STATISTICS);
// Info elements\n
EOT;
            foreach (array_keys($tables) as $i) {
                $tableName = $tables[$i]->getVar('table_name');
                $tableInstall[] = $tables[$i]->getVar('table_install');
                $stuTableName = $languageThereAre.strtoupper($tableName);
                $content .= <<<EOT
\$adminMenu->addInfoBoxLine({$language}STATISTICS, '<label>'.{$stuTableName}.'</label>', \$count{$ucfTableName});\n
EOT;
            }
        }
        if ($tableName == null) {
            $content .= <<<EOT
\$adminMenu->addInfoBoxLine({$language}STATISTICS, '<label>No statistics</label>', 0);\n
EOT;
        }
        if (is_array($tables) && in_array(1, $tableInstall)) {
            $content .= <<<EOT
// Upload Folders
\$folder = array(\n
EOT;
            $stuModuleDirname = strtoupper($moduleDirname);
            foreach (array_keys($tables) as $i) {
                $tableName = $tables[$i]->getVar('table_name');
                if (1 == $tables[$i]->getVar('table_install')) {
                    $content .= <<<EOT
	\t{$stuModuleDirname}_UPLOAD_PATH . '/{$tableName}/',\n
EOT;
                }
            }
            $content .= <<<EOT
);

// Uploads Folders Created
foreach (array_keys( \$folder) as \$i) {
    \$adminMenu->addConfigBoxLine(\$folder[\$i], 'folder');
    \$adminMenu->addConfigBoxLine(array(\$folder[\$i], '777'), 'chmod');
}\n
EOT;
        }
        $content .= <<<EOT
// Render Index
echo \$adminMenu->addNavigation('index.php');
echo \$adminMenu->renderIndex();
EOT;
        $content .= $this->adminphpcode->getAdminIncludeDir('footer');
		
		$this->tdmcfile->create($moduleDirname, 'admin', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}
