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
include __DIR__ . '/../../../mainfile.php';
include_once XOOPS_ROOT_PATH.'/modules/mymodule2/class/testfields.php';
$com_itemid = isset($_REQUEST['com_itemid']) ? (int)$_REQUEST['com_itemid'] : 0;
if ($com_itemid > 0) {
    $testfieldsHandler = xoops_getModuleHandler('testfields', 'mymodule2');
    $testfields = $testfieldshandler->get($com_itemid);
    $com_replytitle = $testfields->getVar('tf_text');
    include XOOPS_ROOT_PATH.'/include/comment_new.php';
}