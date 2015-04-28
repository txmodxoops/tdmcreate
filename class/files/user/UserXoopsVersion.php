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
	public function __construct() { 
		parent::__construct();
		$this->tdmcfile = TDMCreateFile::getInstance();	
	}	
	/*
	*  @static function &getInstance
	*  @param null
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
	public function write($module, $table, $tables, $filename) {    
		$this->setModule($module);
		$this->setTable($table);
		$this->setTables($tables);
		$this->setFileName($filename);
		foreach (array_keys($tables) as $t)
		{	
			$tableName = $tables[$t]->getVar('table_name');			
			$this->setKeywords($tableName);
		}
	}
	/*
	*  @public function setKeywords
	*  @param mixed $keywords
	*/
	public function setKeywords($keywords)
    {        
        if(is_array($keywords)) {
			$this->keywords = $keywords;
		} else {
			$this->keywords[] = $keywords;
		}
    }
	
	/*
	*  @public function getKeywords
	*  @param null
	*/
	public function getKeywords()
    {        
        return $this->keywords;
    }
	
	/*
	*  @private function getXoopsVersionHeader
	*  @param string $language
	*/
	private function getXoopsVersionHeader($module, $table, $language) 
	{ 
		//$dateString = preg_replace('/[^0-9]/', '/', _DBDATESTRING);
		$date = date(_DBDATESTRING); // _DBDATESTRING
		$ret = <<<EOT
defined('XOOPS_ROOT_PATH') or die('Restricted access');
//
\$dirname = basename( dirname( __FILE__ ) ) ;
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
		if ( $module->getVar('mod_user') == 1 ) {
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
		if (is_object($table)) {
			if ( $table->getVar('table_name') != null ) {
			$ret .= <<<EOT
    'onUpdate' => "include/update.php"\n
EOT;
			}
		} 
		$ret .= <<<EOT
);\n
EOT;
		return $ret;
	}
	/*
	*  @private function getXoopsVersionMySQL
	*  @param string $moduleDirname 
	*  @param string $table 
	*/
	private function getXoopsVersionMySQL($moduleDirname, $table) 
	{ 	
		$tableName = $table->getVar('table_name');
		$n = 1;
		$ret = '';
		if ( !empty($tableName) ) {
			$ret .= <<<EOT
// ------------------- Mysql ------------------- //
\$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
// Tables\n
EOT;
			$tables = $this->getTables();
			foreach (array_keys($tables) as $t)	
			{
				$ret .= <<<EOT
\$modversion['tables'][{$n}] = "{$moduleDirname}_{$tables[$t]->getVar('table_name')}";\n
EOT;
				$n++;
			}  
			unset($n);
		}
		return $ret;
	}
	/*
	*  @private function getXoopsVersionSearch
	*  @param string $moduleDirname 
	*/
	private function getXoopsVersionSearch($moduleDirname) 
	{ 
		$ret = <<<EOT
// ------------------- Search ------------------- //
\$modversion['hasSearch'] = 1;
\$modversion['search']['file'] = "include/search.inc.php";
\$modversion['search']['func'] = "{$moduleDirname}_search";\n
EOT;
		return $ret;
	}
	/*
	*  @private function getXoopsVersionComments
	*  @param string $moduleDirname 
	*/
	private function getXoopsVersionComments($moduleDirname) 
	{ 
		$ret = <<<EOT
// ------------------- Comments ------------------- //
\$modversion['comments']['pageName'] = "comments.php";
\$modversion['comments']['itemName'] = "com_id";
// Comment callback functions
\$modversion['comments']['callbackFile'] = "include/comment_functions.php";
\$modversion['comments']['callback']['approve'] = "{$moduleDirname}_com_approve";
\$modversion['comments']['callback']['update'] = "{$moduleDirname}_com_update";\n
EOT;
		return $ret;
	}
	/*
	*  @private function getXoopsVersionTemplatesAdmin
	*  @param string $moduleDirname 
	*/
	private function getXoopsVersionTemplatesAdmin($moduleDirname, $table) 
	{ 
		$tables = $this->getTables();
		$ret = <<<EOT
// ------------------- Templates ------------------- // 
// Admin
\$modversion['templates'][] = array('file' => '{$moduleDirname}_admin_about.tpl', 'description' => '', 'type' => 'admin');
\$modversion['templates'][] = array('file' => '{$moduleDirname}_admin_header.tpl', 'description' => '', 'type' => 'admin');
\$modversion['templates'][] = array('file' => '{$moduleDirname}_admin_index.tpl', 'description' => '', 'type' => 'admin');\n
EOT;
		foreach (array_keys($tables) as $t)
		{	
			$tablePermissions = $tables[$t]->getVar('table_permissions');
			$ret .= <<<EOT
\$modversion['templates'][] = array('file' => '{$moduleDirname}_admin_{$tables[$t]->getVar('table_name')}.tpl', 'description' => '', 'type' => 'admin');\n
EOT;
		}
		if (is_object($table) && $table->getVar('table_permissions') == 1) {
			$ret .= <<<EOT
\$modversion['templates'][] = array('file' => '{$moduleDirname}_admin_permissions.tpl', 'description' => '', 'type' => 'admin');\n
EOT;
		}
		$ret .= <<<EOT
\$modversion['templates'][] = array('file' => '{$moduleDirname}_admin_footer.tpl', 'description' => '', 'type' => 'admin');\n
EOT;
		return $ret;
	}
	/*
	*  @private function getXoopsVersionTemplatesUser
	*  @param string $moduleDirname 
	*/
	private function getXoopsVersionTemplatesUser($moduleDirname) 
	{ 
		$tables = $this->getTables();		
		$ret = <<<EOT
// User
\$modversion['templates'][] = array('file' => '{$moduleDirname}_header.tpl', 'description' => '');
\$modversion['templates'][] = array('file' => '{$moduleDirname}_index.tpl', 'description' => '');\n
EOT;
		foreach (array_keys($tables) as $t)
		{	
			$ret .= <<<EOT
\$modversion['templates'][] = array('file' => '{$moduleDirname}_{$tables[$t]->getVar('table_name')}.tpl', 'description' => '');\n
EOT;
		}
		$ret .= <<<EOT
\$modversion['templates'][] = array('file' => '{$moduleDirname}_footer.tpl', 'description' => '');\n
EOT;
		return $ret;
	}
	/*
	*  @private function getXoopsVersionSubmenu
	*  @param string $language 
	*/
	private function getXoopsVersionSubmenu($language) 
	{ 	
		$ret = <<<EOT
// ------------------- Submenu ------------------- //\n
EOT;
		$tables = $this->getTables();
		$i = 1;
		foreach (array_keys($tables) as $t)	
		{
			$tableName = $tables[$t]->getVar('table_name');
			if ( $tables[$t]->getVar('table_submenu') == 1 ) {
				$ret .= <<<EOT
// Sub {$tableName}
\$modversion['sub'][{$i}]['name'] = {$language}SMNAME{$i};
\$modversion['sub'][{$i}]['url'] = "{$tableName}.php";\n
EOT;
			}
			$i++;
		} 
		unset($i);
		return $ret;
	}
	/*
	*  @private function getXoopsVersionBlocks
	*  @param string $moduleDirname
	*  @param string $language
	*/
	private function getXoopsVersionBlocks($moduleDirname, $language) 
	{ 
		$tables = $this->getTables();
		$ret = <<<EOT
// ------------------- Blocks ------------------- //\n
EOT;
		foreach (array_keys($tables) as $i) {
			$tableName = $tables[$i]->getVar('table_name');			
			if ($tables[$i]->getVar('table_blocks') == 1) {            
				$language1 = $language . strtoupper($tableName);
				$ret .= <<<EOT
\$modversion['blocks'][] = array(
	'file' => "{$tableName}.php",
	'name' => {$language1}_BLOCK,
	'description' => "",
	'show_func' => "b_{$moduleDirname}_{$tableName}_show",
	'edit_func' => "b_{$moduleDirname}_{$tableName}_edit",
	'options' => "{$tables[$i]->getVar('table_fieldname')}|5|25|0",
	'template' => "'{$moduleDirname}_block_{$tableName}.tpl");\n
EOT;
			}
		}
		return $ret;
	}
	/*
	*  @private function getXoopsVersionConfig
	*  @param string $moduleDirname
	*  @param string $language
	*/
	private function getXoopsVersionConfig($module, $table, $language) 
	{ 
		$moduleDirname = $module->getVar('mod_dirname');
		$ret = <<<EOT
// ------------------- Config ------------------- //\n
EOT;
		if (is_object($table)) {	
			$fields = $this->getTableFields($table->getVar('table_id'));
			foreach (array_keys($fields) as $f) 
			{		
				if( $fields[$f]->getVar('field_element') == 4 ) {
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
		if ( $module->getVar('mod_permissions') == 1 ) {
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
    'title' => "{$language}ADMINGROUPS",
    'description' => "{$language}ADMINGROUPS_DESC",
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
		if (is_object($table)) 
		{
			foreach (array_keys($fields) as $f) 
			{		
				$fieldElement = $fields[$f]->getVar('field_element');
				if(( $fieldElement == 10 ) || ( $fieldElement == 11 ) || 
					( $fieldElement == 12 ) || ( $fieldElement == 13 ) || ( $fieldElement == 14 )) {
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
			if ($table->getVar('table_admin') == 1) {
				$ret .= <<<EOT
\$modversion['config'][] = array(
    'name' => "adminpager",
    'title' => "{$language}ADMINPAGER",
    'description' => "{$language}ADMINPAGER_DESC",
    'formtype' => "textbox",
    'valuetype' => "int",
	'default' => 10);\n\n
EOT;
			}		
			if ($table->getVar('table_user') == 1) {
				$ret .= <<<EOT
\$modversion['config'][] = array(
    'name' => "userpager",
    'title' => "{$language}USERPAGER",
    'description' => "{$language}USERPAGER_DESC",
    'formtype' => "textbox",
    'valuetype' => "int",
	'default' => 10);\n\n
EOT;
			}
		}
		if ($table->getVar('table_tag') == 1) {
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
	*  @param string $language
	*  @param string $type
	*  @param string $tableName	
	*  @param string $item
	*  @param string $typeOfNotify
	*/
	private function getTypeNotifications($language, $type = 'category', $tableName, $notifyFile, $item, $typeOfNotify) 
	{			
		$stuTableName = strtoupper($tableName);
		$stuTypeOfNotify = strtoupper($typeOfNotify);		
		switch($type) { 
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
	*  @param string $moduleDirname
	*  @param string $language
	*/
	private function getXoopsVersionNotifications($moduleDirname, $language) 
	{ 
		$notify_file = '';
		$tables = $this->getTables();
		foreach(array_keys($tables) as $t)
		{	
			$tableName = $tables[$t]->getVar('table_name');
			if($tables[$t]->getVar('table_notifications') == 1) {
				if($t < count($tableName)) {
					$notify_file .= "'".$tableName.".php', ";
				} else {
					$notify_file .= "'".$tableName.".php'";
				}
			}
		}
		$ret = <<<EOT
// ------------------- Notifications ------------------- //
\$modversion['hasNotification'] = 1;
\$modversion['notification']['lookup_file'] = 'include/notification.inc.php';
\$modversion['notification']['lookup_func'] = '{$moduleDirname}_notify_iteminfo';

\$modversion['notification']['category'][] = array(
	'name' => "global", 
	'title' => {$language}GLOBAL_NOTIFY,
	'description' => {$language}GLOBAL_NOTIFY_DESC,
	'subscribe_from' => array('index.php', {$notify_file}));

\$modversion['notification']['category'][] = array( 
	'name' => "category",
	'title' => {$language}CATEGORY_NOTIFY,
	'description' => {$language}CATEGORY_NOTIFY_DESC,
	'subscribe_from' => array({$notify_file}),
	'item_name' => "cid",
	'allow_bookmark' => 1);

\$modversion['notification']['category'][] = array( 
	'name' => "file",
	'title' => {$language}FILE_NOTIFY,
	'description' => {$language}FILE_NOTIFY_DESC,
	'subscribe_from' => "singlefile.php",
	'item_name' => "lid",
	'allow_bookmark' => 1);

\$modversion['notification']['event'][] = array( 
	'name' => "new_category",
	'category' => "global",
	'title' => {$language}GLOBAL_NEWCATEGORY_NOTIFY,
	'caption' => {$language}GLOBAL_NEWCATEGORY_NOTIFY_CAPTION,
	'description' => {$language}GLOBAL_NEWCATEGORY_NOTIFY_DESC,
	'mail_template' => "global_newcategory_notify",
	'mail_subject' => {$language}GLOBAL_NEWCATEGORY_NOTIFY_SUBJECT);

\$modversion['notification']['event'][] = array( 
	'name' => "file_modify",
	'category' => "global",
	'admin_only' => 1,
	'title' => {$language}GLOBAL_FILEMODIFY_NOTIFY,
	'caption' => {$language}GLOBAL_FILEMODIFY_NOTIFY_CAPTION,
	'description' => {$language}GLOBAL_FILEMODIFY_NOTIFY_DESC,
	'mail_template' => "global_filemodify_notify",
	'mail_subject' => {$language}GLOBAL_FILEMODIFY_NOTIFY_SUBJECT);

\$modversion['notification']['event'][] = array( 
	'name' => "file_broken",
	'category' => "global",
	'admin_only' => 1,
	'title' => {$language}GLOBAL_FILEBROKEN_NOTIFY,
	'caption' => {$language}GLOBAL_FILEBROKEN_NOTIFY_CAPTION,
	'description' => {$language}GLOBAL_FILEBROKEN_NOTIFY_DESC,
	'mail_template' => "global_filebroken_notify",
	'mail_subject' => {$language}GLOBAL_FILEBROKEN_NOTIFY_SUBJECT);

\$modversion['notification']['event'][] = array( 
	'name' => "file_submit",
	'category' => "global",
	'admin_only' => 1,
	'title' => {$language}GLOBAL_FILESUBMIT_NOTIFY,
	'caption' => {$language}GLOBAL_FILESUBMIT_NOTIFY_CAPTION,
	'description' => {$language}GLOBAL_FILESUBMIT_NOTIFY_DESC,
	'mail_template' => "global_filesubmit_notify",
	'mail_subject' => {$language}GLOBAL_FILESUBMIT_NOTIFY_SUBJECT);

\$modversion['notification']['event'][] = array( 
	'name' => "new_file",
	'category' => "global",
	'title' => {$language}GLOBAL_NEWFILE_NOTIFY,
	'caption' => {$language}GLOBAL_NEWFILE_NOTIFY_CAPTION,
	'description' => {$language}GLOBAL_NEWFILE_NOTIFY_DESC,
	'mail_template' => "global_newfile_notify",
	'mail_subject' => {$language}GLOBAL_NEWFILE_NOTIFY_SUBJECT);

\$modversion['notification']['event'][] = array( 
	'name' => "file_submit",
	'category' => "category",
	'admin_only' => 1,
	'title' => {$language}CATEGORY_FILESUBMIT_NOTIFY,
	'caption' => {$language}CATEGORY_FILESUBMIT_NOTIFY_CAPTION,
	'description' => {$language}CATEGORY_FILESUBMIT_NOTIFY_DESC,
	'mail_template' => "category_filesubmit_notify",
	'mail_subject' => {$language}CATEGORY_FILESUBMIT_NOTIFY_SUBJECT);

\$modversion['notification']['event'][] = array( 
	'name' => "new_file",
	'category' => "category",
	'title' => {$language}CATEGORY_NEWFILE_NOTIFY,
	'caption' => {$language}CATEGORY_NEWFILE_NOTIFY_CAPTION,
	'description' => {$language}CATEGORY_NEWFILE_NOTIFY_DESC,
	'mail_template' => "category_newfile_notify",
	'mail_subject' => {$language}CATEGORY_NEWFILE_NOTIFY_SUBJECT);

\$modversion['notification']['event'][] = array( 
	'name' => "approve",
	'category' => "file",
	'admin_only' => 1,
	'title' => {$language}FILE_APPROVE_NOTIFY,
	'caption' => {$language}FILE_APPROVE_NOTIFY_CAPTION,
	'description' => {$language}FILE_APPROVE_NOTIFY_DESC,
	'mail_template' => "file_approve_notify",
	'mail_subject' => {$language}FILE_APPROVE_NOTIFY_SUBJECT);
EOT;
		return $ret;
	}
	
	/*
	*  @public function render
	*  @param null
	*/
	public function render() {    
		$module = $this->getModule();
        $table = $this->getTable();        		
		$filename = $this->getFileName();
		$moduleDirname = $module->getVar('mod_dirname');		
		$language = $this->getLanguage($moduleDirname, 'MI');			
		$content = $this->getHeaderFilesComments($module, $filename);
		$content .= $this->getXoopsVersionHeader($module, $table, $language);	
		if( $module->getVar('mod_admin') == 1 ) {
			$content .= $this->getXoopsVersionTemplatesAdmin($moduleDirname, $table);
		}	
		if( $module->getVar('mod_user') == 1 ) {
			$content .= $this->getXoopsVersionTemplatesUser($moduleDirname);
		}			
		if (is_object($table)) {			
			$content .= $this->getXoopsVersionMySQL($moduleDirname,  $table);        		
			if ($table->getVar('table_search') == 1) { 	
				$content .= $this->getXoopsVersionSearch($moduleDirname);
			}
			if ($table->getVar('table_comments') == 1) { 
				$content .= $this->getXoopsVersionComments($moduleDirname);
			}
			
			if ($table->getVar('table_submenu') == 1) { 
				$content .= $this->getXoopsVersionSubmenu($language);
			}
			if ($table->getVar('table_blocks') == 1) { 
				$content .= $this->getXoopsVersionBlocks($moduleDirname, $language);
			}			
		}
		$content .= $this->getXoopsVersionConfig($module, $table, $language);
		if (is_object($table)) {	
			if ($table->getVar('table_notifications') == 1) {	
				$content .= $this->getXoopsVersionNotifications($moduleDirname, $language, $filename);
			}
		}
		$this->tdmcfile->create($moduleDirname, '/', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);
		return $this->tdmcfile->renderFile();
	}
}