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
 * tdmcreate module.
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 */
include dirname(__DIR__) . '/preloads/autoloader.php';

include_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
include_once dirname(__DIR__) . '/include/common.php';

$thisDirname = $GLOBALS['xoopsModule']->getVar('dirname');
// Link System Icons
$sysPathIcon16 = $GLOBALS['xoopsModule']->getInfo('sysicons16');
$sysPathIcon32 = $GLOBALS['xoopsModule']->getInfo('sysicons32');
// Link Local Icons
$modPathIcon16 = $GLOBALS['xoopsModule']->getInfo('modicons16');
$modPathIcon32 = $GLOBALS['xoopsModule']->getInfo('modicons32');
//$pathModuleAdmin = $GLOBALS['xoopsModule']->getInfo('dirmoduleadmin');

/** @var \XoopsModules\Tdmcreate\Helper $helper */
$helper = \XoopsModules\Tdmcreate\Helper::getInstance();
$utility = new \XoopsModules\Tdmcreate\Utility();

// MyTextSanitizer
$myts = MyTextSanitizer::getInstance();
if (!isset($xoopsTpl) || !is_object($xoopsTpl)) {
    include_once XOOPS_ROOT_PATH . '/class/template.php';
    $xoopsTpl = new \XoopsTpl();
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

xoops_cp_header();
/** @var \Xmf\Module\Admin $adminObject */
$adminObject = \Xmf\Module\Admin::getInstance();
