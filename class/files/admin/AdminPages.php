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
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 * @version         $Id: AdminPages.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') || die('Restricted access');
/**
 * Class AdminPages.
 */
class AdminPages extends AdminObjects
{
    /*
    * @var string
    */
    private $adminobjects;

    /*
    *  @public function constructor
    *  @param null
    */
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->tdmcfile = TDMCreateFile::getInstance();
        $this->xoopscode = TDMCreateXoopsCode::getInstance();
		$this->adminobjects = AdminObjects::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return AdminPages
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
    *  @public function write
    *  @param string $module
    *  @param string $table
    */
    public function write($module, $table)
    {
        $this->setModule($module);
        $this->setTable($table);
    }

    /*
    *  @private function getAdminPagesHeader
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param $fieldId
    *  @return string
    */
    private function getAdminPagesHeader($moduleDirname, $tableName, $fieldId)
    {
        $ucfModuleDirname = ucfirst($moduleDirname);
        $ucfTableName = ucfirst($tableName);
        $ccFieldId = $this->tdmcfile->getCamelCase($fieldId, false, true);
        $ret = $this->getInclude();
        $ret .= $this->getCommentLine('It recovered the value of argument op in URL$');
        $ret .= $this->xoopscode->getXoopsCodeEqualsOperator('$op', "XoopsRequest::getString('op', 'list')");
        $ret .= $this->getCommentLine("Request {$fieldId}");
        $ret .= $this->xoopscode->getXoopsCodeEqualsOperator("\${$ccFieldId}", "XoopsRequest::getInt('{$fieldId}')");
        $ret .= $this->getCommentLine('Switch options');
        $ret .= $this->getSimpleString('switch(\$op)');
        $ret .= $this->getSimpleString('{');

        return $ret;
    }

    /*
    *  @private function getAdminPagesList
    *  @param $moduleDirname
    *  @param $table
    *  @param $tableFieldname
    *  @param $language
    *  @param $fields
    *  @param $fieldId
    *  @param $fieldInForm
    *  @param $fieldMain
    *  @return string
    */
    private function getAdminPagesList($moduleDirname, $table, $language, $fields, $fieldId, $fieldInForm, $fieldMain)
    {
        $stuModuleDirname = strtoupper($moduleDirname);
        $tableName = $table->getVar('table_name');
        $tableSoleName = $table->getVar('table_solename');
        $tableFieldname = $table->getVar('table_fieldname');
        $ucfTableName = ucfirst($tableName);
        $stuTableName = strtoupper($tableName);
        $stuTableSoleName = strtoupper($tableSoleName);
        $stuTableFieldname = strtoupper($tableFieldname);
        $tableAutoincrement = $table->getVar('table_autoincrement');
        $ret = <<<EOT
    case 'list':
    default:
        \$start = XoopsRequest::getInt('start', 0);
        \$limit = XoopsRequest::getInt('limit', \${$moduleDirname}->getConfig('adminpager'));
        \$templateMain = '{$moduleDirname}_admin_{$tableName}.tpl';
        \$GLOBALS['xoopsTpl']->assign('navigation', \$adminMenu->addNavigation('{$tableName}.php'));\n
EOT;
        if (1 == $fieldInForm) {
            $ret .= <<<EOT
        \$adminMenu->addItemButton({$language}ADD_{$stuTableSoleName}, '{$tableName}.php?op=new', 'add');
        \$GLOBALS['xoopsTpl']->assign('buttons', \$adminMenu->renderButton());\n
EOT;
        }
        $ret .= <<<EOT
        \${$tableName}Count = \${$tableName}Handler->getCount{$ucfTableName}();
        \${$tableName}All = \${$tableName}Handler->getAll{$ucfTableName}(\$start, \$limit);
		\$GLOBALS['xoopsTpl']->assign('{$tableName}_count', \${$tableName}Count);
        \$GLOBALS['xoopsTpl']->assign('{$moduleDirname}_url', {$stuModuleDirname}_URL);
        \$GLOBALS['xoopsTpl']->assign('{$moduleDirname}_upload_url', {$stuModuleDirname}_UPLOAD_URL);
        // Table view
        if (\${$tableName}Count > 0)
        {
            foreach (array_keys(\${$tableName}All) as \$i)
            {
				\${$tableSoleName} = \${$tableName}All[\$i]->getValues{$ucfTableName}();
                \$GLOBALS['xoopsTpl']->append('{$tableName}_list', \${$tableSoleName});
                unset(\${$tableSoleName});
            }
            if ( \${$tableName}Count > \$limit ) {
                include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
                \$pagenav = new XoopsPageNav(\${$tableName}Count, \$limit, \$start, 'start', 'op=list&limit=' . \$limit);
                \$GLOBALS['xoopsTpl']->assign('pagenav', \$pagenav->renderNav(4));
            }
        } else {
            \$GLOBALS['xoopsTpl']->assign('error', {$language}THEREARENT_{$stuTableName});
        }
    break;\n
EOT;

        return $ret;
    }

