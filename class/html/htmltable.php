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
 * @version         $Id: htmltable.php 12258 2014-01-02 09:33:29Z timgno $
*/
defined('XOOPS_ROOT_PATH') or die('Restricted access');
 
/**
 * Base class for html tables
 *
 * @author 		TXMod Xoops <info@txmodxoops.org>
 * @package 	tdmcreate
 * @access 		public
 */
class TDMCreateHtmlTable
{   
    /**
     * array of {@TDMCreateHtmlTable} objects
     *
     * @var array
     */ 
    private $rows = array();
	
	/**
     * array of {@link TDMCreateHtmlTable} objects
     *
     * @var array
     */ 
    private $cells = array();
	
	/**
     * string of {@link TDMCreateHtmlTable} objects
     *
     * @var string
     */
    private $data = '';

	/**
     * string of {@link TDMCreateHtmlTable} objects
     *
     * @var string
     */
    private $_id = '';
	
	/**
     * string of {@link TDMCreateHtmlTable} objects
     *
     * @var string
     */
    private $_class = '';

	/**
     * string of {@link TDMCreateHtmlTable} objects
     *
     * @var string
     */
    private $type = '';

	/**
     * array of {@link TDMCreateHtmlTable} objects
     *
     * @var array
     */
    private $attributes = array();
    
	/**
     * constructor
     *    
     * @param null
     */
    public function __construct() 	
	{       
    }
	
	/*
	*  @public static function &getInstance
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
	
	/**
     * @public Function initTable
     *
     * @param string $id attribute of the table
     * @param string $_class "css class" attribute for the <table> tag
     * @param mixed $attributes attributes for the <table> tag
     */
    public function initTable($_class = null, $id = null, $attributes = false ) 
	{        
		$ret = '<table' . ( !empty($id) ? ' id="' . $id . '"' : '' ); 
        $ret .= ( !empty($_class) ? ' class="' . $_class . '"' : '' );
		if($attributes && is_array($attributes)) {
			$ret .= $this->addAttributes( $attributes ); 
		}
        $ret .= '>';
		return $ret;
    }
    
	/**
     * return key & value of attributes
     *
     * @param array $attributes
     * @return string
     */
    public function addAttributes( $attributes = array()  ) 
	{
        $str = '';
		if(is_array( $attributes )) {
            foreach( $attributes as $k => $v ) {
                $str .= " {$k}=\"{$v}\"";
            }
		}
        return $str;
    }
    
	/**
     * Add an element row to the table
     *
     * @param string $_class
     * @param mixed $attributes
     */
    public function addRow( $class = '', $attributes = null ) 
	{        
		$row = self::getInstance();
		$row->addTableRow( $class, $attributes );
		if(is_array( $attributes )) {
			array_push( $this->rows, $row );
		}
    }
    
	/**
     * Add an element cell to the table
     *
     * @param object $data reference to a {@link XoopsFormElement}
     * @param string $class css element
	 * @param string $type reference to a header or data
     * @param mixed $attributes attributes for a cell
     */
    public function addCell( $data, $class = '', $type = 'data', $attributes = null ) 
	{
        $cell = self::getInstance();
		$cell->addTableCell( $data, $class, $type, $attributes );
        // add new cell to current row's list of cells
        $curRow =& $this->rows[ count( $this->rows ) - 1 ]; // copy by reference
		if(is_array( $attributes )) {	
			if(isset($curRow->cells) && isset($cell)) {
				array_push( $curRow->cells, $cell );
			}
		}
    } 
	
	/**
     * Add an array of cells
     *
     * @param object $data content text
     * @param string $_class css element
	 * @param string $type reference to a header or data
     * @param array $attributes attributes for a cell
     */
    public function addCells( $data, $class = '', $type = 'data', $attributes = array() ) 
	{
	    if (is_array($data)) {
			foreach( $data as $data_cell ) {
				$this->addCell($data_cell, $class, $type, $attributes );
			}
		}
    }
	
	/**
	 * Add Cell for tables
	 *
     * return end of html table
     */
	public function getRows($rows) 
	{
        $ret = '';
		$this->rows = $rows;
		if (is_array( $this->rows )) {
			foreach( $this->rows as $row ) 
			{
				$ret .= !empty($row->_class) ? '<tr class="' . $row->_class . '"' : '<tr';
				$ret .= $this->addAttributes( $row->attributes ) . '>';
				$ret .= $this->getRowCells( $row->cells );
				$ret .= '</tr>';
			}
		}
        return $ret;
    } 
        
	/**
     * get an array of row & cells
     *
     * @param cells $cells elements
     * @return array - array of RowCells
     */
    public function getRowCells($cells) 
	{
        $str = '';
		$this->cells = $cells;
        if (is_array( $this->cells )) {
			foreach( $this->cells as $cell ) 
			{
				$tag = ($cell->type == 'data')? 'td': 'th';
				$str .= !empty($cell->_class) ? '<' . $tag . ' class="' . $cell->_class . '"' : '<' . $tag;
				$str .= $this->addAttributes( $cell->attributes ) . '>';
				$str .= $cell->data;
				$str .= '</' . $tag . '>';
			}
		}
        return $str;
    }  
	
	/**
	 * Add Row for tables
	 *
     * @param string $class css element
     * @param mixed $attributes attributes for a cell
	 */
	public function addTableRow( $class = '', $attributes = null )
	{
		if(empty($class)){
			$this->_class = $class;
		} 
		if(!is_array($attributes)){
			$this->attributes[] = $attributes;
		} else {
			$this->attributes = $attributes;
		}
	}
	
	/**
	 * Add Cell for tables
	 *
     * @param string $data content text
	 * @param string $class css style
	 * @param string $type reference to a header or data
     * @param array $attributes attributes for a cell
	 */
	public function addTableCell( $data, $class = '', $type = '', $attributes = null ) 
	{
		$this->data = $data;
		if(empty($class)){
			$this->_class = $class;
		}
		$this->type = $type;
		if(!is_array($attributes)){
			$this->attributes[] = $attributes;
		} else {
			$this->attributes = $attributes;
		}
	}

	/**
	 * Add Cell for tables
	 *
     * return end of html table
     */
	public function endTable() 
	{        
        $ret = '';
		$class = $this->_class; 
		$attributes = $this->attributes;
		$rows = $this->addRow( $class, $attributes );
		$ret .= $this->getRows($rows);
		$ret .= '</table>';
        return $ret;
    } 
	
	/**
	 * Render Function
	 *
     * return html table
     */
	public function render() 
	{
        //$ret = $this->initTable($id = '', $_class = '', $border = 0, $cellspacing = 2, $cellpadding = 0, $attributes = array() );
		$ret = $this->endTable();
        return $ret;
    } 
}