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
 * @version         $Id: SqlFile.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class SqlFile.
 */
class SqlFile extends TDMCreateFile
{
    /*
    * @var string
    */
    private $tdmcreate = null;

    /*
    * @var string
    */
    private $tdmcfile = null;

    /*
    *  @public function constructor
    *  @param null
    
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
        $this->tdmcreate = TDMCreateHelper::getInstance();
        $this->tdmcfile = TDMCreateFile::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return SqlFile
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
    *  @param $module
    *  @param $table
    *  @param $tables
    *  @param $filename
    */
    /**
     * @param $module
     * @param $tables
     * @param $filename
     */
    public function write($module, $tables, $filename)
    {
        $this->setModule($module);
        $this->setTables($tables);
        $this->setFileName($filename);
    }

    /*
    *  @private function getHeaderSqlComments
    *  @param $moduleName
    */
    /**
     * @param $moduleName
     *
     * @return string
     */
    private function getHeaderSqlComments($moduleName)
    {
        $date = date('D M d, Y');
        $time = date('G:i');
        $server_name = $_SERVER['SERVER_NAME'];
        $server_version = mysql_get_server_info();
        $php_version = phpversion();
        // Header Sql Comments
        $ret = $this->getSimpleString("# SQL Dump for {$moduleName} module");
        $ret .= $this->getSimpleString('# PhpMyAdmin Version: 4.0.4');
        $ret .= $this->getSimpleString('# http://www.phpmyadmin.net');
        $ret .= $this->getSimpleString('#');
        $ret .= $this->getSimpleString("# Host: {$server_name}");
        $ret .= $this->getSimpleString("# Generated on: {$date} to {$time}");
        $ret .= $this->getSimpleString("# Server version: {$server_version}");
        $ret .= $this->getSimpleString("# PHP Version: {$php_version}\n");

        return $ret;
    }

    /*
    *  @private function getHeadDatabaseTable
    *  @param $moduleDirname
    *  @param $tableName
    *  @param integer $fieldsNumb
    *
    *  Unused IF NOT EXISTS
    *  @return string
    */
    private function getHeadDatabaseTable($moduleDirname, $tableName, $fieldsNumb)
    {
        $ret = $this->getSimpleString('#');
        $ret .= $this->getSimpleString("# Structure table for `{$moduleDirname}_{$tableName}` {$fieldsNumb}");
        $ret .= $this->getSimpleString('#');
        $ret .= $this->getSimpleString("\nCREATE TABLE `{$moduleDirname}_{$tableName}` (");

        return $ret;
    }

    /*
    *  @private function getDatabaseTables
    *  @param $moduleDirname
    *  @return null|string
    */
    private function getDatabaseTables($moduleDirname)
    {
        $ret = null;
        $tables = $this->getTables();
        foreach (array_keys($tables) as $t) {
            $tableId = $tables[$t]->getVar('table_id');
            $tableMid = $tables[$t]->getVar('table_mid');
            $tableName = $tables[$t]->getVar('table_name');
            $tableAutoincrement = $tables[$t]->getVar('table_autoincrement');
            $fieldsNumb = $tables[$t]->getVar('table_nbfields');
            $ret .= $this->getDatabaseFields($moduleDirname, $tableMid, $tableId, $tableName, $tableAutoincrement, $fieldsNumb);
        }

        return $ret;
    }

