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
$GLOBALS['xoopsOption']['template_main'] = 'mymodule3_articles.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';

$op = Request::getString('op', 'list');
$artId = Request::getInt('art_id', 0);
$start = Request::getInt('start', 0);
$limit = Request::getInt('limit', $helper->getConfig('userpager'));

// Define Stylesheet
$GLOBALS['xoTheme']->addStylesheet( $style, null );

$GLOBALS['xoopsTpl']->assign('xoops_icons32_url', XOOPS_ICONS32_URL);
$GLOBALS['xoopsTpl']->assign('mymodule3_url', MYMODULE3_URL);

$critArticles = new \CriteriaCompo();
if ($artId > 0) {
	$critArticles->add( new \Criteria( 'art_id', $artId ) );
}
$articlesCount = $articlesHandler->getCount($critArticles);
$GLOBALS['xoopsTpl']->assign('articlesCount', $articlesCount);
$critArticles->setStart( $start );
$critArticles->setLimit( $limit );
$articlesAll = $articlesHandler->getAll($critArticles);
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
$xoBreadcrumbs[] = ['title' => _MA_MYMODULE3_ARTICLES];

// Keywords
mymodule3MetaKeywords($helper->getConfig('keywords').', '. implode(',', $keywords));
unset($keywords);

// Description
mymodule3MetaDescription(_MA_MYMODULE3_ARTICLES_DESC);
$GLOBALS['xoopsTpl']->assign('xoops_mpageurl', MYMODULE3_URL.'/articles.php');
$GLOBALS['xoopsTpl']->assign('mymodule3_upload_url', MYMODULE3_UPLOAD_URL);
require __DIR__ . '/footer.php';
