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
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.7
 *
 * @author          Txmod Xoops <webmaster@txmodxoops.org> - <http://www.txmodxoops.org/>
 *
 * @version         $Id: 1.91 tables.php 11297 2013-03-24 10:58:10Z timgno $
 */
include __DIR__.'/autoload.php';

/**
 * Class TDMCreateTables.
 */
class TDMCreateTables extends XoopsObject
{
    /**
     * Options.
     */
    public $options = [
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
    ];

    /**
    *  @public function constructor class
    *  @param null
    */

    public function __construct()
    {
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

    /**
    *  @static function getInstance
    *  @param null
     * @return TDMCreateTables
     */
    public static function getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /**
    *  @static function getFormTables
    *  @param mixed $action
     *
     * @return XoopsThemeForm
     */
    public function getFormTables($action = false)
    {
        if (false === $action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        $tdmcreate = TDMCreateHelper::getInstance();
        $isNew = $this->isNew();
        $tableName = $this->getVar('table_name');
        $tableMid = $this->getVar('table_mid');
        $title = $isNew ? sprintf(_AM_TDMCREATE_TABLE_NEW) : sprintf(_AM_TDMCREATE_TABLE_EDIT);

        xoops_load('XoopsFormLoader');
        $form = new XoopsThemeForm($title, 'tableform', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');

        $modules = $tdmcreate->getHandler('modules')->getObjects(null);
        $modulesSelect = new XoopsFormSelect(_AM_TDMCREATE_TABLE_MODULES, 'table_mid', $tableMid);
        $modulesSelect->addOption('', _AM_TDMCREATE_TABLE_MODSELOPT);
        foreach ($modules as $mod) {
            $modulesSelect->addOption($mod->getVar('mod_id'), $mod->getVar('mod_name'));
        }
        $form->addElement($modulesSelect, true);

        $tableNameText = new XoopsFormText(_AM_TDMCREATE_TABLE_NAME, 'table_name', 40, 150, $tableName);
        $tableNameText->setDescription(_AM_TDMCREATE_TABLE_NAME_DESC);
        $form->addElement($tableNameText, true);

        $tableSoleNameText = new XoopsFormText(_AM_TDMCREATE_TABLE_SOLENAME, 'table_solename', 40, 150, $this->getVar('table_solename'));
        $tableSoleNameText->setDescription(_AM_TDMCREATE_TABLE_SOLENAME_DESC);
        $form->addElement($tableSoleNameText, true);

        $radioCategory = $isNew ? 0 : $this->getVar('table_category');
        $category = new XoopsFormRadioYN(_AM_TDMCREATE_TABLE_CATEGORY, 'table_category', $radioCategory);
        $category->setDescription(_AM_TDMCREATE_TABLE_CATEGORY_DESC);
        $form->addElement($category);

        $tableFieldname = new XoopsFormText(_AM_TDMCREATE_TABLE_FIELDNAME, 'table_fieldname', 30, 50, $this->getVar('table_fieldname'));
        $tableFieldname->setDescription(_AM_TDMCREATE_TABLE_FIELDNAME_DESC);
        $form->addElement($tableFieldname);

        $tableNumbFileds = new XoopsFormText(_AM_TDMCREATE_TABLE_NBFIELDS, 'table_nbfields', 10, 25, $this->getVar('table_nbfields'));
        $tableNumbFileds->setDescription(_AM_TDMCREATE_TABLE_NBFIELDS_DESC);
        $form->addElement($tableNumbFileds, true);

        if (!$isNew) {
            $tableOrder = new XoopsFormText(_AM_TDMCREATE_TABLE_ORDER, 'table_order', 5, 10, $this->getVar('table_order'));
            $tableOrder->setDescription(_AM_TDMCREATE_TABLE_ORDER_DESC);
            $form->addElement($tableOrder, true);
        }

        $getTableImage = $this->getVar('table_image');
        $tableImage = $getTableImage ?: 'blank.gif';
        $icons32Directory = '/Frameworks/moduleclasses/icons/32';
        $uploadsDirectory = '/uploads/tdmcreate/images/tables';
        $iconsDirectory = is_dir(XOOPS_ROOT_PATH.$icons32Directory) ? $icons32Directory : $uploadsDirectory;

        $imgtray1 = new XoopsFormElementTray(_AM_TDMCREATE_TABLE_IMAGE, '<br>');
        $imgpath1 = sprintf(_AM_TDMCREATE_FORMIMAGE_PATH, ".{$iconsDirectory}/");
        $imageSelect1 = new XoopsFormSelect($imgpath1, 'table_image', $tableImage, 10);
        $imageArray1 = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH.$iconsDirectory);
        foreach ($imageArray1 as $image1) {
            $imageSelect1->addOption("{$image1}", $image1);
        }
        $imageSelect1->setExtra("onchange='showImgSelected(\"image1\", \"table_image\", \"".$iconsDirectory.'", "", "'.XOOPS_URL."\")'");
        $imgtray1->addElement($imageSelect1, false);
        $imgtray1->addElement(new XoopsFormLabel('', "<br><img src='".XOOPS_URL.'/'.$iconsDirectory.'/'.$tableImage."' name='image1' id='image1' alt='' />"));
        $fileseltray1 = new XoopsFormElementTray('', '<br>');
        $fileseltray1->addElement(new XoopsFormFile(_AM_TDMCREATE_FORMUPLOAD, 'attachedfile', $tdmcreate->getConfig('maxsize')));
        $fileseltray1->addElement(new XoopsFormLabel(''));
        $imgtray1->addElement($fileseltray1);
        $imgtray1->setDescription(_AM_TDMCREATE_TABLE_IMAGE_DESC);
        $form->addElement($imgtray1);

        $tableAutoincrement = $this->isNew() ? 1 : $this->getVar('table_autoincrement');
        $checkTableAutoincrement = new XoopsFormRadioYN(_AM_TDMCREATE_TABLE_AUTO_INCREMENT, 'table_autoincrement', $tableAutoincrement);
        $checkTableAutoincrement->setDescription(_AM_TDMCREATE_TABLE_AUTO_INCREMENT_DESC);
        $form->addElement($checkTableAutoincrement);

        $optionsTray = new XoopsFormElementTray(_OPTIONS, '<br>');

        $tableCheckAll = new XoopsFormCheckBox('', 'tablebox', 1);
        $tableCheckAll->addOption('allbox', _AM_TDMCREATE_TABLE_ALL);
        $tableCheckAll->setExtra(' onclick="xoopsCheckAll(\'tableform\', \'tablebox\');" ');
        $tableCheckAll->setClass('xo-checkall');
        $optionsTray->addElement($tableCheckAll);
        // Options
        $checkbox = new XoopsFormCheckbox(' ', 'table_option', $this->getOptionsTables(), '<br>');
        $checkbox->setDescription(_AM_TDMCREATE_OPTIONS_DESC);
        foreach ($this->options as $option) {
            $checkbox->addOption($option, self::getDefinedLanguage('_AM_TDMCREATE_TABLE_'.strtoupper($option)));
        }
        $optionsTray->addElement($checkbox);

        $optionsTray->setDescription(_AM_TDMCREATE_TABLE_OPTIONS_CHECKS_DESC);

        $form->addElement($optionsTray);

        $buttonTray = new XoopsFormElementTray(_REQUIRED.' <sup class="red bold">*</sup>', '');
        $buttonTray->addElement(new XoopsFormHidden('op', 'save'));
        $buttonTray->addElement(new XoopsFormHidden('table_id', ($isNew ? 0 : $this->getVar('table_id'))));
        $buttonTray->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        $form->addElement($buttonTray);

        return $form;
    }

    /**
     * Get Values.
     *
     * @param null $keys
     * @param null $format
     * @param null $maxDepth
     *
     * @return array
     */
    public function getValuesTables($keys = null, $format = null, $maxDepth = null)
    {
        $ret = $this->getValues($keys, $format, $maxDepth);
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
     *
     * @return array
     */
    public function getOptionsTables()
    {
        $retTable = [];
        foreach ($this->options as $option) {
            if (1 == $this->getVar('table_' . $option)) {
                $retTable[] = $option;
            }
        }

        return $retTable;
    }

    /**
     * Get Defined Language.
     *
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
 *  @Class TDMCreateTablesHandler
 *  @extends XoopsPersistableObjectHandler
 */
class TDMCreateTablesHandler extends XoopsPersistableObjectHandler
{
    /**
     *  @public function constructor class
     *
     * @param null|XoopsDatabase $db
     */
    public function __construct(XoopsDatabase $db)
    {
        parent::__construct($db, 'tdmcreate_tables', 'TDMCreateTables', 'table_id', 'table_name');
    }

    /**
     * @param bool $isNew
     *
     * @return object
     */
    public function create($isNew = true)
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
    public function get($i = null, $fields = null)
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
    public function getInsertId()
    {
        return $this->db->getInsertId();
    }

    /**
     * Get Count Modules.
     *
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     *
     * @return int
     */
    public function getCountTables($start = 0, $limit = 0, $sort = 'table_id ASC, table_name', $order = 'ASC')
    {
        $crCountTables = new CriteriaCompo();
        $crCountTables = $this->getTablesCriteria($crCountTables, $start, $limit, $sort, $order);

        return $this->getCount($crCountTables);
    }

    /**
     * Get All Modules.
     *
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     *
     * @return array
     */
    public function getAllTables($start = 0, $limit = 0, $sort = 'table_id ASC, table_name', $order = 'ASC')
    {
        $crAllTables = new CriteriaCompo();
        $crAllTables = $this->getTablesCriteria($crAllTables, $start, $limit, $sort, $order);

        return $this->getAll($crAllTables);
    }

    /**
     * Get All Tables By Module Id.
     *
     * @param        $modId
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     *
     * @return array
     */
    public function getAllTablesByModuleId($modId, $start = 0, $limit = 0, $sort = 'table_order ASC, table_id, table_name', $order = 'ASC')
    {
        $crAllTablesByModuleId = new CriteriaCompo();
        $crAllTablesByModuleId->add(new Criteria('table_mid', $modId));
        $crAllTablesByModuleId = $this->getTablesCriteria($crAllTablesByModuleId, $start, $limit, $sort, $order);

        return $this->getAll($crAllTablesByModuleId);
    }

    /**
     * Get Tables Criteria.
     *
     * @param $crTables
     * @param $start
     * @param $limit
     * @param $sort
     * @param $order
     *
     * @return mixed
     */
    private function getTablesCriteria($crTables, $start, $limit, $sort, $order)
    {
        $crTables->setStart($start);
        $crTables->setLimit($limit);
        $crTables->setSort($sort);
        $crTables->setOrder($order);

        return $crTables;
    }
}
