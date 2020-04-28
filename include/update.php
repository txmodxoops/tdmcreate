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
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 * @param mixed      $module
 * @param null|mixed $prev_version
 */

/**
 * @param      $module
 * @param null $prev_version
 *
 * @return bool|null
 */
function xoops_module_update_tdmcreate(&$module, $prev_version = null)
{

    $ret = null;
    if ($prev_version < 191) {
        update_tdmcreate_v191($module);
    }

	tdmcreate_check_db($module);
	
	//check upload directory
	include_once __DIR__ . '/install.php';
    xoops_module_install_tdmcreate($module);
	
    $errors = $module->getErrors();
    if (!empty($errors)) {
        print_r($errors);
    }

    return $ret;

}

// irmtfan bug fix: solve templates duplicate issue
/**
 * @param $module
 *
 * @return bool
 */
function update_tdmcreate_v191(&$module)
{
    global $xoopsDB;
    $result = $xoopsDB->query(
        'SELECT t1.tpl_id FROM ' . $xoopsDB->prefix('tplfile') . ' t1, ' . $xoopsDB->prefix('tplfile') . ' t2 WHERE t1.tpl_refid = t2.tpl_refid AND t1.tpl_module = t2.tpl_module AND t1.tpl_tplset=t2.tpl_tplset AND t1.tpl_file = t2.tpl_file AND t1.tpl_type = t2.tpl_type AND t1.tpl_id > t2.tpl_id'
    );
    $tplids = [];
    while (list($tplid) = $xoopsDB->fetchRow($result)) {
        $tplids[] = $tplid;
    }
    if (count($tplids) > 0) {
        $tplfileHandler  = xoops_getHandler('tplfile');
        $duplicate_files = $tplfileHandler->getObjects(
            new \Criteria('tpl_id', '(' . implode(',', $tplids) . ')', 'IN')
        );

        if (count($duplicate_files) > 0) {
            foreach (array_keys($duplicate_files) as $i) {
                $tplfileHandler->delete($duplicate_files[$i]);
            }
        }
    }
    $sql = 'SHOW INDEX FROM ' . $xoopsDB->prefix('tplfile') . " WHERE KEY_NAME = 'tpl_refid_module_set_file_type'";
    if (!$result = $xoopsDB->queryF($sql)) {
        xoops_error($xoopsDB->error() . '<br />' . $sql);

        return false;
    }
    $ret = [];
    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        $ret[] = $myrow;
    }
    if (!empty($ret)) {
        $module->setErrors(
            "'tpl_refid_module_set_file_type' unique index is exist. Note: check 'tplfile' table to be sure this index is UNIQUE because XOOPS CORE need it."
        );

        return true;
    }
    $sql = 'ALTER TABLE ' . $xoopsDB->prefix('tplfile') . ' ADD UNIQUE tpl_refid_module_set_file_type ( tpl_refid, tpl_module, tpl_tplset, tpl_file, tpl_type )';
    if (!$result = $xoopsDB->queryF($sql)) {
        xoops_error($xoopsDB->error() . '<br />' . $sql);
        $module->setErrors(
            "'tpl_refid_module_set_file_type' unique index is not added to 'tplfile' table. Warning: do not use XOOPS until you add this unique index."
        );

        return false;
    }

    return true;
}
// irmtfan bug fix: solve templates duplicate issue

/**
 * function to add code for db checking
 * @param $module
 *
 * @return bool
 */
