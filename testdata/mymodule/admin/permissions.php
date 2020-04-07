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
 * My Module module for xoops
 *
 * @copyright     2020 XOOPS Project (https://xooops.org)
 * @license        GPL 2.0 or later
 * @package        mymodule
 * @since          1.0
 * @min_xoops      2.5.9
 * @author         TDM XOOPS - Email:<info@email.com> - Website:<http://xoops.org>
 */

use Xmf\Request;
use XoopsModules\Mymodule;
use XoopsModules\Mymodule\Constants;

require __DIR__ . '/header.php';

// Template Index
$templateMain = 'mymodule_admin_permissions.tpl';
$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('permissions.php'));

$op = Request::getString('op', 'global');

// Get handler
$categoriesHandler = $helper->getHandler('categories');

// Get Form
include_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';
xoops_load('XoopsFormLoader');
$permTableForm = new \XoopsSimpleForm('', 'fselperm', 'permissions.php', 'post');
$formSelect = new \XoopsFormSelect('', 'op', $op);
$formSelect->setExtra('onchange="document.fselperm.submit()"');
$formSelect->addOption('global', _AM_MYMODULE_PERMISSIONS_GLOBAL);
$formSelect->addOption('approve', _AM_MYMODULE_PERMISSIONS_APPROVE);
$formSelect->addOption('submit', _AM_MYMODULE_PERMISSIONS_SUBMIT);
$formSelect->addOption('view', _AM_MYMODULE_PERMISSIONS_VIEW);
$permTableForm->addElement($formSelect);
$permTableForm->display();
switch($op) {
	case 'global':
	default:
		$formTitle = _AM_MYMODULE_PERMISSIONS_GLOBAL;
		$permName = 'mymodule_ac';
		$permDesc = _AM_MYMODULE_PERMISSIONS_GLOBAL_DESC;
		$globalPerms = array( '4' => _AM_MYMODULE_PERMISSIONS_GLOBAL_4, '8' => _AM_MYMODULE_PERMISSIONS_GLOBAL_8, '16' => _AM_MYMODULE_PERMISSIONS_GLOBAL_16 );
	break;
	case 'approve':
		$formTitle = _AM_MYMODULE_PERMISSIONS_APPROVE;
		$permName = 'mymodule_approve';
		$permDesc = _AM_MYMODULE_PERMISSIONS_APPROVE_DESC;
	break;
	case 'submit':
		$formTitle = _AM_MYMODULE_PERMISSIONS_SUBMIT;
		$permName = 'mymodule_submit';
		$permDesc = _AM_MYMODULE_PERMISSIONS_SUBMIT_DESC;
	break;
	case 'view':
		$formTitle = _AM_MYMODULE_PERMISSIONS_VIEW;
		$permName = 'mymodule_view';
		$permDesc = _AM_MYMODULE_PERMISSIONS_VIEW_DESC;
	break;
}
$moduleId = $xoopsModule->getVar('mid');
$permform = new \XoopsGroupPermForm($formTitle, $moduleId, $permName, $permDesc, 'admin/permissions.php');
if($op === 'global') {
	foreach($globalPerms as $gPermId => $gPermName) {
		$permform->addItem($gPermId, $gPermName);
	}
	$GLOBALS['xoopsTpl']->assign('form', $permform->render());
} else {
	$categoriesCount = $categoriesHandler->getCountCategories();
	if($categoriesCount > 0) {
		$categoriesAll = $categoriesHandler->getAllCategories(0, 'cat_name');
		foreach(array_keys($categoriesAll) as $i) {
			$permform->addItem($categoriesAll[$i]->getVar('cat_id'), $categoriesAll[$i]->getVar('cat_name'));
		}
		$GLOBALS['xoopsTpl']->assign('form', $permform->render());
	} else {
		redirect_header('categories.php?op=new', 3, _AM_MYMODULE_NO_PERMISSIONS_SET);
		exit();
	}
}
unset($permform);
require __DIR__ . '/footer.php';
