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

use Xmf\Request;
use XoopsModules\Mymodule3;
use XoopsModules\Mymodule3\Constants;

require __DIR__ . '/header.php';
$tfId = Request::getInt('tf_id');
// Define Stylesheet
$GLOBALS['xoTheme']->addStylesheet( $style, null );
if (empty($tfId)) {
	redirect_header(MYMODULE3_URL . '/index.php', 2, _MA_MYMODULE3_NOTFID);
}
// Get Instance of Handler
$testfieldsHandler = $helper->getHandler('testfields');
// Verify that the article is published
$testfields = $testfieldsHandler->get($tfId);
// Verify permissions
if (!$grouppermHandler->checkRight('mymodule3_view', $tfId->getVar('tf_id'), $groups, $GLOBALS['xoopsModule']->getVar('mid'))) {
	redirect_header(MYMODULE3_URL . '/index.php', 3, _NOPERM);
	exit();
}
$testfield = $testfields->getValuesTestfields();
foreach($testfield as $k => $v) {
	$GLOBALS['xoopsTpl']->append('"{$k}"', $v);
}
$GLOBALS['xoopsTpl']->assign('xoops_sitename', $GLOBALS['xoopsConfig']['sitename']);
$GLOBALS['xoopsTpl']->assign('xoops_pagetitle', strip_tags($testfield->getVar('tf_text') - _MA_MYMODULE3_PRINT - $GLOBALS['xoopsModule']->name()));
$GLOBALS['xoopsTpl']->display('db:testfields_print.tpl');
