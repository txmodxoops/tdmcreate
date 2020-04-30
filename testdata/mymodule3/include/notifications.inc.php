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
 * My Module 3 module for xoops
 *
 * @copyright     2020 XOOPS Project (https://xooops.org)
 * @license        GPL 2.0 or later
 * @package        mymodule3
 * @since          1.0
 * @min_xoops      2.5.9
 * @author         TDM XOOPS - Email:<info@email.com> - Website:<http://xoops.org>
 */

/**
 * comment callback functions
 *
 * @param  $category 
 * @param  $item_id 
 * @return array item|null
 */
function mymodule3_notify_iteminfo($category, $item_id)
{
	global $xoopsDB;

	if (!defined('MYMODULE3_URL')) {
		define('MYMODULE3_URL', XOOPS_URL . '/modules/mymodule3');
	}

	switch($category) {
		case 'global':
			$item['name'] = '';
			$item['url']  = '';
			return $item;
		break;
		case 'articles':
			$sql          = 'SELECT art_title FROM ' . $xoopsDB->prefix('mymodule3_articles') . ' WHERE art_id = '. $item_id;
			$result       = $xoopsDB->query($sql);
			$result_array = $xoopsDB->fetchArray($result);
			$item['name'] = $result_array['art_title'];
			$item['url']  = MYMODULE3_URL . '/articles.php?art_id=' . $item_id;
			return $item;
		break;
		case 'testfields':
			$sql          = 'SELECT tf_text FROM ' . $xoopsDB->prefix('mymodule3_testfields') . ' WHERE tf_id = '. $item_id;
			$result       = $xoopsDB->query($sql);
			$result_array = $xoopsDB->fetchArray($result);
			$item['name'] = $result_array['tf_text'];
			$item['url']  = MYMODULE3_URL . '/testfields.php?tf_id=' . $item_id;
			return $item;
		break;
	}
	return null;
}
