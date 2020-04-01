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
 */

// Define main template
$templateMain = 'tdmcreate_moremymodule.tpl';

include __DIR__ . '/header.php';
// Recovered value of argument op in the URL $
$op = \Xmf\Request::getString('op', 'list');

$fileId = \Xmf\Request::getInt('file_id');

switch ($op) {
    case 'list':
    default:
        $start = \Xmf\Request::getInt('start', 0);
        $limit = \Xmf\Request::getInt('limit', $helper->getConfig('moremymodule_adminpager'));
        $GLOBALS['xoTheme']->addScript('modules/tdmcreate/assets/js/functions.js');
        $GLOBALS['xoTheme']->addStylesheet('modules/tdmcreate/assets/css/admin/style.css');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('moremymodule.php'));
        $adminObject->addItemButton(_AM_TDMCREATE_ADD_MORE_FILE, 'moremymodule.php?op=new', 'add');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        $GLOBALS['xoopsTpl']->assign('tdmc_url', TDMC_URL);
        $GLOBALS['xoopsTpl']->assign('tdmc_upload_imgfile_url', TDMC_UPLOAD_IMGMOD_URL);
        $GLOBALS['xoopsTpl']->assign('modPathIcon16', $modPathIcon16);
        $GLOBALS['xoopsTpl']->assign('sysPathIcon32', $sysPathIcon32);
        $modulesCount = $helper->getHandler('Modules')->getCountModules();
        // Redirect if there aren't modules
        if (0 == $modulesCount) {
            redirect_header('modules.php?op=new', 2, _AM_TDMCREATE_NOTMODULES);
        }
        $moremymoduleCount = $helper->getHandler('Moremymodule')->getCountMoreMymodule();
        $moremymoduleAll   = $helper->getHandler('Moremymodule')->getAllMoreMymodule($start, $limit);
        // Display moremymodule list
        if ($moremymoduleCount > 0) {
            foreach (array_keys($moremymoduleAll) as $i) {
                $mymodule = $moremymoduleAll[$i]->getValuesMoreMymodule();
                $GLOBALS['xoopsTpl']->append('mymodule_list', $mymodule);
                unset($mymodule);
            }
            if ($moremymoduleCount > $limit) {
                include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
                $pagenav = new \XoopsPageNav($moremymoduleCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('error', _AM_TDMCREATE_THEREARENT_MORE_MYMODULE);
        }
        break;
    case 'new':
        $GLOBALS['xoTheme']->addScript('modules/tdmcreate/assets/js/functions.js');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('moremymodule.php'));
        $adminObject->addItemButton(_AM_TDMCREATE_MORE_MYMODULE_LIST, 'moremymodule.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));

        $moremymoduleObj = $helper->getHandler('Moremymodule')->create();
        $form         = $moremymoduleObj->getFormMoreMymodule();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'save':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('moremymodule.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (isset($fileId)) {
            $moremymoduleObj = $helper->getHandler('Moremymodule')->get($fileId);
        } else {
            $moremymoduleObj = $helper->getHandler('Moremymodule')->create();
        }
        // Form file save
        $moremymoduleObj->setVars(
            [
                'file_mid'       => \Xmf\Request::getInt('file_mid', 0, 'POST'),
                'file_name'      => \Xmf\Request::getString('file_name', '', 'POST'),
                'file_extension' => \Xmf\Request::getString('file_extension', '', 'POST'),
                'file_infolder'  => \Xmf\Request::getString('file_infolder', '', 'POST'),
            ]
        );

        if ($helper->getHandler('Moremymodule')->insert($moremymoduleObj)) {
            if ($moremymoduleObj->isNew()) {
                redirect_header('moremymodule.php', 5, sprintf(_AM_TDMCREATE_FILE_FORM_CREATED_OK, \Xmf\Request::getString('file_name', '', 'POST')));
            } else {
                redirect_header('moremymodule.php', 5, sprintf(_AM_TDMCREATE_FILE_FORM_UPDATED_OK, \Xmf\Request::getString('file_name', '', 'POST')));
            }
        }

        $GLOBALS['xoopsTpl']->assign('error', $moremymoduleObj->getHtmlErrors());
        $form = $moremymoduleObj->getFormMoreMymodule();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        // Define main template
        //        $templateMain = 'tdmcreate_moremymodule.tpl';
        $GLOBALS['xoTheme']->addScript('modules/tdmcreate/assets/js/functions.js');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('moremymodule.php'));
        $adminObject->addItemButton(_AM_TDMCREATE_ADD_MODULE, 'moremymodule.php?op=new', 'add');
        $adminObject->addItemButton(_AM_TDMCREATE_MORE_MYMODULE_LIST, 'moremymodule.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));

        $moremymoduleObj = $helper->getHandler('Moremymodule')->get($fileId);
        $form         = $moremymoduleObj->getFormMoreMymodule();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'delete':
        $moremymoduleObj = $helper->getHandler('Moremymodule')->get($fileId);
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('moremymodule.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($helper->getHandler('Moremymodule')->delete($moremymoduleObj)) {
                redirect_header('moremymodule.php', 3, _AM_TDMCREATE_FORM_DELETED_OK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $moremymoduleObj->getHtmlErrors());
            }
        } else {
            xoops_confirm(['ok' => 1, 'file_id' => $fileId, 'op' => 'delete'], \Xmf\Request::getString('REQUEST_URI', '', 'SERVER'), sprintf(_AM_TDMCREATE_FORM_SURE_DELETE, $moremymoduleObj->getVar('file_name')));
        }
        break;
}

include __DIR__ . '/footer.php';
