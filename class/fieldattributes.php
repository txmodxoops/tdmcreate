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
 * @version         $Id: 1.91 fieldattributes.php 11297 2014-05-14 10:58:10Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');
/*
*  @Class TDMCreateFieldattributes
*  @extends XoopsObject 
*/
class TDMCreateFieldattributes extends XoopsObject
{ 
	/*
	*  @public function constructor class
	*  @param null
	*/
	public function __construct()
	{
		$this->XoopsObject();	
		$this->initVar('fieldattribute_id', XOBJ_DTYPE_INT);
        $this->initVar('fieldattribute_name', XOBJ_DTYPE_TXTBOX);		
        $this->initVar('fieldattribute_value', XOBJ_DTYPE_TXTBOX);			       		
	}
}
/*
*  @Class TDMCreateFieldattributesHandler
*  @extends XoopsPersistableObjectHandler
*/
class TDMCreateFieldattributesHandler extends XoopsPersistableObjectHandler 
{
    function __construct(&$db) 
    {
        parent::__construct($db, 'tdmcreate_fieldattributes', 'tdmcreatefieldattributes', 'fieldattribute_id', 'fieldattribute_name');
    }
}