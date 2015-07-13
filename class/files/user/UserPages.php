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
 * @version         $Id: pages.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class UserPages.
 */
class UserPages extends TDMCreateFile
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
        $this->phpcode = TDMCreatePhpCode::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return UserPages
     */
    public static function &getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * @public function write
     *
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
    *  @private function getUserPages
    *  @param string $moduleDirname
    *  @param string $language
    */
    /**
     * @param $moduleDirname
     * @param $language
     *
     * @return string
     */
    private function getUserPages($moduleDirname, $language)
    {
        $table = $this->getTable();
        $tableName = $table->getVar('table_name');
        $tableSoleName = $table->getVar('table_solename');
        $tableFieldname = $table->getVar('table_fieldname');
        $stuModuleDirname = strtoupper($moduleDirname);
        $stuTableName = strtoupper($tableName);
        $stuTableSoleName = strtoupper($tableSoleName);
        $lcfTableName = lcfirst($tableName);
        $ucfTableName = ucfirst($tableName);
        $ret = <<<EOT
include  __DIR__ . '/header.php';
//
\$GLOBALS['xoopsOption']['template_main'] = '{$moduleDirname}_{$tableName}.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';
\$start = XoopsRequest::getInt('start', 0);
\$limit = XoopsRequest::getInt('limit', \${$moduleDirname}->getConfig('userpager'));
// Define Stylesheet
\$xoTheme->addStylesheet( \$style );
//
\$GLOBALS['xoopsTpl']->assign('xoops_icons32_url', XOOPS_ICONS32_URL);
\$GLOBALS['xoopsTpl']->assign('{$moduleDirname}_upload_url', {$stuModuleDirname}_UPLOAD_URL);
//
\${$lcfTableName}Count = \${$lcfTableName}Handler->getCount{$ucfTableName}();
\${$lcfTableName}All = \${$lcfTableName}Handler->getAll{$ucfTableName}(\$start, \$limit);
\$keywords = array();
if (\${$lcfTableName}Count > 0) {
    // Get All {$ucfTableName}
	foreach (array_keys(\${$lcfTableName}All) as \$i)
    {
		\${$tableSoleName} = \${$tableName}All[\$i]->getValues{$ucfTableName}();
        \$GLOBALS['xoopsTpl']->append('{$tableName}_list', \${$tableSoleName});
        unset(\${$tableSoleName});\n
EOT;
        // Fields
        $fields = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName; // fieldMain = fields parameters main field
            }
        }
        $ret .= <<<EOT
        \$keywords[] = \${$lcfTableName}All[\$i]->getVar('{$fieldMain}');
    }
    // Display Navigation
    if (\${$lcfTableName}Count > \$limit) {
        include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        \$nav = new XoopsPageNav(\${$lcfTableName}Count, \$limit, \$start, 'start');
        \$GLOBALS['xoopsTpl']->assign('pagenav', \$nav->renderNav(4));
    }
	\$GLOBALS['xoopsTpl']->assign('type', \${$moduleDirname}->getConfig('table_type'));
	\$GLOBALS['xoopsTpl']->assign('divideby', \${$moduleDirname}->getConfig('divideby'));
	\$GLOBALS['xoopsTpl']->assign('numb_col', \${$moduleDirname}->getConfig('numb_col'));
}
// Breadcrumbs
\$xoBreadcrumbs[] = array('title' => {$language}{$stuTableName}); //'link' => {$stuModuleDirname}_URL . '/{$tableName}.php',
// keywords
{$moduleDirname}MetaKeywords(\${$moduleDirname}->getConfig('keywords').', '. implode(', ', \$keywords));
unset(\$keywords);
// description
{$moduleDirname}MetaDescription({$language}{$stuTableSoleName}_DESC);
//
\$GLOBALS['xoopsTpl']->assign('xoops_mpageurl', {$stuModuleDirname}_URL.'/{$tableName}.php');
//
include  __DIR__ . '/footer.php';
EOT;

        return $ret;
    }

    /*
    *  @public function render
    *  @param null
    */
    /**
     * @param $filename
     *
     * @return bool|string
     */
    public function renderFile()
    {
        $module = $this->getModule();
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language = $this->getLanguage($moduleDirname, 'MA');
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getUserPages($moduleDirname, $language);
        //
        $this->tdmcfile->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}