function tdmcreate_check_db($module)
{
    $ret = true;
	//insert here code for database check
    global $xoopsDB;

    // new form field SelectStatus
    $fname  = 'SelectStatus';
    $fid    = 16;
    $fvalue = 'XoopsFormSelectStatus';
    $result = $xoopsDB->query(
        'SELECT * FROM ' . $xoopsDB->prefix('tdmcreate_fieldelements') . " as fe WHERE fe.fieldelement_name = '{$fname}'"
    );
    $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
    if ($num_rows == 0) {
        $result = $xoopsDB->query(
            'SELECT * FROM ' . $xoopsDB->prefix('tdmcreate_fieldelements') . " as fe WHERE fe.fieldelement_id ={$fid}"
        );
        $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
        if ($num_rows > 0) {
            list($fe_id, $fe_mid, $fe_tid, $fe_name, $fe_value) = $xoopsDB->fetchRow($result);
            //add existing element at end of table
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('tdmcreate_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`) VALUES (NULL, '{$fe_mid}', '{$fe_tid}', '{$fe_name}', '{$fe_value}')";
            $result = $xoopsDB->query($sql);
            // update table fields to new id of previous 16
            $newId = $xoopsDB->getInsertId();
            $sql = 'UPDATE `' . $xoopsDB->prefix('tdmcreate_fields') . "` SET `field_element` = '{$newId}' WHERE `" . $xoopsDB->prefix('tdmcreate_fields') . "`.`field_element` = '{$fid}';";
            $result = $xoopsDB->query($sql);
            // update 16 to new element
            $sql = 'UPDATE `' . $xoopsDB->prefix('tdmcreate_fieldelements') . "` SET `fieldelement_mid` = '0', `fieldelement_tid` = '0', `fieldelement_name` = '{$fname}', `fieldelement_value` = '{$fvalue}' WHERE `fieldelement_id` = {$fid};";
            $result = $xoopsDB->query($sql);
        } else {
            //add missing element
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('tdmcreate_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`) VALUES (NULL, '0', '0', '{$fname}', '{$fvalue}')";
            $result = $xoopsDB->query($sql);
        }
    }

    // new form field Password
    $fname  = 'Password';
    $fid    = 17;
    $fvalue = 'XoopsFormPassword';
    $result = $xoopsDB->query(
        'SELECT * FROM ' . $xoopsDB->prefix('tdmcreate_fieldelements') . " as fe WHERE fe.fieldelement_name = '{$fname}'"
    );
    $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
    if ($num_rows == 0) {
        $result = $xoopsDB->query(
            'SELECT * FROM ' . $xoopsDB->prefix('tdmcreate_fieldelements') . " as fe WHERE fe.fieldelement_id ={$fid}"
        );
        $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
        if ($num_rows > 0) {
            list($fe_id, $fe_mid, $fe_tid, $fe_name, $fe_value) = $xoopsDB->fetchRow($result);
            //add existing element at end of table
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('tdmcreate_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`) VALUES (NULL, '{$fe_mid}', '{$fe_tid}', '{$fe_name}', '{$fe_value}')";
            $result = $xoopsDB->query($sql);
            // update table fields to new id of previous 16
            $newId = $xoopsDB->getInsertId();
            $sql = 'UPDATE `' . $xoopsDB->prefix('tdmcreate_fields') . "` SET `field_element` = '{$newId}' WHERE `" . $xoopsDB->prefix('tdmcreate_fields') . "`.`field_element` = '{$fid}';";
            $result = $xoopsDB->query($sql);
            // update 16 to new element
            $sql = 'UPDATE `' . $xoopsDB->prefix('tdmcreate_fieldelements') . "` SET `fieldelement_mid` = '0', `fieldelement_tid` = '0', `fieldelement_name` = '{$fname}', `fieldelement_value` = '{$fvalue}' WHERE `fieldelement_id` = {$fid};";
            $result = $xoopsDB->query($sql);
        } else {
            //add missing element
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('tdmcreate_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`) VALUES (NULL, '0', '0', '{$fname}', '{$fvalue}')";
            $result = $xoopsDB->query($sql);
        }
    }

    // new form field SelectCountry
    $fname  = 'SelectCountry';
    $fid    = 18;
    $fvalue = 'XoopsFormSelectCountry';
    $result = $xoopsDB->query(
        'SELECT * FROM ' . $xoopsDB->prefix('tdmcreate_fieldelements') . " as fe WHERE fe.fieldelement_name = '{$fname}'"
    );
    $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
    if ($num_rows == 0) {
        $result = $xoopsDB->query(
            'SELECT * FROM ' . $xoopsDB->prefix('tdmcreate_fieldelements') . " as fe WHERE fe.fieldelement_id ={$fid}"
        );
        $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
        if ($num_rows > 0) {
            list($fe_id, $fe_mid, $fe_tid, $fe_name, $fe_value) = $xoopsDB->fetchRow($result);
            //add existing element at end of table
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('tdmcreate_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`) VALUES (NULL, '{$fe_mid}', '{$fe_tid}', '{$fe_name}', '{$fe_value}')";
            $result = $xoopsDB->query($sql);
            // update table fields to new id of previous 16
            $newId = $xoopsDB->getInsertId();
            $sql = 'UPDATE `' . $xoopsDB->prefix('tdmcreate_fields') . "` SET `field_element` = '{$newId}' WHERE `" . $xoopsDB->prefix('tdmcreate_fields') . "`.`field_element` = '{$fid}';";
            $result = $xoopsDB->query($sql);
            // update 16 to new element
            $sql = 'UPDATE `' . $xoopsDB->prefix('tdmcreate_fieldelements') . "` SET `fieldelement_mid` = '0', `fieldelement_tid` = '0', `fieldelement_name` = '{$fname}', `fieldelement_value` = '{$fvalue}' WHERE `fieldelement_id` = {$fid};";
            $result = $xoopsDB->query($sql);
        } else {
            //add missing element
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('tdmcreate_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`) VALUES (NULL, '0', '0', '{$fname}', '{$fvalue}')";
            $result = $xoopsDB->query($sql);
        }
    }

    // new form field SelectLanguage
    $fname  = 'SelectLang';
    $fid    = 19;
    $fvalue = 'XoopsFormSelectLang';
    $result = $xoopsDB->query(
        'SELECT * FROM ' . $xoopsDB->prefix('tdmcreate_fieldelements') . " as fe WHERE fe.fieldelement_name = '{$fname}'"
    );
    $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
    if ($num_rows == 0) {
        $result = $xoopsDB->query(
            'SELECT * FROM ' . $xoopsDB->prefix('tdmcreate_fieldelements') . " as fe WHERE fe.fieldelement_id ={$fid}"
        );
        $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
        if ($num_rows > 0) {
            list($fe_id, $fe_mid, $fe_tid, $fe_name, $fe_value) = $xoopsDB->fetchRow($result);
            //add existing element at end of table
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('tdmcreate_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`) VALUES (NULL, '{$fe_mid}', '{$fe_tid}', '{$fe_name}', '{$fe_value}')";
            $result = $xoopsDB->query($sql);
            // update table fields to new id of previous 16
            $newId = $xoopsDB->getInsertId();
            $sql = 'UPDATE `' . $xoopsDB->prefix('tdmcreate_fields') . "` SET `field_element` = '{$newId}' WHERE `" . $xoopsDB->prefix('tdmcreate_fields') . "`.`field_element` = '{$fid}';";
            $result = $xoopsDB->query($sql);
            // update 16 to new element
            $sql = 'UPDATE `' . $xoopsDB->prefix('tdmcreate_fieldelements') . "` SET `fieldelement_mid` = '0', `fieldelement_tid` = '0', `fieldelement_name` = '{$fname}', `fieldelement_value` = '{$fvalue}' WHERE `fieldelement_id` = {$fid};";
            $result = $xoopsDB->query($sql);
        } else {
            //add missing element
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('tdmcreate_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`) VALUES (NULL, '0', '0', '{$fname}', '{$fvalue}')";
            $result = $xoopsDB->query($sql);
        }
    }

    // new form field Radio
    $fname  = 'Radio';
    $fid    = 20;
    $fvalue = 'XoopsFormRadio';
    $result = $xoopsDB->query(
        'SELECT * FROM ' . $xoopsDB->prefix('tdmcreate_fieldelements') . " as fe WHERE fe.fieldelement_name = '{$fname}'"
    );
    $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
    if ($num_rows == 0) {
        $result = $xoopsDB->query(
            'SELECT * FROM ' . $xoopsDB->prefix('tdmcreate_fieldelements') . " as fe WHERE fe.fieldelement_id ={$fid}"
        );
        $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
        if ($num_rows > 0) {
            list($fe_id, $fe_mid, $fe_tid, $fe_name, $fe_value) = $xoopsDB->fetchRow($result);
            //add existing element at end of table
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('tdmcreate_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`) VALUES (NULL, '{$fe_mid}', '{$fe_tid}', '{$fe_name}', '{$fe_value}')";
            $result = $xoopsDB->query($sql);
            // update table fields to new id of previous 16
            $newId = $xoopsDB->getInsertId();
            $sql = 'UPDATE `' . $xoopsDB->prefix('tdmcreate_fields') . "` SET `field_element` = '{$newId}' WHERE `" . $xoopsDB->prefix('tdmcreate_fields') . "`.`field_element` = '{$fid}';";
            $result = $xoopsDB->query($sql);
            // update 16 to new element
            $sql = 'UPDATE `' . $xoopsDB->prefix('tdmcreate_fieldelements') . "` SET `fieldelement_mid` = '0', `fieldelement_tid` = '0', `fieldelement_name` = '{$fname}', `fieldelement_value` = '{$fvalue}' WHERE `fieldelement_id` = {$fid};";
            $result = $xoopsDB->query($sql);
        } else {
            //add missing element
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('tdmcreate_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`) VALUES (NULL, '0', '0', '{$fname}', '{$fvalue}')";
            $result = $xoopsDB->query($sql);
        }
    }

    // new form field DateTime
    $fname  = 'DateTime';
    $fid    = 21;
    $fvalue = 'XoopsFormDateTime';
    $result = $xoopsDB->query(
        'SELECT * FROM ' . $xoopsDB->prefix('tdmcreate_fieldelements') . " as fe WHERE fe.fieldelement_name = '{$fname}'"
    );
    $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
    if ($num_rows == 0) {
        $result = $xoopsDB->query(
            'SELECT * FROM ' . $xoopsDB->prefix('tdmcreate_fieldelements') . " as fe WHERE fe.fieldelement_id ={$fid}"
        );
        $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
        if ($num_rows > 0) {
            list($fe_id, $fe_mid, $fe_tid, $fe_name, $fe_value) = $xoopsDB->fetchRow($result);
            //add existing element at end of table
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('tdmcreate_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`) VALUES (NULL, '{$fe_mid}', '{$fe_tid}', '{$fe_name}', '{$fe_value}')";
            $result = $xoopsDB->query($sql);
            // update table fields to new id of previous 16
            $newId = $xoopsDB->getInsertId();
            $sql = 'UPDATE `' . $xoopsDB->prefix('tdmcreate_fields') . "` SET `field_element` = '{$newId}' WHERE `" . $xoopsDB->prefix('tdmcreate_fields') . "`.`field_element` = '{$fid}';";
            $result = $xoopsDB->query($sql);
            // update 16 to new element
            $sql = 'UPDATE `' . $xoopsDB->prefix('tdmcreate_fieldelements') . "` SET `fieldelement_mid` = '0', `fieldelement_tid` = '0', `fieldelement_name` = '{$fname}', `fieldelement_value` = '{$fvalue}' WHERE `fieldelement_id` = {$fid};";
            $result = $xoopsDB->query($sql);
        } else {
            //add missing element
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('tdmcreate_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`) VALUES (NULL, '0', '0', '{$fname}', '{$fvalue}')";
            $result = $xoopsDB->query($sql);
        }
    }

    // new form field DateTime
    $fname  = 'SelectCombo';
    $fid    = 22;
    $fvalue = 'XoopsFormSelectCombo';
    $result = $xoopsDB->query(
        'SELECT * FROM ' . $xoopsDB->prefix('tdmcreate_fieldelements') . " as fe WHERE fe.fieldelement_name = '{$fname}'"
    );
    $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
    if ($num_rows == 0) {
        $result = $xoopsDB->query(
            'SELECT * FROM ' . $xoopsDB->prefix('tdmcreate_fieldelements') . " as fe WHERE fe.fieldelement_id ={$fid}"
        );
        $num_rows = $GLOBALS['xoopsDB']->getRowsNum($result);
        if ($num_rows > 0) {
            list($fe_id, $fe_mid, $fe_tid, $fe_name, $fe_value) = $xoopsDB->fetchRow($result);
            //add existing element at end of table
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('tdmcreate_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`) VALUES (NULL, '{$fe_mid}', '{$fe_tid}', '{$fe_name}', '{$fe_value}')";
            $result = $xoopsDB->query($sql);
            // update table fields to new id of previous 16
            $newId = $xoopsDB->getInsertId();
            $sql = 'UPDATE `' . $xoopsDB->prefix('tdmcreate_fields') . "` SET `field_element` = '{$newId}' WHERE `" . $xoopsDB->prefix('tdmcreate_fields') . "`.`field_element` = '{$fid}';";
            $result = $xoopsDB->query($sql);
            // update 16 to new element
            $sql = 'UPDATE `' . $xoopsDB->prefix('tdmcreate_fieldelements') . "` SET `fieldelement_mid` = '0', `fieldelement_tid` = '0', `fieldelement_name` = '{$fname}', `fieldelement_value` = '{$fvalue}' WHERE `fieldelement_id` = {$fid};";
            $result = $xoopsDB->query($sql);
        } else {
            //add missing element
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('tdmcreate_fieldelements') . "` (`fieldelement_id`, `fieldelement_mid`, `fieldelement_tid`, `fieldelement_name`, `fieldelement_value`) VALUES (NULL, '0', '0', '{$fname}', '{$fvalue}')";
            $result = $xoopsDB->query($sql);
        }
    }

    // update table 'tdmcreate_fieldelements'
    $table   = $GLOBALS['xoopsDB']->prefix('tdmcreate_fieldelements');
    $field   = 'fieldelement_sort';
    $check   = $GLOBALS['xoopsDB']->queryF('SHOW COLUMNS FROM `' . $table . "` LIKE '" . $field . "'");
    $numRows = $GLOBALS['xoopsDB']->getRowsNum($check);
    if (!$numRows) {
        $sql = "ALTER TABLE `$table` ADD `$field` INT(8) NOT NULL DEFAULT '0' AFTER `fieldelement_value`;";
        if (!$result = $GLOBALS['xoopsDB']->queryF($sql)) {
            xoops_error($GLOBALS['xoopsDB']->error() . '<br>' . $sql);
            $module->setErrors("Error when adding '$field' to table '$table'.");
            $ret = false;
        }
    }

    return $ret;
}