    /*
    *  @private function getDatabaseFields
    *  @param $moduleDirname
    *  @param $tableName
    *  @param $tableAutoincrement
    *  @param $fieldsNumb
    *  @return null|string
    */
    private function getDatabaseFields($moduleDirname, $tableMid, $tableId, $tableName, $tableAutoincrement, $fieldsNumb)
    {
        $ret = null;
        $j = 0;
        $comma = array();
        $row = array();
        $type = '';
        $fields = $this->getTableFields($tableMid, $tableId, 'field_id ASC, field_name');
        foreach (array_keys($fields) as $f) {
            // Creation of database table
            $ret = $this->getHeadDatabaseTable($moduleDirname, $tableName, $fieldsNumb);
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldType = $fields[$f]->getVar('field_type');
            $fieldValue = $fields[$f]->getVar('field_value');
            $fieldAttribute = $fields[$f]->getVar('field_attribute');
            $fieldNull = $fields[$f]->getVar('field_null');
            $fieldDefault = $fields[$f]->getVar('field_default');
            $fieldKey = $fields[$f]->getVar('field_key');
            if ($fieldType > 1) {
                $fType = $this->tdmcreate->getHandler('fieldtype')->get($fieldType);
                $fieldTypeName = $fType->getVar('fieldtype_name');
            } else {
                $fieldType = null;
            }
            if ($fieldAttribute > 1) {
                $fAttribute = $this->tdmcreate->getHandler('fieldattributes')->get($fieldAttribute);
                $fieldAttribute = $fAttribute->getVar('fieldattribute_name');
            } else {
                $fieldAttribute = null;
            }
            if ($fieldNull > 1) {
                $fNull = $this->tdmcreate->getHandler('fieldnull')->get($fieldNull);
                $fieldNull = $fNull->getVar('fieldnull_name');
            } else {
                $fieldNull = null;
            }
            if (!empty($fieldName)) {
                switch ($fieldType) {
                    case 2:
                    case 3:
                    case 4:
                    case 5:
                        $type = $fieldTypeName.'('.$fieldValue.')';
                        if (empty($fieldDefault)) {
                            $default = "DEFAULT '0'";
                        } else {
                            $default = "DEFAULT '{$fieldDefault}'";
                        }
                        break;
                    case 6:
                    case 7:
                    case 8:
                        $type = $fieldTypeName.'('.$fieldValue.')';
                        if (empty($fieldDefault)) {
                            $default = "DEFAULT '0.00'"; // From MySQL 5.7 Manual
                        } else {
                            $default = "DEFAULT '{$fieldDefault}'";
                        }
                        break;
                    case 9:
                    case 10:
                        $type = $fieldTypeName.'('.$fieldValue.')'; // Used with comma separator
                        break;
                    case 11:
                        $type = $fieldTypeName.'('.$fieldValue.')';
                        if (empty($fieldDefault)) {
                            $default = "DEFAULT 'my@email.com'";
                        } else {
                            $default = "DEFAULT '{$fieldDefault}'";
                        }
                        break;
                    case 12:
                        $type = $fieldTypeName.'('.$fieldValue.')';
                        if (empty($fieldDefault)) {
                            $default = "DEFAULT 'http:\\'";
                        } else {
                            $default = "DEFAULT '{$fieldDefault}'";
                        }
                        break;
                    case 13:
                    case 14:
                        $type = $fieldTypeName.'('.$fieldValue.')';
                        $default = "DEFAULT '{$fieldDefault}'";
                        break;
                    case 15:
                    case 16:
                    case 17:
                    case 18:
                        $type = $fieldTypeName;
                        $default = null;
                        break;
                    case 19:
                    case 20:
                    case 21:
                    case 22:
                        $type = $fieldTypeName.'('.$fieldValue.')';
                        $default = "DEFAULT '{$fieldDefault}'";
                        break;
                    case 23:
                        $type = $fieldTypeName;
                        if (empty($fieldDefault)) {
                            $default = "DEFAULT '1970'"; // From MySQL 5.7 Manual
                        } else {
                            $default = "DEFAULT '{$fieldDefault}'";
                        }
                        break;
                    default:
                        $type = $fieldTypeName.'('.$fieldValue.')';
                        $default = "DEFAULT '{$fieldDefault}'";
                        break;
                }
                if ((0 == $f) && (1 == $tableAutoincrement)) {
                    $row[] = $this->getFieldRow($fieldName, $type, $fieldAttribute, $fieldNull, null, 'AUTO_INCREMENT');
                    $comma[$j] = $this->getKey(2, $fieldName);
                    ++$j;
                } elseif ((0 == $f) && (0 == $tableAutoincrement)) {
                    $row[] = $this->getFieldRow($fieldName, $type, $fieldAttribute, $fieldNull, $default);
                    $comma[$j] = $this->getKey(2, $fieldName);
                    ++$j;
                } else {
                    if (3 == $fieldKey || 4 == $fieldKey || 5 == $fieldKey || 6 == $fieldKey) {
                        switch ($fieldKey) {
                            case 3:
                                $row[] = $this->getFieldRow($fieldName, $type, $fieldAttribute, $fieldNull, $default);
                                $comma[$j] = $this->getKey(3, $fieldName);
                                ++$j;
                                break;
                            case 4:
                                $row[] = $this->getFieldRow($fieldName, $type, $fieldAttribute, $fieldNull, $default);
                                $comma[$j] = $this->getKey(4, $fieldName);
                                ++$j;
                                break;
                            case 5:
                                $row[] = $this->getFieldRow($fieldName, $type, $fieldAttribute, $fieldNull, $default);
                                $comma[$j] = $this->getKey(5, $fieldName);
                                ++$j;
                                break;
                            case 6:
                                $row[] = $this->getFieldRow($fieldName, $type, $fieldAttribute, $fieldNull, $default);
                                $comma[$j] = $this->getKey(6, $fieldName);
                                ++$j;
                                break;
                        }
                    } else {
                        $row[] = $this->getFieldRow($fieldName, $type, $fieldAttribute, $fieldNull, $default);
                    }
                }
            }
        }
        // ================= COMMA ================= //
        for ($i = 0; $i < $j; ++$i) {
            if ($i != $j - 1) {
                $row[] = $comma[$i].',';
            } else {
                $row[] = $comma[$i];
            }
        }
        // ================= COMMA CICLE ================= //
        $ret .= implode("\n", $row);
        unset($j);
        $ret .= $this->getFootDatabaseTable();

        return $ret;
    }

