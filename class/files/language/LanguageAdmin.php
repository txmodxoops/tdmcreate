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
 * @version         $Id: LanguageAdmin.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');
/**
 * Class LanguageAdmin
 */
class LanguageAdmin extends LanguageDefines
{
    /*
    *  @public function constructor
    *  @param null
    */
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->defines  = LanguageDefines::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return LanguageAdmin
     */
    public static function &getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /*
    *  @public function write
    *  @param string $module
    *  @param string $tables
    *  @param string $filename
    *  @param $filename
    */
    public function write($module, $table, $tables, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
		$this->setTables($tables);
        $this->setFileName($filename);
    }

    /*
    *  @public function getLanguageAdminIndex
    *  @param string $language
    *  @param string $tables
    *  @return string
    */
    public function getLanguageAdminIndex($language, $tables)
    {
        $ret = $this->defines->getAboveHeadDefines('Admin Index');
        $ret .= $this->defines->getDefine($language, 'STATISTICS', "Statistics");
        $ret .= $this->defines->getAboveDefines('There are');
        foreach (array_keys($tables) as $t) {
            $tableName    = $tables[$t]->getVar('table_name');
            $stuTableName = strtoupper($tableName);
            $stlTableName = strtolower($tableName);
            $ret .= $this->defines->getDefine($language, "THEREARE_{$stuTableName}", "There are <span class='bold'>%s</span> {$stlTableName} in the database");
        }

        return $ret;
    }

    /*
    *  @public function getLanguageAdminPages
    *  @param string $language
    *  @param string $tables
    *  @return string
    */
    public function getLanguageAdminPages($language, $tables)
    {
        $ret = $this->defines->getAboveHeadDefines('Admin Files');
        $ret .= $this->defines->getAboveDefines('There aren\'t');
        foreach (array_keys($tables) as $t) {
            $tableName    = $tables[$t]->getVar('table_name');
            $stuTableName = strtoupper($tableName);
            $stlTableName = strtolower($tableName);
            $ret .= $this->defines->getDefine($language, "THEREARENT_{$stuTableName}", "There aren't {$stlTableName}");
        }
        $ret .= $this->defines->getAboveDefines('Save/Delete');
        $ret .= $this->defines->getDefine($language, 'FORM_OK', "Successfully saved");
        $ret .= $this->defines->getDefine($language, 'FORM_DELETE_OK', "Successfully deleted");
        $ret .= $this->defines->getDefine($language, 'FORM_SURE_DELETE', "Are you sure to delete: <b><span style='color : Red;'>%s </span></b>");
        $ret .= $this->defines->getDefine($language, 'FORM_SURE_RENEW', "Are you sure to update: <b><span style='color : Red;'>%s </span></b>");
        $ret .= $this->defines->getAboveDefines('Buttons');
        //
        foreach (array_keys($tables) as $t) {
            $tableName         = $tables[$t]->getVar('table_name');
            $tableSoleName     = $tables[$t]->getVar('table_solename');
            $stuTableSoleName  = strtoupper($tableSoleName);
            $ucfTableSoleName  = ucfirst($tableSoleName);
            $ret .= $this->defines->getDefine($language, "ADD_{$stuTableSoleName}", "Add New {$ucfTableSoleName}");
        }
        $ret .= $this->defines->getAboveDefines('Lists');
        //
        foreach (array_keys($tables) as $t) {
            $tableName      = $tables[$t]->getVar('table_name');
            $tableFieldname = $tables[$t]->getVar('table_fieldname');
            $stuTableName   = strtoupper($tableName);
            $ucfTableName   = ucfirst($tableName);
            $ret .= $this->defines->getDefine($language, "{$stuTableName}_LIST", "List of {$ucfTableName}");
        }

        return $ret;
    }

