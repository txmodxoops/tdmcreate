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
 * @version         $Id: UserXoopsVersion.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class UserXoopsVersion.
 */
class UserXoopsVersion extends TDMCreateFile
{
    /**
     * @var array
     */
    private $kw = [];

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
     * @return UserXoopsVersion
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
     *  @param $module
     *  @param mixed $table
     *  @param mixed $tables
     *  @param $filename
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

    /**
     *  @public function setKeywords
     *  @param mixed $keywords
     */
    public function setKeywords($keywords)
    {
        if (is_array($keywords)) {
            $this->kw = $keywords;
        } else {
            $this->kw[] = $keywords;
        }
    }

    /**
     *  @public function getKeywords
     *  @param null
     * @return array
     */
    public function getKeywords()
    {
        return $this->kw;
    }

    /**
     * @private function getXoopsVersionHeader
     * @param $module
     * @param $language
     *
     * @return string
     */
    private function getXoopsVersionHeader($module, $language)
    {
        $xCodeVHeader = TDMCreateXoopsCode::getInstance();
        $uCodeVHeader = UserXoopsCode::getInstance();
        $date = date('Y/m/d');
        $ret = $this->getSimpleString('');
        $ret .= TDMCreatePhpCode::getInstance()->getPhpCodeCommentLine();
        $ret .= $xCodeVHeader->getXcEqualsOperator('$dirname ', 'basename(__DIR__)');
        $ret .= $this->getDashComment('Informations');
        $ha = (1 == $module->getVar('mod_admin')) ? 1 : 0;
        $hm = (1 == $module->getVar('mod_user')) ? 1 : 0;

        $descriptions = [
            'name'                => "{$language}NAME", 'version' => (string)$module->getVar('mod_version'), 'description' => "{$language}DESC",
            'author'              => "'{$module->getVar('mod_author')}'", 'author_mail' => "'{$module->getVar('mod_author_mail')}'", 'author_website_url' => "'{$module->getVar('mod_author_website_url')}'",
            'author_website_name' => "'{$module->getVar('mod_author_website_name')}'", 'credits' => "'{$module->getVar('mod_credits')}'", 'license' => "'{$module->getVar('mod_license')}'",
            'license_url'         => "'http://www.gnu.org/licenses/gpl-3.0.en.html'", 'help' => "'page=help'", 'release_info' => "'{$module->getVar('mod_release_info')}'",
            'release_file' => "XOOPS_URL . '/modules/{$module->getVar('mod_dirname')}/docs/{$module->getVar('mod_release_file')}'", 'release_date' => "'{$date}'",
            'manual' => "'{$module->getVar('mod_manual')}'", 'manual_file' => "XOOPS_URL . '/modules/{$module->getVar('mod_dirname')}/docs/{$module->getVar('mod_manual_file')}'",
            'min_php' => "'{$module->getVar('mod_min_php')}'", 'min_xoops' => "'{$module->getVar('mod_min_xoops')}'", 'min_admin' => "'{$module->getVar('mod_min_admin')}'",
            'min_db' => "array('mysql' => '{$module->getVar('mod_min_mysql')}', 'mysqli' => '{$module->getVar('mod_min_mysql')}')", 'image' => "'assets/images/{$module->getVar('mod_image')}'",
            'dirname' => 'basename(__DIR__)', 'dirmoduleadmin' => "'Frameworks/moduleclasses/moduleadmin'", 'sysicons16' => "'../../Frameworks/moduleclasses/icons/16'",
            'sysicons32' => "'../../Frameworks/moduleclasses/icons/32'", 'modicons16' => "'assets/icons/16'", 'modicons32' => "'assets/icons/32'",
            'demo_site_url' => "'{$module->getVar('mod_demo_site_url')}'", 'demo_site_name' => "'{$module->getVar('mod_demo_site_name')}'", 'support_url' => "'{$module->getVar('mod_support_url')}'",
            'support_name' => "'{$module->getVar('mod_support_name')}'", 'module_website_url' => "'{$module->getVar('mod_website_url')}'", 'module_website_name' => "'{$module->getVar('mod_website_name')}'", 'release' => "'{$module->getVar('mod_release')}'", 'module_status' => "'{$module->getVar('mod_status')}'",
            'system_menu' => '1', 'hasAdmin' => $ha, 'hasMain' => $hm, 'adminindex' => "'admin/index.php'", 'adminmenu' => "'admin/menu.php'",
            'onInstall' => "'include/install.php'", 'onUpdate' => "'include/update.php'",
        ];

        $ret .= $uCodeVHeader->getUserModVersion(1, $descriptions);

        return $ret;
    }

    /**
     *  @private function getXoopsVersionMySQL
     *  @param $moduleDirname
     *  @param $table
     * @param $tables
     * @return string
     */
    private function getXoopsVersionMySQL($moduleDirname, $table, $tables)
    {
        $uCodeVMySQL = UserXoopsCode::getInstance();
        $tableName = $table->getVar('table_name');
        $n = 1;
        $ret = '';
        if (!empty($tableName)) {
            $ret .= $this->getDashComment('Mysql');
            $ret .= $uCodeVMySQL->getUserModVersion(2, "'sql/mysql.sql'", 'sqlfile', "'mysql'");
            $ret .= TDMCreatePhpCode::getInstance()->getPhpCodeCommentLine('Tables');

            foreach (array_keys($tables) as $t) {
                $ret .= $uCodeVMySQL->getUserModVersion(2, "'{$moduleDirname}_{$tables[$t]->getVar('table_name')}'", 'tables', $n);
                ++$n;
            }
            unset($n);
        }

        return $ret;
    }

