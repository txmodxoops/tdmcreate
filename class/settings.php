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
 * settings class.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.7
 *
 * @author          TDM TEAM DEV MODULE
 *
 * @version         $Id: settings.php 13070 2015-05-19 12:24:20Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');
include __DIR__.'/autoload.php';
/*
*  @Class TDMCreateSettings
*  @extends XoopsObject
*/

/**
 * Class TDMCreateSettings.
 */
class TDMCreateSettings extends XoopsObject
{
    /**
     * Instance of TDMCreate class.
     *
     * @var mixed
     */
    private $tdmcreate;

    /**
     * Options.
     */
    public $options = array(
        'admin',
        'user',
        'blocks',
        'search',
        'comments',
        'notifications',
        'permissions',
        'inroot_copy',
    );

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
        $this->initVar('set_type', XOBJ_DTYPE_TXTBOX);
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
     *
     * @return XoopsThemeForm
     */
    public function getFormSettings($action = false)
    {
        //
        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }
        //
        $isNew = $this->isNew();
        $title = $isNew ? sprintf(_AM_TDMCREATE_SETTING_NEW) : sprintf(_AM_TDMCREATE_SETTING_EDIT);
        //
        include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
        //
        $form = new XoopsThemeForm($title, 'settingform', $action, 'post');
        $form->setExtra('enctype="multipart/form-data"');
        //
        $form->addElement(new XoopsFormHidden('set_id', $this->getVar('set_id')));
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_NAME, 'set_name', 50, 255, $this->getVar('set_name')));
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_DIRNAME, 'set_dirname', 25, 255, $this->getVar('set_dirname')));
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_VERSION, 'set_version', 10, 25, $this->getVar('set_version')));
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_SINCE, 'set_since', 10, 25, $this->getVar('set_since')));
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_MIN_PHP, 'set_min_php', 10, 25, $this->getVar('set_min_php')));
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_MIN_XOOPS, 'set_min_xoops', 10, 25, $this->getVar('set_min_xoops')));
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_MIN_ADMIN, 'set_min_admin', 10, 25, $this->getVar('set_min_admin')));
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_MIN_MYSQL, 'set_min_mysql', 10, 25, $this->getVar('set_min_mysql')));
        $form->addElement(new XoopsFormTextArea(_AM_TDMCREATE_SETTING_DESCRIPTION, 'set_description', $this->getVar('set_description'), 4, 25));
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_AUTHOR, 'set_author', 50, 255, $this->getVar('set_author')));
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_LICENSE, 'set_license', 50, 255, $this->getVar('set_license')));
        // Check All Settings Options
        $optionsTray = new XoopsFormElementTray(_OPTIONS, '<br />');
        $checkAllOptions = new XoopsFormCheckBox('', 'settingbox', 1);
        $checkAllOptions->addOption('allbox', _AM_TDMCREATE_SETTING_ALL);
        $checkAllOptions->setExtra(' onclick="xoopsCheckAll(\'settingform\', \'settingbox\');" ');
        $checkAllOptions->setClass('xo-checkall');
        $optionsTray->addElement($checkAllOptions);
        // Options
        $settingOption = $this->getOptionsSettings();
        $checkbox = new XoopsFormCheckbox(' ', 'setting_option', $settingOption, '<br />');
        $checkbox->setDescription(_AM_TDMCREATE_OPTIONS_DESC);
        foreach ($this->options as $option) {
            $checkbox->addOption($option, self::getDefinedLanguage('_AM_TDMCREATE_SETTING_'.strtoupper($option)));
        }
        $optionsTray->addElement($checkbox);
        //
        $form->addElement($optionsTray);
        //
        $modImage = $this->getVar('set_image');
        $modImage = $modImage ? $modImage : $set['image'];
        //
        $uploadirectory = 'uploads/'.$GLOBALS['xoopsModule']->dirname().'/images/modules';
        $imgtray = new XoopsFormElementTray(_AM_TDMCREATE_SETTING_IMAGE, '<br />');
        $imgpath = sprintf(_AM_TDMCREATE_FORMIMAGE_PATH, './'.strtolower($uploadirectory).'/');
        $imageselect = new XoopsFormSelect($imgpath, 'set_image', $modImage);
        $modImage_array = XoopsLists::getImgListAsArray(TDMC_UPLOAD_IMGMOD_PATH);
        foreach ($modImage_array as $image) {
            $imageselect->addOption("{$image}", $image);
        }
        $imageselect->setExtra("onchange='showImgSelected(\"image3\", \"set_image\", \"".$uploadirectory.'", "", "'.XOOPS_URL."\")'");
        $imgtray->addElement($imageselect);
        $imgtray->addElement(new XoopsFormLabel('', "<br /><img src='".TDMC_UPLOAD_IMGMOD_URL.'/'.$modImage."' name='image3' id='image3' alt='' /><br />"));
        //
        $fileseltray = new XoopsFormElementTray('', '<br />');
        $fileseltray->addElement(new XoopsFormFile(_AM_TDMCREATE_FORMUPLOAD, 'attachedfile', $this->tdmcreate->getConfig('maxsize')));
        $fileseltray->addElement(new XoopsFormLabel(''));
        $imgtray->addElement($fileseltray);
        $form->addElement($imgtray);
        //        
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_AUTHOR_MAIL, 'set_author_mail', 50, 255, $this->getVar('set_author_mail')));
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_AUTHOR_WEBSITE_URL, 'set_author_website_url', 50, 255, $this->getVar('set_author_website_url')));
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_AUTHOR_WEBSITE_NAME, 'set_author_website_name', 50, 255, $this->getVar('set_author_website_name')));
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_CREDITS, 'set_credits', 50, 255, $this->getVar('set_credits')));
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_RELEASE_INFO, 'set_release_info', 50, 255, $this->getVar('set_release_info')));
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_RELEASE_FILE, 'set_release_file', 50, 255, $this->getVar('set_release_file')));
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_MANUAL, 'set_manual', 50, 255, $this->getVar('set_manual')));
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_MANUAL_FILE, 'set_manual_file', 50, 255, $this->getVar('set_manual_file')));
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_DEMO_SITE_URL, 'set_demo_site_url', 50, 255, $this->getVar('set_demo_site_url')));
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_DEMO_SITE_NAME, 'set_demo_site_name', 50, 255, $this->getVar('set_demo_site_name')));
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_SUPPORT_URL, 'set_support_url', 50, 255, $this->getVar('set_support_url')));
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_SUPPORT_NAME, 'set_support_name', 50, 255, $this->getVar('set_support_name')));
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_WEBSITE_URL, 'set_website_url', 50, 255, $this->getVar('set_website_url')));
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_WEBSITE_NAME, 'set_website_name', 50, 255, $this->getVar('set_website_name')));
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_RELEASE, 'set_release', 50, 255, $this->getVar('set_release')));
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_STATUS, 'set_status', 50, 255, $this->getVar('set_status')));
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_PAYPAL_BUTTON, 'set_donations', 50, 255, $this->getVar('set_donations')));
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_SETTING_SUBVERSION, 'set_subversion', 50, 255, $this->getVar('set_subversion')));
        //
        $buttonTray = new XoopsFormElementTray(_REQUIRED.' <sup class="red bold">*</sup>', '');
        $buttonTray->addElement(new XoopsFormHidden('op', 'save'));
        $buttonTray->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        $form->addElement($buttonTray);

        return $form;
    }

    /**
     * Get Values.
     */
    public function getValuesSettings($keys = null, $format = null, $maxDepth = null)
    {
        $ret = parent::getValues($keys, $format, $maxDepth);
        // Values
        $ret['id'] = $this->getVar('set_id');
        $ret['name'] = $this->getVar('set_name');
        $ret['version'] = $this->getVar('set_version');
        $ret['image'] = $this->getVar('set_image');
        $ret['release'] = $this->getVar('set_release');
        $ret['status'] = $this->getVar('set_status');
        $ret['type'] = $this->getVar('set_type');

        return $ret;
    }

    /**
     * Get Options.
     */
    /**
     * @param $key
     *
     * @return string
     */
    private function getOptionsSettings()
    {
        $retSet = array();
        foreach ($this->options as $option) {
            if ($this->getVar('set_'.$option) == 1) {
                array_push($retSet, $option);
            }
        }

        return $retSet;
    }

    /**
     * Get Defined Language.
     */
    /**
     * @param $lang
     *
     * @return string
     */
    private static function getDefinedLanguage($lang)
    {
        if (defined($lang)) {
            return constant($lang);
        }

        return $lang;
    }
}
/**
 * Class TDMCreateSettingsHandler.
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
     * @param bool $isNew
     *
     * @return object
     */
    public function &create($isNew = true)
    {
        return parent::create($isNew);
    }

    /**
     * retrieve a field.
     *
     * @param int  $i      field id
     * @param null $fields
     *
     * @return mixed reference to the <a href='psi_element://TDMCreateSettings'>TDMCreateSettings</a> object
     *               object
     */
    public function &get($i = null, $fields = null)
    {
        return parent::get($i, $fields);
    }

    /**
     * get inserted id.
     *
     * @param null
     *
     * @return int reference to the {@link TDMCreateTables} object
     */
    public function &getInsertId()
    {
        return $this->db->getInsertId();
    }

    /**
     * insert a new field in the database.
     *
     * @param object $field reference to the {@link TDMCreateFields} object
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

    /**
     * Get Count Settings.
     */
    public function getCountSettings($start = 0, $limit = 0, $sort = 'set_id ASC, set_name', $order = 'ASC')
    {
        $criteriaCountSettings = new CriteriaCompo();
        $criteriaCountSettings = $this->getSettingsCriteria($criteriaCountSettings, $start, $limit, $sort, $order);

        return $this->getCount($criteriaCountSettings);
    }

    /**
     * Get All Settings.
     */
    public function getAllSettings($start = 0, $limit = 0, $sort = 'set_id ASC, set_name', $order = 'ASC')
    {
        $criteriaAllSettings = new CriteriaCompo();
        $criteriaAllSettings = $this->getSettingsCriteria($criteriaAllSettings, $start, $limit, $sort, $order);

        return $this->getAll($criteriaAllSettings);
    }

    /**
     * Get Settings Criteria.
     */
    private function getSettingsCriteria($criteriaSettings, $start, $limit, $sort, $order)
    {
        $criteriaSettings->setStart($start);
        $criteriaSettings->setLimit($limit);
        $criteriaSettings->setSort($sort);
        $criteriaSettings->setOrder($order);

        return $criteriaSettings;
    }
}
