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
$GLOBALS['xoopsOption']['template_main'] = 'mymodule2_articles.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';

$start = Request::getInt('start', 0);
$limit = Request::getInt('limit', $helper->getConfig('userpager'));

// Define Stylesheet
$GLOBALS['xoTheme']->addStylesheet( $style, null );

$GLOBALS['xoopsTpl']->assign('xoops_icons32_url', XOOPS_ICONS32_URL);
$GLOBALS['xoopsTpl']->assign('mymodule2_url', MYMODULE2_URL);

$articlesCount = $articlesHandler->getCountArticles();
$GLOBALS['xoopsTpl']->assign('articlesCount', $articlesCount);
$articlesAll = $articlesHandler->getAllArticles($start, $limit);
$keywords = [];
if ($articlesCount > 0) {
	$articles = [];
	// Get All Articles
	foreach(array_keys($articlesAll) as $i) {
		$articles[] = $articlesAll[$i]->getValuesArticles();
		$keywords[] = $articlesAll[$i]->getVar('art_title');
	}
	$GLOBALS['xoopsTpl']->assign('articles', $articles);
	unset($articles);
	// Display Navigation
	if ($articlesCount > $limit) {
		include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
		$pagenav = new \XoopsPageNav($articlesCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
		$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
	}
	$GLOBALS['xoopsTpl']->assign('type', $helper->getConfig('table_type'));
	$GLOBALS['xoopsTpl']->assign('divideby', $helper->getConfig('divideby'));
	$GLOBALS['xoopsTpl']->assign('numb_col', $helper->getConfig('numb_col'));
}

// Breadcrumbs
$xoBreadcrumbs[] = ['title' => _MA_MYMODULE2_ARTICLES];

// Keywords
mymodule2MetaKeywords($helper->getConfig('keywords').', '. implode(',', $keywords));
unset($keywords);

// Description
mymodule2MetaDescription(_MA_MYMODULE2_ARTICLES_DESC);
$GLOBALS['xoopsTpl']->assign('xoops_mpageurl', MYMODULE2_URL.'/articles.php');
$GLOBALS['xoopsTpl']->assign('mymodule2_upload_url', MYMODULE2_UPLOAD_URL);
require __DIR__ . '/footer.php';
