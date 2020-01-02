<?php namespace XoopsModules\Tdmcreate\Files\Includes;

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
 * @version         $Id: IncludeComments.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class IncludeComments.
 */
class IncludeComments extends Files\CreateFile
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
     * @return IncludeComments
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
     */
    public function write($module, $table)
    {
        $this->setModule($module);
        $this->setTable($table);
    }

    /**
     *  @public function getCommentsIncludes
     *  @param string $module
     *  @param string $filename
     *
     * @return bool|string
     */
    public function renderCommentsIncludes($module, $filename)
    {
        $moduleDirname = $module->getVar('mod_dirname');
        $content = $this->getHeaderFilesComments($module, $filename . '.php');
        $content .= <<<EOT
include_once __DIR__ . '/../../../mainfile.php';
include_once XOOPS_ROOT_PATH.'/include/{$filename}.php';
EOT;
        $this->create($moduleDirname, 'include', $filename . '.php', $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->render();
    }

    /**
     *  @public function getCommentsNew
     *  @param string $module
     *  @param string $filename
     *
     * @return bool|string
     */
    public function renderCommentsNew($module, $filename)
    {
        $table = $this->getTable();
        $moduleDirname = mb_strtolower($module->getVar('mod_dirname'));
        $tableName = $table->getVar('table_name');
        $fields = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        foreach (array_keys($fields) as $f) {
            if (1 == $fields[$f]->getVar('field_main')) {
                $fpmf = $fields[$f]->getVar('field_name');
            }
        }
        $content = $this->getHeaderFilesComments($module, $filename . '.php');
        $content .= <<<EOT
include __DIR__ . '/../../../mainfile.php';
include_once XOOPS_ROOT_PATH.'/modules/{$moduleDirname}/class/{$tableName}.php';
\$com_itemid = isset(\$_REQUEST['com_itemid']) ? (int)\$_REQUEST['com_itemid'] : 0;
if (\$com_itemid > 0) {
    \${$tableName}Handler = xoops_getModuleHandler('{$tableName}', '{$moduleDirname}');
    \${$tableName} = \${$tableName}handler->get(\$com_itemid);
    \$com_replytitle = \${$tableName}->getVar('{$fpmf}');
    include XOOPS_ROOT_PATH.'/include/{$filename}.php';
}
EOT;
        $this->create($moduleDirname, 'include', $filename . '.php', $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }

    /**
    *  @public function render
    *  @param null
    */
    /*public function render() {
        $module = $this->getModule();
        $table = $this->getTable();
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');

        $content = $this->getHeaderFilesComments($module, $filename);
        switch($filename) {
            case 'comment_edit.php':
                $content .= $this->getCommentsIncludes('comment_edit');
                $this->create($moduleDirname, 'include', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);
                return $this->render();
            break;
            case 'comment_delete.php':
                $content .= $this->getCommentsIncludes('comment_delete');
                $this->create($moduleDirname, 'include', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);
                return $this->render();
            break;
            case 'comment_post.php':
                $content .= $this->getCommentsIncludes('comment_post');
                $this->create($moduleDirname, 'include', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);
                return $this->render();
            break;
            case 'comment_reply.php':
                $content .= $this->getCommentsIncludes('comment_reply');
                $this->create($moduleDirname, 'include', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);
                return $this->render();
            break;
            case 'comment_new.php':
                $content .= $this->getCommentsNew($moduleDirname, 'comment_new');
                $this->create($moduleDirname, 'include', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);
                return $this->render();
            break;
        }
    }*/
}
