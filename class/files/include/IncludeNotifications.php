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
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 * @version         $Id: IncludeNotifications.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class IncludeNotifications.
 */
class IncludeNotifications extends TDMCreateFile
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
     * @return IncludeNotifications
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
    *  @param string $filename
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /**
    *  @static function getNotificationsFunction
    *  @param string $moduleDirname
     *
     * @return string
     */
    public function getNotificationsFunction($moduleDirname)
    {
        $stuModuleDirname = strtoupper($moduleDirname);
        $table = $this->getTable();
        $tableName = $table->getVar('table_name');
        $tableFieldname = $table->getVar('table_fieldname');
        $tableSoleName = $table->getVar('table_solename');
        $fields = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        $fieldParent = 'cid';
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if ((0 == $f) && (1 == $table->getVar('table_autoincrement'))) {
                $fieldId = $fieldName;
            }
            if (1 == $fields[$f]->getVar('field_parent')) {
                $fieldParent = $fieldName;
            }
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName;
            }
        }
        if (1 == $table->getVar('table_single')) {
            $tableSingle = 'single';
        } else {
            $tableSingle = $tableName;
        }
        $ret = <<<EOT
\n/**
 * comment callback functions
 *
 * @param \$category
 * @param \$item_id
 * @return array item
 */
function {$moduleDirname}_notify_iteminfo(\$category, \$item_id)
{
    global \$xoopsModule, \$xoopsModuleConfig, \$xoopsDB;
    //
    if (empty(\$xoopsModule) || \$xoopsModule->getVar('dirname') != '{$moduleDirname}')
    {
        \$moduleHandler = xoops_getHandler('module');
        \$module = \$moduleHandler->getByDirname('{$moduleDirname}');
        \$configHandler = xoops_getHandler('config');
        \$config =& \$configHandler->getConfigsByCat(0, \$module->getVar('mid'));
    } else {
        \$module =& \$xoopsModule;
        \$config =& \$xoopsModuleConfig;
    }
    //
    switch(\$category) {
        case 'global':
            \$item['name'] = '';
            \$item['url'] = '';
            return \$item;
        break;
        case 'category':
            // Assume we have a valid category id
            \$sql = 'SELECT {$fieldMain} FROM ' . \$xoopsDB->prefix('{$moduleDirname}_{$tableName}') . ' WHERE {$fieldId} = '. \$item_id;
            \$result = \$xoopsDB->query(\$sql); // TODO: error check
            \$result_array = \$xoopsDB->fetchArray(\$result);
            \$item['name'] = \$result_array['{$fieldMain}'];
            \$item['url'] = {$stuModuleDirname}_URL . '/{$tableName}.php?{$fieldId}=' . \$item_id;
            return \$item;
        break;
        case '{$tableSoleName}':
            // Assume we have a valid link id
            \$sql = 'SELECT {$fieldId}, {$fieldMain} FROM '.\$xoopsDB->prefix('{$moduleDirname}_{$tableName}') . ' WHERE {$fieldId} = ' . \$item_id;
            \$result = \$xoopsDB->query(\$sql); // TODO: error check
            \$result_array = \$xoopsDB->fetchArray(\$result);
            \$item['name'] = \$result_array['{$fieldMain}'];\n
EOT;
        if ($fieldParent) {
            $ret .= <<<EOT
			\$item['url'] = {$stuModuleDirname}_URL . '/{$tableSingle}.php?{$fieldParent}=' . \$result_array['{$fieldParent}'] . '&amp;{$fieldId}=' . \$item_id;\n
EOT;
        } else {
            $ret .= <<<EOT
			\$item['url'] = {$stuModuleDirname}_URL . '/{$tableSingle}.php?{$fieldId}=' . \$item_id;\n
EOT;
        }
        $ret .= <<<'EOT'
			return $item;
        break;
    }
    return '';
}
EOT;

        return $ret;
    }

    /**
    *  @public function render
    *  @param null
     * @return bool|string
     */
    public function render()
    {
        $module = $this->getModule();
        $filename = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getNotificationsFunction($moduleDirname);

        $this->create($moduleDirname, 'include', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
