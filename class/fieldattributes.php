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
 * @since           2.5.7
 *
 * @author          Txmod Xoops <webmaster@txmodxoops.org> - <http://www.txmodxoops.org/>
 *
 * @version         $Id: 1.91 fieldattributes.php 13027 2015-02-14 12:18:10Z timgno $
 */
defined('XOOPS_ROOT_PATH') || die('Restricted access');

/*
*  @Class TDMCreateFieldattributes
*  @extends XoopsObject
*/

/**
 * Class TDMCreateFieldattributes.
 */
class TDMCreateFieldattributes extends XoopsObject
{
    /*
    *  @public function constructor class
    *  @param null
    */
    /**
     *
     */
    public function __construct()
    {
        $this->initVar('fieldattribute_id', XOBJ_DTYPE_INT);
        $this->initVar('fieldattribute_name', XOBJ_DTYPE_TXTBOX);
        $this->initVar('fieldattribute_value', XOBJ_DTYPE_TXTBOX);
    }
}

/*
*  @Class TDMCreateFieldattributesHandler
*  @extends XoopsPersistableObjectHandler
*/

/**
 * Class TDMCreateFieldattributesHandler.
 */
class TDMCreateFieldattributesHandler extends XoopsPersistableObjectHandler
{
    /**
     * @param null|object $db
     */
    public function __construct(&$db)
    {
        parent::__construct($db, 'tdmcreate_fieldattributes', 'tdmcreatefieldattributes', 'fieldattribute_id', 'fieldattribute_name');
    }
}
