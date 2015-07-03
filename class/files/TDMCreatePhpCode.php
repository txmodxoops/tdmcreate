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
 * @version         $Id: TDMCreatePhpCode.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');
/**
 * Class TDMCreatePhpCode.
 */
class TDMCreatePhpCode extends AdminObjects
{
    /*
    * @var string
    */
    protected $TDMCreatePhpCode;

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
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return TDMCreatePhpCode
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
    *  @public function getPhpCodeIncludeDir
    *  @param $filename
    *  @return string
    */
    public function getPhpCodeIncludeDir($filename = '')
    {
        $ret = <<<EOT
include __DIR__ .'/{$filename}.php';\n
EOT;

        return $ret;
    }

    /**
     *  @public function getPhpCodeSwitch
     *
     *  @param $content
     *
     *  @return string
     */
    public function getPhpCodeSwitch($content)
    {
        $ret = <<<EOT
switch {\n
	\t{$content}
}\n
EOT;

        return $ret;
    }

    /**
     *  @public function getPhpCodeCaseSwitch
     *
     *  @param $case
     *  @param $content
     *
     *  @return string
     */
    public function getPhpCodeCaseSwitch($case = 'list', $content, $defaultAfterCase = false, $default = false)
    {
		if($defaultAfterCase) {
			if(is_string($case)) {
				$ret = <<<EOT
    case '{$case}':\n
EOT;
			} else {
				$ret = <<<EOT
    case {$case}:\n
EOT;
			}
			$ret = <<<EOT
    default:\n
	\t\t{$content}
		break;\n
EOT;
		} else {
			if(is_string($case)) {
				$ret = <<<EOT
    case '{$case}':\n
EOT;
			} else {
				$ret = <<<EOT
    case {$case}:\n
EOT;
			}
			$ret = <<<EOT
	\t\t{$content}
		break;\n
EOT;
		}
		if($default) {
			$ret = <<<EOT
    default:\n
	\t\t{$content}
		break;\n
EOT;
		}
		
        return $ret;
    }    
    
    /*
    *  @public function getPhpCodeTemplateMain
    *  @param $moduleDirname
    *  @param $tableName
    *  @return string
    */
    public function getPhpCodeTemplateMain($moduleDirname, $tableName)
    {
        $ret = <<<EOT
        \$templateMain = '{$moduleDirname}_admin_{$tableName}.tpl';\n
EOT;

        return $ret;
    }

    /**
     *  @public function getPhpCodeXoopsTplAssign
     *
     *  @param string $tplString
     *  @param string $phpRender
     *
     *  @return string
     */
    public function getPhpCodeXoopsTplAssign($tplString, $phpRender)
    {
        $ret = <<<EOT
        \$GLOBALS['xoopsTpl']->assign('{$tplString}', \${$phpRender});\n
EOT;

        return $ret;
    }

    /**
     *  @public function getPhpCodeXoopsTplAppend
     *
     *  @param string $tplString
     *  @param string $phpRender
     *
     *  @return string
     */
    public function getPhpCodeXoopsTplAppend($tplString, $phpRender)
    {
        $ret = <<<EOT
        \$GLOBALS['xoopsTpl']->append('{$tplString}', \${$phpRender});\n
EOT;

        return $ret;
    }

    /**
     *  @public function getPhpCodeXoopsTplAppendByRef
     *
     *  @param string $tplString
     *  @param string $phpRender
     *
     *  @return string
     */
    public function getPhpCodeXoopsTplAppendByRef($tplString, $phpRender)
    {
        $ret = <<<EOT
        \$GLOBALS['xoopsTpl']->appendByRef('{$tplString}', \${$phpRender});\n
EOT;

        return $ret;
    }

    /*
    *  @public function getPhpCodeItemButton
    *  @param $moduleDirname
    *  @param $tableName
    *  @param $admin
    *  @return string
    */
    public function getPhpCodeItemButton($moduleDirname, $tableName, $admin = false)
    {
        $ret = <<<EOT
        \$adminMenu->addItemButton({$language}ADD_{$stuTableSoleName}, '{$tableName}.php?op=new', 'add');
        \$adminMenu->addItemButton({$language}{$stuTableName}_LIST, '{$tableName}.php', 'list');
        \$GLOBALS['xoopsTpl']->assign('navigation', \$adminMenu->addNavigation('{$tableName}.php'));
        \$GLOBALS['xoopsTpl']->assign('buttons', \$adminMenu->renderButton());\n
EOT;

        return $ret;
    }

