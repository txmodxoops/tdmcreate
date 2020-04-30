<?php

namespace XoopsModules\Mymodule;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * My Module module for xoops
 *
 * @copyright     2020 XOOPS Project (https://xooops.org)
 * @license        GPL 2.0 or later
 * @package        mymodule
 * @since          1.0
 * @min_xoops      2.5.9
 * @author         TDM XOOPS - Email:<info@email.com> - Website:<http://xoops.org>
 */

use XoopsModules\Mymodule;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object Categories
 */
class Categories extends \XoopsObject
{
	/**
	 * Constructor 
	 *
	 * @param null
	 */
	public function __construct()
	{
		$this->initVar('cat_id', XOBJ_DTYPE_INT);
		$this->initVar('cat_name', XOBJ_DTYPE_TXTBOX);
		$this->initVar('cat_logo', XOBJ_DTYPE_TXTBOX);
		$this->initVar('cat_created', XOBJ_DTYPE_INT);
		$this->initVar('cat_submitter', XOBJ_DTYPE_INT);
	}

	/**
	 * @static function &getInstance
	 *
	 * @param null
	 */
	public static function getInstance()
	{
		static $instance = false;
		if(!$instance) {
			$instance = new self();
		}
	}

	/**
	 * The new inserted $Id
	 * @return inserted id
	 */
	public function getNewInsertedIdCategories()
	{
		$newInsertedId = $GLOBALS['xoopsDB']->getInsertId();
		return $newInsertedId;
	}

	/**
	 * @public function getForm
	 * @param bool $action
	 * @return XoopsThemeForm
	 */
	public function getFormCategories($action = false)
	{
		$helper = \XoopsModules\Mymodule\Helper::getInstance();
		if(false === $action) {
			$action = $_SERVER['REQUEST_URI'];
		}
		// Title
		$title = $this->isNew() ? sprintf(_AM_MYMODULE_CATEGORY_ADD) : sprintf(_AM_MYMODULE_CATEGORY_EDIT);
		// Get Theme Form
		xoops_load('XoopsFormLoader');
		$form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
		$form->setExtra('enctype="multipart/form-data"');
		// Form Text CatName
		$form->addElement(new \XoopsFormText( _AM_MYMODULE_CATEGORY_NAME, 'cat_name', 50, 255, $this->getVar('cat_name') ), true);
		// Form Text Date Select CatCreated
		$catCreated = $this->isNew() ? 0 : $this->getVar('cat_created');
		$form->addElement(new \XoopsFormTextDateSelect( _AM_MYMODULE_CATEGORY_CREATED, 'cat_created', '', $catCreated ), true);
		// Permissions
		$memberHandler = xoops_getHandler('member');
		$groupList = $memberHandler->getGroupList();
		$gpermHandler = xoops_getHandler('groupperm');
		$fullList[] = array_keys($groupList);
		if(!$this->isNew()) {
			$groupsIdsApprove = $gpermHandler->getGroupIds('mymodule_approve', $this->getVar('cat_id'), $GLOBALS['xoopsModule']->getVar('mid'));
			$groupsIdsApprove[] = array_values($groupsIdsApprove);
			$groupsCanApproveCheckbox = new \XoopsFormCheckBox( _AM_MYMODULE_PERMISSIONS_APPROVE, 'groups_approve[]', $groupsIdsApprove);
			$groupsIdsSubmit = $gpermHandler->getGroupIds('mymodule_submit', $this->getVar('cat_id'), $GLOBALS['xoopsModule']->getVar('mid'));
			$groupsIdsSubmit[] = array_values($groupsIdsSubmit);
			$groupsCanSubmitCheckbox = new \XoopsFormCheckBox( _AM_MYMODULE_PERMISSIONS_SUBMIT, 'groups_submit[]', $groupsIdsSubmit);
			$groupsIdsView = $gpermHandler->getGroupIds('mymodule_view', $this->getVar('cat_id'), $GLOBALS['xoopsModule']->getVar('mid'));
			$groupsIdsView[] = array_values($groupsIdsView);
			$groupsCanViewCheckbox = new \XoopsFormCheckBox( _AM_MYMODULE_PERMISSIONS_VIEW, 'groups_view[]', $groupsIdsView);
		} else {
			$groupsCanApproveCheckbox = new \XoopsFormCheckBox( _AM_MYMODULE_PERMISSIONS_APPROVE, 'groups_approve[]', $fullList);
			$groupsCanSubmitCheckbox = new \XoopsFormCheckBox( _AM_MYMODULE_PERMISSIONS_SUBMIT, 'groups_submit[]', $fullList);
			$groupsCanViewCheckbox = new \XoopsFormCheckBox( _AM_MYMODULE_PERMISSIONS_VIEW, 'groups_view[]', $fullList);
		}
		// To Approve
		$groupsCanApproveCheckbox->addOptionArray($groupList);
		$form->addElement($groupsCanApproveCheckbox);
		// To Submit
		$groupsCanSubmitCheckbox->addOptionArray($groupList);
		$form->addElement($groupsCanSubmitCheckbox);
		// To View
		$groupsCanViewCheckbox->addOptionArray($groupList);
		$form->addElement($groupsCanViewCheckbox);
		// To Save
		$form->addElement(new \XoopsFormHidden('op', 'save'));
		$form->addElement(new \XoopsFormButtonTray('', _SUBMIT, 'submit', '', false));
		return $form;
	}

	/**
	 * Get Values
	 * @param null $keys 
	 * @param null $format 
	 * @param null$maxDepth 
	 * @return array
	 */
	public function getValuesCategories($keys = null, $format = null, $maxDepth = null)
	{
		$helper = \XoopsModules\Mymodule\Helper::getInstance();
		$ret = $this->getValues($keys, $format, $maxDepth);
		$ret['id'] = $this->getVar('cat_id');
		$ret['name'] = $this->getVar('cat_name');
		$ret['logo'] = $this->getVar('cat_logo');
		$ret['created'] = formatTimeStamp($this->getVar('cat_created'), 's');
		$ret['submitter'] = \XoopsUser::getUnameFromId($this->getVar('cat_submitter'));
		return $ret;
	}

	/**
	 * Returns an array representation of the object
	 *
	 * @return array
	 */
	public function toArrayCategories()
	{
		$ret = [];
		$vars = $this->getVars();
		foreach(array_keys($vars) as $var) {
			$ret[$var] = $this->getVar('"{$var}"');
		}
		return $ret;
	}
}
