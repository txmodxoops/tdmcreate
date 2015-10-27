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
class UserVisit extends UserObjects
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
        $this->userobjects = UserObjects::getInstance();
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
    *  @param mixed $table
    *  @param string $filename
    */
    /**
     * @param $module
     * @param $table
     * @param $filename
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
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
     * @param $language
     *
     * @return string
     */
    public function getUserVisit($moduleDirname, $tableName, $tableSoleName, $fields, $language)
    {
        $stuModuleName = strtoupper($moduleDirname);
        $fieldId = $this->userobjects->getUserSaveFieldId($fields);
		$fieldMain = $this->userobjects->getUserSaveFieldMain($fields);
        $ccFieldId = $this->tdmcfile->getCamelCase($fieldId, false, true);
        $ret = <<<EOT
include  __DIR__ . '/header.php';
\$GLOBALS['xoopsOption']['template_main'] = '{$moduleDirname}_{$tableName}.tpl';
\${$ccFieldId} = XoopsRequest::getInt('{$fieldId}');
\$keywords = array();

\$GLOBALS['xoopsTpl']->assign('xoops_pagetitle', strip_tags(\$view_{$tableSoleName}->getVar('{$fieldMain}')  . ' - ' . \$GLOBALS['xoopsModule']->name()));
// keywords
{$moduleDirname}MetaKeywords(\${$moduleDirname}->getConfig('keywords').', '. implode(', ', \$keywords));
unset(\$keywords);
// description
{$moduleDirname}MetaDescription(_MA_TEST1_TEST_DESC);\n
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
        $table = $this->getTable();
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $tableId = $table->getVar('table_id');
        $tableMid = $table->getVar('table_mid');
        $tableName = $table->getVar('table_name');
		$tableSoleName = $table->getVar('table_solename');
        $fields = $this->tdmcfile->getTableFields($tableMid, $tableId);
        $language = $this->getLanguage($moduleDirname, 'MA');
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getUserVisit($moduleDirname, $tableName, $tableSoleName, $fields, $language);

        $this->tdmcfile->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}
