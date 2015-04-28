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
 * @version         $Id: abstract.php 12258 2014-01-02 09:33:29Z timgno $
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Abstract base class
 */
abstract class TDMCreateAbstract
{	
	/**
     * "module" attribute fot files
     *
     * @var mixed
     */
    protected $module = null;
	
	/**
     * "table" attribute fot files
     *
     * @var mixed
     */
    protected $table = null;
	
	/**
     * "tables" attribute fot files
     *
     * @var mixed
     */
    protected $tables = null;
	
	/**
     * "fields" attribute fot files
     *
     * @var mixed
     */
    protected $fields = null;

	/**
     * public function setFileName
     * @param mixed $module
     */
    public function setModule($module) {
		if (is_object($module) && ($module instanceof TDMCreateModules)) {
			$this->module = $module;
		}
    }
	
	/*
	*  @public function getModule
	*  @param null
	*/
    public function getModule() {
        return $this->module;
    }
	
	/**
     * public function setTable
     * @param mixed $table
     */
    public function setTable($table) {        
		if (is_object($table) && ($table instanceof TDMCreateTables)) {            
			$this->table = $table;
		}
    }
	
	/*
	*  @public function getTable
	*  @param null
	*/
	public function getTable() { 		
        return $this->table;
	}
	
	/**
     * public function setTables
     * @param mixed $tables
     */
    public function setTables($tables) { 
		if (is_array($tables)) { 
			$this->tables = $tables;
		}
    }
	
	/*
	*  @public function getTables
	*  @param null
	*/
	public function getTables() { 		
        return $this->tables;
	}
	
	/**
     * @public function setFields
     * @param mixed $fields
     */
    public function setFields($fields) {
		if (is_object($fields) && ($fields instanceof TDMCreateFields)) {
			$this->fields = $fields;	
        }			
    }
	
	/*
	*  @public function getFields
	*  @param null
	*/
	public function getFields() {		
		return $this->fields;
	}
	
    /**
     * Generates output for files.
     *
     * This method is abstract and must be overwritten by the child classes.
     *
     * @abstract
     */
    public function render() {}
}