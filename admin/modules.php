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
 * tdmcreate module.
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.5
 *
 * @author          Txmod Xoops <support@txmodxoops.org>
 *
 * @version         $Id: 1.59 modules.php 11297 2013-03-24 10:58:10Z timgno $
 */
$GLOBALS['xoopsOption']['template_main'] = 'tdmcreate_modules.tpl';

include __DIR__ . '/header.php';
// Recovered value of argument op in the URL $
$op = \Xmf\Request::getString('op', 'list');

$modId = \Xmf\Request::getInt('mod_id');

switch ($op) {
    case 'list':
    default:
        $start = \Xmf\Request::getInt('start', 0);
        $limit = \Xmf\Request::getInt('limit', $helper->getConfig('modules_adminpager'));
        // Define main template
//        $templateMain = 'tdmcreate_modules.tpl';
        $GLOBALS['xoTheme']->addScript('modules/tdmcreate/assets/js/functions.js');
        $GLOBALS['xoTheme']->addStylesheet('modules/tdmcreate/assets/css/admin/style.css');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('modules.php'));
        $adminObject->addItemButton(_AM_TDMCREATE_ADD_MODULE, 'modules.php?op=new', 'add');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        $GLOBALS['xoopsTpl']->assign('tdmc_url', TDMC_URL);
        $GLOBALS['xoopsTpl']->assign('tdmc_upload_imgmod_url', TDMC_UPLOAD_IMGMOD_URL);
        $GLOBALS['xoopsTpl']->assign('modPathIcon16', TDMC_URL . '/' . $modPathIcon16);
        $modulesCount = $helper->getHandler('Modules')->getCountModules();
        $modulesAll = $helper->getHandler('Modules')->getAllModules($start, $limit);
        // Redirect if there aren't modules
        if (0 == $modulesCount) {
            redirect_header('modules.php?op=new', 2, _AM_TDMCREATE_NOTMODULES);
        }
        // Display modules list
        if ($modulesCount > 0) {
            foreach (array_keys($modulesAll) as $i) {
                $module = $modulesAll[$i]->getValuesModules();
                $GLOBALS['xoopsTpl']->append('modules_list', $module);
                unset($module);
            }
            if ($modulesCount > $limit) {
                include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
                $pagenav = new \XoopsPageNav($modulesCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('error', _AM_TDMCREATE_THEREARENT_MODULES);
        }
        break;
    case 'new':
        // Define main template
//        $templateMain = 'tdmcreate_modules.tpl';
        $GLOBALS['xoTheme']->addScript('modules/tdmcreate/assets/js/functions.js');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('modules.php'));
        $adminObject->addItemButton(_AM_TDMCREATE_MODULES_LIST, 'modules.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));

        $modulesObj = $helper->getHandler('Modules')->create();
        $form = $modulesObj->getFormModules();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'save':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('modules.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (isset($modId)) {
            $modulesObj = $helper->getHandler('Modules')->get($modId);
        } else {
            $modulesObj = $helper->getHandler('Modules')->create();
        }
        $moduleDirname = preg_replace('/[^a-zA-Z0-9]\s+/', '', mb_strtolower($_POST['mod_dirname']));
        //Form module save
        $modulesObj->setVars([
                                 'mod_name' => $_POST['mod_name'],
                                 'mod_dirname' => $moduleDirname,
                                 'mod_version' => $_POST['mod_version'],
                                 'mod_since' => $_POST['mod_since'],
                                 'mod_min_php' => $_POST['mod_min_php'],
                                 'mod_min_xoops' => $_POST['mod_min_xoops'],
                                 'mod_min_admin' => $_POST['mod_min_admin'],
                                 'mod_min_mysql' => $_POST['mod_min_mysql'],
                                 'mod_description' => $_POST['mod_description'],
                                 'mod_author' => $_POST['mod_author'],
                                 'mod_author_mail' => $_POST['mod_author_mail'],
                                 'mod_author_website_url' => $_POST['mod_author_website_url'],
                                 'mod_author_website_name' => $_POST['mod_author_website_name'],
                                 'mod_credits' => $_POST['mod_credits'],
                                 'mod_license' => $_POST['mod_license'],
                                 'mod_release_info' => $_POST['mod_release_info'],
                                 'mod_release_file' => $_POST['mod_release_file'],
                                 'mod_manual' => $_POST['mod_manual'],
                                 'mod_manual_file' => $_POST['mod_manual_file'],
                             ]);
        //Form mod_image
        include_once XOOPS_ROOT_PATH . '/class/uploader.php';
        $uploader = new \XoopsMediaUploader(
            TDMC_UPLOAD_IMGMOD_PATH,
            $helper->getConfig('mimetypes'),
            $helper->getConfig('maxsize'),
            null,
            null
        );
        if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
            $uploader->fetchMedia($_POST['xoops_upload_file'][0]);
            if (!$uploader->upload()) {
                $errors = &$uploader->getErrors();
                redirect_header('javascript:history.go(-1)', 3, $errors);
            } else {
                $modulesObj->setVar('mod_image', $uploader->getSavedFileName());
            }
        } else {
            $modulesObj->setVar('mod_image', $_POST['mod_image']);
        }
        //Form module save
        $modulesObj->setVars(
            [
                                 'mod_demo_site_url' => $_POST['mod_demo_site_url'],
                                 'mod_demo_site_name' => $_POST['mod_demo_site_name'],
                                 'mod_support_url' => $_POST['mod_support_url'],
                                 'mod_support_name' => $_POST['mod_support_name'],
                                 'mod_website_url' => $_POST['mod_website_url'],
                                 'mod_website_name' => $_POST['mod_website_name'],
                                 'mod_release' => $_POST['mod_release'],
                                 'mod_status' => $_POST['mod_status'],
                                 'mod_donations' => $_POST['mod_donations'],
                                 'mod_subversion' => $_POST['mod_subversion'],
                             ]
        );
        $moduleOption = \Xmf\Request::getArray('module_option', []);
        $modulesObj->setVar('mod_admin', in_array('admin', $moduleOption));
        $modulesObj->setVar('mod_user', in_array('user', $moduleOption));
        $modulesObj->setVar('mod_blocks', in_array('blocks', $moduleOption));
        $modulesObj->setVar('mod_search', in_array('search', $moduleOption));
        $modulesObj->setVar('mod_comments', in_array('comments', $moduleOption));
        $modulesObj->setVar('mod_notifications', in_array('notifications', $moduleOption));
        $modulesObj->setVar('mod_permissions', in_array('permissions', $moduleOption));
        $modulesObj->setVar('mod_inroot_copy', in_array('inroot_copy', $moduleOption));

        if ($helper->getHandler('Modules')->insert($modulesObj)) {
            if ($modulesObj->isNew()) {
                redirect_header('tables.php', 5, sprintf(_AM_TDMCREATE_MODULE_FORM_CREATED_OK, $_POST['mod_name']));
            } else {
                redirect_header('modules.php', 5, sprintf(_AM_TDMCREATE_MODULE_FORM_UPDATED_OK, $_POST['mod_name']));
            }
        }

        $GLOBALS['xoopsTpl']->assign('error', $modulesObj->getHtmlErrors());
        $form = $modulesObj->getFormModules();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        // Define main template
//        $templateMain = 'tdmcreate_modules.tpl';
        $GLOBALS['xoTheme']->addScript('modules/tdmcreate/assets/js/functions.js');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('modules.php'));
        $adminObject->addItemButton(_AM_TDMCREATE_ADD_MODULE, 'modules.php?op=new', 'add');
        $adminObject->addItemButton(_AM_TDMCREATE_MODULES_LIST, 'modules.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));

        $modulesObj = $helper->getHandler('Modules')->get($modId);
        $form = $modulesObj->getFormModules();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'delete':
        $modulesObj = $helper->getHandler('Modules')->get($modId);
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('modules.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($helper->getHandler('Modules')->delete($modulesObj)) {
                redirect_header('modules.php', 3, _AM_TDMCREATE_FORMDELOK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $modulesObj->getHtmlErrors());
            }
        } else {
            xoops_confirm(['ok' => 1, 'mod_id' => $modId, 'op' => 'delete'], $_SERVER['REQUEST_URI'], sprintf(_AM_TDMCREATE_FORMSUREDEL, $modulesObj->getVar('mod_name')));
        }
        break;
    case 'display':
        $modFieldArray = ['admin', 'user', 'blocks', 'search', 'comments', 'notifications', 'permissions'];
        $id = \Xmf\Request::getInt('mod_id', 0, 'POST');
        if ($id > 0) {
            $modulesObj = $helper->getHandler('Modules')->get($id);
            foreach ($modFieldArray as $moduleField) {
                if (isset($_POST['mod_' . $moduleField])) {
                    $modField = $modulesObj->getVar('mod_' . $moduleField);
                    $modulesObj->setVar('mod_' . $moduleField, !$modField);
                }
            }
            if ($helper->getHandler('Modules')->insert($modulesObj)) {
                redirect_header('modules.php', 3, _AM_TDMCREATE_TOGGLE_SUCCESS);
            }
            $GLOBALS['xoopsTpl']->assign('error', $modulesObj->getHtmlErrors());
        }
        break;
}

include __DIR__ . '/footer.php';
