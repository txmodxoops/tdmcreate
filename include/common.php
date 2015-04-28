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
define('XOOPS_ICONS32_PATH', XOOPS_ROOT_PATH . '/Frameworks/moduleclasses/icons/32' );
// Local Directories
define('TDMC_PATH', XOOPS_ROOT_PATH . '/modules/' . $dirname );
define('TDMC_URL', XOOPS_URL . '/modules/' . $dirname );
define('TDMC_CLASSES_PATH', TDMC_PATH . '/class' );
define('TDMC_CLASSES_URL', TDMC_URL . '/class' );
define('TDMC_DOCS_PATH', TDMC_PATH . '/docs' );
define('TDMC_DOCS_URL', TDMC_URL . '/docs' );
define('TDMC_FONTS_PATH', TDMC_PATH . '/assets/fonts' );
define('TDMC_FONTS_URL', TDMC_URL . '/assets/fonts' );
define('TDMC_IMAGE_PATH', TDMC_PATH . '/assets/images' );
define('TDMC_IMAGE_URL', TDMC_URL . '/assets/images' );
define('TDMC_IMAGE_LOGOS_PATH', TDMC_PATH . '/assets/images/logos' );
define('TDMC_IMAGE_LOGOS_URL', TDMC_URL . '/assets/images/logos' );
define('TDMC_ICONS_PATH', TDMC_PATH . '/assets/icons' );
define('TDMC_ICONS_URL', TDMC_URL . '/assets/icons' );
// Uploads Directories
define('TDMC_UPLOAD_PATH', XOOPS_UPLOAD_PATH . '/' . $dirname );
define('TDMC_UPLOAD_URL', XOOPS_UPLOAD_URL . '/' . $dirname );
define('TDMC_UPLOAD_REPOSITORY_PATH', TDMC_UPLOAD_PATH . '/repository' );
define('TDMC_UPLOAD_REPOSITORY_URL', TDMC_UPLOAD_URL . '/repository' );
define('TDMC_UPLOAD_IMGMOD_PATH', TDMC_UPLOAD_PATH . '/images/modules' );
define('TDMC_UPLOAD_IMGMOD_URL', TDMC_UPLOAD_URL . '/images/modules' );
define('TDMC_UPLOAD_IMGTAB_PATH', TDMC_UPLOAD_PATH . '/images/tables' );
define('TDMC_UPLOAD_IMGTAB_URL', TDMC_UPLOAD_URL . '/images/tables' );
// Xoops Request
include_once XOOPS_ROOT_PATH . '/class/xoopsrequest.php';
include_once TDMC_PATH . '/include/functions.php';
//require_once(TDMC_CLASSES_PATH.'/TDMCreateAutoload.php');
//spl_autoload_register(array(TDMCreateAutoload::getInstance(), 'load'));
// Include files
$cf = '/class/files/';
$cfa = '/class/files/admin/';
$cfb = '/class/files/blocks/';
$cfcl = '/class/files/classes/';
$cfcs = '/class/files/css/';
$cfd = '/class/files/docs/';
$cfi = '/class/files/include/';
$cfl = '/class/files/language/';
$cfs = '/class/files/sql/';
$cftu = '/class/files/templates/user/';
$cfta = '/class/files/templates/admin/';
$cftb = '/class/files/templates/blocks/';
$cfu = '/class/files/user/';
include_once TDMC_PATH . '/class/TDMCreateHelper.php';
include_once TDMC_PATH . '/class/TDMCreateSession.php';
require_once TDMC_PATH . $cf .'TDMCreateFile.php';
include_once TDMC_PATH . $cfa .'AdminAbout.php';
include_once TDMC_PATH . $cfa .'AdminFooter.php';
include_once TDMC_PATH . $cfa .'AdminHeader.php';
include_once TDMC_PATH . $cfa .'AdminIndex.php';
include_once TDMC_PATH . $cfa .'AdminMenu.php';
include_once TDMC_PATH . $cfa .'AdminPages.php';
include_once TDMC_PATH . $cfa .'AdminPermissions.php';
include_once TDMC_PATH . $cfb .'BlocksFiles.php';
include_once TDMC_PATH . $cfcl .'ClassFiles.php';
include_once TDMC_PATH . $cfcl .'ClassHelper.php';
include_once TDMC_PATH . $cfcs .'CssStyles.php';
include_once TDMC_PATH . $cfd .'DocsChangelog.php';
include_once TDMC_PATH . $cfd .'DocsFiles.php';
include_once TDMC_PATH . $cfi .'IncludeComments.php';
include_once TDMC_PATH . $cfi .'IncludeCommentFunctions.php';
include_once TDMC_PATH . $cfi .'IncludeCommon.php';
include_once TDMC_PATH . $cfi .'IncludeFunctions.php';
include_once TDMC_PATH . $cfi .'IncludeInstall.php';
include_once TDMC_PATH . $cfi .'IncludeJquery.php';
include_once TDMC_PATH . $cfi .'IncludeNotifications.php';
include_once TDMC_PATH . $cfi .'IncludeSearch.php';
include_once TDMC_PATH . $cfi .'IncludeUpdate.php';
include_once TDMC_PATH . $cfl .'LanguageAdmin.php';
include_once TDMC_PATH . $cfl .'LanguageBlocks.php';
include_once TDMC_PATH . $cfl .'LanguageHelp.php';
include_once TDMC_PATH . $cfl .'LanguageMailTpl.php';
include_once TDMC_PATH . $cfl .'LanguageMain.php';
include_once TDMC_PATH . $cfl .'LanguageModinfo.php';
include_once TDMC_PATH . $cfs .'SqlFile.php';
include_once TDMC_PATH . $cfta .'TemplatesAdminAbout.php';
include_once TDMC_PATH . $cfta .'TemplatesAdminHeader.php';
include_once TDMC_PATH . $cfta .'TemplatesAdminIndex.php';
include_once TDMC_PATH . $cfta .'TemplatesAdminFooter.php';
include_once TDMC_PATH . $cfta .'TemplatesAdminPages.php';
include_once TDMC_PATH . $cfta .'TemplatesAdminPermissions.php';
include_once TDMC_PATH . $cftb .'TemplatesBlocks.php';
include_once TDMC_PATH . $cftu .'TemplatesUserHeader.php';
include_once TDMC_PATH . $cftu .'TemplatesUserIndex.php';
include_once TDMC_PATH . $cftu .'TemplatesUserFooter.php';
include_once TDMC_PATH . $cftu .'TemplatesUserPages.php';
include_once TDMC_PATH . $cfu .'UserFooter.php';
include_once TDMC_PATH . $cfu .'UserHeader.php';
include_once TDMC_PATH . $cfu .'UserIndex.php';
include_once TDMC_PATH . $cfu .'UserPages.php';
include_once TDMC_PATH . $cfu .'UserNotificationUpdate.php';
include_once TDMC_PATH . $cfu .'UserXoopsVersion.php';