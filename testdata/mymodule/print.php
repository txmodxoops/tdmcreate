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
$artId = Request::getInt('art_id');
// Define Stylesheet
$GLOBALS['xoTheme']->addStylesheet( $style, null );
if(empty($artId)) {
	redirect_header(MYMODULE_URL . '/index.php', 2, _MA_MYMODULE_NOARTID);
}
// Get Instance of Handler
$articlesHandler = $helper->getHandler('articles');
// Verify that the article is published
$articles = $articlesHandler->get($artId);
// Verify permissions
if(!$gpermHandler->checkRight('mymodule_view', $artId->getVar('art_id'), $groups, $GLOBALS['xoopsModule']->getVar('mid'))) {
	redirect_header(MYMODULE_URL . '/index.php', 3, _NOPERM);
	exit();
}
$article = $articles->getValuesArticles();
foreach($article as $k => $v) {
	$GLOBALS['xoopsTpl']->append('"{$k}"', $v);
}
$GLOBALS['xoopsTpl']->assign('xoops_sitename', $GLOBALS['xoopsConfig']['sitename']);
$GLOBALS['xoopsTpl']->assign('xoops_pagetitle', strip_tags($article->getVar('art_title') - _MA_MYMODULE_PRINT - $GLOBALS['xoopsModule']->name()));
$GLOBALS['xoopsTpl']->display('db:articles_print.tpl');
