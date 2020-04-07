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
 * My Module module for xoops
 *
 * @copyright     2020 XOOPS Project (https://xooops.org)
 * @license        GPL 2.0 or later
 * @package        mymodule
 * @since          1.0
 * @min_xoops      2.5.9
 * @author         TDM XOOPS - Email:<info@email.com> - Website:<http://xoops.org>
 */

use XoopsModules\Mymodule;
use XoopsModules\Mymodule\Helper;
use XoopsModules\Mymodule\Constants;

include_once XOOPS_ROOT_PATH.'/modules/mymodule/include/common.php';
// Function show block
function b_mymodule_articles_show($options)
{
    include_once XOOPS_ROOT_PATH.'/modules/mymodule/class/articles.php';
    $myts = MyTextSanitizer::getInstance();
    $GLOBALS['xoopsTpl']->assign('mymodule_upload_url', MYMODULE_UPLOAD_URL);
    $block       = array();
    $typeBlock   = $options[0];
    $limit       = $options[1];
    $lenghtTitle = $options[2];
    $helper = Helper::getInstance();
    $articlesHandler = $helper->getHandler('articles');
    $criteria = new \CriteriaCompo();
    array_shift($options);
    array_shift($options);
    array_shift($options);
	switch($typeBlock)
	{
		// For the block: articles last
		case 'last':
			//$criteria->add(new \Criteria('art_display', 1));
			$criteria->setSort('art_created');
			$criteria->setOrder('DESC');
		break;
		// For the block: articles new
		case 'new':
			//$criteria->add(new \Criteria('art_display', 1));
			$criteria->add(new \Criteria('art_created', strtotime(date(_SHORTDATESTRING)), '>='));
			$criteria->add(new \Criteria('art_created', strtotime(date(_SHORTDATESTRING))+86400, '<='));
			$criteria->setSort('art_created');
			$criteria->setOrder('ASC');
		break;
		// For the block: articles hits
		case 'hits':
            $criteria->setSort('art_hits');
            $criteria->setOrder('DESC');
        break;
		// For the block: articles top
		case 'top':
            $criteria->setSort('art_top');
            $criteria->setOrder('ASC');
        break;
		// For the block: articles random
		case 'random':
			//$criteria->add(new \Criteria('art_display', 1));
			$criteria->setSort('RAND()');
		break;
	}
    $criteria->setLimit($limit);
    $articlesAll = $articlesHandler->getAll($criteria);
	unset($criteria);
	if (count($articlesAll) > 0) {
        foreach(array_keys($articlesAll) as $i)
        {
            $block[$i]['cat'] = $articlesAll[$i]->getVar('art_cat');
            $block[$i]['title'] = $myts->htmlSpecialChars($articlesAll[$i]->getVar('art_title'));
		    $block[$i]['descr'] = strip_tags($articlesAll[$i]->getVar('art_descr'));
            $block[$i]['img'] = $articlesAll[$i]->getVar('art_img');
            $block[$i]['file'] = $articlesAll[$i]->getVar('art_file');
            $block[$i]['created'] = formatTimeStamp($articlesAll[$i]->getVar('art_created'));
		    $block[$i]['submitter'] = \XoopsUser::getUnameFromId($articlesAll[$i]->getVar('art_submitter'));
        }
    }
    return $block;
}

// Function edit block
function b_mymodule_articles_edit($options)
{
    include_once XOOPS_ROOT_PATH.'/modules/mymodule/class/articles.php';
    $helper = Helper::getInstance();
    $articlesHandler = $helper->getHandler('articles');
    $GLOBALS['xoopsTpl']->assign('mymodule_upload_url', MYMODULE_UPLOAD_URL);
    $form  = _MB_MYMODULE_DISPLAY;
    $form .= "<input type='hidden' name='options[0]' value='".$options[0]."' />";
    $form .= "<input type='text' name='options[1]' size='5' maxlength='255' value='" . $options[1] . "' />&nbsp;<br>";
    $form .= _MB_MYMODULE_TITLE_LENGTH." : <input type='text' name='options[2]' size='5' maxlength='255' value='" . $options[2] . "' /><br><br>";
    array_shift($options);
    array_shift($options);
    array_shift($options);
    $criteria = new \CriteriaCompo();
    $criteria->add(new \Criteria('art_id', 0, '!='));
    $criteria->setSort('art_id');
    $criteria->setOrder('ASC');
    $articlesAll = $articlesHandler->getAll($criteria);
    unset($criteria);
    $form .= _MB_MYMODULE_ARTICLES_TO_DISPLAY."<br><select name='options[]' multiple='multiple' size='5'>";
    $form .= "<option value='0' " . (in_array(0, $options) === false ? '' : "selected='selected'") . '>' . _MB_MYMODULE_ALL_ARTICLES . '</option>';
    foreach (array_keys($articlesAll) as $i) {
        $art_id = $articlesAll[$i]->getVar('art_id');
        $form .= "<option value='" . $art_id . "' " . (in_array($art_id, $options) === false ? '' : "selected='selected'") . '>' . $articlesAll[$i]->getVar('art_title') . '</option>';
    }
    $form .= '</select>';
    return $form;
}