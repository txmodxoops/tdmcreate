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
 * Tdmcreate module for xoops
 *
 * @copyright      module for xoops
 * @license        GPL 2.0 or later
 * @package        Tdmcreate
 * @since          1.0
 * @min_xoops      2.5.9
 * @author         Wedega - Email:<webmaster@wedega.com> - Website:<https://wedega.com>
 * @version        $Id: 1.0 install.php 1 Mon 2018-03-19 10:04:53Z XOOPS Project (www.xoops.org) $
 */

use XoopsModules\Tdmcreate;
use XoopsModules\Tdmcreate\Common;

/**
 * @param \XoopsModule $module
 * @return bool
 */
function xoops_module_pre_install_tdmcreate(\XoopsModule $module)
{
    require dirname(__DIR__) . '/preloads/autoloader.php';
    /** @var Tdmcreate\Utility $utility */
    $utility = new Tdmcreate\Utility();

    //check for minimum XOOPS version
    $xoopsSuccess = $utility::checkVerXoops($module);

    // check for minimum PHP version
    $phpSuccess = $utility::checkVerPhp($module);

    if (false !== $xoopsSuccess && false !== $phpSuccess) {
        $moduleTables = &$module->getInfo('tables');
        foreach ($moduleTables as $table) {
            $GLOBALS['xoopsDB']->queryF('DROP TABLE IF EXISTS ' . $GLOBALS['xoopsDB']->prefix($table) . ';');
        }
    }

    return $xoopsSuccess && $phpSuccess;
}

/**
 * @param \XoopsModule $module
 * @return bool|string
 */
function xoops_module_install_tdmcreate(\XoopsModule $module)
{
    require dirname(__DIR__) . '/preloads/autoloader.php';

    /** @var Tdmcreate\Helper $helper */ 
    /** @var Tdmcreate\Utility $utility */
    /** @var Common\Configurator $configurator */
    $helper       = Tdmcreate\Helper::getInstance();
    $utility      = new Tdmcreate\Utility();
    $configurator = new Common\Configurator();

    // Load language files
    $helper->loadLanguage('admin');
    $helper->loadLanguage('modinfo');
    $helper->loadLanguage('common');

    //  ---  CREATE FOLDERS ---------------
    if ($configurator->uploadFolders && is_array($configurator->uploadFolders)) {
        //    foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
        foreach (array_keys($configurator->uploadFolders) as $i) {
            $utility::createFolder($configurator->uploadFolders[$i]);
        }
    }

    //  ---  COPY blank.gif FILES ---------------
    if ($configurator->copyBlankFiles && is_array($configurator->copyBlankFiles)) {
        $file = dirname(__DIR__) . '/assets/images/blank.gif';
        foreach (array_keys($configurator->copyBlankFiles) as $i) {
            $dest = $configurator->copyBlankFiles[$i] . '/blank.gif';
            $utility::copyFile($file, $dest);
        }
		$file = dirname(__DIR__) . '/assets/images/blank.png';
        foreach (array_keys($configurator->copyBlankFiles) as $i) {
            $dest = $configurator->copyBlankFiles[$i] . '/blank.png';
            $utility::copyFile($file, $dest);
        }
    }

    return true;
}
