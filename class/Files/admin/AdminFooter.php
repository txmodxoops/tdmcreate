<?php

namespace XoopsModules\Tdmcreate\Files\Admin;

use XoopsModules\Tdmcreate;
use XoopsModules\Tdmcreate\Files;

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
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 */

/**
 * Class AdminFooter.
 */
class AdminFooter extends Files\CreateFile
{
    /**
     * @var string
     */
    private $xc = null;

    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
        $this->xc      = Tdmcreate\Files\CreateXoopsCode::getInstance();
        $this->phpcode = Tdmcreate\Files\CreatePhpCode::getInstance();
    }

    /**
     * @static function getInstance
     * @return AdminFooter
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
     * @public function write
     * @param string $module
     * @param string $filename
     */
    public function write($module, $filename)
    {
        $this->setModule($module);
        $this->setFileName($filename);
    }

    /**
     * @public function render
     * @param null
     * @return bool|string
     */
    public function render()
    {
        $module        = $this->getModule();
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $content       = $this->getHeaderFilesComments($module, $filename);
        $isset         = $this->phpcode->getPhpCodeIsset('templateMain');
        $display       = "\t" . $this->xc->getXcTplAssign('maintainedby', '$' . $moduleDirname . "->getConfig('maintainedby')");
        $display       .= "\t" . $this->phpcode->getPhpCodeRemoveCarriageReturn($this->xc->getXcTplDisplay(), '', "\r");
        $content       .= $this->phpcode->getPhpCodeConditions($isset, '', '', $display, false, '') . PHP_EOL;
        $content       .= $this->xc->getXcCPFooter();

        $this->create($moduleDirname, 'admin', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