    /**
     *  @private function getXoopsVersionSearch
     * @param $moduleDirname
     *
     * @return string
     */
    private function getXoopsVersionSearch($moduleDirname)
    {
        $uCodeVSearch = UserXoopsCode::getInstance();
        $ret = $this->getDashComment('Search');
        $ret .= $uCodeVSearch->getUserModVersion(1, 1, 'hasSearch');
        $ret .= $uCodeVSearch->getUserModVersion(2, "'include/search.inc.php'", 'search', "'file'");
        $ret .= $uCodeVSearch->getUserModVersion(2, "'{$moduleDirname}_search'", 'search', "'func'");

        return $ret;
    }

    /**
     *  @private function getXoopsVersionComments
     *  @param $moduleDirname
     *
     * @return string
     */
    private function getXoopsVersionComments($moduleDirname)
    {
        $uCodeVComments = UserXoopsCode::getInstance();
        $ret = $this->getDashComment('Comments');
        $ret .= $uCodeVComments->getUserModVersion(2, "'comments.php'", 'comments', "'pageName'");
        $ret .= $uCodeVComments->getUserModVersion(2, "'com_id'", 'comments', "'itemName'");
        $ret .= TDMCreatePhpCode::getInstance()->getPhpCodeCommentLine('Comment callback functions');
        $ret .= $uCodeVComments->getUserModVersion(2, "'include/comment_functions.php'", 'comments', "'callbackFile'");
        $descriptions = ['approve' => "'{$moduleDirname}CommentsApprove'", 'update' => "'{$moduleDirname}CommentsUpdate'"];
        $ret .= $uCodeVComments->getUserModVersion(3, $descriptions, 'comments', "'callback'");

        return $ret;
    }

