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
 * @version         $Id: SqlFile.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

class SqlFile extends TDMCreateFile
{	
	/*
	*  @public function constructor
	*  @param null
	*/
	public function __construct() { 
		parent::__construct();
		$this->tdmcfile = TDMCreateFile::getInstance();				
	}

	/*
	*  @static function &getInstance
	*  @param null
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
	*  @param string $table
	*  @param string $tables
	*  @param string $filename
	*/
	public function write($module, $tables, $filename) {    
		$this->setModule($module);
		$this->setTables($tables);
		$this->setFileName($filename);			
	}
	
	/*
	*  @private function getHeaderSqlComments
	*  @param string $moduleName
	*/
	private function getHeaderSqlComments($moduleName) 
	{   
		$date = date('D M d, Y');
		$time = date('G:i');
		$server_name = $_SERVER['SERVER_NAME'];
		$server_version = mysql_get_server_info();
		$php_version = phpversion();
		// Header Sql Comments
		$ret = <<<SQL
# SQL Dump for {$moduleName} module
# PhpMyAdmin Version: 4.0.4
# http://www.phpmyadmin.net
#
# Host: {$server_name}
# Generated on: {$date} to {$time}
# Server version: {$server_version}
# PHP Version: {$php_version}\n
SQL;
		return $ret;
	}	
	
