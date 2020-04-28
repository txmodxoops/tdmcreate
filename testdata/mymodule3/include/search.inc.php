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
 * search callback functions 
 *
 * @param $queryarray 
 * @param $andor 
 * @param $limit 
 * @param $offset 
 * @param $userid 
 * @return mixed $itemIds
 */
function mymodule3_search($queryarray, $andor, $limit, $offset, $userid)
{
	$ret = [];
	$helper = \XoopsModules\Mymodule3\Helper::getInstance();

	// search in table articles
	// search keywords
	$elementCount = 0;
	$articlesHandler = $helper->getHandler('articles');
	if (is_array($queryarray)) {
		$elementCount = count($queryarray);
	}
	if ($elementCount > 0) {
		$criteriaKeywords = new \CriteriaCompo();
		for($i = 0; $i  <  $elementCount; $i++) {
			$criteriaKeyword = new \CriteriaCompo();
			$criteriaKeyword->add( new \Criteria( 'art_cat', '%' . $queryarray[$i] . '%', 'LIKE' ), 'OR' );
			$criteriaKeyword->add( new \Criteria( 'art_title', '%' . $queryarray[$i] . '%', 'LIKE' ), 'OR' );
			$criteriaKeyword->add( new \Criteria( 'art_descr', '%' . $queryarray[$i] . '%', 'LIKE' ), 'OR' );
			$criteriaKeywords->add( $criteriaKeyword, $andor );
			unset($criteriaKeyword);
		}
	}
	// search user(s)
	if ($userid && is_array($userid)) {
		$userid = array_map('intval', $userid);
		$criteriaUser = new \CriteriaCompo();
		$criteriaUser->add( new \Criteria( 'art_submitter', '(' . implode(',', $userid) . ')', 'IN' ), 'OR' );
	} elseif (is_numeric($userid) && $userid > 0) {
		$criteriaUser = new \CriteriaCompo();
		$criteriaUser->add( new \Criteria( 'art_submitter', $userid ), 'OR' );
	}
	$criteriaSearch = new \CriteriaCompo();
	if (isset($criteriaKeywords)) {
		$criteriaSearch->add( $criteriaKeywords, 'AND' );
	}
	if (isset($criteriaUser)) {
		$criteriaSearch->add( $criteriaUser, 'AND' );
	}
	$criteriaSearch->setStart( $offset );
	$criteriaSearch->setLimit( $limit );
	$criteriaSearch->setSort( 'art_created' );
	$criteriaSearch->setOrder( 'DESC' );
	$articlesAll = $articlesHandler->getAll($criteriaSearch);
	foreach(array_keys($articlesAll) as $i) {
		$ret[] = [
			'image'  => 'assets/icons/16/articles.png',
			'link'   => 'articles.php?op=show&amp;art_id=' . $articlesAll[$i]->getVar('art_id'),
			'title'  => $articlesAll[$i]->getVar('art_title'),
			'time'   => $articlesAll[$i]->getVar('art_created')
		];
	}
	unset($criteriaKeywords);
	unset($criteriaKeyword);
	unset($criteriaUser);
	unset($criteriaSearch);

	// search in table testfields
	// search keywords
	$elementCount = 0;
	$testfieldsHandler = $helper->getHandler('testfields');
	if (is_array($queryarray)) {
		$elementCount = count($queryarray);
	}
	if ($elementCount > 0) {
		$criteriaKeywords = new \CriteriaCompo();
		for($i = 0; $i  <  $elementCount; $i++) {
			$criteriaKeyword = new \CriteriaCompo();
			$criteriaKeyword->add( new \Criteria( 'tf_text', '%' . $queryarray[$i] . '%', 'LIKE' ), 'OR' );
			$criteriaKeyword->add( new \Criteria( 'tf_textarea', '%' . $queryarray[$i] . '%', 'LIKE' ), 'OR' );
			$criteriaKeywords->add( $criteriaKeyword, $andor );
			unset($criteriaKeyword);
		}
	}
	// search user(s)
	if ($userid && is_array($userid)) {
		$userid = array_map('intval', $userid);
		$criteriaUser = new \CriteriaCompo();
		$criteriaUser->add( new \Criteria( 'tf_submitter', '(' . implode(',', $userid) . ')', 'IN' ), 'OR' );
	} elseif (is_numeric($userid) && $userid > 0) {
		$criteriaUser = new \CriteriaCompo();
		$criteriaUser->add( new \Criteria( 'tf_submitter', $userid ), 'OR' );
	}
	$criteriaSearch = new \CriteriaCompo();
	if (isset($criteriaKeywords)) {
		$criteriaSearch->add( $criteriaKeywords, 'AND' );
	}
	if (isset($criteriaUser)) {
		$criteriaSearch->add( $criteriaUser, 'AND' );
	}
	$criteriaSearch->setStart( $offset );
	$criteriaSearch->setLimit( $limit );
	$criteriaSearch->setSort( 'tf_datetime' );
	$criteriaSearch->setOrder( 'DESC' );
	$testfieldsAll = $testfieldsHandler->getAll($criteriaSearch);
	foreach(array_keys($testfieldsAll) as $i) {
		$ret[] = [
			'image'  => 'assets/icons/16/testfields.png',
			'link'   => 'testfields.php?op=show&amp;tf_id=' . $testfieldsAll[$i]->getVar('tf_id'),
			'title'  => $testfieldsAll[$i]->getVar('tf_text'),
			'time'   => $testfieldsAll[$i]->getVar('tf_datetime')
		];
	}
	unset($criteriaKeywords);
	unset($criteriaKeyword);
	unset($criteriaUser);
	unset($criteriaSearch);

	return $ret;

}
