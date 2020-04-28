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
 * My Module 3 module for xoops
 *
 * @copyright     2020 XOOPS Project (https://xooops.org)
 * @license        GPL 2.0 or later
 * @package        mymodule3
 * @since          1.0
 * @min_xoops      2.5.9
 * @author         TDM XOOPS - Email:<info@email.com> - Website:<http://xoops.org>
 */

// 
$moduleDirName      = basename(__DIR__);
$moduleDirNameUpper = mb_strtoupper($moduleDirName);
// ------------------- Informations ------------------- //
$modversion = [
	'name'                => _MI_MYMODULE3_NAME,
	'version'             => 1.0,
	'description'         => _MI_MYMODULE3_DESC,
	'author'              => 'TDM XOOPS',
	'author_mail'         => 'info@email.com',
	'author_website_url'  => 'http://xoops.org',
	'author_website_name' => 'XOOPS Project',
	'credits'             => 'XOOPS Development Team',
	'license'             => 'GPL 2.0 or later',
	'license_url'         => 'http://www.gnu.org/licenses/gpl-3.0.en.html',
	'help'                => 'page=help',
	'release_info'        => 'release_info',
	'release_file'        => XOOPS_URL . '/modules/mymodule3/docs/release_info file',
	'release_date'        => '2020/04/28',
	'manual'              => 'link to manual file',
	'manual_file'         => XOOPS_URL . '/modules/mymodule3/docs/install.txt',
	'min_php'             => '7.0',
	'min_xoops'           => '2.5.9',
	'min_admin'           => '1.2',
	'min_db'              => array('mysql' => '5.6', 'mysqli' => '5.6'),
	'image'               => 'assets/images/logoModule.png',
	'dirname'             => basename(__DIR__),
	'dirmoduleadmin'      => 'Frameworks/moduleclasses/moduleadmin',
	'sysicons16'          => '../../Frameworks/moduleclasses/icons/16',
	'sysicons32'          => '../../Frameworks/moduleclasses/icons/32',
	'modicons16'          => 'assets/icons/16',
	'modicons32'          => 'assets/icons/32',
	'demo_site_url'       => 'https://xoops.org',
	'demo_site_name'      => 'XOOPS Demo Site',
	'support_url'         => 'https://xoops.org/modules/newbb',
	'support_name'        => 'Support Forum',
	'module_website_url'  => 'www.xoops.org',
	'module_website_name' => 'XOOPS Project',
	'release'             => '2017-12-02',
	'module_status'       => 'Beta 1',
	'system_menu'         => 1,
	'hasAdmin'            => 1,
	'hasMain'             => 1,
	'adminindex'          => 'admin/index.php',
	'adminmenu'           => 'admin/menu.php',
	'onInstall'           => 'include/install.php',
	'onUninstall'         => 'include/uninstall.php',
	'onUpdate'            => 'include/update.php',
];
// ------------------- Templates ------------------- //
$modversion['templates'] = [
	// Admin templates
	['file' => 'mymodule3_admin_about.tpl', 'description' => '', 'type' => 'admin'],
	['file' => 'mymodule3_admin_header.tpl', 'description' => '', 'type' => 'admin'],
	['file' => 'mymodule3_admin_index.tpl', 'description' => '', 'type' => 'admin'],
	['file' => 'mymodule3_admin_categories.tpl', 'description' => '', 'type' => 'admin'],
	['file' => 'mymodule3_admin_articles.tpl', 'description' => '', 'type' => 'admin'],
	['file' => 'mymodule3_admin_testfields.tpl', 'description' => '', 'type' => 'admin'],
	['file' => 'mymodule3_admin_permissions.tpl', 'description' => '', 'type' => 'admin'],
	['file' => 'mymodule3_admin_footer.tpl', 'description' => '', 'type' => 'admin'],
	// User templates
	['file' => 'mymodule3_header.tpl', 'description' => ''],
	['file' => 'mymodule3_index.tpl', 'description' => ''],
	['file' => 'mymodule3_categories.tpl', 'description' => ''],
	['file' => 'mymodule3_categories_list.tpl', 'description' => ''],
	['file' => 'mymodule3_articles.tpl', 'description' => ''],
	['file' => 'mymodule3_articles_list.tpl', 'description' => ''],
	['file' => 'mymodule3_testfields.tpl', 'description' => ''],
	['file' => 'mymodule3_testfields_list.tpl', 'description' => ''],
	['file' => 'mymodule3_breadcrumbs.tpl', 'description' => ''],
	['file' => 'mymodule3_broken.tpl', 'description' => ''],
	['file' => 'mymodule3_pdf.tpl', 'description' => ''],
	['file' => 'mymodule3_print.tpl', 'description' => ''],
	['file' => 'mymodule3_rate.tpl', 'description' => ''],
	['file' => 'mymodule3_rss.tpl', 'description' => ''],
	['file' => 'mymodule3_search.tpl', 'description' => ''],
	['file' => 'mymodule3_single.tpl', 'description' => ''],
	['file' => 'mymodule3_submit.tpl', 'description' => ''],
	['file' => 'mymodule3_footer.tpl', 'description' => ''],
];
// ------------------- Mysql ------------------- //
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
// Tables
$modversion['tables'] = [
	'mymodule3_categories',
	'mymodule3_articles',
	'mymodule3_testfields',
];
// ------------------- Search ------------------- //
$modversion['hasSearch'] = 1;
$modversion['search'] = [
	'file' => 'include/search.inc.php',
	'func' => 'mymodule3_search',
];
// ------------------- Comments ------------------- //
$modversion['comments']['pageName'] = 'comments.php';
$modversion['comments']['itemName'] = 'com_id';
// Comment callback functions
$modversion['comments']['callbackFile'] = 'include/comment_functions.php';
$modversion['comments']['callback'] = [
	'approve' => 'mymodule3CommentsApprove',
	'update'  => 'mymodule3CommentsUpdate',
];
// ------------------- Menu ------------------- //
$currdirname  = isset($GLOBALS['xoopsModule']) && is_object($GLOBALS['xoopsModule']) ? $GLOBALS['xoopsModule']->getVar('dirname') : 'system';
if ($moduleDirName == $currdirname) {
	$modversion['sub'][] = [
		'name' => _MI_MYMODULE3_SMNAME1,
		'url'  => 'index.php',
	];
	// Sub articles
	$modversion['sub'][] = [
		'name' => _MI_MYMODULE3_SMNAME2,
		'url'  => 'articles.php',
	];
	// Sub testfields
	$modversion['sub'][] = [
		'name' => _MI_MYMODULE3_SMNAME3,
		'url'  => 'testfields.php',
	];
	// Sub Submit
	$modversion['sub'][] = [
		'name' => _MI_MYMODULE3_SMNAME4,
		'url'  => 'submit.php',
	];
}
// ------------------- Blocks ------------------- //
// Articles last
$modversion['blocks'][] = [
	'file'        => 'articles.php',
	'name'        => _MI_MYMODULE3_ARTICLES_BLOCK_LAST,
	'description' => _MI_MYMODULE3_ARTICLES_BLOCK_LAST_DESC,
	'show_func'   => 'b_mymodule3_articles_show',
	'edit_func'   => 'b_mymodule3_articles_edit',
	'template'    => 'mymodule3_block_articles.tpl',
	'options'     => 'last|5|25|0',
];
// Articles new
$modversion['blocks'][] = [
	'file'        => 'articles.php',
	'name'        => _MI_MYMODULE3_ARTICLES_BLOCK_NEW,
	'description' => _MI_MYMODULE3_ARTICLES_BLOCK_NEW_DESC,
	'show_func'   => 'b_mymodule3_articles_show',
	'edit_func'   => 'b_mymodule3_articles_edit',
	'template'    => 'mymodule3_block_articles.tpl',
	'options'     => 'new|5|25|0',
];
// Articles hits
$modversion['blocks'][] = [
	'file'        => 'articles.php',
	'name'        => _MI_MYMODULE3_ARTICLES_BLOCK_HITS,
	'description' => _MI_MYMODULE3_ARTICLES_BLOCK_HITS_DESC,
	'show_func'   => 'b_mymodule3_articles_show',
	'edit_func'   => 'b_mymodule3_articles_edit',
	'template'    => 'mymodule3_block_articles.tpl',
	'options'     => 'hits|5|25|0',
];
// Articles top
$modversion['blocks'][] = [
	'file'        => 'articles.php',
	'name'        => _MI_MYMODULE3_ARTICLES_BLOCK_TOP,
	'description' => _MI_MYMODULE3_ARTICLES_BLOCK_TOP_DESC,
	'show_func'   => 'b_mymodule3_articles_show',
	'edit_func'   => 'b_mymodule3_articles_edit',
	'template'    => 'mymodule3_block_articles.tpl',
	'options'     => 'top|5|25|0',
];
// Articles random
$modversion['blocks'][] = [
	'file'        => 'articles.php',
	'name'        => _MI_MYMODULE3_ARTICLES_BLOCK_RANDOM,
	'description' => _MI_MYMODULE3_ARTICLES_BLOCK_RANDOM_DESC,
	'show_func'   => 'b_mymodule3_articles_show',
	'edit_func'   => 'b_mymodule3_articles_edit',
	'template'    => 'mymodule3_block_articles.tpl',
	'options'     => 'random|5|25|0',
];
// Testfields last
$modversion['blocks'][] = [
	'file'        => 'testfields.php',
	'name'        => _MI_MYMODULE3_TESTFIELDS_BLOCK_LAST,
	'description' => _MI_MYMODULE3_TESTFIELDS_BLOCK_LAST_DESC,
	'show_func'   => 'b_mymodule3_testfields_show',
	'edit_func'   => 'b_mymodule3_testfields_edit',
	'template'    => 'mymodule3_block_testfields.tpl',
	'options'     => 'last|5|25|0',
];
// Testfields new
$modversion['blocks'][] = [
	'file'        => 'testfields.php',
	'name'        => _MI_MYMODULE3_TESTFIELDS_BLOCK_NEW,
	'description' => _MI_MYMODULE3_TESTFIELDS_BLOCK_NEW_DESC,
	'show_func'   => 'b_mymodule3_testfields_show',
	'edit_func'   => 'b_mymodule3_testfields_edit',
	'template'    => 'mymodule3_block_testfields.tpl',
	'options'     => 'new|5|25|0',
];
// Testfields hits
$modversion['blocks'][] = [
	'file'        => 'testfields.php',
	'name'        => _MI_MYMODULE3_TESTFIELDS_BLOCK_HITS,
	'description' => _MI_MYMODULE3_TESTFIELDS_BLOCK_HITS_DESC,
	'show_func'   => 'b_mymodule3_testfields_show',
	'edit_func'   => 'b_mymodule3_testfields_edit',
	'template'    => 'mymodule3_block_testfields.tpl',
	'options'     => 'hits|5|25|0',
];
// Testfields top
$modversion['blocks'][] = [
	'file'        => 'testfields.php',
	'name'        => _MI_MYMODULE3_TESTFIELDS_BLOCK_TOP,
	'description' => _MI_MYMODULE3_TESTFIELDS_BLOCK_TOP_DESC,
	'show_func'   => 'b_mymodule3_testfields_show',
	'edit_func'   => 'b_mymodule3_testfields_edit',
	'template'    => 'mymodule3_block_testfields.tpl',
	'options'     => 'top|5|25|0',
];
// Testfields random
$modversion['blocks'][] = [
	'file'        => 'testfields.php',
	'name'        => _MI_MYMODULE3_TESTFIELDS_BLOCK_RANDOM,
	'description' => _MI_MYMODULE3_TESTFIELDS_BLOCK_RANDOM_DESC,
	'show_func'   => 'b_mymodule3_testfields_show',
	'edit_func'   => 'b_mymodule3_testfields_edit',
	'template'    => 'mymodule3_block_testfields.tpl',
	'options'     => 'random|5|25|0',
];
// ------------------- Config ------------------- //
// Editor descr
xoops_load('xoopseditorhandler');
$editorHandlerDescr = XoopsEditorHandler::getInstance();
$modversion['config'][] = [
	'name'        => 'editor_descr',
	'title'       => '_MI_MYMODULE3_EDITOR_DESCR',
	'description' => '_MI_MYMODULE3_EDITOR_DESCR_DESC',
	'formtype'    => 'select',
	'valuetype'   => 'text',
	'default'     => 'dhtml',
	'options'     => array_flip($editorHandlerDescr->getList()),
];
// Editor textarea
xoops_load('xoopseditorhandler');
$editorHandlerTextarea = XoopsEditorHandler::getInstance();
$modversion['config'][] = [
	'name'        => 'editor_textarea',
	'title'       => '_MI_MYMODULE3_EDITOR_TEXTAREA',
	'description' => '_MI_MYMODULE3_EDITOR_TEXTAREA_DESC',
	'formtype'    => 'select',
	'valuetype'   => 'text',
	'default'     => 'dhtml',
	'options'     => array_flip($editorHandlerTextarea->getList()),
];
// Editor dhtml
xoops_load('xoopseditorhandler');
$editorHandlerDhtml = XoopsEditorHandler::getInstance();
$modversion['config'][] = [
	'name'        => 'editor_dhtml',
	'title'       => '_MI_MYMODULE3_EDITOR_DHTML',
	'description' => '_MI_MYMODULE3_EDITOR_DHTML_DESC',
	'formtype'    => 'select',
	'valuetype'   => 'text',
	'default'     => 'dhtml',
	'options'     => array_flip($editorHandlerDhtml->getList()),
];
// Get groups
$memberHandler = xoops_getHandler('member');
$xoopsGroups  = $memberHandler->getGroupList();
$groups = [];
foreach($xoopsGroups as $key => $group) {
	$groups[$group]  = $key;
}
// General access groups
$modversion['config'][] = [
	'name'        => 'groups',
	'title'       => '_MI_MYMODULE3_GROUPS',
	'description' => '_MI_MYMODULE3_GROUPS_DESC',
	'formtype'    => 'select_multi',
	'valuetype'   => 'array',
	'default'     => $groups,
	'options'     => $groups,
];
// Upload groups
$modversion['config'][] = [
	'name'        => 'upload_groups',
	'title'       => '_MI_MYMODULE3_UPLOAD_GROUPS',
	'description' => '_MI_MYMODULE3_UPLOAD_GROUPS_DESC',
	'formtype'    => 'select_multi',
	'valuetype'   => 'array',
	'default'     => $groups,
	'options'     => $groups,
];
// Get Admin groups
$criteria  = new \CriteriaCompo();
$criteria->add( new \Criteria( 'group_type', 'Admin' ) );
$memberHandler = xoops_getHandler('member');
$adminXoopsGroups  = $memberHandler->getGroupList($criteria);
$adminGroups = [];
foreach($adminXoopsGroups as $key => $adminGroup) {
	$adminGroups[$adminGroup]  = $key;
}
$modversion['config'][] = [
	'name'        => 'admin_groups',
	'title'       => '_MI_MYMODULE3_ADMIN_GROUPS',
	'description' => '_MI_MYMODULE3_ADMIN_GROUPS_DESC',
	'formtype'    => 'select_multi',
	'valuetype'   => 'array',
	'default'     => $adminGroups,
	'options'     => $adminGroups,
];
// Keywords
$modversion['config'][] = [
	'name'        => 'keywords',
	'title'       => '_MI_MYMODULE3_KEYWORDS',
	'description' => '_MI_MYMODULE3_KEYWORDS_DESC',
	'formtype'    => 'textbox',
	'valuetype'   => 'text',
	'default'     => 'mymodule3, categories, articles, testfields',
];
// create increment steps for file size
include_once __DIR__ . '/include/xoops_version.inc.php';
$iniPostMaxSize       = mymodule3ReturnBytes(ini_get('post_max_size'));
$iniUploadMaxFileSize = mymodule3ReturnBytes(ini_get('upload_max_filesize'));
$maxSize              = min($iniPostMaxSize, $iniUploadMaxFileSize);
if ($maxSize > 10000 * 1048576) {
	$increment = 500;
}
if ($maxSize <= 10000 * 1048576) {
	$increment = 200;
}
if ($maxSize <= 5000 * 1048576) {
	$increment = 100;
}
if ($maxSize <= 2500 * 1048576) {
	$increment = 50;
}
if ($maxSize <= 1000 * 1048576) {
	$increment = 10;
}
if ($maxSize <= 500 * 1048576) {
	$increment = 5;
}
if ($maxSize <= 100 * 1048576) {
	$increment = 2;
}
if ($maxSize <= 50 * 1048576) {
	$increment = 1;
}
if ($maxSize <= 25 * 1048576) {
	$increment = 0.5;
}
$optionMaxsize = [];
$i = $increment;
while ($i * 1048576  <=  $maxSize) {
	$optionMaxsize[$i . ' ' . _MI_MYMODULE3_SIZE_MB] = $i * 1048576;
	$i += $increment;
}
// Uploads : maxsize of image
$modversion['config'][] = [
	'name'        => 'maxsize_image',
	'title'       => '_MI_MYMODULE3_MAXSIZE_IMAGE',
	'description' => '_MI_MYMODULE3_MAXSIZE_IMAGE_DESC',
	'formtype'    => 'select',
	'valuetype'   => 'int',
	'default'     => 3145728,
	'options'     => $optionMaxsize,
];
// Uploads : mimetypes of image
$modversion['config'][] = [
	'name'        => 'mimetypes_image',
	'title'       => '_MI_MYMODULE3_MIMETYPES_IMAGE',
	'description' => '_MI_MYMODULE3_MIMETYPES_IMAGE_DESC',
	'formtype'    => 'select_multi',
	'valuetype'   => 'array',
	'default'     => ['image/gif', 'image/jpeg', 'image/png'],
	'options'     => ['bmp' => 'image/bmp','gif' => 'image/gif','pjpeg' => 'image/pjpeg', 'jpeg' => 'image/jpeg','jpg' => 'image/jpg','jpe' => 'image/jpe', 'png' => 'image/png'],
];
$modversion['config'][] = [
	'name'        => 'maxwidth_image',
	'title'       => '_MI_MYMODULE3_MAXWIDTH_IMAGE',
	'description' => '_MI_MYMODULE3_MAXWIDTH_IMAGE_DESC',
	'formtype'    => 'textbox',
	'valuetype'   => 'int',
	'default'     => 8000,
];
$modversion['config'][] = [
	'name'        => 'maxheight_image',
	'title'       => '_MI_MYMODULE3_MAXHEIGHT_IMAGE',
	'description' => '_MI_MYMODULE3_MAXHEIGHT_IMAGE_DESC',
	'formtype'    => 'textbox',
	'valuetype'   => 'int',
	'default'     => 8000,
];
// Uploads : maxsize of file
$modversion['config'][] = [
	'name'        => 'maxsize_file',
	'title'       => '_MI_MYMODULE3_MAXSIZE_FILE',
	'description' => '_MI_MYMODULE3_MAXSIZE_FILE_DESC',
	'formtype'    => 'select',
	'valuetype'   => 'int',
	'default'     => 3145728,
	'options'     => $optionMaxsize,
];
// Uploads : mimetypes of file
$modversion['config'][] = [
	'name'        => 'mimetypes_file',
	'title'       => '_MI_MYMODULE3_MIMETYPES_FILE',
	'description' => '_MI_MYMODULE3_MIMETYPES_FILE_DESC',
	'formtype'    => 'select_multi',
	'valuetype'   => 'array',
	'default'     => ['application/pdf', 'application/zip', 'text/comma-separated-values', 'text/plain', 'image/gif', 'image/jpeg', 'image/png'],
	'options'     => ['gif' => 'image/gif','pjpeg' => 'image/pjpeg', 'jpeg' => 'image/jpeg','jpg' => 'image/jpg','jpe' => 'image/jpe', 'png' => 'image/png', 'pdf' => 'application/pdf','zip' => 'application/zip','csv' => 'text/comma-separated-values', 'txt' => 'text/plain', 'xml' => 'application/xml', 'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
];
// Admin pager
$modversion['config'][] = [
	'name'        => 'adminpager',
	'title'       => '_MI_MYMODULE3_ADMIN_PAGER',
	'description' => '_MI_MYMODULE3_ADMIN_PAGER_DESC',
	'formtype'    => 'textbox',
	'valuetype'   => 'int',
	'default'     => 10,
];
// User pager
$modversion['config'][] = [
	'name'        => 'userpager',
	'title'       => '_MI_MYMODULE3_USER_PAGER',
	'description' => '_MI_MYMODULE3_USER_PAGER_DESC',
	'formtype'    => 'textbox',
	'valuetype'   => 'int',
	'default'     => 10,
];
// Use tag
$modversion['config'][] = [
	'name'        => 'usetag',
	'title'       => '_MI_MYMODULE3_USE_TAG',
	'description' => '_MI_MYMODULE3_USE_TAG_DESC',
	'formtype'    => 'yesno',
	'valuetype'   => 'int',
	'default'     => 0,
];
// Number column
$modversion['config'][] = [
	'name'        => 'numb_col',
	'title'       => '_MI_MYMODULE3_NUMB_COL',
	'description' => '_MI_MYMODULE3_NUMB_COL_DESC',
	'formtype'    => 'select',
	'valuetype'   => 'int',
	'default'     => 1,
	'options'     => [1 => '1', 2 => '2', 3 => '3', 4 => '4'],
];
// Divide by
$modversion['config'][] = [
	'name'        => 'divideby',
	'title'       => '_MI_MYMODULE3_DIVIDEBY',
	'description' => '_MI_MYMODULE3_DIVIDEBY_DESC',
	'formtype'    => 'select',
	'valuetype'   => 'int',
	'default'     => 1,
	'options'     => [1 => '1', 2 => '2', 3 => '3', 4 => '4'],
];
// Table type
$modversion['config'][] = [
	'name'        => 'table_type',
	'title'       => '_MI_MYMODULE3_TABLE_TYPE',
	'description' => '_MI_MYMODULE3_DIVIDEBY_DESC',
	'formtype'    => 'select',
	'valuetype'   => 'int',
	'default'     => 'bordered',
	'options'     => ['bordered' => 'bordered', 'striped' => 'striped', 'hover' => 'hover', 'condensed' => 'condensed'],
];
// Panel by
$modversion['config'][] = [
	'name'        => 'panel_type',
	'title'       => '_MI_MYMODULE3_PANEL_TYPE',
	'description' => '_MI_MYMODULE3_PANEL_TYPE_DESC',
	'formtype'    => 'select',
	'valuetype'   => 'text',
	'default'     => 'default',
	'options'     => ['default' => 'default', 'primary' => 'primary', 'success' => 'success', 'info' => 'info', 'warning' => 'warning', 'danger' => 'danger'],
];
// Advertise
$modversion['config'][] = [
	'name'        => 'advertise',
	'title'       => '_MI_MYMODULE3_ADVERTISE',
	'description' => '_MI_MYMODULE3_ADVERTISE_DESC',
	'formtype'    => 'textarea',
	'valuetype'   => 'text',
	'default'     => '',
];
// Bookmarks
$modversion['config'][] = [
	'name'        => 'bookmarks',
	'title'       => '_MI_MYMODULE3_BOOKMARKS',
	'description' => '_MI_MYMODULE3_BOOKMARKS_DESC',
	'formtype'    => 'yesno',
	'valuetype'   => 'int',
	'default'     => 0,
];
// Make Sample button visible?
$modversion['config'][] = [
	'name'        => 'displaySampleButton',
	'title'       => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLE_BUTTON',
	'description' => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLE_BUTTON_DESC',
	'formtype'    => 'yesno',
	'valuetype'   => 'int',
	'default'     => 1,
];
// Maintained by
$modversion['config'][] = [
	'name'        => 'maintainedby',
	'title'       => '_MI_MYMODULE3_MAINTAINEDBY',
	'description' => '_MI_MYMODULE3_MAINTAINEDBY_DESC',
	'formtype'    => 'textbox',
	'valuetype'   => 'text',
	'default'     => 'https://xoops.org/modules/newbb',
];
// ------------------- Notifications ------------------- //
$modversion['hasNotification'] = 1;
$modversion['notification'] = [
	'lookup_file' => 'include/notification.inc.php',
	'lookup_func' => 'mymodule3_notify_iteminfo',
];
// Global Notify
$modversion['notification']['category'][1] = [
	'name'           => 'global',
	'title'          => _MI_MYMODULE3_GLOBAL_NOTIFY,
	'description'    => _MI_MYMODULE3_GLOBAL_NOTIFY_DESC,
	'subscribe_from' => ['index.php', 'articles.php', 'testfields.php'],
];
// Category Notify
$modversion['notification']['category'][2] = [
	'name'           => 'category',
	'title'          => _MI_MYMODULE3_CATEGORY_NOTIFY,
	'description'    => _MI_MYMODULE3_CATEGORY_NOTIFY_DESC,
	'subscribe_from' => ['articles.php', 'testfields.php'],
	'item_name'      => 'tf_combobox',
	'allow_bookmark' => 1,
];
// Testfield Notify
$modversion['notification']['category'][3] = [
	'name'           => 'testfield',
	'title'          => _MI_MYMODULE3_TESTFIELD_NOTIFY,
	'description'    => _MI_MYMODULE3_TESTFIELD_NOTIFY_DESC,
	'subscribe_from' => 'testfields.php',
	'item_name'      => 'tf_id',
	'allow_bookmark' => 1,
];
// GLOBAL_NEW_CATEGORY Notify
$modversion['notification']['event'][1] = [
	'name'          => 'new_category',
	'category'      => 'global',
	'admin_only'    => 0,
	'title'         => _MI_MYMODULE3_GLOBAL_NEW_CATEGORY_NOTIFY,
	'caption'       => _MI_MYMODULE3_GLOBAL_NEW_CATEGORY_NOTIFY_CAPTION,
	'description'   => _MI_MYMODULE3_GLOBAL_NEW_CATEGORY_NOTIFY_DESC,
	'mail_template' => 'global_newcategory_notify',
	'mail_subject'  => _MI_MYMODULE3_GLOBAL_NEW_CATEGORY_NOTIFY_SUBJECT,
];
// GLOBAL_MODIFY Notify
$modversion['notification']['event'][2] = [
	'name'          => 'modify',
	'category'      => 'global',
	'admin_only'    => 1,
	'title'         => _MI_MYMODULE3_GLOBAL_MODIFY_NOTIFY,
	'caption'       => _MI_MYMODULE3_GLOBAL_MODIFY_NOTIFY_CAPTION,
	'description'   => _MI_MYMODULE3_GLOBAL_MODIFY_NOTIFY_DESC,
	'mail_template' => 'global_modify_notify',
	'mail_subject'  => _MI_MYMODULE3_GLOBAL_MODIFY_NOTIFY_SUBJECT,
];
// GLOBAL_BROKEN Notify
$modversion['notification']['event'][3] = [
	'name'          => 'broken',
	'category'      => 'global',
	'admin_only'    => 1,
	'title'         => _MI_MYMODULE3_GLOBAL_BROKEN_NOTIFY,
	'caption'       => _MI_MYMODULE3_GLOBAL_BROKEN_NOTIFY_CAPTION,
	'description'   => _MI_MYMODULE3_GLOBAL_BROKEN_NOTIFY_DESC,
	'mail_template' => 'global_broken_notify',
	'mail_subject'  => _MI_MYMODULE3_GLOBAL_BROKEN_NOTIFY_SUBJECT,
];
// GLOBAL_SUBMIT Notify
$modversion['notification']['event'][4] = [
	'name'          => 'submit',
	'category'      => 'global',
	'admin_only'    => 1,
	'title'         => _MI_MYMODULE3_GLOBAL_SUBMIT_NOTIFY,
	'caption'       => _MI_MYMODULE3_GLOBAL_SUBMIT_NOTIFY_CAPTION,
	'description'   => _MI_MYMODULE3_GLOBAL_SUBMIT_NOTIFY_DESC,
	'mail_template' => 'global_submit_notify',
	'mail_subject'  => _MI_MYMODULE3_GLOBAL_SUBMIT_NOTIFY_SUBJECT,
];
// GLOBAL_NEW Notify
$modversion['notification']['event'][5] = [
	'name'          => 'new_testfield',
	'category'      => 'global',
	'admin_only'    => 0,
	'title'         => _MI_MYMODULE3_GLOBAL_NEW_NOTIFY,
	'caption'       => _MI_MYMODULE3_GLOBAL_NEW_NOTIFY_CAPTION,
	'description'   => _MI_MYMODULE3_GLOBAL_NEW_NOTIFY_DESC,
	'mail_template' => 'global_newtestfield_notify',
	'mail_subject'  => _MI_MYMODULE3_GLOBAL_NEW_NOTIFY_SUBJECT,
];
// CATEGORY_SUBMIT Notify
$modversion['notification']['event'][6] = [
	'name'          => 'submit',
	'category'      => 'category',
	'admin_only'    => 1,
	'title'         => _MI_MYMODULE3_CATEGORY_SUBMIT_NOTIFY,
	'caption'       => _MI_MYMODULE3_CATEGORY_SUBMIT_NOTIFY_CAPTION,
	'description'   => _MI_MYMODULE3_CATEGORY_SUBMIT_NOTIFY_DESC,
	'mail_template' => 'category_testfieldsubmit_notify',
	'mail_subject'  => _MI_MYMODULE3_CATEGORY_SUBMIT_NOTIFY_SUBJECT,
];
// CATEGORY Notify
$modversion['notification']['event'][7] = [
	'name'          => 'new_category',
	'category'      => 'category',
	'admin_only'    => 0,
	'title'         => _MI_MYMODULE3_CATEGORY_NOTIFY,
	'caption'       => _MI_MYMODULE3_CATEGORY_NOTIFY_CAPTION,
	'description'   => _MI_MYMODULE3_CATEGORY_NOTIFY_DESC,
	'mail_template' => 'category_newtestfield_notify',
	'mail_subject'  => _MI_MYMODULE3_CATEGORY_NOTIFY_SUBJECT,
];
// TESTFIELD Notify
$modversion['notification']['event'][8] = [
	'name'          => 'approve',
	'category'      => 'testfield',
	'admin_only'    => 1,
	'title'         => _MI_MYMODULE3_TESTFIELD_NOTIFY,
	'caption'       => _MI_MYMODULE3_TESTFIELD_NOTIFY_CAPTION,
	'description'   => _MI_MYMODULE3_TESTFIELD_NOTIFY_DESC,
	'mail_template' => 'testfield_approve_notify',
	'mail_subject'  => _MI_MYMODULE3_TESTFIELD_NOTIFY_SUBJECT,
];
