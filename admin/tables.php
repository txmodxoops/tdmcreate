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
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 * @version         $Id: tables.php 12258 2014-01-02 09:33:29Z timgno $
 */
include __DIR__.'/header.php';
// Recovered value of arguments op in the URL $
$op = XoopsRequest::getString('op', 'list');
//
$modId = XoopsRequest::getInt('mod_id');
// Request vars
$tableId = XoopsRequest::getInt('table_id');
$tableMid = XoopsRequest::getInt('table_mid');
$tableName = XoopsRequest::getInt('table_name');
$tableNumbFields = XoopsRequest::getInt('table_nbfields');
$tableFieldname = XoopsRequest::getString('table_fieldname', '');
//
switch ($op) {
    case 'list':
    default:
        $start = XoopsRequest::getInt('start', 0);
        $limit = XoopsRequest::getInt('limit', $tdmcreate->getConfig('modules_adminpager'));
        // Define main template
        $templateMain = 'tdmcreate_tables.tpl';
        $GLOBALS['xoTheme']->addStylesheet('modules/tdmcreate/assets/css/admin/style.css');
        $GLOBALS['xoTheme']->addScript('browse.php?Frameworks/jquery/plugins/jquery.ui.js');
        $GLOBALS['xoTheme']->addScript('modules/tdmcreate/assets/js/functions.js');
        $GLOBALS['xoTheme']->addScript('modules/tdmcreate/assets/js/sortable.js');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminMenu->addNavigation('tables.php'));
        $adminMenu->addItemButton(_AM_TDMCREATE_ADD_TABLE, 'tables.php?op=new', 'add');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminMenu->renderButton());
        $GLOBALS['xoopsTpl']->assign('tdmc_upload_imgmod_url', TDMC_UPLOAD_IMGMOD_URL);
        $GLOBALS['xoopsTpl']->assign('modPathIcon16', TDMC_URL.'/'.$modPathIcon16);
        // Get the list of modules
        $modulesCount = $tdmcreate->getHandler('modules')->getCountModules();
        // Redirect if there aren't modules
        if (0 == $modulesCount) {
            redirect_header('modules.php?op=new', 10, _AM_TDMCREATE_NOTMODULES);
        }
        $modulesAll = $tdmcreate->getHandler('modules')->getAllModules($start, $limit);
        $tablesCount = $tdmcreate->getHandler('tables')->getObjects(null);
        // Redirect if there aren't tables
        if (0 == $tablesCount) {
            redirect_header('tables.php?op=new', 10, _AM_TDMCREATE_NOTTABLES);
        }
        unset($tablesCount);
        // Display modules list
        if ($modulesCount > 0) {
            foreach (array_keys($modulesAll) as $i) {
                $module = $modulesAll[$i]->getValuesModules();
                // Get the list of tables
                $tablesCount = $tdmcreate->getHandler('tables')->getCountTables();
                $tablesAll = $tdmcreate->getHandler('tables')->getAllTablesByModuleId($i);
                // Display tables list
                $tables = array();
                $lid = 1;
                if ($tablesCount > 0) {
                    foreach (array_keys($tablesAll) as $t) {
                        $table = $tablesAll[$t]->getValuesTables();
                        $alid = array('lid' => $lid);
                        $tables[] = array_merge($table, $alid);
                        unset($table);
                        ++$lid;
                    }
                }
                unset($lid);
                $module['tables'] = $tables;
                $GLOBALS['xoopsTpl']->append('modules_list', $module);
                unset($module);
            }
            if ($modulesCount > $limit) {
                include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
                $pagenav = new XoopsPageNav($modulesCount, $limit, $start, 'start', 'op=list&limit='.$limit);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('error', _AM_TDMCREATE_THEREARENT_TABLES);
        }
        break;

    case 'new':
        // Define main template
        $templateMain = 'tdmcreate_tables.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminMenu->addNavigation('tables.php'));
        $adminMenu->addItemButton(_AM_TDMCREATE_TABLES_LIST, 'tables.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminMenu->renderButton());

        $tablesObj = &$tdmcreate->getHandler('tables')->create();
        $form = $tablesObj->getFormTables();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;

    case 'save':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('tables.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        //
        $tables = &$tdmcreate->getHandler('tables');
        //
        if (isset($tableId)) {
            $tablesObj = &$tables->get($tableId);
        } else {
            // Checking if table name exist in the same module
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('table_mid', $tableMid));
            $tableNameSearch = $tables->getObjects($criteria);
            unset($criteria);
            //unset($criteria);
            foreach (array_keys($tableNameSearch) as $t) {
                if ($tableNameSearch[$t]->getVar('table_name') === $_POST['table_name']) {
                    redirect_header('tables.php?op=new', 3, sprintf(_AM_TDMCREATE_ERROR_TABLE_NAME_EXIST, $_POST['table_name']));
                }
            }
            $tablesObj = &$tables->create();
        }
        $tableOrder = XoopsRequest::getInt('table_order');
        $order = $tablesObj->isNew() ? $tableOrder + 1 : $tableOrder;
        // Form save tables
        $tablesObj->setVars(array(
                                'table_mid' => $tableMid,
                                'table_name' => $_POST['table_name'],
                                'table_solename' => $_POST['table_solename'],
                                'table_category' => (1 == $_REQUEST['table_category']) ? 1 : 0,
                                'table_fieldname' => $tableFieldname,
                                'table_nbfields' => $tableNumbFields,
                                'table_order' => $order, ));
        //Form table_image
        include_once XOOPS_ROOT_PATH.'/class/uploader.php';
        $uploaddir = is_dir(XOOPS_ICONS32_PATH) ? XOOPS_ICONS32_PATH : TDMC_UPLOAD_IMGTAB_PATH;
        $uploader = new XoopsMediaUploader($uploaddir, $tdmcreate->getConfig('mimetypes'),
                                            $tdmcreate->getConfig('maxsize'), null, null);
        if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
            $uploader->fetchMedia($_POST['xoops_upload_file'][0]);
            if (!$uploader->upload()) {
                $errors = $uploader->getErrors();
                redirect_header('javascript:history.go(-1)', 3, $errors);
            } else {
                $tablesObj->setVar('table_image', $uploader->getSavedFileName());
            }
        } else {
            $tablesObj->setVar('table_image', $_POST['table_image']);
        }
        $tablesObj->setVar('table_autoincrement', (1 == $_REQUEST['table_autoincrement']) ? 1 : 0);
        // Options
        $tableOption = XoopsRequest::getArray('table_option', array());
        $tablesObj->setVar('table_install', in_array('install', $tableOption));
        $tablesObj->setVar('table_index', in_array('index', $tableOption));
        $tablesObj->setVar('table_blocks', in_array('blocks', $tableOption));
        $tablesObj->setVar('table_admin', in_array('admin', $tableOption));
        $tablesObj->setVar('table_user', in_array('user', $tableOption));
        $tablesObj->setVar('table_submenu', in_array('submenu', $tableOption));
        $tablesObj->setVar('table_submit', in_array('submit', $tableOption));
        $tablesObj->setVar('table_tag', in_array('tag', $tableOption));
        $tablesObj->setVar('table_broken', in_array('broken', $tableOption));
        $tablesObj->setVar('table_search', in_array('search', $tableOption));
        $tablesObj->setVar('table_comments', in_array('comments', $tableOption));
        $tablesObj->setVar('table_notifications', in_array('notifications', $tableOption));
        $tablesObj->setVar('table_permissions', in_array('permissions', $tableOption));
        $tablesObj->setVar('table_rate', in_array('rate', $tableOption));
        $tablesObj->setVar('table_print', in_array('print', $tableOption));
        $tablesObj->setVar('table_pdf', in_array('pdf', $tableOption));
        $tablesObj->setVar('table_rss', in_array('rss', $tableOption));
        $tablesObj->setVar('table_single', in_array('single', $tableOption));
        $tablesObj->setVar('table_visit', in_array('visit', $tableOption));
        //
        if ($tables->insert($tablesObj)) {
            if ($tablesObj->isNew()) {
                $tableTid = $GLOBALS['xoopsDB']->getInsertId();
                $tableAction = '&field_mid='.$tableMid.'&field_tid='.$tableTid.'&field_numb='.$tableNumbFields.'&field_name='.$tableFieldname;
                // Fields Elements Handler
                $fieldelementObj = $tdmcreate->getHandler('fieldelements')->create();
                $fieldelementObj->setVar('fieldelement_mid', $tableMid);
                $fieldelementObj->setVar('fieldelement_tid', $tableTid);
                $fieldelementObj->setVar('fieldelement_name', 'Table : '.ucfirst($_POST['table_name']));
                $fieldelementObj->setVar('fieldelement_value', 'XoopsFormTables-'.ucfirst($_POST['table_name']));
                // Insert new field element id for table name
                if (!$tdmcreate->getHandler('fieldelements')->insert($fieldelementObj)) {
                    $GLOBALS['xoopsTpl']->assign('error', $fieldelementObj->getHtmlErrors().' Field element');
                }
                redirect_header('fields.php?op=new'.$tableAction, 5, sprintf(_AM_TDMCREATE_TABLE_FORM_CREATED_OK, $_POST['table_name']));
            } else {
                redirect_header('tables.php', 5, sprintf(_AM_TDMCREATE_TABLE_FORM_UPDATED_OK, $_POST['table_name']));
            }
        }
        //
        $GLOBALS['xoopsTpl']->assign('error', $tablesObj->getHtmlErrors());
        $form = $tablesObj->getFormTables();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;

    case 'edit':
        // Define main template
        $templateMain = 'tdmcreate_tables.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminMenu->addNavigation('tables.php'));
        $adminMenu->addItemButton(_AM_TDMCREATE_ADD_TABLE, 'tables.php?op=new', 'add');
        $adminMenu->addItemButton(_AM_TDMCREATE_TABLES_LIST, 'tables.php?op=list', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminMenu->renderButton());

        $tablesObj = $tdmcreate->getHandler('tables')->get($tableId);
        $form = $tablesObj->getFormTables();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;

    case 'order':
        // Initialize tables handler
        $tablesObj = $tdmcreate->getHandler('tables');
        if (isset($_POST['torder'])) {
            $i = 0;
            foreach ($_POST['torder'] as $order) {
                if ($order > 0) {
                    $tableOrder = $tablesObj->get($order);
                    $tableOrder->setVar('table_order', $i);
                    if (!$tablesObj->insert($tableOrder)) {
                        $error = true;
                    }
                    ++$i;
                }
            }
            redirect_header('tables.php', 5, _AM_TDMCREATE_TABLE_ORDER_ERROR);
            unset($i);
        }
        exit;
        break;

    case 'delete':
        $tablesObj = &$tdmcreate->getHandler('tables')->get($tableId);
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('tables.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($tdmcreate->getHandler('tables')->delete($tablesObj)) {
                // Delete items in table fieldelements - idea by goffy
                $fieldelements = $tdmcreate->getHandler('fieldelements')->getAllFieldElementsByModuleAndTableId($tableMid, $tableId);
                foreach (array_keys($fieldelements) as $fe) {
                    $fieldElementsObj = &$tdmcreate->getHandler('fieldelements')->get($fieldelements[$fe]->getVar('fieldelement_id'));
                    if (!$tdmcreate->getHandler('fieldelements')->delete($fieldElementsObj)) {
                        $GLOBALS['xoopsTpl']->assign('error', $fieldElementsObj->getHtmlErrors());
                    }
                    unset($fieldElementsObj);
                }
                redirect_header('tables.php', 3, _AM_TDMCREATE_FORMDELOK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $tablesObj->getHtmlErrors());
            }
        } else {
            xoops_confirm(array('ok' => 1, 'table_id' => $tableId, 'op' => 'delete'), $_SERVER['REQUEST_URI'], sprintf(_AM_TDMCREATE_FORMSUREDEL, $tablesObj->getVar('table_name')));
        }
        break;

    case 'display':
        $modArray = array('admin', 'user', 'blocks', 'search', 'comments', 'notifications', 'permissions');
        $mid = XoopsRequest::getInt('mod_id', 0, 'POST');
        if ($mid > 0) {
            $modulesObj = $tdmcreate->getHandler('modules')->get($mid);
            foreach ($modArray as $modField) {
                if (isset($_POST['mod_'.$modField])) {
                    $mField = $modulesObj->getVar('mod_'.$modField);
                    $modulesObj->setVar('mod_'.$modField, !$mField);
                }
            }
            if ($tdmcreate->getHandler('modules')->insert($modulesObj)) {
                redirect_header('modules.php', 3, _AM_TDMCREATE_TOGGLE_SUCCESS);
            }
            $GLOBALS['xoopsTpl']->assign('error', $modulesObj->getHtmlErrors());
        }
        $tableArray = array('admin', 'user', 'blocks', 'submenu', 'search', 'comments', 'notifications', 'permissions');
        $tid = XoopsRequest::getInt('table_id', 0, 'POST');
        if ($tid > 0) {
            $tablesObj = $tdmcreate->getHandler('tables')->get($tid);
            foreach ($tableArray as $tableField) {
                if (isset($_POST['table_'.$tableField])) {
                    $tblField = $tablesObj->getVar('table_'.$tableField);
                    $tablesObj->setVar('table_'.$tableField, !$tblField);
                }
            }
            if ($tdmcreate->getHandler('tables')->insert($tablesObj)) {
                redirect_header('tables.php', 3, _AM_TDMCREATE_TOGGLE_SUCCESS);
            }
            $GLOBALS['xoopsTpl']->assign('error', $tablesObj->getHtmlErrors());
        }
        break;
}
include __DIR__.'/footer.php';
