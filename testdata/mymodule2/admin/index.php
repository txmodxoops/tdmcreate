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


use XoopsModules\Mymodule2\Common;

include_once dirname(__DIR__) . '/preloads/autoloader.php';
require __DIR__ . '/header.php';

// Template Index
$templateMain = 'mymodule2_admin_index.tpl';

// Count elements
$countCategories = $categoriesHandler->getCount();
$countArticles = $articlesHandler->getCount();
$countTestfields = $testfieldsHandler->getCount();

// InfoBox Statistics
$adminObject->addInfoBox(_AM_MYMODULE2_STATISTICS);
// Info elements
$adminObject->addInfoBoxLine(sprintf( '<label>'._AM_MYMODULE2_THEREARE_CATEGORIES.'</label>', $countCategories));
$adminObject->addInfoBoxLine(sprintf( '<label>'._AM_MYMODULE2_THEREARE_ARTICLES.'</label>', $countArticles));
$adminObject->addInfoBoxLine(sprintf( '<label>'._AM_MYMODULE2_THEREARE_TESTFIELDS.'</label>', $countTestfields));

// Upload Folders
$configurator = new Common\Configurator();
if ($configurator->uploadFolders && is_array($configurator->uploadFolders)) {
	foreach(array_keys($configurator->uploadFolders) as $i) {
		$folder[] = $configurator->uploadFolders[$i];
	}
}
// Uploads Folders Created
foreach(array_keys($folder) as $i) {
	$adminObject->addConfigBoxLine($folder[$i], 'folder');
	$adminObject->addConfigBoxLine(array($folder[$i], '777'), 'chmod');
}

// Render Index
$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('index.php'));
// Test Data
if ($helper->getConfig('displaySampleButton')) {
	xoops_loadLanguage('admin/modulesadmin', 'system');
	include_once dirname(__DIR__) . '/testdata/index.php';
	$adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_ADD_SAMPLEDATA'), '__DIR__ . /../../testdata/index.php?op=load', 'add');
	$adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_SAVE_SAMPLEDATA'), '__DIR__ . /../../testdata/index.php?op=save', 'add');
//	$adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_EXPORT_SCHEMA'), '__DIR__ . /../../testdata/index.php?op=exportschema', 'add');
	$adminObject->displayButton('left');
}
$GLOBALS['xoopsTpl']->assign('index', $adminObject->displayIndex());
// End Test Data
require __DIR__ . '/footer.php';
