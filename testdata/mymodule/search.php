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
$artId = Request::getInt('art_id', 0);
$GLOBALS['xoopsOption']['template_main'] = 'mymodule_index.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';
// ------------------- Define Stylesheet ------------------- //
$GLOBALS['xoTheme']->addStylesheet( $style, null );
$keywords = array();
// ------------------- Breadcrumbs ------------------- //
$xoBreadcrumbs[] = ['title' => ARTICLES_MA_MYMODULE_];
// ------------------- Keywords ------------------- //
mymoduleMetaKeywords($helper->getConfig('keywords').', '. implode(',', $keywords));
unset($keywords);
// ------------------- Description ------------------- //
mymoduleMetaDescription(DESC_MA_MYMODULE__DESC);
$GLOBALS['xoopsTpl']->assign('xoops_mpageurl', MYMODULE_URL.'/index.php');
$GLOBALS['xoopsTpl']->assign('xoops_icons32_url', XOOPS_ICONS32_URL);
$GLOBALS['xoopsTpl']->assign('mymodule_upload_url', MYMODULE_UPLOAD_URL);
require __DIR__ . '/footer.php';
