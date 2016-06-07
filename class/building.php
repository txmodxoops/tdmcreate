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
     *  @static function getInstance
     *
     *  @param null
     *
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
        $tc = TDMCreateHelper::getInstance();
        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }
        xoops_load('XoopsFormLoader');
        $form = new XoopsThemeForm(_AM_TDMCREATE_ADMIN_CONST, 'buildform', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        $moduleObj = $tc->getHandler('modules')->getObjects(null);
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

    /**
     * @param string $dir
     * @param string $pattern
     *
     * @return clearDir
     */
    public function clearDir($dir, $pattern = '*')
    {
        // Find all files and folders matching pattern
        $files = glob($dir."/$pattern");
        // Interate thorugh the files and folders
        foreach ($files as $file) {
            // if it's a directory then re-call clearDir function to delete files inside this directory     
            if (is_dir($file) && !in_array($file, array('..', '.'))) {
                // Remove the directory itself
                self::clearDir($file, $pattern);
            } elseif (is_file($file) && ($file != __FILE__)) {
                // Make sure you don't delete the current script
                unlink($file);
            }
        }
        rmdir($dir);
    }

    /**
     * @param string $src
     * @param string $dst
     *
     * @return copyDir
     */
    public function copyDir($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src.'/'.$file)) {
                    // Copy the directory itself
                    self::copyDir($src.'/'.$file, $dst.'/'.$file);
                } else {
                    // Make sure you copy the current script
                    copy($src.'/'.$file, $dst.'/'.$file);
                }
            }
        }
        closedir($dir);
    }
}
