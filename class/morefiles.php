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
 * morefiles class.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.7
 *
 * @author          Txmod Xoops <webmaster@txmodxoops.org> - <http://www.txmodxoops.org/>
 *
 * @version         $Id: morefiles.php 13080 2015-06-12 10:12:32Z timgno $
 */
include __DIR__.'/autoload.php';
/*
*  @Class TDMCreateMoreFiles
*  @extends XoopsObject
*/

/**
 * Class TDMCreateMoreFiles.
 */
class TDMCreateMoreFiles extends XoopsObject
{
    /**
     * Settings.
     *
     * @var mixed
     */
    private $settings;

    /**
     *  @public function constructor class
     *
     *  @param null
     */
    public function __construct()
    {
        $this->initVar('file_id', XOBJ_DTYPE_INT);
        $this->initVar('file_mid', XOBJ_DTYPE_INT);
        $this->initVar('file_name', XOBJ_DTYPE_TXTBOX);
        $this->initVar('file_extension', XOBJ_DTYPE_TXTBOX);
        $this->initVar('file_infolder', XOBJ_DTYPE_TXTBOX);
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
     *
     *  @param null
     *
     * @return TDMCreateMoreFiles
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
     * @public function getFormMoreFiles
     *
     * @param bool|mixed $action
     * @return XoopsThemeForm
     */
    public function getFormMoreFiles($action = false)
    {
        $tdmcreate = TDMCreateHelper::getInstance();
        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }

        $isNew = $this->isNew();
        $title = $isNew ? sprintf(_AM_TDMCREATE_MORE_FILES_NEW) : sprintf(_AM_TDMCREATE_MORE_FILES_EDIT);

        xoops_load('XoopsFormLoader');

        $form = new XoopsThemeForm($title, 'morefilesform', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');

        $modules = $tdmcreate->getHandler('modules')->getObjects(null);
        $modulesSelect = new XoopsFormSelect(_AM_TDMCREATE_MORE_FILES_MODULES, 'file_mid', $this->getVar('file_mid'));
        $modulesSelect->addOption('', _AM_TDMCREATE_MORE_FILES_MODULE_SELECT);
        foreach ($modules as $mod) {
            //$modulesSelect->addOptionArray();
            $modulesSelect->addOption($mod->getVar('mod_id'), $mod->getVar('mod_name'));
        }
        $form->addElement($modulesSelect, true);

        $modName = new XoopsFormText(_AM_TDMCREATE_MORE_FILES_NAME, 'file_name', 50, 255, $this->getVar('file_name'));
        $modName->setDescription(_AM_TDMCREATE_MORE_FILES_NAME_DESC);
        $form->addElement($modName, true);

        $fileEstension = new XoopsFormText(_AM_TDMCREATE_MORE_FILES_EXTENSION, 'file_extension', 50, 255, $this->getVar('file_extension'));
        $fileEstension->setDescription(_AM_TDMCREATE_MORE_FILES_EXTENSION_DESC);
        $form->addElement($fileEstension, true);

        $fileInfolder = new XoopsFormText(_AM_TDMCREATE_MORE_FILES_INFOLDER, 'file_infolder', 50, 255, $this->getVar('file_infolder'));
        $fileInfolder->setDescription(_AM_TDMCREATE_MORE_FILES_INFOLDER_DESC);
        $form->addElement($fileInfolder, true);

        $form->addElement(new XoopsFormHidden('op', 'save'));
        $form->addElement(new XoopsFormButton(_REQUIRED.' <sup class="red bold">*</sup>', 'submit', _SUBMIT, 'submit'));

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
    public function getValuesMoreFiles($keys = null, $format = null, $maxDepth = null)
    {
        $tdmcreate = TDMCreateHelper::getInstance();
        $ret = $this->getValues($keys, $format, $maxDepth);
        // Values
        $ret['id'] = $this->getVar('file_id');
        $ret['mid'] = $tdmcreate->getHandler('modules')->get($this->getVar('file_mid'))->getVar('mod_name');
        $ret['name'] = $this->getVar('file_name');
        $ret['extension'] = $this->getVar('file_extension');
        $ret['infolder'] = $this->getVar('file_infolder');

        return $ret;
    }
}

/**
 *  @Class TDMCreateMoreFilesHandler
 *  @extends XoopsPersistableObjectHandler
 */
class TDMCreateMoreFilesHandler extends XoopsPersistableObjectHandler
{
    /**
     *  @public function constructor class
     *
     * @param null|object $db
     */
    public function __construct(&$db)
    {
        parent::__construct($db, 'tdmcreate_morefiles', 'tdmcreatemorefiles', 'file_id', 'file_name');
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
     * Get Count MoreFiles.
     *
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     *
     * @return int
     */
    public function getCountMoreFiles($start = 0, $limit = 0, $sort = 'file_id ASC, file_name', $order = 'ASC')
    {
        $crMoreFilesCount = new CriteriaCompo();
        $crMoreFilesCount = $this->getMoreFilesCriteria($crMoreFilesCount, $start, $limit, $sort, $order);

        return $this->getCount($crMoreFilesCount);
    }

    /**
     * Get All MoreFiles.
     *
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     *
     * @return array
     */
    public function getAllMoreFiles($start = 0, $limit = 0, $sort = 'file_id ASC, file_name', $order = 'ASC')
    {
        $crMoreFilesAdd = new CriteriaCompo();
        $crMoreFilesAdd = $this->getMoreFilesCriteria($crMoreFilesAdd, $start, $limit, $sort, $order);

        return $this->getAll($crMoreFilesAdd);
    }

    /**
     * Get All MoreFiles By Module Id.
     *
     * @param        $modId
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     *
     * @return array
     */
    public function getAllMoreFilesByModuleId($modId, $start = 0, $limit = 0, $sort = 'file_id ASC, file_name', $order = 'ASC')
    {
        $crMoreFilesByModuleId = new CriteriaCompo();
        $crMoreFilesByModuleId->add(new Criteria('file_mid', $modId));
        $crMoreFilesByModuleId = $this->getMoreFilesCriteria($crMoreFilesByModuleId, $start, $limit, $sort, $order);

        return $this->getAll($crMoreFilesByModuleId);
    }

    /**
     * Get MoreFiles Criteria.
     *
     * @param $crMoreFiles
     * @param $start
     * @param $limit
     * @param $sort
     * @param $order
     *
     * @return
     */
    private function getMoreFilesCriteria($crMoreFiles, $start, $limit, $sort, $order)
    {
        $crMoreFiles->setStart($start);
        $crMoreFiles->setLimit($limit);
        $crMoreFiles->setSort($sort);
        $crMoreFiles->setOrder($order);

        return $crMoreFiles;
    }
}
