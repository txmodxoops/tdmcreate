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

use Xmf\Request;
use XoopsModules\Mymodule2;
use XoopsModules\Mymodule2\Constants;

require __DIR__ . '/header.php';
$op = Request::getString('op', 'list');
$tfId = Request::getInt('tf_id');
// Template
$GLOBALS['xoopsOption']['template_main'] = 'mymodule2_broken.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';
$GLOBALS['xoTheme']->addStylesheet( $style, null );
// Redirection if not permissions
if ($permSubmit === false) {
	redirect_header('index.php', 2, _NOPERM);
	exit();
}
switch($op) {
	case 'form':
	default:
		// Navigation
		$navigation = _MA_MYMODULE2_SUBMIT_PROPOSER;
		$GLOBALS['xoopsTpl']->assign('navigation', $navigation);
		// Title of page
		$title = _MA_MYMODULE2_SUBMIT_PROPOSER . '&nbsp;-&nbsp;';
		$title .= $GLOBALS['xoopsModule']->name();
		$GLOBALS['xoopsTpl']->assign('xoops_pagetitle', $title);
		// Description
		$GLOBALS['xoTheme']->addMeta( 'meta', 'description', strip_tags(_MA_MYMODULE2_SUBMIT_PROPOSER));
		// Form Create
		$testfieldsObj = $testfieldsHandler->create();
		$form = $testfieldsObj->getFormTestfields();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());

	break;
	case 'save':
		// Security Check
		if ($GLOBALS['xoopsSecurity']->check()) {
			redirect_header('testfields.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
		}
		$testfieldsObj = $testfieldsHandler->create();
		$error = false;
		$errorMessage = '';
		// Test first the validation
		xoops_load('captcha');
		$xoopsCaptcha = \XoopsCaptcha::getInstance();
		if (!$xoopsCaptcha->verify()) {
			$errorMessage .= $xoopsCaptcha->getMessage().'<br>';
			$error = true;
		}
		$testfieldsObj->setVar('tf_text', Request::getString('tf_text', ''));
		$testfieldsObj->setVar('tf_textarea', Request::getString('tf_textarea', ''));
		$testfieldsObj->setVar('tf_dhtml', Request::getString('tf_dhtml', ''));
		$testfieldsObj->setVar('tf_checkbox', Request::getInt('tf_checkbox', 0));
		$testfieldsObj->setVar('tf_yesno', Request::getInt('tf_yesno', 0));
		$testfieldsObj->setVar('tf_selectbox', Request::getString('tf_selectbox', ''));
		$testfieldsObj->setVar('tf_user', Request::getInt('tf_user', 0));
		$testfieldsObj->setVar('tf_color', Request::getString('tf_color', ''));
		// Set Var tf_imagelist
		include_once XOOPS_ROOT_PATH . '/class/uploader.php';
		$uploader = new \XoopsMediaUploader(XOOPS_ROOT_PATH . '/Frameworks/moduleclasses/icons/32', 
													$helper->getConfig('mimetypes_image'), 
													$helper->getConfig('maxsize_image'), null, null);
		if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
			//$uploader->setPrefix(tf_imagelist_);
			//$uploader->fetchMedia($_POST['xoops_upload_file'][0]);
			if (!$uploader->upload()) {
				$errors = $uploader->getErrors();
				redirect_header('javascript:history.go(-1).php', 3, $errors);
			} else {
				$testfieldsObj->setVar('tf_imagelist', $uploader->getSavedFileName());
			}
		} else {
			$testfieldsObj->setVar('tf_imagelist', Request::getString('tf_imagelist'));
		}
		$testfieldsObj->setVar('tf_urlfile', formatUrl($_REQUEST['tf_urlfile']));
		// Set Var tf_urlfile
		include_once XOOPS_ROOT_PATH . '/class/uploader.php';
		$filename       = $_FILES['tf_urlfile']['name'];
		$imgNameDef     = Request::getString('tf_text');
		$uploader = new \XoopsMediaUploader(MYMODULE2_UPLOAD_FILES_PATH . '/testfields/', 
													$helper->getConfig('mimetypes_file'), 
													$helper->getConfig('maxsize_file'), null, null);
		if ($uploader->fetchMedia($_POST['xoops_upload_file'][1])) {
			$extension = preg_replace('/^.+\.([^.]+)$/sU', '', $filename);
			$imgName = str_replace(' ', '', $imgNameDef) . '.' . $extension;
			$uploader->setPrefix($imgName);
			$uploader->fetchMedia($_POST['xoops_upload_file'][1]);
			if (!$uploader->upload()) {
				$errors = $uploader->getErrors();
			} else {
				$testfieldsObj->setVar('tf_urlfile', $uploader->getSavedFileName());
			}
		} else {
			if ($filename > '') {
				$uploaderErrors = $uploader->getErrors();
			}
			$testfieldsObj->setVar('tf_urlfile', Request::getString('tf_urlfile'));
		}
		// Set Var tf_uplimage
		include_once XOOPS_ROOT_PATH . '/class/uploader.php';
		$filename       = $_FILES['tf_uplimage']['name'];
		$imgMimetype    = $_FILES['tf_uplimage']['type'];
		$imgNameDef     = Request::getString('tf_text');
		$uploaderErrors = '';
		$uploader = new \XoopsMediaUploader(MYMODULE2_UPLOAD_IMAGE_PATH . '/testfields/', 
													$helper->getConfig('mimetypes_image'), 
													$helper->getConfig('maxsize_image'), null, null);
		if ($uploader->fetchMedia($_POST['xoops_upload_file'][2])) {
			$extension = preg_replace('/^.+\.([^.]+)$/sU', '', $filename);
			$imgName = str_replace(' ', '', $imgNameDef) . '.' . $extension;
			$uploader->setPrefix($imgName);
			$uploader->fetchMedia($_POST['xoops_upload_file'][2]);
			if (!$uploader->upload()) {
				$uploaderErrors = $uploader->getErrors();
			} else {
				$savedFilename = $uploader->getSavedFileName();
				$maxwidth  = (int)$helper->getConfig('maxwidth_image');
				$maxheight = (int)$helper->getConfig('maxheight_image');
				if ($maxwidth > 0 && $maxheight > 0) {
					// Resize image
					$imgHandler                = new Mymodule2\Common\Resizer();
					$imgHandler->sourceFile    = MYMODULE2_UPLOAD_IMAGE_PATH . '/testfields/' . $savedFilename;
					$imgHandler->endFile       = MYMODULE2_UPLOAD_IMAGE_PATH . '/testfields/' . $savedFilename;
					$imgHandler->imageMimetype = $imgMimetype;
					$imgHandler->maxWidth      = $maxwidth;
					$imgHandler->maxHeight     = $maxheight;
					$result                    = $imgHandler->resizeImage();
				}
				$testfieldsObj->setVar('tf_uplimage', $savedFilename);
			}
		} else {
			if ($filename > '') {
				$uploaderErrors = $uploader->getErrors();
			}
			$testfieldsObj->setVar('tf_uplimage', Request::getString('tf_uplimage'));
		}
		// Set Var tf_uplfile
		include_once XOOPS_ROOT_PATH . '/class/uploader.php';
		$filename       = $_FILES['tf_uplfile']['name'];
		$imgNameDef     = Request::getString('tf_text');
		$uploader = new \XoopsMediaUploader(MYMODULE2_UPLOAD_FILES_PATH . '/testfields/', 
													$helper->getConfig('mimetypes_file'), 
													$helper->getConfig('maxsize_file'), null, null);
		if ($uploader->fetchMedia($_POST['xoops_upload_file'][3])) {
			$extension = preg_replace('/^.+\.([^.]+)$/sU', '', $filename);
			$imgName = str_replace(' ', '', $imgNameDef) . '.' . $extension;
			$uploader->setPrefix($imgName);
			$uploader->fetchMedia($_POST['xoops_upload_file'][3]);
			if (!$uploader->upload()) {
				$errors = $uploader->getErrors();
			} else {
				$testfieldsObj->setVar('tf_uplfile', $uploader->getSavedFileName());
			}
		} else {
			if ($filename > '') {
				$uploaderErrors = $uploader->getErrors();
			}
			$testfieldsObj->setVar('tf_uplfile', Request::getString('tf_uplfile'));
		}
		$testfieldTextdateselect = date_create_from_format(_SHORTDATESTRING, Request::getString('tf_textdateselect'));
		$testfieldsObj->setVar('tf_textdateselect', $testfieldTextdateselect->getTimestamp());
		// Set Var tf_selectfile
		include_once XOOPS_ROOT_PATH . '/class/uploader.php';
		$filename       = $_FILES['tf_selectfile']['name'];
		$imgNameDef     = Request::getString('tf_text');
		$uploader = new \XoopsMediaUploader(MYMODULE2_UPLOAD_FILES_PATH . '/testfields/', 
													$helper->getConfig('mimetypes_file'), 
													$helper->getConfig('maxsize_file'), null, null);
		if ($uploader->fetchMedia($_POST['xoops_upload_file'][4])) {
			$extension = preg_replace('/^.+\.([^.]+)$/sU', '', $filename);
			$imgName = str_replace(' ', '', $imgNameDef) . '.' . $extension;
			$uploader->setPrefix($imgName);
			$uploader->fetchMedia($_POST['xoops_upload_file'][4]);
			if (!$uploader->upload()) {
				$errors = $uploader->getErrors();
			} else {
				$testfieldsObj->setVar('tf_selectfile', $uploader->getSavedFileName());
			}
		} else {
			if ($filename > '') {
				$uploaderErrors = $uploader->getErrors();
			}
			$testfieldsObj->setVar('tf_selectfile', Request::getString('tf_selectfile'));
		}
		if ($error === true) {
			$GLOBALS['xoopsTpl']->assign('error_message', $errorMessage);
		} else {
			// Insert Data
			if ($testfields1->insert($testfieldsObj)) {
				redirect_header('index.php', 2, _MA_MYMODULE2_FORM_OK);
			}
		}
		// Get Form Error
		$GLOBALS['xoopsTpl']->assign('error', $testfieldsObj->getHtmlErrors());
		$form = $testfieldsObj->getFormTestfields();
		$GLOBALS['xoopsTpl']->assign('form', $form->display());

	break;
}
require __DIR__ . '/footer.php';
