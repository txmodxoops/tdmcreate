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
 * tdmcreate module.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.7
 *
 * @author          Txmod Xoops <webmaster@txmodxoops.org> - <http://www.txmodxoops.org/>
 *
 * @version         $Id: 1.91 tables.php 11297 2013-03-24 10:58:10Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');
include __DIR__.'/autoload.php';
/*
*  @Class TDMCreateTables
*  @extends XoopsObject
*/

/**
 * Class TDMCreateTables.
 */
class TDMCreateTables extends XoopsObject
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
        'install',
        'index',
        'blocks',
        'admin',
        'user',
        'submenu',
        'submit',
        'tag',
        'broken',
        'search',
        'comments',
        'notifications',
        'permissions',
        'rate',
        'print',
        'pdf',
        'rss',
        'single',
        'visit',
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
        $this->initVar('table_install', XOBJ_DTYPE_INT);
        $this->initVar('table_index', XOBJ_DTYPE_INT);
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
     *
     * @return XoopsThemeForm
     */
    public function getForm($action = false)
    {
        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }

        $isNew = $this->isNew();
        $tableName = $this->getVar('table_name');
        $tableMid = $this->getVar('table_mid');
        $title = $isNew ? sprintf(_AM_TDMCREATE_TABLE_NEW) : sprintf(_AM_TDMCREATE_TABLE_EDIT);

        xoops_load('XoopsFormLoader');
        $form = new XoopsThemeForm($title, 'tableform', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        //
        $tabTray = new TDMCreateFormTabTray('', 'uniqueid', xoops_getModuleOption('jquery_theme', 'system'));
        //
        $tab1 = new TDMCreateFormTab(_AM_TDMCREATE_IMPORTANT, 'important');
        //
        $modules = $this->tdmcreate->getHandler('modules')->getObjects(null);
        $modulesSelect = new XoopsFormSelect(_AM_TDMCREATE_TABLE_MODULES, 'table_mid', $tableMid);
        $modulesSelect->addOption('', _AM_TDMCREATE_TABLE_MODSELOPT);
        foreach ($modules as $mod) {
            //$modulesSelect->addOptionArray();
            $modulesSelect->addOption($mod->getVar('mod_id'), $mod->getVar('mod_name'));
        }
        $tab1->addElement($modulesSelect, true);
        //
        $tableNameText = new XoopsFormText(_AM_TDMCREATE_TABLE_NAME, 'table_name', 40, 150, $tableName);
        $tableNameText->setDescription(_AM_TDMCREATE_TABLE_NAME_DESC);
        $tab1->addElement($tableNameText, true);
        //
        $tableSoleNameText = new XoopsFormText(_AM_TDMCREATE_TABLE_SOLENAME, 'table_solename', 40, 150, $this->getVar('table_solename'));
        $tableSoleNameText->setDescription(_AM_TDMCREATE_TABLE_SOLENAME_DESC);
        $tab1->addElement($tableSoleNameText, true);
        //
        $tablesHandler = &$this->tdmcreate->getHandler('tables');
        $criteria = new CriteriaCompo(new Criteria('table_category', 0), 'AND');
        $criteria->add(new Criteria('table_mid', $tableMid), 'AND');
        $criteria->add(new Criteria('table_name', $tableName));
        $tableCategory = $tablesHandler->getCount($criteria);
        unset($criteria);
        //if ( ($tableCategory == 0) || $isNew ) {
        $radioCategory = $isNew ? 0 : $this->getVar('table_category');
        $category = new XoopsFormRadioYN(_AM_TDMCREATE_TABLE_CATEGORY, 'table_category', $radioCategory);
        $category->setDescription(_AM_TDMCREATE_TABLE_CATEGORY_DESC);
        $tab1->addElement($category);
        //}
        //
        $tableFieldname = new XoopsFormText(_AM_TDMCREATE_TABLE_FIELDNAME, 'table_fieldname', 30, 50, $this->getVar('table_fieldname'));
        $tableFieldname->setDescription(_AM_TDMCREATE_TABLE_FIELDNAME_DESC);
        $tab1->addElement($tableFieldname);
        //
        $tableNumbFileds = new XoopsFormText(_AM_TDMCREATE_TABLE_NBFIELDS, 'table_nbfields', 10, 25, $this->getVar('table_nbfields'));
        $tableNumbFileds->setDescription(_AM_TDMCREATE_TABLE_NBFIELDS_DESC);
        $tab1->addElement($tableNumbFileds, true);
        //
        if (!$isNew) {
            $tableOrder = new XoopsFormText(_AM_TDMCREATE_TABLE_ORDER, 'table_order', 5, 10, $this->getVar('table_order'));
            $tableOrder->setDescription(_AM_TDMCREATE_TABLE_ORDER_DESC);
            $tab1->addElement($tableOrder, true);
        }
        //
        $getTableImage = $this->getVar('table_image');
        $tableImage = $getTableImage ?: 'blank.gif';
        $icons32Directory = '/Frameworks/moduleclasses/icons/32';
        $uploadsDirectory = '/uploads/tdmcreate/images/tables';
        $iconsDirectory = is_dir(XOOPS_ROOT_PATH.$icons32Directory) ? $icons32Directory : $uploadsDirectory;
        //
        $imgtray1 = new XoopsFormElementTray(_AM_TDMCREATE_TABLE_IMAGE, '<br />');
        $imgpath1 = sprintf(_AM_TDMCREATE_FORMIMAGE_PATH, ".{$iconsDirectory}/");
        $imageSelect1 = new XoopsFormSelect($imgpath1, 'table_image', $tableImage, 10);
        $imageArray1 = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH.$iconsDirectory);
        foreach ($imageArray1 as $image1) {
            $imageSelect1->addOption("{$image1}", $image1);
        }
        $imageSelect1->setExtra("onchange='showImgSelected(\"image1\", \"table_image\", \"".$iconsDirectory.'", "", "'.XOOPS_URL."\")'");
        $imgtray1->addElement($imageSelect1, false);
        $imgtray1->addElement(new XoopsFormLabel('', "<br /><img src='".XOOPS_URL.'/'.$iconsDirectory.'/'.$tableImage."' name='image1' id='image1' alt='' />"));
        $fileseltray1 = new XoopsFormElementTray('', '<br />');
        $fileseltray1->addElement(new XoopsFormFile(_AM_TDMCREATE_FORMUPLOAD, 'attachedfile', $this->tdmcreate->getConfig('maxsize')));
        $fileseltray1->addElement(new XoopsFormLabel(''));
        $imgtray1->addElement($fileseltray1);
        $imgtray1->setDescription(_AM_TDMCREATE_TABLE_IMAGE_DESC);
        $tab1->addElement($imgtray1);
        //
        $tableAutoincrement = $this->isNew() ? 1 : $this->getVar('table_autoincrement');
        $checkTableAutoincrement = new XoopsFormRadioYN(_AM_TDMCREATE_TABLE_AUTO_INCREMENT, 'table_autoincrement', $tableAutoincrement);
        $checkTableAutoincrement->setDescription(_AM_TDMCREATE_TABLE_AUTO_INCREMENT_DESC);
        $tab1->addElement($checkTableAutoincrement);
        $tabTray->addElement($tab1);
        //
        $tab2 = new TDMCreateFormTab(_AM_TDMCREATE_OPTIONS_CHECK, 'options_check');
        //
        $optionsTray = new XoopsFormElementTray(_OPTIONS, '<br />');
        //
        $tableCheckAll = new XoopsFormCheckBox('', 'tablebox', 1);
        $tableCheckAll->addOption('allbox', _AM_TDMCREATE_TABLE_ALL);
        $tableCheckAll->setExtra(' onclick="xoopsCheckAll(\'tableform\', \'tablebox\');" ');
        $tableCheckAll->setClass('xo-checkall');
        $optionsTray->addElement($tableCheckAll);
        // Options
        $tableOption = $this->getTablesOptions();
        $checkbox = new XoopsFormCheckbox(' ', 'table_option', $tableOption, '<br />');
        $checkbox->setDescription(_AM_TDMCREATE_OPTIONS_DESC);
        foreach ($this->options as $option) {
            $checkbox->addOption($option, self::getDefinedLanguage('_AM_TDMCREATE_TABLE_'.strtoupper($option)));
        }
        $optionsTray->addElement($checkbox);
        //
        $optionsTray->setDescription(_AM_TDMCREATE_TABLE_OPTIONS_CHECKS_DESC);
        //
        $tab2->addElement($optionsTray);
        //
        $buttonTray = new XoopsFormElementTray(_REQUIRED.' <sup class="red bold">*</sup>', '');
        $buttonTray->addElement(new XoopsFormHidden('op', 'save'));
        $buttonTray->addElement(new XoopsFormHidden('table_id', ($isNew ? 0 : $this->getVar('table_id'))));
        $buttonTray->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        $tab2->addElement($buttonTray);
        $tabTray->addElement($tab2);
        $form->addElement($tabTray);

        return $form;
    }

    /**
     * Get Values.
     */
    public function getValues($keys = null, $format = null, $maxDepth = null)
    {
        $ret = parent::getValues($keys, $format, $maxDepth);
        // Values
        $ret['id'] = $this->getVar('table_id');
        $ret['mid'] = $this->getVar('table_mid');
        $ret['name'] = ucfirst($this->getVar('table_name'));
        $ret['image'] = $this->getVar('table_image');
        $ret['nbfields'] = $this->getVar('table_nbfields');
        $ret['order'] = $this->getVar('table_order');
        $ret['blocks'] = $this->getVar('table_blocks');
        $ret['admin'] = $this->getVar('table_admin');
        $ret['user'] = $this->getVar('table_user');
        $ret['submenu'] = $this->getVar('table_submenu');
        $ret['search'] = $this->getVar('table_search');
        $ret['comments'] = $this->getVar('table_comments');
        $ret['notifications'] = $this->getVar('table_notifications');
        $ret['permissions'] = $this->getVar('table_permissions');

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
    public function getTablesOptions()
    {
        $retTable = array();
        foreach ($this->options as $option) {
            if ($this->getVar('table_'.$option) == 1) {
                array_push($retTable, $option);
            }
        }

        return $retTable;
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

/*
*  @Class TDMCreateTablesHandler
*  @extends XoopsPersistableObjectHandler
*/

/**
 * Class TDMCreateTablesHandler.
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
     * retrieve a field.
     *
     * @param int  $i      field id
     * @param null $fields
     *
     * @return mixed reference to the <a href='psi_element://TDMCreateFields'>TDMCreateFields</a> object
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
     * Get Count Modules.
     */
    public function getCountTables($start = 0, $limit = 0, $sort = 'table_id ASC, table_name', $order = 'ASC')
    {
        $criteria = new CriteriaCompo();
        $criteria->setSort($sort);
        $criteria->setOrder($order);
        $criteria->setStart($start);
        $criteria->setLimit($limit);

        return parent::getCount($criteria);
    }

    /**
     * Get All Modules.
     */
    public function getAllTables($start = 0, $limit = 0, $sort = 'table_id ASC, table_name', $order = 'ASC')
    {
        $criteria = new CriteriaCompo();
        $criteria->setSort($sort);
        $criteria->setOrder($order);
        $criteria->setStart($start);
        $criteria->setLimit($limit);

        return parent::getAll($criteria);
    }

    /**
     * Get All Tables By Module Id.
     */
    public function getAllTablesByModuleId($modId, $start = 0, $limit = 0, $sort = 'table_order ASC, table_id, table_name', $order = 'ASC')
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('table_mid', $modId));
        $criteria->setSort($sort);
        $criteria->setOrder($order);
        $criteria->setStart($start);
        $criteria->setLimit($limit);

        return parent::getAll($criteria);
    }
}
