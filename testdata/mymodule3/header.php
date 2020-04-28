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
include dirname(dirname(__DIR__)) . '/mainfile.php';
include __DIR__ . '/include/common.php';
$moduleDirName = basename(__DIR__);
// Breadcrumbs
$xoBreadcrumbs = [];
$xoBreadcrumbs[] = ['title' => _MA_MYMODULE3_TITLE, 'link' => MYMODULE3_URL . '/'];
// Get instance of module
$helper = \XoopsModules\Mymodule3\Helper::getInstance();
$categoriesHandler = $helper->getHandler('categories');
$articlesHandler = $helper->getHandler('articles');
$testfieldsHandler = $helper->getHandler('testfields');
// Permission
include_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';
$grouppermHandler = xoops_getHandler('groupperm');
if (is_object($xoopsUser)) {
	$groups  = $xoopsUser->getGroups();
} else {
	$groups  = XOOPS_GROUP_ANONYMOUS;
}
// 
$myts = MyTextSanitizer::getInstance();
// Default Css Style
$style = MYMODULE3_URL . '/assets/css/style.css';
if (!file_exists($style)) {
	return false;
}
// Smarty Default
$sysPathIcon16 = $GLOBALS['xoopsModule']->getInfo('sysicons16');
$sysPathIcon32 = $GLOBALS['xoopsModule']->getInfo('sysicons32');
$pathModuleAdmin = $GLOBALS['xoopsModule']->getInfo('dirmoduleadmin');
$modPathIcon16 = $GLOBALS['xoopsModule']->getInfo('modicons16');
$modPathIcon32 = $GLOBALS['xoopsModule']->getInfo('modicons16');
// Load Languages
xoops_loadLanguage('main');
xoops_loadLanguage('modinfo');
