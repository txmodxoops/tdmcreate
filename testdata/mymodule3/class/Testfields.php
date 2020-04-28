<?php

namespace XoopsModules\Mymodule3;

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

use XoopsModules\Mymodule3;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object Testfields
 */
class Testfields extends \XoopsObject
{
	/**
	 * Constructor 
	 *
	 * @param null
	 */
	public function __construct()
	{
		$this->initVar('tf_id', XOBJ_DTYPE_INT);
		$this->initVar('tf_text', XOBJ_DTYPE_TXTBOX);
		$this->initVar('tf_textarea', XOBJ_DTYPE_TXTBOX);
		$this->initVar('tf_dhtml', XOBJ_DTYPE_TXTBOX);
		$this->initVar('tf_checkbox', XOBJ_DTYPE_INT);
		$this->initVar('tf_yesno', XOBJ_DTYPE_INT);
		$this->initVar('tf_selectbox', XOBJ_DTYPE_TXTBOX);
		$this->initVar('tf_user', XOBJ_DTYPE_INT);
		$this->initVar('tf_color', XOBJ_DTYPE_TXTBOX);
		$this->initVar('tf_imagelist', XOBJ_DTYPE_TXTBOX);
		$this->initVar('tf_urlfile', XOBJ_DTYPE_TXTBOX);
		$this->initVar('tf_uplimage', XOBJ_DTYPE_TXTBOX);
		$this->initVar('tf_uplfile', XOBJ_DTYPE_TXTBOX);
		$this->initVar('tf_textdateselect', XOBJ_DTYPE_TXTBOX);
		$this->initVar('tf_selectfile', XOBJ_DTYPE_TXTBOX);
		$this->initVar('tf_password', XOBJ_DTYPE_TXTBOX);
		$this->initVar('tf_country_list', XOBJ_DTYPE_TXTBOX);
		$this->initVar('tf_language', XOBJ_DTYPE_TXTBOX);
		$this->initVar('tf_radio', XOBJ_DTYPE_TXTBOX);
		$this->initVar('tf_status', XOBJ_DTYPE_TXTBOX);
		$this->initVar('tf_datetime', XOBJ_DTYPE_INT);
		$this->initVar('tf_combobox', XOBJ_DTYPE_INT);
	}

	/**
	 * @static function &getInstance
	 *
	 * @param null
	 */
	public static function getInstance()
	{
		static $instance = false;
		if (!$instance) {
			$instance = new self();
		}
	}

	/**
	 * The new inserted $Id
	 * @return inserted id
	 */
	public function getNewInsertedIdTestfields()
	{
		$newInsertedId = $GLOBALS['xoopsDB']->getInsertId();
		return $newInsertedId;
	}

