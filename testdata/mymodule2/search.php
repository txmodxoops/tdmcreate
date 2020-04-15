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
$tfId = Request::getInt('tf_id', 0);
$GLOBALS['xoopsOption']['template_main'] = 'mymodule2_index.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';
// ------------------- Define Stylesheet ------------------- //
$GLOBALS['xoTheme']->addStylesheet( $style, null );
$keywords = array();
// ------------------- Breadcrumbs ------------------- //
$xoBreadcrumbs[] = ['title' => TESTFIELDS_MA_MYMODULE2_];
// ------------------- Keywords ------------------- //
mymodule2MetaKeywords($helper->getConfig('keywords').', '. implode(',', $keywords));
unset($keywords);
// ------------------- Description ------------------- //
mymodule2MetaDescription(DESC_MA_MYMODULE2__DESC);
$GLOBALS['xoopsTpl']->assign('xoops_mpageurl', MYMODULE2_URL.'/index.php');
$GLOBALS['xoopsTpl']->assign('xoops_icons32_url', XOOPS_ICONS32_URL);
$GLOBALS['xoopsTpl']->assign('mymodule2_upload_url', MYMODULE2_UPLOAD_URL);
require __DIR__ . '/footer.php';
