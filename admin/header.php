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
 * @version         $Id: header.php 12258 2014-01-02 09:33:29Z timgno $
*/
include_once dirname(dirname(dirname(dirname(__FILE__)))) . '/include/cp_header.php';
include_once dirname(dirname(__FILE__)) . '/include/common.php';
//
$thisDirname = $GLOBALS['xoopsModule']->getVar('dirname');
// Link System Icons
$sysPathIcon16 = $GLOBALS['xoopsModule']->getInfo('sysicons16');
$sysPathIcon32 = $GLOBALS['xoopsModule']->getInfo('sysicons32');
// Link Local Icons
$modPathIcon16 = $GLOBALS['xoopsModule']->getInfo('modicons16');
$modPathIcon32 = $GLOBALS['xoopsModule']->getInfo('modicons32');
$pathModuleAdmin = $GLOBALS['xoopsModule']->getInfo('dirmoduleadmin');
// TDMCreate Instance
$tdmcreate = TDMCreateHelper::getInstance();
// MyTextSanitizer
$myts =& MyTextSanitizer::getInstance();
if (!isset($xoopsTpl) || !is_object($xoopsTpl)) {
	include_once(XOOPS_ROOT_PATH."/class/template.php");
	$xoopsTpl = new XoopsTpl();
}
// System Icons
$GLOBALS['xoopsTpl']->assign('sysPathIcon16', $sysPathIcon16);
$GLOBALS['xoopsTpl']->assign('sysPathIcon32', $sysPathIcon32);
// Local Icons
$GLOBALS['xoopsTpl']->assign('modPathIcon16', $modPathIcon16);
$GLOBALS['xoopsTpl']->assign('modPathIcon32', $modPathIcon32);
// Load languages
xoops_loadLanguage('admin', $thisDirname);
xoops_loadLanguage('modinfo', $thisDirname);
xoops_loadLanguage('main', $thisDirname);
// Locad admin menu class
if ( file_exists($GLOBALS['xoops']->path($pathModuleAdmin.'/moduleadmin.php'))){
	include_once $GLOBALS['xoops']->path($pathModuleAdmin.'/moduleadmin.php');
}else{
	redirect_header("../../../admin.php", 5, _AM_MODULEADMIN_MISSING, false);
}
xoops_cp_header();
$adminMenu = new ModuleAdmin();	