    /*
    *  @private function getFootDatabaseTable
    *  @param null
    */
    /**
     * @return string
     */
    private function getFootDatabaseTable()
    {
        return "\n) ENGINE=InnoDB;\n\n";
    }

    /*
    *  @private function getFieldRow
    *  @param $fieldName
    *  @param $fieldTypeValue
    *  @param $fieldAttribute
    *  @param $fieldNull
    *  @param $fieldDefault
    *  @param $autoincrement
    *  @return string
    */
    private function getFieldRow($fieldName, $fieldTypeValue, $fieldAttribute = null, $fieldNull = null, $fieldDefault = null, $autoincrement = null)
    {
        $retAutoincrement = "  `{$fieldName}` {$fieldTypeValue} {$fieldAttribute} {$fieldNull} {$autoincrement},";
        $retFieldAttribute = "  `{$fieldName}` {$fieldTypeValue} {$fieldAttribute} {$fieldNull} {$fieldDefault},";
        $fieldDefault = "  `{$fieldName}` {$fieldTypeValue} {$fieldNull} {$fieldDefault},";
        $retShort = "  `{$fieldName}` {$fieldTypeValue},";

        $ret = $retShort;
        if ($autoincrement != null) {
            $ret = $retAutoincrement;
        } elseif ($fieldAttribute != null) {
            $ret = $retFieldAttribute;
        } elseif ($fieldAttribute == null) {
            $ret = $fieldDefault;
        }

        return $ret;
    }

    /*
    *  @private function getKey
    *  @return string
    */
    private function getKey($key, $fieldName)
    {
        switch ($key) {
            case 2: // PRIMARY KEY
                $ret = "  PRIMARY KEY (`{$fieldName}`)";
                break;
            case 3: // UNIQUE KEY
                $ret = "  UNIQUE KEY `{$fieldName}` (`{$fieldName}`)";
                break;
            case 4: // KEY
                $ret = "  KEY `{$fieldName}` (`{$fieldName}`)";
                break;
            case 5: // INDEX
                $ret = "  INDEX (`{$fieldName}`)";
                break;
            case 6: // FULLTEXT KEY
                $ret = "  FULLTEXT KEY `{$fieldName}` (`{$fieldName}`)";
                break;
        }

        return $ret;
    }

    /*
    *  @private function getComma
    *  @param $row
    *  @param $comma
    *  @return string
    */
    private function getComma($row, $comma = null)
    {
        return "\t\t\t{$row}{$comma}";
    }

    /*
    *  @private function getCommaCicle
    *  @param $comma
    *  @param $index
    *  @return string
    */
    private function getCommaCicle($comma, $index)
    {
        // Comma issue
        for ($i = 1; $i <= $index; ++$i) {
            if ($i != $index - 1) {
                $ret = $this->getComma(isset($comma[$i]), ',')."\n";
            } else {
                $ret = $this->getComma(isset($comma[$i]))."\n";
            }
        }

        return $ret;
    }

    /*
    *  @public function render
    *  @param null
    *  @return bool|string
    */
    public function render()
    {
        $module = $this->getModule();
        $filename = $this->getFileName();
        $moduleName = strtolower($module->getVar('mod_name'));
        $moduleDirname = strtolower($module->getVar('mod_dirname'));
        $content = $this->getHeaderSqlComments($moduleName);
        $content      .= $this->getDatabaseTables($moduleDirname);
        //
        $this->tdmcfile->create($moduleDirname, 'sql', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}
