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
 * tdmcreate module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         tdmcreate
 * @since           2.5.5
 * @author          Txmod Xoops <support@txmodxoops.org>
 * @version         $Id: 1.59 settings.php 11297 2013-03-24 10:58:10Z timgno $
 */
include  __DIR__ . '/header.php';
// Recovered value of argument op in the URL $
$op = XoopsRequest::getString('op', 'edit');
//
$setId = XoopsRequest::getInt('set_id');
//
switch ($op) {
    case 'edit':
    default:
        // Define main template
        $templateMain = 'tdmcreate_settings.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminMenu->addNavigation('settings.php'));
        $settingsObj = $tdmcreate->getHandler('settings')->get($setId);
        $form        = $settingsObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;

    case 'save':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('settings.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
		if (isset($setId)) {
            $settingsObj =& $tdmcreate->getHandler('settings')->get($setId);
        } 
        $moduleDirname = preg_replace('/[^a-zA-Z0-9]\s+/', '', strtolower($_POST['set_dirname']));
        //Form module save
        $settingsObj->setVars(array(
                                 'set_name'                => $_POST['set_name'],
                                 'set_dirname'             => $moduleDirname,
                                 'set_version'             => $_POST['set_version'],
                                 'set_since'               => $_POST['set_since'],
                                 'set_min_php'             => $_POST['set_min_php'],
                                 'set_min_xoops'           => $_POST['set_min_xoops'],
                                 'set_min_admin'           => $_POST['set_min_admin'],
                                 'set_min_mysql'           => $_POST['set_min_mysql'],
                                 'set_description'         => $_POST['set_description'],
                                 'set_author'              => $_POST['set_author'],
                                 'set_author_mail'         => $_POST['set_author_mail'],
                                 'set_author_website_url'  => $_POST['set_author_website_url'],
                                 'set_author_website_name' => $_POST['set_author_website_name'],
                                 'set_credits'             => $_POST['set_credits'],
                                 'set_license'             => $_POST['set_license'],
                                 'set_release_info'        => $_POST['set_release_info'],
                                 'set_release_file'        => $_POST['set_release_file'],
                                 'set_manual'              => $_POST['set_manual'],
                                 'set_manual_file'         => $_POST['set_manual_file']));
        //Form mod_image
        $settingsObj->setVar('set_image', $_POST['set_image']);
        //Form module save
        $settingsObj->setVars(array(
                                 'set_demo_site_url'  => $_POST['set_demo_site_url'],
                                 'set_demo_site_name' => $_POST['set_demo_site_name'],
                                 'set_support_url'    => $_POST['set_support_url'],
                                 'set_support_name'   => $_POST['set_support_name'],
                                 'set_website_url'    => $_POST['set_website_url'],
                                 'set_website_name'   => $_POST['set_website_name'],
                                 'set_release'        => $_POST['set_release'],
                                 'set_status'         => $_POST['set_status'],
                                 'set_admin'          => ((1 == $_REQUEST['set_admin']) ? 1 : 0),
                                 'set_user'           => ((1 == $_REQUEST['set_user']) ? 1 : 0),
                                 'set_blocks'         => ((1 == $_REQUEST['set_blocks']) ? 1 : 0),
                                 'set_search'         => ((1 == $_REQUEST['set_search']) ? 1 : 0),
                                 'set_comments'       => ((1 == $_REQUEST['set_comments']) ? 1 : 0),
                                 'set_notifications'  => ((1 == $_REQUEST['set_notifications']) ? 1 : 0),
                                 'set_permissions'    => ((1 == $_REQUEST['set_permissions']) ? 1 : 0),
                                 'set_inroot_copy'    => ((1 == $_REQUEST['set_inroot_copy']) ? 1 : 0),
                                 'set_donations'      => $_POST['set_donations'],
                                 'set_subversion'     => $_POST['set_subversion'])
        );

        if ($tdmcreate->getHandler('settings')->insert($settingsObj)) {
            redirect_header('settings.php', 5, sprintf(_AM_TDMCREATE_MODULE_FORM_UPDATED_OK, $_POST['set_name']));
        }

        $GLOBALS['xoopsTpl']->assign('error', $settingsObj->getHtmlErrors());
        $form =& $settingsObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
    break;   
}
include  __DIR__ . '/footer.php';
