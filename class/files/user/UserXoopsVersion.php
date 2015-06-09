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
 * tdmcreate module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         tdmcreate
 * @since           2.5.0
 * @author          Txmod Xoops http://www.txmodxoops.org
 * @version         $Id: xoopsversion_file.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class UserXoopsVersion
 */
class UserXoopsVersion extends TDMCreateFile
{
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
        $this->tdmcfile = TDMCreateFile::getInstance();
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
	
	/**
     * @private function getModVersionHeaderComment
     *
     * @param $comment	 
     * @return string
     */
    private function getModVersionHeaderComment($comment)
    {
		$ret = <<<EOT
// ------------------- {$comment} ------------------- //
EOT;
		
		return $ret;
    }
	
	/**
     * @private function getModVersionComment
     *
     * @param $comment	 
     * @return string
     */
    private function getModVersionComment($comment)
    {
		$ret = <<<EOT
// {$comment}
EOT;
		
		return $ret;
    }
	
	/**
     * @private function getModVersionArray
     *
     * @param $array
	 * @param $type
	 * @param $left
	 * @param $desc
     * @param $index
	 * @param $right 
	 
     * @return string
     */
    private function getModVersionArray($array = 1, $left, $desc = '', $index = null, $right = null, $n = '\n')
    {
		if(!is_string($desc)) {
			if($array == 1) {
				$ret = <<<EOT
\$modversion['{$left}'] = {$desc};{$n}
EOT;
			}	
			if($array == 2 && $index == 'empty') {
				$ret = <<<EOT
\$modversion['{$left}'][] = {$desc};{$n}
EOT;
			}
			if($array == 2 && !is_string($index)) {
				$ret = <<<EOT
\$modversion['{$left}'][{$index}] = {$desc};{$n}
EOT;
			}
			if($array == 2 && is_string($index)) {
				$ret = <<<EOT
\$modversion['{$left}']['{$index}'] = {$desc};{$n}
EOT;
			}
			if($array == 3) {
				$ret = <<<EOT
\$modversion['{$left}'][{$index}][{$right}] = {$desc};{$n}
EOT;
			}
		} else {
			if($array == 1) {
				$ret = <<<EOT
\$modversion['{$left}'] = "{$desc}";{$n}
EOT;
			}	
			if($array == 2 && $index == 'empty') {
				$ret = <<<EOT
\$modversion['{$left}'][] = "{$desc}";{$n}
EOT;
			}
			if($array == 2 && !is_string($index)) {
				$ret = <<<EOT
\$modversion['{$left}'][{$index}] = "{$desc}";{$n}
EOT;
			}
			if($array == 2 && is_string($index)) {
				$ret = <<<EOT
\$modversion['{$left}']['{$index}'] = "{$desc}";{$n}
EOT;
			}
			if($array == 3) {
				$ret = <<<EOT
\$modversion['{$left}'][{$index}][{$right}] = "{$desc}";{$n}
EOT;
			}				
		}
		
		return $ret;
    }

