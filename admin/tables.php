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
 * @since           2.5.0
 * @author          Txmod Xoops http://www.txmodxoops.org
 * @version         $Id: tables.php 12258 2014-01-02 09:33:29Z timgno $
 */
include  __DIR__ . '/header.php';
// Recovered value of arguments op in the URL $
$op = XoopsRequest::getString('op', 'list');
//
$mod_id = XoopsRequest::getInt('mod_id');
// Request vars
$tableId         = XoopsRequest::getInt('table_id');
$tableMid        = XoopsRequest::getInt('table_mid');
$tableName       = XoopsRequest::getInt('table_name');
$tableNumbFields = XoopsRequest::getInt('table_nbfields');
$tableFieldname  = XoopsRequest::getString('table_fieldname', '');
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
        $GLOBALS['xoopsTpl']->assign('tdmc_url', TDMC_URL);
        $GLOBALS['xoopsTpl']->assign('tdmc_icons_url', TDMC_ICONS_URL);
        $GLOBALS['xoopsTpl']->assign('tdmc_upload_imgmod_url', TDMC_UPLOAD_IMGMOD_URL);        
        $GLOBALS['xoopsTpl']->assign('tdmc_upload_imgtab_url', TDMC_UPLOAD_IMGTAB_URL);
        $GLOBALS['xoopsTpl']->assign('modPathIcon16', $modPathIcon16);
        $GLOBALS['xoopsTpl']->assign('sysPathIcon32', $sysPathIcon32);
        // Get the list of modules
		$modulesCount = $tdmcreate->getHandler('modules')->getCountModules();
        // Redirect if there aren't modules
        if (0 == $modulesCount) {
            redirect_header('modules.php?op=new', 10, _AM_TDMCREATE_NOTMODULES);
        }
        $modulesAll  = $tdmcreate->getHandler('modules')->getAllModules($start, $limit);
        $tablesCount = $tdmcreate->getHandler('tables')->getObjects(null);
        // Redirect if there aren't tables
        if (0 == $tablesCount) {
            redirect_header('tables.php?op=new', 10, _AM_TDMCREATE_NOTTABLES);
        }
        unset($tablesCount);
        // Display modules list
        if ($modulesCount > 0) {
            foreach (array_keys($modulesAll) as $i) {
                $module = $modulesAll[$i]->getValues();
                // Get the list of tables
                $tablesCount = $tdmcreate->getHandler('tables')->getCountTables();
                $tablesAll   = $tdmcreate->getHandler('tables')->getAllTablesByModuleId($i);
                // Display tables list
                $tables = array();
                $lid    = 1;
                if ($tablesCount > 0) {
                    foreach (array_keys($tablesAll) as $t) {                        
                        $table    = $tablesAll[$t]->getValues();
						$alid     = array('lid' => $lid);
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
                include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
                $pagenav = new XoopsPageNav($modulesCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
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

        $tablesObj =& $tdmcreate->getHandler('tables')->create();
        $form      = $tablesObj->getForm();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;

    case 'save':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('tables.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        //
        $tables =& $tdmcreate->getHandler('tables');        
        //
        if (isset($tableId)) {
            $tablesObj =& $tables->get($tableId);			
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
			$tablesObj =& $tables->create();
        }
		$tableOrder = XoopsRequest::getInt('table_order');
        $order = $tablesObj->isNew() ? $tableOrder + 1 : $tableOrder;
        // Form save tables
        $tablesObj->setVars(array(
                                'table_mid'       => $tableMid,
                                'table_name'      => $_POST['table_name'],
								'table_solename'  => $_POST['table_solename'],
                                'table_category'  => ((1 == $_REQUEST['table_category']) ? 1 : 0),
                                'table_fieldname' => $tableFieldname,
                                'table_nbfields'  => $tableNumbFields,
                                'table_order'     => $order));
        //Form table_image
        include_once XOOPS_ROOT_PATH . '/class/uploader.php';
        $uploaddir = is_dir(XOOPS_ICONS32_PATH) ? XOOPS_ICONS32_PATH : TDMC_UPLOAD_IMGTAB_PATH;
        $uploader  = new XoopsMediaUploader($uploaddir, $tdmcreate->getConfig('mimetypes'),
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
        $tablesObj->setVars(array(
                                'table_autoincrement' => ((1 == $_REQUEST['table_autoincrement']) ? 1 : 0),
                                'table_index'         => ((1 == $_REQUEST['table_index']) ? 1 : 0),
								'table_blocks'        => ((1 == $_REQUEST['table_blocks']) ? 1 : 0),
                                'table_admin'         => ((1 == $_REQUEST['table_admin']) ? 1 : 0),
                                'table_user'          => ((1 == $_REQUEST['table_user']) ? 1 : 0),
                                'table_submenu'       => ((1 == $_REQUEST['table_submenu']) ? 1 : 0),
                                'table_submit'        => ((1 == $_REQUEST['table_submit']) ? 1 : 0),
                                'table_tag'           => ((1 == $_REQUEST['table_tag']) ? 1 : 0),
                                'table_broken'        => ((1 == $_REQUEST['table_broken']) ? 1 : 0),
                                'table_search'        => ((1 == $_REQUEST['table_search']) ? 1 : 0),
                                'table_comments'      => ((1 == $_REQUEST['table_comments']) ? 1 : 0),
                                'table_notifications' => ((1 == $_REQUEST['table_notifications']) ? 1 : 0),
                                'table_permissions'   => ((1 == $_REQUEST['table_permissions']) ? 1 : 0),
                                'table_rate'          => ((1 == $_REQUEST['table_rate']) ? 1 : 0),
                                'table_print'         => ((1 == $_REQUEST['table_print']) ? 1 : 0),
                                'table_pdf'           => ((1 == $_REQUEST['table_pdf']) ? 1 : 0),
                                'table_rss'           => ((1 == $_REQUEST['table_rss']) ? 1 : 0),
                                'table_single'        => ((1 == $_REQUEST['table_single']) ? 1 : 0),
                                'table_visit'         => ((1 == $_REQUEST['table_visit']) ? 1 : 0)
                            ));
        //
        if ($tables->insert($tablesObj)) {
            if ($tablesObj->isNew()) {
                $tableTid    = $GLOBALS['xoopsDB']->getInsertId();
                $tableAction = '&field_mid=' . $tableMid . '&field_tid=' . $tableTid . '&field_numb=' . $tableNumbFields . '&field_name=' . $tableFieldname;
                redirect_header('fields.php?op=new' . $tableAction, 5, sprintf(_AM_TDMCREATE_TABLE_FORM_CREATED_OK, $_POST['table_name']));
            } else {
                redirect_header('tables.php', 5, sprintf(_AM_TDMCREATE_TABLE_FORM_UPDATED_OK, $_POST['table_name']));
            }
        }
        //
        $GLOBALS['xoopsTpl']->assign('error', $tablesObj->getHtmlErrors());
        $form = $tablesObj->getForm();
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
        $form      = $tablesObj->getForm();
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
        $tablesObj =& $tdmcreate->getHandler('tables')->get($tableId);
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('tables.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($tdmcreate->getHandler('tables')->delete($tablesObj)) {
                redirect_header('tables.php', 3, _AM_TDMCREATE_FORMDELOK);
            } else {
                echo $tablesObj->getHtmlErrors();
            }
        } else {
            xoops_confirm(array('ok' => 1, 'table_id' => $tableId, 'op' => 'delete'), $_SERVER['REQUEST_URI'], sprintf(_AM_TDMCREATE_FORMSUREDEL, $tablesObj->getVar('table_name')));
        }
        break;

    case 'display':
        $modId = tdmcreate_CleanVars($_POST, 'mod_id', 0, 'int');
        if ($modId > 0) {
            $modulesObj = $tdmcreate->getHandler('modules')->get($modId);
			if (isset($_POST['mod_admin'])) {
				$mod_admin = $modulesObj->getVar('mod_admin');
				$modulesObj->setVar('mod_admin', !$mod_admin);
			}
			if (isset($_POST['mod_user'])) {
				$mod_user = $modulesObj->getVar('mod_user');
				$modulesObj->setVar('mod_user', !$mod_user);
			}
			if (isset($_POST['mod_blocks'])) {
				$mod_blocks = $modulesObj->getVar('mod_blocks');
				$modulesObj->setVar('mod_blocks', !$mod_blocks);
			}
			if (isset($_POST['mod_search'])) {
				$mod_search = $modulesObj->getVar('mod_search');
				$modulesObj->setVar('mod_search', !$mod_search);
			}
			if (isset($_POST['mod_comments'])) {
				$mod_comments = $modulesObj->getVar('mod_comments');
				$modulesObj->setVar('mod_comments', !$mod_comments);
			}
			if (isset($_POST['mod_notifications'])) {
				$mod_notifications = $modulesObj->getVar('mod_notifications');
				$modulesObj->setVar('mod_notifications', !$mod_notifications);
			}
			if (isset($_POST['mod_permissions'])) {
				$mod_permissions  = $modulesObj->getVar('mod_permissions');
				$modulesObj->setVar('mod_permissions', !$mod_permissions);
			}
            if ($tdmcreate->getHandler('modules')->insert($modulesObj)) {
                redirect_header('modules.php', 3, _AM_TDMCREATE_TOGGLE_SUCCESS);
            }
            $GLOBALS['xoopsTpl']->assign('error', $modulesObj->getHtmlErrors());
        }
        
		$tableId = tdmcreate_CleanVars($_POST, 'table_id', 0, 'int');
        if ($tableId > 0) {
            $tablesObj = $tdmcreate->getHandler('tables')->get($tableId);
			if (isset($_POST['table_admin'])) {
				$table_admin = $tablesObj->getVar('table_admin');
				$tablesObj->setVar('table_admin', !$table_admin);
			}
			if (isset($_POST['table_user'])) {
				$table_user = $tablesObj->getVar('table_user');
				$tablesObj->setVar('table_user', !$table_user);
			}
			if (isset($_POST['table_blocks'])) {
				$table_blocks = $tablesObj->getVar('table_blocks');
				$tablesObj->setVar('table_blocks', !$table_blocks);
			}
			if (isset($_POST['table_search'])) {
				$table_search = $tablesObj->getVar('table_search');
				$tablesObj->setVar('table_search', !$table_search);
			}
			if (isset($_POST['table_comments'])) {
				$table_comments = $tablesObj->getVar('table_comments');
				$tablesObj->setVar('table_comments', !$table_comments);
			}
			if (isset($_POST['table_notifications'])) {
				$table_notifications = $tablesObj->getVar('table_notifications');
				$tablesObj->setVar('table_notifications', !$table_notifications);
			}
			if (isset($_POST['table_permissions'])) {
				$table_permissions  = $tablesObj->getVar('table_permissions');
				$tablesObj->setVar('table_permissions', !$table_permissions);
			}
            if ($tdmcreate->getHandler('tables')->insert($tablesObj)) {
                redirect_header('tables.php', 3, _AM_TDMCREATE_TOGGLE_SUCCESS);
            }
            $GLOBALS['xoopsTpl']->assign('error', $tablesObj->getHtmlErrors());
        }	
		break;
}
include  __DIR__ . '/footer.php';
