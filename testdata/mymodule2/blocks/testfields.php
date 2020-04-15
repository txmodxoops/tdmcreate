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

use XoopsModules\Mymodule2;
use XoopsModules\Mymodule2\Helper;
use XoopsModules\Mymodule2\Constants;

include_once XOOPS_ROOT_PATH.'/modules/mymodule2/include/common.php';
// Function show block
function b_mymodule2_testfields_show($options)
{
    include_once XOOPS_ROOT_PATH.'/modules/mymodule2/class/testfields.php';
    $myts = MyTextSanitizer::getInstance();
    $GLOBALS['xoopsTpl']->assign('mymodule2_upload_url', MYMODULE2_UPLOAD_URL);
    $block       = array();
    $typeBlock   = $options[0];
    $limit       = $options[1];
    $lenghtTitle = $options[2];
    $helper = Helper::getInstance();
    $testfieldsHandler = $helper->getHandler('testfields');
    $criteria = new \CriteriaCompo();
    array_shift($options);
    array_shift($options);
    array_shift($options);
	switch($typeBlock)
	{
		// For the block: testfields last
		case 'last':
			//$criteria->add(new \Criteria('tf_display', 1));
			$criteria->setSort('tf_created');
			$criteria->setOrder('DESC');
		break;
		// For the block: testfields new
		case 'new':
			//$criteria->add(new \Criteria('tf_display', 1));
			$criteria->add(new \Criteria('tf_created', strtotime(date(_SHORTDATESTRING)), '>='));
			$criteria->add(new \Criteria('tf_created', strtotime(date(_SHORTDATESTRING))+86400, '<='));
			$criteria->setSort('tf_created');
			$criteria->setOrder('ASC');
		break;
		// For the block: testfields hits
		case 'hits':
            $criteria->setSort('tf_hits');
            $criteria->setOrder('DESC');
        break;
		// For the block: testfields top
		case 'top':
            $criteria->setSort('tf_top');
            $criteria->setOrder('ASC');
        break;
		// For the block: testfields random
		case 'random':
			//$criteria->add(new \Criteria('tf_display', 1));
			$criteria->setSort('RAND()');
		break;
	}
    $criteria->setLimit($limit);
    $testfieldsAll = $testfieldsHandler->getAll($criteria);
	unset($criteria);
	if (count($testfieldsAll) > 0) {
        foreach(array_keys($testfieldsAll) as $i)
        {
        }
    }
    return $block;
}

// Function edit block
function b_mymodule2_testfields_edit($options)
{
    include_once XOOPS_ROOT_PATH.'/modules/mymodule2/class/testfields.php';
    $helper = Helper::getInstance();
    $testfieldsHandler = $helper->getHandler('testfields');
    $GLOBALS['xoopsTpl']->assign('mymodule2_upload_url', MYMODULE2_UPLOAD_URL);
    $form  = _MB_MYMODULE2_DISPLAY;
    $form .= "<input type='hidden' name='options[0]' value='".$options[0]."' />";
    $form .= "<input type='text' name='options[1]' size='5' maxlength='255' value='" . $options[1] . "' />&nbsp;<br>";
    $form .= _MB_MYMODULE2_TITLE_LENGTH." : <input type='text' name='options[2]' size='5' maxlength='255' value='" . $options[2] . "' /><br><br>";
    array_shift($options);
    array_shift($options);
    array_shift($options);
    $criteria = new \CriteriaCompo();
    $criteria->add(new \Criteria('tf_id', 0, '!='));
    $criteria->setSort('tf_id');
    $criteria->setOrder('ASC');
    $testfieldsAll = $testfieldsHandler->getAll($criteria);
    unset($criteria);
    $form .= _MB_MYMODULE2_TESTFIELDS_TO_DISPLAY."<br><select name='options[]' multiple='multiple' size='5'>";
    $form .= "<option value='0' " . (in_array(0, $options) === false ? '' : "selected='selected'") . '>' . _MB_MYMODULE2_ALL_TESTFIELDS . '</option>';
    foreach (array_keys($testfieldsAll) as $i) {
        $tf_id = $testfieldsAll[$i]->getVar('tf_id');
        $form .= "<option value='" . $tf_id . "' " . (in_array($tf_id, $options) === false ? '' : "selected='selected'") . '>' . $testfieldsAll[$i]->getVar('tf_text') . '</option>';
    }
    $form .= '</select>';
    return $form;
}