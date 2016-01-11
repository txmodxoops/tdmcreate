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
defined('XOOPS_ROOT_PATH') || die('Restricted access');

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
        $criteriaCountFieldElements = new CriteriaCompo();
        $criteriaCountFieldElements = $this->getFieldElementsCriteria($criteriaCountFieldElements, $start, $limit, $sort, $order);

        return parent::getCount($criteriaCountFieldElements);
    }

    /**
     * Get Objects Fields.
     */
    public function getObjectsFieldElements($start = 0, $limit = 0, $sort = 'fieldelement_id ASC, fieldelement_name', $order = 'ASC')
    {
        $criteriaObjectsFieldElements = new CriteriaCompo();
        $criteriaObjectsFieldElements = $this->getFieldElementsCriteria($criteriaObjectsFieldElements, $start, $limit, $sort, $order);

        return $this->getObjects($criteriaObjectsFieldElements);
    }

    /**
     * Get All Fields.
     */
    public function getAllFieldElements($start = 0, $limit = 0, $sort = 'fieldelement_id ASC, fieldelement_name', $order = 'ASC')
    {
        $criteriaAllFieldElements = new CriteriaCompo();
        $criteriaAllFieldElements = $this->getFieldElementsCriteria($criteriaAllFieldElements, $start, $limit, $sort, $order);

        return $this->getAll($criteriaAllFieldElements);
    }

    /**
     * Get All Fields By Module & Table Id.
     */
    public function getAllFieldElementsByModuleAndTableId($modId, $tabId, $start = 0, $limit = 0, $sort = 'fieldelement_id ASC, fieldelement_name', $order = 'ASC')
    {
        $criteriaAllFieldElementsByModule = new CriteriaCompo();
        $criteriaAllFieldElementsByModule->add(new Criteria('fieldelement_mid', $modId));
        $criteriaAllFieldElementsByModule->add(new Criteria('fieldelement_tid', $tabId));
        $criteriaAllFieldElementsByModule = $this->getFieldElementsCriteria($criteriaAllFieldElementsByModule, $start, $limit, $sort, $order);

        return $this->getAll($criteriaAllFieldElementsByModule);
    }

    /**
     * Get FieldElements Criteria.
     */
    private function getFieldElementsCriteria($criteriaFieldElements, $start, $limit, $sort, $order)
    {
        $criteriaFieldElements->setStart($start);
        $criteriaFieldElements->setLimit($limit);
        $criteriaFieldElements->setSort($sort);
        $criteriaFieldElements->setOrder($order);

        return $criteriaFieldElements;
    }
}
