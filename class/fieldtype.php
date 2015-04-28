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
 * @version         $Id: 1.91 fieldtype.php 11297 2014-05-14 10:58:10Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');
/*
*  @Class TDMCreateFieldtype
*  @extends XoopsObject 
*/
class TDMCreateFieldtype extends XoopsObject
{ 
	/*
	*  @public function constructor class
	*  @param null
	*/
	public function __construct()
	{
		$this->XoopsObject(); 
        $this->initVar('fieldtype_id', XOBJ_DTYPE_INT);		
		$this->initVar('fieldtype_name', XOBJ_DTYPE_TXTBOX);
		$this->initVar('fieldtype_value', XOBJ_DTYPE_TXTBOX);		
	}
}
/*
*  @Class TDMCreateFieldtypeHandler
*  @extends XoopsPersistableObjectHandler
*/
class TDMCreateFieldtypeHandler extends XoopsPersistableObjectHandler 
{
    function __construct(&$db) 
    {
        parent::__construct($db, 'tdmcreate_fieldtype', 'tdmcreatefieldtype', 'fieldtype_id', 'fieldtype_name');
    }
}