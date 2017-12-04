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
 * @version         $Id: LanguageModinfo.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class LanguageModinfo.
 */
class LanguageModinfo extends TDMCreateFile
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
     * @return LanguageModinfo
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
     *
     * @param $module
     * @param $table
     * @param $filename
     *
     * @return string
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /**
     * @private function getLanguageMain
     *
     * @param $language
     * @param $module
     *
     * @return string
     */
    private function getLanguageMain($language, $module)
    {
        $df = LanguageDefines::getInstance();
        $ret = $df->getAboveHeadDefines('Admin Main');
        $ret .= $df->getDefine($language, 'NAME', "{$module->getVar('mod_name')}");
        $ret .= $df->getDefine($language, 'DESC', "{$module->getVar('mod_description')}");

        return $ret;
    }

    /**
     * @private function getLanguageMenu
     *
     * @param $module
     * @param $language
     *
     * @return string
     */
    private function getLanguageMenu($module, $language)
    {
        $df = LanguageDefines::getInstance();
        $tables = $this->getTableTables($module->getVar('mod_id'), 'table_order');
        $menu = 1;
        $ret = $df->getAboveHeadDefines('Admin Menu');
        $ret .= $df->getDefine($language, "ADMENU{$menu}", 'Dashboard');
        $tablePermissions = [];
        foreach (array_keys($tables) as $i) {
            ++$menu;
            $tablePermissions[] = $tables[$i]->getVar('table_permissions');
            $ucfTableName = ucfirst($tables[$i]->getVar('table_name'));
            $ret .= $df->getDefine($language, "ADMENU{$menu}", "{$ucfTableName}");
        }
        if (in_array(1, $tablePermissions)) {
            ++$menu;
            $ret .= $df->getDefine($language, "ADMENU{$menu}", 'Permissions');
        }
        $ret .= $df->getDefine($language, 'ABOUT', 'About');
        unset($menu, $tablePermissions);

        return $ret;
    }

    /**
    *  @private function getLanguageAdmin
    *  @param $language
     *
     * @return string
     */
    private function getLanguageAdmin($language)
    {
        $df = LanguageDefines::getInstance();
        $ret = $df->getAboveHeadDefines('Admin Nav');
        $ret .= $df->getDefine($language, 'ADMIN_PAGER', 'Admin pager');
        $ret .= $df->getDefine($language, 'ADMIN_PAGER_DESC', 'Admin per page list');

        return $ret;
    }

    /**
    *  @private function getLanguageSubmenu
    *  @param $language
    *  @param array $tables
     *
     * @return string
     */
    private function getLanguageSubmenu($language, $tables)
    {
        $df = LanguageDefines::getInstance();
        $ret = $df->getAboveDefines('Submenu');
        $i = 1;
        $tableSubmit = [];
        foreach (array_keys($tables) as $t) {
            $tableName = $tables[$t]->getVar('table_name');
            $tableSubmit[] = $tables[$t]->getVar('table_submit');
            if (1 == $tables[$t]->getVar('table_submenu')) {
                $ret .= $df->getDefine($language, "SMNAME{$i}", "{$tableName}");
            }
            ++$i;
        }
        if (in_array(1, $tableSubmit)) {
            $ret .= $df->getDefine($language, "SMNAME{$i}", 'Submit');
        }
        unset($i, $tableSubmit);

        return $ret;
    }

    /**
    *  @private function getLanguageBlocks
    *  @param $language
    *  @param array $tables
     *
     * @return string
     */
    private function getLanguageBlocks($tables, $language)
    {
        $df = LanguageDefines::getInstance();
        $ret = $df->getAboveDefines('Blocks');
        foreach (array_keys($tables) as $i) {
            $tableName = $tables[$i]->getVar('table_name');
            $stuTableName = strtoupper($tableName);
            $tableSoleName = $tables[$i]->getVar('table_solename');
            $stuTableSoleName = strtoupper($tableSoleName);
            $ucfTableName = ucfirst($tableName);
            $ucfTableSoleName = ucfirst($stuTableSoleName);

            $ret .= $df->getDefine($language, "{$stuTableName}_BLOCK", "{$ucfTableName} block");
            $ret .= $df->getDefine($language, "{$stuTableName}_BLOCK_DESC", "{$ucfTableName} block description");
            if ($tables[$i]->getVar('table_category') == 1) {
                $ret .= $df->getDefine($language, "{$stuTableName}_BLOCK_{$stuTableSoleName}", "{$ucfTableName} block {$ucfTableSoleName}");
                $ret .= $df->getDefine($language, "{$stuTableName}_BLOCK_{$stuTableSoleName}_DESC", "{$ucfTableName} block {$ucfTableSoleName} description");
            } else {
                $ret .= $df->getDefine($language, "{$stuTableName}_BLOCK_{$stuTableSoleName}", "{$ucfTableName} block  {$ucfTableSoleName}");
                $ret .= $df->getDefine($language, "{$stuTableName}_BLOCK_{$stuTableSoleName}_DESC", "{$ucfTableName} block  {$ucfTableSoleName} description");
                $ret .= $df->getDefine($language, "{$stuTableName}_BLOCK_LAST", "{$ucfTableName} block last");
                $ret .= $df->getDefine($language, "{$stuTableName}_BLOCK_LAST_DESC", "{$ucfTableName} block last description");
                $ret .= $df->getDefine($language, "{$stuTableName}_BLOCK_NEW", "{$ucfTableName} block new");
                $ret .= $df->getDefine($language, "{$stuTableName}_BLOCK_NEW_DESC", "{$ucfTableName} block new description");
                $ret .= $df->getDefine($language, "{$stuTableName}_BLOCK_HITS", "{$ucfTableName} block hits");
                $ret .= $df->getDefine($language, "{$stuTableName}_BLOCK_HITS_DESC", "{$ucfTableName} block hits description");
                $ret .= $df->getDefine($language, "{$stuTableName}_BLOCK_TOP", "{$ucfTableName} block top");
                $ret .= $df->getDefine($language, "{$stuTableName}_BLOCK_TOP_DESC", "{$ucfTableName} block top description");
                $ret .= $df->getDefine($language, "{$stuTableName}_BLOCK_RANDOM", "{$ucfTableName} block random");
                $ret .= $df->getDefine($language, "{$stuTableName}_BLOCK_RANDOM_DESC", "{$ucfTableName} block random description");
            }
        }

        return $ret;
    }

    /**
    *  @private function getLanguageUser
    *  @param $language
     *
     * @return string
     */
    private function getLanguageUser($language)
    {
        $df = LanguageDefines::getInstance();
        $ret = $df->getAboveDefines('User');
        $ret .= $df->getDefine($language, 'USER_PAGER', 'User pager');
        $ret .= $df->getDefine($language, 'USER_PAGER_DESC', 'User per page list');

        return $ret;
    }

    /**
    *  @private function getLanguageConfig
    *  @param $language
    *  @param $table
     *
     * @return string
     */
    private function getLanguageConfig($language, $table)
    {
        $df = LanguageDefines::getInstance();
        $ret = $df->getAboveDefines('Config');
        if (is_object($table) && $table->getVar('table_image') != '') {
            $fields = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
            $fieldElement = [];
            foreach (array_keys($fields) as $f) {
                $fieldElement[] = $fields[$f]->getVar('field_element');
                if (in_array(4, $fieldElement)) {
                    $fieldName = $fields[$f]->getVar('field_name');
                    $rpFieldName = $this->getRightString($fieldName);
                    $ucfFieldName = ucfirst($rpFieldName);
                    $stuFieldName = strtoupper($rpFieldName);
                    $ret .= $df->getDefine($language, 'EDITOR_'.$stuFieldName, 'Editor');
                    $ret .= $df->getDefine($language, 'EDITOR_'.$stuFieldName.'_DESC', 'Select the Editor '.$ucfFieldName.' to use');
                }
            }
            unset($fieldElement);
        }
        $ret .= $df->getDefine($language, 'KEYWORDS', 'Keywords');
        $ret .= $df->getDefine($language, 'KEYWORDS_DESC', 'Insert here the keywords (separate by comma)');
        if (is_object($table)) {
            /*if ($table->getVar('table_permissions') != 0) {
                $ret .= $df->getDefine($language, "GROUPS", "Groups");
                $ret .= $df->getDefine($language, "GROUPS_DESC", "Groups to have permissions");
                $ret .= $df->getDefine($language, "ADMIN_GROUPS", "Admin Groups");
                $ret .= $df->getDefine($language, "ADMIN_GROUPS_DESC", "Admin Groups to have permissions access");
            }*/
            if ($table->getVar('table_image') != '') {
                $ret .= $df->getDefine($language, 'MAXSIZE', 'Max size');
                $ret .= $df->getDefine($language, 'MAXSIZE_DESC', 'Set a number of max size uploads files in byte');
                $ret .= $df->getDefine($language, 'MIMETYPES', 'Mime Types');
                $ret .= $df->getDefine($language, 'MIMETYPES_DESC', 'Set the mime types selected');
            }
            if ($table->getVar('table_tag') != 0) {
                $ret .= $df->getDefine($language, 'USE_TAG', 'Use TAG');
                $ret .= $df->getDefine($language, 'USE_TAG_DESC', 'If you use tag module, check this option to yes');
            }
        }
        $getDefinesConf = [
            'NUMB_COL'   => 'Number Columns', 'NUMB_COL_DESC' => 'Number Columns to View.', 'DIVIDEBY' => 'Divide By', 'DIVIDEBY_DESC' => 'Divide by columns number.',
            'TABLE_TYPE' => 'Table Type', 'TABLE_TYPE_DESC' => 'Table Type is the bootstrap html table.', 'PANEL_TYPE' => 'Panel Type', 'PANEL_TYPE_DESC' => 'Panel Type is the bootstrap html div.', 'IDPAYPAL' => 'Paypal ID', 'IDPAYPAL_DESC' => 'Insert here your PayPal ID for donactions.', 'ADVERTISE' => 'Advertisement Code', 'ADVERTISE_DESC' => 'Insert here the advertisement code', 'MAINTAINEDBY' => 'Maintained By', 'MAINTAINEDBY_DESC' => 'Allow url of support site or community', 'BOOKMARKS' => 'Social Bookmarks', 'BOOKMARKS_DESC' => 'Show Social Bookmarks in the single page', 'FACEBOOK_COMMENTS' => 'Facebook comments', 'FACEBOOK_COMMENTS_DESC' => 'Allow Facebook comments in the single page', 'DISQUS_COMMENTS' => 'Disqus comments', 'DISQUS_COMMENTS_DESC' => 'Allow Disqus comments in the single page',
        ];
        foreach ($getDefinesConf as $defc => $descc) {
            $ret .= $df->getDefine($language, $defc, $descc);
        }

        return $ret;
    }

    /**
    *  @private function getLanguageNotifications
    *  @param $language
    *  @param mixed $tableSoleName
     *
     * @return string
     */
    private function getLanguageNotifications($language, $tableSoleName)
    {
        $df = LanguageDefines::getInstance();
        $ret = $df->getAboveDefines('Notifications');
        $stuTableSoleName = strtoupper($tableSoleName);
        $ucfTableSoleName = ucfirst($tableSoleName);
        $getDefinesNotif = [
            'GLOBAL_NOTIFY'        => 'Global notify', 'GLOBAL_NOTIFY_DESC' => 'Global notify desc', 'CATEGORY_NOTIFY' => 'Category notify',
            'CATEGORY_NOTIFY_DESC' => 'Category notify desc', $stuTableSoleName.'_NOTIFY' => $ucfTableSoleName.' notify', $stuTableSoleName.'_NOTIFY_DESC' => $ucfTableSoleName.' notify desc', 'GLOBAL_NEWCATEGORY_NOTIFY' => 'Global newcategory notify', 'GLOBAL_NEWCATEGORY_NOTIFY_CAPTION' => 'Global newcategory notify caption', 'GLOBAL_NEWCATEGORY_NOTIFY_DESC' => 'Global newcategory notify desc', 'GLOBAL_NEWCATEGORY_NOTIFY_SUBJECT' => 'Global newcategory notify subject', 'GLOBAL_'.$stuTableSoleName.'MODIFY_NOTIFY' => 'Global '.$tableSoleName.'modify notify', 'GLOBAL_'.$stuTableSoleName.'MODIFY_NOTIFY_CAPTION' => 'Global '.$tableSoleName.' modify notify caption', 'GLOBAL_'.$stuTableSoleName.'MODIFY_NOTIFY_DESC' => 'Global '.$tableSoleName.'modify notify desc', 'GLOBAL_'.$stuTableSoleName.'MODIFY_NOTIFY_SUBJECT' => 'Global '.$tableSoleName.' modify notify subject', 'GLOBAL_'.$stuTableSoleName.'BROKEN_NOTIFY' => 'Global '.$tableSoleName.' broken notify', 'GLOBAL_'.$stuTableSoleName.'BROKEN_NOTIFY_CAPTION' => 'Global '.$tableSoleName.'broken notify caption', 'GLOBAL_'.$stuTableSoleName.'BROKEN_NOTIFY_DESC' => 'Global '.$tableSoleName.'broken notify desc', 'GLOBAL_'.$stuTableSoleName.'BROKEN_NOTIFY_SUBJECT' => 'Global '.$tableSoleName.'broken notify subject', 'GLOBAL_'.$stuTableSoleName.'SUBMIT_NOTIFY' => 'Global '.$tableSoleName.' submit notify', 'GLOBAL_'.$stuTableSoleName.'SUBMIT_NOTIFY_CAPTION' => 'Global '.$tableSoleName.' submit notify caption', 'GLOBAL_'.$stuTableSoleName.'SUBMIT_NOTIFY_DESC' => 'Global '.$tableSoleName.'submit notify desc', 'GLOBAL_'.$stuTableSoleName.'SUBMIT_NOTIFY_SUBJECT' => 'Global '.$tableSoleName.'submit notify subject', 'GLOBAL_NEW'.$stuTableSoleName.'_NOTIFY' => 'Global new'.$tableSoleName.' notify', 'GLOBAL_NEW'.$stuTableSoleName.'_NOTIFY_CAPTION' => 'Global new'.$tableSoleName.' notify caption', 'GLOBAL_NEW'.$stuTableSoleName.'_NOTIFY_DESC' => 'Global new'.$tableSoleName.' notify desc', 'GLOBAL_NEW'.$stuTableSoleName.'_NOTIFY_SUBJECT' => 'Global new'.$tableSoleName.' notify subject', 'CATEGORY_'.$stuTableSoleName.'SUBMIT_NOTIFY' => 'Category '.$tableSoleName.'submit notify', 'CATEGORY_'.$stuTableSoleName.'SUBMIT_NOTIFY_CAPTION' => 'Category '.$tableSoleName.' submit notify caption', 'CATEGORY_'.$stuTableSoleName.'SUBMIT_NOTIFY_DESC' => 'Category '.$tableSoleName.' submit notify desc', 'CATEGORY_'.$stuTableSoleName.'SUBMIT_NOTIFY_SUBJECT' => 'Category '.$tableSoleName.' submit notify subject', 'CATEGORY_NEW'.$stuTableSoleName.'_NOTIFY' => 'Category new'.$tableSoleName.' notify', 'CATEGORY_NEW'.$stuTableSoleName.'_NOTIFY_CAPTION' => 'Category new'.$tableSoleName.' notify caption', 'CATEGORY_NEW'.$stuTableSoleName.'_NOTIFY_DESC' => 'Category new'.$tableSoleName.' notify desc', 'CATEGORY_NEW'.$stuTableSoleName.'_NOTIFY_SUBJECT' => 'Category new'.$tableSoleName.' notify subject', $stuTableSoleName.'_APPROVE_NOTIFY' => $ucfTableSoleName.' approve notify', $stuTableSoleName.'_APPROVE_NOTIFY_CAPTION' => $ucfTableSoleName.' approve notify caption', $stuTableSoleName.'_APPROVE_NOTIFY_DESC' => $ucfTableSoleName.' approve notify desc', $stuTableSoleName.'_APPROVE_NOTIFY_SUBJECT' => $ucfTableSoleName.' approve notify subject',
        ];
        foreach ($getDefinesNotif as $defn => $descn) {
            $ret .= $df->getDefine($language, $defn, $descn);
        }

        return $ret;
    }

    /**
    *  @private function getLanguagePermissionsGroups
    *  @param $language
     *
     * @return string
     */
    private function getLanguagePermissionsGroups($language)
    {
        $df = LanguageDefines::getInstance();
        $ret = $df->getAboveDefines('Permissions Groups');
        $ret .= $df->getDefine($language, 'GROUPS', 'Groups access');
        $ret .= $df->getDefine($language, 'GROUPS_DESC', 'Select general access permission for groups.');
        $ret .= $df->getDefine($language, 'ADMIN_GROUPS', 'Admin Group Permissions');
        $ret .= $df->getDefine($language, 'ADMIN_GROUPS_DESC', 'Which groups have access to tools and permissions page');

        return $ret;
    }

    /**
    *  @private function getFooter
    *  @param null
     * @return string
     */
    private function getLanguageFooter()
    {
        $df = LanguageDefines::getInstance();
        $ret = $df->getBelowDefines('End');

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
        $table = $this->getTable();
        $tables = $this->getTableTables($module->getVar('mod_id'));
        $tableAdmin = [];
        $tableUser = [];
        $tableSubmenu = [];
        $tableBlocks = [];
        $tableNotifications = [];
        $tablePermissions = [];
        foreach (array_keys($tables) as $t) {
            $tableSoleName = $tables[$t]->getVar('table_solename');
            $tableAdmin[] = $tables[$t]->getVar('table_admin');
            $tableUser[] = $tables[$t]->getVar('table_user');
            $tableSubmenu[] = $tables[$t]->getVar('table_submenu');
            $tableBlocks[] = $tables[$t]->getVar('table_blocks');
            $tableNotifications[] = $tables[$t]->getVar('table_notifications');
            $tablePermissions[] = $tables[$t]->getVar('table_permissions');
        }
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language = $this->getLanguage($moduleDirname, 'MI');
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getLanguageMain($language, $module);
        $content .= $this->getLanguageMenu($module, $language);
        if (in_array(1, $tableAdmin)) {
            $content .= $this->getLanguageAdmin($language);
        }
        if (in_array(1, $tableUser)) {
            $content .= $this->getLanguageUser($language);
        }
        if (in_array(1, $tableSubmenu)) {
            $content .= $this->getLanguageSubmenu($language, $tables);
        }
        //if (in_array(1, $tableBlocks)) {
            $content .= $this->getLanguageBlocks($tables, $language);
        //}
        $content .= $this->getLanguageConfig($language, $table);
        if (in_array(1, $tableNotifications)) {
            $content .= $this->getLanguageNotifications($language, $tableSoleName);
        }
        if (in_array(1, $tablePermissions)) {
            $content .= $this->getLanguagePermissionsGroups($language);
        }
        $content .= $this->getLanguageFooter();

        $this->create($moduleDirname, 'language/'.$GLOBALS['xoopsConfig']['language'], $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
