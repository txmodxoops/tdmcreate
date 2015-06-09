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
 * @version         $Id: TDMCreateTableFields.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');
/**
 * Class TDMCreateTableFields
 */
class TDMCreateTableFields extends TDMCreateAbstract
{
    /**
     *  @public function constructor
     *  @param mixed $table
     */
    public function __construct()
    {
        $this->tdmcreate = TDMCreateHelper::getInstance();
    }
	
	/*
     * @static function &getInstance
     *  
     * @return TDMCreateFields
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
     *  @public function getTableTables
     *  @param  $mId
     *  @return mixed
     */
    public function getTableTables($mId, $sort = 'table_id ASC, table_name', $order = 'ASC')
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('table_mid', $mId)); // $mId = module Id
		$criteria->setSort($sort);
        $criteria->setOrder($order);
        $tables = $this->tdmcreate->getHandler('tables')->getObjects($criteria);
        unset($criteria);

        return $tables;
    }

    /**
     *  @public function getTableFields
     *  @param $mId
	 *  @param $tId
     *  @return mixed
     */
    public function getTableFields($mId, $tId, $sort = 'field_id ASC, field_name', $order = 'ASC')
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('field_mid', $mId)); // $mId = module Id
		$criteria->add(new Criteria('field_tid', $tId)); // $tId = table Id
		$criteria->setSort($sort);
        $criteria->setOrder($order);
        $fields = $this->tdmcreate->getHandler('fields')->getObjects($criteria);
        unset($criteria);

        return $fields;
    }
	
	/**
     *  @public function getTableFieldElements
     *  @param $mId
	 *  @param $tId
     *  @return mixed
     */
    public function getTableFieldElements($mId = null, $tId = null, $sort = 'fieldelement_id ASC, fieldelement_name', $order = 'ASC')
    {
        $criteria = new CriteriaCompo();
		if($mId != null) {
			$criteria->add(new Criteria('fieldelement_mid', $mId)); // $mId = module Id
			$criteria->setSort($sort);
			$criteria->setOrder($order);
		}
		if($tId != null) {
			$criteria->add(new Criteria('fieldelement_tid', $tId)); // $tId = table Id
			$criteria->setSort($sort);
			$criteria->setOrder($order);
		}
        $fieldElements = $this->tdmcreate->getHandler('fieldelements')->getObjects($criteria);
        unset($criteria);

        return $fieldElements;
    }
}
