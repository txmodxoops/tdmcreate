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
 * @version         $Id: user_index.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class UserIndex.
 */
class UserIndex extends UserObjects
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
     * @return UserIndex
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
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /**
     * @private function getTemplateHeaderFile
     *
     * @param $moduleDirname
     *
     * @return string
     */
    private function getTemplateHeaderFile($moduleDirname)
    {
        $ret = <<<EOT
\$GLOBALS['xoopsOption']['template_main'] = '{$moduleDirname}_index.tpl';
include_once XOOPS_ROOT_PATH.'/header.php';
// Define Stylesheet
\$GLOBALS['xoTheme']->addStylesheet( \$style );\n
EOT;

        return $ret;
    }

    /**
     * @private function getBodyCategoriesIndex
     *
     * @param $moduleDirname
     * @param $language
     *
     * @return string
     */
    private function getBodyCategoriesIndex($moduleDirname, $tableMid, $tableId, $tableName, $tableSoleName, $tableFieldname)
    {
        $ucfTableName = ucfirst($tableName);
        // Fields
        $fields = $this->getTableFields($tableMid, $tableId);

        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (0 == $f) {
                $fieldId = $fieldName; // fieldMain = fields parameters main field
            }
            $fieldParentId[] = $fields[$f]->getVar('field_parent');
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName; // fieldMain = fields parameters main field
            }
            if (1 == $fields[$f]->getVar('field_parent')) {
                $fieldParent = $fieldName; // fieldMain = fields parameters main field
            }
        }
        $ret = '';
        if (in_array(1, $fieldParentId)) {
            $ret .= <<<EOT
\${$tableName}Count = \${$tableName}Handler->getCount{$ucfTableName}();
// If there are {$tableName}
\$count = 1;
if (\${$tableName}Count > 0) {
	\${$tableName}All = \${$tableName}Handler->getAll{$ucfTableName}();
	include_once XOOPS_ROOT_PATH . '/class/tree.php';
	\$mytree = new XoopsObjectTree(\${$tableName}All, '{$fieldId}', '{$fieldParent}');
	foreach (array_keys(\${$tableName}All) as \${$tableFieldname})
	{
		\${$tableSoleName} = \${$tableName}All[\${$tableFieldname}]->getValues{$ucfTableName}();
		\$acount = array('count' => \$count);
		\${$tableSoleName} = array_merge(\${$tableSoleName}, \$acount);
		\$GLOBALS['xoopsTpl']->append('{$tableName}', \${$tableSoleName});
		unset(\${$tableSoleName});
	}
	\$GLOBALS['xoopsTpl']->assign('numb_col', \${$moduleDirname}->getConfig('numb_col'));
}
unset(\$count);\n
EOT;
        }

        return $ret;
    }

    /**
     * @private function getBodyPagesIndex
     *
     * @param $moduleDirname
     * @param $language
     *
     * @return string
     */
    private function getBodyPagesIndex($moduleDirname, $tableName, $tableSoleName, $tableFieldname, $language)
    {
        $stuModuleDirname = strtoupper($moduleDirname);
        $ucfTableName = ucfirst($tableName);
        $ret = <<<EOT
\${$tableName}Count = \${$tableName}Handler->getCount{$ucfTableName}();
\$count = 1;
if (\${$tableName}Count > 0) {
	\$start = XoopsRequest::getInt('start', 0);
	\$limit = XoopsRequest::getInt('limit', \${$moduleDirname}->getConfig('userpager'));
    \${$tableName}All = \${$tableName}Handler->getAll{$ucfTableName}(\$start, \$limit);
	// Get All {$ucfTableName}
	foreach(array_keys(\${$tableName}All) as \${$tableFieldname})
    {
		\${$tableSoleName} = \${$tableName}All[\${$tableFieldname}]->getValues{$ucfTableName}();
        \$acount = array('count' => \$count);
		\${$tableSoleName} = array_merge(\${$tableSoleName}, \$acount);
		\$GLOBALS['xoopsTpl']->append('{$tableName}', \${$tableSoleName});
        unset(\${$tableSoleName});
		++\$count;
    }
    // Display Navigation
    if (\${$tableName}Count > \$limit) {
        include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        \$nav = new XoopsPageNav(\${$tableName}Count, \$limit, \$start, 'start');
        \$GLOBALS['xoopsTpl']->assign('pagenav', \$nav->renderNav(4));
    }
	\$GLOBALS['xoopsTpl']->assign('divideby', \${$moduleDirname}->getConfig('divideby'));
	\$GLOBALS['xoopsTpl']->assign('lang_thereare', sprintf({$language}INDEX_THEREARE, \${$tableName}Count));
}
unset(\$count);
// Breadcrumbs
\$xoBreadcrumbs[] = array('title' => {$language}INDEX); //'link' => {$stuModuleDirname}_URL . '/index.php';
\$GLOBALS['xoopsTpl']->assign('panel_type', \${$moduleDirname}->getConfig('panel_type'));
\$GLOBALS['xoopsTpl']->assign('table_type', \${$moduleDirname}->getConfig('table_type'));\n
EOT;

        return $ret;
    }

    /**
     * @private function getDefaultFunctions
     *
     * @param $moduleDirname
     * @param $language
     *
     * @return string
     */
    private function getDefaultFunctions($moduleDirname, $language)
    {
        $stuModuleDirname = strtoupper($moduleDirname);
        $ret = <<<EOT
// keywords
{$moduleDirname}MetaKeywords(\${$moduleDirname}->getConfig('keywords'));
// description
{$moduleDirname}MetaDescription({$language}DESC);
//
\$GLOBALS['xoopsTpl']->assign('xoops_mpageurl', {$stuModuleDirname}_URL.'/index.php');
\$GLOBALS['xoopsTpl']->assign('xoops_icons32_url', XOOPS_ICONS32_URL);
\$GLOBALS['xoopsTpl']->assign('{$moduleDirname}_upload_url', {$stuModuleDirname}_UPLOAD_URL);\n
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
        $tables = $this->getTableTables($module->getVar('mod_id'), 'table_order');
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language = $this->getLanguage($moduleDirname, 'MA');
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getInclude();
        $content .= $this->getTemplateHeaderFile($moduleDirname);
        foreach (array_keys($tables) as $t) {
            $tableId = $tables[$t]->getVar('table_id');
            $tableMid = $tables[$t]->getVar('table_mid');
            $tableName = $tables[$t]->getVar('table_name');
            $tableSoleName = $tables[$t]->getVar('table_solename');
            $tableCategory = $tables[$t]->getVar('table_category');
            $tableFieldname = $tables[$t]->getVar('table_fieldname');
            $tableIndex = $tables[$t]->getVar('table_index');
            if ((1 == $tableCategory) && (1 == $tableIndex)) {
                $content .= $this->getBodyCategoriesIndex($moduleDirname, $tableMid, $tableId, $tableName, $tableSoleName, $tableFieldname);
            }
            if ((0 == $tableCategory) && (1 == $tableIndex)) {
                $content .= $this->getBodyPagesIndex($moduleDirname, $tableName, $tableSoleName, $tableFieldname, $language);
            }
        }
        $content .= $this->getDefaultFunctions($moduleDirname, $language);
        $content .= $this->getInclude('footer');
        //
        $this->tdmcfile->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}
