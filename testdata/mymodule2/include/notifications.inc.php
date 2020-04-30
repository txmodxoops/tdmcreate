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

/**
 * comment callback functions
 *
 * @param $category
 * @param $item_id
 * @return array item|null
 */
function mymodule2_notify_iteminfo($category, $item_id)
{
    global $xoopsModule, $xoopsModuleConfig, $xoopsDB;
    //
    if (empty($xoopsModule) || $xoopsModule->getVar('dirname') != 'mymodule2')
    {
        $moduleHandler = xoops_getHandler('module');
        $module = $moduleHandler->getByDirname('mymodule2');
        $configHandler = xoops_getHandler('config');
        $config =& $configHandler->getConfigsByCat(0, $module->getVar('mid'));
    } else {
        $module = $xoopsModule;
        $config = $xoopsModuleConfig;
    }
    //
    switch($category) {
        case 'global':
            $item['name'] = '';
            $item['url'] = '';
            return $item;
        break;
        case 'category':
            // Assume we have a valid category id
            $sql = 'SELECT tf_text FROM ' . $xoopsDB->prefix('mymodule2_testfields') . ' WHERE tf_id = '. $item_id;
            $result = $xoopsDB->query($sql); // TODO: error check
            $result_array = $xoopsDB->fetchArray($result);
            $item['name'] = $result_array['tf_text'];
            $item['url'] = MYMODULE2_URL . '/testfields.php?tf_id=' . $item_id;
            return $item;
        break;
        case 'testfield':
            // Assume we have a valid link id
            $sql = 'SELECT tf_id, tf_text FROM '.$xoopsDB->prefix('mymodule2_testfields') . ' WHERE tf_id = ' . $item_id;
            $result = $xoopsDB->query($sql); // TODO: error check
            $result_array = $xoopsDB->fetchArray($result);
            $item['name'] = $result_array['tf_text'];
			$item['url'] = MYMODULE2_URL . '/single.php?cid=' . $result_array['cid'] . '&amp;tf_id=' . $item_id;
			return $item;
        break;
    }
    return null;
}