    /**
     *  @private function getXoopsVersionTemplatesAdmin
     *  @param $moduleDirname
     * @param $tables
     *
     * @return string
     */
    private function getXoopsVersionTemplatesAdmin($moduleDirname, $tables)
    {
        $ret = $this->getDashComment('Templates');
        $ret .= TDMCreatePhpCode::getInstance()->getPhpCodeCommentLine('Admin');

        $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'about', '', true);
        $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'header', '', true);
        $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'index', '', true);
        $tablePermissions = [];
        foreach (array_keys($tables) as $t) {
            $tableName = $tables[$t]->getVar('table_name');
            $tablePermissions[] = $tables[$t]->getVar('table_permissions');
            $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, $tableName, '', true);
        }
        if (in_array(1, $tablePermissions, true)) {
            $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'permissions', '', true);
        }
        $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'footer', '', true);

        return $ret;
    }

    /**
     *  @private function getXoopsVersionTemplatesLine
     * @param        $moduleDirname
     * @param        $type
     * @param string $extra
     * @param bool   $isAdmin
     * @return string
     */
    private function getXoopsVersionTemplatesLine($moduleDirname, $type, $extra = '', $isAdmin = false)
    {
        $uCodeVTLine = UserXoopsCode::getInstance();
        $ret = '';
        $desc = "'description' => ''";
        $arrayFile = "array('file' =>";
        if ($isAdmin) {
            $ret .= $uCodeVTLine->getUserModVersion(2, "{$arrayFile} '{$moduleDirname}_admin_{$type}.tpl', {$desc}, 'type' => 'admin')", 'templates', '');
        } else {
            if ('' !== $extra) {
                $ret .= $uCodeVTLine->getUserModVersion(2, "{$arrayFile} '{$moduleDirname}_{$type}_{$extra}.tpl', {$desc})", 'templates', '');
            } else {
                $ret .= $uCodeVTLine->getUserModVersion(2, "{$arrayFile} '{$moduleDirname}_{$type}.tpl', {$desc})", 'templates', '');
            }
        }

        return $ret;
    }

    /**
     *  @private function getXoopsVersionTemplatesUser
     *  @param $moduleDirname
     * @param $tables
     * @return string
     */
    private function getXoopsVersionTemplatesUser($moduleDirname, $tables)
    {
        $ret = TDMCreatePhpCode::getInstance()->getPhpCodeCommentLine('User');

        $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'header', '');
        $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'index', '');
        $tableBroken = [];
        $tablePdf = [];
        $tablePrint = [];
        $tableRate = [];
        $tableRss = [];
        $tableSearch = [];
        $tableSingle = [];
        $tableSubmit = [];
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
            $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, $tableName, '');
            $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, $tableName, 'list');
        }
        $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'breadcrumbs', '');
        if (in_array(1, $tableBroken, true)) {
            $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'broken', '');
        }
        if (in_array(1, $tablePdf, true)) {
            $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'pdf', '');
        }
        if (in_array(1, $tablePrint, true)) {
            $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'print', '');
        }
        if (in_array(1, $tableRate, true)) {
            $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'rate', '');
        }
        if (in_array(1, $tableRss, true)) {
            $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'rss', '');
        }
        if (in_array(1, $tableSearch, true)) {
            $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'search', '');
        }
        if (in_array(1, $tableSingle, true)) {
            $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'single', '');
        }
        if (in_array(1, $tableSubmit, true)) {
            $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'submit', '');
        }
        $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'footer', '');

        return $ret;
    }

    /**
     *  @private function getXoopsVersionSubmenu
     * @param $language
     * @param $tables
     * @return string
     */
    private function getXoopsVersionSubmenu($language, $tables)
    {
        $phpCodeVSubmenu = TDMCreatePhpCode::getInstance();
        $uCodeVSubmenu = UserXoopsCode::getInstance();
        $ret = $this->getDashComment('Submenu');
        $i = 1;
        $tableSubmit = [];
        foreach (array_keys($tables) as $t) {
            $tableName = $tables[$t]->getVar('table_name');
            $tableSubmit[] = $tables[$t]->getVar('table_submit');
            if (1 == $tables[$t]->getVar('table_submenu')) {
                $ret .= $phpCodeVSubmenu->getPhpCodeCommentLine('Sub', $tableName);
                $tname = ['name' => "{$language}SMNAME{$i}", 'url' => "'{$tableName}.php'"];
                $ret .= $uCodeVSubmenu->getUserModVersion(3, $tname, 'sub', $i);
            }
            ++$i;
        }
        if (in_array(1, $tableSubmit, true)) {
            $ret .= $phpCodeVSubmenu->getPhpCodeCommentLine('Sub', 'Submit');
            $submit = ['name' => "{$language}SMNAME{$i}", 'url' => "'submit.php'"];
            $ret .= $uCodeVSubmenu->getUserModVersion(3, $submit, 'sub', $i);
        }
        unset($i);

        return $ret;
    }

    /**
     *  @private function getXoopsVersionBlocks
     * @param $moduleDirname
     * @param $tables
     * @param $language
     * @return string
     */
    private function getXoopsVersionBlocks($moduleDirname, $tables, $language)
    {
        $ret = $this->getDashComment('Blocks');
        $ret .= $this->getSimpleString('$b = 1;');
        $tableCategory = [];
        foreach (array_keys($tables) as $i) {
            $tableName = $tables[$i]->getVar('table_name');
            $tableFieldName = $tables[$i]->getVar('table_fieldname');
            $tableSoleName = $tables[$i]->getVar('table_solename');
            $stuTableSoleName = mb_strtoupper($tableSoleName);
            $tableCategory[] = $tables[$i]->getVar('table_category');
            if (in_array(1, $tableCategory, true)) {
                $ret .= $this->getXoopsVersionTypeBlocks($moduleDirname, $tableName, $stuTableSoleName, $language, $tableFieldName);
            } else {
                $ret .= $this->getXoopsVersionTypeBlocks($moduleDirname, $tableName, 'LAST', $language, 'last');
                $ret .= $this->getXoopsVersionTypeBlocks($moduleDirname, $tableName, 'NEW', $language, 'new');
                $ret .= $this->getXoopsVersionTypeBlocks($moduleDirname, $tableName, 'HITS', $language, 'hits');
                $ret .= $this->getXoopsVersionTypeBlocks($moduleDirname, $tableName, 'TOP', $language, 'top');
                $ret .= $this->getXoopsVersionTypeBlocks($moduleDirname, $tableName, 'RANDOM', $language, 'random');
            }
        }
        $ret .= $this->getSimpleString('unset($b);');

        return $ret;
    }

    /**
     *  @private function getXoopsVersionTypeBlocks
     * @param $moduleDirname
     * @param $tableName
     * @param $stuTableSoleName
     * @param $language
     * @param $type
     * @return string
     */
    private function getXoopsVersionTypeBlocks($moduleDirname, $tableName, $stuTableSoleName, $language, $type)
    {
        $phpCodeVTBlocks = TDMCreatePhpCode::getInstance();
        $uCodeVTBlocks = UserXoopsCode::getInstance();
        $stuTableName = mb_strtoupper($tableName);
        $ucfTableName = ucfirst($tableName);
        $ret = $phpCodeVTBlocks->getPhpCodeCommentLine($ucfTableName);
        $blocks = [
            'file' => "'{$tableName}.php'", 'name' => "{$language}{$stuTableName}_BLOCK_{$stuTableSoleName}", 'description' => "{$language}{$stuTableName}_BLOCK_{$stuTableSoleName}_DESC",
            'show_func' => "'b_{$moduleDirname}_{$tableName}_show'", 'edit_func' => "'b_{$moduleDirname}_{$tableName}_edit'",
            'template' => "'{$moduleDirname}_block_{$tableName}.tpl'", 'options' => "'{$type}|5|25|0'",
        ];
        $ret .= $uCodeVTBlocks->getUserModVersion(3, $blocks, 'blocks', '$b');
        $ret .= $this->getSimpleString('++$b;');

        return $ret;
    }

    /**
     * @private function getXoopsVersionConfig
     * @param $module
     * @param $tables
     * @param $language
     *
     * @return string
     */
    private function getXoopsVersionConfig($module, $tables, $language)
    {
        $phpCodeVConfig = TDMCreatePhpCode::getInstance();
        $xCodeVConfig = TDMCreateXoopsCode::getInstance();
        $uCodeVConfig = UserXoopsCode::getInstance();
        $moduleDirname = $module->getVar('mod_dirname');
        $ret = $this->getDashComment('Config');
        $ret .= $this->getSimpleString('$c = 1;');

        $table_permissions = 0;
        $table_admin = 0;
        $table_user = 0;
        $table_tag = 0;
        $field_images = 0;
        foreach ($tables as $table) {
            $fields = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
            foreach (array_keys($fields) as $f) {
                $fieldElement = $fields[$f]->getVar('field_element');
                switch ($fieldElement) {
                    case '3':
                    case '4':
                    case 3:
                    case 4:
                        $fieldName = $fields[$f]->getVar('field_name');
                        $rpFieldName = $this->getRightString($fieldName);
                        $ucfFieldName = ucfirst($rpFieldName);
                        $stuFieldName = mb_strtoupper($rpFieldName);
                        $ret .= $phpCodeVConfig->getPhpCodeCommentLine('Editor', $rpFieldName);
                        $ret .= $xCodeVConfig->getXcLoad('xoopseditorhandler');
                        $ret .= $xCodeVConfig->getXcEqualsOperator('$editorHandler' . $ucfFieldName, 'XoopsEditorHandler::getInstance()');
                        $editor = [
                            'name' => "'editor_{$rpFieldName}'", 'title' => "'{$language}EDITOR_{$stuFieldName}'", 'description' => "'{$language}EDITOR_{$stuFieldName}_DESC'",
                            'formtype' => "'select'", 'valuetype' => "'text'", 'default' => "'dhtml'", 'options' => 'array_flip($editorHandler' . $ucfFieldName . '->getList())',
                        ];
                        $ret .= $uCodeVConfig->getUserModVersion(3, $editor, 'config', '$c');
                        $ret .= $this->getSimpleString('++$c;');
                    break;
                    case '10':
                    case '11':
                    case '12':
                    case '13':
                    case '14':
                    case 10:
                    case 11:
                    case 12:
                    case 13:
                    case 14:
                        $field_images = 1;
                    break;
                    case 'else':
                    default:
                    break;
                }
            }
            if (1 == $table->getVar('table_permissions')) {
                $table_permissions = 1;
            }
            if (1 == $table->getVar('table_admin')) {
                $table_admin = 1;
            }
            if (1 == $table->getVar('table_user')) {
                $table_user = 1;
            }
            if (1 == $table->getVar('table_tag')) {
                $table_tag = 1;
            }
        }

        if (1 === $table_permissions) {
            $ret .= $phpCodeVConfig->getPhpCodeCommentLine('Get groups');
            $ret .= $xCodeVConfig->getXcEqualsOperator('$memberHandler ', "xoops_getHandler('member')", '', false);
            $ret .= $xCodeVConfig->getXcEqualsOperator('$xoopsGroups ', '$memberHandler->getGroupList()');
            $group = $xCodeVConfig->getXcEqualsOperator('$groups[$group] ', '$key', null, false, "\t");
            $ret .= $phpCodeVConfig->getPhpCodeForeach('xoopsGroups', false, 'key', 'group', $group);
            $groups = [
                'name' => "'groups'", 'title' => "'{$language}GROUPS'", 'description' => "'{$language}GROUPS_DESC'",
                'formtype' => "'select_multi'", 'valuetype' => "'array'", 'default' => '$groups', 'options' => '$groups',
            ];
            $ret .= $uCodeVConfig->getUserModVersion(3, $groups, 'config', '$c');
            $ret .= $this->getSimpleString('++$c;');
            $ret .= $phpCodeVConfig->getPhpCodeCommentLine('Get Admin groups');
            $ret .= $xCodeVConfig->getXcEqualsOperator('$criteria ', 'new CriteriaCompo()');
            $ret .= $this->getSimpleString("\$criteria->add( new Criteria( 'group_type', 'Admin' ) );");
            $ret .= $xCodeVConfig->getXcEqualsOperator('$memberHandler ', "xoops_getHandler('member')", '', false);
            $ret .= $xCodeVConfig->getXcEqualsOperator('$adminXoopsGroups ', '$memberHandler->getGroupList($criteria)');
            $adminGroup = $xCodeVConfig->getXcEqualsOperator('$adminGroups[$adminGroup] ', '$key', null, false, "\t");
            $ret .= $phpCodeVConfig->getPhpCodeForeach('adminXoopsGroups', false, 'key', 'adminGroup', $adminGroup);
            $adminGroups = [
                'name' => "'admin_groups'", 'title' => "'{$language}GROUPS'", 'description' => "'{$language}GROUPS_DESC'",
                'formtype' => "'select_multi'", 'valuetype' => "'array'", 'default' => '$adminGroups', 'options' => '$adminGroups',
            ];
            $ret .= $uCodeVConfig->getUserModVersion(3, $adminGroups, 'config', '$c');
            $ret .= $this->getSimpleString('++$c;');
        }
        $keyword = implode(', ', $this->getKeywords());
        $ret .= $phpCodeVConfig->getPhpCodeCommentLine('Keywords');
        $arrayKeyword = [
            'name' => "'keywords'", 'title' => "'{$language}KEYWORDS'", 'description' => "'{$language}KEYWORDS_DESC'",
            'formtype' => "'textbox'", 'valuetype' => "'text'", 'default' => "'{$moduleDirname}, {$keyword}'",
        ];
        $ret .= $uCodeVConfig->getUserModVersion(3, $arrayKeyword, 'config', '$c');
        $ret .= $this->getSimpleString('++$c;');
        unset($this->keywords);

        if (1 === $field_images) {
            $ret .= $phpCodeVConfig->getPhpCodeCommentLine('Uploads : maxsize of image');
            $maxsize = [
                'name' => "'maxsize'", 'title' => "'{$language}MAXSIZE'", 'description' => "'{$language}MAXSIZE_DESC'",
                'formtype' => "'textbox'", 'valuetype' => "'int'", 'default' => '5000000',
            ];
            $ret .= $uCodeVConfig->getUserModVersion(3, $maxsize, 'config', '$c');
            $ret .= $phpCodeVConfig->getPhpCodeCommentLine('Uploads : mimetypes of image');
            $ret .= $this->getSimpleString('++$c;');
            $mimetypes = [
                'name' => "'mimetypes'", 'title' => "'{$language}MIMETYPES'", 'description' => "'{$language}MIMETYPES_DESC'",
                'formtype' => "'select_multi'", 'valuetype' => "'array'", 'default' => "array('image/gif', 'image/jpeg', 'image/png')",
                'options' => "array('bmp' => 'image/bmp','gif' => 'image/gif','pjpeg' => 'image/pjpeg', 'jpeg' => 'image/jpeg','jpg' => 'image/jpg','jpe' => 'image/jpe', 'png' => 'image/png')",
            ];
            $ret .= $uCodeVConfig->getUserModVersion(3, $mimetypes, 'config', '$c');
            $ret .= $this->getSimpleString('++$c;');
        }
        if (1 === $table_admin) {
            $ret .= $phpCodeVConfig->getPhpCodeCommentLine('Admin pager');
            $adminPager = [
                'name' => "'adminpager'", 'title' => "'{$language}ADMIN_PAGER'", 'description' => "'{$language}ADMIN_PAGER_DESC'",
                'formtype' => "'textbox'", 'valuetype' => "'int'", 'default' => '10',
            ];
            $ret .= $uCodeVConfig->getUserModVersion(3, $adminPager, 'config', '$c');
            $ret .= $this->getSimpleString('++$c;');
        }
        if (1 === $table_user) {
            $ret .= $phpCodeVConfig->getPhpCodeCommentLine('User pager');
            $userPager = [
                'name' => "'userpager'", 'title' => "'{$language}USER_PAGER'", 'description' => "'{$language}USER_PAGER_DESC'",
                'formtype' => "'textbox'", 'valuetype' => "'int'", 'default' => '10',
            ];
            $ret .= $uCodeVConfig->getUserModVersion(3, $userPager, 'config', '$c');
            $ret .= $this->getSimpleString('++$c;');
        }
        if (1 === $table_tag) {
            $ret .= $phpCodeVConfig->getPhpCodeCommentLine('Use tag');
            $useTag = [
                'name' => "'usetag'", 'title' => "'{$language}USE_TAG'", 'description' => "'{$language}USE_TAG_DESC'",
                'formtype' => "'yesno'", 'valuetype' => "'int'", 'default' => '0',
            ];
            $ret .= $uCodeVConfig->getUserModVersion(3, $useTag, 'config', '$c');
            $ret .= $this->getSimpleString('++$c;');
        }
        $ret .= $phpCodeVConfig->getPhpCodeCommentLine('Number column');
        $numbCol = [
            'name' => "'numb_col'", 'title' => "'{$language}NUMB_COL'", 'description' => "'{$language}NUMB_COL_DESC'",
            'formtype' => "'select'", 'valuetype' => "'int'", 'default' => '1', 'options' => "array(1 => '1', 2 => '2', 3 => '3', 4 => '4')",
        ];
        $ret .= $uCodeVConfig->getUserModVersion(3, $numbCol, 'config', '$c');
        $ret .= $this->getSimpleString('++$c;');
        $ret .= $phpCodeVConfig->getPhpCodeCommentLine('Divide by');
        $divideby = [
            'name' => "'divideby'", 'title' => "'{$language}DIVIDEBY'", 'description' => "'{$language}DIVIDEBY_DESC'",
            'formtype' => "'select'", 'valuetype' => "'int'", 'default' => '1', 'options' => "array(1 => '1', 2 => '2', 3 => '3', 4 => '4')",
        ];
        $ret .= $uCodeVConfig->getUserModVersion(3, $divideby, 'config', '$c');
        $ret .= $this->getSimpleString('++$c;');
        $ret .= $phpCodeVConfig->getPhpCodeCommentLine('Table type');
        $tableType = [
            'name' => "'table_type'", 'title' => "'{$language}TABLE_TYPE'", 'description' => "'{$language}DIVIDEBY_DESC'",
            'formtype' => "'select'", 'valuetype' => "'int'", 'default' => "'bordered'", 'options' => "array('bordered' => 'bordered', 'striped' => 'striped', 'hover' => 'hover', 'condensed' => 'condensed')",
        ];
        $ret .= $uCodeVConfig->getUserModVersion(3, $tableType, 'config', '$c');
        $ret .= $this->getSimpleString('++$c;');
        $ret .= $phpCodeVConfig->getPhpCodeCommentLine('Panel by');
        $panelType = [
            'name' => "'panel_type'", 'title' => "'{$language}PANEL_TYPE'", 'description' => "'{$language}PANEL_TYPE_DESC'",
            'formtype' => "'select'", 'valuetype' => "'text'", 'default' => "'default'", 'options' => "array('default' => 'default', 'primary' => 'primary', 'success' => 'success', 'info' => 'info', 'warning' => 'warning', 'danger' => 'danger')",
        ];
        $ret .= $uCodeVConfig->getUserModVersion(3, $panelType, 'config', '$c');
        $ret .= $this->getSimpleString('++$c;');
        $ret .= $phpCodeVConfig->getPhpCodeCommentLine('Advertise');
        $advertise = [
            'name' => "'advertise'", 'title' => "'{$language}ADVERTISE'", 'description' => "'{$language}ADVERTISE_DESC'",
            'formtype' => "'textarea'", 'valuetype' => "'text'", 'default' => "''",
        ];
        $ret .= $uCodeVConfig->getUserModVersion(3, $advertise, 'config', '$c');
        $ret .= $this->getSimpleString('++$c;');
        $ret .= $phpCodeVConfig->getPhpCodeCommentLine('Bookmarks');
        $bookmarks = [
            'name' => "'bookmarks'", 'title' => "'{$language}BOOKMARKS'", 'description' => "'{$language}BOOKMARKS_DESC'",
            'formtype' => "'yesno'", 'valuetype' => "'int'", 'default' => '0',
        ];
        $ret .= $uCodeVConfig->getUserModVersion(3, $bookmarks, 'config', '$c');
        $ret .= $this->getSimpleString('++$c;');
        $ret .= $phpCodeVConfig->getPhpCodeCommentLine('Facebook Comments');
        $facebookComments = [
            'name' => "'facebook_comments'", 'title' => "'{$language}FACEBOOK_COMMENTS'", 'description' => "'{$language}FACEBOOK_COMMENTS_DESC'",
            'formtype' => "'yesno'", 'valuetype' => "'int'", 'default' => '0',
        ];
        $ret .= $uCodeVConfig->getUserModVersion(3, $facebookComments, 'config', '$c');
        $ret .= $this->getSimpleString('++$c;');
        $ret .= $phpCodeVConfig->getPhpCodeCommentLine('Disqus Comments');
        $disqusComments = [
            'name' => "'disqus_comments'", 'title' => "'{$language}DISQUS_COMMENTS'", 'description' => "'{$language}DISQUS_COMMENTS_DESC'",
            'formtype' => "'yesno'", 'valuetype' => "'int'", 'default' => '0',
        ];
        $ret .= $uCodeVConfig->getUserModVersion(3, $disqusComments, 'config', '$c');
        $ret .= $this->getSimpleString('++$c;');
        $ret .= $phpCodeVConfig->getPhpCodeCommentLine('Maintained by');
        $maintainedby = [
            'name' => "'maintainedby'", 'title' => "'{$language}MAINTAINEDBY'", 'description' => "'{$language}MAINTAINEDBY_DESC'",
            'formtype' => "'textbox'", 'valuetype' => "'text'", 'default' => "'{$module->getVar('mod_support_url')}'",
        ];
        $ret .= $uCodeVConfig->getUserModVersion(3, $maintainedby, 'config', '$c');
        $ret .= $this->getSimpleString('unset($c);');

        return $ret;
    }

    /**
     *  @private function getNotificationsType
     * @param $language
     * @param $type
     * @param $tableName
     * @param $notifyFile
     * @param $item
     * @param $typeOfNotify
     *
     * @return string
     */
    private function getNotificationsType($language, $type, $tableName, $notifyFile, $item, $typeOfNotify)
    {
        $phpCodeNType = TDMCreatePhpCode::getInstance();
        $uCodeNType = UserXoopsCode::getInstance();
        $stuTableName = mb_strtoupper($tableName);
        $stuTypeOfNotify = mb_strtoupper($typeOfNotify);
        $notifyFile = explode(', ', $notifyFile);
        $notifyFile = implode(', ', $notifyFile);
        $ret = '';
        switch ($type) {
            case 'category':
                $ret .= $phpCodeNType->getPhpCodeCommentLine('Category Notify');
                $category = [
                    'name' => "'category'", 'title' => "'{$language}{$stuTableName}_NOTIFY'", 'description' => "'{$language}{$stuTableName}_NOTIFY_DESC'",
                    'subscribe_from' => "array('index.php',{$notifyFile})", 'item_name' => "'{$item}'", "'allow_bookmark'" => '1',
                ];
                $ret .= $uCodeNType->getUserModVersion(3, $category, 'notification', "'{$type}'");
                break;
            case 'event':
                $ret .= $phpCodeNType->getPhpCodeCommentLine('Event Notify');
                $event = [
                    'name' => "'{$typeOfNotify}'", 'category' => "'{$tableName}'", 'admin_only' => '1', "'title'" => "'{$language}{$stuTableName}_{$stuTypeOfNotify}_NOTIFY'",
                    'caption' => "'{$language}{$stuTableName}_{$stuTypeOfNotify}_NOTIFY_CAPTION'", 'description' => "'{$language}{$stuTableName}_{$stuTypeOfNotify}_NOTIFY_DESC'",
                    'mail_template' => "'{$tableName}_{$typeOfNotify}_notify'", 'mail_subject' => "'{$language}{$stuTableName}_{$stuTypeOfNotify}_NOTIFY_SUBJECT'",
                ];
                $ret .= $uCodeNType->getUserModVersion(3, $event, 'notification', "'{$type}'");
                break;
        }

        return $ret;
    }

    /**
     *  @private function getXoopsVersionNotifications
     * @param $module
     * @param $language
     * @return string
     */
    private function getXoopsVersionNotifications($module, $language)
    {
        $uCodeVN = UserXoopsCode::getInstance();
        $moduleDirname = $module->getVar('mod_dirname');
        $ret = $this->getDashComment('Notifications');
        $ret .= $uCodeVN->getUserModVersion(1, 1, 'hasNotification');
        $notifications = ["'lookup_file'" => "'include/notification.inc.php'", "'lookup_func'" => "'{$moduleDirname}_notify_iteminfo'"];
        $ret .= $uCodeVN->getUserModVersion(2, $notifications, 'notification');

        $notifyFiles = [];
        $single = 'single';
        $tables = $this->getTableTables($module->getVar('mod_id'), 'table_order');
        $tableCategory = [];
        $tableBroken = [];
        $tableSubmit = [];
        $tableId = null;
        $tableMid = null;
        foreach (array_keys($tables) as $t) {
            $tableId = $tables[$t]->getVar('table_id');
            $tableMid = $tables[$t]->getVar('table_mid');
            $tableName = $tables[$t]->getVar('table_name');
            $tableSoleName = $tables[$t]->getVar('table_solename');
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
        $ret .= $this->getXoopsVersionNotificationTableName($language, 'category', $tableSoleName, $tableSoleName, $single, $fieldId, 1, $num);
        unset($num);
        $num = 1;
        if (in_array(1, $tableCategory, true)) {
            $ret .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'new_category', 'global', 0, 'global', 'newcategory', 'global_newcategory_notify', $num);
            ++$num;
        }
        $ret .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', $tableSoleName . '_modify', 'global', 1, 'global', $tableSoleName . 'modify', 'global_' . $tableSoleName . 'modify_notify', $num);
        if (in_array(1, $tableBroken, true)) {
            ++$num;
            $ret .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', $tableSoleName . '_broken', 'global', 1, 'global', $tableSoleName . 'broken', 'global_' . $tableSoleName . 'broken_notify', $num);
        }
        if (in_array(1, $tableSubmit, true)) {
            ++$num;
            $ret .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', $tableSoleName . '_submit', 'global', 1, 'global', $tableSoleName . 'submit', 'global_' . $tableSoleName . 'submit_notify', $num);
        }
        ++$num;
        $ret .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'new_' . $tableSoleName, 'global', 0, 'global', 'new' . $tableSoleName, 'global_new' . $tableSoleName . '_notify', $num);
        if (in_array(1, $tableCategory, true)) {
            ++$num;
            $ret .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', $tableSoleName . '_submit', 'category', 1, 'category', $tableSoleName . 'submit', 'category_' . $tableSoleName . 'submit_notify', $num);
            ++$num;
            $ret .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'new_' . $tableSoleName, 'category', 0, 'category', 'new' . $tableSoleName, 'category_new' . $tableSoleName . '_notify', $num);
        }
        ++$num;
        $ret .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'approve', $tableSoleName, 1, $tableSoleName, 'approve', $tableSoleName . '_approve_notify', $num);
        unset($num);

        return $ret;
    }

    /**
     *  @private function getXoopsVersionNotificationGlobal
     * @param $language
     * @param $type
     * @param $name
     * @param $title
     * @param $from
     *
     * @param $num
     * @return string
     */
    private function getXoopsVersionNotificationGlobal($language, $type, $name, $title, $from, $num)
    {
        $phpCodeVNG = TDMCreatePhpCode::getInstance();
        $uCodeVNG = UserXoopsCode::getInstance();
        $title = mb_strtoupper($title);
        $implodeFrom = implode(".php', '", $from);
        $ret = $phpCodeVNG->getPhpCodeCommentLine('Global Notify');
        $global = [
            'name' => "'{$name}'", 'title' => "{$language}{$title}_NOTIFY", 'description' => "{$language}{$title}_NOTIFY_DESC",
            'subscribe_from' => "array('index.php', '{$implodeFrom}.php')",
        ];
        $ret .= $uCodeVNG->getUserModVersion(4, $global, 'notification', "'{$type}'", $num);

        return $ret;
    }

    /**
     *  @private function getXoopsVersionNotificationCategory
     * @param $language
     * @param $type
     * @param $name
     * @param $title
     * @param $file
     * @param $item
     * @param $allow
     * @param $num
     * @return string
     */
    private function getXoopsVersionNotificationCategory($language, $type, $name, $title, $file, $item, $allow, $num)
    {
        $phpCodeVNC = TDMCreatePhpCode::getInstance();
        $uCodeVNC = UserXoopsCode::getInstance();
        $title = mb_strtoupper($title);
        $impFile = implode(".php', '", $file);
        $ret = $phpCodeVNC->getPhpCodeCommentLine('Category Notify');
        $global = [
            'name' => "'{$name}'", 'title' => "{$language}{$title}_NOTIFY", 'description' => "{$language}{$title}_NOTIFY_DESC",
            'subscribe_from' => "array('{$impFile}.php')", 'item_name' => "'{$item}'", 'allow_bookmark' => (string)$allow,
        ];
        $ret .= $uCodeVNC->getUserModVersion(4, $global, 'notification', "'{$type}'", $num);

        return $ret;
    }

    /**
     *  @private function getXoopsVersionNotificationTableName
     * @param $language
     * @param $type
     * @param $name
     * @param $title
     * @param $file
     * @param $item
     * @param $allow
     *
     * @param $num
     * @return string
     */
    private function getXoopsVersionNotificationTableName($language, $type, $name, $title, $file, $item, $allow, $num)
    {
        $phpCodeVNTN = TDMCreatePhpCode::getInstance();
        $uCodeVNTN = UserXoopsCode::getInstance();
        $stuTitle = mb_strtoupper($title);
        $ucfTitle = ucfirst($title);
        $ret = $phpCodeVNTN->getPhpCodeCommentLine($ucfTitle . ' Notify');
        $table = [
            'name' => "'{$name}'", 'title' => "{$language}{$stuTitle}_NOTIFY", 'description' => "{$language}{$stuTitle}_NOTIFY_DESC",
            'subscribe_from' => "'{$file}.php'", 'item_name' => "'{$item}'", 'allow_bookmark' => (string)$allow,
        ];
        $ret .= $uCodeVNTN->getUserModVersion(4, $table, 'notification', "'{$type}'", $num);

        return $ret;
    }

    /**
     *  @private function getXoopsVersionNotifications
     * @param $language
     * @param $type
     * @param $name
     * @param $category
     * @param $admin
     * @param $title
     * @param $table
     * @param $mail
     *
     * @param $num
     * @return string
     */
    private function getXoopsVersionNotificationCodeComplete($language, $type, $name, $category, $admin, $title, $table, $mail, $num)
    {
        $phpCodeVNCC = TDMCreatePhpCode::getInstance();
        $uCodeVNCC = UserXoopsCode::getInstance();
        $title = mb_strtoupper($title);
        $table = mb_strtoupper($table);
        $ucfTitle = ucfirst($title);
        $ret = $phpCodeVNCC->getPhpCodeCommentLine($ucfTitle . ' Notify');
        $event = [
            'name'          => "'{$name}'", 'category' => "'{$category}'", 'admin_only' => (string)$admin, 'title' => "{$language}{$title}_{$table}_NOTIFY",
            'caption'       => "{$language}{$title}_{$table}_NOTIFY_CAPTION", 'description' => "{$language}{$title}_{$table}_NOTIFY_DESC",
            'mail_template' => "'{$mail}'", 'mail_subject' => "{$language}{$title}_{$table}_NOTIFY_SUBJECT",
        ];
        $ret .= $uCodeVNCC->getUserModVersion(4, $event, 'notification', "'{$type}'", $num);

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
        $tableSearch = [];
        $tableComments = [];
        $tableSubmenu = [];
        $tableBlocks = [];
        $tableNotifications = [];
        foreach (array_keys($tables) as $t) {
            $tableSearch[] = $tables[$t]->getVar('table_search');
            $tableComments[] = $tables[$t]->getVar('table_comments');
            $tableSubmenu[] = $tables[$t]->getVar('table_submenu');
            $tableBlocks[] = $tables[$t]->getVar('table_blocks');
            $tableNotifications[] = $tables[$t]->getVar('table_notifications');
        }
        if (in_array(1, $tableSearch, true)) {
            $content .= $this->getXoopsVersionSearch($moduleDirname);
        }
        if (in_array(1, $tableComments, true)) {
            $content .= $this->getXoopsVersionComments($moduleDirname);
        }
        if (in_array(1, $tableSubmenu, true)) {
            $content .= $this->getXoopsVersionSubmenu($language, $tables);
        }
        if (in_array(1, $tableBlocks, true)) {
            $content .= $this->getXoopsVersionBlocks($moduleDirname, $tables, $language);
        }
        $content .= $this->getXoopsVersionConfig($module, $tables, $language);
        if (in_array(1, $tableNotifications, true)) {
            $content .= $this->getXoopsVersionNotifications($module, $language);
        }
        $this->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
