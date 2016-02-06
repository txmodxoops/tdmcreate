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
 * @version         $Id: UserXoopsVersion.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class UserXoopsVersion.
 */
class UserXoopsVersion extends TDMCreateFile
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
    * @var mixed
    */
    private $usercode = null;

    /*
    * @var array
    */
    private $keywords = array();

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
        $this->phpcode = TDMCreatePhpCode::getInstance();
        $this->xoopscode = TDMCreateXoopsCode::getInstance();
        $this->usercode = UserXoopsCode::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return UserXoopsVersion
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
    *  @param $module
    *  @param mixed $table
    *  @param mixed $tables
    *  @param $filename
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
        foreach (array_keys($tables) as $t) {
            $tableName = $tables[$t]->getVar('table_name');
            $this->setKeywords($tableName);
        }
    }

    /*
    *  @public function setKeywords
    *  @param mixed $keywords
    */
    /**
     * @param $keywords
     */
    public function setKeywords($keywords)
    {
        if (is_array($keywords)) {
            $this->keywords = $keywords;
        } else {
            $this->keywords[] = $keywords;
        }
    }

    /*
    *  @public function getKeywords
    *  @param null
    */
    /**
     * @return array
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /*
     * @private function getXoopsVersionHeader
     * @param $module
     * @param $language
     *
     * @return string
     */
    private function getXoopsVersionHeader($module, $language)
    {
        $date = date(_DBDATESTRING);
        $ret = $this->getSimpleString("defined('XOOPS_ROOT_PATH') || die('Restricted access');");
        $ret .= $this->getCommentLine();
        $ret .= $this->xoopscode->getXoopsCodeEqualsOperator('$dirname ', 'basename(__DIR__)');
        $ret .= $this->getHeaderComment('Informations');
        $ha = (1 == $module->getVar('mod_admin')) ? 1 : 0;
        $hm = (1 == $module->getVar('mod_user')) ? 1 : 0;

        $descriptions = array('name' => "{$language}NAME", 'version' => "{$module->getVar('mod_version')}", 'description' => "{$language}DESC",
                            'author' => "'{$module->getVar('mod_author')}'", 'author_mail' => "'{$module->getVar('mod_author_mail')}'", 'author_website_url' => "'{$module->getVar('mod_author_website_url')}'",
                            'author_website_name' => "'{$module->getVar('mod_author_website_name')}'",'credits' => "'{$module->getVar('mod_credits')}'",'license' => "'{$module->getVar('mod_license')}'",
                            'license_url' => "'www.gnu.org/licenses/gpl-2.0.html/'", 'help' => "'page=help'", 'release_info' => "'{$module->getVar('mod_release_info')}'",
                            'release_file' => "XOOPS_URL . '/modules/{$module->getVar('mod_dirname')}/docs/{$module->getVar('mod_release_file')}'", 'release_date' => "'{$date}'",
                            'manual' => "'{$module->getVar('mod_manual')}'", 'manual_file' => "XOOPS_URL . '/modules/{$module->getVar('mod_dirname')}/docs/{$module->getVar('mod_manual_file')}'",
                            'min_php' => "'{$module->getVar('mod_min_php')}'", 'min_xoops' => "'{$module->getVar('mod_min_xoops')}'", 'min_admin' => "'{$module->getVar('mod_min_admin')}'",
                            'min_db' => "array('mysql' => '{$module->getVar('mod_min_mysql')}', 'mysqli' => '{$module->getVar('mod_min_mysql')}')", 'image' => "'assets/images/{$module->getVar('mod_image')}'",
                            'dirname' => 'basename(__DIR__)', 'dirmoduleadmin' => "'Frameworks/moduleclasses/moduleadmin'", 'sysicons16' => "'../../Frameworks/moduleclasses/icons/16'",
                            'sysicons32' => "'../../Frameworks/moduleclasses/icons/32'", 'modicons16' => "'assets/icons/16'", 'modicons32' => "'assets/icons/32'",
                            'demo_site_url' => "'{$module->getVar('mod_demo_site_url')}'", 'demo_site_name' => "'{$module->getVar('mod_demo_site_name')}'", 'support_url' => "'{$module->getVar('mod_support_url')}'",
                            'support_name' => "'{$module->getVar('mod_support_name')}'", 'module_website_url' => "'{$module->getVar('mod_website_url')}'", 'module_website_name' => "'{$module->getVar('mod_website_name')}'", 'release' => "'{$module->getVar('mod_release')}'", 'module_status' => "'{$module->getVar('mod_status')}'",
                            'system_menu' => '1', 'hasAdmin' => $ha, 'hasMain' => $hm, 'adminindex' => "'admin/index.php'", 'adminmenu' => "'admin/menu.php'",
                            'onInstall' => "'include/install.php'", 'onUpdate' => "'include/update.php'", );

        $ret .= $this->usercode->getUserModVersion(1, $descriptions);

        return $ret;
    }

    /*
    *  @private function getXoopsVersionMySQL
    *  @param $moduleDirname
    *  @param $table
    */
    /**
     * @param $moduleDirname
     * @param $table
     *
     * @return string
     */
    private function getXoopsVersionMySQL($moduleDirname, $table, $tables)
    {
        $tableName = $table->getVar('table_name');
        $n = 1;
        $ret = '';
        if (!empty($tableName)) {
            $ret .= $this->getHeaderComment('Mysql');
            $ret .= $this->usercode->getUserModVersion(2, "'sql/mysql.sql'", 'sqlfile', "'mysql'");
            $ret .= $this->getCommentLine('Tables');

            foreach (array_keys($tables) as $t) {
                $ret .= $this->usercode->getUserModVersion(2, "'{$moduleDirname}_{$tables[$t]->getVar('table_name')}'", 'tables', $n);
                ++$n;
            }
            unset($n);
        }

        return $ret;
    }

    /*
    *  @private function getXoopsVersionSearch
    *  @param $moduleDirname
    */
    /**
     * @param $moduleDirname
     *
     * @return string
     */
    private function getXoopsVersionSearch($moduleDirname)
    {
        $ret = $this->getHeaderComment('Search');
        $ret .= $this->usercode->getUserModVersion(1, 1, 'hasSearch');
        $ret .= $this->usercode->getUserModVersion(2, "'include/search.inc.php'", 'search', "'file'");
        $ret .= $this->usercode->getUserModVersion(2, "'{$moduleDirname}_search'", 'search', "'func'");

        return $ret;
    }

    /*
    *  @private function getXoopsVersionComments
    *  @param $moduleDirname
    */
    /**
     * @param $moduleDirname
     *
     * @return string
     */
    private function getXoopsVersionComments($moduleDirname)
    {
        $ret = $this->getHeaderComment('Comments');
        $ret .= $this->usercode->getUserModVersion(2, "'comments.php'", 'comments', "'pageName'");
        $ret .= $this->usercode->getUserModVersion(2, "'com_id'", 'comments', "'itemName'");
        $ret .= $this->getCommentLine('Comment callback functions');
        $ret .= $this->usercode->getUserModVersion(2, "'include/comment_functions.php'", 'comments', "'callbackFile'");
        $descriptions = array('approve' => "'{$moduleDirname}CommentsApprove'", 'update' => "'{$moduleDirname}CommentsUpdate'");
        $ret .= $this->usercode->getUserModVersion(3, $descriptions, 'comments', "'callback'");

        return $ret;
    }

    /*
    *  @private function getXoopsVersionTemplatesAdmin
    *  @param $moduleDirname
    */
    /**
     * @param $moduleDirname
     * @param $tables
     *
     * @return string
     */
    private function getXoopsVersionTemplatesAdmin($moduleDirname, $tables)
    {
        $ret = $this->getHeaderComment('Templates');
        $ret .= $this->getCommentLine('Admin');

        $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'about', false, true);
        $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'header', false, true);
        $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'index', false, true);
        $tablePermissions = array();
        foreach (array_keys($tables) as $t) {
            $tableName = $tables[$t]->getVar('table_name');
            $tablePermissions[] = $tables[$t]->getVar('table_permissions');
            $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, $tableName, false, true);
        }
        if (in_array(1, $tablePermissions)) {
            $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'permissions', false, true);
        }
        $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'footer', false, true);

        return $ret;
    }

    /*
    *  @private function getXoopsVersionTemplatesLine
    *  @param $moduleDirname
    */
    /**
     * @param $moduleDirname
     *
     * @return string
     */
    private function getXoopsVersionTemplatesLine($moduleDirname, $type, $extra = '', $isAdmin = false)
    {
        $ret = '';
        $desc = "'description' => ''";
        $arrayFile = "array('file' =>";
        if ($isAdmin) {
            $ret .= $this->usercode->getUserModVersion(2, "{$arrayFile} '{$moduleDirname}_admin_{$type}.tpl', {$desc}, 'type' => 'admin')", 'templates', '');
        } else {
            if ($extra !== '') {
                $ret .= $this->usercode->getUserModVersion(2, "{$arrayFile} '{$moduleDirname}_{$type}_{$extra}.tpl', {$desc})", 'templates', '');
            } else {
                $ret .= $this->usercode->getUserModVersion(2, "{$arrayFile} '{$moduleDirname}_{$type}.tpl', {$desc})", 'templates', '');
            }
        }

        return $ret;
    }

    /*
    *  @private function getXoopsVersionTemplatesUser
    *  @param $moduleDirname
    */
    /**
     * @param $moduleDirname
     *
     * @return string
     */
    private function getXoopsVersionTemplatesUser($moduleDirname, $tables)
    {
        $ret = $this->getCommentLine('User');

        $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'header');
        $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'index');
        $tableBroken = array();
        $tablePdf = array();
        $tablePrint = array();
        $tableRate = array();
        $tableRss = array();
        $tableSearch = array();
        $tableSingle = array();
        $tableSubmit = array();
        foreach (array_keys($tables) as $t) {
            $tableName = $tables[$t]->getVar('table_name');
            $tableBroken[] = $tables[$t]->getVar('table_broken');
            $tablePdf[] = $tables[$t]->getVar('table_pdf');
            $tablePrint[] = $tables[$t]->getVar('table_print');
            $tableRate[] = $tables[$t]->getVar('table_rate');
            $tableRss[] = $tables[$t]->getVar('table_rss');
            $tableSearch[] = $tables[$t]->getVar('table_search');
            $tableSingle[] = $tables[$t]->getVar('table_single');
            $tableSubmit[] = $tables[$t]->getVar('table_submit');
            $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, $tableName);
            $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, $tableName, 'list');
        }
        $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'breadcrumbs');
        if (in_array(1, $tableBroken)) {
            $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'broken');
        }
        if (in_array(1, $tablePdf)) {
            $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'pdf');
        }
        if (in_array(1, $tablePrint)) {
            $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'print');
        }
        if (in_array(1, $tableRate)) {
            $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'rate');
        }
        if (in_array(1, $tableRss)) {
            $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'rss');
        }
        if (in_array(1, $tableSearch)) {
            $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'search');
        }
        if (in_array(1, $tableSingle)) {
            $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'single');
        }
        if (in_array(1, $tableSubmit)) {
            $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'submit');
        }
        $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'footer');

        return $ret;
    }

    /*
    *  @private function getXoopsVersionSubmenu
    *  @param $language
    */
    /**
     * @param $language
     *
     * @return string
     */
    private function getXoopsVersionSubmenu($language, $tables)
    {
        $ret = $this->getHeaderComment('Submenu');
        $i = 1;
        $tableSubmit = array();
        foreach (array_keys($tables) as $t) {
            $tableName = $tables[$t]->getVar('table_name');
            $tableSubmit[] = $tables[$t]->getVar('table_submit');
            if (1 == $tables[$t]->getVar('table_submenu')) {
                $ret .= $this->getCommentLine('Sub', $tableName);
                $tname = array('name' => "{$language}SMNAME{$i}", 'url' => "'{$tableName}.php'");
                $ret .= $this->usercode->getUserModVersion(3, $tname, 'sub', $i);
            }
            ++$i;
        }
        if (in_array(1, $tableSubmit)) {
            $ret .= $this->getCommentLine('Sub', 'Submit');
            $submit = array('name' => "{$language}SMNAME{$i}", 'url' => "'submit.php'");
            $ret .= $this->usercode->getUserModVersion(3, $submit, 'sub', $i);
        }
        unset($i);

        return $ret;
    }

    /*
    *  @private function getXoopsVersionBlocks
    *  @param $moduleDirname
    *  @param $language
    */
    /**
     * @param $moduleDirname
     * @param $language
     *
     * @return string
     */
    private function getXoopsVersionBlocks($moduleDirname, $tables, $language)
    {
        $ret = $this->getHeaderComment('Blocks');
        $ret .= $this->getSimpleString('$b = 1;');
        foreach (array_keys($tables) as $i) {
            $tableName = $tables[$i]->getVar('table_name');
            $tableFieldName = $tables[$i]->getVar('table_fieldname');
            if (1 == $tables[$i]->getVar('table_category')) {
                $ret .= $this->getXoopsVersionTypeBlocks($moduleDirname, $tableName, $language, $tableFieldName);
            } else {
                $ret .= $this->getXoopsVersionTypeBlocks($moduleDirname, $tableName, $language, 'last');
                $ret .= $this->getXoopsVersionTypeBlocks($moduleDirname, $tableName, $language, 'new');
                $ret .= $this->getXoopsVersionTypeBlocks($moduleDirname, $tableName, $language, 'hits');
                $ret .= $this->getXoopsVersionTypeBlocks($moduleDirname, $tableName, $language, 'top');
                $ret .= $this->getXoopsVersionTypeBlocks($moduleDirname, $tableName, $language, 'random');
            }
        }
        $ret .= $this->getSimpleString('unset($b);');

        return $ret;
    }

    /*
    *  @private function getXoopsVersionTypeBlocks
    *  @param $moduleDirname
    *  @param $language
    */
    /**
     * @param $moduleDirname
     * @param $language
     *
     * @return string
     */
    private function getXoopsVersionTypeBlocks($moduleDirname, $tableName, $language, $type)
    {
        $stuTableName = strtoupper($tableName);
        $stuType = strtoupper($type);
        $ucfType = ucfirst($type);
        $ret = $this->getCommentLine("{$ucfType}");
        $blocks = array('file' => "'{$tableName}.php'", 'name' => "{$language}{$stuTableName}_BLOCK_{$stuType}", 'description' => "{$language}{$stuTableName}_BLOCK_{$stuType}_DESC",
                        'show_func' => "'b_{$moduleDirname}_{$tableName}_show'", 'edit_func' => "'b_{$moduleDirname}_{$tableName}_edit'",
                        'template' => "'{$moduleDirname}_block_{$tableName}.tpl'", 'options' => "'{$type}|5|25|0'", );
        $ret .= $this->usercode->getUserModVersion(3, $blocks, 'blocks', '$b');
        $ret .= $this->getSimpleString('++$b;');

        return $ret;
    }

    /*
    *  @private function getXoopsVersionConfig
    *  @param $moduleDirname
    *  @param $language
    */
    /**
     * @param $module
     * @param $table
     * @param $language
     *
     * @return string
     */
    private function getXoopsVersionConfig($module, $table, $language)
    {
        $moduleDirname = $module->getVar('mod_dirname');
        $ret = $this->getHeaderComment('Config');
        $ret .= $this->getSimpleString('$c = 1;');
        $fields = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        $fieldName = array();
        $fieldElement = array();
        foreach (array_keys($fields) as $f) {
            $fieldName[] = $fields[$f]->getVar('field_name');
            $fieldElement[] = $fields[$f]->getVar('field_element');
        }
        if (in_array(4, $fieldElement)) {
            $rpFieldName = $this->getRightString($fieldName);
            $ucfFieldName = ucfirst($rpFieldName);
            $ret .= $this->getCommentLine('Editor', $rpFieldName);
            $ret .= $this->xoopscode->getXoopsCodeLoad('xoopseditorhandler');
            $ret .= $this->xoopscode->getXoopsCodeEqualsOperator('editorHandler ', 'XoopsEditorHandler::getInstance()');
            $editor = array('name' => "'{$moduleDirname}_editor_{$rpFieldName}'", 'title' => "'{$language}EDITOR_{$ucfFieldName}'", 'description' => "'{$language}EDITOR_{$ucfFieldName}_DESC'",
                    'formtype' => "'select'", 'valuetype' => "'text'", 'default' => "'dhtml'", 'options' => 'array_flip($editorHandler->getList())', );
            $ret .= $this->usercode->getUserModVersion(3, $editor, 'config', '$c');
            $ret .= $this->getSimpleString('++$c;');
        }
        if (1 == $table->getVar('table_permissions')) {
            $ret .= $this->getCommentLine('Get groups');
            $ret .= $this->xoopscode->getXoopsCodeEqualsOperator('$memberHandler ', "xoops_gethandler('member')", true);
            $ret .= $this->xoopscode->getXoopsCodeEqualsOperator('$xoopsGroups ', '$memberHandler->getGroupList()');
            $group = $this->xoopscode->getXoopsCodeEqualsOperator('$groups[$group] ', '$key');
            $ret .= $this->phpcode->getPhpCodeForeach('xoopsGroups', false, 'key', 'group', $group);
            $groups = array('name' => "'groups'", 'title' => "'{$language}GROUPS'", 'description' => "'{$language}GROUPS_DESC'",
                        'formtype' => "'select_multi'", 'valuetype' => "'array'", 'default' => '$groups', 'options' => '$groups', );
            $ret .= $this->usercode->getUserModVersion(3, $groups, 'config', '$c');
            $ret .= $this->getSimpleString('++$c;');
            $ret .= $this->getCommentLine('Get Admin groups');
            $ret .= $this->xoopscode->getXoopsCodeEqualsOperator('$criteria ', 'new CriteriaCompo()');
            $ret .= $this->getSimpleString("\$criteria->add( new Criteria( 'group_type', 'Admin' ) );");
            $ret .= $this->xoopscode->getXoopsCodeEqualsOperator('$memberHandler ', "xoops_gethandler('member')", true);
            $ret .= $this->xoopscode->getXoopsCodeEqualsOperator('$adminXoopsGroups ', '$memberHandler->getGroupList($criteria)');
            $adminGroup = $this->xoopscode->getXoopsCodeEqualsOperator('$adminGroups[$adminGroup] ', '$key');
            $ret .= $this->phpcode->getPhpCodeForeach('adminXoopsGroups', false, 'key', 'adminGroup', $adminGroup);
            $adminGroups = array('name' => "'admin_groups'", 'title' => "'{$language}GROUPS'", 'description' => "'{$language}GROUPS_DESC'",
                        'formtype' => "'select_multi'", 'valuetype' => "'array'", 'default' => '$adminGroups', 'options' => '$adminGroups', );
            $ret .= $this->usercode->getUserModVersion(3, $adminGroups, 'config', '$c');
            $ret .= $this->getSimpleString('++$c;');
        }
        $keyword = implode(', ', $this->getKeywords());
        $ret .= $this->getCommentLine('Keywords');
        $arrayKeyword = array('name' => "'keywords'", 'title' => "'{$language}KEYWORDS'", 'description' => "'{$language}KEYWORDS_DESC'",
                        'formtype' => "'textbox'", 'valuetype' => "'text'", 'default' => "'{$moduleDirname}, {$keyword}'", );
        $ret .= $this->usercode->getUserModVersion(3, $arrayKeyword, 'config', '$c');
        $ret .= $this->getSimpleString('++$c;');
        unset($this->keywords);
        if (is_object($table)) {
            if (in_array(array(10, 11, 12, 13, 14), $fieldElement)) {
                $ret .= $this->getCommentLine('Uploads : maxsize of image');
                $maxsize = array('name' => "'maxsize'", 'title' => "'{$language}MAXSIZE'", 'description' => "'{$language}MAXSIZE_DESC'",
                    'formtype' => "'textbox'", 'valuetype' => "'int'", 'default' => '5000000', );
                $ret .= $this->usercode->getUserModVersion(3, $maxsize, 'config', '$c');
                $ret .= $this->getCommentLine('Uploads : mimetypes of image');
                $ret .= $this->getSimpleString('++$c;');
                $mimetypes = array('name' => "'mimetypes'", 'title' => "'{$language}MIMETYPES'", 'description' => "'{$language}MIMETYPES_DESC'",
                    'formtype' => "'select_multi'", 'valuetype' => "'array'", 'default' => "array('image/gif', 'image/jpeg', 'image/png')", 'options' => "array('bmp' => 'image/bmp','gif' => 'image/gif','pjpeg' => 'image/pjpeg',
				   'jpeg' => 'image/jpeg','jpg' => 'image/jpg','jpe' => 'image/jpe',
				   'png' => 'image/png')", );
                $ret .= $this->usercode->getUserModVersion(3, $mimetypes, 'config', '$c');
                $ret .= $this->getSimpleString('++$c;');
            }
            if (1 == $table->getVar('table_admin')) {
                $ret .= $this->getCommentLine('Admin pager');
                $adminPager = array('name' => "'adminpager'", 'title' => "'{$language}ADMIN_PAGER'", 'description' => "'{$language}ADMIN_PAGER_DESC'",
                        'formtype' => "'textbox'", 'valuetype' => "'int'", 'default' => '10', );
                $ret .= $this->usercode->getUserModVersion(3, $adminPager, 'config', '$c');
                $ret .= $this->getSimpleString('++$c;');
            }
            if (1 == $table->getVar('table_user')) {
                $ret .= $this->getCommentLine('User pager');
                $userPager = array('name' => "'userpager'", 'title' => "'{$language}USER_PAGER'", 'description' => "'{$language}USER_PAGER_DESC'",
                        'formtype' => "'textbox'", 'valuetype' => "'int'", 'default' => '10', );
                $ret .= $this->usercode->getUserModVersion(3, $userPager, 'config', '$c');
                $ret .= $this->getSimpleString('++$c;');
            }
            if (1 == $table->getVar('table_tag')) {
                $ret .= $this->getCommentLine('Use tag');
                $useTag = array('name' => "'usetag'", 'title' => "'{$language}USE_TAG'", 'description' => "'{$language}USE_TAG_DESC'",
                        'formtype' => "'yesno'", 'valuetype' => "'int'", 'default' => '0', );
                $ret .= $this->usercode->getUserModVersion(3, $useTag, 'config', '$c');
                $ret .= $this->getSimpleString('++$c;');
            }
        }
        $ret .= $this->getCommentLine('Number column');
        $numbCol = array('name' => "'numb_col'", 'title' => "'{$language}NUMB_COL'", 'description' => "'{$language}NUMB_COL_DESC'",
                        'formtype' => "'select'", 'valuetype' => "'int'", 'default' => '1', 'options' => "array(1 => '1', 2 => '2', 3 => '3', 4 => '4')", );
        $ret .= $this->usercode->getUserModVersion(3, $numbCol, 'config', '$c');
        $ret .= $this->getSimpleString('++$c;');
        $ret .= $this->getCommentLine('Divide by');
        $divideby = array('name' => "'divideby'", 'title' => "'{$language}DIVIDEBY'", 'description' => "'{$language}DIVIDEBY_DESC'",
                        'formtype' => "'select'", 'valuetype' => "'int'", 'default' => '1', 'options' => "array(1 => '1', 2 => '2', 3 => '3', 4 => '4')", );
        $ret .= $this->usercode->getUserModVersion(3, $divideby, 'config', '$c');
        $ret .= $this->getSimpleString('++$c;');
        $ret .= $this->getCommentLine('Table type');
        $tableType = array('name' => "'table_type'", 'title' => "'{$language}DIVIDEBY'", 'description' => "'{$language}DIVIDEBY_DESC'",
                        'formtype' => "'select'", 'valuetype' => "'int'", 'default' => "'bordered'", 'options' => "array('bordered' => 'bordered', 'striped' => 'striped', 'hover' => 'hover', 'condensed' => 'condensed')", );
        $ret .= $this->usercode->getUserModVersion(3, $tableType, 'config', '$c');
        $ret .= $this->getSimpleString('++$c;');
        $ret .= $this->getCommentLine('Panel by');
        $panelType = array('name' => "'panel_type'", 'title' => "'{$language}PANEL_TYPE'", 'description' => "'{$language}PANEL_TYPE_DESC'",
                        'formtype' => "'select'", 'valuetype' => "'text'", 'default' => "'default'", 'options' => "array('default' => 'default', 'primary' => 'primary', 'success' => 'success', 'info' => 'info', 'warning' => 'warning', 'danger' => 'danger')", );
        $ret .= $this->usercode->getUserModVersion(3, $panelType, 'config', '$c');
        $ret .= $this->getSimpleString('++$c;');
        $ret .= $this->getCommentLine('Panel by');
        $advertise = array('name' => "'advertise'", 'title' => "'{$language}ADVERTISE'", 'description' => "'{$language}ADVERTISE_DESC'",
                        'formtype' => "'textarea'", 'valuetype' => "'text'", 'default' => "''", );
        $ret .= $this->usercode->getUserModVersion(3, $advertise, 'config', '$c');
        $ret .= $this->getSimpleString('++$c;');
        $ret .= $this->getCommentLine('Panel by');
        $bookmarks = array('name' => "'bookmarks'", 'title' => "'{$language}BOOKMARKS'", 'description' => "'{$language}BOOKMARKS_DESC'",
                        'formtype' => "'yesno'", 'valuetype' => "'int'", 'default' => '0', );
        $ret .= $this->usercode->getUserModVersion(3, $bookmarks, 'config', '$c');
        $ret .= $this->getSimpleString('++$c;');
        $ret .= $this->getCommentLine('Facebook Comments');
        $facebookComments = array('name' => "'facebook_comments'", 'title' => "'{$language}FACEBOOK_COMMENTS'", 'description' => "'{$language}FACEBOOK_COMMENTS_DESC'",
                        'formtype' => "'yesno'", 'valuetype' => "'int'", 'default' => '0', );
        $ret .= $this->usercode->getUserModVersion(3, $facebookComments, 'config', '$c');
        $ret .= $this->getSimpleString('++$c;');
        $ret .= $this->getCommentLine('Disqus Comments');
        $disqusComments = array('name' => "'disqus_comments'", 'title' => "'{$language}DISQUS_COMMENTS'", 'description' => "'{$language}DISQUS_COMMENTS_DESC'",
                        'formtype' => "'yesno'", 'valuetype' => "'int'", 'default' => '0', );
        $ret .= $this->usercode->getUserModVersion(3, $disqusComments, 'config', '$c');
        $ret .= $this->getSimpleString('++$c;');
        $ret .= $this->getCommentLine('Maintained by');
        $maintainedby = array('name' => "'maintainedby'", 'title' => "'{$language}MAINTAINEDBY'", 'description' => "'{$language}MAINTAINEDBY_DESC'",
                        'formtype' => "'yesno'", 'valuetype' => "'int'", 'default' => "'{$module->getVar('mod_support_url')}'", );
        $ret .= $this->usercode->getUserModVersion(3, $maintainedby, 'config', '$c');
        $ret .= $this->getSimpleString('unset($c);');

        return $ret;
    }

    /*
    *  @private function getNotificationsType
    *  @param $language
    *  @param $type
    *  @param $tableName
    *  @param $item
    *  @param $typeOfNotify
    */
    /**
     * @param $language
     * @param $type
     * @param $tableName
     * @param $notifyFile
     * @param $item
     * @param $typeOfNotify
     *
     * @return string
     */
    private function getNotificationsType($language, $type = 'category', $tableName, $notifyFile, $item, $typeOfNotify)
    {
        $stuTableName = strtoupper($tableName);
        $stuTypeOfNotify = strtoupper($typeOfNotify);
        $notifyFile = explode(', ', $notifyFile);
        $notifyFile = implode(', ', $notifyFile);
        $ret = '';
        switch ($type) {
            case 'category':
                $ret .= $this->getCommentLine('Category Notify');
                $category = array('name' => "'category'", 'title' => "'{$language}{$stuTableName}_NOTIFY'", 'description' => "'{$language}{$stuTableName}_NOTIFY_DESC'",
                                    'subscribe_from' => "array('index.php',{$notifyFile})", 'item_name' => "'{$item}'", "'allow_bookmark'" => '1', );
                $ret .= $this->usercode->getUserModVersion(3, $category, 'notification', "'{$type}'");
                break;
            case 'event':
                $ret .= $this->getCommentLine('Event Notify');
                $event = array('name' => "'{$typeOfNotify}'", 'category' => "'{$tableName}'", 'admin_only' => '1', "'title'" => "'{$language}{$stuTableName}_{$stuTypeOfNotify}_NOTIFY'",
                                'caption' => "'{$language}{$stuTableName}_{$stuTypeOfNotify}_NOTIFY_CAPTION'", 'description' => "'{$language}{$stuTableName}_{$stuTypeOfNotify}_NOTIFY_DESC'",
                                'mail_template' => "'{$tableName}_{$typeOfNotify}_notify'", 'mail_subject' => "'{$language}{$stuTableName}_{$stuTypeOfNotify}_NOTIFY_SUBJECT'", );
                $ret .= $this->usercode->getUserModVersion(3, $event, 'notification', "'{$type}'");
                break;
        }

        return $ret;
    }

    /*
    *  @private function getXoopsVersionNotifications
    *  @param $moduleDirname
    *  @param $language
    */
    /**
     * @param $moduleDirname
     * @param $language
     *
     * @return string
     */
    private function getXoopsVersionNotifications($module, $language)
    {
        $moduleDirname = $module->getVar('mod_dirname');
        $ret = $this->getHeaderComment('Notifications');
        $ret .= $this->usercode->getUserModVersion(1, 1, 'hasNotification');
        $notifications = array("'lookup_file'" => "'include/notification.inc.php'", "'lookup_func'" => "'{$moduleDirname}_notify_iteminfo'");
        $ret .= $this->usercode->getUserModVersion(2, $notifications, 'notification');

        $notifyFiles = array();
        $single = 'single';
        $tables = $this->getTableTables($module->getVar('mod_id'), 'table_order');
        $tableCategory = array();
        $tableBroken = array();
        $tableSubmit = array();
        $tableId = null;
        $tableMid = null;
        foreach (array_keys($tables) as $t) {
            $tableId = $tables[$t]->getVar('table_id');
            $tableMid = $tables[$t]->getVar('table_mid');
            $tableName = $tables[$t]->getVar('table_name');
            $tableCategory[] = $tables[$t]->getVar('table_category');
            $tableBroken[] = $tables[$t]->getVar('table_broken');
            $tableSubmit[] = $tables[$t]->getVar('table_submit');
            if (1 == $tables[$t]->getVar('table_notifications')) {
                if ($t <= count($tableName)) {
                    $notifyFiles[] = $tables[$t]->getVar('table_name');
                }
            }
            if (1 == $tables[$t]->getVar('table_single')) {
                $single = $tableName;
            }
        }
        $fields = $this->getTableFields($tableMid, $tableId);
        $fieldId = null;
        $fieldParent = null;
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            if (0 == $f) {
                $fieldId = $fieldName;
            }
            if ($fieldElement > 15) {
                $fieldParent = $fieldName;
            }
        }

        $num = 1;
        $ret .= $this->getXoopsVersionNotificationGlobal($language, 'category', 'global', 'global', $notifyFiles, $num);
        ++$num;
        $ret .= $this->getXoopsVersionNotificationCategory($language, 'category', 'category', 'category', $notifyFiles, $fieldParent, '1', $num);
        ++$num;
        $ret .= $this->getXoopsVersionNotificationTableName($language, 'category', 'file', 'file', $single, $fieldId, 1, $num);
        unset($num);
        $num = 1;
        if (in_array(1, $tableCategory)) {
            ++$num;
            $ret .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'new_category', 'global', 0, 'global', 'newcategory', 'global_newcategory_notify', $num);
        }
        $ret .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'file_modify', 'global', 1, 'global', 'filemodify', 'global_filemodify_notify', $num);
        if (in_array(1, $tableBroken)) {
            ++$num;
            $ret .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'file_broken', 'global', 1, 'global', 'filebroken', 'global_filebroken_notify', $num);
        }
        if (in_array(1, $tableSubmit)) {
            ++$num;
            $ret .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'file_submit', 'global', 1, 'global', 'filesubmit', 'global_filesubmit_notify', $num);
        }
        ++$num;
        $ret .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'new_file', 'global', 0, 'global', 'newfile', 'global_newfile_notify', $num);
        if (in_array(1, $tableCategory)) {
            ++$num;
            $ret .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'file_submit', 'category', 1, 'category', 'filesubmit', 'category_filesubmit_notify', $num);
            ++$num;
            $ret .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'new_file', 'category', 0, 'category', 'newfile', 'category_newfile_notify', $num);
        }
        ++$num;
        $ret .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'approve', 'file', 1, 'file', 'approve', 'file_approve_notify', $num);
        unset($num);

        return $ret;
    }

    /*
    *  @private function getXoopsVersionNotificationGlobal
    */
    /**
     * @param $language
     * @param $type
     * @param $name
     * @param $title
     * @param $from
     *
     * @return string
     */
    private function getXoopsVersionNotificationGlobal($language, $type, $name, $title, $from, $num)
    {
        $title = strtoupper($title);
        $implodeFrom = implode(".php', '", $from);
        $ret = $this->getCommentLine('Global Notify');
        $global = array('name' => "'{$name}'", 'title' => "{$language}{$title}_NOTIFY", 'description' => "{$language}{$title}_NOTIFY_DESC",
                        'subscribe_from' => "array('index.php', '{$implodeFrom}.php')", );
        $ret .= $this->usercode->getUserModVersion(4, $global, 'notification', "'{$type}'", $num);

        return $ret;
    }

    /*
    *  @private function getXoopsVersionNotificationCategory
    */
    /**
     * @param $language
     * @param $type
     * @param $name
     * @param $title
     * @param $from
     *
     * @return string
     */
    private function getXoopsVersionNotificationCategory($language, $type, $name, $title, $file, $item, $allow, $num)
    {
        $title = strtoupper($title);
        $impFile = implode(".php', '", $file);
        $ret = $this->getCommentLine('Category Notify');
        $global = array('name' => "'{$name}'", 'title' => "{$language}{$title}_NOTIFY", 'description' => "{$language}{$title}_NOTIFY_DESC",
                        'subscribe_from' => "array('{$impFile}.php')", 'item_name' => "'{$item}'", 'allow_bookmark' => "{$allow}", );
        $ret .= $this->usercode->getUserModVersion(4, $global, 'notification', "'{$type}'", $num);

        return $ret;
    }

    /*
    *  @private function getXoopsVersionNotificationTableName
    */
    /**
     * @param $language
     * @param $type
     * @param $name
     * @param $title
     * @param $file
     * @param $item
     * @param $allow
     *
     * @return string
     */
    private function getXoopsVersionNotificationTableName($language, $type, $name, $title, $file, $item = 'cid', $allow = 1, $num)
    {
        $stuTitle = strtoupper($title);
        $ucfTitle = ucfirst($title);
        $ret = $this->getCommentLine($ucfTitle.' Notify');
        $table = array('name' => "'{$name}'", 'title' => "{$language}{$stuTitle}_NOTIFY", 'description' => "{$language}{$stuTitle}_NOTIFY_DESC",
                        'subscribe_from' => "'{$file}.php'", 'item_name' => "'{$item}'", 'allow_bookmark' => "{$allow}", );
        $ret .= $this->usercode->getUserModVersion(4, $table, 'notification', "'{$type}'", $num);

        return $ret;
    }

    /*
    *  @private function getXoopsVersionNotifications
    */
    /**
     * @param $language
     * @param $type
     * @param $name
     * @param $title
     * @param $from
     * @param $item
     * @param $mail
     *
     * @return string
     */
    private function getXoopsVersionNotificationCodeComplete($language, $type, $name, $category, $admin = 1, $title, $table, $mail, $num)
    {
        $title = strtoupper($title);
        $table = strtoupper($table);
        $ucfTitle = ucfirst($title);
        $ret = $this->getCommentLine($ucfTitle.' Notify');
        $event = array('name' => "'{$name}'", 'category' => "'{$category}'", 'admin_only' => "{$admin}", 'title' => "{$language}{$title}_{$table}_NOTIFY",
                        'caption' => "{$language}{$title}_{$table}_NOTIFY_CAPTION", 'description' => "{$language}{$title}_{$table}_NOTIFY_DESC",
                        'mail_template' => "'{$mail}'", 'mail_subject' => "{$language}{$title}_{$table}_NOTIFY_SUBJECT", );
        $ret .= $this->usercode->getUserModVersion(4, $event, 'notification', "'{$type}'", $num);

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
        $content .= $this->getXoopsVersionHeader($module, $language);
        if (1 == $module->getVar('mod_admin')) {
            $content .= $this->getXoopsVersionTemplatesAdmin($moduleDirname, $tables);
        }
        if (1 == $module->getVar('mod_user')) {
            $content .= $this->getXoopsVersionTemplatesUser($moduleDirname, $tables);
        }
        $content .= $this->getXoopsVersionMySQL($moduleDirname, $table, $tables);
        $tableSearch = array();
        $tableComments = array();
        $tableSubmenu = array();
        $tableBlocks = array();
        $tableNotifications = array();
        foreach (array_keys($tables) as $t) {
            $tableSearch[] = $tables[$t]->getVar('table_search');
            $tableComments[] = $tables[$t]->getVar('table_comments');
            $tableSubmenu[] = $tables[$t]->getVar('table_submenu');
            $tableBlocks[] = $tables[$t]->getVar('table_blocks');
            $tableNotifications[] = $tables[$t]->getVar('table_notifications');
        }
        if (in_array(1, $tableSearch)) {
            $content .= $this->getXoopsVersionSearch($moduleDirname);
        }
        if (in_array(1, $tableComments)) {
            $content .= $this->getXoopsVersionComments($moduleDirname);
        }
        if (in_array(1, $tableSubmenu)) {
            $content .= $this->getXoopsVersionSubmenu($language, $tables);
        }
        if (in_array(1, $tableBlocks)) {
            $content .= $this->getXoopsVersionBlocks($moduleDirname, $tables, $language);
        }
        $content .= $this->getXoopsVersionConfig($module, $table, $language);
        if (in_array(1, $tableNotifications)) {
            $content .= $this->getXoopsVersionNotifications($module, $language);
        }
        $this->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