    /*
    *  @private function getXoopsVersionHeader
    *  @param $language
    */
    /**
     * @param $module
     * @param $table
     * @param $language
     * @return string
     */
    private function getXoopsVersionHeader($module, $table, $language)
    {
		//$dateString = preg_replace('/[^0-9]/', '/', _DBDATESTRING);
        $date = date(_DBDATESTRING); // _DBDATESTRING
        $ret  = <<<EOT
defined('XOOPS_ROOT_PATH') or die('Restricted access');
//
\$dirname = basename(__DIR__);
// ------------------- Informations ------------------- //
\$modversion = array(
    'name' => {$language}NAME,
    'version' => {$module->getVar('mod_version')},
    'description' => {$language}DESC,
    'author' => "{$module->getVar('mod_author')}",
    'author_mail' => "{$module->getVar('mod_author_mail')}",
    'author_website_url' => "{$module->getVar('mod_author_website_url')}",
    'author_website_name' => "{$module->getVar('mod_author_website_name')}",
    'credits' => "{$module->getVar('mod_credits')}",
    'license' => "{$module->getVar('mod_license')}",
    'help' => "page=help",
    'license' => "GNU GPL 2.0",
    'license_url' => "www.gnu.org/licenses/gpl-2.0.html/",
    //
    'release_info' => "{$module->getVar('mod_release_info')}",
    'release_file' => XOOPS_URL."/modules/{\$dirname}/docs/{$module->getVar('mod_release_file')}",
    'release_date' => "{$date}",
    //
    'manual' => "{$module->getVar('mod_manual')}",
    'manual_file' => XOOPS_URL."/modules/{\$dirname}/docs/{$module->getVar('mod_manual_file')}",
    'min_php' => "{$module->getVar('mod_min_php')}",
    'min_xoops' => "{$module->getVar('mod_min_xoops')}",
    'min_admin' => "{$module->getVar('mod_min_admin')}",
    'min_db' => array('mysql' => '{$module->getVar('mod_min_mysql')}', 'mysqli' => '{$module->getVar('mod_min_mysql')}'),
    'image' => "assets/images/{$module->getVar('mod_image')}",
    'dirname' => "{\$dirname}",
    //Frameworks
    'dirmoduleadmin' => "Frameworks/moduleclasses/moduleadmin",
    'sysicons16' => "../../Frameworks/moduleclasses/icons/16",
    'sysicons32' => "../../Frameworks/moduleclasses/icons/32",
    // Local path icons
    'modicons16' => "assets/icons/16",
    'modicons32' => "assets/icons/32",
    //About
    'demo_site_url' => "{$module->getVar('mod_demo_site_url')}",
    'demo_site_name' => "{$module->getVar('mod_demo_site_name')}",
    'support_url' => "{$module->getVar('mod_support_url')}",
    'support_name' => "{$module->getVar('mod_support_name')}",
    'module_website_url' => "{$module->getVar('mod_website_url')}",
    'module_website_name' => "{$module->getVar('mod_website_name')}",
    'release' => "{$module->getVar('mod_release')}",
    'module_status' => "{$module->getVar('mod_status')}",
    // Admin system menu
    'system_menu' => 1,
    // Admin things
    'hasAdmin' => 1,
    'adminindex' => "admin/index.php",
    'adminmenu' => "admin/menu.php",\n
EOT;
        if (1 == $module->getVar('mod_user')) {
            $ret .= <<<EOT
    // Main things
    'hasMain' => 1,\n
EOT;
        } else {
            $ret .= <<<EOT
    // Main things
    'hasMain' => 0,\n
EOT;
        }
        $ret .= <<<EOT
    // Install/Update
    'onInstall' => "include/install.php",\n
EOT;
        if (is_object($table) && $table->getVar('table_name') != null) {
                $ret .= <<<EOT
    'onUpdate' => "include/update.php"\n
EOT;

        }
        $ret .= <<<EOT
);\n
EOT;

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
     * @return string
     */
    private function getXoopsVersionMySQL($moduleDirname, $table, $tables)
    {
        $tableName = $table->getVar('table_name');
        $n         = 1;
        $ret       = '';
        if (!empty($tableName)) {
            $ret .= <<<EOT
// ------------------- Mysql ------------------- //
\$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
// Tables\n
EOT;
            foreach (array_keys($tables) as $t) {
                $ret .= <<<EOT
\$modversion['tables'][{$n}] = "{$moduleDirname}_{$tables[$t]->getVar('table_name')}";\n
EOT;
                ++$n;
            }
            unset($n);
        }
		$ret .= <<<EOT
\n
EOT;
        
		return $ret;
    }

    /*
    *  @private function getXoopsVersionSearch
    *  @param $moduleDirname
    */
    /**
     * @param $moduleDirname
     * @return string
     */
    private function getXoopsVersionSearch($moduleDirname)
    {
        $ret = <<<EOT
// ------------------- Search ------------------- //
\$modversion['hasSearch'] = 1;
\$modversion['search']['file'] = "include/search.inc.php";
\$modversion['search']['func'] = "{$moduleDirname}_search";\n\n
EOT;

        return $ret;
    }

    /*
    *  @private function getXoopsVersionComments
    *  @param $moduleDirname
    */
    /**
     * @param $moduleDirname
     * @return string
     */
    private function getXoopsVersionComments($moduleDirname)
    {
        $ret = <<<EOT
// ------------------- Comments ------------------- //
\$modversion['comments']['pageName'] = "comments.php";
\$modversion['comments']['itemName'] = "com_id";
// Comment callback functions
\$modversion['comments']['callbackFile'] = "include/comment_functions.php";
\$modversion['comments']['callback']['approve'] = "{$moduleDirname}CommentsApprove";
\$modversion['comments']['callback']['update'] = "{$moduleDirname}CommentsUpdate";\n\n
EOT;

        return $ret;
    }

    /*
    *  @private function getXoopsVersionTemplatesAdmin
    *  @param $moduleDirname
    */
    /**
     * @param $moduleDirname
     * @param $table
     * @return string
     */
    private function getXoopsVersionTemplatesAdmin($moduleDirname, $table, $tables)
    {
        $ret    = <<<EOT
// ------------------- Templates ------------------- //
// Admin\n
EOT;
        $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'about', false, true);
		$ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'header', false, true);
		$ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'index', false, true);
		foreach (array_keys($tables) as $t) {
			$tableName        = $tables[$t]->getVar('table_name');
            $tablePermissions = $tables[$t]->getVar('table_permissions');
            $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, $tableName, false, true);
        }
        if (is_object($table) && 1 == $table->getVar('table_permissions')) {
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
     * @return string
     */
    private function getXoopsVersionTemplatesLine($moduleDirname, $type, $extra = false, $isAdmin = false)
    {        
		if ($isAdmin) {
            $ret = <<<EOT
\$modversion['templates'][] = array('file' => '{$moduleDirname}_admin_{$type}.tpl', 'description' => '', 'type' => 'admin');\n
EOT;
        } else {
			if(!$extra) {
				$ret = <<<EOT
\$modversion['templates'][] = array('file' => '{$moduleDirname}_{$type}.tpl', 'description' => '');\n
EOT;
			} else {	
				$ret = <<<EOT
\$modversion['templates'][] = array('file' => '{$moduleDirname}_{$type}_{$extra}.tpl', 'description' => '');\n
EOT;
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
     * @return string
     */
    private function getXoopsVersionTemplatesUser($moduleDirname, $tables)
    {
        $table  = $this->getTable(); 
		$ret    = <<<EOT
// User\n
EOT;
        $ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'header');
		$ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'index');
		foreach (array_keys($tables) as $t) {
            $tableName = $tables[$t]->getVar('table_name');
			$ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, $tableName);
			$ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, $tableName, 'list');
        }
		$ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'breadcrumbs');
		if(1 == $table->getVar('table_broken')) {
			$ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'broken');
		}
		if(1 == $table->getVar('table_pdf')) {
			$ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'pdf');
		}
		if(1 == $table->getVar('table_print')) {
			$ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'print');
		}
		if(1 == $table->getVar('table_rate')) {
			$ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'rate');
		}
		if(1 == $table->getVar('table_rss')) {
			$ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'rss');
		}
		if(1 == $table->getVar('table_search')) {
			$ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'search');
		}
		if(1 == $table->getVar('table_single')) {
			$ret .= $this->getXoopsVersionTemplatesLine($moduleDirname, 'single');
		}
		if(1 == $table->getVar('table_submit')) {
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
     * @return string
     */
    private function getXoopsVersionSubmenu($language, $tables)
    {
        $ret    = <<<EOT
// ------------------- Submenu ------------------- //\n
EOT;
        $i      = 1;
        foreach (array_keys($tables) as $t) {
            $tableName = $tables[$t]->getVar('table_name');
            if (1 == $tables[$t]->getVar('table_submenu')) {
                $ret .= <<<EOT
// Sub {$tableName}
\$modversion['sub'][{$i}]['name'] = {$language}SMNAME{$i};
\$modversion['sub'][{$i}]['url'] = "{$tableName}.php";\n
EOT;
            }
            ++$i;
        }
        unset($i);
		$ret .= <<<EOT
\n
EOT;
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
     * @return string
     */
    private function getXoopsVersionBlocks($moduleDirname, $table, $tables, $language)
    {
        $ret = <<<EOT
// ------------------- Blocks ------------------- //\n
EOT;
        
		foreach (array_keys($tables) as $i) {
            $tableName      = $tables[$i]->getVar('table_name');
			$tableFieldName = $tables[$i]->getVar('table_fieldname');
            if (1 == $tables[$i]->getVar('table_blocks')) {
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
        }

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
     * @return string
     */
    private function getXoopsVersionTypeBlocks($moduleDirname, $tableName, $language, $type)
    {
        $stuTableName = strtoupper($tableName);
		$ret = <<<EOT
\$modversion['blocks'][] = array(
    'file' => "{$tableName}.php",
    'name' => {$language}{$stuTableName}_BLOCK,
    'description' => {$language}{$stuTableName}_BLOCK_DESC,
    'show_func' => "b_{$moduleDirname}_{$tableName}_show",
    'edit_func' => "b_{$moduleDirname}_{$tableName}_edit",
    'options' => "{$type}|5|25|0",
    'template' => "'{$moduleDirname}_block_{$tableName}.tpl");\n\n
EOT;

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
     * @return string
     */
    private function getXoopsVersionConfig($module, $table, $language)
    {
        $moduleDirname = $module->getVar('mod_dirname');
        $ret           = <<<EOT
// ------------------- Config ------------------- //\n
EOT;
        if (is_object($table)) {
            $fields = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
            foreach (array_keys($fields) as $f) {
                if ($fields[$f]->getVar('field_element') == 4) {
                    $ret .= <<<EOT
// Editor
xoops_load('xoopseditorhandler');
\$editorHandler = XoopsEditorHandler::getInstance();
\$modversion['config'][] = array(
    'name' => "{$moduleDirname}_editor",
    'title' => "{$language}EDITOR",
    'description' => "{$language}EDITOR_DESC",
    'formtype' => "select",
    'valuetype' => "text",
    'options' => array_flip(\$editorHandler->getList()),
    'default' => "dhtml");\n\n
EOT;
                }
            }
        }
        if (1 == $table->getVar('table_permissions')) {
            $ret .= <<<EOT
// Get groups
\$memberHandler =& xoops_gethandler('member');
\$xoopsgroups = \$memberHandler->getGroupList();
foreach (\$xoopsgroups as \$key => \$group) {
    \$groups[\$group] = \$key;
}
\$modversion['config'][] = array(
    'name' => "groups",
    'title' => "{$language}GROUPS",
    'description' => "{$language}GROUPS_DESC",
    'formtype' => "select_multi",
    'valuetype' => "array",
    'options' => \$groups,
    'default' => \$groups);
    
// Get Admin groups
\$criteria = new CriteriaCompo();
\$criteria->add( new Criteria( 'group_type', 'Admin' ) );
\$memberHandler =& xoops_gethandler('member');
\$admin_xoopsgroups = \$memberHandler->getGroupList(\$criteria);
foreach (\$admin_xoopsgroups as \$key => \$admin_group) {
    \$admin_groups[\$admin_group] = \$key;
}
\$modversion['config'][] = array(
    'name' => "admin_groups",
    'title' => "{$language}ADMIN_GROUPS",
    'description' => "{$language}ADMIN_GROUPS_DESC",
    'formtype' => "select_multi",
    'valuetype' => "array",
    'options' => \$admin_groups,
    'default' => \$admin_groups);\n\n
EOT;
        }
        $keyword = implode(', ', $this->getKeywords());
        $ret .= <<<EOT
\$modversion['config'][] = array(
    'name' => "keywords",
    'title' => "{$language}KEYWORDS",
    'description' => "{$language}KEYWORDS_DESC",
    'formtype' => "textbox",
    'valuetype' => "text",
    'default' => "{$moduleDirname}, {$keyword}");\n\n
EOT;
        unset($this->keywords);
        if (is_object($table)) {
            foreach (array_keys($fields) as $f) {
                $fieldElement = $fields[$f]->getVar('field_element');
                if ((10 == $fieldElement) || (11 == $fieldElement) ||
                    (12 == $fieldElement) || (13 == $fieldElement) || (14 == $fieldElement)
                ) {
                    $ret .= <<<EOT
//Uploads : maxsize of image
\$modversion['config'][] = array(
    'name' => "maxsize",
    'title' => "{$language}MAXSIZE",
    'description' => "{$language}MAXSIZE_DESC",
    'formtype' => "textbox",
    'valuetype' => "int",
    'default' => 5000000);

//Uploads : mimetypes of image
\$modversion['config'][] = array(
    'name' => "mimetypes",
    'title' => "{$language}MIMETYPES",
    'description' => "{$language}MIMETYPES_DESC",
    'formtype' => "select_multi",
    'valuetype' => "array",
    'default' => array("image/gif", "image/jpeg", "image/png"),
    'options' => array("bmp" => "image/bmp","gif" => "image/gif","pjpeg" => "image/pjpeg",
                       "jpeg" => "image/jpeg","jpg" => "image/jpg","jpe" => "image/jpe",
                       "png" => "image/png"));\n\n
EOT;
                }
            }
            if (1 == $table->getVar('table_admin')) {
                $ret .= <<<EOT
\$modversion['config'][] = array(
    'name' => "adminpager",
    'title' => "{$language}ADMIN_PAGER",
    'description' => "{$language}ADMIN_PAGER_DESC",
    'formtype' => "textbox",
    'valuetype' => "int",
    'default' => 10);\n\n
EOT;
            }
            if (1 == $table->getVar('table_user')) {
                $ret .= <<<EOT
\$modversion['config'][] = array(
    'name' => "userpager",
    'title' => "{$language}USER_PAGER",
    'description' => "{$language}USER_PAGER_DESC",
    'formtype' => "textbox",
    'valuetype' => "int",
    'default' => 10);\n\n
EOT;
            }
        }
        if (1 == $table->getVar('table_tag')) {
            $ret .= <<<EOT
\$modversion['config'][] = array(
    'name' => "usetag",
    'title' => "{$language}USE_TAG",
    'description' => "{$language}USE_TAG_DESC",
    'formtype' => "yesno",
    'valuetype' => "int",
    'default' => 0);\n\n
EOT;
        }
        $ret .= <<<EOT
\$modversion['config'][] = array(
    'name' => "numb_col",
    'title' => "{$language}NUMB_COL",
    'description' => "{$language}NUMB_COL_DESC",
    'formtype' => "select",
    'valuetype' => "int",
    'default' => 1,
	'options' => array(1 => "1", 2 => "2", 3 => "3", 4 => "4"));

\$modversion['config'][] = array(
    'name' => "divideby",
    'title' => "{$language}DIVIDEBY",
    'description' => "{$language}DIVIDEBY_DESC",
    'formtype' => "select",
    'valuetype' => "int",
    'default' => 1,
	'options' => array(1 => "1", 2 => "2", 3 => "3", 4 => "4"));
	
\$modversion['config'][] = array(
    'name' => "table_type",
    'title' => "{$language}TABLE_TYPE",
    'description' => "{$language}TABLE_TYPE_DESC",
    'formtype' => "select",
    'valuetype' => "text",
    'default' => "bordered",
    'options' => array('bordered' => "bordered", 'striped' => "striped", 'hover' => "hover", 'condensed' => "condensed"));
						
\$modversion['config'][] = array(
    'name' => "table_type",
    'title' => "{$language}PANEL_TYPE",
    'description' => "{$language}PANEL_TYPE_DESC",
    'formtype' => "select",
    'valuetype' => "text",
    'default' => "default",
    'options' => array('default' => "default", 'primary' => "primary", 'success' => "success", 'info' => "info", 'warning' => "warning", 'danger' => "danger"));

\$modversion['config'][] = array(
    'name' => "advertise",
    'title' => "{$language}ADVERTISE",
    'description' => "{$language}ADVERTISE_DESC",
    'formtype' => "textarea",
    'valuetype' => "text",
    'default' => "");

\$modversion['config'][] = array(
    'name' => "bookmarks",
    'title' => "{$language}BOOKMARKS",
    'description' => "{$language}BOOKMARKS_DESC",
    'formtype' => "yesno",
    'valuetype' => "int",
    'default' => 0);

\$modversion['config'][] = array(
    'name' => "fbcomments",
    'title' => "{$language}FBCOMMENTS",
    'description' => "{$language}FBCOMMENTS_DESC",
    'formtype' => "yesno",
    'valuetype' => "int",
    'default' => 0);\n
EOT;

        return $ret;
    }

    /*
    *  @private function getTypeNotifications
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
     * @return string
     */
    private function getTypeNotifications($language, $type = 'category', $tableName, $notifyFile, $item, $typeOfNotify)
    {
        $stuTableName    = strtoupper($tableName);
        $stuTypeOfNotify = strtoupper($typeOfNotify);
        switch ($type) {
            case 'category':
                $ret = <<<EOT
\$modversion['notification']['{$type}'][] = array(
    'name' => "category",
    'title' => {$language}{$stuTableName}_NOTIFY,
    'description' => {$language}{$stuTableName}_NOTIFY_DESC,
    'subscribe_from' => array({$notifyFile}),
    'item_name' => "{$item}",
    'allow_bookmark' => 1);\n
EOT;
                break;
            case 'event':
                $ret = <<<EOT
\$modversion['notification']['{$type}'][] = array(
    'name' => "{$typeOfNotify}",
    'category' => "{$tableName}",
    'admin_only' => 1,
    'title' => {$language}{$stuTableName}_{$stuTypeOfNotify}_NOTIFY,
    'caption' => {$language}{$stuTableName}_{$stuTypeOfNotify}_NOTIFY_CAPTION,
    'description' => {$language}{$stuTableName}_{$stuTypeOfNotify}_NOTIFY_DESC,
    'mail_template' => "{$tableName}_{$typeOfNotify}_notify",
    'mail_subject' => {$language}{$stuTableName}_{$stuTypeOfNotify}_NOTIFY_SUBJECT);\n
EOT;
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
     * @param $filename
     * @return string
     */
    private function getXoopsVersionNotifications($module, $language, $filename)
    {
        $moduleDirname = $module->getVar('mod_dirname');
		$ret           = <<<EOT
// ------------------- Notifications ------------------- //
\$modversion['hasNotification'] = 1;
\$modversion['notification']['lookup_file'] = 'include/notification.inc.php';
\$modversion['notification']['lookup_func'] = '{$moduleDirname}_notify_iteminfo';\n\n
EOT;
		$notifyFiles = '';
		$single      = 'single';		
        $tables      = $this->getTableTables($module->getVar('mod_id'));
		foreach (array_keys($tables) as $t) {
            $tableId       = $tables[$t]->getVar('table_id');
			$tableMid      = $tables[$t]->getVar('table_mid');
			$tableName     = $tables[$t]->getVar('table_name');
			$tableCategory = $tables[$t]->getVar('table_category');
			$tableBroken   = $tables[$t]->getVar('table_broken');
			$tableSubmit   = $tables[$t]->getVar('table_submit');
            if (1 == $tables[$t]->getVar('table_notifications')) {
                if ($t <= count($tableName)) {
					$notifyFiles = "'" . $tableName . ".php'";
                } else {
                    $notifyFiles = $tableName . '.php';
                }
            }
			if (1 == $tables[$t]->getVar('table_single')) {
                $single = $tableName;
            }
        }
		$fields = $this->tdmcfile->getTableFields($tableMid, $tableId);
		$fieldParent = null;
		foreach (array_keys($fields) as $f) {
            //$fieldId   = $fields[$f]->getVar('field_id');
			$fieldMid  = $fields[$f]->getVar('field_mid');
			$fieldTid  = $fields[$f]->getVar('field_tid');
			$fieldName = $fields[$f]->getVar('field_name');
			if (0 == $f) {
                $fieldId = $fieldName;
            }
			if (1 == $fields[$f]->getVar('field_parent')) {
                $fieldParent = $fieldName;
            }
        }
        
		$ret .= $this->getXoopsVersionNotificationCodeShort($language, 'category', 'global', 'global', $notifyFiles);
		if(1 == $tableCategory) {
			$ret .= $this->getXoopsVersionNotificationCodeItem($language, 'category', 'category', 'category', $notifyFiles, $fieldParent, '1');
		}
		$ret .= $this->getXoopsVersionNotificationCodeItem($language, 'category', 'file', 'file', $single.'.php', $fieldId, 1);
		if(1 == $tableCategory) {
			$ret .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'new_category', 'global', 0, 'global', 'newcategory', 'global_newcategory_notify');
		}
		$ret .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'file_modify', 'global', 1, 'global', 'filemodify', 'global_filemodify_notify');
		if(1 == $tableBroken) {
			$ret .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'file_broken', 'global', 1, 'global', 'filebroken', 'global_filebroken_notify');
		}
		if(1 == $tableSubmit) {
			$ret .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'file_submit', 'global', 1, 'global', 'filesubmit', 'global_filesubmit_notify');
		}
		$ret .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'new_file', 'global', 0, 'global', 'newfile', 'global_newfile_notify');
		if(1 == $tableCategory) {
			$ret .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'file_submit', 'category', 1, 'category', 'filesubmit', 'category_filesubmit_notify');
			$ret .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'new_file', 'category', 0, 'category', 'newfile', 'category_newfile_notify');
		}
		$ret .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'approve', 'file', 1, 'file', 'approve', 'file_approve_notify');
        return $ret;
    }
	
	/*
    *  @private function getXoopsVersionNotificationCodeShort
    */
    /**
     * @param $language
     * @param $type
	 * @param $name
	 * @param $title
	 * @param $from
     * @return string
     */
    private function getXoopsVersionNotificationCodeShort($language, $type, $name, $title, $from)
    {        
        $title = strtoupper($title);
		$from  = explode(', ', $from);
		$from  = implode(', ', $from);
		$ret = <<<EOT
\$modversion['notification']['{$type}'][] = array(
    'name' => "{$name}",
    'title' => {$language}{$title}_NOTIFY,
    'description' => {$language}{$title}_NOTIFY_DESC,
    'subscribe_from' => array('index.php', {$from}));\n\n
EOT;

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
	 * @param $allow
     * @return string
     */
    private function getXoopsVersionNotificationCodeItem($language, $type, $name, $title, $from, $item = 'cid', $allow = 1)
    {        
        $title = strtoupper($title);
		$ret = <<<EOT
\$modversion['notification']['{$type}'][] = array(
    'name' => "{$name}",
    'title' => {$language}{$title}_NOTIFY,
    'description' => {$language}{$title}_NOTIFY_DESC,
    'subscribe_from' => "{$from}",
    'item_name' => "{$item}",
    'allow_bookmark' => {$allow});\n\n
EOT;

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
     * @return string
     */
    private function getXoopsVersionNotificationCodeComplete($language, $type, $name, $category, $admin = 1, $title, $table, $mail)
    {        
        $title = strtoupper($title);
		$table = strtoupper($table);
		$ret = <<<EOT
\$modversion['notification']['{$type}'][] = array(
    'name' => "{$name}",
    'category' => "{$category}",
    'admin_only' => {$admin},
    'title' => {$language}{$title}_{$table}_NOTIFY,
    'caption' => {$language}{$title}_{$table}_NOTIFY_CAPTION,
    'description' => {$language}{$title}_{$table}_NOTIFY_DESC,
    'mail_template' => "{$mail}",
    'mail_subject' => {$language}{$title}_{$table}_NOTIFY_SUBJECT);\n\n
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
        $module        = $this->getModule();
        $table         = $this->getTable();
		$tables        = $this->getTables();
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language      = $this->getLanguage($moduleDirname, 'MI');
        $content       = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getXoopsVersionHeader($module, $table, $language);
        if (1 == $module->getVar('mod_admin')) {
            $content .= $this->getXoopsVersionTemplatesAdmin($moduleDirname, $table, $tables);
        }
        if (1 == $module->getVar('mod_user')) {
            $content .= $this->getXoopsVersionTemplatesUser($moduleDirname, $tables);
        }
		$content .= $this->getXoopsVersionMySQL($moduleDirname, $table, $tables);
		if (1 == $table->getVar('table_search')) {
			$content .= $this->getXoopsVersionSearch($moduleDirname);
		}
		if (1 == $table->getVar('table_comments')) {
			$content .= $this->getXoopsVersionComments($moduleDirname);
		}

		//if (1 == $table->getVar('table_submenu')) {
			$content .= $this->getXoopsVersionSubmenu($language, $tables);
		//}
		if (1 == $table->getVar('table_blocks')) {
			$content .= $this->getXoopsVersionBlocks($moduleDirname, $table, $tables, $language);
		}
        $content .= $this->getXoopsVersionConfig($module, $table, $language);
        if (1 == $table->getVar('table_notifications')) {
            $content .= $this->getXoopsVersionNotifications($module, $language, $filename);
        }
        $this->tdmcfile->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}
