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
		$templateMain = 'mymodule3_admin_categories.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('categories.php'));
		$adminObject->addItemButton(_AM_MYMODULE3_ADD_CATEGORY, 'categories.php?op=new', 'add');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
		$categoriesCount = $categoriesHandler->getCountCategories();
		$categoriesAll = $categoriesHandler->getAllCategories($start, $limit);
		$GLOBALS['xoopsTpl']->assign('categories_count', $categoriesCount);
		$GLOBALS['xoopsTpl']->assign('mymodule3_url', MYMODULE3_URL);
		$GLOBALS['xoopsTpl']->assign('mymodule3_upload_url', MYMODULE3_UPLOAD_URL);
		// Table view categories
		if ($categoriesCount > 0) {
			foreach(array_keys($categoriesAll) as $i) {
				$category = $categoriesAll[$i]->getValuesCategories();
				$GLOBALS['xoopsTpl']->append('categories_list', $category);
				unset($category);
			}
			// Display Navigation
			if ($categoriesCount > $limit) {
				include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
				$pagenav = new \XoopsPageNav($categoriesCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
				$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
			}
		} else {
			$GLOBALS['xoopsTpl']->assign('error', _AM_MYMODULE3_THEREARENT_CATEGORIES);
		}
	break;
	case 'new':
		$templateMain = 'mymodule3_admin_categories.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('categories.php'));
		$adminObject->addItemButton(_AM_MYMODULE3_CATEGORIES_LIST, 'categories.php', 'list');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
		// Get Form
		$categoriesObj = $categoriesHandler->create();
		$form = $categoriesObj->getFormCategories();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());
	break;
	case 'save':
		// Security Check
		if (!$GLOBALS['xoopsSecurity']->check()) {
			redirect_header('categories.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
		}
		if (isset($catId)) {
			$categoriesObj = $categoriesHandler->get($catId);
		} else {
			$categoriesObj = $categoriesHandler->create();
		}
		// Set Vars
		$categoriesObj->setVar('cat_name', Request::getString('cat_name', ''));
		// Set Var cat_logo
		include_once XOOPS_ROOT_PATH . '/class/uploader.php';
		$filename       = $_FILES['cat_logo']['name'];
		$imgMimetype    = $_FILES['cat_logo']['type'];
		$imgNameDef     = Request::getString('cat_name');
		$uploaderErrors = '';
		$uploader = new \XoopsMediaUploader(MYMODULE3_UPLOAD_IMAGE_PATH . '/categories/', 
													$helper->getConfig('mimetypes_image'), 
													$helper->getConfig('maxsize_image'), null, null);
		if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
			$extension = preg_replace('/^.+\.([^.]+)$/sU', '', $filename);
			$imgName = str_replace(' ', '', $imgNameDef) . '.' . $extension;
			$uploader->setPrefix($imgName);
			$uploader->fetchMedia($_POST['xoops_upload_file'][0]);
			if (!$uploader->upload()) {
				$uploaderErrors = $uploader->getErrors();
			} else {
				$savedFilename = $uploader->getSavedFileName();
				$maxwidth  = (int)$helper->getConfig('maxwidth_image');
				$maxheight = (int)$helper->getConfig('maxheight_image');
				if ($maxwidth > 0 && $maxheight > 0) {
					// Resize image
					$imgHandler                = new Mymodule3\Common\Resizer();
					$imgHandler->sourceFile    = MYMODULE3_UPLOAD_IMAGE_PATH . '/categories/' . $savedFilename;
					$imgHandler->endFile       = MYMODULE3_UPLOAD_IMAGE_PATH . '/categories/' . $savedFilename;
					$imgHandler->imageMimetype = $imgMimetype;
					$imgHandler->maxWidth      = $maxwidth;
					$imgHandler->maxHeight     = $maxheight;
					$result                    = $imgHandler->resizeImage();
				}
				$categoriesObj->setVar('cat_logo', $savedFilename);
			}
		} else {
			if ($filename > '') {
				$uploaderErrors = $uploader->getErrors();
			}
			$categoriesObj->setVar('cat_logo', Request::getString('cat_logo'));
		}
		$categoryCreated = date_create_from_format(_SHORTDATESTRING, Request::getString('cat_created'));
		$categoriesObj->setVar('cat_created', $categoryCreated->getTimestamp());
		$categoriesObj->setVar('cat_submitter', Request::getInt('cat_submitter', 0));
		// Insert Data
		if ($categoriesHandler->insert($categoriesObj)) {
			if ('' !== $uploaderErrors) {
				redirect_header('categories.php?op=edit&cat_id=' . $catId, 5, $uploaderErrors);
			} else {
				redirect_header('categories.php?op=list', 2, _AM_MYMODULE3_FORM_OK);
			}
		}
		// Get Form
		$GLOBALS['xoopsTpl']->assign('error', $categoriesObj->getHtmlErrors());
		$form = $categoriesObj->getFormCategories();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());
	break;
	case 'edit':
		$templateMain = 'mymodule3_admin_categories.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('categories.php'));
		$adminObject->addItemButton(_AM_MYMODULE3_ADD_CATEGORY, 'categories.php?op=new', 'add');
		$adminObject->addItemButton(_AM_MYMODULE3_CATEGORIES_LIST, 'categories.php', 'list');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
		// Get Form
		$categoriesObj = $categoriesHandler->get($catId);
		$form = $categoriesObj->getFormCategories();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());
	break;
	case 'delete':
		$categoriesObj = $categoriesHandler->get($catId);
		if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
			if (!$GLOBALS['xoopsSecurity']->check()) {
				redirect_header('categories.php', 3, implode(', ', $GLOBALS['xoopsSecurity']->getErrors()));
			}
			if ($categoriesHandler->delete($categoriesObj)) {
				redirect_header('categories.php', 3, _AM_MYMODULE3_FORM_DELETE_OK);
			} else {
				$GLOBALS['xoopsTpl']->assign('error', $categoriesObj->getHtmlErrors());
			}
		} else {
			xoops_confirm(array('ok' => 1, 'cat_id' => $catId, 'op' => 'delete'), $_SERVER['REQUEST_URI'], sprintf(_AM_MYMODULE3_FORM_SURE_DELETE, $categoriesObj->getVar('cat_name')));
		}
	break;
}
require __DIR__ . '/footer.php';