	/*
	*  @private function getHeadDatabaseTable
	*  @param string $moduleDirname
	*  @param string $tableName
	*  @param integer $fieldsNumb
	*
	*  Unused IF NOT EXISTS
	*/
	private function getHeadDatabaseTable($moduleDirname, $tableName, $fieldsNumb) 
	{    
		$ret = <<<SQL

#
# Structure table for `{$moduleDirname}_{$tableName}` {$fieldsNumb}
#
		
CREATE TABLE `{$moduleDirname}_{$tableName}` (\n
SQL;
		return $ret;
	}
	
	/*
	*  @private function getDatabaseTables
	*  @param string $moduleDirname
	*/
	private function getDatabaseTables($moduleDirname) 
	{    
		$ret = null;
		$tables = $this->getTables();
		foreach(array_keys($tables) as $t) 
		{
			$tableId = $tables[$t]->getVar('table_id');
			$tableName = $tables[$t]->getVar('table_name');
			$tableAutoincrement = $tables[$t]->getVar('table_autoincrement');
			$fieldsNumb = $tables[$t]->getVar('table_nbfields');					
			$ret .= $this->getDatabaseFields($moduleDirname, $tableId, $tableName, $tableAutoincrement, $fieldsNumb);
		}		
		return $ret;
	}
	
	/*
	*  @private function getDatabaseFields
	*  @param string $moduleDirname
	*  @param string $tableName
	*  @param integer $tableAutoincrement
	*  @param integer $fieldsNumb
	*/
	private function getDatabaseFields($moduleDirname, $tableId, $tableName, $tableAutoincrement, $fieldsNumb) 
	{    		
		$ret = null; $j = 0; $comma = array(); $row = array();
        $fields = $this->getTableFields($tableId);		
		foreach(array_keys($fields) as $f) 
		{
			// Creation of database table  
			$ret = $this->getHeadDatabaseTable($moduleDirname, $tableName, $fieldsNumb);
			$fieldName = $fields[$f]->getVar('field_name');
			$fieldType = $fields[$f]->getVar('field_type');
			$fieldValue = $fields[$f]->getVar('field_value');
			$fieldAttribute = $fields[$f]->getVar('field_attribute');
			$fieldNull = $fields[$f]->getVar('field_null');
			$fieldDefault = $fields[$f]->getVar('field_default');
			$fieldKey = $fields[$f]->getVar('field_key');
			if($fieldType > 1) {					
				$fType = $this->tdmcreate->getHandler('fieldtype')->get($fieldType);
				$fieldTypeName = $fType->getVar('fieldtype_name');									
			} else {
				$fieldType = null;
			}
			if($fieldAttribute > 1) {					
				$fAttribute = $this->tdmcreate->getHandler('fieldattributes')->get($fieldAttribute);
				$fieldAttribute = $fAttribute->getVar('fieldattribute_name');									
			} else {
				$fieldAttribute = null;
			}
			if($fieldNull > 1) {					
				$fNull = $this->tdmcreate->getHandler('fieldnull')->get($fieldNull);
				$fieldNull = $fNull->getVar('fieldnull_name');									
			} else {
				$fieldNull = null;
			}			
			if ( !empty($fieldName) )
			{								
				switch( $fieldType ) {
					case 15:
					case 16:
					case 17:
					case 18:
					case 19:	
					case 20:
					case 21:
						$type = $fieldTypeName;
						$default = null;
                    break;					
					default:
						$type = $fieldTypeName.'('.$fieldValue.')';
						$default = "DEFAULT '{$fieldDefault}'";
					break;
				}	
				if( ($f == 0) && ($tableAutoincrement == 1) ) {
					$row[] = $this->getFieldRow($fieldName, $type, $fieldAttribute, $fieldNull, null, 'AUTO_INCREMENT');					
					$comma[$j] = $this->getKey(2, $fieldName);
					$j++;
				} elseif( ($f == 0) && ($tableAutoincrement == 0) ) {
					$row[] = $this->getFieldRow($fieldName, $type, $fieldAttribute, $fieldNull, $default);					
					$comma[$j] = $this->getKey(2, $fieldName);
					$j++;
				} else {
					if( $fieldKey == 3 || $fieldKey == 4 || $fieldKey == 5 || $fieldKey == 6)
					{
						switch( $fieldKey ) {					
							case 3:
								$row[] = $this->getFieldRow($fieldName, $type, $fieldAttribute, $fieldNull, $default);
								$comma[$j] = $this->getKey(3, $fieldName);
								$j++;
							break;
							case 4:
								$row[] = $this->getFieldRow($fieldName, $type, $fieldAttribute, $fieldNull, $default);
								$comma[$j] = $this->getKey(4, $fieldName);
								$j++;
							break;
							case 5:
								$row[] = $this->getFieldRow($fieldName, $type, $fieldAttribute, $fieldNull, $default);
								$comma[$j] = $this->getKey(5, $fieldName);
								$j++;
							break;
							case 6:
								$row[] = $this->getFieldRow($fieldName, $type, $fieldAttribute, $fieldNull, $default);
								$comma[$j] = $this->getKey(6, $fieldName);
								$j++;
							break;											
						}	
					} else {
						$row[] = $this->getFieldRow($fieldName, $type, $fieldAttribute, $fieldNull, $default);
					}										
				}								
			}
		}
		// ================= COMMA ================= //
		for ($i=0; $i < $j; $i++) {
			if ( $i != $j - 1 ) {
				$row[] = $comma[$i].',';
			} else {
				$row[] = $comma[$i];
			}
		}
		// ================= COMMA CICLE ================= //
		//$row[] = $this->getCommaCicle($comma, $j);
		$ret .= implode("\n", $row);				
		unset($j);
		$ret .= $this->getFootDatabaseTable();
		return $ret;
	}
	/*
	*  @private function getFootDatabaseTable
	*  @param null
	*/
	private function getFootDatabaseTable() {    
		$ret = <<<SQL
\n) ENGINE=MyISAM;\n
SQL;
		return $ret;
	}
	/*
	*  @private function getFieldRow
	*  @param string $fieldName
	*  @param string $fieldTypeValue
	*  @param string $fieldAttribute
	*  @param string $fieldNull
	*  @param string $fieldDefault
	*  @param string $autoincrement
	*/
	private function getFieldRow($fieldName, $fieldTypeValue, $fieldAttribute = null, $fieldNull = null, $fieldDefault = null, $autoincrement = null) {    
		$retAutoincrement = <<<SQL
  `{$fieldName}` {$fieldTypeValue} {$fieldAttribute} {$fieldNull} {$autoincrement},
SQL;
		$retFieldAttribute = <<<SQL
  `{$fieldName}` {$fieldTypeValue} {$fieldAttribute} {$fieldNull} {$fieldDefault},
SQL;
		$fieldDefault = <<<SQL
  `{$fieldName}` {$fieldTypeValue} {$fieldNull} {$fieldDefault},
SQL;
		$retShort = <<<SQL
  `{$fieldName}` {$fieldTypeValue},
SQL;
		if($autoincrement != null) {
			$ret = $retAutoincrement;
		} elseif($fieldAttribute != null) {
			$ret = $retFieldAttribute;
		} elseif($fieldAttribute == null) {
			$ret = $fieldDefault;
		} else {
			$ret = $retShort;
		}
		return $ret;
	}
	/*
	*  @private function getKey
	*  @param integer $key
	*  @param array $fieldName
	*/
	private function getKey($key, $fieldName) {    
		switch( $key ) {
			case 2: // PRIMARY KEY
				$ret = <<<SQL
  PRIMARY KEY (`{$fieldName}`)
SQL;
			break;
			case 3: // UNIQUE KEY
				$ret = <<<SQL
  UNIQUE KEY `{$fieldName}` (`{$fieldName}`)
SQL;
			break;
			case 4: // KEY
				$ret = <<<SQL
  KEY `{$fieldName}` (`{$fieldName}`)
SQL;
			break;
			case 5: // INDEX
				$ret = <<<SQL
  INDEX (`{$fieldName}`)
SQL;
			break;
			case 6: // FULLTEXT KEY
				$ret = <<<SQL
  FULLTEXT KEY `{$fieldName}` (`{$fieldName}`)
SQL;
			break;
		}
		return $ret;
	}	
		
	/*
	*  @private function getComma
	*  @param array $row
	*  @param string $comma
	*/
	private function getComma($row, $comma = null) {    
		$ret = <<<SQL
			{$row}{$comma}
SQL;
		return $ret;
	}	
	/*
	*  @private function getCommaCicle
	*  @param array $comma
	*  @param integer $index
	*/
	private function getCommaCicle($comma, $index) {    
		// Comma issue
		for ($i = 1; $i <= $index; $i++)
		{
			if ( $i != $index - 1 ) {
				$ret = $this->getComma(isset($comma[$i]), ','). "\n";
			} else {
				$ret = $this->getComma(isset($comma[$i])). "\n";
			}
		}
		return $ret;
	}	
	
	/*
	*  @public function render
	*  @param null
	*/
	public function render() {    
		$module = $this->getModule();		
		$filename = $this->getFileName();
		$moduleName = strtolower($module->getVar('mod_name'));
        $moduleDirname = strtolower($module->getVar('mod_dirname'));		
		$content = $this->getHeaderSqlComments($moduleName);
		$content .= $this->getDatabaseTables($moduleDirname);
		//
		$this->tdmcfile->create($moduleDirname, 'sql', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);
		return $this->tdmcfile->renderFile();
	}
}