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
 * My Module 2 module for xoops
 *
 * @copyright     2020 XOOPS Project (https://xooops.org)
 * @license        GPL 2.0 or later
 * @package        mymodule2
 * @since          1.0
 * @min_xoops      2.5.9
 * @author         TDM XOOPS - Email:<info@email.com> - Website:<http://xoops.org>
 */

use Xmf\Request;
use XoopsModules\Mymodule2;
use XoopsModules\Mymodule2\Constants;

require __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'mymodule2_testfields.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';

$start = Request::getInt('start', 0);
$limit = Request::getInt('limit', $helper->getConfig('userpager'));

// Define Stylesheet
$GLOBALS['xoTheme']->addStylesheet( $style, null );

$GLOBALS['xoopsTpl']->assign('xoops_icons32_url', XOOPS_ICONS32_URL);
$GLOBALS['xoopsTpl']->assign('mymodule2_url', MYMODULE2_URL);

$testfieldsCount = $testfieldsHandler->getCountTestfields();
$GLOBALS['xoopsTpl']->assign('testfieldsCount', $testfieldsCount);
$testfieldsAll = $testfieldsHandler->getAllTestfields($start, $limit);
$keywords = [];
if ($testfieldsCount > 0) {
	$testfields = [];
	// Get All Testfields
	foreach(array_keys($testfieldsAll) as $i) {
		$testfields[] = $testfieldsAll[$i]->getValuesTestfields();
		$keywords[] = $testfieldsAll[$i]->getVar('tf_text');
	}
	$GLOBALS['xoopsTpl']->assign('testfields', $testfields);
	unset($testfields);
	// Display Navigation
	if ($testfieldsCount > $limit) {
		include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
		$pagenav = new \XoopsPageNav($testfieldsCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
		$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
	}
	$GLOBALS['xoopsTpl']->assign('type', $helper->getConfig('table_type'));
	$GLOBALS['xoopsTpl']->assign('divideby', $helper->getConfig('divideby'));
	$GLOBALS['xoopsTpl']->assign('numb_col', $helper->getConfig('numb_col'));
}

// Breadcrumbs
$xoBreadcrumbs[] = ['title' => _MA_MYMODULE2_TESTFIELDS];

// Keywords
mymodule2MetaKeywords($helper->getConfig('keywords').', '. implode(',', $keywords));
unset($keywords);

// Description
mymodule2MetaDescription(_MA_MYMODULE2_TESTFIELDS_DESC);
$GLOBALS['xoopsTpl']->assign('xoops_mpageurl', MYMODULE2_URL.'/testfields.php');
$GLOBALS['xoopsTpl']->assign('mymodule2_upload_url', MYMODULE2_UPLOAD_URL);
require __DIR__ . '/footer.php';
