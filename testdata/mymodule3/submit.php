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

use Xmf\Request;
use XoopsModules\Mymodule3;
use XoopsModules\Mymodule3\Constants;

require __DIR__ . '/header.php';
xoops_loadLanguage('admin', 'mymodule3');
// It recovered the value of argument op in URL$
$op = Request::getString('op', 'form');
// Template
$GLOBALS['xoopsOption']['template_main'] = 'mymodule3_submit.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';
$GLOBALS['xoTheme']->addStylesheet( $style, null );
$permissionsHandler = $helper->getHandler('permissions');
$permSubmit = $permissionsHandler->getPermGlobalSubmit();
// Redirection if not permissions
if ($permSubmit === false) {
	redirect_header('index.php', 2, _NOPERM);
	exit();
}
switch($op) {
	case 'form':
	default:
		// Navigation
		$navigation = _MA_MYMODULE3_SUBMIT_PROPOSER;
		$GLOBALS['xoopsTpl']->assign('navigation', $navigation);
		// Title of page
		$title = _MA_MYMODULE3_SUBMIT_PROPOSER . '&nbsp;-&nbsp;';
		$title .= $GLOBALS['xoopsModule']->name();
		$GLOBALS['xoopsTpl']->assign('xoops_pagetitle', $title);
		// Description
		$GLOBALS['xoTheme']->addMeta( 'meta', 'description', strip_tags(_MA_MYMODULE3_SUBMIT_PROPOSER));
		// Form Create
		$testfieldsObj = $testfieldsHandler->create();
		$form = $testfieldsObj->getFormTestfields();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());
	break;
	case 'save':
		// Security Check
		if (!$GLOBALS['xoopsSecurity']->check()) {
			redirect_header('testfields.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
		}
		$testfieldsObj = $testfieldsHandler->create();
		$testfieldsObj->setVar('tf_text', Request::getString('tf_text', ''));
		$testfieldsObj->setVar('tf_textarea', Request::getString('tf_textarea', ''));
		$testfieldsObj->setVar('tf_dhtml', Request::getString('tf_dhtml', ''));
		$testfieldsObj->setVar('tf_checkbox', Request::getInt('tf_checkbox', 0));
		$testfieldsObj->setVar('tf_yesno', Request::getInt('tf_yesno', 0));
		$testfieldsObj->setVar('tf_selectbox', Request::getInt('tf_selectbox', 0));
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
		$uploader = new \XoopsMediaUploader(MYMODULE3_UPLOAD_FILES_PATH . '/testfields/', 
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
		$uploader = new \XoopsMediaUploader(MYMODULE3_UPLOAD_IMAGE_PATH . '/testfields/', 
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
					$imgHandler                = new Mymodule3\Common\Resizer();
					$imgHandler->sourceFile    = MYMODULE3_UPLOAD_IMAGE_PATH . '/testfields/' . $savedFilename;
					$imgHandler->endFile       = MYMODULE3_UPLOAD_IMAGE_PATH . '/testfields/' . $savedFilename;
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
		$uploader = new \XoopsMediaUploader(MYMODULE3_UPLOAD_FILES_PATH . '/testfields/', 
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
		$uploader = new \XoopsMediaUploader(MYMODULE3_UPLOAD_FILES_PATH . '/testfields/', 
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
		$tfPassword = Request::getString('tf_password', '');
		if ('' !== $tfPassword) {
			$testfieldsObj->setVar('tf_password', password_hash($tfPassword, PASSWORD_DEFAULT));
		}
		$testfieldsObj->setVar('tf_country_list', Request::getString('tf_country_list', ''));
		$testfieldsObj->setVar('tf_language', Request::getString('tf_language', ''));
		$testfieldsObj->setVar('tf_radio', Request::getInt('tf_radio', 0));
		$testfieldsObj->setVar('tf_status', Request::getInt('tf_status', 0));
		$testfieldDatetimeArr = Request::getArray('tf_datetime');
		$testfieldDatetime = strtotime($testfieldDatetimeArr['date']) + (int)$testfieldDatetimeArr['time'];
		$testfieldsObj->setVar('tf_datetime', $testfieldDatetime);
		$testfieldsObj->setVar('tf_combobox', Request::getInt('tf_combobox', 0));
		// Insert Data
		if ($testfieldsHandler->insert($testfieldsObj)) {
			$newTfId = $testfieldsObj->getNewInsertedIdTestfields();
			$permId = isset($_REQUEST['tf_id']) ? $tfId : $newTfId;
			$grouppermHandler = xoops_getHandler('groupperm');
			$mid = $GLOBALS['xoopsModule']->getVar('mid');
			// Permission to view_testfields
			$grouppermHandler->deleteByModule($mid, 'mymodule3_view_testfields', $permId);
			if (isset($_POST['groups_view_testfields'])) {
				foreach($_POST['groups_view_testfields'] as $onegroupId) {
					$grouppermHandler->addRight('mymodule3_view_testfields', $permId, $onegroupId, $mid);
				}
			}
			// Permission to submit_testfields
			$grouppermHandler->deleteByModule($mid, 'mymodule3_submit_testfields', $permId);
			if (isset($_POST['groups_submit_testfields'])) {
				foreach($_POST['groups_submit_testfields'] as $onegroupId) {
					$grouppermHandler->addRight('mymodule3_submit_testfields', $permId, $onegroupId, $mid);
				}
			}
			// Permission to approve_testfields
			$grouppermHandler->deleteByModule($mid, 'mymodule3_approve_testfields', $permId);
			if (isset($_POST['groups_approve_testfields'])) {
				foreach($_POST['groups_approve_testfields'] as $onegroupId) {
					$grouppermHandler->addRight('mymodule3_approve_testfields', $permId, $onegroupId, $mid);
				}
			}
			if ('' !== $uploaderErrors) {
				redirect_header('testfields.php?op=edit&tf_id=' . $tfId, 5, $uploaderErrors);
			} else {
				redirect_header('testfields.php?op=list', 2, _MA_MYMODULE3_FORM_OK);
			}
		}
		// Get Form Error
		$GLOBALS['xoopsTpl']->assign('error', $testfieldsObj->getHtmlErrors());
		$form = $testfieldsObj->getFormTestfields();
		$GLOBALS['xoopsTpl']->assign('form', $form->display());
	break;
}
// Breadcrumbs
$xoBreadcrumbs[] = ['title' => _MA_MYMODULE3_SUBMIT];
require __DIR__ . '/footer.php';
