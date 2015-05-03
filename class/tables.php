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
 * tdmcreate module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         tdmcreate
 * @since           2.5.5
 * @author          Txmod Xoops <support@txmodxoops.org>
 * @version         $Id: 1.91 tables.php 11297 2013-03-24 10:58:10Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/*
*  @Class TDMCreateTables
*  @extends XoopsObject
*/

/**
 * Class TDMCreateTables
 */
class TDMCreateTables extends XoopsObject
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
        $this->initVar('table_id', XOBJ_DTYPE_INT);
        $this->initVar('table_mid', XOBJ_DTYPE_INT);
        $this->initVar('table_category', XOBJ_DTYPE_INT);
        $this->initVar('table_name', XOBJ_DTYPE_TXTBOX);
		$this->initVar('table_solename', XOBJ_DTYPE_TXTBOX);
        $this->initVar('table_fieldname', XOBJ_DTYPE_TXTBOX);
        $this->initVar('table_nbfields', XOBJ_DTYPE_INT);
        $this->initVar('table_order', XOBJ_DTYPE_INT);
        $this->initVar('table_image', XOBJ_DTYPE_TXTBOX);
        $this->initVar('table_autoincrement', XOBJ_DTYPE_INT);
        $this->initVar('table_blocks', XOBJ_DTYPE_INT);
        $this->initVar('table_admin', XOBJ_DTYPE_INT);
        $this->initVar('table_user', XOBJ_DTYPE_INT);
        $this->initVar('table_submenu', XOBJ_DTYPE_INT);
        $this->initVar('table_submit', XOBJ_DTYPE_INT);
        $this->initVar('table_tag', XOBJ_DTYPE_INT);
        $this->initVar('table_broken', XOBJ_DTYPE_INT);
        $this->initVar('table_search', XOBJ_DTYPE_INT);
        $this->initVar('table_comments', XOBJ_DTYPE_INT);
        $this->initVar('table_notifications', XOBJ_DTYPE_INT);
        $this->initVar('table_permissions', XOBJ_DTYPE_INT);
        $this->initVar('table_rate', XOBJ_DTYPE_INT);
        $this->initVar('table_print', XOBJ_DTYPE_INT);
        $this->initVar('table_pdf', XOBJ_DTYPE_INT);
        $this->initVar('table_rss', XOBJ_DTYPE_INT);
        $this->initVar('table_single', XOBJ_DTYPE_INT);
        $this->initVar('table_visit', XOBJ_DTYPE_INT);
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
     * @return TDMCreateTables
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
    *  @static function getForm
    *  @param mixed $action
    */
    /**
     * @param bool $action
     * @return XoopsThemeForm
     */
    public function getForm($action = false)
    {
        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }

        $isNew      = $this->isNew();
        $table_name = $this->getVar('table_name');
        $table_mid  = $this->getVar('table_mid');
        $title      = $isNew ? sprintf(_AM_TDMCREATE_TABLE_NEW) : sprintf(_AM_TDMCREATE_TABLE_EDIT);

        xoops_load('xoopsformloader');
        $form = new XoopsThemeForm($title, 'tableform', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        //
        $modules     = $this->tdmcreate->getHandler('modules')->getObjects(null);
        $mods_select = new XoopsFormSelect(_AM_TDMCREATE_TABLE_MODULES, 'table_mid', $table_mid);
        $mods_select->addOption('', _AM_TDMCREATE_TABLE_MODSELOPT);
        foreach ($modules as $mod) {
            //$mods_select->addOptionArray();
            $mods_select->addOption($mod->getVar('mod_id'), $mod->getVar('mod_name'));
        }
        $form->addElement($mods_select, true);
        //
        $table_name_field = new XoopsFormText(_AM_TDMCREATE_TABLE_NAME, 'table_name', 40, 150, $table_name);
        $table_name_field->setDescription(_AM_TDMCREATE_TABLE_NAME_DESC);
        $form->addElement($table_name_field, true);
		//
        $table_solename_field = new XoopsFormText(_AM_TDMCREATE_TABLE_SOLENAME, 'table_solename', 40, 150, $this->getVar('table_solename'));
        $table_solename_field->setDescription(_AM_TDMCREATE_TABLE_SOLENAME_DESC);
        $form->addElement($table_solename_field, true);
        //
        $tablesHandler =& $this->tdmcreate->getHandler('tables');
        $criteria      = new CriteriaCompo(new Criteria('table_category', 0), 'AND');
        $criteria->add(new Criteria('table_mid', $table_mid), 'AND');
        $criteria->add(new Criteria('table_name', $table_name));
        $table_category = $tablesHandler->getCount($criteria);
        unset($criteria);
        //if ( ($table_category == 0) || $isNew ) {
        $radio_category = $isNew ? 0 : $this->getVar('table_category');
        $category       = new XoopsFormRadioYN(_AM_TDMCREATE_TABLE_CATEGORY, 'table_category', $radio_category);
        $category->setDescription(_AM_TDMCREATE_TABLE_CATEGORY_DESC);
        $form->addElement($category);
        //}
        //
        $table_fieldname = new XoopsFormText(_AM_TDMCREATE_TABLE_FIELDNAME, 'table_fieldname', 30, 50, $this->getVar('table_fieldname'));
        $table_fieldname->setDescription(_AM_TDMCREATE_TABLE_FIELDNAME_DESC);
        $form->addElement($table_fieldname);
        //
        $table_nbfield = new XoopsFormText(_AM_TDMCREATE_TABLE_NBFIELDS, 'table_nbfields', 10, 25, $this->getVar('table_nbfields'));
        $table_nbfield->setDescription(_AM_TDMCREATE_TABLE_NBFIELDS_DESC);
        $form->addElement($table_nbfield, true);
        //
        if (!$isNew) {
            $table_order = new XoopsFormText(_AM_TDMCREATE_TABLE_ORDER, 'table_order', 5, 10, $this->getVar('table_order'));
            $table_order->setDescription(_AM_TDMCREATE_TABLE_ORDER_DESC);
            $form->addElement($table_order, true);
        }
        //
        $get_table_image = $this->getVar('table_image');
        $table_image     = $get_table_image ?: 'blank.gif';
        $iconsdir        = '/Frameworks/moduleclasses/icons/32';
        $uploads_dir     = '/uploads/tdmcreate/images/tables';
        $iconsdirectory  = is_dir(XOOPS_ROOT_PATH . $iconsdir) ? $iconsdir : $uploads_dir;
        //
        $imgtray1     = new XoopsFormElementTray(_AM_TDMCREATE_TABLE_IMAGE, '<br />');
        $imgpath1     = sprintf(_AM_TDMCREATE_FORMIMAGE_PATH, ".{$iconsdirectory}/");
        $imageselect1 = new XoopsFormSelect($imgpath1, 'table_image', $table_image, 10);
        $image_array1 = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH . $iconsdirectory);
        foreach ($image_array1 as $image1) {
            $imageselect1->addOption("{$image1}", $image1);
        }
        $imageselect1->setExtra("onchange='showImgSelected(\"image1\", \"table_image\", \"" . $iconsdirectory . "\", \"\", \"" . XOOPS_URL . "\")'");
        $imgtray1->addElement($imageselect1, false);
        $imgtray1->addElement(new XoopsFormLabel('', "<br /><img src='" . XOOPS_URL . "/" . $iconsdirectory . "/" . $table_image . "' name='image1' id='image1' alt='' />"));
        $fileseltray1 = new XoopsFormElementTray('', '<br />');
        $fileseltray1->addElement(new XoopsFormFile(_AM_TDMCREATE_FORMUPLOAD, 'attachedfile', $this->tdmcreate->getConfig('maxsize')));
        $fileseltray1->addElement(new XoopsFormLabel(''));
        $imgtray1->addElement($fileseltray1);
        $imgtray1->setDescription(_AM_TDMCREATE_TABLE_IMAGE_DESC);
        $form->addElement($imgtray1);
        //
        $table_autoincrement       = $this->isNew() ? 1 : $this->getVar('table_autoincrement');
        $check_table_autoincrement = new XoopsFormRadioYN(_AM_TDMCREATE_TABLE_AUTO_INCREMENT, 'table_autoincrement', $table_autoincrement);
        $check_table_autoincrement->setDescription(_AM_TDMCREATE_TABLE_AUTO_INCREMENT_DESC);
        //$check_table_autoincrement->addOption(1, _AM_TDMCREATE_TABLE_AUTO_INCREMENT_OPTION);
        $form->addElement($check_table_autoincrement);
        //
        $options_tray = new XoopsFormElementTray(_OPTIONS, '<br />');
        //
        $table_checkbox_all = new XoopsFormCheckBox('', "tablebox", 1);
        $table_checkbox_all->addOption('allbox', _AM_TDMCREATE_TABLE_ALL);
        $table_checkbox_all->setExtra(" onclick='xoopsCheckAll(\"tableform\", \"tablebox\");' ");
        $table_checkbox_all->setClass('xo-checkall');
        $options_tray->addElement($table_checkbox_all);
        //
        $table_blocks       = $isNew ? 0 : $this->getVar('table_blocks');
        $check_table_blocks = new XoopsFormCheckBox(' ', 'table_blocks', $table_blocks);
        $check_table_blocks->addOption(1, _AM_TDMCREATE_TABLE_BLOCKS);
        $options_tray->addElement($check_table_blocks);
        //
        $table_admin       = $isNew ? 0 : $this->getVar('table_admin');
        $check_table_admin = new XoopsFormCheckBox(' ', 'table_admin', $table_admin);
        $check_table_admin->addOption(1, _AM_TDMCREATE_TABLE_ADMIN);
        $options_tray->addElement($check_table_admin);
        //
        $table_user       = $isNew ? 0 : $this->getVar('table_user');
        $check_table_user = new XoopsFormCheckBox(' ', 'table_user', $table_user);
        $check_table_user->addOption(1, _AM_TDMCREATE_TABLE_USER);
        $options_tray->addElement($check_table_user);
        //
        $table_submenu       = $isNew ? 0 : $this->getVar('table_submenu');
        $check_table_submenu = new XoopsFormCheckBox(' ', 'table_submenu', $table_submenu);
        $check_table_submenu->addOption(1, _AM_TDMCREATE_TABLE_SUBMENU);
        $options_tray->addElement($check_table_submenu);
        //
        $table_submit       = $isNew ? 0 : $this->getVar('table_submit');
        $check_table_submit = new XoopsFormCheckBox(' ', 'table_submit', $table_submit);
        $check_table_submit->addOption(1, _AM_TDMCREATE_TABLE_SUBMIT);
        $options_tray->addElement($check_table_submit);
        //
        $table_tag       = $isNew ? 0 : $this->getVar('table_tag');
        $check_table_tag = new XoopsFormCheckBox(' ', 'table_tag', $table_tag);
        $check_table_tag->addOption(1, _AM_TDMCREATE_TABLE_TAG);
        $options_tray->addElement($check_table_tag);
        //
        $table_broken       = $isNew ? 0 : $this->getVar('table_broken');
        $check_table_broken = new XoopsFormCheckBox(' ', 'table_broken', $table_broken);
        $check_table_broken->addOption(1, _AM_TDMCREATE_TABLE_BROKEN);
        $options_tray->addElement($check_table_broken);
        //
        $criteria = new CriteriaCompo(new Criteria('table_search', ($isNew ? 0 : 1)), 'AND');
        $criteria->add(new Criteria('table_mid', $table_mid), 'AND');
        $criteria->add(new Criteria('table_name', $table_name));
        $table_comments = $tablesHandler->getCount($criteria);
        unset($criteria);
        //if( ($table_comments == 0) || $isNew ) {
        $table_search       = $isNew ? 0 : $this->getVar('table_search');
        $check_table_search = new XoopsFormCheckBox(' ', 'table_search', $table_search);
        $check_table_search->addOption(1, _AM_TDMCREATE_TABLE_SEARCH);
        $options_tray->addElement($check_table_search);
        //}
        //
        $criteria = new CriteriaCompo(new Criteria('table_comments', ($isNew ? 0 : 1)), 'AND');
        $criteria->add(new Criteria('table_mid', $table_mid), 'AND');
        $criteria->add(new Criteria('table_name', $table_name));
        $table_comments = $tablesHandler->getCount($criteria);
        unset($criteria);
        //if ( ($table_comments == 0) || $isNew ) {
        $table_comments       = $isNew ? 0 : $this->getVar('table_comments');
        $check_table_comments = new XoopsFormCheckBox(' ', 'table_comments', $table_comments);
        $check_table_comments->addOption(1, _AM_TDMCREATE_TABLE_COMMENTS);
        $options_tray->addElement($check_table_comments);
        //}
        //
        $table_notifications       = $isNew ? 0 : $this->getVar('table_notifications');
        $check_table_notifications = new XoopsFormCheckBox(' ', 'table_notifications', $table_notifications);
        $check_table_notifications->addOption(1, _AM_TDMCREATE_TABLE_NOTIFICATIONS);
        $options_tray->addElement($check_table_notifications);
        //
        $criteria = new CriteriaCompo(new Criteria('table_permissions', ($isNew ? 0 : 1)), 'AND');
        $criteria->add(new Criteria('table_mid', $table_mid), 'AND');
        $criteria->add(new Criteria('table_name', $table_name));
        $table_permissions = $tablesHandler->getCount($criteria);
        unset($criteria);
        //if ( ($table_permissions == 0) && ($table_category == 0) || $isNew ) {
        $table_permissions       = $isNew ? 0 : $this->getVar('table_permissions');
        $check_table_permissions = new XoopsFormCheckBox(' ', 'table_permissions', $table_permissions);
        $check_table_permissions->addOption(1, _AM_TDMCREATE_TABLE_PERMISSIONS);
        $options_tray->addElement($check_table_permissions);
        //}
        //
        $table_rate       = $isNew ? 0 : $this->getVar('table_rate');
        $check_table_rate = new XoopsFormCheckBox(' ', 'table_rate', $table_rate);
        $check_table_rate->addOption(1, _AM_TDMCREATE_TABLE_RATE);
        $options_tray->addElement($check_table_rate);
        //
        $table_print       = $isNew ? 0 : $this->getVar('table_print');
        $check_table_print = new XoopsFormCheckBox(' ', 'table_print', $table_print);
        $check_table_print->addOption(1, _AM_TDMCREATE_TABLE_PRINT);
        $options_tray->addElement($check_table_print);
        //
        $table_pdf       = $isNew ? 0 : $this->getVar('table_pdf');
        $check_table_pdf = new XoopsFormCheckBox(' ', 'table_pdf', $table_pdf);
        $check_table_pdf->addOption(1, _AM_TDMCREATE_TABLE_PDF);
        $options_tray->addElement($check_table_pdf);
        //
        $table_rss       = $isNew ? 0 : $this->getVar('table_rss');
        $check_table_rss = new XoopsFormCheckBox(' ', 'table_rss', $table_rss);
        $check_table_rss->addOption(1, _AM_TDMCREATE_TABLE_RSS);
        $options_tray->addElement($check_table_rss);
        //
        $table_single       = $isNew ? 0 : $this->getVar('table_single');
        $check_table_single = new XoopsFormCheckBox(' ', 'table_single', $table_single);
        $check_table_single->addOption(1, _AM_TDMCREATE_TABLE_SINGLE);
        $options_tray->addElement($check_table_single);
        //
        $table_visit       = $isNew ? 0 : $this->getVar('table_visit');
        $check_table_visit = new XoopsFormCheckBox(' ', 'table_visit', $table_visit);
        $check_table_visit->addOption(1, _AM_TDMCREATE_TABLE_VISIT);
        $options_tray->addElement($check_table_visit);
        //
        $options_tray->setDescription(_AM_TDMCREATE_TABLE_OPTIONS_CHECKS_DESC);
        //
        $form->addElement($options_tray);
        //
        $form->addElement(new XoopsFormHidden('op', 'save'));
        $form->addElement(new XoopsFormHidden('table_id', ($isNew ? 0 : $this->getVar('table_id'))));
        $form->addElement(new XoopsFormButton(_REQUIRED . ' <sup class="red bold">*</sup>', 'submit', _SUBMIT, 'submit'));

        return $form;
    }
}

/*
*  @Class TDMCreateTablesHandler
*  @extends XoopsPersistableObjectHandler
*/

/**
 * Class TDMCreateTablesHandler
 */
class TDMCreateTablesHandler extends XoopsPersistableObjectHandler
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
        parent::__construct($db, 'tdmcreate_tables', 'tdmcreatetables', 'table_id', 'table_name');
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
}