    /**
     *  @public function getPhpCodeAddNavigation
     *
     *  @param $tableName
     *
     *  @return string
     */
    public function getPhpCodeAddNavigation($tableName)
    {
        $ret = <<<EOT
        \$GLOBALS['xoopsTpl']->assign('navigation', \$adminMenu->addNavigation('{$tableName}.php'));\n
EOT;

        return $ret;
    }

    /**
     *  @public function getPhpCodeObjHandlerCreate
     *
     *  @param string $tableName
     *
     *  @return string
     */
    public function getPhpCodeObjHandlerCreate($tableName)
    {
        $ret = <<<EOT
        \${$tableName}Obj =& \${$tableName}Handler->create();\n
EOT;

        return $ret;
    }

    /**
     *  @public function getPhpCodeSetVarsObjects
     *
     *  @param $moduleDirname
     *  @param $tableName
     *  @param $fields
     *
     *  @return string
     */
    public function getPhpCodeSetVarsObjects($moduleDirname, $tableName, $fields)
    {
        $ret = <<<EOT
        // Set Vars\n
EOT;
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
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
                        $ret .= $this->adminobjects->getSimpleSetVar($tableName, $fieldName);
                        break;
                }
            }
        }

        return $ret;
    }

    /**
     *  @public function getPhpCodeXoopsSecurity
     *
     *  @param $tableName
     *
     *  @return string
     */
    public function getPhpCodeXoopsSecurity($tableName)
    {
        $ret = <<<EOT
        if ( !\$GLOBALS['xoopsSecurity']->check() ) {
			redirect_header('{$tableName}.php', 3, implode(',', \$GLOBALS['xoopsSecurity']->getErrors()));
        }\n
EOT;

        return $ret;
    }

    /*
    *  @public function getPhpCodeInsertData
    *  @param $tableName
    *  @param $language
    *  @return string
    */
    public function getPhpCodeInsertData($tableName, $language)
    {
        $ret = <<<EOT
        // Insert Data
        if (\${$tableName}Handler->insert(\${$tableName}Obj)) {
			redirect_header('{$tableName}.php?op=list', 2, {$language}FORM_OK);
        }\n
EOT;

        return $ret;
    }

    /**
     *  @public function getPhpCodeGetFormError
     *
     *  @param $tableName
     *
     *  @return string
     */
    public function getPhpCodeGetFormError($tableName)
    {
        $ret = <<<EOT
        // Get Form
        \$GLOBALS['xoopsTpl']->assign('error', \${$tableName}Obj->getHtmlErrors());
        \$form =& \${$tableName}Obj->getForm();
        \$GLOBALS['xoopsTpl']->assign('form', \$form->render());\n
EOT;

        return $ret;
    }

    /**
     *  @public function getPhpCodeGetFormId
     *
     *  @param string $tableName
     *  @param string $fieldId
     *
     *  @return string
     */
    public function getPhpCodeGetFormId($tableName, $fieldId)
    {
        $ret = <<<EOT
        // Get Form
        \${$tableName}Obj = \${$tableName}Handler->get(\${$fieldId});
        \$form = \${$tableName}Obj->getForm();
        \$GLOBALS['xoopsTpl']->assign('form', \$form->render());\n
EOT;

        return $ret;
    }

    /**
     *  @public function getPhpCodeGetObjHandlerId
     *
     *  @param string $tableName
     *  @param string $fieldId
     *
     *  @return string
     */
    public function getPhpCodeGetObjHandlerId($tableName, $fieldId)
    {
        $ret = <<<EOT
        \${$tableName}Obj =& \${$tableName}Handler->get(\${$fieldId});\n
EOT;

        return $ret;
    }

    /*
    *  @public function getPhpCodeDelete
    *  @param string $tableName
    *  @param string $language
    *  @param string $fieldId
    *  @param string $fieldMain
    *  @return string
    */
    public function getPhpCodeDelete($tableName, $language, $fieldId, $fieldMain)
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
    *  @public function getPhpCodeUpdate
    *  @param string $moduleDirname
    *  @param string $tableName
    *  @param string $language
    *  @param string $fieldId
    *  @param string $fieldMain
    *  @return string
    */
    public function getPhpCodeUpdate($language, $tableName, $language, $fieldId, $fieldMain)
    {
        $ret = <<<EOT
    case 'update':
        if (isset(\${$fieldId})) {
            \${$tableName}Obj =& \${$tableName}Handler->get(\${$fieldId});
        }
        \${$tableName}Obj->setVar("\${$tableName}_display", \$_POST["\${$tableName}_display"]);

        if (\${$tableName}Handler->insert(\${$tableName}Obj)) {
            redirect_header("\${$tableName}.php", 3, {$language}FORM_OK);
        }
        echo \${$tableName}Obj->getHtmlErrors();
    break;\n
EOT;

        return $ret;
    }    
}
