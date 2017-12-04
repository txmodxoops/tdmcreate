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
 * @version         $Id: 1.59 addfiles.php 11297 2013-03-24 10:58:10Z timgno $
 */
include __DIR__.'/header.php';
// Recovered value of argument op in the URL $
$op = XoopsRequest::getString('op', 'list');

$fileId = XoopsRequest::getInt('file_id');

switch ($op) {
    case 'list':
    default:
        $start = XoopsRequest::getInt('start', 0);
        $limit = XoopsRequest::getInt('limit', $tdmcreate->getConfig('addfiles_adminpager'));
        // Define main template
        $templateMain = 'tdmcreate_addfiles.tpl';
        $GLOBALS['xoTheme']->addScript('modules/tdmcreate/assets/js/functions.js');
        $GLOBALS['xoTheme']->addStylesheet('modules/tdmcreate/assets/css/admin/style.css');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminMenu->displayNavigation('addfiles.php'));
        $adminMenu->addItemButton(_AM_TDMCREATE_ADD_FILE, 'addfiles.php?op=new', 'add');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminMenu->renderButton());
        $GLOBALS['xoopsTpl']->assign('tdmc_url', TDMC_URL);
        $GLOBALS['xoopsTpl']->assign('tdmc_upload_imgfile_url', TDMC_UPLOAD_IMGMOD_URL);
        $GLOBALS['xoopsTpl']->assign('modPathIcon16', $modPathIcon16);
        $GLOBALS['xoopsTpl']->assign('sysPathIcon32', $sysPathIcon32);
        $modulesCount = $tdmcreate->getHandler('modules')->getCountModules();
        // Redirect if there aren't modules
        if (0 == $modulesCount) {
            redirect_header('modules.php?op=new', 2, _AM_TDMCREATE_NOT_MODULES);
        }
        $addfilesCount = $tdmcreate->getHandler('addfiles')->getCountAddFiles();
        $addfilesAll = $tdmcreate->getHandler('addfiles')->getAllAddFiles($start, $limit);
        // Display addfiles list
        if ($addfilesCount > 0) {
            foreach (array_keys($addfilesAll) as $i) {
                $files = $addfilesAll[$i]->getAddFilesValues();
                $GLOBALS['xoopsTpl']->append('files_list', $files);
                unset($files);
            }
            if ($addfilesCount > $limit) {
                include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
                $pagenav = new XoopsPageNav($addfilesCount, $limit, $start, 'start', 'op=list&limit='.$limit);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('error', _AM_TDMCREATE_THEREARENT_MODULES);
        }
        break;

    case 'new':
        // Define main template
        $templateMain = 'tdmcreate_addfiles.tpl';
        $GLOBALS['xoTheme']->addScript('modules/tdmcreate/assets/js/functions.js');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminMenu->displayNavigation('addfiles.php'));
        $adminMenu->addItemButton(_AM_TDMCREATE_ADDFILES_LIST, 'addfiles.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminMenu->renderButton());

        $addfilesObj = &$tdmcreate->getHandler('addfiles')->create();
        $form = $addfilesObj->getFormAddFiles();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;

    case 'save':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('addfiles.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (isset($fileId)) {
            $addfilesObj = &$tdmcreate->getHandler('addfiles')->get($fileId);
        } else {
            $addfilesObj = &$tdmcreate->getHandler('addfiles')->create();
        }
        // Form file save
        $addfilesObj->setVars([
                                 'file_mid' => $_POST['file_mid'],
                                 'file_name' => $_POST['file_name'],
                                 'file_extension' => $_POST['file_extension'],
                                 'file_infolder' => $_POST['file_infolder'],
                              ]);

        if ($tdmcreate->getHandler('addfiles')->insert($addfilesObj)) {
            if ($addfilesObj->isNew()) {
                redirect_header('addfiles.php', 5, sprintf(_AM_TDMCREATE_FILE_FORM_CREATED_OK, $_POST['file_name']));
            } else {
                redirect_header('addfiles.php', 5, sprintf(_AM_TDMCREATE_FILE_FORM_UPDATED_OK, $_POST['file_name']));
            }
        }

        $GLOBALS['xoopsTpl']->assign('error', $addfilesObj->getHtmlErrors());
        $form = &$addfilesObj->getFormAddFiles();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;

    case 'edit':
        // Define main template
        $templateMain = 'tdmcreate_addfiles.tpl';
        $GLOBALS['xoTheme']->addScript('modules/tdmcreate/assets/js/functions.js');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminMenu->displayNavigation('addfiles.php'));
        $adminMenu->addItemButton(_AM_TDMCREATE_ADD_MODULE, 'addfiles.php?op=new', 'add');
        $adminMenu->addItemButton(_AM_TDMCREATE_ADDFILES_LIST, 'addfiles.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminMenu->renderButton());

        $addfilesObj = $tdmcreate->getHandler('addfiles')->get($fileId);
        $form = $addfilesObj->getFormAddFiles();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;

    case 'delete':
        $addfilesObj = &$tdmcreate->getHandler('addfiles')->get($fileId);
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('addfiles.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($tdmcreate->getHandler('addfiles')->delete($addfilesObj)) {
                redirect_header('addfiles.php', 3, _AM_TDMCREATE_FORM_DELETED_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $addfilesObj->getHtmlErrors());
            }
        } else {
            xoops_confirm(['ok' => 1, 'file_id' => $fileId, 'op' => 'delete'], $_SERVER['REQUEST_URI'], sprintf(_AM_TDMCREATE_FORM_SURE_DELETE, $addfilesObj->getVar('file_name')));
        }
        break;
}

include __DIR__.'/footer.php';
