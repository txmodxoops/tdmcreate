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
if (!defined('XOOPS_ICONS32_PATH')) {
	define('XOOPS_ICONS32_PATH', XOOPS_ROOT_PATH . '/Frameworks/moduleclasses/icons/32');
}
if (!defined('XOOPS_ICONS32_URL')) {
	define('XOOPS_ICONS32_URL', XOOPS_URL . '/Frameworks/moduleclasses/icons/32');
}
define('MYMODULE3_DIRNAME', 'mymodule3');
define('MYMODULE3_PATH', XOOPS_ROOT_PATH.'/modules/'.MYMODULE3_DIRNAME);
define('MYMODULE3_URL', XOOPS_URL.'/modules/'.MYMODULE3_DIRNAME);
define('MYMODULE3_ICONS_PATH', MYMODULE3_PATH.'/assets/icons');
define('MYMODULE3_ICONS_URL', MYMODULE3_URL.'/assets/icons');
define('MYMODULE3_IMAGE_PATH', MYMODULE3_PATH.'/assets/images');
define('MYMODULE3_IMAGE_URL', MYMODULE3_URL.'/assets/images');
define('MYMODULE3_UPLOAD_PATH', XOOPS_UPLOAD_PATH.'/'.MYMODULE3_DIRNAME);
define('MYMODULE3_UPLOAD_URL', XOOPS_UPLOAD_URL.'/'.MYMODULE3_DIRNAME);
define('MYMODULE3_UPLOAD_FILES_PATH', MYMODULE3_UPLOAD_PATH.'/files');
define('MYMODULE3_UPLOAD_FILES_URL', MYMODULE3_UPLOAD_URL.'/files');
define('MYMODULE3_UPLOAD_IMAGE_PATH', MYMODULE3_UPLOAD_PATH.'/images');
define('MYMODULE3_UPLOAD_IMAGE_URL', MYMODULE3_UPLOAD_URL.'/images');
define('MYMODULE3_UPLOAD_SHOTS_PATH', MYMODULE3_UPLOAD_PATH.'/images/shots');
define('MYMODULE3_UPLOAD_SHOTS_URL', MYMODULE3_UPLOAD_URL.'/images/shots');
define('MYMODULE3_ADMIN', MYMODULE3_URL . '/admin/index.php');
$localLogo = MYMODULE3_IMAGE_URL . '/tdmxoops_logo.png';
// Module Information
$copyright = "<a href='http://xoops.org' title='XOOPS Project' target='_blank'><img src='".$localLogo."' alt='XOOPS Project' /></a>";
include_once XOOPS_ROOT_PATH . '/class/xoopsrequest.php';
include_once MYMODULE3_PATH . '/class/helper.php';
include_once MYMODULE3_PATH . '/include/functions.php';
