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
 * @version         $Id: TDMCreateXoopsCode.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class TDMCreateXoopsCode.
 */
class TDMCreateXoopsCode
{
    /*
    * @var mixed
    */
    private $tdmcfile = null;

    /*
    * @var mixed
    */
    private $phpcode = null;

    /*
    * @var string
    */
    protected $xoopscode;

    /*
    *  @public function constructor
    *  @param null
    */
    /**
     *
     */
    public function __construct()
    {
        $this->tdmcfile = TDMCreateFile::getInstance();
        $this->phpcode = TDMCreatePhpCode::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return TDMCreateXoopsCode
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
     *  @public function getXoopsCodeSwitch
     *  @param $op
     *  @param $cases
     *  @param $defaultAfterCase
     *  @param $default
     *  @param $tabulation
     *
     * @return string
     */
    public function getXoopsCodeSwitch($op = '', $cases = array(), $defaultAfterCase = false, $default = false, $t = '')
    {
        $contentSwitch = $this->phpcode->getPhpCodeCaseSwitch($cases, $defaultAfterCase, $default, $t);

        return $this->phpcode->getPhpCodeSwitch($op, $contentSwitch, $t);
    }

    /*
    *  @public function getXoopsCodeEqualsOperator
    *  @param $left
    *  @param $right
    *  @param $ref
    *
    *  @return string
    */
    public function getXoopsCodeEqualsOperator($left, $right, $ref = false)
    {
        if (false === $ref) {
            $ret = "{$left}= {$right};\n";
        } else {
            $ret = "{$left}=& {$right};\n";
        }

        return $ret;
    }

    /*
    *  @public function getXoopsCodeCPHeader
    *  @param null
    *  @return string
    */
    public function getXoopsCodeCPHeader()
    {
        return "xoops_cp_header();\n";
    }

    /*
    *  @public function getXoopsCodeCPFooter
    *  @param null
    *  @return string
    */
    public function getXoopsCodeCPFooter()
    {
        return "xoops_cp_footer();\n";
    }

    /**
     *  @public function getXoopsCodeLoad
     *
     *  @param $var
     *
     *  @return string
     */
    public function getXoopsCodeLoad($var = '')
    {
        return "xoops_load('{$var}');\n";
    }

    /**
     *  @public function getXoopsCodeLoadLanguage
     *
     *  @param $lang
     *
     *  @return string
     */
    public function getXoopsCodeLoadLanguage($lang)
    {
        return "xoops_loadLanguage('{$lang}');\n";
    }

    /*
    *  @public function getXoopsCodeAnchorFunction
    *  @param $anchor
    *  @param $name
    *  @param $vars
    *
    *  @return string
    */
    public function getXoopsCodeAnchorFunction($anchor, $name, $vars)
    {
        return "\${$anchor}->{$name}({$vars})";
    }

    /*
    *  @public function getXoopsCodeSetVar
    *  @param $tableName
    *  @param $fieldName
    *  @param $var
    *  @return string
    */
    public function getXoopsCodeSetVar($tableName, $fieldName, $var)
    {
        return "\${$tableName}Obj->setVar('{$fieldName}', {$var});\n";
    }

    /*
    *  @public function getXoopsCodeGetVar
    *  @param $varLeft
    *  @param $handle
    *  @param $var
    *  @param $isParam
    *
    *  @return string
    */
    public function getXoopsCodeGetVar($varLeft = '', $handle = '', $var = '', $isParam = false)
    {
        if ($isParam === false) {
            $ret = "\${$varLeft} = \${$handle}->getVar('{$var}');\n";
        } else {
            $ret = "\${$handle}->getVar('{$var}')";
        }

        return $ret;
    }

    /*
    *  @public function getXoopsCodeGroupPermForm
    *  @param $varLeft
    *  @param $formTitle
    *  @param $moduleId
    *  @param $permName
    *  @param $permDesc
    *  @param $filename
    *
    *  @return string
    */
    public function getXoopsCodeGroupPermForm($varLeft = '', $formTitle = '', $moduleId = '', $permName = '', $permDesc = '', $filename = '')
    {
        return "\${$varLeft} = new XoopsGroupPermForm({$formTitle}, {$moduleId}, {$permName}, {$permDesc}, {$filename});\n";
    }

    /*
    *  @public function getXoopsCodeAddItem
    *  @param $varLeft
    *  @param $paramLeft
    *  @param $paramRight
    *
    *  @return string
    */
    public function getXoopsCodeAddItem($varLeft = '', $paramLeft = '', $paramRight = '')
    {
        return "\${$varLeft}->addItem({$paramLeft}, {$paramRight});\n";
    }

    /*
    *  @public function getXoopsCodeTextDateSelectSetVar
    *  @param $tableName
    *  @param $fieldName
    *  @return string
    */
    public function getXoopsCodeTextDateSelectSetVar($tableName, $fieldName)
    {
        return $this->getXoopsCodeSetVar($tableName, $fieldName, "strtotime(\$_POST['{$fieldName}'])");
    }

    /*
    *  @public function getXoopsCodeCheckBoxOrRadioYNSetVar
    *  @param $tableName
    *  @param $fieldName
    *  @return string
    */
    public function getXoopsCodeCheckBoxOrRadioYNSetVar($tableName, $fieldName)
    {
        return $this->getXoopsCodeSetVar($tableName, $fieldName, "((1 == \$_REQUEST['{$fieldName}']) ? '1' : '0')");
    }

    /*
    *  @public function getXoopsCodeXoopsMediaUploader
    *  @param $var
    *  @param $dirPath
    *  @param $tableName
    *  @param $moduleDirname
    *  @return string
    */
    public function getXoopsCodeXoopsMediaUploader($var = '', $dirPath, $tableName, $moduleDirname)
    {
        $mimetypes = $this->getXoopsCodeGetConfig($moduleDirname, 'mimetypes');
        $maxsize = $this->getXoopsCodeGetConfig($moduleDirname, 'maxsize');

        return "\${$var} = new XoopsMediaUploader({$dirPath} . '/{$tableName}', {$mimetypes}, {$maxsize}, null, null);\n";
    }

    /*
    *  @public function getXoopsCodeXoopsCaptcha
    *  @param $var
    *  @param $instance
    *
    *  @return string
    */
    public function getXoopsCodeGetInstance($var = '', $instance = '')
    {
        return "\${$var} = {$instance}::getInstance();\n";
    }

    /*
    *  @public function getXoopsCodeXoopsCaptcha
    *  @param null
    *  @return string
    */
    public function getXoopsCodeXoopsCaptcha()
    {
        return "\$xoopsCaptcha = XoopsCaptcha::getInstance();\n";
    }

    /*
    *  @public function getXoopsCodeGetConfig
    *  @param $moduleDirname
    *  @param $name
    *  @return string
    */
    public function getXoopsCodeGetConfig($moduleDirname, $name)
    {
        return "\${$moduleDirname}->getConfig('{$name}')";
    }

    /*
    *  @public function getXoopsCodeIdGetVar
    *  @param $lpFieldName
    *  @return string
    */
    public function getXoopsCodeIdGetVar($lpFieldName)
    {
        return "\${$lpFieldName}['id'] = \$i;\n";
    }

    /*
    *  @public function getXoopsCodeGetVarAll
    *  @param $lpFieldName
    *  @param $rpFieldName
    *  @param $tableName
    *  @param $fieldName
    *  @return string
    */
    public function getXoopsCodeGetVarAll($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        return "\${$lpFieldName}['{$rpFieldName}'] = \${$tableName}All[\$i]->getVar('{$fieldName}');\n";
    }

    /*
    *  @public function getXoopsHandlerInstance
    *  @param $moduleDirname
    *  
    *  @return string
    */
    public function getXoopsHandlerInstance($moduleDirname)
    {
        $ucfModuleDirname = ucfirst($moduleDirname);
        $ret = "// Get instance of module\n";
        $ret .= "\${$moduleDirname} = {$ucfModuleDirname}Helper::getInstance();\n";

        return $ret;
    }

    /*
    *  @public function getXoopsHandlerLine
    *  @param $moduleDirname
    *  @param $tableName
    *  @return string
    */
    public function getXoopsHandlerLine($moduleDirname, $tableName)
    {
        return "\${$tableName}Handler =& \${$moduleDirname}->getHandler('{$tableName}');\n";
    }

    /*
    *  @public function getXoopsSimpleForm
    *  @param $left
    *  @param $element
    *  @param $elementsContent
    *  @param $caption
    *  @param $var
    *  @param $filename
    *  @param $type
    *  
    *  @return string
    */
    public function getXoopsSimpleForm($left = '', $element = '', $elementsContent = '', $caption = '', $var = '', $filename = '', $type = 'post')
    {
        $ret = "\${$left} = new XoopsSimpleForm({$caption}, '{$var}', '{$filename}.php', '{$type}');\n";
        if (!empty($elementsContent)) {
            $ret .= "{$elementsContent}";
        }
        $ret .= "\${$left}->addElement(\${$element});\n";
        $ret .= "\${$left}->display();\n";

        return $ret;
    }

    /*
    *  @public function getXoopsFormSelect
    *  @param $varSelect
    *  @param $caption
    *  @param $var
    *  @param $options
    *  @param $setExtra
    *  
    *  @return string
    */
    public function getXoopsFormSelect($varSelect = '', $caption = '', $var = '', $options = array(), $setExtra = true)
    {
        $ret = "\${$varSelect} = new XoopsFormSelect({$caption}, '{$var}', \${$var});\n";
        if (false !== $setExtra) {
            $ret .= "\${$varSelect}->setExtra('{$setExtra}');\n";
        }
        foreach ($options as $key => $value) {
            $ret .= "\${$varSelect}->addOption('{$key}', {$value});\n";
        }

        return $ret;
    }

    /*
     *  @public function getXoopsCodeUnameFromId
     *  @param $left
     *  @param $tableName
     *
     * @return string
     */
    public function getXoopsCodeUnameFromId($left, $value)
    {
        return "\${$left} = XoopsUser::getUnameFromId({$value});\n";
    }

    /*
    *  @public function getXoopsCodeFormatTimeStamp
    *  @param $lpFieldName
    *  @param $rpFieldName
    *  @param $tableName
    *  @param $fieldName
    *  @return string
    */
    public function getXoopsCodeFormatTimeStamp($left, $value, $format = 's')
    {
        return "\${$left} = formatTimeStamp({$value}, '{$format}');\n";
    }

    /*
    *  @public function getXoopsCodeTopicGetVar
    *  @param $lpFieldName
    *  @param $rpFieldName
    *  @param $tableName
    *  @param $tableNameTopic
    *  @param $fieldNameParent
    *  @param $fieldNameTopic
    *  @return string
    */
    public function getXoopsCodeTopicGetVar($lpFieldName, $rpFieldName, $tableName, $tableNameTopic, $fieldNameParent, $fieldNameTopic)
    {
        $ret = <<<EOT
\t\t\t\t// Get Var {$fieldNameParent}
\t\t\t\t\${$rpFieldName} =& \${$tableNameTopic}Handler->get(\${$tableName}All[\$i]->getVar('{$fieldNameParent}'));
\t\t\t\t\${$lpFieldName}['{$rpFieldName}'] = \${$rpFieldName}->getVar('{$fieldNameTopic}');\n
EOT;

        return $ret;
    }

    /*
    *  @public function getXoopsCodeParentTopicGetVar
    *  @param $moduleDirname
    *  @param $lpFieldName
    *  @param $rpFieldName
    *  @param $tableName
    *  @param $tableSoleNameTopic
    *  @param $tableNameTopic
    *  @param $fieldNameParent
    *  @return string
    */
    public function getXoopsCodeParentTopicGetVar($moduleDirname, $lpFieldName, $rpFieldName, $tableName, $tableSoleNameTopic, $tableNameTopic, $fieldNameParent)
    {
        $ret = <<<EOT
\t\t\t\tif(!isset(\${$tableNameTopic}Handler)) {
\t\t\t\t\t// Get {$tableNameTopic} Handler
\t\t\t\t\t\${$tableNameTopic}Handler =& \${$moduleDirname}->getHandler('{$tableNameTopic}');
\t\t\t\t}
\t\t\t\t// Get Var {$fieldNameParent}
\t\t\t\t\${$lpFieldName}['{$rpFieldName}'] = \${$tableNameTopic}Handler->get{$tableSoleNameTopic}FromId(\${$tableName}All[\$i]->getVar('{$fieldNameParent}'));\n
EOT;

        return $ret;
    }

    /*
    *  @public function getXoopsCodeUploadImageGetVar
    *  @param $lpFieldName
    *  @param $rpFieldName
    *  @param $tableName
    *  @param $fieldName
    *  @return string
    */
    public function getXoopsCodeUploadImageGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        $ret = <<<EOT
\t\t\t\t// Get Var {$fieldName}
\t\t\t\t\${$fieldName} = \${$tableName}All[\$i]->getVar('{$fieldName}');
\t\t\t\t\${$lpFieldName}['{$rpFieldName}'] = \${$fieldName} ? \${$fieldName} : 'blank.gif';\n
EOT;

        return $ret;
    }
    /*
    *  @public function getXoopsCodeUrlFileGetVar
    *  @param $lpFieldName
    *  @param $rpFieldName
    *  @param $tableName
    *  @param $fieldName
    *  @return string
    */
    public function getXoopsCodeUrlFileGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        return $this->getXoopsCodeGetVarAll($lpFieldName, $rpFieldName, $tableName, $fieldName);
    }
    /*
    *  @public function getXoopsCodeTextAreaGetVar
    *  @param $lpFieldName
    *  @param $rpFieldName
    *  @param $tableName
    *  @param $fieldName
    *  @return string
    */
    public function getXoopsCodeTextAreaGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        return "\${$lpFieldName}['{$rpFieldName}'] = strip_tags(\${$tableName}All[\$i]->getVar('{$fieldName}'));\n";
    }

    /*
    *  @public function getXoopsCodeSelectUserGetVar
    *  @param $lpFieldName
    *  @param $rpFieldName
    *  @param $tableName
    *  @param $fieldName
    * @return string
    */
    public function getXoopsCodeSelectUserGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        return "\${$lpFieldName}['{$rpFieldName}'] = XoopsUser::getUnameFromId(\${$tableName}All[\$i]->getVar('{$fieldName}'), 's');\n";
    }

    /*
    *  @public function getXoopsCodeTextDateSelectGetVar
    *  @param $lpFieldName
    *  @param $rpFieldName
    *  @param $tableName
    *  @param $fieldName
    *  @return string
    */
    public function getXoopsCodeTextDateSelectGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        return "\${$lpFieldName}['{$rpFieldName}'] = formatTimeStamp(\${$tableName}All[\$i]->getVar('{$fieldName}'), 's');\n";
    }

    /*
    *  @public function getXoopsCodeUserHeader
    *  @param $moduleDirname
    *  @param $tableName
    *  @return string
    */
    public function getXoopsCodeXoopsOptionTemplateMain($moduleDirname, $tableName)
    {
        return "\$GLOBALS['xoopsOption']['template_main'] = '{$moduleDirname}_{$tableName}.tpl';\n";
    }

    /*
    *  @public function getXoopsCodeUserHeader
    *  @param $moduleDirname
    *  @param $tableName
    *  @return string
    */
    public function getXoopsCodeUserHeader($moduleDirname, $tableName)
    {
        $ret = $this->phpcode->getPhpCodeIncludeDir('__DIR__', 'header');
        $ret .= $this->getXoopsCodeXoopsOptionTemplateMain($moduleDirname, $tableName);
        $ret .= $this->phpcode->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'header', true);

        return $ret;
    }

    /*
    *  @public function getXoopsCodePermissionsHeader
    *  @param null
    */
    /**
     * @return string
     */
    public function getXoopsCodePermissionsHeader()
    {
        $ret = $this->phpcode->getPhpCodeCommentLine('Permission');
        $ret .= $this->phpcode->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/xoopsform/grouppermform', true);
        $ret .= $this->getXoopsCodeEqualsOperator('$gperm_handler', "xoops_gethandler('groupperm')", true);
        $groups = $this->getXoopsCodeEqualsOperator('$groups', '$xoopsUser->getGroups()');
        $elseGroups = $this->getXoopsCodeEqualsOperator('$groups', 'XOOPS_GROUP_ANONYMOUS');
        $ret .= $this->phpcode->getPhpCodeConditions('is_object($xoopsUser)', '', $type = '', $groups, $elseGroups);

        return $ret;
    }

    /**
     *  @public function getXoopsCodeGetFieldId
     *
     *  @param $fields
     *
     *  @return string
     */
    public function getXoopsCodeGetFieldId($fields)
    {
        $fieldId = 'id';
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (0 == $f) {
                $fieldId = $fieldName;
            }
        }

        return $fieldId;
    }

    /**
     *  @public function getXoopsCodeGetFieldName
     *
     *  @param $fields
     *
     *  @return string
     */
    public function getXoopsCodeGetFieldName($fields)
    {
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
        }

        return $fieldName;
    }

    /**
     *  @public function getXoopsCodeGetFieldParentId
     *
     *  @param $fields
     *
     *  @return string
     */
    public function getXoopsCodeGetFieldParentId($fields)
    {
        $fieldPid = 'pid';
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (1 == $fields[$f]->getVar('field_parent')) {
                $fieldPid = $fieldName;
            }
        }

        return $fieldPid;
    }

    /**
     *  @public function getXoopsCodeUserSaveElements
     *
     *  @param $moduleDirname
     *  @param $tableName
     *  @param $fields
     *
     *  @return string
     */
    public function getXoopsCodeUserSaveElements($moduleDirname, $tableName, $fields)
    {
        $ret = '';
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName;
            }
            if ((5 == $fieldElement) || (6 == $fieldElement)) {
                $ret .= $this->getXoopsCodeCheckBoxOrRadioYNSetVar($tableName, $fieldName);
            } elseif (13 == $fieldElement) {
                $ret .= $this->getXoopsCodeUploadImageSetVar($moduleDirname, $tableName, $fieldName, $fieldMain);
            } elseif (14 == $fieldElement) {
                $ret .= $this->getXoopsCodeUploadFileSetVar($moduleDirname, $tableName, $fieldName);
            } elseif (15 == $fieldElement) {
                $ret .= $this->getXoopsCodeTextDateSelectSetVar($tableName, $fieldName);
            } else {
                $ret .= $this->getXoopsCodeSetVar($tableName, $fieldName, "\$_POST['{$fieldName}']");
            }
        }

        return $ret;
    }

    /*
    *  @public function getXoopsCodeXoopsRequest
    *  @param $left
    *  @param $var1
    *  @param $var2
    *  @param $type
    *  @param $metod
    *  @return string
    */
    public function getXoopsCodeXoopsRequest($left = '', $var1 = '', $var2 = '', $type = 'String', $metod = false)
    {
        $ret = '';
        $intVars = ($var2 != '') ? "'{$var1}', {$var2}" : "'{$var1}'";
        if ($type == 'String') {
            $ret .= "\${$left} = XoopsRequest::getString('{$var1}', '{$var2}');\n";
        } elseif ($type == 'Int') {
            $ret .= "\${$left} = XoopsRequest::getInt({$intVars});\n";
        } elseif ($type == 'Int' && $metod !== false) {
            $ret .= "\${$left} = XoopsRequest::getInt({$intVars}, '{$metod}');\n";
        }

        return $ret;
    }

    /**
     *  @public function getXoopsCodeTplAssign     
     *
     *  @param $tplString
     *  @param $phpRender
     *  @param $leftIsString 
     *
     *  @return string
     */
    public function getXoopsCodeTplAssign($tplString, $phpRender, $leftIsString = true)
    {
        $assign = "\$GLOBALS['xoopsTpl']->assign(";
        if ($leftIsString === false) {
            $ret = $assign."{$tplString}, {$phpRender});\n";
        } else {
            $ret = $assign."'{$tplString}', {$phpRender});\n";
        }

        return $ret;
    }

    /**
     *  @public function getXoopsCodeXoopsTplAppend
     *
     *  @param $tplString
     *  @param $phpRender
     *
     *  @return string
     */
    public function getXoopsCodeXoopsTplAppend($tplString, $phpRender)
    {
        return "\$GLOBALS['xoopsTpl']->append('{$tplString}', {$phpRender});\n";
    }

    /**
     *  @public function getXoopsCodeXoopsTplAppendByRef
     *
     *  @param $tplString
     *  @param $phpRender
     *
     *  @return string
     */
    public function getXoopsCodeXoopsTplAppendByRef($tplString, $phpRender)
    {
        return "\$GLOBALS['xoopsTpl']->appendByRef('{$tplString}', {$phpRender});\n";
    }

    /**
     *  @public function getXoopsCodePath
     *
     *  @param $directory
     *  @param $filename
     *  @param $isParam
     *
     *  @return string
     */
    public function getXoopsCodePath($directory, $filename, $isParam = false)
    {
        if ($isParam === false) {
            $ret = "\$GLOBALS['xoops']->path({$directory}.'/{$filename}.php');\n";
        } else {
            $ret = "\$GLOBALS['xoops']->path({$directory}.'/{$filename}.php')";
        }

        return $ret;
    }

    /**
     *  @public function getXoopsCodeTplDisplay
     *
     *  @param $displayTpl
     *
     *  @return string
     */
    public function getXoopsCodeTplDisplay($displayTpl = '{$templateMain}')
    {
        return "\$GLOBALS['xoopsTpl']->display(\"db:{$displayTpl}\");\n";
    }

    /**
     *  @public function getXoopsCodeGetInfo
     *
     *  @param $left
     *  @param $string
     *  @param $isParam
     *
     *  @return string
     */
    public function getXoopsCodeGetInfo($left = '', $string, $isParam = false)
    {
        if ($isParam === false) {
            $ret = "\${$left} = \$GLOBALS['xoopsModule']->getInfo('{$string}');\n";
        } else {
            $ret = "\$GLOBALS['xoopsModule']->getInfo('{$string}')";
        }

        return $ret;
    }

    /**
     *  @public function getXoopsCodeCheckRight
     *
     *  @param $anchor
     *  @param $permString
     *  @param $var
     *  @param $groups
     *  @param $mid
     *  @param $isParam
     *
     *  @return string
     */
    public function getXoopsCodeCheckRight($anchor, $permString = '', $var = '', $groups = '', $mid = '', $isParam = false)
    {
        if ($isParam === false) {
            $ret = "{$anchor}->checkRight('{$permString}', {$var}, {$groups}, {$mid});\n";
        } else {
            $ret = "{$anchor}->checkRight('{$permString}', {$var}, {$groups}, {$mid})";
        }

        return $ret;
    }

    /**
     *  @public function getXoopsCodeObjHandlerCreate
     *
     *  @param $tableName
     *
     *  @return string
     */
    public function getXoopsCodeObjHandlerCreate($tableName)
    {
        return "\${$tableName}Obj =& \${$tableName}Handler->create();\n";
    }

    /**
     *  @public function getXoopsCodeObjHandlerCount
     *
     *  @param $tableName
     *
     *  @return string
     */
    public function getXoopsCodeObjHandlerCount($tableName)
    {
        $ucfTableName = ucfirst($tableName);
        $ret = "\${$tableName}Count = \${$tableName}Handler->getCount{$ucfTableName}();\n";

        return $ret;
    }

    /**
     *  @public function getXoopsCodeObjHandlerAll
     *
     *  @param $tableName
     *  @param $fieldMain
     *  @param $start
     *  @param $limit
     *
     *  @return string
     */
    public function getXoopsCodeObjHandlerAll($tableName, $fieldMain = '', $start = '0', $limit = '0')
    {
        $ucfTableName = ucfirst($tableName);
        $startLimit = ($limit != '0') ? "{$start}, {$limit}" : '0';
        $params = ($fieldMain != '') ? "{$startLimit}, '{$fieldMain}'" : $startLimit;
        $ret = "\${$tableName}All = \${$tableName}Handler->getAll{$ucfTableName}({$params});\n";

        return $ret;
    }

    /**
     *  @public function getXoopsCodeGetValues
     *
     *  @param $tableName
     *  @param $tableSoleName
     *
     *  @return string
     */
    public function getXoopsCodeGetValues($tableName, $tableSoleName, $index = 'i', $noArray = false)
    {
        $ucfTableName = ucfirst($tableName);
        if ($noArray == false) {
            $ret = "\${$tableSoleName} = \${$tableName}All[\${$index}]->getValues{$ucfTableName}();\n";
        } else {
            $ret = "\${$tableSoleName} = \${$tableName}->getValues{$ucfTableName}();\n";
        }

        return $ret;
    }

    /**
     *  @public function getXoopsCodeObjectTree
     *
     *  @param $var
     *  @param $tableName
     *  @param $fieldId
     *  @param $fieldParent
     *
     *  @return string
     */
    public function getXoopsCodeObjectTree($var = 'mytree', $tableName, $fieldId, $fieldParent)
    {
        $ret = "\${$var} = new XoopsObjectTree(\${$tableName}All, '{$fieldId}', '{$fieldParent}');\n";

        return $ret;
    }

    /**
     *  @public function getXoopsCodeSetVarsObjects
     *
     *  @param $moduleDirname
     *  @param $tableName
     *  @param $fields
     *
     *  @return string
     */
    public function getXoopsCodeSetVarsObjects($moduleDirname, $tableName, $fields)
    {
        $ret = '';
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName;
            }
            if ($f > 0) { // If we want to hide field id
                switch ($fieldElement) {
                    case 5:
                    case 6:
                        $ret .= $this->getXoopsCodeCheckBoxOrRadioYNSetVar($tableName, $fieldName);
                        break;
                    case 11:
                        $ret .= $this->getXoopsCodeImageListSetVar($moduleDirname, $tableName, $fieldName);
                        break;
                    case 12:
                        $ret .= $this->getXoopsCodeFileSetVar($moduleDirname, $tableName, $fieldName, true);
                        break;
                    case 13:
                        $ret .= $this->getXoopsCodeUploadImageSetVar($moduleDirname, $tableName, $fieldName, $fieldMain);
                        break;
                    case 14:
                        $ret .= $this->getXoopsCodeFileSetVar($moduleDirname, $tableName, $fieldName);
                        break;
                    case 15:
                        $ret .= $this->getXoopsCodeTextDateSelectSetVar($tableName, $fieldName);
                        break;
                    default:
                        $ret .= $this->getXoopsCodeSetVar($tableName, $fieldName, "\$_POST['{$fieldName}']");
                        break;
                }
            }
        }

        return $ret;
    }

    /**
     *  @public function getXoopsCodeSecurity
     *
     *  @param $tableName
     *
     *  @return string
     */
    public function getXoopsCodeSecurity($tableName)
    {
        $securityError = $this->getXoopsCodeSecurityGetError();
        $implode = $this->phpcode->getPhpCodeImplode(',', $securityError);
        $content = $this->getXoopsCodeRedirectHeader($tableName.'.php', '', 3, $implode);
        $securityCheck = $this->getXoopsCodeSecurityCheck();

        return $this->phpcode->getPhpCodeConditions('!'.$securityCheck, '', '', $content);
    }

    /*
    *  @public function getXoopsCodeInsertData
    *  @param $tableName
    *  @param $language
    *  @return string
    */
    public function getXoopsCodeInsertData($tableName, $language)
    {
        $content = $this->getXoopsCodeRedirectHeader($tableName.'.php', '?op=list', 2, "{$language}FORM_OK");
        $handlerInsert = $this->getXoopsCodeHandler($tableName, $tableName, false, true, false, 'Obj');

        return $this->phpcode->getPhpCodeConditions($handlerInsert, '', '', $content);
    }

    /*
    *  @public function getXoopsCodeRedirectHeader
    *  @param $tableName
    *  @param $options
    *  @param $numb
    *  @param $var
    *  @param $isString
    *
    *  @return string
    */
    public function getXoopsCodeRedirectHeader($tableName, $options = '', $numb = '2', $var, $isString = true)
    {
        if ($isString === false) {
            $ret = "redirect_header({$tableName}, {$numb}, {$var});\n";
        } else {
            $ret = "redirect_header('{$tableName}{$options}', {$numb}, {$var});\n";
        }

        return $ret;
    }

    /*
    *  @public function getXoopsCodeXoopsConfirm
    *  @param $tableName
    *  @param $language
    *  @param $fieldId
    *  @param $fieldMain    
    *  @param $options
    *
    *  @return string
    */
    public function getXoopsCodeXoopsConfirm($tableName, $language, $fieldId, $fieldMain, $options = 'delete')
    {
        $stuOptions = strtoupper($options);
        $ccFieldId = $this->tdmcfile->getCamelCase($fieldId, false, true);
        $ret = "xoops_confirm(array('ok' => 1, '{$fieldId}' => \${$ccFieldId}, 'op' => {$options}), \$_SERVER['REQUEST_URI'], sprintf({$language}FORM_SURE_{$stuOptions}, \${$tableName}Obj->getVar('{$fieldMain}')));\n";

        return $ret;
    }

    /*
    *  @public function getXoopsCodeAddStylesheet
    *  @param $style
    *  
    *  @return string
    */
    public function getXoopsCodeAddStylesheet($style = 'style')
    {
        return "\$GLOBALS['xoTheme']->addStylesheet( \${$style}, null );\n";
    }

    /*
    *  @public function getXoopsCodeSecurityCheck
    *  @param $denial
    *  @return boolean
    */
    public function getXoopsCodeSecurityCheck($denial = '')
    {
        return "{$denial}\$GLOBALS['xoopsSecurity']->check()";
    }

    /*
    *  @public function getXoopsCodeSecurityErrors
    *  @param null
    *  @return string
    */
    public function getXoopsCodeSecurityErrors()
    {
        return "\$GLOBALS['xoopsSecurity']->getErrors()";
    }

    /**
     *  @public function getXoopsCodeHtmlErrors
     *
     *  @param $tableName
     *  @param $isParam
     *  @param $obj
     *
     *  @return string
     */
    public function getXoopsCodeHtmlErrors($tableName, $isParam = false, $obj = 'Obj')
    {
        $ret = '';
        if ($isParam) {
            $ret = "\${$tableName}{$obj}->getHtmlErrors()";
        } else {
            $ret = "\${$tableName}{$obj} =& \${$tableName}->getHtmlErrors();";
        }

        return $ret;
    }

    /**
     *  @public function getXoopsCodeObjHandlerCount
     *
     *  @param $left
     *  @param $tableName
     *  @param $obj
     *
     *  @return string
     */
    public function getXoopsCodeGetForm($left, $tableName, $obj = '')
    {
        $ucfTableName = ucfirst($tableName);

        return "\${$left} =& \${$tableName}{$obj}->getForm{$ucfTableName}();\n";
    }

    /**
     *  @public function getXoopsCodeGet
     *
     *  @param $tableName
     *  @param $var
     *  @param $obj
     *  @param $isHandler
     *  @param $isParam
     *
     *  @return string
     */
    public function getXoopsCodeGet($tableName, $var, $obj = '', $isHandler = false, $isParam = false)
    {
        $handler = $isHandler === false ? '' : 'Handler';
        if ($isParam) {
            $ret = "\${$tableName}{$handler}->get(\${$var})";
        } else {
            $ret = "\${$tableName}{$obj} =& \${$tableName}{$handler}->get(\${$var});\n";
        }

        return $ret;
    }

    /**
     *  @public function getXoopsCodeHandler
     *
     *  @param $tableName
     *  @param $var
     *  @param $obj
     *  @param $isHandler
     *
     *  @return string
     */
    public function getXoopsCodeInsert($tableName, $var, $obj = '', $isHandler = false)
    {
        $handler = ($isHandler === false) ? '' : 'Handler';
        if ($obj != '') {
            $ret = "\${$tableName}{$handler}->insert(\${$var}{$obj})";
        } else {
            $ret = "\${$tableName}{$handler}->insert(\${$var})";
        }

        return $ret;
    }

    /**
     *  @public function getXoopsCodeDelete
     *
     *  @param $tableName
     *  @param $var
     *  @param $obj
     *  @param $isHandler
     *
     *  @return string
     */
    public function getXoopsCodeDelete($tableName, $var, $obj = '', $isHandler = false)
    {
        $handler = $isHandler === false ? '' : 'Handler';
        if ($obj != '') {
            $ret = "\${$tableName}{$handler}->delete(\${$var}{$obj})";
        } else {
            $ret = "\${$tableName}{$handler}->delete(\${$var})";
        }

        return $ret;
    }

    /**
     *  @public function getXoopsCodeHandler
     *
     *  @param $tableName
     *  @param $var
     *
     *  @return string
     */
    public function getXoopsCodeHandler($tableName, $var, $get = false, $insert = false, $delete = false, $obj = '')
    {
        if ($get) {
            $ret = "\${$tableName}Handler->get(\${$var});";
        } elseif ($insert && ($obj != '')) {
            $ret = "\${$tableName}Handler->insert(\${$var}{$obj});";
        } elseif ($delete && ($obj != '')) {
            $ret = "\${$tableName}Handler->delete(\${$var}{$obj});";
        }

        return $ret;
    }

    /*
    *  @public function getXoopsCodeCaseDelete
    *  @param $tableName
    *  @param $language
    *  @param $fieldId
    *  @param $fieldMain
    *  @return string
    */
    public function getXoopsCodeCaseDelete($language, $tableName, $fieldId, $fieldMain)
    {
        $ccFieldId = $this->tdmcfile->getCamelCase($fieldId, false, true);
        $ret = $this->getXoopsCodeGet($tableName, $ccFieldId, 'Obj', true);

        $reqOk = "\$_REQUEST['ok']";
        $isset = $this->phpcode->getPhpCodeIsset($reqOk);
        $xoopsSecurityCheck = $this->getXoopsCodeSecurityCheck();
        $xoopsSecurityErrors = $this->getXoopsCodeSecurityErrors();
        $implode = $this->phpcode->getPhpCodeImplode(', ', $xoopsSecurityErrors);
        $redirectHeaderErrors = $this->getXoopsCodeRedirectHeader($tableName, '', '3', $implode);

        $delete = $this->getXoopsCodeDelete($tableName, $tableName, 'Obj', true);
        $condition = $this->phpcode->getPhpCodeConditions('!'.$xoopsSecurityCheck, '', '', $redirectHeaderErrors);

        $redirectHeaderLanguage = $this->getXoopsCodeRedirectHeader($tableName, '', '3', "{$language}FORM_DELETE_OK");
        $htmlErrors = $this->getXoopsCodeHtmlErrors($tableName, true);
        $internalElse = $this->getXoopsCodeTplAssign('error', $htmlErrors);
        $condition .= $this->phpcode->getPhpCodeConditions($delete, '', '', $redirectHeaderLanguage, $internalElse);

        $mainElse = $this->getXoopsCodeXoopsConfirm($tableName, $language, $fieldId, $fieldMain);
        $ret .= $this->phpcode->getPhpCodeConditions($isset, ' && ', "1 == {$reqOk}", $condition, $mainElse);

        return $ret;
    }

    /*
    *  @public function getTopicGetVar
    *  @param $lpFieldName
    *  @param $rpFieldName
    *  @param $tableName
    *  @param $tableNameTopic
    *  @param $fieldNameParent
    *  @param $fieldNameTopic
    *  @return string
    */
    public function getTopicGetVar($lpFieldName, $rpFieldName, $tableName, $tableNameTopic, $fieldNameParent, $fieldNameTopic)
    {
        $ret = <<<EOT
\t\t// Get Var {$fieldNameParent}
\t\t\${$rpFieldName} =& \${$tableNameTopic}Handler->get(\${$tableName}All[\$i]->getVar('{$fieldNameParent}'));
\t\t\${$lpFieldName}['{$rpFieldName}'] = \${$rpFieldName}->getVar('{$fieldNameTopic}');\n
EOT;

        return $ret;
    }

    /*
    *  @public function getUploadImageGetVar
    *  @param $lpFieldName
    *  @param $rpFieldName
    *  @param $tableName
    *  @param $fieldName
    *  @return string
    */
    public function getUploadImageGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        $ret = <<<EOT
