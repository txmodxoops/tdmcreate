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
 * modules class
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         tdmcreate
 * @since           2.5.x
 * @author          TDM TEAM DEV MODULE
 * @version         $Id: modules.php 12209 2013-10-23 02:49:09Z beckmi $
 * @version         $Id: modules.php 13040 2015-04-25 15:12:12Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');
/*
*  @Class TDMCreateModules
*  @extends XoopsObject
*/

/**
 * Class TDMCreateModules
 */
class TDMCreateModules extends XoopsObject
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
        $this->initVar('mod_id', XOBJ_DTYPE_INT);
        $this->initVar('mod_name', XOBJ_DTYPE_TXTBOX);
        $this->initVar('mod_dirname', XOBJ_DTYPE_TXTBOX);
        $this->initVar('mod_version', XOBJ_DTYPE_TXTBOX);
        $this->initVar('mod_since', XOBJ_DTYPE_TXTBOX);
        $this->initVar('mod_min_php', XOBJ_DTYPE_TXTBOX);
        $this->initVar('mod_min_xoops', XOBJ_DTYPE_TXTBOX);
        $this->initVar('mod_min_admin', XOBJ_DTYPE_TXTBOX);
        $this->initVar('mod_min_mysql', XOBJ_DTYPE_TXTBOX);
        $this->initVar('mod_description', XOBJ_DTYPE_TXTAREA);
        $this->initVar('mod_author', XOBJ_DTYPE_TXTBOX);
        $this->initVar('mod_author_mail', XOBJ_DTYPE_TXTBOX);
        $this->initVar('mod_author_website_url', XOBJ_DTYPE_TXTBOX);
        $this->initVar('mod_author_website_name', XOBJ_DTYPE_TXTBOX);
        $this->initVar('mod_credits', XOBJ_DTYPE_TXTBOX);
        $this->initVar('mod_license', XOBJ_DTYPE_TXTBOX);
        $this->initVar('mod_release_info', XOBJ_DTYPE_TXTBOX);
        $this->initVar('mod_release_file', XOBJ_DTYPE_TXTBOX);
        $this->initVar('mod_manual', XOBJ_DTYPE_TXTBOX);
        $this->initVar('mod_manual_file', XOBJ_DTYPE_TXTBOX);
        $this->initVar('mod_image', XOBJ_DTYPE_TXTBOX);
        $this->initVar('mod_demo_site_url', XOBJ_DTYPE_TXTBOX);
        $this->initVar('mod_demo_site_name', XOBJ_DTYPE_TXTBOX);
        $this->initVar('mod_support_url', XOBJ_DTYPE_TXTBOX);
        $this->initVar('mod_support_name', XOBJ_DTYPE_TXTBOX);
        $this->initVar('mod_website_url', XOBJ_DTYPE_TXTBOX);
        $this->initVar('mod_website_name', XOBJ_DTYPE_TXTBOX);
        $this->initVar('mod_release', XOBJ_DTYPE_TXTBOX);
        $this->initVar('mod_status', XOBJ_DTYPE_TXTBOX);
        $this->initVar('mod_admin', XOBJ_DTYPE_INT);
        $this->initVar('mod_user', XOBJ_DTYPE_INT);
        $this->initVar('mod_blocks', XOBJ_DTYPE_INT);
        $this->initVar('mod_search', XOBJ_DTYPE_INT);
        $this->initVar('mod_comments', XOBJ_DTYPE_INT);
        $this->initVar('mod_notifications', XOBJ_DTYPE_INT);
        $this->initVar('mod_permissions', XOBJ_DTYPE_INT);
        $this->initVar('mod_inroot_copy', XOBJ_DTYPE_INT);
        $this->initVar('mod_donations', XOBJ_DTYPE_TXTBOX);
        $this->initVar('mod_subversion', XOBJ_DTYPE_TXTBOX);
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
     * @return TDMCreateModules
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
		$set = array();
		$settings = $this->tdmcreate->getHandler('settings')->getObjects(null);
		foreach ($settings as $setting) 
		{
            $set['name']          		= $setting->getVar('set_name');
			$set['dirname']       		= $setting->getVar('set_dirname');
			$set['version']       		= $setting->getVar('set_version');
			$set['since']         		= $setting->getVar('set_since');
			$set['min_php']       		= $setting->getVar('set_min_php');
			$set['min_xoops']     		= $setting->getVar('set_min_xoops');
			$set['min_admin']     		= $setting->getVar('set_min_admin');
			$set['min_mysql']     		= $setting->getVar('set_min_mysql');
			$set['description']   		= $setting->getVar('set_description');
			$set['author']        		= $setting->getVar('set_author');
			$set['license']       		= $setting->getVar('set_license');
			$set['admin']         		= $setting->getVar('set_admin');
			$set['user']          		= $setting->getVar('set_user');
			$set['blocks']        		= $setting->getVar('set_blocks');
			$set['search']        		= $setting->getVar('set_search');
			$set['comments']      		= $setting->getVar('set_comments');
			$set['notifications'] 		= $setting->getVar('set_notifications');
			$set['permissions']   		= $setting->getVar('set_permissions');
			$set['inroot']       		= $setting->getVar('set_inroot_copy');
			$set['image']         		= $setting->getVar('set_image');			
			$set['author_mail']         = $setting->getVar('set_author_mail');
			$set['author_website_url']  = $setting->getVar('set_author_website_url');
			$set['author_website_name'] = $setting->getVar('set_author_website_name');
			$set['credits']     		= $setting->getVar('set_credits');
			$set['release_info']     	= $setting->getVar('set_release_info');
			$set['release_file']     	= $setting->getVar('set_release_file');
			$set['manual']   			= $setting->getVar('set_manual');
			$set['manual_file']         = $setting->getVar('set_manual_file');
			$set['demo_site_url']       = $setting->getVar('set_demo_site_url');
			$set['demo_site_name']      = $setting->getVar('set_demo_site_name');
			$set['support_url']         = $setting->getVar('set_support_url');
			$set['support_name']        = $setting->getVar('set_support_name');
			$set['website_url']        	= $setting->getVar('set_website_url');
			$set['website_name']      	= $setting->getVar('set_website_name');
			$set['release'] 			= $setting->getVar('set_release');
			$set['status']  			= $setting->getVar('set_status');
			$set['donations']        	= $setting->getVar('set_donations');
			$set['subversion']         	= $setting->getVar('set_subversion');
        }
        //
        $isNew = $this->isNew();
        $title = $isNew ? sprintf(_AM_TDMCREATE_MODULE_NEW) : sprintf(_AM_TDMCREATE_MODULE_EDIT);
        //
        include_once(XOOPS_ROOT_PATH . '/class/xoopsformloader.php');
        //
        $form = new XoopsThemeForm($title, 'moduleform', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        //
        $form->insertBreak('<div class="center"><b>' . _AM_TDMCREATE_MODULE_IMPORTANT . '</b></div>', 'head');        
        //
		$modName = $isNew ? $set['name'] : $this->getVar('mod_name');
        $modName = new XoopsFormText(_AM_TDMCREATE_MODULE_NAME, 'mod_name', 50, 255, $modName);
        $modName->setDescription(_AM_TDMCREATE_MODULE_NAME_DESC);
        $form->addElement($modName, true);
        //
        $modDirname = $isNew ? $set['dirname'] : $this->getVar('mod_dirname');
        $modDirname = new XoopsFormText(_AM_TDMCREATE_MODULE_DIRNAME, 'mod_dirname', 25, 255, $modDirname);
        $modDirname->setDescription(_AM_TDMCREATE_MODULE_DIRNAME_DESC);
        $form->addElement($modDirname, true);
        //
		$modVersion = $isNew ? $set['version'] : $this->getVar('mod_version');
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_MODULE_VERSION, 'mod_version', 10, 25, $modVersion), true);
        //
		$modSince = $isNew ? $set['since'] : $this->getVar('mod_since');
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_MODULE_SINCE, 'mod_since', 10, 25, $modSince), true);
        //
		$modMinPhp = $isNew ? $set['min_php'] : $this->getVar('mod_min_php');
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_MODULE_MIN_PHP, 'mod_min_php', 10, 25, $modMinPhp), true);
        //
		$modMinXoops = $isNew ? $set['min_xoops'] : $this->getVar('mod_min_xoops');
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_MODULE_MIN_XOOPS, 'mod_min_xoops', 10, 25, $modMinXoops), true);
        //
		$modMinAdmin = $isNew ? $set['min_admin'] : $this->getVar('mod_min_admin');
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_MODULE_MIN_ADMIN, 'mod_min_admin', 10, 25, $modMinAdmin), true);
        //
		$modMinMysql = $isNew ? $set['min_mysql'] : $this->getVar('mod_min_mysql');
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_MODULE_MIN_MYSQL, 'mod_min_mysql', 10, 25, $modMinMysql), true);
        // Name description
        $editor_configs           = array();
        $editor_configs['name']   = 'mod_description';
        $editor_configs['value']  = $isNew ? $set['description'] : $this->getVar('mod_description', 'e');
        $editor_configs['rows']   = 5;
        $editor_configs['cols']   = 100;
        $editor_configs['width']  = '50%';
        $editor_configs['height'] = '100px';
        $editor_configs['editor'] = $this->tdmcreate->getConfig('tdmcreate_editor');
        $form->addElement(new XoopsFormEditor(_AM_TDMCREATE_MODULE_DESCRIPTION, 'mod_description', $editor_configs), true);
        // Author
        $modAuthor = $isNew ? $set['author'] : $this->getVar('mod_author');
		$form->addElement(new XoopsFormText(_AM_TDMCREATE_MODULE_AUTHOR, 'mod_author', 50, 255, $modAuthor), true);
        $modLicense = $isNew ? $set['license'] : $this->getVar('mod_license');
		$form->addElement(new XoopsFormText(_AM_TDMCREATE_MODULE_LICENSE, 'mod_license', 50, 255, $modLicense), true);
        //
        $options_tray = new XoopsFormElementTray(_OPTIONS, '<br />');
        //
        $mod_checkbox_all = new XoopsFormCheckBox('', "modulebox", 1);
        $mod_checkbox_all->addOption('allbox', _AM_TDMCREATE_MODULE_ALL);
        $mod_checkbox_all->setExtra(" onclick='xoopsCheckAll(\"moduleform\", \"modulebox\");' ");
        $mod_checkbox_all->setClass('xo-checkall');
        $options_tray->addElement($mod_checkbox_all);
        //
        $mod_admin       = $isNew ? $set['admin'] : $this->getVar('mod_admin');
        $check_mod_admin = new XoopsFormCheckBox(' ', 'mod_admin', $mod_admin);
        $check_mod_admin->addOption(1, _AM_TDMCREATE_MODULE_ADMIN);
        $options_tray->addElement($check_mod_admin);
        //
        $mod_user       = $isNew ? $set['user'] : $this->getVar('mod_user');
        $check_mod_user = new XoopsFormCheckBox(' ', 'mod_user', $mod_user);
        $check_mod_user->addOption(1, _AM_TDMCREATE_MODULE_USER);
        $options_tray->addElement($check_mod_user);
        //
        $mod_blocks       = $isNew ? $set['blocks'] : $this->getVar('mod_blocks');
        $check_mod_blocks = new XoopsFormCheckBox(' ', 'mod_blocks', $mod_blocks);
        $check_mod_blocks->addOption(1, _AM_TDMCREATE_MODULE_BLOCKS);
        $options_tray->addElement($check_mod_blocks);
        //
        $mod_search       = $isNew ? $set['search'] : $this->getVar('mod_search');
        $check_mod_search = new XoopsFormCheckBox(' ', 'mod_search', $mod_search);
        $check_mod_search->addOption(1, _AM_TDMCREATE_MODULE_SEARCH);
        $options_tray->addElement($check_mod_search);
        //
        $mod_comments       = $isNew ? $set['comments'] : $this->getVar('mod_comments');
        $check_mod_comments = new XoopsFormCheckBox(' ', 'mod_comments', $mod_comments);
        $check_mod_comments->addOption(1, _AM_TDMCREATE_MODULE_COMMENTS);
        $options_tray->addElement($check_mod_comments);
        //
        $mod_notifications       = $isNew ? $set['notifications'] : $this->getVar('mod_notifications');
        $check_mod_notifications = new XoopsFormCheckBox(' ', 'mod_notifications', $mod_notifications);
        $check_mod_notifications->addOption(1, _AM_TDMCREATE_MODULE_NOTIFICATIONS);
        $options_tray->addElement($check_mod_notifications);
        //
        $mod_permissions       = $isNew ? $set['permissions'] : $this->getVar('mod_permissions');
        $check_mod_permissions = new XoopsFormCheckBox(' ', 'mod_permissions', $mod_permissions);
        $check_mod_permissions->addOption(1, _AM_TDMCREATE_MODULE_PERMISSIONS);
        $options_tray->addElement($check_mod_permissions);
        //
        $mod_inroot_copy       = $isNew ? $set['inroot'] : $this->getVar('mod_inroot_copy');
        $check_mod_inroot_copy = new XoopsFormCheckBox(' ', 'mod_inroot_copy', $mod_inroot_copy);
        $check_mod_inroot_copy->addOption(1, _AM_TDMCREATE_MODULE_INROOT_MODULES_COPY);
        $options_tray->addElement($check_mod_inroot_copy);
        //
        $form->addElement($options_tray);
        //
        $modImage = $this->getVar('mod_image');
        $modImage = $modImage ? $modImage : $set['image'];
        //
        $uploadirectory  = 'uploads/' . $GLOBALS['xoopsModule']->dirname() . '/images/modules';
        $imgtray         = new XoopsFormElementTray(_AM_TDMCREATE_MODULE_IMAGE, '<br />');
        $imgpath         = sprintf(_AM_TDMCREATE_FORMIMAGE_PATH, './' . strtolower($uploadirectory) . '/');
        $imageselect     = new XoopsFormSelect($imgpath, 'mod_image', $modImage);
        $mod_image_array = XoopsLists::getImgListAsArray(TDMC_UPLOAD_IMGMOD_PATH);
        foreach ($mod_image_array as $image) {
            $imageselect->addOption("{$image}", $image);
        }
        $imageselect->setExtra("onchange='showImgSelected(\"image3\", \"mod_image\", \"" . $uploadirectory . "\", \"\", \"" . XOOPS_URL . "\")'");
        $imgtray->addElement($imageselect);
        $imgtray->addElement(new XoopsFormLabel('', "<br /><img src='" . TDMC_UPLOAD_IMGMOD_URL . "/" . $modImage . "' name='image3' id='image3' alt='' /><br />"));
        //
        $fileseltray = new XoopsFormElementTray('', '<br />');
        $fileseltray->addElement(new XoopsFormFile(_AM_TDMCREATE_FORMUPLOAD, 'attachedfile', $this->tdmcreate->getConfig('maxsize')));
        $fileseltray->addElement(new XoopsFormLabel(''));
        $imgtray->addElement($fileseltray);
        $form->addElement($imgtray);
        //---------- START LOGO GENERATOR -----------------
        $tables_img = $this->getVar('table_image') ?: 'about.png';
        $iconsdir   = '/Frameworks/moduleclasses/icons/32';
        if (is_dir(XOOPS_ROOT_PATH . $iconsdir)) {
            $uploadirectory = $iconsdir;
            $imgpath        = sprintf(_AM_TDMCREATE_FORMIMAGE_PATH, ".{$iconsdir}/");
        } else {
            $uploadirectory = "/uploads/" . $GLOBALS['xoopsModule']->dirname() . "/images/tables";
            $imgpath        = sprintf(_AM_TDMCREATE_FORMIMAGE_PATH, "./uploads/" . $GLOBALS['xoopsModule']->dirname() . "/images/tables");
        }
        $createLogoTray   = new XoopsFormElementTray(_AM_TDMCREATE_MODULE_CREATENEWLOGO, '<br />');
        $iconSelect       = new XoopsFormSelect($imgpath, 'tables_img', $tables_img, 8);
        $tables_img_array = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH . $uploadirectory);
        foreach ($tables_img_array as $image) {
            $iconSelect->addOption("{$image}", $image);
        }
        $iconSelect->setExtra("onchange='showImgSelected2(\"image4\", \"tables_img\", \"" . $uploadirectory . "\", \"\", \"" . XOOPS_URL . "\")'");
        $createLogoTray->addElement($iconSelect);
        $createLogoTray->addElement(new XoopsFormLabel('', "<br /><img src='" . XOOPS_URL . "/" . $uploadirectory . "/" . $tables_img . "' name='image4' id='image4' alt='' />"));
        // Create preview and submit buttons
        $buttonLogoGenerator4 = new XoopsFormButton('', 'button4', _AM_TDMCREATE_MODULE_CREATENEWLOGO, 'button');
        $buttonLogoGenerator4->setExtra("onclick='createNewModuleLogo(\"" . TDMC_URL . "\")'");
        $createLogoTray->addElement($buttonLogoGenerator4);
        //
        $form->addElement($createLogoTray);
        //------------ END LOGO GENERATOR --------------------
        //
        $form->insertBreak('<div class="center"><b>' . _AM_TDMCREATE_MODULE_NOTIMPORTANT . '</b></div>', 'head');
        //
		$modAuthorMail = $isNew ? $set['author_mail'] : $this->getVar('mod_author_mail');
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_MODULE_AUTHOR_MAIL, 'mod_author_mail', 50, 255, $modAuthorMail));
        //
		$modAuthorWebsiteUrl = $isNew ? $set['author_website_url'] : $this->getVar('mod_author_website_url');
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_MODULE_AUTHOR_WEBSITE_URL, 'mod_author_website_url', 50, 255, $modAuthorWebsiteUrl));
        //
		$modAuthorWebsiteName = $isNew ? $set['author_website_name'] : $this->getVar('mod_author_website_name');
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_MODULE_AUTHOR_WEBSITE_NAME, 'mod_author_website_name', 50, 255, $modAuthorWebsiteName));
        //
		$modCredits = $isNew ? $set['credits'] : $this->getVar('mod_credits');
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_MODULE_CREDITS, 'mod_credits', 50, 255, $modCredits));
        //
		$modReleaseInfo = $isNew ? $set['release_info'] : $this->getVar('mod_release_info');
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_MODULE_RELEASE_INFO, 'mod_release_info', 50, 255, $modReleaseInfo));
        //
		$modReleaseFile = $isNew ? $set['release_file'] : $this->getVar('mod_release_file');
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_MODULE_RELEASE_FILE, 'mod_release_file', 50, 255, $modReleaseFile));
        //
		$modManual = $isNew ? $set['manual'] : $this->getVar('mod_manual');
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_MODULE_MANUAL, 'mod_manual', 50, 255, $modManual));
        //
		$modManualFile = $isNew ? $set['manual_file'] : $this->getVar('mod_manual_file');
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_MODULE_MANUAL_FILE, 'mod_manual_file', 50, 255, $modManualFile));
        //
		$modDemoSiteUrl = $isNew ? $set['demo_site_url'] : $this->getVar('mod_demo_site_url');
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_MODULE_DEMO_SITE_URL, 'mod_demo_site_url', 50, 255, $modDemoSiteUrl));
        //
		$modDemoSiteName = $isNew ? $set['demo_site_name'] : $this->getVar('mod_demo_site_name');
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_MODULE_DEMO_SITE_NAME, 'mod_demo_site_name', 50, 255, $modDemoSiteName));
        //
		$modSupportUrl = $isNew ? $set['support_url'] : $this->getVar('mod_support_url');
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_MODULE_SUPPORT_URL, 'mod_support_url', 50, 255, $modSupportUrl));
        //
		$modSupportName = $isNew ? $set['support_name'] : $this->getVar('mod_support_name');
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_MODULE_SUPPORT_NAME, 'mod_support_name', 50, 255, $modSupportName));
        //
		$modWebsiteUrl = $isNew ? $set['website_url'] : $this->getVar('mod_website_url');
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_MODULE_WEBSITE_URL, 'mod_website_url', 50, 255, $modWebsiteUrl));
        //
		$modWebsiteName = $isNew ? $set['website_name'] : $this->getVar('mod_website_name');
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_MODULE_WEBSITE_NAME, 'mod_website_name', 50, 255, $modWebsiteName));
        //
		$modRelease = $isNew ? $set['release'] : $this->getVar('mod_release');
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_MODULE_RELEASE, 'mod_release', 50, 255, $modRelease));
        //
		$modStatus = $isNew ? $set['status'] : $this->getVar('mod_status');
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_MODULE_STATUS, 'mod_status', 50, 255, $modStatus));
        //
		$modDonations = $isNew ? $set['donations'] : $this->getVar('mod_donations');
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_MODULE_PAYPAL_BUTTON, 'mod_donations', 50, 255, $modDonations));
        //
		$modSubversion = $isNew ? $set['subversion'] : $this->getVar('mod_subversion');
        $form->addElement(new XoopsFormText(_AM_TDMCREATE_MODULE_SUBVERSION, 'mod_subversion', 50, 255, $modSubversion));
        //
        $form->addElement(new XoopsFormHidden('op', 'save'));
        $form->addElement(new XoopsFormButton(_REQUIRED . ' <span class="red bold">*</span>', 'submit', _SUBMIT, 'submit'));

        return $form;
    }

    /*
    *  @private static function createLogo
    *  @param mixed $logoIcon
    *  @param string $moduleDirname
    */
    /**
     * @param $logoIcon
     * @param $moduleDirname
     * @return bool|string
     */
    private static function createLogo($logoIcon, $moduleDirname)
    {
        if (!extension_loaded('gd')) {
            return false;
        } else {
            $requiredFunctions = array('imagecreatefrompng', 'imagefttext', 'imagecopy', 'imagepng', 'imagedestroy', 'imagecolorallocate');
            foreach ($requiredFunctions as $func) {
                if (!function_exists($func)) {
                    return false;
                }
            }
        }
        if (!file_exists($imageBase = TDMC_IMAGE_LOGOS_PATH . '/empty.png') ||
            !file_exists($font = TDMC_FONTS_PATH . '/VeraBd.ttf') ||
            !file_exists($iconFile = XOOPS_ICONS32_PATH . '/' . basename($logoIcon))
        ) {
            return false;
        }
        $imageModule = imagecreatefrompng($imageBase);
        $imageIcon   = imagecreatefrompng($iconFile);
        // Write text
        $textColor   = imagecolorallocate($imageModule, 0, 0, 0);
        $spaceBorder = (92 - strlen($moduleDirname) * 7.5) / 2;
        imagefttext($imageModule, 8.5, 0, $spaceBorder, 45, $textColor, $font, ucfirst($moduleDirname), array());
        imagecopy($imageModule, $imageIcon, 29, 2, 0, 0, 32, 32);
        $logoImg = '/' . $moduleDirname . '_logo.png';
        imagepng($imageModule, TDMC_UPLOAD_IMGMOD_PATH . $logoImg);
        imagedestroy($imageModule);
        imagedestroy($imageIcon);

        return TDMC_UPLOAD_IMGMOD_URL . $logoImg;
    }
	
	/**
     * Get Values
     */
	public function getValues($keys = null, $format = null, $maxDepth = null)
    {        
		$ret = parent::getValues($keys, $format, $maxDepth);
		// Values
		$ret['id'] 			  = $this->getVar('mod_id');
		$ret['name']          = $this->getVar('mod_name');
		$ret['version']       = $this->getVar('mod_version');
		$ret['image']         = $this->getVar('mod_image');
		$ret['release']       = $this->getVar('mod_release');
		$ret['status']        = $this->getVar('mod_status');
		$ret['admin']         = $this->getVar('mod_admin');
		$ret['user']          = $this->getVar('mod_user');
		$ret['blocks']        = $this->getVar('mod_blocks');
		$ret['search']        = $this->getVar('mod_search');
		$ret['comments']      = $this->getVar('mod_comments');
		$ret['notifications'] = $this->getVar('mod_notifications');
		$ret['permissions']   = $this->getVar('mod_permissions');
        
		return $ret;
    }
}

