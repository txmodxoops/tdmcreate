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
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.5
 *
 * @author          Txmod Xoops <support@txmodxoops.org>
 *
 * @version         $Id: 1.91 fieldnull.php 11297 2014-05-14 10:58:10Z timgno $
 */

/**
 * Class TDMCreateFieldnull.
 */
class TDMCreateFieldnull extends XoopsObject
{
    /**
    *  @public function constructor class
    *  @param null
    */

    public function __construct()
    {
        $this->initVar('fieldnull_id', XOBJ_DTYPE_INT);
        $this->initVar('fieldnull_name', XOBJ_DTYPE_TXTBOX);
        $this->initVar('fieldnull_value', XOBJ_DTYPE_TXTBOX);
    }

    /**
    *  @static function getInstance
    *  @param null
     * @return TDMCreateFieldnull
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
     * Get Values.
     * @param null $keys
     * @param null $format
     * @param null $maxDepth
     * @return array
     */
    public function getValuesFieldnull($keys = null, $format = null, $maxDepth = null)
    {
        $ret = $this->getValues($keys, $format, $maxDepth);
        // Values
        $ret['id'] = $this->getVar('fieldnull_id');
        $ret['name'] = $this->getVar('fieldnull_name');
        $ret['value'] = $this->getVar('fieldnull_value');

        return $ret;
    }
}

/**
 * Class TDMCreateFieldnullHandler.
 */
class TDMCreateFieldnullHandler extends XoopsPersistableObjectHandler
{
    /**
     * @param null|XoopsDatabase $db
     */
    public function __construct(XoopsDatabase $db)
    {
        parent::__construct($db, 'tdmcreate_fieldnull', 'tdmcreatefieldnull', 'fieldnull_id', 'fieldnull_name');
    }
}
