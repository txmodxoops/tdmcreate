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
$tfId = Request::getInt('tf_id', 0);
$GLOBALS['xoopsOption']['template_main'] = 'mymodule3_single.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';
// Define Stylesheet
$GLOBALS['xoTheme']->addStylesheet( $style, null );
$keywords = array();
// Breadcrumbs
$xoBreadcrumbs[] = ['title' => _MA_MYMODULE3_TESTFIELDS];
// Keywords
mymodule3MetaKeywords($helper->getConfig('keywords').', '. implode(',', $keywords));
unset($keywords);
// Description
mymodule3MetaDescription(_MA_MYMODULE3_TESTFIELDS_DESC);
$GLOBALS['xoopsTpl']->assign('xoops_mpageurl', MYMODULE3_URL.'/index.php');
$GLOBALS['xoopsTpl']->assign('xoops_icons32_url', XOOPS_ICONS32_URL);
$GLOBALS['xoopsTpl']->assign('mymodule3_upload_url', MYMODULE3_UPLOAD_URL);
require __DIR__ . '/footer.php';
