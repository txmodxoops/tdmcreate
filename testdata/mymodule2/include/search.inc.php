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
 * My Module 2 module for xoops
 *
 * @copyright     2020 XOOPS Project (https://xooops.org)
 * @license        GPL 2.0 or later
 * @package        mymodule2
 * @since          1.0
 * @min_xoops      2.5.9
 * @author         TDM XOOPS - Email:<info@email.com> - Website:<http://xoops.org>
 */


/**
 * search callback functions
 * @param $queryarray
 * @param $andor
 * @param $limit
 * @param $offset
 * @param $userid
 */
function mymodule2_search($queryarray, $andor, $limit, $offset, $userid)
{
    global $xoopsDB;
    $sql = "SELECT 'tf_id', 'tf_text' FROM " . $xoopsDB->prefix('mymodule2_testfields') . ' WHERE tf_id != 0';
    if ( $userid != 0 ) {
        $sql .= ' AND tf_submitter='.(int)$userid;
    }
    if ( is_array($queryarray) && $count = count($queryarray) )
    {
        $sql .= " AND (()";
        for($i = 1; $i < $count; ++$i)
        {
            $sql .= " $andor ";
            $sql .= "()";
        }
        $sql .= ')';
    }
    $sql .= " ORDER BY 'tf_id' DESC";
    $result = $xoopsDB->query($sql,$limit,$offset);
    $ret = array();
    $i = 0;
    while($myrow = $xoopsDB->fetchArray($result))
    {
        $ret[$i]['image'] = 'assets/icons/32/blank.gif';
        $ret[$i]['link'] = 'testfields.php?tf_id='.$myrow['tf_id'];
        $ret[$i]['title'] = $myrow['tf_text'];
        ++$i;
    }
    unset($i);
}