	/**
	 * @public function getForm
	 * @param bool $action
	 * @return \XoopsThemeForm
	 */
	public function getFormTestfields($action = false)
	{
		$helper = \XoopsModules\Mymodule3\Helper::getInstance();
		if (false === $action) {
			$action = $_SERVER['REQUEST_URI'];
		}
		// Permissions for uploader
		$grouppermHandler = xoops_getHandler('groupperm');
		$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
		if ($GLOBALS['xoopsUser']) {
			if (!$GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid())) {
				$permissionUpload = $grouppermHandler->checkRight('upload_groups', 32, $groups, $GLOBALS['xoopsModule']->getVar('mid')) ? true : false;
			} else {
				$permissionUpload = true;
			}
		} else {
			$permissionUpload = $grouppermHandler->checkRight('upload_groups', 32, $groups, $GLOBALS['xoopsModule']->getVar('mid')) ? true : false;
		}
		// Title
		$title = $this->isNew() ? sprintf(_AM_MYMODULE3_TESTFIELD_ADD) : sprintf(_AM_MYMODULE3_TESTFIELD_EDIT);
		// Get Theme Form
		xoops_load('XoopsFormLoader');
		$form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
		$form->setExtra('enctype="multipart/form-data"');
		// Form Text tfText
		$form->addElement(new \XoopsFormText( _AM_MYMODULE3_TESTFIELD_TEXT, 'tf_text', 50, 255, $this->getVar('tf_text') ));
		// Form Editor TextArea tfTextarea
		$form->addElement(new \XoopsFormTextArea( _AM_MYMODULE3_TESTFIELD_TEXTAREA, 'tf_textarea', $this->getVar('tf_textarea'), 4, 47 ));
		// Form Editor DhtmlTextArea tfDhtml
		$editorConfigs = [];
		$editorConfigs['name'] = 'tf_dhtml';
		$editorConfigs['value'] = $this->getVar('tf_dhtml', 'e');
		$editorConfigs['rows'] = 5;
		$editorConfigs['cols'] = 40;
		$editorConfigs['width'] = '100%';
		$editorConfigs['height'] = '400px';
		$editorConfigs['editor'] = $helper->getConfig('editor_dhtml');
		$form->addElement(new \XoopsFormEditor( _AM_MYMODULE3_TESTFIELD_DHTML, 'tf_dhtml', $editorConfigs));
		// Form Check Box tfCheckbox
		$tfCheckbox = $this->isNew() ? 0 : $this->getVar('tf_checkbox');
		$checkTfCheckbox = new \XoopsFormCheckBox( _AM_MYMODULE3_TESTFIELD_CHECKBOX, 'tf_checkbox', $tfCheckbox);
		$checkTfCheckbox->addOption(1, _AM_MYMODULE3_TESTFIELD_CHECKBOX);
		$form->addElement($checkTfCheckbox);
		// Form Radio Yes/No tfYesno
		$tfYesno = $this->isNew() ? 0 : $this->getVar('tf_yesno');
		$form->addElement(new \XoopsFormRadioYN( _AM_MYMODULE3_TESTFIELD_YESNO, 'tf_yesno', $tfYesno));
		// Testfields handler
		$testfieldsHandler = $helper->getHandler('testfields');
		// Form Select tfSelectbox
		$tfSelectboxSelect = new \XoopsFormSelect( _AM_MYMODULE3_TESTFIELD_SELECTBOX, 'tf_selectbox', $this->getVar('tf_selectbox'));
		$tfSelectboxSelect->addOption('Empty');
		$tfSelectboxSelect->addOptionArray($testfieldsHandler->getList());
		$form->addElement($tfSelectboxSelect);
		// Form Select User tfUser
		$form->addElement(new \XoopsFormSelectUser( _AM_MYMODULE3_TESTFIELD_USER, 'tf_user', false, $this->getVar('tf_user') ));
		// Form Color Picker tfColor
		$form->addElement(new \XoopsFormColorPicker( _AM_MYMODULE3_TESTFIELD_COLOR, 'tf_color', $this->getVar('tf_color') ));
		// Form Frameworks Images Files tfImagelist
		// Form Frameworks Images tfImagelist: Select Uploaded Image
		$getTfImagelist = $this->getVar('tf_imagelist');
		$tfImagelist = $getTfImagelist ? $getTfImagelist : 'blank.gif';
		$imageDirectory = '/Frameworks/moduleclasses/icons/32';
		$imageTray = new \XoopsFormElementTray(_AM_MYMODULE3_TESTFIELD_IMAGELIST, '<br>' );
		$imageSelect = new \XoopsFormSelect( sprintf(_AM_MYMODULE3_TESTFIELD_IMAGELIST_UPLOADS, ".{$imageDirectory}/"), 'tf_imagelist', $tfImagelist, 5);
		$imageArray = \XoopsLists::getImgListAsArray( XOOPS_ROOT_PATH . $imageDirectory );
		foreach($imageArray as $image1) {
			$imageSelect->addOption("{$image1}", $image1);
		}
		$imageSelect->setExtra("onchange='showImgSelected(\"imglabel_tf_imagelist\", \"tf_imagelist\", \"" . $imageDirectory . "\", \"\", \"" . XOOPS_URL . "\")'");
		$imageTray->addElement($imageSelect, false);
		$imageTray->addElement(new \XoopsFormLabel('', "<br><img src='" . XOOPS_URL . "/" . $imageDirectory . "/" . $tfImagelist . "' id='imglabel_tf_imagelist' alt='' style='max-width:100px' />"));
		// Form Frameworks Images tfImagelist: Upload new image
		$fileSelectTray = new \XoopsFormElementTray('', '<br>' );
		$fileSelectTray->addElement(new \XoopsFormFile( _AM_MYMODULE3_FORM_UPLOAD_NEW, 'tf_imagelist', $helper->getConfig('maxsize_image') ));
		$fileSelectTray->addElement(new \XoopsFormLabel(''));
		$imageTray->addElement($fileSelectTray);
		$form->addElement($imageTray);
		// Form Url Text File tfUrlfile
		$formUrlFile = new \XoopsFormElementTray(_AM_MYMODULE3_TESTFIELD_URLFILE, '<br><br>' );
		$formUrl = $this->isNew() ? '' : $this->getVar('tf_urlfile');
		$formText = new \XoopsFormText( _AM_MYMODULE3_TESTFIELD_URLFILE_UPLOADS, 'tf_urlfile', 75, 255, $formUrl );
		$formUrlFile->addElement($formText);
		$formUrlFile->addElement(new \XoopsFormFile( _AM_MYMODULE3_FORM_UPLOAD, 'tf_urlfile', $helper->getConfig('maxsize_file') ));
		$form->addElement($formUrlFile);
		// Form Image tfUplimage
		// Form Image tfUplimage: Select Uploaded Image 
		$getTfUplimage = $this->getVar('tf_uplimage');
		$tfUplimage = $getTfUplimage ? $getTfUplimage : 'blank.gif';
		$imageDirectory = '/uploads/mymodule3/images/testfields';
		$imageTray = new \XoopsFormElementTray(_AM_MYMODULE3_TESTFIELD_UPLIMAGE, '<br>' );
		$imageSelect = new \XoopsFormSelect( sprintf(_AM_MYMODULE3_TESTFIELD_UPLIMAGE_UPLOADS, ".{$imageDirectory}/"), 'tf_uplimage', $tfUplimage, 5);
		$imageArray = \XoopsLists::getImgListAsArray( XOOPS_ROOT_PATH . $imageDirectory );
		foreach($imageArray as $image1) {
			$imageSelect->addOption("{$image1}", $image1);
		}
		$imageSelect->setExtra("onchange='showImgSelected(\"imglabel_tf_uplimage\", \"tf_uplimage\", \"" . $imageDirectory . "\", \"\", \"" . XOOPS_URL . "\")'");
		$imageTray->addElement($imageSelect, false);
		$imageTray->addElement(new \XoopsFormLabel('', "<br><img src='" . XOOPS_URL . "/" . $imageDirectory . "/" . $tfUplimage . "' id='imglabel_tf_uplimage' alt='' style='max-width:100px' />"));
		// Form Image tfUplimage: Upload new image
		if ($permissionUpload) {
			$maxsize = $helper->getConfig('maxsize_image');
			$imageTray->addElement(new \XoopsFormFile( '<br>' . _AM_MYMODULE3_FORM_UPLOAD_NEW, 'tf_uplimage', $maxsize ));
			$imageTray->addElement(new \XoopsFormLabel(_AM_MYMODULE3_FORM_UPLOAD_SIZE, ($maxsize / 1048576) . ' '  . _AM_MYMODULE3_FORM_UPLOAD_SIZE_MB));
			$imageTray->addElement(new \XoopsFormLabel(_AM_MYMODULE3_FORM_UPLOAD_IMG_WIDTH, $helper->getConfig('maxwidth_image') . ' px'));
			$imageTray->addElement(new \XoopsFormLabel(_AM_MYMODULE3_FORM_UPLOAD_IMG_HEIGHT, $helper->getConfig('maxheight_image') . ' px'));
		} else {
			$imageTray->addElement(new \XoopsFormHidden( 'tf_uplimage', $tfUplimage ));
		}
		$form->addElement($imageTray, );
		// Form File Upload tfUplfile
		$tfUplfile = $this->isNew() ? '' : $this->getVar('tf_uplfile');
		if ($permissionUpload) {
			$fileUploadTray = new \XoopsFormElementTray(_AM_MYMODULE3_TESTFIELD_UPLFILE, '<br>' );
			$fileDirectory = '/uploads/mymodule3/files/testfields';
			if (!$this->isNew()) {
				$fileUploadTray->addElement(new \XoopsFormLabel(sprintf(_AM_MYMODULE3_TESTFIELD_UPLFILE_UPLOADS, ".{$fileDirectory}/"), $tfUplfile));
			}
			$maxsize = $helper->getConfig('maxsize_file');
			$fileUploadTray->addElement(new \XoopsFormFile( '', 'tf_uplfile', $maxsize ));
			$fileUploadTray->addElement(new \XoopsFormLabel(_AM_MYMODULE3_FORM_UPLOAD_SIZE, ($maxsize / 1048576) . ' '  . _AM_MYMODULE3_FORM_UPLOAD_SIZE_MB));
			$form->addElement($fileUploadTray, );
		} else {
			$form->addElement(new \XoopsFormHidden( 'tf_uplfile', $tfUplfile ));
		}
		// Form Text Date Select tfTextdateselect
		$tfTextdateselect = $this->isNew() ? 0 : $this->getVar('tf_textdateselect');
		$form->addElement(new \XoopsFormTextDateSelect( _AM_MYMODULE3_TESTFIELD_TEXTDATESELECT, 'tf_textdateselect', '', $tfTextdateselect ));
		// Form File tfSelectfile
		// Form File tfSelectfile: Select Uploaded File 
		$getTfSelectfile = $this->getVar('tf_selectfile');
		$tfSelectfile = $getTfSelectfile ? $getTfSelectfile : 'blank.gif';
		$fileDirectory = '/uploads/mymodule3/files/testfields';
		$fileTray = new \XoopsFormElementTray(_AM_MYMODULE3_TESTFIELD_SELECTFILE, '<br>' );
		$fileSelect = new \XoopsFormSelect( sprintf(_AM_MYMODULE3_TESTFIELD_SELECTFILE_UPLOADS, ".{$fileDirectory}/"), 'tf_selectfile', $tfSelectfile, 5);
		$fileArray = \XoopsLists::getImgListAsArray( XOOPS_ROOT_PATH . $fileDirectory );
		foreach($fileArray as $file1) {
			$fileSelect->addOption("{$file1}", $file1);
		}
		$fileTray->addElement($fileSelect, false);
		// Form File tfSelectfile: Upload new file
		if ($permissionUpload) {
			$maxsize = $helper->getConfig('maxsize_file');
			$fileTray->addElement(new \XoopsFormFile( '<br>' . _AM_MYMODULE3_FORM_UPLOAD_NEW, 'tf_selectfile', $maxsize ));
			$fileTray->addElement(new \XoopsFormLabel(_AM_MYMODULE3_FORM_UPLOAD_SIZE, ($maxsize / 1048576) . ' '  . _AM_MYMODULE3_FORM_UPLOAD_SIZE_MB));
		} else {
			$fileTray->addElement(new \XoopsFormHidden( 'tf_selectfile', $tfSelectfile ));
		}
		$form->addElement($fileTray, );
		// Form Text Enter Password tfPassword
		$form->addElement(new \XoopsFormPassword( _AM_MYMODULE3_TESTFIELD_PASSWORD, 'tf_password', 10, 32 ));
		// Form Select Country tfCountry_list
		$tfCountry_listSelect = new \XoopsFormSelect( _AM_MYMODULE3_TESTFIELD_COUNTRY_LIST, 'tf_country_list', $this->getVar('tf_country_list'));
		$tfCountry_listSelect->addOption('', _NONE);
		$countryArray = \XoopsLists::getCountryList();
		$tfCountry_listSelect->addOptionArray($countryArray);
		$form->addElement($tfCountry_listSelect);
		// Form Select Lang tfLanguage
		$tfLanguageSelect = new \XoopsFormSelect( _AM_MYMODULE3_TESTFIELD_LANGUAGE, 'tf_language', $this->getVar('tf_language'));
		$tfLanguageSelect->addOption('', _NONE);
		$langArray = \XoopsLists::getLangList();
		$tfLanguageSelect->addOptionArray($langArray);
		$form->addElement($tfLanguageSelect);
		// Form Radio tfRadio
		$tfRadio = $this->isNew() ? 0 : $this->getVar('tf_radio');
		$tfRadioSelect = new \XoopsFormRadio( _AM_MYMODULE3_TESTFIELD_RADIO, 'tf_radio', $tfRadio);
		$tfRadioSelect->addOption('0', _NONE);
		$tfRadioSelect->addOption('1', _AM_MYMODULE3_LIST_1);
		$tfRadioSelect->addOption('2', _AM_MYMODULE3_LIST_2);
		$tfRadioSelect->addOption('3', _AM_MYMODULE3_LIST_3);
		$form->addElement($tfRadioSelect);
		// Form Select Status tfStatus
		$tfStatusSelect = new \XoopsFormSelect( _AM_MYMODULE3_TESTFIELD_STATUS, 'tf_status', $this->getVar('tf_status'));
		$tfStatusSelect->addOption(Constants::STATUS_NONE, _AM_MYMODULE3_STATUS_NONE);
		$tfStatusSelect->addOption(Constants::STATUS_OFFLINE, _AM_MYMODULE3_STATUS_OFFLINE);
		$tfStatusSelect->addOption(Constants::STATUS_SUBMITTED, _AM_MYMODULE3_STATUS_SUBMITTED);
		$tfStatusSelect->addOption(Constants::STATUS_APPROVED, _AM_MYMODULE3_STATUS_APPROVED);
		$form->addElement($tfStatusSelect, true);
		// Form Text Date Select tfDatetime
		$tfDatetime = $this->isNew() ? 0 : $this->getVar('tf_datetime');
		$form->addElement(new \XoopsFormDateTime( _AM_MYMODULE3_TESTFIELD_DATETIME, 'tf_datetime', '', $tfDatetime ));
		// Testfields handler
		$testfieldsHandler = $helper->getHandler('testfields');
		// Form Select tfCombobox
		$tfComboboxSelect = new \XoopsFormSelect( _AM_MYMODULE3_TESTFIELD_COMBOBOX, 'tf_combobox', $this->getVar('tf_combobox'), 5);
		$tfComboboxSelect->addOption('0', _NONE);
		$tfComboboxSelect->addOption('1', _AM_MYMODULE3_LIST_1);
		$tfComboboxSelect->addOption('2', _AM_MYMODULE3_LIST_2);
		$tfComboboxSelect->addOption('3', _AM_MYMODULE3_LIST_3);
		$form->addElement($tfComboboxSelect);
		// Permissions
		$memberHandler = xoops_getHandler('member');
		$groupList = $memberHandler->getGroupList();
		$grouppermHandler = xoops_getHandler('groupperm');
		$fullList[] = array_keys($groupList);
		if (!$this->isNew()) {
			$groupsIdsApprove = $grouppermHandler->getGroupIds('mymodule3_approve_testfields', $this->getVar('tf_id'), $GLOBALS['xoopsModule']->getVar('mid'));
			$groupsIdsApprove[] = array_values($groupsIdsApprove);
			$groupsCanApproveCheckbox = new \XoopsFormCheckBox( _AM_MYMODULE3_PERMISSIONS_APPROVE, 'groups_approve_testfields[]', $groupsIdsApprove);
			$groupsIdsSubmit = $grouppermHandler->getGroupIds('mymodule3_submit_testfields', $this->getVar('tf_id'), $GLOBALS['xoopsModule']->getVar('mid'));
			$groupsIdsSubmit[] = array_values($groupsIdsSubmit);
			$groupsCanSubmitCheckbox = new \XoopsFormCheckBox( _AM_MYMODULE3_PERMISSIONS_SUBMIT, 'groups_submit_testfields[]', $groupsIdsSubmit);
			$groupsIdsView = $grouppermHandler->getGroupIds('mymodule3_view_testfields', $this->getVar('tf_id'), $GLOBALS['xoopsModule']->getVar('mid'));
			$groupsIdsView[] = array_values($groupsIdsView);
			$groupsCanViewCheckbox = new \XoopsFormCheckBox( _AM_MYMODULE3_PERMISSIONS_VIEW, 'groups_view_testfields[]', $groupsIdsView);
		} else {
			$groupsCanApproveCheckbox = new \XoopsFormCheckBox( _AM_MYMODULE3_PERMISSIONS_APPROVE, 'groups_approve_testfields[]', $fullList);
			$groupsCanSubmitCheckbox = new \XoopsFormCheckBox( _AM_MYMODULE3_PERMISSIONS_SUBMIT, 'groups_submit_testfields[]', $fullList);
			$groupsCanViewCheckbox = new \XoopsFormCheckBox( _AM_MYMODULE3_PERMISSIONS_VIEW, 'groups_view_testfields[]', $fullList);
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
	public function getValuesTestfields($keys = null, $format = null, $maxDepth = null)
	{
		$helper = \XoopsModules\Mymodule3\Helper::getInstance();
		$ret = $this->getValues($keys, $format, $maxDepth);
		$ret['id'] = $this->getVar('tf_id');
		$ret['text'] = $this->getVar('tf_text');
		$ret['textarea'] = strip_tags($this->getVar('tf_textarea'));
		$ret['dhtml'] = strip_tags($this->getVar('tf_dhtml'));
		$ret['checkbox'] = $this->getVar('tf_checkbox');
		$ret['yesno'] = (int)$this->getVar('tf_yesno') > 0 ? _YES : _NO;
		$ret['selectbox'] = $this->getVar('tf_selectbox');
		$ret['user'] = \XoopsUser::getUnameFromId($this->getVar('tf_user'));
		$ret['color'] = $this->getVar('tf_color');
		$ret['imagelist'] = $this->getVar('tf_imagelist');
		$ret['urlfile'] = $this->getVar('tf_urlfile');
		$ret['uplimage'] = $this->getVar('tf_uplimage');
		$ret['uplfile'] = $this->getVar('tf_uplfile');
		$ret['textdateselect'] = formatTimeStamp($this->getVar('tf_textdateselect'), 's');
		$ret['selectfile'] = $this->getVar('tf_selectfile');
		$ret['password'] = $this->getVar('tf_password');
		$ret['country_list'] = $this->getVar('tf_country_list');
		$ret['language'] = $this->getVar('tf_language');
		$ret['radio'] = $this->getVar('tf_radio');
		$ret['status'] = $this->getVar('tf_status');
		$ret['datetime'] = formatTimeStamp($this->getVar('tf_datetime'), 'm');
		$ret['combobox'] = $this->getVar('tf_combobox');
		return $ret;
	}

	/**
	 * Returns an array representation of the object
	 *
	 * @return array
	 */
	public function toArrayTestfields()
	{
		$ret = [];
		$vars = $this->getVars();
		foreach(array_keys($vars) as $var) {
			$ret[$var] = $this->getVar('"{$var}"');
		}
		return $ret;
	}
}
