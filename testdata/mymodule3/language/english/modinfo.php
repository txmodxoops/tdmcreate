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

include_once 'common.php';

// ---------------- Admin Main ----------------
define('_MI_MYMODULE3_NAME', 'My Module 3');
define('_MI_MYMODULE3_DESC', 'This module is for doing following...');
// ---------------- Admin Menu ----------------
define('_MI_MYMODULE3_ADMENU1', 'Dashboard');
define('_MI_MYMODULE3_ADMENU2', 'Categories');
define('_MI_MYMODULE3_ADMENU3', 'Articles');
define('_MI_MYMODULE3_ADMENU4', 'Testfields');
define('_MI_MYMODULE3_ADMENU5', 'Permissions');
define('_MI_MYMODULE3_ADMENU6', 'Feedback');
define('_MI_MYMODULE3_ABOUT', 'About');
// ---------------- Admin Nav ----------------
define('_MI_MYMODULE3_ADMIN_PAGER', 'Admin pager');
define('_MI_MYMODULE3_ADMIN_PAGER_DESC', 'Admin per page list');
// User
define('_MI_MYMODULE3_USER_PAGER', 'User pager');
define('_MI_MYMODULE3_USER_PAGER_DESC', 'User per page list');
// Submenu
define('_MI_MYMODULE3_SMNAME1', 'Index page');
define('_MI_MYMODULE3_SMNAME2', 'Articles');
define('_MI_MYMODULE3_SMNAME3', 'Testfields');
define('_MI_MYMODULE3_SMNAME4', 'Submit');
define('_MI_MYMODULE3_SMNAME5', 'Search');
// Blocks
define('_MI_MYMODULE3_CATEGORIES_BLOCK', 'Categories block');
define('_MI_MYMODULE3_CATEGORIES_BLOCK_DESC', 'Categories block description');
define('_MI_MYMODULE3_CATEGORIES_BLOCK_CATEGORY', 'Categories block CATEGORY');
define('_MI_MYMODULE3_CATEGORIES_BLOCK_CATEGORY_DESC', 'Categories block CATEGORY description');
define('_MI_MYMODULE3_ARTICLES_BLOCK', 'Articles block');
define('_MI_MYMODULE3_ARTICLES_BLOCK_DESC', 'Articles block description');
define('_MI_MYMODULE3_ARTICLES_BLOCK_ARTICLE', 'Articles block  ARTICLE');
define('_MI_MYMODULE3_ARTICLES_BLOCK_ARTICLE_DESC', 'Articles block  ARTICLE description');
define('_MI_MYMODULE3_ARTICLES_BLOCK_LAST', 'Articles block last');
define('_MI_MYMODULE3_ARTICLES_BLOCK_LAST_DESC', 'Articles block last description');
define('_MI_MYMODULE3_ARTICLES_BLOCK_NEW', 'Articles block new');
define('_MI_MYMODULE3_ARTICLES_BLOCK_NEW_DESC', 'Articles block new description');
define('_MI_MYMODULE3_ARTICLES_BLOCK_HITS', 'Articles block hits');
define('_MI_MYMODULE3_ARTICLES_BLOCK_HITS_DESC', 'Articles block hits description');
define('_MI_MYMODULE3_ARTICLES_BLOCK_TOP', 'Articles block top');
define('_MI_MYMODULE3_ARTICLES_BLOCK_TOP_DESC', 'Articles block top description');
define('_MI_MYMODULE3_ARTICLES_BLOCK_RANDOM', 'Articles block random');
define('_MI_MYMODULE3_ARTICLES_BLOCK_RANDOM_DESC', 'Articles block random description');
define('_MI_MYMODULE3_TESTFIELDS_BLOCK', 'Testfields block');
define('_MI_MYMODULE3_TESTFIELDS_BLOCK_DESC', 'Testfields block description');
define('_MI_MYMODULE3_TESTFIELDS_BLOCK_TESTFIELD', 'Testfields block  TESTFIELD');
define('_MI_MYMODULE3_TESTFIELDS_BLOCK_TESTFIELD_DESC', 'Testfields block  TESTFIELD description');
define('_MI_MYMODULE3_TESTFIELDS_BLOCK_LAST', 'Testfields block last');
define('_MI_MYMODULE3_TESTFIELDS_BLOCK_LAST_DESC', 'Testfields block last description');
define('_MI_MYMODULE3_TESTFIELDS_BLOCK_NEW', 'Testfields block new');
define('_MI_MYMODULE3_TESTFIELDS_BLOCK_NEW_DESC', 'Testfields block new description');
define('_MI_MYMODULE3_TESTFIELDS_BLOCK_HITS', 'Testfields block hits');
define('_MI_MYMODULE3_TESTFIELDS_BLOCK_HITS_DESC', 'Testfields block hits description');
define('_MI_MYMODULE3_TESTFIELDS_BLOCK_TOP', 'Testfields block top');
define('_MI_MYMODULE3_TESTFIELDS_BLOCK_TOP_DESC', 'Testfields block top description');
define('_MI_MYMODULE3_TESTFIELDS_BLOCK_RANDOM', 'Testfields block random');
define('_MI_MYMODULE3_TESTFIELDS_BLOCK_RANDOM_DESC', 'Testfields block random description');
// Config
define('_MI_MYMODULE3_EDITOR_DESCR', 'Editor');
define('_MI_MYMODULE3_EDITOR_DESCR_DESC', 'Select the Editor Descr to use');
define('_MI_MYMODULE3_EDITOR_DHTML', 'Editor');
define('_MI_MYMODULE3_EDITOR_DHTML_DESC', 'Select the Editor Dhtml to use');
define('_MI_MYMODULE3_KEYWORDS', 'Keywords');
define('_MI_MYMODULE3_KEYWORDS_DESC', 'Insert here the keywords (separate by comma)');
define('_MI_MYMODULE3_SIZE_MB', 'MB');
define('_MI_MYMODULE3_MAXSIZE_IMAGE', 'Max size image');
define('_MI_MYMODULE3_MAXSIZE_IMAGE_DESC', 'Define the max size for uploading images');
define('_MI_MYMODULE3_MIMETYPES_IMAGE', 'Mime types image');
define('_MI_MYMODULE3_MIMETYPES_IMAGE_DESC', 'Define the allowed mime types for uploading images');
define('_MI_MYMODULE3_MAXWIDTH_IMAGE', 'Max width image');
define('_MI_MYMODULE3_MAXWIDTH_IMAGE_DESC', 'Set the max width which is allowed for uploading images (in pixel)<br>0 means that images keep original size<br>If original image is smaller the image will be not enlarged');
define('_MI_MYMODULE3_MAXHEIGHT_IMAGE', 'Max height image');
define('_MI_MYMODULE3_MAXHEIGHT_IMAGE_DESC', 'Set the max height which is allowed for uploading images (in pixel)<br>0 means that images keep original size<br>If original image is smaller the image will be not enlarged');
define('_MI_MYMODULE3_MAXSIZE_FILE', 'Max size file');
define('_MI_MYMODULE3_MAXSIZE_FILE_DESC', 'Define the max size for uploading files');
define('_MI_MYMODULE3_MIMETYPES_FILE', 'Mime types file');
define('_MI_MYMODULE3_MIMETYPES_FILE_DESC', 'Define the allowed mime types for uploading files');
define('_MI_MYMODULE3_USE_TAG', 'Use TAG');
define('_MI_MYMODULE3_USE_TAG_DESC', 'If you use tag module, check this option to yes');
define('_MI_MYMODULE3_NUMB_COL', 'Number Columns');
define('_MI_MYMODULE3_NUMB_COL_DESC', 'Number Columns to View.');
define('_MI_MYMODULE3_DIVIDEBY', 'Divide By');
define('_MI_MYMODULE3_DIVIDEBY_DESC', 'Divide by columns number.');
define('_MI_MYMODULE3_TABLE_TYPE', 'Table Type');
define('_MI_MYMODULE3_TABLE_TYPE_DESC', 'Table Type is the bootstrap html table.');
define('_MI_MYMODULE3_PANEL_TYPE', 'Panel Type');
define('_MI_MYMODULE3_PANEL_TYPE_DESC', 'Panel Type is the bootstrap html div.');
define('_MI_MYMODULE3_IDPAYPAL', 'Paypal ID');
define('_MI_MYMODULE3_IDPAYPAL_DESC', 'Insert here your PayPal ID for donactions.');
define('_MI_MYMODULE3_ADVERTISE', 'Advertisement Code');
define('_MI_MYMODULE3_ADVERTISE_DESC', 'Insert here the advertisement code');
define('_MI_MYMODULE3_MAINTAINEDBY', 'Maintained By');
define('_MI_MYMODULE3_MAINTAINEDBY_DESC', 'Allow url of support site or community');
define('_MI_MYMODULE3_BOOKMARKS', 'Social Bookmarks');
define('_MI_MYMODULE3_BOOKMARKS_DESC', 'Show Social Bookmarks in the single page');
define('_MI_MYMODULE3_FACEBOOK_COMMENTS', 'Facebook comments');
define('_MI_MYMODULE3_FACEBOOK_COMMENTS_DESC', 'Allow Facebook comments in the single page');
define('_MI_MYMODULE3_DISQUS_COMMENTS', 'Disqus comments');
define('_MI_MYMODULE3_DISQUS_COMMENTS_DESC', 'Allow Disqus comments in the single page');
// Notifications
define('_MI_MYMODULE3_GLOBAL_NOTIFY', 'Global notify');
define('_MI_MYMODULE3_GLOBAL_NOTIFY_DESC', 'Global notify desc');
define('_MI_MYMODULE3_GLOBAL_MODIFY_NOTIFY', 'Global modify notify');
define('_MI_MYMODULE3_GLOBAL_MODIFY_NOTIFY_CAPTION', 'Global modify notify caption');
define('_MI_MYMODULE3_GLOBAL_MODIFY_NOTIFY_DESC', 'Global modify notify desc');
define('_MI_MYMODULE3_GLOBAL_MODIFY_NOTIFY_SUBJECT', 'Global modify notify subject');
define('_MI_MYMODULE3_GLOBAL_BROKEN_NOTIFY', 'Global broken notify');
define('_MI_MYMODULE3_GLOBAL_BROKEN_NOTIFY_CAPTION', 'Global broken notify caption');
define('_MI_MYMODULE3_GLOBAL_BROKEN_NOTIFY_DESC', 'Global broken notify desc');
define('_MI_MYMODULE3_GLOBAL_BROKEN_NOTIFY_SUBJECT', 'Global broken notify subject');
define('_MI_MYMODULE3_GLOBAL_SUBMIT_NOTIFY', 'Global submit notify');
define('_MI_MYMODULE3_GLOBAL_SUBMIT_NOTIFY_CAPTION', 'Global submit notify caption');
define('_MI_MYMODULE3_GLOBAL_SUBMIT_NOTIFY_DESC', 'Global submit notify desc');
define('_MI_MYMODULE3_GLOBAL_SUBMIT_NOTIFY_SUBJECT', 'Global submit notify subject');
define('_MI_MYMODULE3_GLOBAL_NEW_NOTIFY', 'Global new notify');
define('_MI_MYMODULE3_GLOBAL_NEW_NOTIFY_CAPTION', 'Global new notify caption');
define('_MI_MYMODULE3_GLOBAL_NEW_NOTIFY_DESC', 'Global new notify desc');
define('_MI_MYMODULE3_GLOBAL_NEW_NOTIFY_SUBJECT', 'Global new notify subject');
define('_MI_MYMODULE3_CATEGORY_NOTIFY', 'Category notify');
define('_MI_MYMODULE3_CATEGORY_NOTIFY_DESC', 'Category notify desc');
define('_MI_MYMODULE3_CATEGORY_NOTIFY_CAPTION', 'Category notify caption');
define('_MI_MYMODULE3_CATEGORY_NOTIFY_SUBJECT', 'Category notify Subject');
define('_MI_MYMODULE3_CATEGORY_SUBMIT_NOTIFY', 'Category submit notify');
define('_MI_MYMODULE3_CATEGORY_SUBMIT_NOTIFY_CAPTION', 'Category submit notify caption');
define('_MI_MYMODULE3_CATEGORY_SUBMIT_NOTIFY_DESC', 'Category submit notify desc');
define('_MI_MYMODULE3_CATEGORY_SUBMIT_NOTIFY_SUBJECT', 'Category submit notify subject');
define('_MI_MYMODULE3_TESTFIELD_NOTIFY', 'Testfield notify');
define('_MI_MYMODULE3_TESTFIELD_NOTIFY_DESC', 'Testfield notify desc');
define('_MI_MYMODULE3_TESTFIELD_NOTIFY_CAPTION', 'Testfield notify caption');
define('_MI_MYMODULE3_TESTFIELD_NOTIFY_SUBJECT', 'Testfield notify subject');
define('_MI_MYMODULE3_GLOBAL_NEW_CATEGORY_NOTIFY', 'Global newcategory notify');
define('_MI_MYMODULE3_GLOBAL_NEW_CATEGORY_NOTIFY_CAPTION', 'Global newcategory notify caption');
define('_MI_MYMODULE3_GLOBAL_NEW_CATEGORY_NOTIFY_DESC', 'Global newcategory notify desc');
define('_MI_MYMODULE3_GLOBAL_NEW_CATEGORY_NOTIFY_SUBJECT', 'Global newcategory notify subject');
define('_MI_MYMODULE3_GLOBAL_TESTFIELD_MODIFY_NOTIFY', 'Global testfield modify notify');
define('_MI_MYMODULE3_GLOBAL_TESTFIELD_MODIFY_NOTIFY_CAPTION', 'Global testfield modify notify caption');
define('_MI_MYMODULE3_GLOBAL_TESTFIELD_MODIFY_NOTIFY_DESC', 'Global testfield modify notify desc');
define('_MI_MYMODULE3_GLOBAL_TESTFIELD_MODIFY_NOTIFY_SUBJECT', 'Global testfield modify notify subject');
define('_MI_MYMODULE3_GLOBAL_TESTFIELD_BROKEN_NOTIFY', 'Global testfield broken notify');
define('_MI_MYMODULE3_GLOBAL_TESTFIELD_BROKEN_NOTIFY_CAPTION', 'Global testfield broken notify caption');
define('_MI_MYMODULE3_GLOBAL_TESTFIELD_BROKEN_NOTIFY_DESC', 'Global testfield broken notify desc');
define('_MI_MYMODULE3_GLOBAL_TESTFIELD_BROKEN_NOTIFY_SUBJECT', 'Global testfield broken notify subject');
define('_MI_MYMODULE3_GLOBAL_TESTFIELD_SUBMIT_NOTIFY', 'Global testfield submit notify');
define('_MI_MYMODULE3_GLOBAL_TESTFIELD_SUBMIT_NOTIFY_CAPTION', 'Global testfield submit notify caption');
define('_MI_MYMODULE3_GLOBAL_TESTFIELD_SUBMIT_NOTIFY_DESC', 'Global testfield submit notify desc');
define('_MI_MYMODULE3_GLOBAL_TESTFIELD_SUBMIT_NOTIFY_SUBJECT', 'Global testfield submit notify subject');
define('_MI_MYMODULE3_GLOBAL_NEW_TESTFIELD_NOTIFY', 'Global new testfield notify');
define('_MI_MYMODULE3_GLOBAL_NEW_TESTFIELD_NOTIFY_CAPTION', 'Global new testfield notify caption');
define('_MI_MYMODULE3_GLOBAL_NEW_TESTFIELD_NOTIFY_DESC', 'Global new testfield notify desc');
define('_MI_MYMODULE3_GLOBAL_NEW_TESTFIELD_NOTIFY_SUBJECT', 'Global new testfield notify subject');
define('_MI_MYMODULE3_CATEGORY_TESTFIELD_SUBMIT_NOTIFY', 'Category testfield submit notify');
define('_MI_MYMODULE3_CATEGORY_TESTFIELD_SUBMIT_NOTIFY_CAPTION', 'Category testfield submit notify caption');
define('_MI_MYMODULE3_CATEGORY_TESTFIELD_SUBMIT_NOTIFY_DESC', 'Category testfield submit notify desc');
define('_MI_MYMODULE3_CATEGORY_TESTFIELD_SUBMIT_NOTIFY_SUBJECT', 'Category testfield submit notify subject');
define('_MI_MYMODULE3_CATEGORY_NEW_TESTFIELD_NOTIFY', 'Category new testfield notify');
define('_MI_MYMODULE3_CATEGORY_NEW_TESTFIELD_NOTIFY_CAPTION', 'Category new testfield notify caption');
define('_MI_MYMODULE3_CATEGORY_NEW_TESTFIELD_NOTIFY_DESC', 'Category new testfield notify desc');
define('_MI_MYMODULE3_CATEGORY_NEW_TESTFIELD_NOTIFY_SUBJECT', 'Category new testfield notify subject');
define('_MI_MYMODULE3_APPROVE_NOTIFY', 'Testfield approve notify');
define('_MI_MYMODULE3_APPROVE_NOTIFY_CAPTION', 'Testfield approve notify caption');
define('_MI_MYMODULE3_APPROVE_NOTIFY_DESC', 'Testfield approve notify desc');
define('_MI_MYMODULE3_APPROVE_NOTIFY_SUBJECT', 'Testfield approve notify subject');
// Permissions Groups
define('_MI_MYMODULE3_GROUPS', 'Groups access');
define('_MI_MYMODULE3_GROUPS_DESC', 'Select general access permission for groups.');
define('_MI_MYMODULE3_ADMIN_GROUPS', 'Admin Group Permissions');
define('_MI_MYMODULE3_ADMIN_GROUPS_DESC', 'Which groups have access to tools and permissions page');
define('_MI_MYMODULE3_UPLOAD_GROUPS', 'Upload Group Permissions');
define('_MI_MYMODULE3_UPLOAD_GROUPS_DESC', 'Which groups have permissions to upload files');
// ---------------- End ----------------
