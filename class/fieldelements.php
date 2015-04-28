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
 * @since           2.5.5
 * @author          Txmod Xoops <support@txmodxoops.org>
 * @version         $Id: 1.91 fieldelements.php 11297 2014-03-24 09:11:10Z timgno $
 */ 
defined('XOOPS_ROOT_PATH') or die('Restricted access');
/*
*  @Class TDMCreateFieldElements
*  @extends XoopsObject 
*/
class TDMCreateFieldElements extends XoopsObject
{ 
	/*
	*  @public function constructor class
	*  @param null
	*/
	public function __construct()
	{		
		$this->XoopsObject();
		$this->initVar('fieldelement_id', XOBJ_DTYPE_INT);
        $this->initVar('fieldelement_mid', XOBJ_DTYPE_INT);	
        $this->initVar('fieldelement_tid', XOBJ_DTYPE_INT);			
		$this->initVar('fieldelement_name', XOBJ_DTYPE_TXTBOX);     
		$this->initVar('fieldelement_value', XOBJ_DTYPE_TXTBOX);		
	}
	
	/**
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        $arg = isset($args[0]) ? $args[0] : null;
        return $this->getVar($method, $arg);
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
}
/*
*  @Class TDMCreateFieldElementsHandler
*  @extends XoopsPersistableObjectHandler
*/
class TDMCreateFieldElementsHandler extends XoopsPersistableObjectHandler 
{
    /*
	*  @public function constructor class
	*  @param mixed $db
	*/
	public function __construct(&$db) 
    {
        parent::__construct($db, 'tdmcreate_fieldelements', 'tdmcreatefieldelements', 'fieldelement_id', 'fieldelement_name');
    }
}