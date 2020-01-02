<?php

namespace XoopsModules\Tdmcreate\Files\Includes;

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
 * @version         $Id: IncludeCommentFunctions.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class IncludeCommentFunctions.
 */
class IncludeCommentFunctions extends Files\CreateFile
{
    /**
     *  @public function constructor
     *  @param null
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *  @static function getInstance
     *  @param null
     * @return IncludeCommentFunctions
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
     *  @public function write
     *  @param string $module
     *  @param mixed $table
     * @param $filename
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /**
     *  @public function render
     *  @param null
     * @return bool|string
     */
    public function render()
    {
        $module = $this->getModule();
        $table = $this->getTable();
        $moduleDirname = $module->getVar('mod_dirname');
        $tableName = $table->getVar('table_name');
        $ucfModuleDirname = ucfirst($moduleDirname);
        $ucfTableName = ucfirst($tableName);
        $filename = $this->getFileName();
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= <<<EOT
\n/**
 * CommentsUpdate
 *
 * @param mixed \$itemId
 * @param mixed \$itemNumb
 * @return bool
 */
function {$moduleDirname}CommentsUpdate(\$itemId, \$itemNumb) {
    \$itemId = (int)\$itemId;
    \$itemNumb = (int)\$itemNumb;
    \$article = new {$ucfModuleDirname}{$ucfTableName}(\$itemId);
    if (!\$article->updateComments(\$itemNumb)) {
        return false;
    }
    return true;
}
\n/**
 * CommentsApprove
 *
 * @param string  \$comment
 * @return void
 */
function {$moduleDirname}CommentsApprove(&\$comment){
    // notification mail here
}
EOT;
        $this->create($moduleDirname, 'include', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
