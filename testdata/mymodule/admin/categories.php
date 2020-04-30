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
// Request cat_id
$catId = Request::getInt('cat_id');
switch($op) {
	case 'list':
	default:
		// Define Stylesheet
		$GLOBALS['xoTheme']->addStylesheet( $style, null );
		$start = Request::getInt('start', 0);
		$limit = Request::getInt('limit', $helper->getConfig('adminpager'));
		$templateMain = 'mymodule_admin_categories.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('categories.php'));
		$adminObject->addItemButton(_AM_MYMODULE_ADD_CATEGORY, 'categories.php?op=new', 'add');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
		$categoriesCount = $categoriesHandler->getCountCategories();
		$categoriesAll = $categoriesHandler->getAllCategories($start, $limit);
		$GLOBALS['xoopsTpl']->assign('categories_count', $categoriesCount);
		$GLOBALS['xoopsTpl']->assign('mymodule_url', MYMODULE_URL);
		$GLOBALS['xoopsTpl']->assign('mymodule_upload_url', MYMODULE_UPLOAD_URL);
		// Table view categories
		if($categoriesCount > 0) {
			foreach(array_keys($categoriesAll) as $i) {
				$category = $categoriesAll[$i]->getValuesCategories();
				$GLOBALS['xoopsTpl']->append('categories_list', $category);
				unset($category);
			}
			// Display Navigation
			if($categoriesCount > $limit) {
				include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
				$pagenav = new \XoopsPageNav($categoriesCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
				$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
			}
		} else {
			$GLOBALS['xoopsTpl']->assign('error', _AM_MYMODULE_THEREARENT_CATEGORIES);
		}

	break;
	case 'new':
		$templateMain = 'mymodule_admin_categories.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('categories.php'));
		$adminObject->addItemButton(_AM_MYMODULE_CATEGORIES_LIST, 'categories.php', 'list');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
		// Get Form
		$categoriesObj = $categoriesHandler->create();
		$form = $categoriesObj->getFormCategories();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());

	break;
	case 'save':
		// Security Check
		if(!$GLOBALS['xoopsSecurity']->check()) {
			redirect_header('categories.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
		}
		if(isset($catId)) {
			$categoriesObj = $categoriesHandler->get($catId);
		} else {
			$categoriesObj = $categoriesHandler->create();
		}
		// Set Vars
		$categoriesObj->setVar('cat_name', Request::getString('cat_name', ''));
		// Set Var cat_logo
		include_once XOOPS_ROOT_PATH . '/class/uploader.php';
		$uploader = new \XoopsMediaUploader(MYMODULE_UPLOAD_IMAGE_PATH . '/categories/', 
													$helper->getConfig('mimetypes'), 
													$helper->getConfig('maxsize'), null, null);
		if($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
			$extension = preg_replace('/^.+\.([^.]+)$/sU', '', $_FILES['attachedfile']['name']);
			$imgName = str_replace(' ', '', Request::getString('cat_name')) . '.' . $extension;
			$uploader->setPrefix($imgName);
			$uploader->fetchMedia($_POST['xoops_upload_file'][0]);
			if(!$uploader->upload()) {
				$errors = $uploader->getErrors();
				redirect_header('javascript:history.go(-1).php', 3, $errors);
			} else {
				$categoriesObj->setVar('cat_logo', $uploader->getSavedFileName());
			}
		} else {
			$categoriesObj->setVar('cat_logo', Request::getString('cat_logo'));
		}
		$categoryCreated = date_create_from_format(_SHORTDATESTRING, $_POST['cat_created']);
		$categoriesObj->setVar('cat_created', $categoryCreated->getTimestamp());
		$categoriesObj->setVar('cat_submitter', Request::getInt('cat_submitter', 0));
		// Insert Data
		if($categoriesHandler->insert($categoriesObj)) {
			$newCatId = $categoriesObj->getNewInsertedIdCategories();
			$permId = isset($_REQUEST['cat_id']) ? $catId : $newCatId;
			$gpermHandler = xoops_getHandler('groupperm');
			// Permission to view
			if(isset($_POST['groups_view'])) {
				foreach($_POST['groups_view'] as $onegroupId) {
					$gpermHandler->addRight('mymodule_view', $permId, $onegroupId, $GLOBALS['xoopsModule']->getVar('mid'));
				}
			}
			// Permission to submit
			if(isset($_POST['groups_submit'])) {
				foreach($_POST['groups_submit'] as $onegroupId) {
					$gpermHandler->addRight('mymodule_submit', $permId, $onegroupId, $GLOBALS['xoopsModule']->getVar('mid'));
				}
			}
			// Permission to approve
			if(isset($_POST['groups_approve'])) {
				foreach($_POST['groups_approve'] as $onegroupId) {
					$gpermHandler->addRight('mymodule_approve', $permId, $onegroupId, $GLOBALS['xoopsModule']->getVar('mid'));
				}
			}
			redirect_header('categories.php?op=list', 2, _AM_MYMODULE_FORM_OK);
		}
		// Get Form
		$GLOBALS['xoopsTpl']->assign('error', $categoriesObj->getHtmlErrors());
		$form = $categoriesObj->getFormCategories();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());

	break;
	case 'edit':
		$templateMain = 'mymodule_admin_categories.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('categories.php'));
		$adminObject->addItemButton(_AM_MYMODULE_ADD_CATEGORY, 'categories.php?op=new', 'add');
		$adminObject->addItemButton(_AM_MYMODULE_CATEGORIES_LIST, 'categories.php', 'list');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
		// Get Form
		$categoriesObj = $categoriesHandler->get($catId);
		$form = $categoriesObj->getFormCategories();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());

	break;
	case 'delete':
		$categoriesObj = $categoriesHandler->get($catId);
		if(isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
			if(!$GLOBALS['xoopsSecurity']->check()) {
				redirect_header('categories.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
			}
			if($categoriesHandler->delete($categoriesObj)) {
				redirect_header('categories.php', 3, _AM_MYMODULE_FORM_DELETE_OK);
			} else {
				$GLOBALS['xoopsTpl']->assign('error', $categoriesObj->getHtmlErrors());
			}
		} else {
			xoops_confirm(array('ok' => 1, 'cat_id' => $catId, 'op' => 'delete'), $_SERVER['REQUEST_URI'], sprintf(_AM_MYMODULE_FORM_SURE_DELETE, $categoriesObj->getVar('cat_name')));
		}

	break;
}
require __DIR__ . '/footer.php';
