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
     *  @public function getXcSwitch
     *  @param $op
     *  @param $cases
     *  @param $defaultAfterCase
     *  @param $default
     *  @param $t - Indentation 
     *
     * @return string
     */
    public function getXcSwitch($op = '', $cases = array(), $defaultAfterCase = false, $default = false, $t = '')
    {
        $phpCodeSwitch = TDMCreatePhpCode::getInstance();
        $contentSwitch = $phpCodeSwitch->getPhpCodeCaseSwitch($cases, $defaultAfterCase, $default, $t);

        return $phpCodeSwitch->getPhpCodeSwitch($op, $contentSwitch, $t);
    }

    /*
    *  @public function getXcEqualsOperator
    *  @param $var
    *  @param $value
    *  @param $interlock
    *  @param $ref
    *  @param $t - Indentation 
    *
    *  @return string
    */
    public function getXcEqualsOperator($var, $value, $interlock = null, $ref = false, $t = '')
    {
        if (false === $ref) {
            $ret = "{$t}{$var} {$interlock}= {$value};\n";
        } else {
            $ret = "{$t}{$var} =& {$value};\n";
        }

        return $ret;
    }

    /*
    *  @public function getXcCPHeader
    *  @param null
    *  @return string
    */
    public function getXcCPHeader()
    {
        return "xoops_cp_header();\n";
    }

    /*
    *  @public function getXcCPFooter
    *  @param null
    *  @return string
    */
    public function getXcCPFooter()
    {
        return "xoops_cp_footer();\n";
    }

    /**
     *  @public function getXcLoad
     *
     *  @param $var
     *
     *  @return string
     */
    public function getXcLoad($var = '', $t = '')
    {
        return "{$t}xoops_load('{$var}');\n";
    }

    /**
     *  @public function getXcLoadLanguage
     *
     *  @param $lang
     *
     *  @return string
     */
    public function getXcLoadLanguage($lang, $t = '')
    {
        return "{$t}xoops_loadLanguage('{$lang}');\n";
    }

    /*
    *  @public function getXcAnchorFunction
    *  @param $anchor
    *  @param $name
    *  @param $vars
    *  @param $close
    *
    *  @return string
    */
    public function getXcAnchorFunction($anchor, $name, $vars, $close = false)
    {
        $semicolon = $close !== false ? ';' : '';

        return "\${$anchor}->{$name}({$vars}){$semicolon}";
    }

    /*
    *  @public function getXcSetVar
    *  @param $tableName
    *  @param $fieldName
    *  @param $var
    *  @return string
    */
    public function getXcSetVar($tableName, $fieldName, $var, $t = '')
    {
        return "{$t}\${$tableName}Obj->setVar('{$fieldName}', {$var});\n";
    }

    /*
    *  @public function getXcGetVar
    *  @param $varLeft
    *  @param $handle
    *  @param $var
    *  @param $isParam
    *
    *  @return string
    */
    public function getXcGetVar($varLeft = '', $handle = '', $var = '', $isParam = false, $t = '')
    {
        if (!$isParam) {
            $ret = "{$t}\${$varLeft} = \${$handle}->getVar('{$var}');\n";
        } else {
            $ret = "\${$handle}->getVar('{$var}')";
        }

        return $ret;
    }

    /*
    *  @public function getXcGroupPermForm
    *  @param $varLeft
    *  @param $formTitle
    *  @param $moduleId
    *  @param $permName
    *  @param $permDesc
    *  @param $filename
    *
    *  @return string
    */
    public function getXcGroupPermForm($varLeft = '', $formTitle = '', $moduleId = '', $permName = '', $permDesc = '', $filename = '', $t = '')
    {
        return "{$t}\${$varLeft} = new XoopsGroupPermForm({$formTitle}, {$moduleId}, {$permName}, {$permDesc}, {$filename});\n";
    }

    /*
    *  @public function getXcAddItem
    *  @param $varLeft
    *  @param $paramLeft
    *  @param $paramRight
    *
    *  @return string
    */
    public function getXcAddItem($varLeft = '', $paramLeft = '', $paramRight = '', $t = '')
    {
        return "{$t}\${$varLeft}->addItem({$paramLeft}, {$paramRight});\n";
    }

    /*
    *  @public function getXcGetGroupIds
    *  @param $var
    *  @param $param1
    *  @param $param2
    *  @param $param3
    *
    *  @return string
    */
    public function getXcGetGroupIds($var = '', $anchor = '', $param1 = null, $param2 = null, $param3 = null, $t = '')
    {
        return "{$t}\${$var} = \${$anchor}->getGroupIds({$param1}, {$param2}, {$param3});\n";
    }

    /*
    *  @public function getXcGetItemIds
    *  @param $var
    *  @param $param1
    *  @param $param2
    *  @param $param3
    *
    *  @return string
    */
    public function getXcGetItemIds($var = '', $anchor = '', $param1 = null, $param2 = null, $param3 = null, $t = '')
    {
        return "{$t}\${$var} = \${$anchor}->getItemIds({$param1}, {$param2}, {$param3});\n";
    }

    /*
    *  @public function getXcTextDateSelectSetVar
    *  @param $tableName
    *  @param $fieldName
    *  @return string
    */
    public function getXcTextDateSelectSetVar($tableName, $fieldName, $t = '')
    {
        return self::getXcSetVar($tableName, $fieldName, "strtotime(\$_POST['{$fieldName}'])", $t);
    }

    /*
    *  @public function getXcCheckBoxOrRadioYNSetVar
    *  @param $tableName
    *  @param $fieldName
    *  @return string
    */
    public function getXcCheckBoxOrRadioYNSetVar($tableName, $fieldName, $t = '')
    {
        return self::getXcSetVar($tableName, $fieldName, "((1 == \$_REQUEST['{$fieldName}']) ? '1' : '0')", $t);
    }

    /*
    *  @public function getXcMediaUploader
    *  @param $var
    *  @param $dirPath
    *  @param $moduleDirname
    *  @return string
    */
    public function getXcMediaUploader($var = '', $dirPath, $moduleDirname, $t = '')
    {
        $mimetypes = self::getXcGetConfig($moduleDirname, 'mimetypes');
        $maxsize = self::getXcGetConfig($moduleDirname, 'maxsize');

        return "{$t}\${$var} = new XoopsMediaUploader({$dirPath}, 
													{$mimetypes}, 
													{$maxsize}, null, null);\n";
    }

    /*
    *  @public function getXcXoopsCaptcha
    *  @param $var
    *  @param $instance
    *
    *  @return string
    */
    public function getXcGetInstance($var = '', $instance = '', $t = '')
    {
        return "{$t}\${$var} = {$instance}::getInstance();\n";
    }

    /*
    *  @public function getXcXoopsCaptcha
    *  @param null
    *  @return string
    */
    public function getXcXoopsCaptcha($t = '')
    {
        return "{$t}\$xoopsCaptcha = XoopsCaptcha::getInstance();\n";
    }

    /*
    *  @public function getXcXoopsImgListArray
    *  @param $return
    *  @param $var
    *  @param $t
    *
    *  @return string
    */
    public function getXcXoopsImgListArray($return, $var, $t = '')
    {
        return "{$t}\${$return} = XoopsLists::getImgListAsArray( {$var} );\n";
    }

    /*
    *  @public function getXcGetConfig
    *  @param $moduleDirname
    *  @param $name
    *  @return string
    */
    public function getXcGetConfig($moduleDirname, $name)
    {
        return "\${$moduleDirname}->getConfig('{$name}')";
    }

    /*
    *  @public function getXcIdGetVar
    *  @param $lpFieldName
    *  @return string
    */
    public function getXcIdGetVar($lpFieldName, $t = '')
    {
        return "{$t}\${$lpFieldName}['id'] = \$i;\n";
    }

    /*
    *  @public function getXcGetVarAll
    *  @param $lpFieldName
    *  @param $rpFieldName
    *  @param $tableName
    *  @param $fieldName
    *  @return string
    */
    public function getXcGetVarAll($lpFieldName, $rpFieldName, $tableName, $fieldName, $t = '')
    {
        return "{$t}\${$lpFieldName}['{$rpFieldName}'] = \${$tableName}All[\$i]->getVar('{$fieldName}');\n";
    }

    /*
    *  @public function getXoopsHandlerInstance
    *  @param $moduleDirname
    *  
    *  @return string
    */
    public function getXoopsHandlerInstance($moduleDirname, $t = '')
    {
        $ucfModuleDirname = ucfirst($moduleDirname);
        $ret = "{$t}// Get instance of module\n";
        $ret .= "{$t}\${$moduleDirname} = {$ucfModuleDirname}Helper::getInstance();\n";

        return $ret;
    }

    /*
    *  @public function getXoopsHandlerLine
    *  @param $moduleDirname
    *  @param $tableName
    *  @return string
    */
    public function getXoopsHandlerLine($moduleDirname, $tableName, $t = '')
    {
        return "{$t}\${$tableName}Handler =& \${$moduleDirname}->getHandler('{$tableName}');\n";
    }

    /*
    *  @public function getXoopsClearHandler
    *  @param $left
    *  @param $ref
    *  @param $anchor
    *  @param $var
    *
    *  @return string
    */
    public function getXoopsClearHandler($left, $ref = '&', $anchor, $var, $t = '')
    {
        return "{$t}\${$left}Handler ={$ref} \${$anchor}->getHandler('{$var}');\n";
    }

    /*
    *  @public function getXoopsFormSelectExtraOptions
    *  @param $varSelect
    *  @param $caption
    *  @param $var
    *  @param $options
    *  @param $setExtra
    *  
    *  @return string
    */
    public function getXoopsFormSelectExtraOptions($varSelect = '', $caption = '', $var = '', $options = array(), $setExtra = true, $t = '')
    {
        $ret = "{$t}\${$varSelect} = new XoopsFormSelect({$caption}, '{$var}', \${$var});\n";
        if (false !== $setExtra) {
            $ret .= "{$t}\${$varSelect}->setExtra('{$setExtra}');\n";
        }
        foreach ($options as $key => $value) {
            $ret .= "{$t}\${$varSelect}->addOption('{$key}', {$value});\n";
        }

        return $ret;
    }

    /*
     *  @public function getXcUnameFromId
     *  @param $left
     *  @param $tableName
     *
     * @return string
     */
    public function getXcUnameFromId($left, $value, $t = '')
    {
        return "{$t}\${$left} = XoopsUser::getUnameFromId({$value});\n";
    }

    /*
    *  @public function getXcFormatTimeStamp
    *  @param $lpFieldName
    *  @param $rpFieldName
    *  @param $tableName
    *  @param $fieldName
    *  @return string
    */
    public function getXcFormatTimeStamp($left, $value, $format = 's', $t = '')
    {
        return "{$t}\${$left} = formatTimeStamp({$value}, '{$format}');\n";
    }

    /*
    *  @public function getXcTopicGetVar
    *  @param $lpFieldName
    *  @param $rpFieldName
    *  @param $tableName
    *  @param $tableNameTopic
    *  @param $fieldNameParent
    *  @param $fieldNameTopic
    *  @return string
    */
    public function getXcTopicGetVar($lpFieldName, $rpFieldName, $tableName, $tableNameTopic, $fieldNameParent, $fieldNameTopic, $t = '')
    {
        $pTopic = TDMCreatePhpCode::getInstance();
        $ret = $pTopic->getPhpCodeCommentLine('Get Var', $fieldNameParent, $t);
        $fieldParent = self::getXcGetVar('', "\${$tableName}All[\$i]", $fieldNameParent, true, '');
        $ret .= self::getXcGet($rpFieldName, $fieldParent, '', $tableNameTopic.'Handler', false, $t);
        $ret .= self::getXcGetVar("\${$lpFieldName}['{$rpFieldName}']", "\${$rpFieldName}", $fieldNameTopic, false, $t);

        return $ret;
    }

    /*
    *  @public function getXcParentTopicGetVar
    *  @param $moduleDirname
    *  @param $lpFieldName
    *  @param $rpFieldName
    *  @param $tableName
    *  @param $tableSoleNameTopic
    *  @param $tableNameTopic
    *  @param $fieldNameParent
    *  @return string
    */
    public function getXcParentTopicGetVar($moduleDirname, $lpFieldName, $rpFieldName, $tableName, $tableSoleNameTopic, $tableNameTopic, $fieldNameParent, $t = '')
    {
        $pParentTopic = TDMCreatePhpCode::getInstance();
        $parentTopic = $pParentTopic->getPhpCodeCommentLine('Get', $tableNameTopic.' Handler', $t."\t");
        $parentTopic .= self::getXoopsHandlerLine($moduleDirname, $tableNameTopic, $t."\t");
        $elseGroups = self::getXcEqualsOperator('$groups', 'XOOPS_GROUP_ANONYMOUS');
        $ret = $pParentTopic->getPhpCodeConditions("!isset(\${$tableNameTopic}Handler", '', '', $parentTopic, $elseGroups);
        $ret .= self::getXcGetVarFromID("\${$lpFieldName}['{$rpFieldName}']", $tableNameTopic, $tableSoleNameTopic, $tableName, $fieldNameParent, $t);

        return $ret;
    }

    /*
    *  @public function getXcGetVarFromID
    *  @param $left
    *  @param $anchor
    *  @param $var
    *  @param $tableName
    *  @param $fieldName
    *  @return string
    */
    public function getXcGetVarFromID($left, $anchor, $var, $tableName, $fieldName, $t = '')
    {
        $pVarFromID = TDMCreatePhpCode::getInstance();
        $ret = $pVarFromID->getPhpCodeCommentLine('Get Var', $fieldName, $t);
        $getVarFromID = self::getXcGetVar('', "\${$tableName}All[\$i]", $fieldName, true, '');
        $rightGet = self::getXcAnchorFunction($anchor.'Handler', 'get'.$var.'FromId', $getVarFromID);
        $ret .= self::getXcEqualsOperator($left, $rightGet, null, false, $t);

        return $ret;
    }

    /*
    *  @public function getXcUploadImageGetVar
    *  @param $lpFieldName
    *  @param $rpFieldName
    *  @param $tableName
    *  @param $fieldName
    *  @return string
    */
    public function getXcUploadImageGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName, $t = '')
    {
        $pUploadImage = TDMCreatePhpCode::getInstance();
        $ret = $pUploadImage->getPhpCodeCommentLine('Get Var', $fieldName, $t);
        $ret .= self::getXcGetVar($fieldName, "\${$tableName}All[\$i]", $fieldName, false, '');
        $ret .= $pUploadImage->getPhpCodeTernaryOperator("{$lpFieldName}['{$rpFieldName}']", "\${$fieldName}", "\${$fieldName}", "'blank.gif'", $t);

        return $ret;
    }
    /*
    *  @public function getXcUrlFileGetVar
    *  @param $lpFieldName
    *  @param $rpFieldName
    *  @param $tableName
    *  @param $fieldName
    *  @return string
    */
    public function getXcUrlFileGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName)
    {
        return self::getXcGetVarAll($lpFieldName, $rpFieldName, $tableName, $fieldName);
    }
    /*
    *  @public function getXcTextAreaGetVar
    *  @param $lpFieldName
    *  @param $rpFieldName
    *  @param $tableName
    *  @param $fieldName
    *  @return string
    */
    public function getXcTextAreaGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName, $t = '')
    {
        $phpCodeTextArea = TDMCreatePhpCode::getInstance();
        $getVar = self::getXcGetVar('', "\${$tableName}All[\$i]", $fieldName, true, '');

        return "{$t}".$phpCodeTextArea->getPhpCodeStripTags("{$lpFieldName}['{$rpFieldName}']", $getVar, false, $t);
    }

    /*
    *  @public function getXcSelectUserGetVar
    *  @param $lpFieldName
    *  @param $rpFieldName
    *  @param $tableName
    *  @param $fieldName
    * @return string
    */
    public function getXcSelectUserGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName, $t = '')
    {
        return "{$t}\${$lpFieldName}['{$rpFieldName}'] = XoopsUser::getUnameFromId(\${$tableName}All[\$i]->getVar('{$fieldName}'), 's');\n";
    }

    /*
    *  @public function getXcTextDateSelectGetVar
    *  @param $lpFieldName
    *  @param $rpFieldName
    *  @param $tableName
    *  @param $fieldName
    *  @return string
    */
    public function getXcTextDateSelectGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName, $t = '')
    {
        return "{$t}\${$lpFieldName}['{$rpFieldName}'] = formatTimeStamp(\${$tableName}All[\$i]->getVar('{$fieldName}'), 's');\n";
    }

    /*
    *  @public function getXcUserHeader
    *  @param $moduleDirname
    *  @param $tableName
    *  @return string
    */
    public function getXcXoopsOptionTemplateMain($moduleDirname, $tableName, $t = '')
    {
        return "{$t}\$GLOBALS['xoopsOption']['template_main'] = '{$moduleDirname}_{$tableName}.tpl';\n";
    }

    /*
    *  @public function getXcUserHeader
    *  @param $moduleDirname
    *  @param $tableName
    *  @return string
    */
    public function getXcUserHeader($moduleDirname, $tableName)
    {
        $phpCodeUserHeader = TDMCreatePhpCode::getInstance();
        $ret = $phpCodeUserHeader->getPhpCodeIncludeDir('__DIR__', 'header');
        $ret .= self::getXcXoopsOptionTemplateMain($moduleDirname, $tableName);
        $ret .= $phpCodeUserHeader->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'header', true);

        return $ret;
    }

    /*
    *  @public function getXcPermissionsHeader
    *  @param null
    */
    /**
     * @return string
     */
    public function getXcPermissionsHeader()
    {
        $phpCodePHeader = TDMCreatePhpCode::getInstance();
        $ret = $phpCodePHeader->getPhpCodeCommentLine('Permission');
        $ret .= $phpCodePHeader->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/xoopsform/grouppermform', true);
        $ret .= self::getXcEqualsOperator('$gpermHandler', "xoops_gethandler('groupperm')", true);
        $groups = self::getXcEqualsOperator('$groups', '$xoopsUser->getGroups()');
        $elseGroups = self::getXcEqualsOperator('$groups', 'XOOPS_GROUP_ANONYMOUS');
        $ret .= $phpCodePHeader->getPhpCodeConditions('is_object($xoopsUser)', '', $type = '', $groups, $elseGroups);

        return $ret;
    }

    /**
     *  @public function getXcGetFieldId
     *
     *  @param $fields
     *
     *  @return string
     */
    public function getXcGetFieldId($fields)
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
     *  @public function getXcGetFieldName
     *
     *  @param $fields
     *
     *  @return string
     */
    public function getXcGetFieldName($fields)
    {
        $fieldName = '';
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
        }

        return $fieldName;
    }

    /**
     *  @public function getXcGetFieldParentId
     *
     *  @param $fields
     *
     *  @return string
     */
    public function getXcGetFieldParentId($fields)
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
     *  @public function getXcUserSaveElements
     *
     *  @param $moduleDirname
     *  @param $tableName
     *  @param $fields
     *
     *  @return string
     */
    public function getXcUserSaveElements($moduleDirname, $tableName, $fields)
    {
        $axCodeUserSave = AdminXoopsCode::getInstance();
        $ret = '';
        $fieldMain = '';
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName;
            }
            if ((5 == $fieldElement) || (6 == $fieldElement)) {
                $ret .= self::getXcCheckBoxOrRadioYNSetVar($tableName, $fieldName);
            } elseif (13 == $fieldElement) {
                $ret .= $axCodeUserSave->getAxcUploadImageSetVar($moduleDirname, $tableName, $fieldName, $fieldMain);
            } elseif (14 == $fieldElement) {
                $ret .= $axCodeUserSave->getXcUploadFileSetVar($moduleDirname, $tableName, $fieldName);
            } elseif (15 == $fieldElement) {
                $ret .= self::getXcTextDateSelectSetVar($tableName, $fieldName);
            } else {
                $ret .= self::getXcSetVar($tableName, $fieldName, "\$_POST['{$fieldName}']");
            }
        }

        return $ret;
    }

    /*
    *  @public function getXcXoopsRequest
    *  @param $left
    *  @param $var1
    *  @param $var2
    *  @param $type
    *  @param $metod
    *  @return string
    */
    public function getXcXoopsRequest($left = '', $var1 = '', $var2 = '', $type = 'String', $metod = false, $t = '')
    {
        $ret = '';
        $intVars = ($var2 != '') ? "'{$var1}', {$var2}" : "'{$var1}'";
        if ($type == 'String') {
            $ret .= "{$t}\${$left} = XoopsRequest::getString('{$var1}', '{$var2}');\n";
        } elseif ($type == 'Int') {
            $ret .= "{$t}\${$left} = XoopsRequest::getInt({$intVars});\n";
        } elseif ($type == 'Int' && $metod !== false) {
            $ret .= "{$t}\${$left} = XoopsRequest::getInt({$intVars}, '{$metod}');\n";
        }

        return $ret;
    }

    /**
     *  @public function getXcTplAssign     
     *
     *  @param $tplString
     *  @param $phpRender
     *  @param $leftIsString 
     *
     *  @return string
     */
    public function getXcTplAssign($tplString, $phpRender, $leftIsString = true, $t = '')
    {
        $assign = "{$t}\$GLOBALS['xoopsTpl']->assign(";
        if ($leftIsString === false) {
            $ret = $assign."{$tplString}, {$phpRender});\n";
        } else {
            $ret = $assign."'{$tplString}', {$phpRender});\n";
        }

        return $ret;
    }

    /**
     *  @public function getXcXoopsTplAppend
     *
     *  @param $tplString
     *  @param $phpRender
     *
     *  @return string
     */
    public function getXcXoopsTplAppend($tplString, $phpRender, $t = '')
    {
        return "{$t}\$GLOBALS['xoopsTpl']->append('{$tplString}', {$phpRender});\n";
    }

    /**
     *  @public function getXcXoopsTplAppendByRef
     *
     *  @param $tplString
     *  @param $phpRender
     *
     *  @return string
     */
    public function getXcXoopsTplAppendByRef($tplString, $phpRender, $t = '')
    {
        return "{$t}\$GLOBALS['xoopsTpl']->appendByRef('{$tplString}', {$phpRender});\n";
    }

    /**
     *  @public function getXcPath
     *
     *  @param $directory
     *  @param $filename
     *  @param $isParam
     *
     *  @return string
     */
    public function getXcPath($directory, $filename, $isParam = false, $t = '')
    {
        if (!$isParam) {
            $ret = "{$t}\$GLOBALS['xoops']->path({$directory}.'/{$filename}.php');\n";
        } else {
            $ret = "\$GLOBALS['xoops']->path({$directory}.'/{$filename}.php')";
        }

        return $ret;
    }

    /**
     *  @public function getXcTplDisplay
     *
     *  @param $displayTpl
     *
     *  @return string
     */
    public function getXcTplDisplay($displayTpl = '{$templateMain}', $t = '')
    {
        return "{$t}\$GLOBALS['xoopsTpl']->display(\"db:{$displayTpl}\");\n";
    }

    /**
     *  @public function getXcGetInfo
     *
     *  @param $left
     *  @param $string
     *  @param $isParam
     *
     *  @return string
     */
    public function getXcGetInfo($left = '', $string, $isParam = false, $t = '')
    {
        if (!$isParam) {
            $ret = "{$t}\${$left} = \$GLOBALS['xoopsModule']->getInfo('{$string}');\n";
        } else {
            $ret = "\$GLOBALS['xoopsModule']->getInfo('{$string}')";
        }

        return $ret;
    }

    /**
     *  @public function getXcAddRight
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
    public function getXcAddRight($anchor, $permString = '', $var = '', $groups = '', $mid = '', $isParam = false, $t = '')
    {
        if (!$isParam) {
            $ret = "{$t}\${$anchor}->addRight('{$permString}', {$var}, {$groups}, {$mid});\n";
        } else {
            $ret = "\${$anchor}->addRight('{$permString}', {$var}, {$groups}, {$mid})";
        }

        return $ret;
    }

    /**
     *  @public function getXcCheckRight
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
    public function getXcCheckRight($anchor, $permString = '', $var = '', $groups = '', $mid = '', $isParam = false, $t = '')
    {
        if (!$isParam) {
            $ret = "{$t}{$anchor}->checkRight('{$permString}', {$var}, {$groups}, {$mid});\n";
        } else {
            $ret = "{$anchor}->checkRight('{$permString}', {$var}, {$groups}, {$mid})";
        }

        return $ret;
    }

    /**
     *  @public function getXcObjHandlerCreate
     *
     *  @param $tableName
     *
     *  @return string
     */
    public function getXcObjHandlerCreate($tableName, $t = '')
    {
        return "{$t}\${$tableName}Obj =& \${$tableName}Handler->create();\n";
    }

    /**
     *  @public function getXcObjHandlerCount
     *
     *  @param $tableName
     *
     *  @return string
     */
    public function getXcObjHandlerCount($tableName, $t = '')
    {
        $ucfTableName = ucfirst($tableName);
        $ret = "{$t}\${$tableName}Count = \${$tableName}Handler->getCount{$ucfTableName}();\n";

        return $ret;
    }

    /**
     *  @public function getXcClearCount
     *
     *  @param $tableName
     *
     *  @return string
     */
    public function getXcClearHandlerCount($left, $anchor = '', $params = '', $t = '')
    {
        $ret = "{$t}\${$left} = \${$anchor}Handler->getCount({$params});\n";

        return $ret;
    }

    /**
     *  @public function getXcObjHandlerAll
     *
     *  @param $tableName
     *  @param $fieldMain
     *  @param $start
     *  @param $limit
     *
     *  @return string
     */
    public function getXcObjHandlerAll($tableName, $fieldMain = '', $start = '0', $limit = '0', $t = '')
    {
        $ucfTableName = ucfirst($tableName);
        $startLimit = ($limit != '0') ? "{$start}, {$limit}" : '0';
        $params = ($fieldMain != '') ? "{$startLimit}, '{$fieldMain}'" : $startLimit;
        $ret = "{$t}\${$tableName}All = \${$tableName}Handler->getAll{$ucfTableName}({$params});\n";

        return $ret;
    }

    /**
     *  @public function getXcClearHandlerAll
     *
     *  @param $tableName
     *  @param $fieldMain
     *  @param $start
     *  @param $limit
     *
     *  @return string
     */
    public function getXcClearHandlerAll($left, $anchor = '', $params = '', $t = '')
    {
        $ret = "{$t}\${$left} = \${$anchor}Handler->getAll({$params});\n";

        return $ret;
    }

    /**
     *  @public function getXcGetValues
     *
     *  @param $tableName
     *  @param $tableSoleName
     *
     *  @return string
     */
    public function getXcGetValues($tableName, $tableSoleName, $index = 'i', $noArray = false, $t = '')
    {
        $ucfTableName = ucfirst($tableName);
        if (!$noArray) {
            $ret = "{$t}\${$tableSoleName} = \${$tableName}All[\${$index}]->getValues{$ucfTableName}();\n";
        } else {
            $ret = "{$t}\${$tableSoleName} = \${$tableName}->getValues{$ucfTableName}();\n";
        }

        return $ret;
    }

    /**
     *  @public function getXcSetVarsObjects
     *
     *  @param $moduleDirname
     *  @param $tableName
     *  @param $fields
     *
     *  @return string
     */
    public function getXcSetVarsObjects($moduleDirname, $tableName, $fields)
    {
        $axCode = AdminXoopsCode::getInstance();
        $ret = '';
        $fieldMain = '';
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
                        $ret .= self::getXcCheckBoxOrRadioYNSetVar($tableName, $fieldName);
                        break;
                    case 11:
                        $ret .= $axCode->getAxcImageListSetVar($moduleDirname, $tableName, $fieldName);
                        break;
                    case 12:
                        $ret .= $axCode->getAxcUploadFileSetVar($moduleDirname, $tableName, $fieldName, true);
                        break;
                    case 13:
                        $ret .= $axCode->getAxcUploadImageSetVar($moduleDirname, $tableName, $fieldName, $fieldMain);
                        break;
                    case 14:
                        $ret .= $axCode->getAxcUploadFileSetVar($moduleDirname, $tableName, $fieldName);
                        break;
                    case 15:
                        $ret .= self::getXcTextDateSelectSetVar($tableName, $fieldName);
                        break;
                    default:
                        $ret .= self::getXcSetVar($tableName, $fieldName, "\$_POST['{$fieldName}']");
                        break;
                }
            }
        }

        return $ret;
    }

    /**
     *  @public function getXcSecurity
     *
     *  @param $tableName
     *
     *  @return string
     */
    public function getXcSecurity($tableName, $t = '')
    {
        $phpCodeSecurity = TDMCreatePhpCode::getInstance();
        $securityError = self::getXcSecurityErrors();
        $implode = $phpCodeSecurity->getPhpCodeImplode(',', $securityError);
        $content = "{$t}\t".self::getXcRedirectHeader($tableName.'.php', '', 3, $implode, $t);
        $securityCheck = self::getXcSecurityCheck();

        return $phpCodeSecurity->getPhpCodeConditions('!'.$securityCheck, '', '', $content, $t);
    }

    /*
    *  @public function getXcInsertData
    *  @param $tableName
    *  @param $language
    *  @return string
    */
    public function getXcInsertData($tableName, $language, $t = '')
    {
        $phpCodeInsertData = TDMCreatePhpCode::getInstance();
        $content = "{$t}\t".self::getXcRedirectHeader($tableName.'.php', '?op=list', 2, "{$language}FORM_OK");
        $handlerInsert = self::getXcHandler($tableName, $tableName, false, true, false, 'Obj');

        return $phpCodeInsertData->getPhpCodeConditions($handlerInsert, '', '', $content, $t);
    }

    /*
    *  @public function getXcRedirectHeader
    *  @param $directory
    *  @param $options
    *  @param $numb
    *  @param $var
    *  @param $isString
    *
    *  @return string
    */
    public function getXcRedirectHeader($directory, $options = '', $numb = '2', $var, $isString = true, $t = '')
    {
        $ret = '';
        if (!$isString) {
            $ret = "{$t}redirect_header({$directory}, {$numb}, {$var});\n";
        } else {
            $ret = "{$t}redirect_header('{$directory}{$options}', {$numb}, {$var});\n";
        }

        return $ret;
    }

    /*
    *  @public function getXcXoopsConfirm
    *  @param $tableName
    *  @param $language
    *  @param $fieldId
    *  @param $fieldMain    
    *  @param $options
    *
    *  @return string
    */
    public function getXcXoopsConfirm($tableName, $language, $fieldId, $fieldMain, $options = 'delete', $t = '')
    {
        $stuOptions = strtoupper($options);
        $ccFieldId = TDMCreateFile::getInstance()->getCamelCase($fieldId, false, true);
        $phpXoopsConfirm = TDMCreatePhpCode::getInstance();
        $array = "array('ok' => 1, '{$fieldId}' => \${$ccFieldId}, 'op' => '{$options}')";
        $server = $phpXoopsConfirm->getPhpCodeGlobalsVariables('REQUEST_URI', 'SERVER');
        $getVar = self::getXcGetVar('', $tableName.'Obj', $fieldMain, true, '');
        $sprintf = $phpXoopsConfirm->getPhpCodeSprintf($language.'FORM_SURE_'.$stuOptions, $getVar);
        $ret = "{$t}xoops_confirm({$array}, {$server}, {$sprintf});\n";

        return $ret;
    }

    /*
    *  @public function getXcAddStylesheet
    *  @param $style
    *  
    *  @return string
    */
    public function getXcAddStylesheet($style = 'style', $t = '')
    {
        return "{$t}\$GLOBALS['xoTheme']->addStylesheet( \${$style}, null );\n";
    }

    /*
    *  @public function getXcSecurityCheck
    *  @param $denial
    *  @return boolean
    */
    public function getXcSecurityCheck($denial = '')
    {
        return "{$denial}\$GLOBALS['xoopsSecurity']->check()";
    }

    /*
    *  @public function getXcSecurityErrors
    *  @param null
    *  @return string
    */
    public function getXcSecurityErrors()
    {
        return "\$GLOBALS['xoopsSecurity']->getErrors()";
    }

    /**
     *  @public function getXcHtmlErrors
     *
     *  @param $tableName
     *  @param $isParam
     *  @param $obj
     *
     *  @return string
     */
    public function getXcHtmlErrors($tableName, $isParam = false, $obj = 'Obj', $t = '')
    {
        $ret = '';
        if ($isParam) {
            $ret = "\${$tableName}{$obj}->getHtmlErrors()";
        } else {
            $ret = "{$t}\${$tableName}{$obj} =& \${$tableName}->getHtmlErrors();";
        }

        return $ret;
    }

    /**
     *  @public function getXcObjHandlerCount
     *
     *  @param $left
     *  @param $tableName
     *  @param $obj
     *
     *  @return string
     */
    public function getXcGetForm($left, $tableName, $obj = '', $t = '')
    {
        $ucfTableName = ucfirst($tableName);

        return "{$t}\${$left} =& \${$tableName}{$obj}->getForm{$ucfTableName}();\n";
    }

    /**
     *  @public function getXcGet
     *
     *  @param $left
     *  @param $var
     *  @param $obj
     *  @param $handler
     *  @param $isParam
     *
     *  @return string
     */
    public function getXcGet($left, $var, $obj = '', $handler = 'Handler', $isParam = false, $t = '')
    {
        $ret = '';
        if ($isParam) {
            $ret = "\${$left}{$handler}->get(\${$var})";
        } else {
            $ret = "{$t}\${$left}{$obj} =& \${$handler}->get(\${$var});\n";
        }

        return $ret;
    }

    /**
     *  @public function getXcHandler
     *
     *  @param $left
     *  @param $var
     *  @param $obj
     *  @param $isHandler
     *
     *  @return string
     */
    public function getXcInsert($left, $var, $obj = '', $handler = 'Handler')
    {
        return "\${$left}{$handler}->insert(\${$var}{$obj})";
    }

    /**
     *  @public function getXcDelete
     *
     *  @param $tableName
     *  @param $var
     *  @param $obj
     *  @param $isHandler
     *
     *  @return string
     */
    public function getXcDelete($left, $var, $obj = '', $handler = 'Handler')
    {
        return "\${$left}{$handler}->delete(\${$var}{$obj})";
    }

    /**
     *  @public function getXcHandler
     *
     *  @param $left
     *  @param $var
     *
     *  @return string
     */
    public function getXcHandler($left, $var, $get = false, $insert = false, $delete = false, $obj = '', $t = '')
    {
        $ret = '';
        if ($get) {
            $ret = "{$t}\${$left}Handler->get(\${$var});";
        } elseif ($insert && ($obj != '')) {
            $ret = "{$t}\${$left}Handler->insert(\${$var}{$obj});";
        } elseif ($delete && ($obj != '')) {
            $ret = "{$t}\${$left}Handler->delete(\${$var}{$obj});";
        }

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
    public function getTopicGetVar($lpFieldName, $rpFieldName, $tableName, $tableNameTopic, $fieldNameParent, $fieldNameTopic, $t = '')
    {
        $ret = TDMCreatePhpCode::getInstance()->getPhpCodeCommentLine('Get Var', $fieldNameParent, $t);
        $paramGet = self::getXcGetVar('', "\${$tableName}All[\$i]", $fieldNameParent, true, '');
        $ret .= self::getXcGet($rpFieldName, $paramGet, '', $tableNameTopic.'Handler', false, $t);
        $ret .= self::getXcGetVar("\${$lpFieldName}['{$rpFieldName}']", "\${$rpFieldName}", $fieldNameTopic, false, $t);

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
    public function getUploadImageGetVar($lpFieldName, $rpFieldName, $tableName, $fieldName, $t = '')
    {
        $pImageGetVar = TDMCreatePhpCode::getInstance();
        $ret = $pImageGetVar->getPhpCodeCommentLine('Get Var', $fieldName, $t);
        $ret .= self::getXcGetVar($fieldName, "\${$tableName}All[\$i]", $fieldName, false, '');
        $ret .= $pImageGetVar->getPhpCodeTernaryOperator('uploadImage', "\${$fieldName}", "\${$fieldName}", "'blank.gif'", $t);
        $ret .= self::getXcEqualsOperator("${$lpFieldName}['{$rpFieldName}']", '$uploadImage', null, false, $t);

        return $ret;
    }

    /**
     *  @public function getXcSaveFieldId
     *
     *  @param $fields
     *
     *  @return string
     */
    public function getXcSaveFieldId($fields)
    {
        $fieldId = '';
        foreach (array_keys($fields) as $f) {
            if (0 == $f) {
                $fieldId = $fields[$f]->getVar('field_name');
            }
        }

        return $fieldId;
    }

    /**
     *  @public function getXcSaveFieldMain
     *
     *  @param $fields
     *
     *  @return string
     */
    public function getXcSaveFieldMain($fields)
    {
        $fieldMain = '';
        foreach (array_keys($fields) as $f) {
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fields[$f]->getVar('field_name');
            }
        }

        return $fieldMain;
    }

    /**
     *  @public function getXcSaveElements
     *
     *  @param $moduleDirname
     *  @param $tableName
     *  @param $fields
     *
     *  @return string
     */
    public function getXcSaveElements($moduleDirname, $tableName, $fields)
    {
        $axCodeSaveElements = AdminXoopsCode::getInstance();
        $ret = '';
        $fieldMain = '';
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            $fieldElement = $fields[$f]->getVar('field_element');
            if (1 == $fields[$f]->getVar('field_main')) {
                $fieldMain = $fieldName;
            }
            if ((5 == $fieldElement) || (6 == $fieldElement)) {
                $ret .= self::getXcCheckBoxOrRadioYNSetVar($tableName, $fieldName);
            } elseif (13 == $fieldElement) {
                $ret .= $axCodeSaveElements->getAxcUploadImageSetVar($moduleDirname, $tableName, $fieldName, $fieldMain);
            } elseif (14 == $fieldElement) {
                $ret .= $axCodeSaveElements->getAxcUploadFileSetVar($moduleDirname, $tableName, $fieldName);
            } elseif (15 == $fieldElement) {
                $ret .= self::getXcTextDateSelectSetVar($tableName, $fieldName);
            } else {
                $ret .= self::getXcSetVar($tableName, $fieldName, "\$_POST['{$fieldName}']");
            }
        }

        return $ret;
    }

    /*
    *  @public function getXcPageNav
    *  @param $tableName
    *
    *  @return string
    */
    public function getXcPageNav($tableName, $t = '')
    {
        $phpCodePageNav = TDMCreatePhpCode::getInstance();
        $classXCode = ClassXoopsCode::getInstance();
        $ret = $phpCodePageNav->getPhpCodeCommentLine('Display Navigation', null, $t);
        $condition = $phpCodePageNav->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'class/pagenav', true, false, 'include', $t."\t");
        $condition .= $classXCode->getClassXoopsPageNav('pagenav', $tableName.'Count', 'limit', 'start', 'start', "'op=list&limit=' . \$limit", false, $t."\t");
        $condition .= self::getXcTplAssign('pagenav', '$pagenav->renderNav(4)', true, $t."\t");
        $ret .= $phpCodePageNav->getPhpCodeConditions("\${$tableName}Count", ' > ', '$limit', $condition, false, $t);

        return $ret;
    }
}
