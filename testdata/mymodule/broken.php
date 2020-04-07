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
 * My Module module for xoops
 *
 * @copyright     2020 XOOPS Project (https://xooops.org)
 * @license        GPL 2.0 or later
 * @package        mymodule
 * @since          1.0
 * @min_xoops      2.5.9
 * @author         TDM XOOPS - Email:<info@email.com> - Website:<http://xoops.org>
 */

use Xmf\Request;
use XoopsModules\Mymodule;
use XoopsModules\Mymodule\Constants;

require __DIR__ . '/header.php';
$op = Request::getString('op', 'list');
$artId = Request::getInt('art_id');
// Template
$GLOBALS['xoopsOption']['template_main'] = 'mymodule_broken.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';
$GLOBALS['xoTheme']->addStylesheet( $style, null );
// Redirection if not permissions
if($permSubmit === false) {
	redirect_header('index.php', 2, _NOPERM);
	exit();
}
switch($op) {
	case 'form':
	default:
		// Navigation
		$navigation = _MA_MYMODULE_SUBMIT_PROPOSER;
		$GLOBALS['xoopsTpl']->assign('navigation', $navigation);
		// Title of page
		$title = _MA_MYMODULE_SUBMIT_PROPOSER . '&nbsp;-&nbsp;';
		$title .= $GLOBALS['xoopsModule']->name();
		$GLOBALS['xoopsTpl']->assign('xoops_pagetitle', $title);
		// Description
		$GLOBALS['xoTheme']->addMeta( 'meta', 'description', strip_tags(_MA_MYMODULE_SUBMIT_PROPOSER));
		// Form Create
		$articlesObj = $articlesHandler->create();
		$form = $articlesObj->getFormArticles();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());

	break;
	case 'save':
		// Security Check
		if($GLOBALS['xoopsSecurity']->check()) {
			redirect_header('articles.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
		}
		$articlesObj = $articlesHandler->create();
		$error = false;
		$errorMessage = '';
		// Test first the validation
		xoops_load('captcha');
		$xoopsCaptcha = \XoopsCaptcha::getInstance();
		if(!$xoopsCaptcha->verify()) {
			$errorMessage .= $xoopsCaptcha->getMessage().'<br>';
			$error = true;
		}
		$articlesObj->setVar('art_cat', Request::getInt('art_cat', 0));
		$articlesObj->setVar('art_title', Request::getString('art_title', ''));
		$articlesObj->setVar('art_descr', Request::getString('art_descr', ''));
		// Set Var art_img
		include_once XOOPS_ROOT_PATH . '/class/uploader.php';
		$uploader = new \XoopsMediaUploader(MYMODULE_UPLOAD_IMAGE_PATH . '/articles/', 
													$helper->getConfig('mimetypes'), 
													$helper->getConfig('maxsize'), null, null);
		if($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
			$extension = preg_replace('/^.+\.([^.]+)$/sU', '', $_FILES['attachedfile']['name']);
			$imgName = str_replace(' ', '', Request::getString('art_title')) . '.' . $extension;
			$uploader->setPrefix($imgName);
			$uploader->fetchMedia($_POST['xoops_upload_file'][0]);
			if(!$uploader->upload()) {
				$errors = $uploader->getErrors();
				redirect_header('javascript:history.go(-1).php', 3, $errors);
			} else {
				$articlesObj->setVar('art_img', $uploader->getSavedFileName());
			}
		} else {
			$articlesObj->setVar('art_img', Request::getString('art_img'));
		}
		$articlesObj->setVar('art_online', Request::getInt('art_online', 0));
		// Set Var art_file
		include_once XOOPS_ROOT_PATH . '/class/uploader.php';
		$uploader = new \XoopsMediaUploader(MYMODULE_UPLOAD_FILES_PATH . '/articles/', 
													$helper->getConfig('mimetypes'), 
													$helper->getConfig('maxsize'), null, null);
		if($uploader->fetchMedia($_POST['xoops_upload_file'][1])) {
			$extension = preg_replace('/^.+\.([^.]+)$/sU', '', $_FILES['art_file']['name']);
			$imgName = str_replace(' ', '', Request::getString('art_title')) . '.' . $extension;
			$uploader->setPrefix($imgName);
			$uploader->fetchMedia($_POST['xoops_upload_file'][1]);
			if(!$uploader->upload()) {
				$errors = $uploader->getErrors();
				redirect_header('javascript:history.go(-1).php', 3, $errors);
			} else {
				$articlesObj->setVar('art_file', $uploader->getSavedFileName());
			}
		} else {
			$articlesObj->setVar('art_file', Request::getString('art_file'));
		}
		$articleCreated = date_create_from_format(_SHORTDATESTRING, $_POST['art_created']);
		$articlesObj->setVar('art_created', $articleCreated->getTimestamp());
		$articlesObj->setVar('art_submitter', Request::getInt('art_submitter', 0));
		if($error === true) {
			$GLOBALS['xoopsTpl']->assign('error_message', $errorMessage);
		} else {
			// Insert Data
			if($articles1->insert($articlesObj)) {
				redirect_header('index.php', 2, _MA_MYMODULE_FORM_OK);
			}
		}
		// Get Form Error
		$GLOBALS['xoopsTpl']->assign('error', $articlesObj->getHtmlErrors());
		$form = $articlesObj->getFormArticles();
		$GLOBALS['xoopsTpl']->assign('form', $form->display());

	break;
}
require __DIR__ . '/footer.php';
