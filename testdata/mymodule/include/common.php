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
if (!defined('XOOPS_ICONS32_PATH')) {
    define('XOOPS_ICONS32_PATH', XOOPS_ROOT_PATH . '/Frameworks/moduleclasses/icons/32');
}
if (!defined('XOOPS_ICONS32_URL')) {
    define('XOOPS_ICONS32_URL', XOOPS_URL . '/Frameworks/moduleclasses/icons/32');
}
define('MYMODULE_DIRNAME', 'mymodule');
define('MYMODULE_PATH', XOOPS_ROOT_PATH.'/modules/'.MYMODULE_DIRNAME);
define('MYMODULE_URL', XOOPS_URL.'/modules/'.MYMODULE_DIRNAME);
define('MYMODULE_ICONS_PATH', MYMODULE_PATH.'/assets/icons');
define('MYMODULE_ICONS_URL', MYMODULE_URL.'/assets/icons');
define('MYMODULE_IMAGE_PATH', MYMODULE_PATH.'/assets/images');
define('MYMODULE_IMAGE_URL', MYMODULE_URL.'/assets/images');
define('MYMODULE_UPLOAD_PATH', XOOPS_UPLOAD_PATH.'/'.MYMODULE_DIRNAME);
define('MYMODULE_UPLOAD_URL', XOOPS_UPLOAD_URL.'/'.MYMODULE_DIRNAME);
define('MYMODULE_UPLOAD_FILES_PATH', MYMODULE_UPLOAD_PATH.'/files');
define('MYMODULE_UPLOAD_FILES_URL', MYMODULE_UPLOAD_URL.'/files');
define('MYMODULE_UPLOAD_IMAGE_PATH', MYMODULE_UPLOAD_PATH.'/images');
define('MYMODULE_UPLOAD_IMAGE_URL', MYMODULE_UPLOAD_URL.'/images');
define('MYMODULE_UPLOAD_SHOTS_PATH', MYMODULE_UPLOAD_PATH.'/images/shots');
define('MYMODULE_UPLOAD_SHOTS_URL', MYMODULE_UPLOAD_URL.'/images/shots');
define('MYMODULE_ADMIN', MYMODULE_URL . '/admin/index.php');
$localLogo = MYMODULE_IMAGE_URL . '/tdmxoops_logo.png';
// Module Information
$copyright = "<a href='http://xoops.org' title='XOOPS Project' target='_blank'><img src='".$localLogo."' alt='XOOPS Project' /></a>";
include_once XOOPS_ROOT_PATH . '/class/xoopsrequest.php';
include_once MYMODULE_PATH . '/class/helper.php';
include_once MYMODULE_PATH . '/include/functions.php';
