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
 * @version         $Id: install.php 11084 2013-02-23 15:44:20Z timgno $
 */

$indexFile = XOOPS_UPLOAD_PATH.'/index.html';
$blankFile = XOOPS_UPLOAD_PATH.'/blank.gif';
$emptyFile = XOOPS_ROOT_PATH.'/modules/tdmcreate/assets/images/empty.png';

// Making of "uploads" folder
$tdmcreate = XOOPS_UPLOAD_PATH.'/tdmcreate';
if(!is_dir($tdmcreate))
	mkdir($tdmcreate, 0777);
	chmod($tdmcreate, 0777);
copy($indexFile, $tdmcreate.'/index.html');

// Making of images uploads folder
$repository = $tdmcreate.'/repository';
if(!is_dir($repository))
	mkdir($repository, 0777);
	chmod($repository, 0777);
copy($indexFile, $repository.'/index.html');

// Making of images uploads folder
$images = $tdmcreate.'/images';
if(!is_dir($images))
	mkdir($images, 0777);
	chmod($images, 0777);
copy($indexFile, $images.'/index.html');
copy($blankFile, $images.'/blank.gif');

// Making of "repository" images folder
$repository = $images.'/repository';
if(!is_dir($repository))
	mkdir($repository, 0777);
	chmod($repository, 0777);
copy($indexFile, $repository.'/index.html');
//copy($blankFile, $repository.'/blank.gif');
copy($emptyFile, $repository.'/empty.png');

// Making of "tables" images folder
$tables = $images.'/tables';
if(!is_dir($tables))
	mkdir($tables, 0777);
	chmod($tables, 0777);
copy($indexFile, $tables.'/index.html');
//copy($blankFile, $tables.'/blank.gif');