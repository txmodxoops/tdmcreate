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
// It recovered the value of argument op in URL$
$op = Request::getString('op', 'list');
// Request art_id
$artId = Request::getInt('art_id');
switch($op) {
	case 'list':
	default:
		// Define Stylesheet
		$GLOBALS['xoTheme']->addStylesheet( $style, null );
		$start = Request::getInt('start', 0);
		$limit = Request::getInt('limit', $helper->getConfig('adminpager'));
		$templateMain = 'mymodule_admin_articles.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('articles.php'));
		$adminObject->addItemButton(_AM_MYMODULE_ADD_ARTICLE, 'articles.php?op=new', 'add');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
		$articlesCount = $articlesHandler->getCountArticles();
		$articlesAll = $articlesHandler->getAllArticles($start, $limit);
		$GLOBALS['xoopsTpl']->assign('articles_count', $articlesCount);
		$GLOBALS['xoopsTpl']->assign('mymodule_url', MYMODULE_URL);
		$GLOBALS['xoopsTpl']->assign('mymodule_upload_url', MYMODULE_UPLOAD_URL);
		// Table view articles
		if($articlesCount > 0) {
			foreach(array_keys($articlesAll) as $i) {
				$article = $articlesAll[$i]->getValuesArticles();
				$GLOBALS['xoopsTpl']->append('articles_list', $article);
				unset($article);
			}
			// Display Navigation
			if($articlesCount > $limit) {
				include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
				$pagenav = new \XoopsPageNav($articlesCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
				$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
			}
		} else {
			$GLOBALS['xoopsTpl']->assign('error', _AM_MYMODULE_THEREARENT_ARTICLES);
		}

	break;
	case 'new':
		$templateMain = 'mymodule_admin_articles.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('articles.php'));
		$adminObject->addItemButton(_AM_MYMODULE_ARTICLES_LIST, 'articles.php', 'list');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
		// Get Form
		$articlesObj = $articlesHandler->create();
		$form = $articlesObj->getFormArticles();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());

	break;
	case 'save':
		// Security Check
		if(!$GLOBALS['xoopsSecurity']->check()) {
			redirect_header('articles.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
		}
		if(isset($artId)) {
			$articlesObj = $articlesHandler->get($artId);
		} else {
			$articlesObj = $articlesHandler->create();
		}
		// Set Vars
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
		// Insert Data
		if($articlesHandler->insert($articlesObj)) {
			redirect_header('articles.php?op=list', 2, _AM_MYMODULE_FORM_OK);
		}
		// Get Form
		$GLOBALS['xoopsTpl']->assign('error', $articlesObj->getHtmlErrors());
		$form = $articlesObj->getFormArticles();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());

	break;
	case 'edit':
		$templateMain = 'mymodule_admin_articles.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('articles.php'));
		$adminObject->addItemButton(_AM_MYMODULE_ADD_ARTICLE, 'articles.php?op=new', 'add');
		$adminObject->addItemButton(_AM_MYMODULE_ARTICLES_LIST, 'articles.php', 'list');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
		// Get Form
		$articlesObj = $articlesHandler->get($artId);
		$form = $articlesObj->getFormArticles();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());

	break;
	case 'delete':
		$articlesObj = $articlesHandler->get($artId);
		if(isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
			if(!$GLOBALS['xoopsSecurity']->check()) {
				redirect_header('articles.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
			}
			if($articlesHandler->delete($articlesObj)) {
				redirect_header('articles.php', 3, _AM_MYMODULE_FORM_DELETE_OK);
			} else {
				$GLOBALS['xoopsTpl']->assign('error', $articlesObj->getHtmlErrors());
			}
		} else {
			xoops_confirm(array('ok' => 1, 'art_id' => $artId, 'op' => 'delete'), $_SERVER['REQUEST_URI'], sprintf(_AM_MYMODULE_FORM_SURE_DELETE, $articlesObj->getVar('art_title')));
		}

	break;
}
require __DIR__ . '/footer.php';
