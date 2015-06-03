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
 * settings class
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         tdmcreate
 * @since           2.5.7
 * @author          TDM TEAM DEV MODULE
 * @version         $Id: settings.php 13070 2015-05-19 12:24:20Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');
include __DIR__ . '/autoload.php';
/*
*  @Class TDMCreateSettings
*  @extends XoopsObject
*/

/**
 * Class TDMCreateSettings
 */
class TDMCreateSettings extends XoopsObject
{
    /**
     * Instance of TDMCreate class
     *
     * @var mixed
     */
    private $tdmcreate;

    /*
    *  @public function constructor class
    *  @param null
    */
    /**
     *
     */
    public function __construct()
    {
        $this->tdmcreate = TDMCreateHelper::getInstance();
		$this->initVar('set_id', XOBJ_DTYPE_INT);
        $this->initVar('set_name', XOBJ_DTYPE_TXTBOX, $this->tdmcreate->getConfig('name'));
        $this->initVar('set_dirname', XOBJ_DTYPE_TXTBOX, $this->tdmcreate->getConfig('dirname'));
        $this->initVar('set_version', XOBJ_DTYPE_TXTBOX, $this->tdmcreate->getConfig('version'));
        $this->initVar('set_since', XOBJ_DTYPE_TXTBOX, $this->tdmcreate->getConfig('since'));
        $this->initVar('set_min_php', XOBJ_DTYPE_TXTBOX, $this->tdmcreate->getConfig('min_php'));
        $this->initVar('set_min_xoops', XOBJ_DTYPE_TXTBOX, $this->tdmcreate->getConfig('min_xoops'));
        $this->initVar('set_min_admin', XOBJ_DTYPE_TXTBOX, $this->tdmcreate->getConfig('min_admin'));
        $this->initVar('set_min_mysql', XOBJ_DTYPE_TXTBOX, $this->tdmcreate->getConfig('min_mysql'));
        $this->initVar('set_description', XOBJ_DTYPE_TXTAREA, $this->tdmcreate->getConfig('description'));
        $this->initVar('set_author', XOBJ_DTYPE_TXTBOX, $this->tdmcreate->getConfig('author'));
        $this->initVar('set_author_mail', XOBJ_DTYPE_TXTBOX, $this->tdmcreate->getConfig('author_email'));
        $this->initVar('set_author_website_url', XOBJ_DTYPE_TXTBOX, $this->tdmcreate->getConfig('author_website_url'));
        $this->initVar('set_author_website_name', XOBJ_DTYPE_TXTBOX, $this->tdmcreate->getConfig('author_website_name'));
        $this->initVar('set_credits', XOBJ_DTYPE_TXTBOX, $this->tdmcreate->getConfig('credits'));
        $this->initVar('set_license', XOBJ_DTYPE_TXTBOX, $this->tdmcreate->getConfig('license'));
        $this->initVar('set_release_info', XOBJ_DTYPE_TXTBOX, $this->tdmcreate->getConfig('release_info'));
        $this->initVar('set_release_file', XOBJ_DTYPE_TXTBOX, $this->tdmcreate->getConfig('release_file'));
        $this->initVar('set_manual', XOBJ_DTYPE_TXTBOX, $this->tdmcreate->getConfig('manual'));
        $this->initVar('set_manual_file', XOBJ_DTYPE_TXTBOX, $this->tdmcreate->getConfig('manual_file'));
        $this->initVar('set_image', XOBJ_DTYPE_TXTBOX, $this->tdmcreate->getConfig('image'));
        $this->initVar('set_demo_site_url', XOBJ_DTYPE_TXTBOX, $this->tdmcreate->getConfig('demo_site_url'));
        $this->initVar('set_demo_site_name', XOBJ_DTYPE_TXTBOX, $this->tdmcreate->getConfig('demo_site_name'));
        $this->initVar('set_support_url', XOBJ_DTYPE_TXTBOX, $this->tdmcreate->getConfig('support_url'));
        $this->initVar('set_support_name', XOBJ_DTYPE_TXTBOX, $this->tdmcreate->getConfig('support_name'));
        $this->initVar('set_website_url', XOBJ_DTYPE_TXTBOX, $this->tdmcreate->getConfig('website_url'));
        $this->initVar('set_website_name', XOBJ_DTYPE_TXTBOX, $this->tdmcreate->getConfig('website_name'));
        $this->initVar('set_release', XOBJ_DTYPE_TXTBOX, $this->tdmcreate->getConfig('release_date'));
        $this->initVar('set_status', XOBJ_DTYPE_TXTBOX, $this->tdmcreate->getConfig('status'));
        $this->initVar('set_admin', XOBJ_DTYPE_INT, $this->tdmcreate->getConfig('display_admin'));
        $this->initVar('set_user', XOBJ_DTYPE_INT, $this->tdmcreate->getConfig('display_user'));
        $this->initVar('set_blocks', XOBJ_DTYPE_INT, $this->tdmcreate->getConfig('active_blocks'));
        $this->initVar('set_search', XOBJ_DTYPE_INT, $this->tdmcreate->getConfig('active_search'));
        $this->initVar('set_comments', XOBJ_DTYPE_INT, $this->tdmcreate->getConfig('active_comments'));
        $this->initVar('set_notifications', XOBJ_DTYPE_INT, $this->tdmcreate->getConfig('active_notifications'));
        $this->initVar('set_permissions', XOBJ_DTYPE_INT, $this->tdmcreate->getConfig('active_permissions'));
        $this->initVar('set_inroot_copy', XOBJ_DTYPE_INT, $this->tdmcreate->getConfig('inroot_copy'));
        $this->initVar('set_donations', XOBJ_DTYPE_TXTBOX, $this->tdmcreate->getConfig('donations'));
        $this->initVar('set_subversion', XOBJ_DTYPE_TXTBOX, $this->tdmcreate->getConfig('subversion'));
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
     * @return TDMCreateSettings
     */
    public static function &getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /*
    *  @public function getForm
    *  @param mixed $action
    */
    /**
     * @param bool $action
     * @return XoopsThemeForm
     */
    public function getForm($action = false)
    {
        //
        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }
		$isNew = $this->isNew();
		
        //
        include_once(XOOPS_ROOT_PATH . '/class/xoopsformloader.php');
        //
        $form = new XoopsThemeForm(_AM_TDMCREATE_SETTING_EDIT, 'settingform', $action, 'post');		
        $form->setExtra('enctype="multipart/form-data"');
		
		$tabTray = new TDMCreateFormTabTray('', 'uniqueid', xoops_getModuleOption('jquery_theme', 'system'));
		//
		$tab1 = new TDMCreateFormTab(_AM_TDMCREATE_IMPORTANT, 'important');
		//
		$tab1->addElement(new XoopsFormHidden('set_id', $this->getVar('set_id')));
        $tab1->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_NAME, 'set_name', 50, 255, $this->getVar('set_name')));
        $tab1->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_DIRNAME, 'set_dirname', 25, 255, $this->getVar('set_dirname')));
        $tab1->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_VERSION, 'set_version', 10, 25, $this->getVar('set_version')));
        $tab1->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_SINCE, 'set_since', 10, 25, $this->getVar('set_since')));
        $tab1->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_MIN_PHP, 'set_min_php', 10, 25, $this->getVar('set_min_php')));
        $tab1->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_MIN_XOOPS, 'set_min_xoops', 10, 25, $this->getVar('set_min_xoops')));
        $tab1->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_MIN_ADMIN, 'set_min_admin', 10, 25, $this->getVar('set_min_admin')));
        $tab1->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_MIN_MYSQL, 'set_min_mysql', 10, 25, $this->getVar('set_min_mysql')));
		$tab1->addElement(new XoopsFormTextArea(_AM_TDMCREATE_SETTING_DESCRIPTION, 'set_description', $this->getVar('set_description'), 4, 25));
        $tab1->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_AUTHOR, 'set_author', 50, 255, $this->getVar('set_author')));
        $tab1->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_LICENSE, 'set_license', 50, 255, $this->getVar('set_license')));
		$tabTray->addElement($tab1);
		//
		$tab2 = new TDMCreateFormTab(_AM_TDMCREATE_OPTIONS_CHECK, 'options_check');
			$options_tray = new XoopsFormElementTray(_OPTIONS, '<br />');
			$mod_checkbox_all = new XoopsFormCheckBox('', "settingbox", 1);
			$mod_checkbox_all->addOption('allbox', _AM_TDMCREATE_SETTING_ALL);
			$mod_checkbox_all->setExtra(" onclick='xoopsCheckAll(\"settingform\", \"settingbox\");' ");
			$mod_checkbox_all->setClass('xo-checkall');
			$options_tray->addElement($mod_checkbox_all);
			$mod_admin       = $isNew ? $this->tdmcreate->getConfig('display_admin') : $this->getVar('set_admin');
			$check_mod_admin = new XoopsFormCheckBox(' ', 'set_admin', $mod_admin);
			$check_mod_admin->addOption(1, _AM_TDMCREATE_SETTING_ADMIN);
			$options_tray->addElement($check_mod_admin);
			$mod_user       = $isNew ? $this->tdmcreate->getConfig('display_user') : $this->getVar('set_user');
			$check_mod_user = new XoopsFormCheckBox(' ', 'set_user', $mod_user);
			$check_mod_user->addOption(1, _AM_TDMCREATE_SETTING_USER);
			$options_tray->addElement($check_mod_user);
			$mod_blocks       = $isNew ? $this->tdmcreate->getConfig('active_blocks') : $this->getVar('set_blocks');
			$check_mod_blocks = new XoopsFormCheckBox(' ', 'set_blocks', $mod_blocks);
			$check_mod_blocks->addOption(1, _AM_TDMCREATE_SETTING_BLOCKS);
			$options_tray->addElement($check_mod_blocks);
			$mod_search       = $isNew ? $this->tdmcreate->getConfig('active_search') : $this->getVar('set_search');
			$check_mod_search = new XoopsFormCheckBox(' ', 'set_search', $mod_search);
			$check_mod_search->addOption(1, _AM_TDMCREATE_SETTING_SEARCH);
			$options_tray->addElement($check_mod_search);
			$mod_comments       = $isNew ? $this->tdmcreate->getConfig('active_comments') : $this->getVar('set_comments');
			$check_mod_comments = new XoopsFormCheckBox(' ', 'set_comments', $mod_comments);
			$check_mod_comments->addOption(1, _AM_TDMCREATE_SETTING_COMMENTS);
			$options_tray->addElement($check_mod_comments);
			$mod_notifications       = $isNew ? $this->tdmcreate->getConfig('active_notifications') : $this->getVar('set_notifications');
			$check_mod_notifications = new XoopsFormCheckBox(' ', 'set_notifications', $mod_notifications);
			$check_mod_notifications->addOption(1, _AM_TDMCREATE_SETTING_NOTIFICATIONS);
			$options_tray->addElement($check_mod_notifications);
			$mod_permissions       = $isNew ? $this->tdmcreate->getConfig('active_permissions') : $this->getVar('set_permissions');
			$check_mod_permissions = new XoopsFormCheckBox(' ', 'set_permissions', $mod_permissions);
			$check_mod_permissions->addOption(1, _AM_TDMCREATE_SETTING_PERMISSIONS);
			$options_tray->addElement($check_mod_permissions);
			$mod_inroot_copy       = $isNew ? $this->tdmcreate->getConfig('active_permissions') : $this->getVar('set_inroot_copy');
			$check_mod_inroot_copy = new XoopsFormCheckBox(' ', 'set_inroot_copy', $mod_inroot_copy);
			$check_mod_inroot_copy->addOption(1, _AM_TDMCREATE_SETTING_INROOT_MODULES_COPY);
			$options_tray->addElement($check_mod_inroot_copy);
		$tab2->addElement($options_tray);
		$tabTray->addElement($tab2);
		//
		$tab3 = new TDMCreateFormTab(_AM_TDMCREATE_NOT_IMPORTANT, 'not_important');
		
		$tab3->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_IMAGE, 'set_image', 30, 100, $this->getVar('set_image')));
        $tab3->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_AUTHOR_MAIL, 'set_author_mail', 50, 255, $this->getVar('set_author_mail')));
        $tab3->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_AUTHOR_WEBSITE_URL, 'set_author_website_url', 50, 255, $this->getVar('set_author_website_url')));
        $tab3->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_AUTHOR_WEBSITE_NAME, 'set_author_website_name', 50, 255, $this->getVar('set_author_website_name')));
        $tab3->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_CREDITS, 'set_credits', 50, 255, $this->getVar('set_credits')));
        $tab3->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_RELEASE_INFO, 'set_release_info', 50, 255, $this->getVar('set_release_info')));
        $tab3->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_RELEASE_FILE, 'set_release_file', 50, 255, $this->getVar('set_release_file')));
        $tab3->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_MANUAL, 'set_manual', 50, 255, $this->getVar('set_manual')));
        $tab3->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_MANUAL_FILE, 'set_manual_file', 50, 255, $this->getVar('set_manual_file')));
        $tab3->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_DEMO_SITE_URL, 'set_demo_site_url', 50, 255, $this->getVar('set_demo_site_url')));
        $tab3->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_DEMO_SITE_NAME, 'set_demo_site_name', 50, 255, $this->getVar('set_demo_site_name')));
        $tab3->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_SUPPORT_URL, 'set_support_url', 50, 255, $this->getVar('set_support_url')));
        $tab3->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_SUPPORT_NAME, 'set_support_name', 50, 255, $this->getVar('set_support_name')));
        $tab3->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_WEBSITE_URL, 'set_website_url', 50, 255, $this->getVar('set_website_url')));
        $tab3->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_WEBSITE_NAME, 'set_website_name', 50, 255, $this->getVar('set_website_name')));
        $tab3->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_RELEASE, 'set_release', 50, 255, $this->getVar('set_release')));
        $tab3->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_STATUS, 'set_status', 50, 255, $this->getVar('set_status')));
        $tab3->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_PAYPAL_BUTTON, 'set_donations', 50, 255, $this->getVar('set_donations')));
        $tab3->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_SUBVERSION, 'set_subversion', 50, 255, $this->getVar('set_subversion')));        
		
		$tab3->addElement(new XoopsFormHidden('op', 'save'));
        $tab3->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
		$tabTray->addElement($tab3);
		$form->addElement($tabTray);
        return $form;
    }    
}
/**
 * Class TDMCreateSettingsHandler
 */
class TDMCreateSettingsHandler extends XoopsPersistableObjectHandler
{
    /**
     * @param null|object $db
     */
    public function __construct(&$db)
    {
        parent::__construct($db, 'tdmcreate_settings', 'tdmcreatesettings', 'set_id', 'set_name');
    }    

    /**
     * retrieve a field
     *
     * @param $i
     * @param $fields
     * @return mixed reference to the object
     */
    public function &get($i = null, $fields = null)
    {
        return parent::get($i, $fields);
    }
    

    /**
     * insert a new field in the database
     *
     * @param $field object
     * @param bool   $force
     *
     * @return bool FALSE if failed, TRUE if already present and unchanged or successful
     */
    public function &insert(&$field, $force = false)
    {
        if (!parent::insert($field, $force)) {
            return false;
        }

        return true;
    }
}