\t\t// Get Var {$fieldName}
\t\t\${$fieldName} = \${$tableName}All[\$i]->getVar('{$fieldName}');
\t\t\$upload_image = \${$fieldName} ? \${$fieldName} : 'blank.gif';
\t\t\${$lpFieldName}['{$rpFieldName}'] = \$upload_image;\n
EOT;

        return $ret;
    }

    /*
    *  @public function getXoopsCodeUpdate
    *  @param $language
    *  @param $tableName
    *  @param $fieldId
    *  @param $fieldName
    *  @return string
    */
    public function getXoopsCodeUpdate($language, $tableName, $fieldId, $fieldName)
    {
        $ccFieldId = $this->tdmcfile->getCamelCase($fieldId, false, true);
        $get = $this->getXoopsCodeGet($tableName, $ccFieldId, 'Obj', true);
        $isset = $this->phpcode->getPhpCodeIsset($ccFieldId);
        $get = $this->getXoopsCodeHandler($tableName, $fieldId, true);
        $ret = $this->phpcode->getPhpCodeConditions($isset, '', '', $get);
        $ret .= $this->getXoopsCodeSetVar($tableName, $fieldName, "\$_POST['{$fieldName}']");
        $handlerInsert = $this->$this->getXoopsCodeHandler($tableName, $tableName, false, true, false, 'Obj');
        $redirect = $this->getXoopsCodeRedirectHeader($tableName, '?op=list', 2, "{$language}FORM_UPDATE_OK");
        $ret .= $this->phpcode->getPhpCodeConditions($handlerInsert, '', '', $redirect);

        $ret .= $this->getXoopsCodeTplAssign('error', "\${$tableName}Obj->getHtmlErrors()");

        return $this->phpcode->getPhpCodeCaseSwitch('update', $ret);
    }

    /**
     *  @public function getXoopsCodeSaveFieldId
     *
     *  @param $fields
     *
     *  @return string
     */
    public function getXoopsCodeSaveFieldId($fields)
    {
        foreach (array_keys($fields) as $f) {
            if (0 == $f) {
                $fieldId = $fields[$f]->getVar('field_name');
            }
        }

        return $fieldId;
    }

    /**
     *  @public function getXoopsCodeSaveFieldMain
     *
     *  @param $fields
     *
     *  @return string
     */
    public function getXoopsCodeSaveFieldMain($fields)
    {
        foreach (array_keys($fields) as $f) {
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fields[$f]->getVar('field_name');
            }
        }

        return $fieldMain;
    }

    /**
     *  @public function getXoopsCodeSaveElements
     *
     *  @param $moduleDirname
     *  @param $tableName
     *  @param $fields
     *
     *  @return string
     */
    public function getXoopsCodeSaveElements($moduleDirname, $tableName, $fields)
    {
        $ret = '';
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName;
            }
            if ((5 == $fieldElement) || (6 == $fieldElement)) {
                $ret .= $this->getXoopsCodeCheckBoxOrRadioYNSetVar($tableName, $fieldName);
            } elseif (13 == $fieldElement) {
                $ret .= $this->getXoopsCodeUploadImageSetVar($moduleDirname, $tableName, $fieldName, $fieldMain);
            } elseif (14 == $fieldElement) {
                $ret .= $this->getXoopsCodeUploadFileSetVar($moduleDirname, $tableName, $fieldName);
            } elseif (15 == $fieldElement) {
                $ret .= $this->getXoopsCodeTextDateSelectSetVar($tableName, $fieldName);
            } else {
                $ret .= $this->getXoopsCodeSetVar($tableName, $fieldName, "\$_POST['{$fieldName}']");
            }
        }

        return $ret;
    }

    /*
    *  @public function getXoopsCodePageNav
    *  @param $tableName
    *
    *  @return string
    */
    public function getXoopsCodePageNav($tableName)
    {
        $condition = $this->phpcode->getPhpCodeCommentLine('Display Navigation');
        $condition .= $this->phpcode->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/pagenav', true);
        $condition .= "\$pagenav = new XoopsPageNav(\${$tableName}Count, \$limit, \$start, 'start', 'op=list&limit=' . \$limit);\n";
        $condition .= $this->getXoopsCodeTplAssign('pagenav', '$pagenav->renderNav(4)');
        $ret = $this->phpcode->getPhpCodeConditions("\${$tableName}Count", ' > ', '$limit', $condition, false, "\t\t");

        return $ret;
    }
}
