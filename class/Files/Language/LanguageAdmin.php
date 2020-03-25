<?php

namespace XoopsModules\Tdmcreate\Files\Language;

use XoopsModules\Tdmcreate;
use XoopsModules\Tdmcreate\Files;

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
 */

/**
 * Class LanguageAdmin.
 */
class LanguageAdmin extends Files\CreateFile
{
    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
        $this->defines = LanguageDefines::getInstance();
    }

    /**
     * @static function getInstance
     * @param null
     * @return LanguageAdmin
     */
    public static function getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * @public function write
     * @param string $module
     * @param        $table
     * @param string $tables
     * @param string $filename
     */
    public function write($module, $table, $tables, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setTables($tables);
        $this->setFileName($filename);
    }

    /**
     * @public function getLanguageAdminIndex
     * @param string $language
     * @param string $tables
     * @return string
     */
    public function getLanguageAdminIndex($language, $tables)
    {
        $ret = $this->defines->getAboveHeadDefines('Admin Index');
        $ret .= $this->defines->getDefine($language, 'STATISTICS', 'Statistics');
        $ret .= $this->defines->getAboveDefines('There are');
        foreach (array_keys($tables) as $t) {
            $tableName    = $tables[$t]->getVar('table_name');
            $stuTableName = mb_strtoupper($tableName);
            $stlTableName = mb_strtolower($tableName);
            $ret          .= $this->defines->getDefine($language, "THEREARE_{$stuTableName}", "There are <span class='bold'>%s</span> {$stlTableName} in the database", true);
        }

        return $ret;
    }

    /**
     * @public function getLanguageAdminPages
     * @param string $language
     * @param string $tables
     * @return string
     */
    public function getLanguageAdminPages($language, $tables)
    {
        $ret = $this->defines->getAboveHeadDefines('Admin Files');
        $ret .= $this->defines->getAboveDefines('There aren\'t');
        foreach (array_keys($tables) as $t) {
            $tableName    = $tables[$t]->getVar('table_name');
            $stuTableName = mb_strtoupper($tableName);
            $stlTableName = mb_strtolower($tableName);
            $ret          .= $this->defines->getDefine($language, "THEREARENT_{$stuTableName}", "There aren't {$stlTableName}", true);
        }
        $ret .= $this->defines->getAboveDefines('Save/Delete');
        $ret .= $this->defines->getDefine($language, 'FORM_OK', 'Successfully saved');
        $ret .= $this->defines->getDefine($language, 'FORM_DELETE_OK', 'Successfully deleted');
        $ret .= $this->defines->getDefine($language, 'FORM_SURE_DELETE', "Are you sure to delete: <b><span style='color : Red;'>%s </span></b>", true);
        $ret .= $this->defines->getDefine($language, 'FORM_SURE_RENEW', "Are you sure to update: <b><span style='color : Red;'>%s </span></b>", true);
        $ret .= $this->defines->getAboveDefines('Buttons');

        foreach (array_keys($tables) as $t) {
            $tableName        = $tables[$t]->getVar('table_name');
            $tableSoleName    = $tables[$t]->getVar('table_solename');
            $stuTableSoleName = mb_strtoupper($tableSoleName);
            $ucfTableSoleName = ucfirst($tableSoleName);
            $ret              .= $this->defines->getDefine($language, "ADD_{$stuTableSoleName}", "Add New {$ucfTableSoleName}");
        }
        $ret .= $this->defines->getAboveDefines('Lists');

        foreach (array_keys($tables) as $t) {
            $tableName    = $tables[$t]->getVar('table_name');
            $stuTableName = mb_strtoupper($tableName);
            $ucfTableName = ucfirst($tableName);
            $ret          .= $this->defines->getDefine($language, "{$stuTableName}_LIST", "List of {$ucfTableName}");
        }

        return $ret;
    }

    /**
     * @public function getLanguageAdminClass
     * @param string $language
     * @param string $tables
     * @return string
     */
    public function getLanguageAdminClass($language, $tables, $moduleDirname)
    {
        $ret = $this->defines->getAboveHeadDefines('Admin Classes');

        foreach (array_keys($tables) as $t) {
            $tableId          = $tables[$t]->getVar('table_id');
            $tableMid         = $tables[$t]->getVar('table_mid');
            $tableName        = $tables[$t]->getVar('table_name');
            $tableSoleName    = $tables[$t]->getVar('table_solename');
            $ucfTableSoleName = ucfirst($tableSoleName);

            $fields      = $this->getTableFields($tableMid, $tableId);
            $fieldInForm = 0;
            foreach (array_keys($fields) as $f) {
                if ($fieldInForm < $fields[$f]->getVar('field_inform')) {
                    $fieldInForm = $fields[$f]->getVar('field_inform');
                }
            }
            if (1 == $fieldInForm) {
                $ret .= $this->defines->getAboveDefines("{$ucfTableSoleName} add/edit");
                $ret .= $this->defines->getDefine($language, "{$tableSoleName}_ADD", "Add {$ucfTableSoleName}");
                $ret .= $this->defines->getDefine($language, "{$tableSoleName}_EDIT", "Edit {$ucfTableSoleName}");
            }
            $ret .= $this->defines->getAboveDefines("Elements of {$ucfTableSoleName}");

            foreach (array_keys($fields) as $f) {
                $fieldName    = $fields[$f]->getVar('field_name');
                $fieldElement = $fields[$f]->getVar('field_element');
                $stuFieldName = mb_strtoupper($fieldName);

                $rpFieldName = $this->getRightString($fieldName);
                if ($fieldElement > 15) {
                    $fieldElements    = Tdmcreate\Helper::getInstance()->getHandler('fieldelements')->get($fieldElement);
                    $fieldElementTid  = $fieldElements->getVar('fieldelement_tid');
                    $fieldElementName = $fieldElements->getVar('fieldelement_name');
                    $fieldNameDesc    = mb_substr($fieldElementName, mb_strrpos($fieldElementName, ':'), mb_strlen($fieldElementName));
                    $fieldNameDesc    = str_replace(': ', '', $fieldNameDesc);
                } else {
                    $fieldNameDesc = false !== mb_strpos($rpFieldName, '_') ? str_replace('_', ' ', ucfirst($rpFieldName)) : ucfirst($rpFieldName);
                }

                $ret          .= $this->defines->getDefine($language, $tableSoleName . '_' . $rpFieldName, $fieldNameDesc);
                $stuTableName = mb_strtoupper($tableName);

                switch ($fieldElement) {
                    case 10:
                        $ret .= $this->defines->getDefine($language, "FORM_IMAGE_LIST_{$stuTableName}", "{$fieldNameDesc} in frameworks images");
                        break;
                    case 12:
                        $ret .= $this->defines->getDefine($language, "FORM_URL_{$stuTableName}", "{$fieldNameDesc} in text url");
                        $ret .= $this->defines->getDefine($language, 'FORM_URL_UPLOAD', "{$fieldNameDesc} in uploads files");
                        break;
                    case 13:
                        $ret .= $this->defines->getDefine($language, "FORM_UPLOAD_IMAGE_{$stuTableName}", "{$fieldNameDesc} in ./uploads/{$moduleDirname}/images/{$tableName}/ :");
                        break;
                    case 14:
                        $ret .= $this->defines->getDefine($language, "FORM_UPLOAD_FILE_{$stuTableName}", "{$fieldNameDesc} in ./uploads/{$moduleDirname}/files/{$tableName}/ :");
                        break;
                }
            }
        }
        $ret .= $this->defines->getAboveDefines('General');
        $ret .= $this->defines->getDefine($language, 'FORM_UPLOAD', 'Upload file');
        $ret .= $this->defines->getDefine($language, 'FORM_UPLOAD_NEW', 'Upload new file: ');
        $ret .= $this->defines->getDefine($language, 'FORM_IMAGE_PATH', 'Files in %s :');
        $ret .= $this->defines->getDefine($language, 'FORM_ACTION', 'Action');
        $ret .= $this->defines->getDefine($language, 'FORM_EDIT', 'Modification');
        $ret .= $this->defines->getDefine($language, 'FORM_DELETE', 'Clear');

        return $ret;
    }

    /**
     * @public function getLanguageAdminPermissions
     * @param string $language
     * @return string
     */
    public function getLanguageAdminPermissions($language)
    {
        $ret = $this->defines->getAboveHeadDefines('Admin Permissions');
        $ret .= $this->defines->getAboveDefines('Permissions');
        $ret .= $this->defines->getDefine($language, 'PERMISSIONS_GLOBAL', 'Permissions global');
        $ret .= $this->defines->getDefine($language, 'PERMISSIONS_GLOBAL_DESC', 'Permissions global to check type of.');
        $ret .= $this->defines->getDefine($language, 'PERMISSIONS_GLOBAL_4', 'Permissions global to approve');
        $ret .= $this->defines->getDefine($language, 'PERMISSIONS_GLOBAL_8', 'Permissions global to submit');
        $ret .= $this->defines->getDefine($language, 'PERMISSIONS_GLOBAL_16', 'Permissions global to view');
        $ret .= $this->defines->getDefine($language, 'PERMISSIONS_APPROVE', 'Permissions to approve');
        $ret .= $this->defines->getDefine($language, 'PERMISSIONS_APPROVE_DESC', 'Permissions to approve');
        $ret .= $this->defines->getDefine($language, 'PERMISSIONS_SUBMIT', 'Permissions to submit');
        $ret .= $this->defines->getDefine($language, 'PERMISSIONS_SUBMIT_DESC', 'Permissions to submit');
        $ret .= $this->defines->getDefine($language, 'PERMISSIONS_VIEW', 'Permissions to view');
        $ret .= $this->defines->getDefine($language, 'PERMISSIONS_VIEW_DESC', 'Permissions to view');
        $ret .= $this->defines->getDefine($language, 'NO_PERMISSIONS_SET', 'No permission set');

        return $ret;
    }

    /**
     * @public function getLanguageAdminFoot
     * @param string $language
     * @return string
     */
    public function getLanguageAdminFoot($language)
    {
        $ret = $this->defines->getAboveHeadDefines('Admin Others');
        $ret .= $this->defines->getDefine($language, 'MAINTAINEDBY', ' is maintained by ');
        $ret .= $this->defines->getBelowDefines('End');
        $ret .= $this->defines->getBlankLine();

        return $ret;
    }

    /**
     * @public function render
     * @param null
     * @return bool|string
     */
    public function render()
    {
        $module = $this->getModule();
        $tables = $this->getTableTables($module->getVar('mod_id'));
        foreach (array_keys($tables) as $t) {
            $tablePermissions[] = $tables[$t]->getVar('table_permissions');
        }
        $tables        = $this->getTables();
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language      = $this->getLanguage($moduleDirname, 'AM');
        $content       = $this->getHeaderFilesComments($module, $filename);
        if (is_array($tables)) {
            $content .= $this->getLanguageAdminIndex($language, $tables);
            $content .= $this->getLanguageAdminPages($language, $tables);
            $content .= $this->getLanguageAdminClass($language, $tables, $moduleDirname);
        }
        if (in_array(1, $tablePermissions)) {
            $content .= $this->getLanguageAdminPermissions($language);
        }
        $content .= $this->getLanguageAdminFoot($language);

        $this->create($moduleDirname, 'language/' . $GLOBALS['xoopsConfig']['language'], $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
