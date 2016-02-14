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
 * @version         $Id: LanguageModinfo.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class LanguageModinfo.
 */
class LanguageModinfo extends TDMCreateFile
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
        $this->defines = LanguageDefines::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return LanguageModinfo
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
    *  @param mixed $tables
    *  @param string $filename
    */
    /**
     * @param $module
     * @param $table
     * @param $tables
     * @param $filename
     */
    public function write($module, $table, $tables, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setTables($tables);
        $this->setFileName($filename);
    }

    /*
    *  @private function getLanguageMain
    *  @param string $language
    *  @param string $module
    */
    /**
     * @param $language
     * @param $module
     *
     * @return string
     */
    private function getLanguageMain($language, $module)
    {
        $ret = $this->defines->getAboveHeadDefines('Admin Main');
        $ret .= $this->defines->getDefine($language, 'NAME', "{$module->getVar('mod_name')}");
        $ret .= $this->defines->getDefine($language, 'DESC', "{$module->getVar('mod_description')}");

        return $ret;
    }

    /*
    *  @private function getLanguageMenu
    *  @param string $language
    *  @param array $table
    */
    /**
     * @param $language
     * @param $table
     *
     * @return string
     */
    private function getLanguageMenu($module, $language, $table)
    {
        $tables = $this->getTableTables($module->getVar('mod_id'), 'table_order');
        $menu = 1;
        $ret = $this->defines->getAboveHeadDefines('Admin Menu');
        $ret .= $this->defines->getDefine($language, "ADMENU{$menu}", 'Dashboard');
        foreach (array_keys($tables) as $i) {
            ++$menu;
            $tablePermissions[] = $tables[$i]->getVar('table_permissions');
            $ucfTableName = ucfirst($tables[$i]->getVar('table_name'));
            $ret .= $this->defines->getDefine($language, "ADMENU{$menu}", "{$ucfTableName}");
        }
        if (in_array(1, $tablePermissions)) {
            ++$menu;
            $ret .= $this->defines->getDefine($language, "ADMENU{$menu}", 'Permissions');
        }
        $ret .= $this->defines->getDefine($language, 'ABOUT', 'About');
        unset($menu);

        return $ret;
    }

    /*
    *  @private function getLanguageAdmin
    *  @param string $language
    */
    /**
     * @param $language
     *
     * @return string
     */
    private function getLanguageAdmin($language)
    {
        $ret = $this->defines->getAboveHeadDefines('Admin Nav');
        $ret .= $this->defines->getDefine($language, 'ADMIN_PAGER', 'Admin pager');
        $ret .= $this->defines->getDefine($language, 'ADMIN_PAGER_DESC', 'Admin per page list');

        return $ret;
    }

    /*
    *  @private function getLanguageSubmenu
    *  @param string $language
    *  @param array $tables
    */
    /**
     * @param $language
     * @param $tables
     *
     * @return string
     */
    private function getLanguageSubmenu($language, $tables)
    {
        $ret = $this->defines->getAboveDefines('Submenu');
        $i = 1;
        foreach (array_keys($tables) as $t) {
            $tableName = $tables[$t]->getVar('table_name');
            $tableSubmit[] = $tables[$t]->getVar('table_submit');
            if (1 == $tables[$t]->getVar('table_submenu')) {
                $ret .= $this->defines->getDefine($language, "SMNAME{$i}", "{$tableName}");
            }
            ++$i;
        }
        if (in_array(1, $tableSubmit)) {
            $ret .= $this->defines->getDefine($language, "SMNAME{$i}", 'Submit');
        }
        unset($i);

        return $ret;
    }

    /*
    *  @private function getLanguageBlocks
    *  @param string $language
    *  @param array $tables
    */
    /**
     * @param $language
     * @param $tables
     *
     * @return string
     */
    private function getLanguageBlocks($tables, $language)
    {
        $ret = $this->defines->getAboveDefines('Blocks');
        foreach (array_keys($tables) as $i) {
            $tableName = $tables[$i]->getVar('table_name');
            $stuTableName = strtoupper($tableName);
            $tableSoleName = $tables[$i]->getVar('table_solename');
            $stuTableSoleName = strtoupper($tableSoleName);
            $ucfTableName = ucfirst($tableName);
            $ucfTableSoleName = ucfirst($stuTableSoleName);
            if (1 == $tables[$i]->getVar('table_blocks')) {
                $ret .= $this->defines->getDefine($language, "{$stuTableName}_BLOCK", "{$ucfTableName} block");
                $ret .= $this->defines->getDefine($language, "{$stuTableName}_BLOCK_DESC", "{$ucfTableName} block description");
                if ($tables[$i]->getVar('table_category') == 1) {
                    $ret .= $this->defines->getDefine($language, "{$stuTableName}_BLOCK_{$stuTableSoleName}", "{$ucfTableName} block {$ucfTableSoleName}");
                    $ret .= $this->defines->getDefine($language, "{$stuTableName}_BLOCK_{$stuTableSoleName}_DESC", "{$ucfTableName} block {$ucfTableSoleName} description");
                } else {
                    $ret .= $this->defines->getDefine($language, "{$stuTableName}_BLOCK_{$stuTableSoleName}", "{$ucfTableName} block  {$ucfTableSoleName}");
                    $ret .= $this->defines->getDefine($language, "{$stuTableName}_BLOCK_{$stuTableSoleName}_DESC", "{$ucfTableName} block  {$ucfTableSoleName} description");
                    $ret .= $this->defines->getDefine($language, "{$stuTableName}_BLOCK_LAST", "{$ucfTableName} block last");
                    $ret .= $this->defines->getDefine($language, "{$stuTableName}_BLOCK_LAST_DESC", "{$ucfTableName} block last description");
                    $ret .= $this->defines->getDefine($language, "{$stuTableName}_BLOCK_NEW", "{$ucfTableName} block new");
                    $ret .= $this->defines->getDefine($language, "{$stuTableName}_BLOCK_NEW_DESC", "{$ucfTableName} block new description");
                    $ret .= $this->defines->getDefine($language, "{$stuTableName}_BLOCK_HITS", "{$ucfTableName} block hits");
                    $ret .= $this->defines->getDefine($language, "{$stuTableName}_BLOCK_HITS_DESC", "{$ucfTableName} block hits description");
                    $ret .= $this->defines->getDefine($language, "{$stuTableName}_BLOCK_TOP", "{$ucfTableName} block top");
                    $ret .= $this->defines->getDefine($language, "{$stuTableName}_BLOCK_TOP_DESC", "{$ucfTableName} block top description");
                    $ret .= $this->defines->getDefine($language, "{$stuTableName}_BLOCK_RANDOM", "{$ucfTableName} block random");
                    $ret .= $this->defines->getDefine($language, "{$stuTableName}_BLOCK_RANDOM_DESC", "{$ucfTableName} block random description");
                }
            }
        }

        return $ret;
    }

    /*
    *  @private function getLanguageUser
    *  @param string $language
    */
    /**
     * @param $language
     *
     * @return string
     */
    private function getLanguageUser($language)
    {
        $ret = $this->defines->getAboveDefines('User');
        $ret .= $this->defines->getDefine($language, 'USER_PAGER', 'User pager');
        $ret .= $this->defines->getDefine($language, 'USER_PAGER_DESC', 'User per page list');

        return $ret;
    }

    /*
    *  @private function getLanguageConfig
    *  @param string $language
    *  @param string $table
    */
    /**
     * @param $language
     * @param $table
     *
     * @return string
     */
    private function getLanguageConfig($language, $table)
    {
        $ret = $this->defines->getAboveDefines('Config');
        if (is_object($table) && $table->getVar('table_image') != '') {
            $ret .= $this->defines->getDefine($language, 'EDITOR', 'Editor');
            $ret .= $this->defines->getDefine($language, 'EDITOR_DESC', 'Select the Editor to use');
        }
        $ret .= $this->defines->getDefine($language, 'KEYWORDS', 'Keywords');
        $ret .= $this->defines->getDefine($language, 'KEYWORDS_DESC', 'Insert here the keywords (separate by comma)');
        if (is_object($table)) {
            /*if ($table->getVar('table_permissions') != 0) {
                $ret .= $this->defines->getDefine($language, "GROUPS", "Groups");
                $ret .= $this->defines->getDefine($language, "GROUPS_DESC", "Groups to have permissions");
                $ret .= $this->defines->getDefine($language, "ADMIN_GROUPS", "Admin Groups");
                $ret .= $this->defines->getDefine($language, "ADMIN_GROUPS_DESC", "Admin Groups to have permissions access");
            }*/
            if ($table->getVar('table_image') != '') {
                $ret .= $this->defines->getDefine($language, 'MAXSIZE', 'Max size');
                $ret .= $this->defines->getDefine($language, 'MAXSIZE_DESC', 'Set a number of max size uploads file in byte');
                $ret .= $this->defines->getDefine($language, 'MIMETYPES', 'Mime Types');
                $ret .= $this->defines->getDefine($language, 'MIMETYPES_DESC', 'Set the mime types selected');
            }
            if ($table->getVar('table_tag') != 0) {
                $ret .= $this->defines->getDefine($language, 'USE_TAG', 'Use TAG');
                $ret .= $this->defines->getDefine($language, 'USE_TAG_DESC', 'If you use tag module, check this option to yes');
            }
        }
        $getDefinesConf = array('NUMB_COL' => 'Number Columns', 'NUMB_COL_DESC' => 'Number Columns to View.', 'DIVIDEBY' => 'Divide By', 'DIVIDEBY_DESC' => 'Divide by columns number.',
                                'TABLE_TYPE' => 'Table Type', 'TABLE_TYPE_DESC' => 'Table Type is the bootstrap html table.', 'PANEL_TYPE' => 'Panel Type', 'PANEL_TYPE_DESC' => 'Panel Type is the bootstrap html div.', 'IDPAYPAL' => 'Paypal ID', 'IDPAYPAL_DESC' => 'Insert here your PayPal ID for donactions.', 'ADVERTISE' => 'Advertisement Code', 'ADVERTISE_DESC' => 'Insert here the advertisement code', 'MAINTAINEDBY' => 'Maintained By', 'MAINTAINEDBY_DESC' => 'Allow url of support site or community', 'BOOKMARKS' => 'Social Bookmarks', 'BOOKMARKS_DESC' => 'Show Social Bookmarks in the single page', 'FACEBOOK_COMMENTS' => 'Facebook comments', 'FACEBOOK_COMMENTS_DESC' => 'Allow Facebook comments in the single page', 'DISQUS_COMMENTS' => 'Disqus comments', 'DISQUS_COMMENTS_DESC' => 'Allow Disqus comments in the single page', );
        foreach ($getDefinesConf as $defc => $descc) {
            $ret .= $this->defines->getDefine($language, $defc, $descc);
        }

        return $ret;
    }

    /*
    *  @private function getLanguageNotifications
    *  @param string $language
    *  @param mixed $table
    */
    /**
     * @param $language
     *
     * @return string
     */
    private function getLanguageNotifications($language)
    {
        $ret = $this->defines->getAboveDefines('Notifications');
        $getDefinesNotif = array('GLOBAL_NOTIFY' => 'Global notify', 'GLOBAL_NOTIFY_DESC' => 'Global notify desc', 'CATEGORY_NOTIFY' => 'Category notify',
                                'CATEGORY_NOTIFY_DESC' => 'Category notify desc', 'FILE_NOTIFY' => 'File notify', 'FILE_NOTIFY_DESC' => 'File notify desc', 'GLOBAL_NEWCATEGORY_NOTIFY' => 'Global newcategory notify', 'GLOBAL_NEWCATEGORY_NOTIFY_CAPTION' => 'Global newcategory notify caption', 'GLOBAL_NEWCATEGORY_NOTIFY_DESC' => 'Global newcategory notify desc', 'GLOBAL_NEWCATEGORY_NOTIFY_SUBJECT' => 'Global newcategory notify subject', 'GLOBAL_FILEMODIFY_NOTIFY' => 'Global filemodify notify', 'GLOBAL_FILEMODIFY_NOTIFY_CAPTION' => 'Global filemodify notify caption', 'GLOBAL_FILEMODIFY_NOTIFY_DESC' => 'Global filemodify notify desc', 'GLOBAL_FILEMODIFY_NOTIFY_SUBJECT' => 'Global filemodify notify subject', 'GLOBAL_FILEBROKEN_NOTIFY' => 'Global filebroken notify', 'GLOBAL_FILEBROKEN_NOTIFY_CAPTION' => 'Global filebroken notify caption', 'GLOBAL_FILEBROKEN_NOTIFY_DESC' => 'Global filebroken notify desc', 'GLOBAL_FILEBROKEN_NOTIFY_SUBJECT' => 'Global filebroken notify subject', 'GLOBAL_FILESUBMIT_NOTIFY' => 'Global filesubmit notify', 'GLOBAL_FILESUBMIT_NOTIFY_CAPTION' => 'Global filesubmit notify caption', 'GLOBAL_FILESUBMIT_NOTIFY_DESC' => 'Global filesubmit notify desc', 'GLOBAL_FILESUBMIT_NOTIFY_SUBJECT' => 'Global filesubmit notify subject', 'GLOBAL_NEWFILE_NOTIFY' => 'Global newfile notify', 'GLOBAL_NEWFILE_NOTIFY_CAPTION' => 'Global newfile notify caption', 'GLOBAL_NEWFILE_NOTIFY_DESC' => 'Global newfile notify desc', 'GLOBAL_NEWFILE_NOTIFY_SUBJECT' => 'Global newfile notify subject', 'CATEGORY_FILESUBMIT_NOTIFY' => 'Category filesubmit notify', 'CATEGORY_FILESUBMIT_NOTIFY_CAPTION' => 'Category filesubmit notify caption', 'CATEGORY_FILESUBMIT_NOTIFY_DESC' => 'Category filesubmit notify desc', 'CATEGORY_FILESUBMIT_NOTIFY_SUBJECT' => 'Category filesubmit notify subject', 'CATEGORY_NEWFILE_NOTIFY' => 'Category newfile notify', 'CATEGORY_NEWFILE_NOTIFY_CAPTION' => 'Category newfile notify caption', 'CATEGORY_NEWFILE_NOTIFY_DESC' => 'Category newfile notify desc', 'CATEGORY_NEWFILE_NOTIFY_SUBJECT' => 'Category newfile notify subject', 'FILE_APPROVE_NOTIFY' => 'File approve notify', 'FILE_APPROVE_NOTIFY_CAPTION' => 'File approve notify caption', 'FILE_APPROVE_NOTIFY_DESC' => 'File approve notify desc', 'FILE_APPROVE_NOTIFY_SUBJECT' => 'File approve notify subject', );
        foreach ($getDefinesNotif as $defn => $descn) {
            $ret .= $this->defines->getDefine($language, $defn, $descn);
        }

        return $ret;
    }

    /*
    *  @private function getLanguagePermissionsGroups
    *  @param string $language
    */
    /**
     * @param $language
     *
     * @return string
     */
    private function getLanguagePermissionsGroups($language)
    {
        $ret = $this->defines->getAboveDefines('Permissions Groups');
        $ret .= $this->defines->getDefine($language, 'GROUPS', 'Groups access');
        $ret .= $this->defines->getDefine($language, 'GROUPS_DESC', 'Select general access permission for groups.');
        $ret .= $this->defines->getDefine($language, 'ADMIN_GROUPS', 'Admin Group Permissions');
        $ret .= $this->defines->getDefine($language, 'ADMIN_GROUPS_DESC', 'Which groups have access to tools and permissions page');

        return $ret;
    }

    /*
    *  @private function getFooter
    *  @param null
    */
    /**
     * @return string
     */
    private function getLanguageFooter()
    {
        $ret = $this->defines->getBelowDefines('End');

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
        $tables = $this->getTables();
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language = $this->getLanguage($moduleDirname, 'MI');
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getLanguageMain($language, $module);
        $content .= $this->getLanguageMenu($module, $language, $table);
        if (1 == $table->getVar('table_admin')) {
            $content .= $this->getLanguageAdmin($language);
        }
        if (1 == $table->getVar('table_user')) {
            $content .= $this->getLanguageUser($language);
        }
        //if (1 == $table->getVar('table_submenu')) {
            $content .= $this->getLanguageSubmenu($language, $tables);
        //}
        if (1 == $table->getVar('table_blocks')) {
            $content .= $this->getLanguageBlocks($tables, $language);
        }
        $content .= $this->getLanguageConfig($language, $table);
        if (1 == $table->getVar('table_notifications')) {
            $content .= $this->getLanguageNotifications($language);
        }
        if (1 == $table->getVar('table_permissions')) {
            $content .= $this->getLanguagePermissionsGroups($language);
        }
        $content .= $this->getLanguageFooter();
        //
        $this->create($moduleDirname, 'language/'.$GLOBALS['xoopsConfig']['language'], $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
