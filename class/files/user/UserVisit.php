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
 * @version         $Id: UserVisit.php 12258 2014-01-02 09:33:29Z timgno \$
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class UserVisit.
 */
class UserVisit extends TDMCreateFile
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
        $this->tdmcfile = TDMCreateFile::getInstance();
        $this->phpcode = TDMCreatePhpCode::getInstance();
        $this->xoopscode = TDMCreateXoopsCode::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return UserVisit
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
    *  @param string $filename
    */
    /**
     * @param $module
     * @param $filename
     */
    public function write($module, $filename)
    {
        $this->setModule($module);
        $this->setFileName($filename);
    }

    /*
    *  @public function getAdminPagesList
    *  @param string $tableName
    *  @param string $language
    */
    /**
     * @param $module
     * @param $tableName
     *
     * @return string
     */
    public function getUserVisit($moduleDirname, $tableName, $fields)
    {
        $fieldId = $this->xoopscode->getXoopsCodeGetFieldId($fields);
        $ccFieldId = $this->tdmcfile->getCamelCase($fieldId, false, true);
        $ret = <<<EOT
include  __DIR__ . '/header.php';
\${$ccFieldId} = XoopsRequest::getInt('{$fieldId}');
\$agree = XoopsRequest::getInt('agree', 0, 'GET');
\$sql = sprintf("UPDATE ".\$xoopsDB->prefix('{$moduleDirname}_{$tableName}')." SET hits = hits+1 WHERE {$fieldId} =\${$ccFieldId}");
\$xoopsDB->queryF(\$sql);
\$result = \$xoopsDB->query("SELECT url FROM ".\$xoopsDB->prefix('{$moduleDirname}_{$tableName}')." WHERE {$fieldId}=\${$ccFieldId}");
list(\$url) = \$xoopsDB->fetchRow(\$result);
\$url = \$myts->htmlSpecialChars(preg_replace('/javascript:/si' , 'java script:', \$url), ENT_QUOTES);
if (!empty(\$url)) {
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	// HTTP/1.0
	header("Pragma: no-cache");
	// Date in the past
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	// always modified
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Refresh: 0; url=\$url");
} else {
	reportBroken(\${$ccFieldId});
}\n
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
        $tables = $this->getTableTables($module->getVar('mod_id'));
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        foreach (array_keys($tables) as $t) {
            $tableId = $tables[$t]->getVar('table_id');
            $tableMid = $tables[$t]->getVar('table_mid');
            $tableName = $tables[$t]->getVar('table_name');
            $tableVisit[] = $tables[$t]->getVar('table_visit');
        }
        $fields = $this->tdmcfile->getTableFields($tableMid, $tableId);
        $content = $this->getHeaderFilesComments($module, $filename);
        if (in_array(1, $tableVisit)) {
            $content .= $this->getUserVisit($moduleDirname, $tableName, $fields);
        }
        $this->tdmcfile->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}
