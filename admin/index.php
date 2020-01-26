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
$GLOBALS['xoopsOption']['template_main'] = 'tdmcreate_index.tpl';

include __DIR__ . '/header.php';
$countSettings = $helper->getHandler('Settings')->getCount();
$countModules  = $helper->getHandler('Modules')->getCount();
$countTables   = $helper->getHandler('Tables')->getCount();
$countFields   = $helper->getHandler('Fields')->getCount();
$countFiles    = $helper->getHandler('Morefiles')->getCount();
unset($criteria);

//$templateMain = 'tdmcreate_index.tpl';
$adminObject->addInfoBox(_AM_TDMCREATE_ADMIN_NUMMODULES);
$adminObject->addInfoBoxLine(sprintf('<label>' . _AM_TDMCREATE_THEREARE_NUMSETTINGS . '</label>', $countSettings), 'Blue');
$adminObject->addInfoBoxLine(sprintf('<label>' . _AM_TDMCREATE_THEREARE_NUMMODULES . '</label>', $countModules), 'Green');
$adminObject->addInfoBoxLine(sprintf('<label>' . _AM_TDMCREATE_THEREARE_NUMTABLES . '</label>', $countTables), 'Orange');
$adminObject->addInfoBoxLine(sprintf('<label>' . _AM_TDMCREATE_THEREARE_NUMFIELDS . '</label>', $countFields), 'Gray');
$adminObject->addInfoBoxLine(sprintf('<label>' . _AM_TDMCREATE_THEREARE_NUMFILES . '</label>', $countFiles), 'Red');
// Upload Folders
$folder = [
    TDMC_UPLOAD_PATH,
    TDMC_UPLOAD_REPOSITORY_PATH,
    TDMC_UPLOAD_IMGMOD_PATH,
    TDMC_UPLOAD_IMGTAB_PATH,
];

// Uploads Folders Created
foreach (array_keys($folder) as $i) {
    $adminObject->addConfigBoxLine($folder[$i], 'folder');
    $adminObject->addConfigBoxLine([$folder[$i], '777'], 'chmod');
}

$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('index.php'));
$GLOBALS['xoopsTpl']->assign('index', $adminObject->displayIndex());

include __DIR__ . '/footer.php';
