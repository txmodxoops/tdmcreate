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

include_once 'common.php';

// ---------------- Admin Main ----------------
define('_MI_MYMODULE_NAME', 'My Module');
define('_MI_MYMODULE_DESC', 'This module is for doing following...');
// ---------------- Admin Menu ----------------
define('_MI_MYMODULE_ADMENU1', 'Dashboard');
define('_MI_MYMODULE_ADMENU2', 'Categories');
define('_MI_MYMODULE_ADMENU3', 'Articles');
define('_MI_MYMODULE_ADMENU4', 'Permissions');
define('_MI_MYMODULE_ADMENU5', 'Feedback');
define('_MI_MYMODULE_ABOUT', 'About');
// ---------------- Admin Nav ----------------
define('_MI_MYMODULE_ADMIN_PAGER', 'Admin pager');
define('_MI_MYMODULE_ADMIN_PAGER_DESC', 'Admin per page list');
// User
define('_MI_MYMODULE_USER_PAGER', 'User pager');
define('_MI_MYMODULE_USER_PAGER_DESC', 'User per page list');
// Submenu
define('_MI_MYMODULE_SMNAME1', 'Index page');
define('_MI_MYMODULE_SMNAME2', 'Articles');
define('_MI_MYMODULE_SMNAME3', 'Submit');
define('_MI_MYMODULE_SMNAME4', 'Search');
// Blocks
define('_MI_MYMODULE_CATEGORIES_BLOCK', 'Categories block');
define('_MI_MYMODULE_CATEGORIES_BLOCK_DESC', 'Categories block description');
define('_MI_MYMODULE_CATEGORIES_BLOCK_CATEGORY', 'Categories block CATEGORY');
define('_MI_MYMODULE_CATEGORIES_BLOCK_CATEGORY_DESC', 'Categories block CATEGORY description');
define('_MI_MYMODULE_ARTICLES_BLOCK', 'Articles block');
define('_MI_MYMODULE_ARTICLES_BLOCK_DESC', 'Articles block description');
define('_MI_MYMODULE_ARTICLES_BLOCK_ARTICLE', 'Articles block  ARTICLE');
define('_MI_MYMODULE_ARTICLES_BLOCK_ARTICLE_DESC', 'Articles block  ARTICLE description');
define('_MI_MYMODULE_ARTICLES_BLOCK_LAST', 'Articles block last');
define('_MI_MYMODULE_ARTICLES_BLOCK_LAST_DESC', 'Articles block last description');
define('_MI_MYMODULE_ARTICLES_BLOCK_NEW', 'Articles block new');
define('_MI_MYMODULE_ARTICLES_BLOCK_NEW_DESC', 'Articles block new description');
define('_MI_MYMODULE_ARTICLES_BLOCK_HITS', 'Articles block hits');
define('_MI_MYMODULE_ARTICLES_BLOCK_HITS_DESC', 'Articles block hits description');
define('_MI_MYMODULE_ARTICLES_BLOCK_TOP', 'Articles block top');
define('_MI_MYMODULE_ARTICLES_BLOCK_TOP_DESC', 'Articles block top description');
define('_MI_MYMODULE_ARTICLES_BLOCK_RANDOM', 'Articles block random');
define('_MI_MYMODULE_ARTICLES_BLOCK_RANDOM_DESC', 'Articles block random description');
// Config
define('_MI_MYMODULE_EDITOR_DESCR', 'Editor');
define('_MI_MYMODULE_EDITOR_DESCR_DESC', 'Select the Editor Descr to use');
define('_MI_MYMODULE_KEYWORDS', 'Keywords');
define('_MI_MYMODULE_KEYWORDS_DESC', 'Insert here the keywords (separate by comma)');
define('_MI_MYMODULE_MAXSIZE', 'Max size');
define('_MI_MYMODULE_MAXSIZE_DESC', 'Set a number of max size uploads files in byte');
define('_MI_MYMODULE_MIMETYPES', 'Mime Types');
define('_MI_MYMODULE_MIMETYPES_DESC', 'Set the mime types selected');
define('_MI_MYMODULE_USE_TAG', 'Use TAG');
define('_MI_MYMODULE_USE_TAG_DESC', 'If you use tag module, check this option to yes');
define('_MI_MYMODULE_NUMB_COL', 'Number Columns');
define('_MI_MYMODULE_NUMB_COL_DESC', 'Number Columns to View.');
define('_MI_MYMODULE_DIVIDEBY', 'Divide By');
define('_MI_MYMODULE_DIVIDEBY_DESC', 'Divide by columns number.');
define('_MI_MYMODULE_TABLE_TYPE', 'Table Type');
define('_MI_MYMODULE_TABLE_TYPE_DESC', 'Table Type is the bootstrap html table.');
define('_MI_MYMODULE_PANEL_TYPE', 'Panel Type');
define('_MI_MYMODULE_PANEL_TYPE_DESC', 'Panel Type is the bootstrap html div.');
define('_MI_MYMODULE_IDPAYPAL', 'Paypal ID');
define('_MI_MYMODULE_IDPAYPAL_DESC', 'Insert here your PayPal ID for donactions.');
define('_MI_MYMODULE_ADVERTISE', 'Advertisement Code');
define('_MI_MYMODULE_ADVERTISE_DESC', 'Insert here the advertisement code');
define('_MI_MYMODULE_MAINTAINEDBY', 'Maintained By');
define('_MI_MYMODULE_MAINTAINEDBY_DESC', 'Allow url of support site or community');
define('_MI_MYMODULE_BOOKMARKS', 'Social Bookmarks');
define('_MI_MYMODULE_BOOKMARKS_DESC', 'Show Social Bookmarks in the single page');
define('_MI_MYMODULE_FACEBOOK_COMMENTS', 'Facebook comments');
define('_MI_MYMODULE_FACEBOOK_COMMENTS_DESC', 'Allow Facebook comments in the single page');
define('_MI_MYMODULE_DISQUS_COMMENTS', 'Disqus comments');
define('_MI_MYMODULE_DISQUS_COMMENTS_DESC', 'Allow Disqus comments in the single page');
// Notifications
define('_MI_MYMODULE_GLOBAL_NOTIFY', 'Global notify');
define('_MI_MYMODULE_GLOBAL_NOTIFY_DESC', 'Global notify desc');
define('_MI_MYMODULE_GLOBAL_MODIFY_NOTIFY', 'Global modify notify');
define('_MI_MYMODULE_GLOBAL_MODIFY_NOTIFY_CAPTION', 'Global modify notify caption');
define('_MI_MYMODULE_GLOBAL_MODIFY_NOTIFY_DESC', 'Global modify notify desc');
define('_MI_MYMODULE_GLOBAL_MODIFY_NOTIFY_SUBJECT', 'Global modify notify subject');
define('_MI_MYMODULE_GLOBAL_BROKEN_NOTIFY', 'Global broken notify');
define('_MI_MYMODULE_GLOBAL_BROKEN_NOTIFY_CAPTION', 'Global broken notify caption');
define('_MI_MYMODULE_GLOBAL_BROKEN_NOTIFY_DESC', 'Global broken notify desc');
define('_MI_MYMODULE_GLOBAL_BROKEN_NOTIFY_SUBJECT', 'Global broken notify subject');
define('_MI_MYMODULE_GLOBAL_SUBMIT_NOTIFY', 'Global submit notify');
define('_MI_MYMODULE_GLOBAL_SUBMIT_NOTIFY_CAPTION', 'Global submit notify caption');
define('_MI_MYMODULE_GLOBAL_SUBMIT_NOTIFY_DESC', 'Global submit notify desc');
define('_MI_MYMODULE_GLOBAL_SUBMIT_NOTIFY_SUBJECT', 'Global submit notify subject');
define('_MI_MYMODULE_GLOBAL_NEW_NOTIFY', 'Global new notify');
define('_MI_MYMODULE_GLOBAL_NEW_NOTIFY_CAPTION', 'Global new notify caption');
define('_MI_MYMODULE_GLOBAL_NEW_NOTIFY_DESC', 'Global new notify desc');
define('_MI_MYMODULE_GLOBAL_NEW_NOTIFY_SUBJECT', 'Global new notify subject');
define('_MI_MYMODULE_CATEGORY_NOTIFY', 'Category notify');
define('_MI_MYMODULE_CATEGORY_NOTIFY_DESC', 'Category notify desc');
define('_MI_MYMODULE_CATEGORY_NOTIFY_CAPTION', 'Category notify caption');
define('_MI_MYMODULE_CATEGORY_NOTIFY_SUBJECT', 'Category notify Subject');
define('_MI_MYMODULE_CATEGORY_SUBMIT_NOTIFY', 'Category submit notify');
define('_MI_MYMODULE_CATEGORY_SUBMIT_NOTIFY_CAPTION', 'Category submit notify caption');
define('_MI_MYMODULE_CATEGORY_SUBMIT_NOTIFY_DESC', 'Category submit notify desc');
define('_MI_MYMODULE_CATEGORY_SUBMIT_NOTIFY_SUBJECT', 'Category submit notify subject');
define('_MI_MYMODULE_ARTICLE_NOTIFY', 'Article notify');
define('_MI_MYMODULE_ARTICLE_NOTIFY_DESC', 'Article notify desc');
define('_MI_MYMODULE_ARTICLE_NOTIFY_CAPTION', 'Article notify caption');
define('_MI_MYMODULE_ARTICLE_NOTIFY_SUBJECT', 'Article notify subject');
define('_MI_MYMODULE_GLOBAL_NEW_CATEGORY_NOTIFY', 'Global newcategory notify');
define('_MI_MYMODULE_GLOBAL_NEW_CATEGORY_NOTIFY_CAPTION', 'Global newcategory notify caption');
define('_MI_MYMODULE_GLOBAL_NEW_CATEGORY_NOTIFY_DESC', 'Global newcategory notify desc');
define('_MI_MYMODULE_GLOBAL_NEW_CATEGORY_NOTIFY_SUBJECT', 'Global newcategory notify subject');
define('_MI_MYMODULE_GLOBAL_ARTICLE_MODIFY_NOTIFY', 'Global article modify notify');
define('_MI_MYMODULE_GLOBAL_ARTICLE_MODIFY_NOTIFY_CAPTION', 'Global article modify notify caption');
define('_MI_MYMODULE_GLOBAL_ARTICLE_MODIFY_NOTIFY_DESC', 'Global article modify notify desc');
define('_MI_MYMODULE_GLOBAL_ARTICLE_MODIFY_NOTIFY_SUBJECT', 'Global article modify notify subject');
define('_MI_MYMODULE_GLOBAL_ARTICLE_BROKEN_NOTIFY', 'Global article broken notify');
define('_MI_MYMODULE_GLOBAL_ARTICLE_BROKEN_NOTIFY_CAPTION', 'Global article broken notify caption');
define('_MI_MYMODULE_GLOBAL_ARTICLE_BROKEN_NOTIFY_DESC', 'Global article broken notify desc');
define('_MI_MYMODULE_GLOBAL_ARTICLE_BROKEN_NOTIFY_SUBJECT', 'Global article broken notify subject');
define('_MI_MYMODULE_GLOBAL_ARTICLE_SUBMIT_NOTIFY', 'Global article submit notify');
define('_MI_MYMODULE_GLOBAL_ARTICLE_SUBMIT_NOTIFY_CAPTION', 'Global article submit notify caption');
define('_MI_MYMODULE_GLOBAL_ARTICLE_SUBMIT_NOTIFY_DESC', 'Global article submit notify desc');
define('_MI_MYMODULE_GLOBAL_ARTICLE_SUBMIT_NOTIFY_SUBJECT', 'Global article submit notify subject');
define('_MI_MYMODULE_GLOBAL_NEW_ARTICLE_NOTIFY', 'Global new article notify');
define('_MI_MYMODULE_GLOBAL_NEW_ARTICLE_NOTIFY_CAPTION', 'Global new article notify caption');
define('_MI_MYMODULE_GLOBAL_NEW_ARTICLE_NOTIFY_DESC', 'Global new article notify desc');
define('_MI_MYMODULE_GLOBAL_NEW_ARTICLE_NOTIFY_SUBJECT', 'Global new article notify subject');
define('_MI_MYMODULE_CATEGORY_ARTICLE_SUBMIT_NOTIFY', 'Category article submit notify');
define('_MI_MYMODULE_CATEGORY_ARTICLE_SUBMIT_NOTIFY_CAPTION', 'Category article submit notify caption');
define('_MI_MYMODULE_CATEGORY_ARTICLE_SUBMIT_NOTIFY_DESC', 'Category article submit notify desc');
define('_MI_MYMODULE_CATEGORY_ARTICLE_SUBMIT_NOTIFY_SUBJECT', 'Category article submit notify subject');
define('_MI_MYMODULE_CATEGORY_NEW_ARTICLE_NOTIFY', 'Category new article notify');
define('_MI_MYMODULE_CATEGORY_NEW_ARTICLE_NOTIFY_CAPTION', 'Category new article notify caption');
define('_MI_MYMODULE_CATEGORY_NEW_ARTICLE_NOTIFY_DESC', 'Category new article notify desc');
define('_MI_MYMODULE_CATEGORY_NEW_ARTICLE_NOTIFY_SUBJECT', 'Category new article notify subject');
define('_MI_MYMODULE_APPROVE_NOTIFY', 'Article approve notify');
define('_MI_MYMODULE_APPROVE_NOTIFY_CAPTION', 'Article approve notify caption');
define('_MI_MYMODULE_APPROVE_NOTIFY_DESC', 'Article approve notify desc');
define('_MI_MYMODULE_APPROVE_NOTIFY_SUBJECT', 'Article approve notify subject');
// Permissions Groups
define('_MI_MYMODULE_GROUPS', 'Groups access');
define('_MI_MYMODULE_GROUPS_DESC', 'Select general access permission for groups.');
define('_MI_MYMODULE_ADMIN_GROUPS', 'Admin Group Permissions');
define('_MI_MYMODULE_ADMIN_GROUPS_DESC', 'Which groups have access to tools and permissions page');
define('_MI_MYMODULE_UPLOAD_GROUPS', 'Upload Group Permissions');
define('_MI_MYMODULE_UPLOAD_GROUPS_DESC', 'Which groups have permissions to upload files');
// ---------------- End ----------------