/*
*  @Class TDMCreateModulesHandler
*  @extends XoopsPersistableObjectHandler
*/

/**
 * Class TDMCreateModulesHandler
 */
class TDMCreateModulesHandler extends XoopsPersistableObjectHandler
{
    /*
    *  @public function constructor class
    *  @param mixed $db
    */
    /**
     * @param null|object $db
     */
    public function __construct(&$db)
    {
        parent::__construct($db, 'tdmcreate_modules', 'tdmcreatemodules', 'mod_id', 'mod_name');
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
     * retrieve a field
     *
     * @param  int $i field id
     * @param null $fields
     * @return mixed reference to the <a href='psi_element://TDMCreateFields'>TDMCreateFields</a> object
     *                object
     */
    public function &get($i = null, $fields = null)
    {
        return parent::get($i, $fields);
    }

    /**
     * get inserted id
     *
     * @param null
     * @return integer reference to the {@link TDMCreateTables} object
     */
    public function &getInsertId()
    {
        return $this->db->getInsertId();
    }

    /**
     * insert a new field in the database
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
     * Get Count Modules
     */
    public function getCountModules($start = 0, $limit = 0, $sort = 'mod_id ASC, mod_name', $order = 'ASC')
    {
        $criteria = new CriteriaCompo();
        $criteria->setSort($sort);
        $criteria->setOrder($order);
        $criteria->setStart($start);
        $criteria->setLimit($limit);
		return parent::getCount($criteria);
    }

	/**
     * Get All Modules
     */
	public function getAllModules($start = 0, $limit = 0, $sort = 'mod_id ASC, mod_name', $order = 'ASC')
    {
        $criteria = new CriteriaCompo();
        $criteria->setSort($sort);
        $criteria->setOrder($order);
        $criteria->setStart($start);
        $criteria->setLimit($limit);
        return parent::getAll($criteria);
    }
}