    /*
    *  @private function getAdminPagesNew
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $language
    *  @return string
    */
    private function getAdminPagesNew($moduleDirname, $tableName, $language)
    {
        $stuTableName = strtoupper($tableName);
        $ucfTableName = ucfirst($tableName);

        $ret = <<<EOT
    case 'new':
        \$templateMain = '{$moduleDirname}_admin_{$tableName}.tpl';
        \$adminMenu->addItemButton({$language}{$stuTableName}_LIST, '{$tableName}.php', 'list');
        \$GLOBALS['xoopsTpl']->assign('navigation', \$adminMenu->addNavigation('{$tableName}.php'));
        \$GLOBALS['xoopsTpl']->assign('buttons', \$adminMenu->renderButton());
        // Get Form
        \${$tableName}Obj =& \${$tableName}Handler->create();
        \$form = \${$tableName}Obj->getForm{$ucfTableName}();
        \$GLOBALS['xoopsTpl']->assign('form', \$form->render());
    break;\n
EOT;

        return $ret;
    }

    /*
    *  @private function getAdminPagesSave
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $language
    *  @param string $fields
    *  @param string $fieldId
    *  @param string $fieldMain
    *  @return string
    */
    private function getAdminPagesSave($moduleDirname, $tableName, $language, $fields, $fieldId, $fieldMain)
    {
        $ccFieldId = $this->tdmcfile->getCamelCase($fieldId, false, true);
        $ucfTableName = ucfirst($tableName);
        $ret = <<<EOT
    case 'save':
        if ( !\$GLOBALS['xoopsSecurity']->check() ) {
			redirect_header('{$tableName}.php', 3, implode(',', \$GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (isset(\${$ccFieldId})) {
           \${$tableName}Obj =& \${$tableName}Handler->get(\${$ccFieldId});
        } else {
           \${$tableName}Obj =& \${$tableName}Handler->create();
        }
        // Set Vars\n
EOT;
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldType = $fields[$f]->getVar('field_type');
            $fieldElement = $fields[$f]->getVar('field_element');
            if ($f > 0) { // If we want to hide field id
                switch ($fieldElement) {
                    case 5:
                    case 6:
                        $ret .= $this->adminobjects->getCheckBoxOrRadioYNSetVar($tableName, $fieldName);
                        break;
                    case 10:
                        $ret .= $this->adminobjects->getImageListSetVar($moduleDirname, $tableName, $fieldName);
                        break;
                    case 12:
                        $ret .= $this->adminobjects->getUrlFileSetVar($moduleDirname, $tableName, $fieldName);
                        break;
                    case 13:
                        if (1 == $fields[$f]->getVar('field_main')) {
                            $fieldMain = $fieldName;
                        }
                        $ret .= $this->adminobjects->getUploadImageSetVar($moduleDirname, $tableName, $fieldName, $fieldMain);
                        break;
                    case 14:
                        $ret .= $this->adminobjects->getUploadFileSetVar($moduleDirname, $tableName, $fieldName);
                        break;
                    case 15:
                        $ret .= $this->adminobjects->getTextDateSelectSetVar($tableName, $fieldName);
                        break;
                    default:
                        if ($fieldType == 2 || $fieldType == 7 || $fieldType == 8) {
                            $ret .= $this->adminobjects->getSetVarNumb($tableName, $fieldName);
                        } else {
                            $ret .= $this->adminobjects->getSimpleSetVar($tableName, $fieldName);
                        }
                        break;
                }
            }
        }
        $ret .= <<<EOT
        // Insert Data
        if (\${$tableName}Handler->insert(\${$tableName}Obj)) {
			redirect_header('{$tableName}.php?op=list', 2, {$language}FORM_OK);
        }
        // Get Form
        \$GLOBALS['xoopsTpl']->assign('error', \${$tableName}Obj->getHtmlErrors());
        \$form =& \${$tableName}Obj->getForm{$ucfTableName}();
        \$GLOBALS['xoopsTpl']->assign('form', \$form->render());
    break;\n
EOT;

        return $ret;
    }

    /*
    *  @private function getAdminPagesEdit
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $tableFieldname
    *  @param string $language
    *  @param string $fieldId
    *  @return string
    */
    private function getAdminPagesEdit($moduleDirname, $table, $language, $fieldId)
    {
        $tableName = $table->getVar('table_name');
        $tableSoleName = $table->getVar('table_solename');
        $tableFieldname = $table->getVar('table_fieldname');
        $stuTableName = strtoupper($tableName);
        $ucfTableName = ucfirst($tableName);
        $stuTableSoleName = strtoupper($tableSoleName);
        $stuTableFieldname = strtoupper($tableFieldname);
        $ccFieldId = $this->tdmcfile->getCamelCase($fieldId, false, true);
        $ret = <<<EOT
    case 'edit':
        \$templateMain = '{$moduleDirname}_admin_{$tableName}.tpl';
        \$adminMenu->addItemButton({$language}ADD_{$stuTableSoleName}, '{$tableName}.php?op=new', 'add');
        \$adminMenu->addItemButton({$language}{$stuTableName}_LIST, '{$tableName}.php', 'list');
        \$GLOBALS['xoopsTpl']->assign('navigation', \$adminMenu->addNavigation('{$tableName}.php'));
        \$GLOBALS['xoopsTpl']->assign('buttons', \$adminMenu->renderButton());
        // Get Form
        \${$tableName}Obj = \${$tableName}Handler->get(\${$ccFieldId});
        \$form = \${$tableName}Obj->getForm{$ucfTableName}();
        \$GLOBALS['xoopsTpl']->assign('form', \$form->render());
    break;\n
EOT;

        return $ret;
    }

    /*
    *  @private function getAdminPagesDelete
    *  @param string $tableName
    *  @param string $language
    *  @param string $fieldId
    *  @param string $fieldMain
    *  @return string
    */
    private function getAdminPagesDelete($tableName, $language, $fieldId, $fieldMain)
    {
        $ccFieldId = $this->tdmcfile->getCamelCase($fieldId, false, true);
        $ret = $this->getSimpleString("case 'delete':");
        $ret .= <<<EOT
        \${$tableName}Obj =& \${$tableName}Handler->get(\${$ccFieldId});
        if (isset(\$_REQUEST['ok']) && 1 == \$_REQUEST['ok']) {
            if ( !\$GLOBALS['xoopsSecurity']->check() ) {
                redirect_header('{$tableName}.php', 3, implode(', ', \$GLOBALS['xoopsSecurity']->getErrors()));
            }
            if (\${$tableName}Handler->delete(\${$tableName}Obj)) {
                redirect_header('{$tableName}.php', 3, {$language}FORM_DELETE_OK);
            } else {
                \$GLOBALS['xoopsTpl']->assign('error', \${$tableName}Obj->getHtmlErrors());
            }
        } else {
            xoops_confirm(array('ok' => 1, '{$fieldId}' => \${$ccFieldId}, 'op' => 'delete'), \$_SERVER['REQUEST_URI'], sprintf({$language}FORM_SURE_DELETE, \${$tableName}Obj->getVar('{$fieldMain}')));
        }
    break;\n
EOT;

        return $ret;
    }

    /*
    *  @private function getAdminPagesUpdate
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $fieldId
    *  @param string $fieldName
    *  @return string
    */
    private function getAdminPagesUpdate($moduleDirname, $tableName, $fieldId, $fieldName)
    {
        $stuModuleName = strtoupper($moduleDirname);
        $ccFieldId = $this->tdmcfile->getCamelCase($fieldId, false, true);
        $ret = $this->getSimpleString("case 'update':");
        $ret .= <<<EOT
        if (isset(\${$ccFieldId})) {
            \${$tableName}Obj =& \${$tableName}Handler->get(\${$ccFieldId});
        }
        \${$tableName}Obj->setVar('{$fieldName}', \$_POST['{$fieldName}']);
        if (\${$tableName}Handler->insert(\${$tableName}Obj)) {
            redirect_header('{$tableName}.php', 3, _AM_{$stuModuleName}_FORM_OK);
        }
		\$GLOBALS['xoopsTpl']->assign('error', \${$tableName}Obj->getHtmlErrors());
    break;\n
EOT;

        return $ret;
    }

    /*
    *  @private function getAdminPagesFooter
    *  @param null
    */
    /**
     * @return string
     */
    private function getAdminPagesFooter()
    {
        $ret = <<<EOT
}
include  __DIR__ . '/footer.php';
EOT;

        return $ret;
    }

    /*
    *  @public function render
    *  @param null
    */
    /**
     * @param $filename
     *
     * @return bool|string
     */
    public function renderFile($filename)
    {
        $module = $this->getModule();
        $table = $this->getTable();
        $moduleDirname = $module->getVar('mod_dirname');
        $tableName = $table->getVar('table_name');
        $tableSoleName = $table->getVar('table_solename');
        $language = $this->tdmcfile->getLanguage($moduleDirname, 'AM');
        $fields = $this->tdmcfile->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldInForm = $fields[$f]->getVar('field_inform');
            if (0 == $f) {
                $fieldId = $fieldName;
            }
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName;
            }
        }
        $content = $this->getHeaderFilesComments($module, $filename);
        $content .= $this->getAdminPagesHeader($moduleDirname, $tableName, $fieldId);
        $content .= $this->getAdminPagesList($moduleDirname, $table, $language, $fields, $fieldId, $fieldInForm, $fieldMain);
        if (1 == $fieldInForm) {
            $content .= $this->getAdminPagesNew($moduleDirname, $tableName, $language);
            $content .= $this->getAdminPagesSave($moduleDirname, $tableName, $language, $fields, $fieldId, $fieldMain);
            $content .= $this->getAdminPagesEdit($moduleDirname, $table, $language, $fieldId);
        }
        $content .= $this->getAdminPagesDelete($tableName, $language, $fieldId, $fieldMain);
        if (strstr($fieldName, 'update') || strstr($fieldName, 'online') || strstr($fieldName, 'display')) {
            $content .= $this->getAdminPagesUpdate($moduleDirname, $tableName, $fieldId, $fieldName);
        }
        $content .= $this->getAdminPagesFooter();
        //
        $this->tdmcfile->create($moduleDirname, 'admin', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}
