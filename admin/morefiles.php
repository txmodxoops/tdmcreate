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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.5
 *
 * @author          Txmod Xoops <support@txmodxoops.org>
 *
 * @version         $Id: 1.59 morefiles.php 11297 2013-03-24 10:58:10Z timgno $
 */
include __DIR__.'/header.php';
// Recovered value of argument op in the URL $
$op = XoopsRequest::getString('op', 'list');
//
$fileId = XoopsRequest::getInt('file_id');
//
switch ($op) {
    case 'list':
    default:
        $start = XoopsRequest::getInt('start', 0);
        $limit = XoopsRequest::getInt('limit', $tdmcreate->getConfig('morefiles_adminpager'));
        // Define main template
        $templateMain = 'tdmcreate_morefiles.tpl';
        $GLOBALS['xoTheme']->addScript('modules/tdmcreate/assets/js/functions.js');
        $GLOBALS['xoTheme']->addStylesheet('modules/tdmcreate/assets/css/admin/style.css');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminMenu->addNavigation('morefiles.php'));
        $adminMenu->addItemButton(_AM_TDMCREATE_ADD_MORE_FILE, 'morefiles.php?op=new', 'add');
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
        $morefilesCount = $tdmcreate->getHandler('morefiles')->getCountMoreFiles();
        $morefilesAll = $tdmcreate->getHandler('morefiles')->getAllMoreFiles($start, $limit);
        // Display morefiles list
        if ($morefilesCount > 0) {
            foreach (array_keys($morefilesAll) as $i) {
                $files = $morefilesAll[$i]->getValues();
                $GLOBALS['xoopsTpl']->append('files_list', $files);
                unset($files);
            }
            if ($morefilesCount > $limit) {
                include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
                $pagenav = new XoopsPageNav($morefilesCount, $limit, $start, 'start', 'op=list&limit='.$limit);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('error', _AM_TDMCREATE_THEREARENT_MODULES);
        }
        break;

    case 'new':
        // Define main template
        $templateMain = 'tdmcreate_morefiles.tpl';
        $GLOBALS['xoTheme']->addScript('modules/tdmcreate/assets/js/functions.js');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminMenu->addNavigation('morefiles.php'));
        $adminMenu->addItemButton(_AM_TDMCREATE_MORE_FILES_LIST, 'morefiles.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminMenu->renderButton());

        $morefilesObj = &$tdmcreate->getHandler('morefiles')->create();
        $form = $morefilesObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;

    case 'save':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('morefiles.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (isset($fileId)) {
            $morefilesObj = &$tdmcreate->getHandler('morefiles')->get($fileId);
        } else {
            $morefilesObj = &$tdmcreate->getHandler('morefiles')->create();
        }
        // Form file save
        $morefilesObj->setVars(array(
                                 'file_mid' => $_POST['file_mid'],
                                 'file_name' => $_POST['file_name'],
                                 'file_extension' => $_POST['file_extension'],
                                 'file_infolder' => $_POST['file_infolder'], ));

        if ($tdmcreate->getHandler('morefiles')->insert($morefilesObj)) {
            if ($morefilesObj->isNew()) {
                redirect_header('morefiles.php', 5, sprintf(_AM_TDMCREATE_FILE_FORM_CREATED_OK, $_POST['file_name']));
            } else {
                redirect_header('morefiles.php', 5, sprintf(_AM_TDMCREATE_FILE_FORM_UPDATED_OK, $_POST['file_name']));
            }
        }

        $GLOBALS['xoopsTpl']->assign('error', $morefilesObj->getHtmlErrors());
        $form = &$morefilesObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;

    case 'edit':
        // Define main template
        $templateMain = 'tdmcreate_morefiles.tpl';
        $GLOBALS['xoTheme']->addScript('modules/tdmcreate/assets/js/functions.js');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminMenu->addNavigation('morefiles.php'));
        $adminMenu->addItemButton(_AM_TDMCREATE_ADD_MODULE, 'morefiles.php?op=new', 'add');
        $adminMenu->addItemButton(_AM_TDMCREATE_MORE_FILES_LIST, 'morefiles.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminMenu->renderButton());

        $morefilesObj = $tdmcreate->getHandler('morefiles')->get($fileId);
        $form = $morefilesObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;

    case 'delete':
        $morefilesObj = &$tdmcreate->getHandler('morefiles')->get($fileId);
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('morefiles.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($tdmcreate->getHandler('morefiles')->delete($morefilesObj)) {
                redirect_header('morefiles.php', 3, _AM_TDMCREATE_FORM_DELETED_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $morefilesObj->getHtmlErrors());
            }
        } else {
            xoops_confirm(array('ok' => 1, 'file_id' => $fileId, 'op' => 'delete'), $_SERVER['REQUEST_URI'], sprintf(_AM_TDMCREATE_FORM_SURE_DELETE, $morefilesObj->getVar('file_name')));
        }
        break;
}

include __DIR__.'/footer.php';
