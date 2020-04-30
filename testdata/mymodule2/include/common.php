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
if (!defined('XOOPS_ICONS32_PATH')) {
    define('XOOPS_ICONS32_PATH', XOOPS_ROOT_PATH . '/Frameworks/moduleclasses/icons/32');
}
if (!defined('XOOPS_ICONS32_URL')) {
    define('XOOPS_ICONS32_URL', XOOPS_URL . '/Frameworks/moduleclasses/icons/32');
}
define('MYMODULE2_DIRNAME', 'mymodule2');
define('MYMODULE2_PATH', XOOPS_ROOT_PATH.'/modules/'.MYMODULE2_DIRNAME);
define('MYMODULE2_URL', XOOPS_URL.'/modules/'.MYMODULE2_DIRNAME);
define('MYMODULE2_ICONS_PATH', MYMODULE2_PATH.'/assets/icons');
define('MYMODULE2_ICONS_URL', MYMODULE2_URL.'/assets/icons');
define('MYMODULE2_IMAGE_PATH', MYMODULE2_PATH.'/assets/images');
define('MYMODULE2_IMAGE_URL', MYMODULE2_URL.'/assets/images');
define('MYMODULE2_UPLOAD_PATH', XOOPS_UPLOAD_PATH.'/'.MYMODULE2_DIRNAME);
define('MYMODULE2_UPLOAD_URL', XOOPS_UPLOAD_URL.'/'.MYMODULE2_DIRNAME);
define('MYMODULE2_UPLOAD_FILES_PATH', MYMODULE2_UPLOAD_PATH.'/files');
define('MYMODULE2_UPLOAD_FILES_URL', MYMODULE2_UPLOAD_URL.'/files');
define('MYMODULE2_UPLOAD_IMAGE_PATH', MYMODULE2_UPLOAD_PATH.'/images');
define('MYMODULE2_UPLOAD_IMAGE_URL', MYMODULE2_UPLOAD_URL.'/images');
define('MYMODULE2_UPLOAD_SHOTS_PATH', MYMODULE2_UPLOAD_PATH.'/images/shots');
define('MYMODULE2_UPLOAD_SHOTS_URL', MYMODULE2_UPLOAD_URL.'/images/shots');
define('MYMODULE2_ADMIN', MYMODULE2_URL . '/admin/index.php');
$localLogo = MYMODULE2_IMAGE_URL . '/tdmxoops_logo.png';
// Module Information
$copyright = "<a href='http://xoops.org' title='XOOPS Project' target='_blank'><img src='".$localLogo."' alt='XOOPS Project' /></a>";
include_once XOOPS_ROOT_PATH . '/class/xoopsrequest.php';
include_once MYMODULE2_PATH . '/class/helper.php';
include_once MYMODULE2_PATH . '/include/functions.php';
