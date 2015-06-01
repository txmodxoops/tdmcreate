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
 * tdmcreate module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         tdmcreate
 * @since           2.5.0
 * @author          Txmod Xoops http://www.txmodxoops.org
 * @version         $Id: AdminPages.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');
/**
 * Class AdminPages
 */
class AdminPages extends TDMCreateFile
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
        $this->adminobjects = AdminObjects::getInstance();
        $this->tdmcfile     = TDMCreateFile::getInstance();
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
    *  @public function getAdminPagesHeader
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param $fieldId
    *  @return string
    */
    public function getAdminPagesHeader($moduleDirname, $tableName, $fieldId)
    {
        $ucfModuleDirname = ucfirst($moduleDirname);
		$ucfTableName     = ucfirst($tableName);
        $ret              = <<<EOT
include  __DIR__ . '/header.php';
//It recovered the value of argument op in URL$
\$op = XoopsRequest::getString('op', 'list');
// Request {$fieldId}
\${$fieldId} = XoopsRequest::getInt('{$fieldId}');
// Switch options
switch (\$op)
{\n
EOT;
        
		return $ret;
    }

    /*
    *  @public function getAdminPagesList    
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
    public function getAdminPagesList($moduleDirname, $table, $language, $fields, $fieldId, $fieldInForm, $fieldMain)
    {
        $stuModuleDirname   = strtoupper($moduleDirname);
        $tableName          = $table->getVar('table_name');
		$tableSoleName      = $table->getVar('table_solename');
		$tableFieldname     = $table->getVar('table_fieldname');
		$ucfTableName       = ucfirst($tableName);
		$stuTableName       = strtoupper($tableName);
		$stuTableSoleName   = strtoupper($tableSoleName);
        $stuTableFieldname  = strtoupper($tableFieldname);
        $tableAutoincrement = $table->getVar('table_autoincrement');
        $ret                = <<<EOT
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
				\${$tableSoleName} = \${$tableName}All[\$i]->getValues();
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
    *  @public function getAdminPagesNew
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $language
    *  @return string
    */
    public function getAdminPagesNew($moduleDirname, $tableName, $language)
    {
        $stuTableName = strtoupper($tableName);
        $ret          = <<<EOT
    case 'new':
        \$templateMain = '{$moduleDirname}_admin_{$tableName}.tpl';
        \$adminMenu->addItemButton({$language}{$stuTableName}_LIST, '{$tableName}.php', 'list');
        \$GLOBALS['xoopsTpl']->assign('navigation', \$adminMenu->addNavigation('{$tableName}.php'));
        \$GLOBALS['xoopsTpl']->assign('buttons', \$adminMenu->renderButton());
        // Get Form
        \${$tableName}Obj =& \${$tableName}Handler->create();
        \$form = \${$tableName}Obj->getForm();
        \$GLOBALS['xoopsTpl']->assign('form', \$form->render());
    break;\n
EOT;
        
		return $ret;
    }

    /*
    *  @public function getAdminPagesSave
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $language
    *  @param string $fields
    *  @param string $fieldId
    *  @param string $fieldMain
    *  @return string
    */
    public function getAdminPagesSave($moduleDirname, $tableName, $language, $fields, $fieldId, $fieldMain)
    {
        $ret = <<<EOT
    case 'save':
        if ( !\$GLOBALS['xoopsSecurity']->check() ) {
			redirect_header('{$tableName}.php', 3, implode(',', \$GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (isset(\${$fieldId})) {
           \${$tableName}Obj =& \${$tableName}Handler->get(\${$fieldId});
        } else {
           \${$tableName}Obj =& \${$tableName}Handler->create();
        }
        // Set Vars\n
EOT;
        foreach (array_keys($fields) as $f) {
            $fieldName    = $fields[$f]->getVar('field_name');			
            $fieldElement = $fields[$f]->getVar('field_element');
            if ($f > 0) { // If we want to hide field id
                switch ($fieldElement) {
                    case 5:
                    case 6:
                        $ret .= $this->adminobjects->getCheckBoxOrRadioYNSetVar($tableName, $fieldName);
                        break;
                    case 11:
                        $ret .= $this->adminobjects->getImageListSetVar($moduleDirname, $tableName, $fieldName);
                        break;
                    case 12:
                        $ret .= $this->adminobjects->getUrlFileSetVar($moduleDirname, $tableName, $fieldName);
                        break;
                    case 13:
						if(1 == $fields[$f]->getVar('field_main')) {							
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
                        $ret .= $this->adminobjects->getSimpleSetVar($tableName, $fieldName);
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
        \$form =& \${$tableName}Obj->getForm();
        \$GLOBALS['xoopsTpl']->assign('form', \$form->render());
    break;\n
EOT;
        
		return $ret;
    }

    /*
    *  @public function getAdminPagesEdit
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $tableFieldname
    *  @param string $language
    *  @param string $fieldId
    *  @return string
    */
    public function getAdminPagesEdit($moduleDirname, $table, $language, $fieldId)
    {
        $tableName         = $table->getVar('table_name');
		$tableSoleName     = $table->getVar('table_solename');
		$tableFieldname    = $table->getVar('table_fieldname');
		$stuTableName      = strtoupper($tableName);
		$stuTableSoleName  = strtoupper($tableSoleName);
        $stuTableFieldname = strtoupper($tableFieldname);
        $ret               = <<<EOT
    case 'edit':
        \$templateMain = '{$moduleDirname}_admin_{$tableName}.tpl';
        \$adminMenu->addItemButton({$language}ADD_{$stuTableSoleName}, '{$tableName}.php?op=new', 'add');
        \$adminMenu->addItemButton({$language}{$stuTableName}_LIST, '{$tableName}.php', 'list');
        \$GLOBALS['xoopsTpl']->assign('navigation', \$adminMenu->addNavigation('{$tableName}.php'));
        \$GLOBALS['xoopsTpl']->assign('buttons', \$adminMenu->renderButton());
        // Get Form
        \${$tableName}Obj = \${$tableName}Handler->get(\${$fieldId});
        \$form = \${$tableName}Obj->getForm();
        \$GLOBALS['xoopsTpl']->assign('form', \$form->render());
    break;\n
EOT;
        
		return $ret;
    }

    /*
    *  @public function getAdminPagesDelete
    *  @param string $tableName
    *  @param string $language
    *  @param string $fieldId
    *  @param string $fieldMain
    *  @return string
    */
    public function getAdminPagesDelete($tableName, $language, $fieldId, $fieldMain)
    {
        $ret = <<<EOT
    case 'delete':
        \${$tableName}Obj =& \${$tableName}Handler->get(\${$fieldId});
        if (isset(\$_REQUEST['ok']) && 1 == \$_REQUEST['ok']) {
            if ( !\$GLOBALS['xoopsSecurity']->check() ) {
                redirect_header('{$tableName}.php', 3, implode(', ', \$GLOBALS['xoopsSecurity']->getErrors()));
            }
            if (\${$tableName}Handler->delete(\${$tableName}Obj)) {
                redirect_header('{$tableName}.php', 3, {$language}FORMDELOK);
            } else {
                echo \${$tableName}Obj->getHtmlErrors();
            }
        } else {
            xoops_confirm(array('ok' => 1, '{$fieldId}' => \${$fieldId}, 'op' => 'delete'), \$_SERVER['REQUEST_URI'], sprintf({$language}FORMSUREDEL, \${$tableName}Obj->getVar('{$fieldMain}')));
        }
    break;\n
EOT;

        return $ret;
    }

    /*
    *  @public function getAdminPagesUpdate
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $language
    *  @param string $fieldId
    *  @param string $fieldMain
    *  @return string
    */
    public function getAdminPagesUpdate($moduleDirname, $tableName, $language, $fieldId, $fieldMain)
    {
        $upModuleName = strtoupper($moduleDirname);
        $ret          = <<<EOT
    case 'update':
        if (isset(\${$fieldId})) {
            \${$tableName}Obj =& \${$tableName}Handler->get(\${$fieldId});
        }
        \${$tableName}Obj->setVar("\${$tableName}_display", \$_POST["\${$tableName}_display"]);

        if (\${$tableName}Handler->insert(\${$tableName}Obj)) {
            redirect_header("\${$tableName}.php", 3, _AM_{$upModuleName}_FORMOK);
        }
        echo \${$tableName}Obj->getHtmlErrors();
    break;\n
EOT;
        
		return $ret;
    }

    /*
    *  @public function getAdminPagesFooter
    *  @param null
    */
    /**
     * @return string
     */
    public function getAdminPagesFooter()
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
     * @return bool|string
     */
    public function renderFile($filename)
    {
        $module         = $this->getModule();
        $table          = $this->getTable();
        $moduleDirname  = $module->getVar('mod_dirname');
        $tableName      = $table->getVar('table_name');        
        $language       = $this->tdmcfile->getLanguage($moduleDirname, 'AM');
        $fields         = $this->tdmcfile->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        foreach (array_keys($fields) as $f) {
            $fieldName   = $fields[$f]->getVar('field_name');
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
        $content .= $this->getAdminPagesFooter();
        //
        $this->tdmcfile->create($moduleDirname, 'admin', $filename, $content, _AM_TDMCREATE_FILE_CREATED, _AM_TDMCREATE_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}