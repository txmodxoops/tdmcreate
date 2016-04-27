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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 * @version         $Id: menu.php 11084 2013-02-23 15:44:20Z timgno $
 */
$module_handler = &xoops_getHandler('module');
$xoopsModule = &XoopsModule::getByDirname('TDMCreate');
$moduleInfo = &$module_handler->get($xoopsModule->getVar('mid'));
$sysPathIcon32 = $moduleInfo->getInfo('sysicons32');
$modPathIcon32 = $moduleInfo->getInfo('modicons32');
$adminmenu = array();
$i = 1;
$adminmenu[$i]['title'] = _MI_TDMCREATE_ADMENU1;
$adminmenu[$i]['link'] = 'admin/index.php';
$adminmenu[$i]['icon'] = $sysPathIcon32.'/dashboard.png';
++$i;
$adminmenu[$i]['title'] = _MI_TDMCREATE_ADMENU2;
$adminmenu[$i]['link'] = 'admin/settings.php';
$adminmenu[$i]['icon'] = $modPathIcon32.'/settings.png';
++$i;
$adminmenu[$i]['title'] = _MI_TDMCREATE_ADMENU3;
$adminmenu[$i]['link'] = 'admin/modules.php';
$adminmenu[$i]['icon'] = $modPathIcon32.'/addmodule.png';
++$i;
$adminmenu[$i]['title'] = _MI_TDMCREATE_ADMENU4;
$adminmenu[$i]['link'] = 'admin/tables.php';
$adminmenu[$i]['icon'] = $modPathIcon32.'/addtable.png';
++$i;
$adminmenu[$i]['title'] = _MI_TDMCREATE_ADMENU5;
$adminmenu[$i]['link'] = 'admin/fields.php';
$adminmenu[$i]['icon'] = $modPathIcon32.'/fields.png';
++$i;
$adminmenu[$i]['title'] = _MI_TDMCREATE_ADMENU6;
$adminmenu[$i]['link'] = 'admin/morefiles.php';
$adminmenu[$i]['icon'] = $modPathIcon32.'/files.png';
++$i;
$adminmenu[$i]['title'] = _MI_TDMCREATE_ADMENU7;
$adminmenu[$i]['link'] = 'admin/building.php';
$adminmenu[$i]['icon'] = $modPathIcon32.'/builder.png';
++$i;
$adminmenu[$i]['title'] = _MI_TDMCREATE_ABOUT;
$adminmenu[$i]['link'] = 'admin/about.php';
$adminmenu[$i]['icon'] = $sysPathIcon32.'/about.png';
unset($i);
