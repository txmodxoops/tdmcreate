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
 * @version         $Id: index.php 11084 2013-02-23 15:44:20Z timgno $
 */
include __DIR__.'/header.php';
$countSettings = $tdmcreate->getHandler('settings')->getCount();
$countModules = $tdmcreate->getHandler('modules')->getCount();
$countTables = $tdmcreate->getHandler('tables')->getCount();
$countFields = $tdmcreate->getHandler('fields')->getCount();
$countFiles = $tdmcreate->getHandler('morefiles')->getCount();
unset($criteria);
$templateMain = 'tdmcreate_index.tpl';
$adminMenu->addInfoBox(_AM_TDMCREATE_ADMIN_NUMMODULES);
$adminMenu->addInfoBoxLine(sprintf('<label>'._AM_TDMCREATE_THEREARE_NUMSETTINGS.'</label>', $countSettings), 'Blue');
$adminMenu->addInfoBoxLine(sprintf('<label>'._AM_TDMCREATE_THEREARE_NUMMODULES.'</label>', $countModules), 'Green');
$adminMenu->addInfoBoxLine(sprintf('<label>'._AM_TDMCREATE_THEREARE_NUMTABLES.'</label>', $countTables), 'Orange');
$adminMenu->addInfoBoxLine(sprintf('<label>'._AM_TDMCREATE_THEREARE_NUMFIELDS.'</label>', $countFields), 'Gray');
$adminMenu->addInfoBoxLine(sprintf('<label>'._AM_TDMCREATE_THEREARE_NUMFILES.'</label>', $countFiles), 'Red');
// Upload Folders
$folder = [
        TDMC_UPLOAD_PATH,
        TDMC_UPLOAD_REPOSITORY_PATH,
        TDMC_UPLOAD_IMGMOD_PATH,
        TDMC_UPLOAD_IMGTAB_PATH,
];

// Uploads Folders Created
foreach (array_keys($folder) as $i) {
    $adminMenu->addConfigBoxLine($folder[$i], 'folder');
    $adminMenu->addConfigBoxLine([$folder[$i], '777'], 'chmod');
}

$GLOBALS['xoopsTpl']->assign('navigation', $adminMenu->displayNavigation('index.php'));
$GLOBALS['xoopsTpl']->assign('index', $adminMenu->renderIndex());
include __DIR__.'/footer.php';
