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
 * Class Object Articles
 */
class Articles extends \XoopsObject
{
	/**
	 * Constructor 
	 *
	 * @param null
	 */
	public function __construct()
	{
		$this->initVar('art_id', XOBJ_DTYPE_INT);
		$this->initVar('art_cat', XOBJ_DTYPE_INT);
		$this->initVar('art_title', XOBJ_DTYPE_TXTBOX);
		$this->initVar('art_descr', XOBJ_DTYPE_TXTAREA);
		$this->initVar('art_img', XOBJ_DTYPE_TXTBOX);
		$this->initVar('art_online', XOBJ_DTYPE_INT);
		$this->initVar('art_file', XOBJ_DTYPE_TXTBOX);
		$this->initVar('art_created', XOBJ_DTYPE_INT);
		$this->initVar('art_submitter', XOBJ_DTYPE_INT);
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
	public function getNewInsertedIdArticles()
	{
		$newInsertedId = $GLOBALS['xoopsDB']->getInsertId();
		return $newInsertedId;
	}

	/**
	 * @public function getForm
	 * @param bool $action
	 * @return XoopsThemeForm
	 */
	public function getFormArticles($action = false)
	{
		$helper = \XoopsModules\Mymodule\Helper::getInstance();
		if(false === $action) {
			$action = $_SERVER['REQUEST_URI'];
		}
		// Permissions for uploader
		$gpermHandler = xoops_getHandler('groupperm');
		$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
		if($GLOBALS['xoopsUser']) {
			if(!$GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid())) {
				$permissionUpload = $gpermHandler->checkRight('upload_groups', 32, $groups, $GLOBALS['xoopsModule']->getVar('mid')) ? true : false;
			} else {
				$permissionUpload = true;
			}
		} else {
			$permissionUpload = $gpermHandler->checkRight('upload_groups', 32, $groups, $GLOBALS['xoopsModule']->getVar('mid')) ? true : false;
		}
		// Title
		$title = $this->isNew() ? sprintf(_AM_MYMODULE_ARTICLE_ADD) : sprintf(_AM_MYMODULE_ARTICLE_EDIT);
		// Get Theme Form
		xoops_load('XoopsFormLoader');
		$form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
		$form->setExtra('enctype="multipart/form-data"');
		// Use tag module
		$dirTag = is_dir(XOOPS_ROOT_PATH . '/modules/tag') ? true : false;
		if(($helper->getConfig('usetag') == 1) && $dirTag) {
			$tagId = $this->isNew() ? 0 : $this->getVar('art_id');
			include_once XOOPS_ROOT_PATH . '/modules/tag/include/formtag.php';
			$form->addElement(new \XoopsFormTag( 'tag', 60, 255, $tagId, 0 ), true);
		}
		// Form Table categories
		$categoriesHandler = $helper->getHandler('categories');
		$artCatSelect = new \XoopsFormSelect( _AM_MYMODULE_ARTICLE_CAT, 'art_cat', $this->getVar('art_cat'));
		$artCatSelect->addOptionArray($categoriesHandler->getList());
		$form->addElement($artCatSelect, true);
		// Form Text ArtTitle
		$form->addElement(new \XoopsFormText( _AM_MYMODULE_ARTICLE_TITLE, 'art_title', 50, 255, $this->getVar('art_title') ), true);
		// Form editor ArtDescr
		$editorConfigs = [];
		$editorConfigs['name'] = 'art_descr';
		$editorConfigs['value'] = $this->getVar('art_descr', 'e');
		$editorConfigs['rows'] = 5;
		$editorConfigs['cols'] = 40;
		$editorConfigs['width'] = '100%';
		$editorConfigs['height'] = '400px';
		$editorConfigs['editor'] = $helper->getConfig('editor_descr');
		$form->addElement(new \XoopsFormEditor( _AM_MYMODULE_ARTICLE_DESCR, 'art_descr', $editorConfigs), true);
		// Form Image ArtImg
		// Form Image ArtImg: Select Uploaded Image 
		$getArtImg = $this->getVar('art_img');
		$artImg = $getArtImg ? $getArtImg : 'blank.gif';
		$imageDirectory = '/uploads/mymodule/images/articles';
		$imageTray = new \XoopsFormElementTray(_AM_MYMODULE_FORM_UPLOAD, '<br>' );
		$imageSelect = new \XoopsFormSelect( sprintf(_AM_MYMODULE_FORM_IMAGE_PATH, ".{$imageDirectory}/"), 'art_img', $artImg, 5);
		$imageArray = \XoopsLists::getImgListAsArray( XOOPS_ROOT_PATH . $imageDirectory );
		foreach($imageArray as $image1) {
			$imageSelect->addOption("{$image1}", $image1);
		}
		$imageSelect->setExtra("onchange='showImgSelected(\"image1\", \"art_img\", \"".$imageDirectory."\", \"\", \"".XOOPS_URL."\")'");
		$imageTray->addElement($imageSelect, false);
		$imageTray->addElement(new \XoopsFormLabel('', "<br><img src='".XOOPS_URL."/".$imageDirectory."/".$artImg."' name='image1' id='image1' alt='' style='max-width:100px' />"));
		// Form Image ArtImg: Upload Image
		if($permissionUpload) {
			$imageTray->addElement(new \XoopsFormFile( _AM_MYMODULE_FORM_UPLOAD_NEW, 'attachedfile', $helper->getConfig('maxsize') ));
		} else {
			$imageTray->addElement(new \XoopsFormHidden( 'art_img', $artImg ));
		}
		$form->addElement($imageTray);
		// Form Radio Yes/No ArtOnline
		$artOnline = $this->isNew() ? 0 : $this->getVar('art_online');
		$form->addElement(new \XoopsFormRadioYN( _AM_MYMODULE_ARTICLE_ONLINE, 'art_online', $artOnline), true);
		// Form File ArtFile
		$artFile = $this->isNew() ? '' : $this->getVar('art_file');
		if($permissionUpload) {
			$fileUploadTray = new \XoopsFormElementTray(_AM_MYMODULE_FORM_UPLOAD, '<br>' );
			if(!$this->isNew()) {
				$fileUploadTray->addElement(new \XoopsFormLabel(_AM_MYMODULE_FORM_UPLOAD_FILE_ARTICLES, $artFile));
			}
			$fileUploadTray->addElement(new \XoopsFormFile( '', 'art_file', $helper->getConfig('maxsize') ));
			$form->addElement($fileUploadTray);
		} else {
			$form->addElement(new \XoopsFormHidden( 'art_file', $artFile ));
		}
		// Form Text Date Select ArtCreated
		$artCreated = $this->isNew() ? 0 : $this->getVar('art_created');
		$form->addElement(new \XoopsFormTextDateSelect( _AM_MYMODULE_ARTICLE_CREATED, 'art_created', '', $artCreated ));
		// Form Select User ArtSubmitter
		$form->addElement(new \XoopsFormSelectUser( _AM_MYMODULE_ARTICLE_SUBMITTER, 'art_submitter', false, $this->getVar('art_submitter') ));
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
	public function getValuesArticles($keys = null, $format = null, $maxDepth = null)
	{
		$helper = \XoopsModules\Mymodule\Helper::getInstance();
		$ret = $this->getValues($keys, $format, $maxDepth);
		$ret['id'] = $this->getVar('art_id');
		$categories = $helper->getHandler('categories');
		$categoriesObj = $categories->get($this->getVar('art_cat'));
		$ret['cat'] = $categoriesObj->getVar('cat_name');
		$ret['title'] = $this->getVar('art_title');
		$ret['descr'] = strip_tags($this->getVar('art_descr'));
		$ret['img'] = $this->getVar('art_img');
		$ret['online'] = $this->getVar('art_online');
		$ret['file'] = $this->getVar('art_file');
		$ret['created'] = formatTimeStamp($this->getVar('art_created'), 's');
		$ret['submitter'] = \XoopsUser::getUnameFromId($this->getVar('art_submitter'));
		return $ret;
	}

	/**
	 * Returns an array representation of the object
	 *
	 * @return array
	 */
	public function toArrayArticles()
	{
		$ret = [];
		$vars = $this->getVars();
		foreach(array_keys($vars) as $var) {
			$ret[$var] = $this->getVar('"{$var}"');
		}
		return $ret;
	}
}
