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
 * TDMCreateBuilding class.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.x
 *
 * @author          TDM TEAM DEV MODULE
 *
 * @version         $Id: building.php 12425 2014-02-23 22:40:09Z timgno $
 */

/**
 * Class TDMCreateBuilding.
 */
class TDMCreateBuilding
{
    /**
     * @var mixed
     */
    private $tc;

    /*
    *  @public function constructor class
    *  @param null
    */
    /**
     *
     */
    public function __construct()
    {
        $this->tc = TDMCreateHelper::getInstance();
    }

    /*
    *  @static function getInstance
    *  @param null
    */
    /**
     * @return TDMCreateBuilding
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
     * @param bool $action
     *
     * @return XoopsThemeForm
     */
    public function getForm($action = false)
    {
        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }
        xoops_load('XoopsFormLoader');
        $form = new XoopsThemeForm(_AM_TDMCREATE_ADMIN_CONST, 'buildform', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        $moduleObj = &$this->tc->getHandler('modules')->getObjects(null);
        $mod_select = new XoopsFormSelect(_AM_TDMCREATE_CONST_MODULES, 'mod_id', 'mod_id');
        $mod_select->addOption('', _AM_TDMCREATE_BUILD_MODSELOPT);
        foreach ($moduleObj as $mod) {
            $mod_select->addOption($mod->getVar('mod_id'), $mod->getVar('mod_name'));
        }
        $form->addElement($mod_select, true);

        $form->addElement(new XoopsFormHidden('op', 'build'));
        $form->addElement(new XoopsFormButton(_REQUIRED.' <sup class="red bold">*</sup>', 'submit', _SUBMIT, 'submit'));

        return $form;
    }
}
