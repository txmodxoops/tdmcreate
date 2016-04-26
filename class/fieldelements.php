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
 * @since           2.5.5
 *
 * @author          Txmod Xoops <support@txmodxoops.org>
 *
 * @version         $Id: 1.91 fieldelements.php 11297 2014-03-24 09:11:10Z timgno $
 */

/*
*  @Class TDMCreateFieldElements
*  @extends XoopsObject
*/

/**
 * Class TDMCreateFieldElements.
 */
class TDMCreateFieldElements extends XoopsObject
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
    /**
     * @return TDMCreateFieldElements
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
     * Get Values.
     */
    public function getValuesFieldElements($keys = null, $format = null, $maxDepth = null)
    {
        $ret = $this->getValues($keys, $format, $maxDepth);
        // Values
        $ret['id'] = $this->getVar('fieldelement_id');
        $ret['mid'] = $this->getVar('fieldelement_mid');
        $ret['tid'] = $this->getVar('fieldelement_tid');
        $ret['name'] = $this->getVar('fieldelement_name');
        $ret['value'] = $this->getVar('fieldelement_value');

        return $ret;
    }
}

/*
*  @Class TDMCreateFieldElementsHandler
*  @extends XoopsPersistableObjectHandler
*/

/**
 * Class TDMCreateFieldElementsHandler.
 */
class TDMCreateFieldElementsHandler extends XoopsPersistableObjectHandler
{
    /*
    *  @public function constructor class
    *  @param mixed $db
    */
    /**
     * @param null|object $db
     */
    public function __construct(&$db)
    {
        parent::__construct($db, 'tdmcreate_fieldelements', 'tdmcreatefieldelements', 'fieldelement_id', 'fieldelement_name');
    }

    /**
     * Get Count Fields.
     */
    public function getCountFieldElements($start = 0, $limit = 0, $sort = 'fieldelement_id ASC, fieldelement_name', $order = 'ASC')
    {
        $crCountFieldElems = new CriteriaCompo();
        $crCountFieldElems = $this->getFieldElementsCriteria($crCountFieldElems, $start, $limit, $sort, $order);

        return parent::getCount($crCountFieldElems);
    }

    /**
     * Get Objects Fields.
     */
    public function getObjectsFieldElements($start = 0, $limit = 0, $sort = 'fieldelement_id ASC, fieldelement_name', $order = 'ASC')
    {
        $crObjectsFieldElems = new CriteriaCompo();
        $crObjectsFieldElems = $this->getFieldElementsCriteria($crObjectsFieldElems, $start, $limit, $sort, $order);

        return $this->getObjects($crObjectsFieldElems);
    }

    /**
     * Get All Fields.
     */
    public function getAllFieldElements($start = 0, $limit = 0, $sort = 'fieldelement_id ASC, fieldelement_name', $order = 'ASC')
    {
        $crAllFieldElems = new CriteriaCompo();
        $crAllFieldElems = $this->getFieldElementsCriteria($crAllFieldElems, $start, $limit, $sort, $order);

        return $this->getAll($crAllFieldElems);
    }

    /**
     * Get All Fields By Module & Table Id.
     */
    public function getAllFieldElementsByModuleAndTableId($modId, $tabId, $start = 0, $limit = 0, $sort = 'fieldelement_id ASC, fieldelement_name', $order = 'ASC')
    {
        $crAllFieldElemsByModule = new CriteriaCompo();
        $crAllFieldElemsByModule->add(new Criteria('fieldelement_mid', $modId));
        $crAllFieldElemsByModule->add(new Criteria('fieldelement_tid', $tabId));
        $crAllFieldElemsByModule = $this->getFieldElementsCriteria($crAllFieldElemsByModule, $start, $limit, $sort, $order);

        return $this->getAll($crAllFieldElemsByModule);
    }

    /**
     * Get FieldElements Criteria.
     */
    private function getFieldElementsCriteria($crFieldElemsCriteria, $start, $limit, $sort, $order)
    {
        $crFieldElemsCriteria->setStart($start);
        $crFieldElemsCriteria->setLimit($limit);
        $crFieldElemsCriteria->setSort($sort);
        $crFieldElemsCriteria->setOrder($order);

        return $crFieldElemsCriteria;
    }
}
