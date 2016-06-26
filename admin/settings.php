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
 * @version         $Id: 1.59 settings.php 11297 2013-03-24 10:58:10Z timgno $
 */
include __DIR__ .'/header.php';
// Recovered value of argument op in the URL $
$op = XoopsRequest::getString('op', 'list');
//
$setId = XoopsRequest::getInt('set_id');
//
switch ($op) {
    case 'list':
    default:
        $start = XoopsRequest::getInt('start', 0);
        $limit = XoopsRequest::getInt('limit', $tdmcreate->getConfig('settings_adminpager'));
        // Define main template
        $templateMain = 'tdmcreate_settings.tpl';
        $GLOBALS['xoTheme']->addScript('modules/tdmcreate/assets/js/functions.js');
        $GLOBALS['xoTheme']->addStylesheet('modules/tdmcreate/assets/css/admin/style.css');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminMenu->displayNavigation('settings.php'));
        $adminMenu->addItemButton(_AM_TDMCREATE_ADD_SETTING, 'settings.php?op=new', 'add');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminMenu->renderButton());
        $GLOBALS['xoopsTpl']->assign('tdmc_upload_imgmod_url', TDMC_UPLOAD_IMGMOD_URL);
        $GLOBALS['xoopsTpl']->assign('tdmc_url', TDMC_URL);
        $GLOBALS['xoopsTpl']->assign('modPathIcon16', TDMC_URL.'/'.$modPathIcon16);
        $GLOBALS['xoopsTpl']->assign('sysPathIcon32', $sysPathIcon32);
        $settingsCount = $tdmcreate->getHandler('settings')->getCountSettings();
        $settingsAll = $tdmcreate->getHandler('settings')->getAllSettings($start, $limit);
        // Display settings list
        if ($settingsCount > 0) {
            foreach (array_keys($settingsAll) as $i) {
                $setting = $settingsAll[$i]->getValuesSettings();
                $GLOBALS['xoopsTpl']->append('settings_list', $setting);
                unset($setting);
            }
            if ($settingsCount > $limit) {
                include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
                $pagenav = new XoopsPageNav($settingsCount, $limit, $start, 'start', 'op=list&limit='.$limit);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('error', _AM_TDMCREATE_THEREARENT_SETTINGS);
        }
        break;
    case 'new':
        // Define main template
        $templateMain = 'tdmcreate_settings.tpl';
        $GLOBALS['xoTheme']->addScript('modules/tdmcreate/assets/js/functions.js');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminMenu->displayNavigation('settings.php'));
        $adminMenu->addItemButton(_AM_TDMCREATE_SETTINGS_LIST, 'settings.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminMenu->renderButton());

        $settingsObj = $tdmcreate->getHandler('settings')->create();
        $form = $settingsObj->getFormSettings();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'save':
        if ($GLOBALS['xoopsSecurity']->check()) {
            redirect_header('settings.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (isset($setId)) {
            $settingsObj = $tdmcreate->getHandler('settings')->get($setId);
        } else {
            $settingsObj = $tdmcreate->getHandler('settings')->create();
        }
        $setModuleDirname = preg_replace('/[^a-zA-Z0-9]\s+/', '', strtolower($_POST['set_dirname']));
        //Form module save
        $settingsObj->setVars(array(
                                 'set_name' => $_POST['set_name'],
                                 'set_dirname' => $setModuleDirname,
                                 'set_version' => $_POST['set_version'],
                                 'set_since' => $_POST['set_since'],
                                 'set_min_php' => $_POST['set_min_php'],
                                 'set_min_xoops' => $_POST['set_min_xoops'],
                                 'set_min_admin' => $_POST['set_min_admin'],
                                 'set_min_mysql' => $_POST['set_min_mysql'],
                                 'set_description' => $_POST['set_description'],
                                 'set_author' => $_POST['set_author'],
                                 'set_author_mail' => $_POST['set_author_mail'],
                                 'set_author_website_url' => $_POST['set_author_website_url'],
                                 'set_author_website_name' => $_POST['set_author_website_name'],
                                 'set_credits' => $_POST['set_credits'],
                                 'set_license' => $_POST['set_license'],
                                 'set_release_info' => $_POST['set_release_info'],
                                 'set_release_file' => $_POST['set_release_file'],
                                 'set_manual' => $_POST['set_manual'],
                                 'set_manual_file' => $_POST['set_manual_file'], ));
        //Form set_image
        $settingsObj->setVar('set_image', $_POST['set_image']);
        //Form module save
        $settingsObj->setVars(array(
                                 'set_demo_site_url' => $_POST['set_demo_site_url'],
                                 'set_demo_site_name' => $_POST['set_demo_site_name'],
                                 'set_support_url' => $_POST['set_support_url'],
                                 'set_support_name' => $_POST['set_support_name'],
                                 'set_website_url' => $_POST['set_website_url'],
                                 'set_website_name' => $_POST['set_website_name'],
                                 'set_release' => $_POST['set_release'],
                                 'set_status' => $_POST['set_status'],
                                 'set_donations' => $_POST['set_donations'],
                                 'set_subversion' => $_POST['set_subversion'], )
        );
        $settingOption = XoopsRequest::getArray('setting_option', array());
        $settingsObj->setVar('set_admin', in_array('admin', $settingOption));
        $settingsObj->setVar('set_user', in_array('user', $settingOption));
        $settingsObj->setVar('set_blocks', in_array('blocks', $settingOption));
        $settingsObj->setVar('set_search', in_array('search', $settingOption));
        $settingsObj->setVar('set_comments', in_array('comments', $settingOption));
        $settingsObj->setVar('set_notifications', in_array('notifications', $settingOption));
        $settingsObj->setVar('set_permissions', in_array('permissions', $settingOption));
        $settingsObj->setVar('set_inroot_copy', in_array('inroot', $settingOption));

        $settingsObj->setVar('set_type', $_POST['set_type']);

        if ($tdmcreate->getHandler('settings')->insert($settingsObj)) {
            redirect_header('settings.php', 5, sprintf(_AM_TDMCREATE_MODULE_FORM_UPDATED_OK, $_POST['set_name']));
        }

        $GLOBALS['xoopsTpl']->assign('error', $settingsObj->getHtmlErrors());
        $form = $settingsObj->getFormSettings();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        // Define main template
        $templateMain = 'tdmcreate_settings.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminMenu->displayNavigation('settings.php'));
        $adminMenu->addItemButton(_AM_TDMCREATE_ADD_SETTING, 'settings.php?op=new', 'add');
        $adminMenu->addItemButton(_AM_TDMCREATE_SETTINGS_LIST, 'settings.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminMenu->renderButton());
        $settingsObj = $tdmcreate->getHandler('settings')->get($setId);
        $form = $settingsObj->getFormSettings();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'delete':
        $settingsObj = $tdmcreate->getHandler('settings')->get($setId);
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('settings.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($tdmcreate->getHandler('settings')->delete($settingsObj)) {
                redirect_header('settings.php', 3, _AM_TDMCREATE_FORMDELOK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $settingsObj->getHtmlErrors());
            }
        } else {
            xoops_confirm(array('ok' => 1, 'set_id' => $setId, 'op' => 'delete'), $_SERVER['REQUEST_URI'], sprintf(_AM_TDMCREATE_FORMSUREDEL, $settingsObj->getVar('set_name')));
        }
        break;
    case 'display':
        $id = XoopsRequest::getInt('set_id', 0, 'POST');
        if ($id > 0) {
            $settingsObj = $tdmcreate->getHandler('settings')->get($id);
            if (isset($_POST['set_type'])) {
                $setType = $settingsObj->getVar('set_type');
                $settingsObj->setVar('set_type', !$setType);
            }
            $GLOBALS['xoopsTpl']->assign('error', $settingsObj->getHtmlErrors());
        }
        break;
}
include __DIR__ .'/footer.php';
