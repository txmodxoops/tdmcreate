<?php

namespace XoopsModules\Mymodule3;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * My Module 3 module for xoops
 *
 * @copyright     2020 XOOPS Project (https://xooops.org)
 * @license        GPL 2.0 or later
 * @package        mymodule3
 * @since          1.0
 * @min_xoops      2.5.9
 * @author         TDM XOOPS - Email:<info@email.com> - Website:<http://xoops.org>
 */

use XoopsModules\Mymodule3;


/**
 * Class Object Handler Categories
 */
class CategoriesHandler extends \XoopsPersistableObjectHandler
{
	/**
	 * Constructor 
	 *
	 * @param \XoopsDatabase $db
	 */
	public function __construct(\XoopsDatabase $db)
	{
		parent::__construct($db, 'mymodule3_categories', Categories::class, 'cat_id', 'cat_name');
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
	 * Get Count Categories in the database
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return int
	 */
	public function getCountCategories($start = 0, $limit = 0, $sort = 'cat_id ASC, cat_name', $order = 'ASC')
	{
		$crCountCategories = new \CriteriaCompo();
		$crCountCategories = $this->getCategoriesCriteria($crCountCategories, $start, $limit, $sort, $order);
		return parent::getCount($crCountCategories);
	}

	/**
	 * Get All Categories in the database
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return array
	 */
	public function getAllCategories($start = 0, $limit = 0, $sort = 'cat_id ASC, cat_name', $order = 'ASC')
	{
		$crAllCategories = new \CriteriaCompo();
		$crAllCategories = $this->getCategoriesCriteria($crAllCategories, $start, $limit, $sort, $order);
		return parent::getAll($crAllCategories);
	}

	/**
	 * Get Criteria Categories
	 * @param        $crCategories
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return int
	 */
	private function getCategoriesCriteria($crCategories, $start, $limit, $sort, $order)
	{
		$crCategories->setStart( $start );
		$crCategories->setLimit( $limit );
		$crCategories->setSort( $sort );
		$crCategories->setOrder( $order );
		return $crCategories;
	}
}
