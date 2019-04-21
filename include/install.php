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
 * @version         $Id: install.php 11084 2013-02-23 15:44:20Z timgno $
 */
$indexFile = XOOPS_UPLOAD_PATH.'/index.html';
$blankFile = XOOPS_UPLOAD_PATH.'/blank.gif';
$emptyFile = XOOPS_ROOT_PATH.'/modules/tdmcreate/assets/images/logos/empty.png';

// Making of "uploads" folder
$tdmcreate = XOOPS_UPLOAD_PATH.'/tdmcreate';
if (!is_dir($tdmcreate)) {
    if (!mkdir($tdmcreate, 0777) && !is_dir($tdmcreate)) {
        throw new \RuntimeException(sprintf('Directory "%s" was not created', $tdmcreate));
    }
}
    chmod($tdmcreate, 0777);
copy($indexFile, $tdmcreate.'/index.html');

// Making of images uploads folder
$repository = $tdmcreate.'/repository';
if (!is_dir($repository)) {
    if (!mkdir($repository, 0777) && !is_dir($repository)) {
        throw new \RuntimeException(sprintf('Directory "%s" was not created', $repository));
    }
}
    chmod($repository, 0777);
copy($indexFile, $repository.'/index.html');

// Making of images uploads folder
$images = $tdmcreate.'/images';
if (!is_dir($images)) {
    if (!mkdir($images, 0777) && !is_dir($images)) {
        throw new \RuntimeException(sprintf('Directory "%s" was not created', $images));
    }
}
    chmod($images, 0777);
copy($indexFile, $images.'/index.html');
copy($blankFile, $images.'/blank.gif');

// Making of "modules" images folder
$modules = $images.'/modules';
if (!is_dir($modules)) {
    if (!mkdir($modules, 0777) && !is_dir($modules)) {
        throw new \RuntimeException(sprintf('Directory "%s" was not created', $modules));
    }
}
    chmod($modules, 0777);
copy($indexFile, $modules.'/index.html');
copy($blankFile, $modules.'/blank.gif');
copy($emptyFile, $modules.'/empty.png');

// Making of "tables" images folder
$tables = $images.'/tables';
if (!is_dir($tables)) {
    if (!mkdir($tables, 0777) && !is_dir($tables)) {
        throw new \RuntimeException(sprintf('Directory "%s" was not created', $tables));
    }
}
    chmod($tables, 0777);
copy($indexFile, $tables.'/index.html');
copy($blankFile, $tables.'/blank.gif');