    /*
    *  @public function getLanguageAdminClass
    *  @param string $language
    *  @param string $tables
    *  @return string
    */
    public function getLanguageAdminClass($language, $tables)
    {
        $ret = $this->defines->getAboveHeadDefines('Admin Classes');
        //
        foreach (array_keys($tables) as $t) {
            $tableId           = $tables[$t]->getVar('table_id');
			$tableMid          = $tables[$t]->getVar('table_mid');
            $tableName         = $tables[$t]->getVar('table_name');
			$tableSoleName     = $tables[$t]->getVar('table_solename');
            $ucfTableSoleName  = ucfirst($tableSoleName);
            //
			$fields = $this->getTableFields($tableMid, $tableId);
			foreach (array_keys($fields) as $f) {
				$fieldInForm = $fields[$f]->getVar('field_inform');
            }
			if(1 == $fieldInForm) {
				$ret .= $this->defines->getAboveDefines("{$ucfTableSoleName} add/edit");
				$ret .= $this->defines->getDefine($language, "{$tableSoleName}_ADD", "Add {$ucfTableSoleName}");
				$ret .= $this->defines->getDefine($language, "{$tableSoleName}_EDIT", "Edit {$ucfTableSoleName}");					
			}
			$ret .= $this->defines->getAboveDefines("Elements of {$ucfTableSoleName}");
            //
            foreach (array_keys($fields) as $f) {
                $fieldName    = $fields[$f]->getVar('field_name');
                $fieldElement = $fields[$f]->getVar('field_element');
                $stuFieldName = strtoupper($fieldName);
                //
                $rpFieldName = $this->tdmcfile->getRightString($fieldName);
				if ($fieldElement > 15) {
					$fieldElements      = $this->tdmcreate->getHandler('fieldelements')->get($fieldElement);
					$fieldElementTid    = $fieldElements->getVar('fieldelement_tid');
					$fieldElementName   = $fieldElements->getVar('fieldelement_name');
					$fieldNameDesc = substr($fieldElementName, strrpos($fieldElementName, ':'), strlen($fieldElementName));
					$fieldNameDesc = str_replace(': ', '', $fieldNameDesc);
				} else {				
					$fieldNameDesc = ucfirst($rpFieldName);
				}
                //
                $ret .= $this->defines->getDefine($language, $tableSoleName . '_' . $rpFieldName, $fieldNameDesc);
				$stuTableName = strtoupper($tableName);
                //
                switch ($fieldElement) {
                    case 10:
                        $ret .= $this->defines->getDefine($language, "FORM_IMAGE_LIST_{$stuTableName}", "{$fieldNameDesc} in frameworks images");
                        break;
					case 12:
                        $ret .= $this->defines->getDefine($language, "FORM_URL_{$stuTableName}", "{$fieldNameDesc} in text url");
						$ret .= $this->defines->getDefine($language, "FORM_URL_UPLOAD", "{$fieldNameDesc} in uploads files");
                        break;
                    case 13:
                        $ret .= $this->defines->getDefine($language, "FORM_UPLOAD_IMAGE_{$stuTableName}", "{$fieldNameDesc} in uploads images");
                        break;
                    case 14:
                        $ret .= $this->defines->getDefine($language, "FORM_UPLOAD_FILE_{$stuTableName}", "{$fieldNameDesc} in uploads files");
                        break;
                }
            }
        }
        $ret .= $this->defines->getAboveDefines('General');
        $ret .= $this->defines->getDefine($language, 'FORM_UPLOAD', "Upload file");
        $ret .= $this->defines->getDefine($language, 'FORM_IMAGE_PATH', "Files in %s ");
        $ret .= $this->defines->getDefine($language, 'FORM_ACTION', "Action");
        $ret .= $this->defines->getDefine($language, 'FORM_EDIT', "Modification");
        $ret .= $this->defines->getDefine($language, 'FORM_DELETE', "Clear");

        return $ret;
    }

    /*
    *  @public function getLanguageAdminPermissions
    *  @param string $language
    *  @return string
    */
    public function getLanguageAdminPermissions($language)
    {
        $ret = $this->defines->getAboveHeadDefines('Admin Permissions');
        $ret .= $this->defines->getAboveDefines('Permissions');
        $ret .= $this->defines->getDefine($language, 'PERMISSIONS_APPROVE', "Permissions to approve");
        $ret .= $this->defines->getDefine($language, 'PERMISSIONS_SUBMIT', "Permissions to submit");
        $ret .= $this->defines->getDefine($language, 'PERMISSIONS_VIEW', "Permissions to view");
        $ret .= $this->defines->getDefine($language, 'GLOBAL', "Permissions global");
        $ret .= $this->defines->getDefine($language, 'GLOBAL_DESC', "Permissions global");
        $ret .= $this->defines->getDefine($language, 'GLOBAL_4', "Permissions global");
        $ret .= $this->defines->getDefine($language, 'GLOBAL_8', "Permissions global");
        $ret .= $this->defines->getDefine($language, 'GLOBAL_16', "Permissions global");
        $ret .= $this->defines->getDefine($language, 'APPROVE', "Permissions to approve");
        $ret .= $this->defines->getDefine($language, 'APPROVE_DESC', "Permissions to approve");
        $ret .= $this->defines->getDefine($language, 'SUBMIT', "Permissions to submit");
        $ret .= $this->defines->getDefine($language, 'SUBMIT_DESC', "Permissions to submit");
        $ret .= $this->defines->getDefine($language, 'VIEW', "Permissions to view");
        $ret .= $this->defines->getDefine($language, 'VIEW_DESC', "Permissions to view");
        $ret .= $this->defines->getDefine($language, 'NOPERMSSET', "No permission set");

        return $ret;
    }

    /*
    *  @public function getLanguageAdminFoot
    *  @param string $language
    *  @return string
    */
    public function getLanguageAdminFoot($language)
    {
        $ret = $this->defines->getAboveHeadDefines('Admin Others');
        $ret .= $this->defines->getDefine($language, 'MAINTAINEDBY', " is maintained by ");
        $ret .= $this->defines->getBelowDefines('End');

        return $ret;
    }

    /*
    *  @public function render
    *  @param null
    */
    /**
     * @return bool|string
     */
    public function render()
    {
        $module        = $this->getModule();
		$tables        = $this->getTables();
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language      = $this->getLanguage($moduleDirname, 'AM');
        $content       = $this->getHeaderFilesComments($module, $filename);
        if (is_array($tables)) {
            $content .= $this->getLanguageAdminIndex($language, $tables);
            $content .= $this->getLanguageAdminPages($language, $tables);
            $content .= $this->getLanguageAdminClass($language, $tables);
            $content .= $this->getLanguageAdminPermissions($language);
        }
        $content .= $this->getLanguageAdminFoot($language);
        //
        $this->tdmcfile->create($moduleDirname, 'language/english', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);
        return $this->tdmcfile->renderFile();
    }
}