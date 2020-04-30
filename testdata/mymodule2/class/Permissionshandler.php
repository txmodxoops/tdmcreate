<?php

namespace XoopsModules\Mymodule2;

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

use XoopsModules\Mymodule2;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object Permissionshandler
 */
class Permissionshandler extends \XoopsPersistableObjectHandler
{
	/**
	 * Constructor 
	 *
	 * @param null
	 */
	public function __construct()
	{
	}

	/**
	 * @public function permGlobalApprove
	 * returns right for global approve
	 *
	 * @param null
	 * return bool
	 */
	public function getPermGlobalApprove()
	{
		global $xoopsUser, $xoopsModule;
		$currentuid = 0;
		if (isset($xoopsUser) && is_object($xoopsUser)) {
			if ($xoopsUser->isAdmin($xoopsModule->mid())) {
				return true;
			}
			$currentuid = $xoopsUser->uid();
		}
		$grouppermHandler = xoops_getHandler('groupperm');
		$mid = $xoopsModule->mid();
		$memberHandler = xoops_getHandler('member');
		if (0 == $currentuid) {
			$my_group_ids = [XOOPS_GROUP_ANONYMOUS];
		} else {
			$my_group_ids = $memberHandler->getGroupsByUser($currentuid);;
		}
		if ($grouppermHandler->checkRight('mymodule2_ac', 4, $my_group_ids, $mid)) {
			return true;
		}
		return false;
	}

	/**
	 * @public function permGlobalSubmit
	 * returns right for global submit
	 *
	 * @param null
	 * return bool
	 */
	public function getPermGlobalSubmit()
	{
		global $xoopsUser, $xoopsModule;
		$currentuid = 0;
		if (isset($xoopsUser) && is_object($xoopsUser)) {
			if ($xoopsUser->isAdmin($xoopsModule->mid())) {
				return true;
			}
			$currentuid = $xoopsUser->uid();
		}
		$grouppermHandler = xoops_getHandler('groupperm');
		$mid = $xoopsModule->mid();
		$memberHandler = xoops_getHandler('member');
		if (0 == $currentuid) {
			$my_group_ids = [XOOPS_GROUP_ANONYMOUS];
		} else {
			$my_group_ids = $memberHandler->getGroupsByUser($currentuid);;
		}
		if ($this->getGlobalApprove()) {
			return true;
		}
		if ($grouppermHandler->checkRight('mymodule2_ac', 8, $my_group_ids, $mid)) {
			return true;
		}
		return false;
	}

	/**
	 * @public function permGlobalView
	 * returns right for global view
	 *
	 * @param null
	 * return bool
	 */
	public function getPermGlobalView()
	{
		global $xoopsUser, $xoopsModule;
		$currentuid = 0;
		if (isset($xoopsUser) && is_object($xoopsUser)) {
			if ($xoopsUser->isAdmin($xoopsModule->mid())) {
				return true;
			}
			$currentuid = $xoopsUser->uid();
		}
		$grouppermHandler = xoops_getHandler('groupperm');
		$mid = $xoopsModule->mid();
		$memberHandler = xoops_getHandler('member');
		if (0 == $currentuid) {
			$my_group_ids = [XOOPS_GROUP_ANONYMOUS];
		} else {
			$my_group_ids = $memberHandler->getGroupsByUser($currentuid);;
		}
		if ($this->getGlobalApprove()) {
			return true;
		}
		if ($this->getGlobalSubmit()) {
			return true;
		}
		if ($grouppermHandler->checkRight('mymodule2_ac', 16, $my_group_ids, $mid)) {
			return true;
		}
		return false;
	}
}
