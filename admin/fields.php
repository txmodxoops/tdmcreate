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
 * @version         $Id: 1.91 fields.php 12258 2014-01-02 09:33:29Z timgno $
 */
include  __DIR__ . '/header.php';
// Recovered value of arguments op in the URL $
$op = XoopsRequest::getString('op', 'list');
// Get fields Variables
$fieldMid   = XoopsRequest::getInt('field_mid');
$fieldTid   = XoopsRequest::getInt('field_tid');
$fieldNumb  = XoopsRequest::getInt('field_numb');
$fieldOrder = XoopsRequest::getInt('field_order');
$fieldName  = XoopsRequest::getString('field_name', '');
// switch op
switch ($op) {
    case 'list':
    default:
        $start = XoopsRequest::getInt('start', 0);
        $limit = XoopsRequest::getInt('limit', $tdmcreate->getConfig('tables_adminpager'));
        // Define main template
        $template_main = 'tdmcreate_fields.tpl';
        $GLOBALS['xoTheme']->addStylesheet('modules/tdmcreate/assets/css/admin/style.css');
        $GLOBALS['xoTheme']->addScript('modules/tdmcreate/assets/js/functions.js');
        $GLOBALS['xoTheme']->addScript('modules/tdmcreate/assets/js/sortable.js');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminMenu->addNavigation('fields.php'));
        $adminMenu->addItemButton(_AM_TDMCREATE_ADD_TABLE, 'tables.php?op=new', 'add');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminMenu->renderButton());
        $GLOBALS['xoopsTpl']->assign('tdmc_url', TDMC_URL);
        $GLOBALS['xoopsTpl']->assign('tdmc_icons_url', TDMC_ICONS_URL);
        $GLOBALS['xoopsTpl']->assign('tdmc_upload_url', TDMC_UPLOAD_URL);
        $GLOBALS['xoopsTpl']->assign('tdmc_upload_imgtab_url', TDMC_UPLOAD_IMGTAB_URL);
        $GLOBALS['xoopsTpl']->assign('modPathIcon16', $modPathIcon16);
        $GLOBALS['xoopsTpl']->assign('sysPathIcon32', $sysPathIcon32);
        // Redirect if there aren't modules
        $countModules = $tdmcreate->getHandler('modules')->getCount();
        if (0 == $countModules) {
            redirect_header('modules.php?op=new', 2, _AM_TDMCREATE_NOTMODULES);
        }
        unset($countModules);
        // Redirect if there aren't tables
        $handlerTables = $tdmcreate->getHandler('tables');
        $countTables   = $handlerTables->getCount();
        if (0 == $countTables) {
            redirect_header('tables.php?op=new', 2, _AM_TDMCREATE_NOTTABLES);
        }
        unset($countTables);
        // Get the list of tables
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('table_mid', $handlerTables->getVar('table_mid')));
        $criteria->setStart($start);
        $criteria->setLimit($limit);
        $criteria->setSort('table_id ASC, table_order ASC, table_name');
        $criteria->setOrder('ASC');
        $countTables = $tdmcreate->getHandler('tables')->getCount($criteria);
        $tablesAll   = $tdmcreate->getHandler('tables')->getAll($criteria);
        unset($criteria);
        if ($countTables > 0) {
            $tlid = 1;
            foreach (array_keys($tablesAll) as $tid) {
                // Display tables list
                $table['id']            = $tid;
                $table['lid']           = $tlid;
                $table['mid']           = $tablesAll[$tid]->getVar('table_mid');
                $table['name']          = ucfirst($tablesAll[$tid]->getVar('table_name'));
                $table['image']         = $tablesAll[$tid]->getVar('table_image');
                $table['nbfields']      = $tablesAll[$tid]->getVar('table_nbfields');
                $table['order']         = $tablesAll[$tid]->getVar('table_order');
                $table['autoincrement'] = $tablesAll[$tid]->getVar('table_autoincrement');
                $table['blocks']        = $tablesAll[$tid]->getVar('table_blocks');
                $table['admin']         = $tablesAll[$tid]->getVar('table_admin');
                $table['user']          = $tablesAll[$tid]->getVar('table_user');
                $table['search']        = $tablesAll[$tid]->getVar('table_search');
                // Get the list of fields
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('field_mid', $table['mid']));
                $criteria->add(new Criteria('field_tid', $tid));
                $criteria->setSort('field_order ASC, field_id ASC, field_name');
                $criteria->setOrder('ASC');
                $countFields = $tdmcreate->getHandler('fields')->getCount($criteria);
                $fieldsAll   = $tdmcreate->getHandler('fields')->getObjects($criteria);
                unset($criteria);
                // Display fields list
                $fields = array();
                $lid    = 1;
                if ($countFields > 0) {
                    foreach (array_keys($fieldsAll) as $fid) {
                        $field['id']       = $fid;
                        $field['lid']      = $lid;
                        $field['order']    = $fieldsAll[$fid]->getVar('field_order');
                        $field['name']     = str_replace('_', ' ', ucfirst($fieldsAll[$fid]->getVar('field_name')));
                        $field['parent']   = $fieldsAll[$fid]->getVar('field_parent');
                        $field['inlist']   = $fieldsAll[$fid]->getVar('field_inlist');
                        $field['inform']   = $fieldsAll[$fid]->getVar('field_inform');
                        $field['admin']    = $fieldsAll[$fid]->getVar('field_admin');
                        $field['user']     = $fieldsAll[$fid]->getVar('field_user');
                        $field['block']    = $fieldsAll[$fid]->getVar('field_block');
                        $field['main']     = $fieldsAll[$fid]->getVar('field_main');
                        $field['search']   = $fieldsAll[$fid]->getVar('field_search');
                        $field['required'] = $fieldsAll[$fid]->getVar('field_required');
                        $fields[]          = $field;
                        unset($field);
                        ++$lid;
                    }
                }
                ++$tlid;
                unset($lid);
                $table['fields'] = $fields;
                $GLOBALS['xoopsTpl']->append('tables_list', $table);
                unset($table);
            }
            unset($tlid, $fields);
            if ($countTables > $limit) {
                include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
                $pagenav = new XoopsPageNav($countTables, $limit, $start, 'start', 'op=list&limit=' . $limit);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('error', _AM_TDMCREATE_THEREARENT_FIELDS);
        }
        break;

    case 'new':
        // Define main template
        $template_main = 'tdmcreate_fields.tpl';
        $GLOBALS['xoTheme']->addStylesheet('modules/tdmcreate/assets/css/admin/style.css');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminMenu->addNavigation('fields.php'));
        $adminMenu->addItemButton(_AM_TDMCREATE_TABLES_LIST, 'tables.php', 'list');
        $adminMenu->addItemButton(_AM_TDMCREATE_FIELDS_LIST, 'fields.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminMenu->renderButton());
        // Form Add
        $fieldsObj =& $tdmcreate->getHandler('fields')->create();
        $form      = $fieldsObj->getFormNew($fieldMid, $fieldTid, $fieldNumb, $fieldName);
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;

    case 'save':
        //
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('fields.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        $fieldId = XoopsRequest::getInt('field_id');
        // Fields Handler
        $fields = $tdmcreate->getHandler('fields');
	//
	$order = $fieldsObj->isNew() ? $fieldOrder + 1 : $fieldOrder;
        // Set Variables
        foreach ($_POST['field_id'] as $key => $value) {
            if (isset($value)) {
                $fieldsObj =& $fields->get($value);
            } else {
                $fieldsObj =& $fields->create();
            }            
		// Set Data
		$fieldsObj->setVar('field_mid', $fieldMid);
		$fieldsObj->setVar('field_tid', $fieldTid);
		$fieldsObj->setVar('field_order', $_POST['field_name'][$key] ? $_POST['field_name'][$key] : $order);
		$fieldsObj->setVar('field_name', $_POST['field_name'][$key] ? $_POST['field_name'][$key] : '');
		$fieldsObj->setVar('field_type', $_POST['field_type'][$key] ? $_POST['field_type'][$key] : '');
		$fieldsObj->setVar('field_value', $_POST['field_value'][$key] ? $_POST['field_value'][$key] : '');
		$fieldsObj->setVar('field_attribute', ($_POST['field_attribute'][$key] ? $_POST['field_attribute'][$key] : '');
		$fieldsObj->setVar('field_null', $_POST['field_null'][$key] ? $_POST['field_null'][$key] : '');
		$fieldsObj->setVar('field_default', $_POST['field_default'][$key] ? $_POST['field_default'][$key] : '');
		$fieldsObj->setVar('field_key', $_POST['field_key'][$key] ? $_POST['field_key'][$key] : '');
		$fieldsObj->setVar('field_element', $_POST['field_element'][$key] ? $_POST['field_element'][$key] : '');
		$fieldsObj->setVar('field_parent', ($_REQUEST['field_parent'][$key] == 1) ? 1 : 0);
		$fieldsObj->setVar('field_inlist', ($_REQUEST['field_inlist'][$key] == 1) ? 1 : 0);
		$fieldsObj->setVar('field_inform', ($_REQUEST['field_inform'][$key] == 1) ? 1 : 0);
		$fieldsObj->setVar('field_admin', ($_REQUEST['field_admin'][$key] == 1) ? 1 : 0);
		$fieldsObj->setVar('field_user', ($_REQUEST['field_user'][$key] == 1) ? 1 : 0);
		$fieldsObj->setVar('field_block', ($_REQUEST['field_block'][$key] == 1) ? 1 : 0);
		$fieldsObj->setVar('field_main', ($key == $_REQUEST['field_main'] ? 1 : 0));
		$fieldsObj->setVar('field_search', ($_REQUEST['field_search'][$key] == 1) ? 1 : 0);
		$fieldsObj->setVar('field_required', ($_REQUEST['field_required'][$key] == 1) ? 1 : 0);
		// Insert Data
		$tdmcreate->getHandler('fields')->insert($fieldsObj);
        }
        // Get table name from field table id
        $tables    =& $tdmcreate->getHandler('tables')->get($fieldTid);
        $tableName = $tables->getVar('table_name');
        // Set field elements
        if ($fieldsObj->isNew()) {
            // Fields Elements Handler
            $fieldelementObj =& $tdmcreate->getHandler('fieldelements')->create();
            $fieldelementObj->setVar('fieldelement_mid', $fieldMid);
            $fieldelementObj->setVar('fieldelement_tid', $fieldTid);
            $fieldelementObj->setVar('fieldelement_name', 'Table : ' . ucfirst($tableName));
            $fieldelementObj->setVar('fieldelement_value', 'XoopsFormTables-' . ucfirst($tableName));
            // Insert new field element id for table name
            if (!$tdmcreate->getHandler('fieldelements')->insert($fieldelementObj)) {
                $GLOBALS['xoopsTpl']->assign('error', $fieldelementObj->getHtmlErrors() . ' Field element');
            }
            redirect_header('fields.php', 2, sprintf(_AM_TDMCREATE_FIELDS_FORM_SAVED_OK, $tableName));
        } else {
            // Needed code from table name by field_tid
            redirect_header('fields.php', 2, sprintf(_AM_TDMCREATE_FIELDS_FORM_UPDATED_OK, $tableName));
        }
        //
        $GLOBALS['xoopsTpl']->assign('error', $fieldsObj->getHtmlErrors());
        $form = $fieldsObj->getForm(null, $fieldTid);
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;

    case 'edit':
        // Define main template
        $template_main = 'tdmcreate_fields.tpl';
        $GLOBALS['xoopsTpl']->assign('navigation', $adminMenu->addNavigation('fields.php'));
        $adminMenu->addItemButton(_AM_TDMCREATE_ADD_TABLE, 'tables.php?op=new', 'add');
        $adminMenu->addItemButton(_AM_TDMCREATE_TABLES_LIST, 'tables.php', 'list');
        $adminMenu->addItemButton(_AM_TDMCREATE_FIELDS_LIST, 'fields.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminMenu->renderButton());
        // Form Edit
        $fieldId   = XoopsRequest::getInt('field_id');
        $fieldsObj = $tdmcreate->getHandler('fields')->get($fieldId);
        $form      = $fieldsObj->getFormEdit($fieldMid, $fieldTid);
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;

    case 'order':
        // Initialize fields handler
        $fieldsObj = $tdmcreate->getHandler('fields');
        if (isset($_POST['forder'])) {
            $i = 0;
            foreach ($_POST['forder'] as $order) {
                if ($order > 0) {
                    $fieldOrder = $fieldsObj->get($order);
                    $fieldOrder->setVar('field_order', $i);
                    if (!$fieldsObj->insert($fieldOrder)) {
                        $error = true;
                    }
                    ++$i;
                }
            }
            redirect_header('fields.php', 5, _AM_TDMCREATE_FIELD_ORDER_ERROR);
            unset($i);
        }
        exit;
        break;

    case 'delete':
        $tablesObj =& $tdmcreate->getHandler('tables')->get($fieldTid);
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('fields.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($tdmcreate->getHandler('tables')->delete($tablesObj)) {
                redirect_header('fields.php', 3, _AM_TDMCREATE_FORMDELOK);
            } else {
                echo $tablesObj->getHtmlErrors();
            }
        } else {
            xoops_confirm(array('ok' => 1, 'field_tid' => $fieldTid, 'op' => 'delete'), $_SERVER['REQUEST_URI'], sprintf(_AM_TDMCREATE_FORMSUREDEL, $tablesObj->getVar('table_name')));
        }
        break;

    case 'display':
        // Fields Handler
        $fields = $tdmcreate->getHandler('fields');
        //
        foreach ($_POST['field_id'] as $key => $value) {
            $fieldsObj =& $fields->get($value);
            $fieldsObj->setVar('field_parent', ($_REQUEST['field_parent'][$key] == 1) ? 0 : 1);
            $fieldsObj->setVar('field_inlist', ($_REQUEST['field_inlist'][$key] == 1) ? 0 : 1);
            $fieldsObj->setVar('field_inform', ($_REQUEST['field_inform'][$key] == 1) ? 0 : 1);
            $fieldsObj->setVar('field_admin', ($_REQUEST['field_admin'][$key] == 1) ? 0 : 1);
            $fieldsObj->setVar('field_user', ($_REQUEST['field_user'][$key] == 1) ? 0 : 1);
            $fieldsObj->setVar('field_block', ($_REQUEST['field_block'][$key] == 1) ? 0 : 1);
            $fieldsObj->setVar('field_main', ($key == $_REQUEST['field_main']) ? 0 : 1);
            $fieldsObj->setVar('field_search', ($_REQUEST['field_search'][$key] == 1) ? 0 : 1);
            $fieldsObj->setVar('field_required', ($_REQUEST['field_required'][$key] == 1) ? 0 : 1);            
        }
	if ($fieldsObj->insert($fieldsObj, true)) {
		redirect_header('fields.php', 3, _AM_TDMCREATE_TOGGLE_SUCCESS);
	} else {
		redirect_header('fields.php', 3, _AM_TDMCREATE_TOGGLE_FAILED);
	}
}
include  __DIR__ . '/footer.php';
