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
 * tdmcreate module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         tdmcreate
 * @since           2.5.0
 * @author          Txmod Xoops http://www.txmodxoops.org
 * @version         $Id: common.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');
$dirname = $GLOBALS['xoopsModule']->getVar('dirname');
// Root Frameworks icons 32x32 directory
define('XOOPS_ICONS32_PATH', XOOPS_ROOT_PATH . '/Frameworks/moduleclasses/icons/32');
// Local Directories
define('TDMC_PATH', XOOPS_ROOT_PATH . '/modules/' . $dirname);
define('TDMC_URL', XOOPS_URL . '/modules/' . $dirname);
define('TDMC_CLASSES_PATH', TDMC_PATH . '/class');
define('TDMC_CLASSES_URL', TDMC_URL . '/class');
define('TDMC_DOCS_PATH', TDMC_PATH . '/docs');
define('TDMC_DOCS_URL', TDMC_URL . '/docs');
define('TDMC_FONTS_PATH', TDMC_PATH . '/assets/fonts');
define('TDMC_FONTS_URL', TDMC_URL . '/assets/fonts');
define('TDMC_IMAGE_PATH', TDMC_PATH . '/assets/images');
define('TDMC_IMAGE_URL', TDMC_URL . '/assets/images');
define('TDMC_IMAGES_LOGOS_PATH', TDMC_PATH . '/assets/images/logos');
define('TDMC_IMAGES_LOGOS_URL', TDMC_URL . '/assets/images/logos');
define('TDMC_ICONS_PATH', TDMC_PATH . '/assets/icons');
define('TDMC_ICONS_URL', TDMC_URL . '/assets/icons');
// Uploads Directories
define('TDMC_UPLOAD_PATH', XOOPS_UPLOAD_PATH . '/' . $dirname);
define('TDMC_UPLOAD_URL', XOOPS_UPLOAD_URL . '/' . $dirname);
define('TDMC_UPLOAD_REPOSITORY_PATH', TDMC_UPLOAD_PATH . '/repository');
define('TDMC_UPLOAD_REPOSITORY_URL', TDMC_UPLOAD_URL . '/repository');
define('TDMC_UPLOAD_IMGMOD_PATH', TDMC_UPLOAD_PATH . '/images/modules');
define('TDMC_UPLOAD_IMGMOD_URL', TDMC_UPLOAD_URL . '/images/modules');
define('TDMC_UPLOAD_IMGTAB_PATH', TDMC_UPLOAD_PATH . '/images/tables');
define('TDMC_UPLOAD_IMGTAB_URL', TDMC_UPLOAD_URL . '/images/tables');
// Xoops Request
include_once XOOPS_ROOT_PATH . '/class/xoopsrequest.php';
include_once TDMC_PATH . '/include/functions.php';
include_once TDMC_PATH . '/class/helper.php';