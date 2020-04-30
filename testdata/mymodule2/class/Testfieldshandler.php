<?php

namespace XoopsModules\Mymodule2;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * My Module 2 module for xoops
 *
 * @copyright     2020 XOOPS Project (https://xooops.org)
 * @license        GPL 2.0 or later
 * @package        mymodule2
 * @since          1.0
 * @min_xoops      2.5.9
 * @author         TDM XOOPS - Email:<info@email.com> - Website:<http://xoops.org>
 */

use XoopsModules\Mymodule2;


/**
 * Class Object Handler Testfields
 */
class TestfieldsHandler extends \XoopsPersistableObjectHandler
{
	/**
	 * Constructor 
	 *
	 * @param null|XoopsDatabase $db
	 */
	public function __construct(\XoopsDatabase $db)
	{
		parent::__construct($db, 'mymodule2_testfields', Testfields::class, 'tf_id', 'tf_text');
	}

	/**
	 * @param bool $isNew
	 *
	 * @return object
	 */
	public function create($isNew = true)
	{
		return parent::create($isNew);
	}

	/**
	 * retrieve a field
	 *
	 * @param int $i field id
	 * @param null fields
	 * @return mixed reference to the {@link Get} object
	 */
	public function get($i = null, $fields = null)
	{
		return parent::get($i, $fields);
	}

	/**
	 * get inserted id
	 *
	 * @param null
	 * @return integer reference to the {@link Get} object
	 */
	public function getInsertId()
	{
		return $this->db->getInsertId();
	}

	/**
	 * Get Count Testfields in the database
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return int
	 */
	public function getCountTestfields($start = 0, $limit = 0, $sort = 'tf_id ASC, tf_text', $order = 'ASC')
	{
		$crCountTestfields = new \CriteriaCompo();
		$crCountTestfields = $this->getTestfieldsCriteria($crCountTestfields, $start, $limit, $sort, $order);
		return parent::getCount($crCountTestfields);
	}

	/**
	 * Get All Testfields in the database
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return array
	 */
	public function getAllTestfields($start = 0, $limit = 0, $sort = 'tf_id ASC, tf_text', $order = 'ASC')
	{
		$crAllTestfields = new \CriteriaCompo();
		$crAllTestfields = $this->getTestfieldsCriteria($crAllTestfields, $start, $limit, $sort, $order);
		return parent::getAll($crAllTestfields);
	}

	/**
	 * Get Criteria Testfields
	 * @param        $crTestfields
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return int
	 */
	private function getTestfieldsCriteria($crTestfields, $start, $limit, $sort, $order)
	{
		$crTestfields->setStart( $start );
		$crTestfields->setLimit( $limit );
		$crTestfields->setSort( $sort );
		$crTestfields->setOrder( $order );
		return $crTestfields;
	}
}
