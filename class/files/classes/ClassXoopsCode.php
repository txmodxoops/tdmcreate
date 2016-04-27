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
 * @version         $Id: ClassXoopsCode.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class ClassXoopsCode.
 */
class ClassXoopsCode
{
    /*
    *  @static function getInstance
    *  @param null
    */
    /**
     * @return ClassXoopsCode
     */
    public static function getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /*
     * @public function getClassCriteriaCompo
     * @param $var
     *  
     * @return string
     */
    public function getClassCriteriaCompo($var, $t = '')
    {
        return "{$t}\${$var} = new CriteriaCompo();\n";
    }

    /*
     * @public function getClassCriteria
     * @param $var
     * @param $param1
     * @param $param2
     * @param $param3
     *  
     * @return string
     */
    public function getClassCriteria($var, $param1, $param2 = '', $param3 = '', $isParam = false, $t = '')
    {
        $params = ($param2 != '') ? ', '.$param2 : '';
        $params .= ($param3 != '') ? ', '.$param3 : '';

        if ($isParam === false) {
            $ret = "{$t}\${$var} = new Criteria( {$param1}{$params} );\n";
        } else {
            $ret = "new Criteria( {$param1}{$params} )";
        }

        return $ret;
    }

    /*
     * @public function getClassAdd
     * @param $var
     * @param $param
     *  
     * @return string
     */
    public function getClassAdd($var, $param, $t = '')
    {
        return "{$t}\${$var}->add( {$param} );\n";
    }

    /*
     * @public function getClassSetStart
     * @param $var
     * @param $start
     *  
     * @return string
     */
    public function getClassSetStart($var, $start, $t = '')
    {
        return "{$t}\${$var}->setStart( \${$start} );\n";
    }

    /*
     * @public function getClassSetLimit
     * @param $var
     * @param $limit
     *  
     * @return string
     */
    public function getClassSetLimit($var, $limit, $t = '')
    {
        return "{$t}\${$var}->setLimit( \${$limit} );\n";
    }

    /*
     * @public function getClassAdd
     * @param $var
     * @param $sort
     *  
     * @return string
     */
    public function getClassSetSort($var, $sort, $t = '')
    {
        return "{$t}\${$var}->setSort( \${$sort} );\n";
    }

    /*
     * @public function getClassAdd
     * @param $var
     * @param $order
     *  
     * @return string
     */
    public function getClassSetOrder($var, $order, $t = '')
    {
        return "{$t}\${$var}->setOrder( \${$order} );\n";
    }

    /*
     * @public function getClassAdd
     * @param $paramLeft
     * @param $paramRight
     * @param $var
     * @param $t = Indentation
     *  
     * @return string
     */
    public function getClassInitVar($paramLeft = '', $paramRight = '', $var = 'this', $t = "\t\t")
    {
        $stuParamRight = strtoupper($paramRight);

        return "{$t}\${$var}->initVar('{$paramLeft}', XOBJ_DTYPE_{$stuParamRight});\n";
    }

    /*
     * @public function getClassXoopsPageNav
     * @param $var
     * @param $param1
     * @param $param2
     * @param $param3
     * @param $param4
     * @param $param5
     * @param $isParam
     * @param $t = Indentation
     *  
     * @return string
     */
    public function getClassXoopsPageNav($var, $param1, $param2 = null, $param3 = null, $param4 = null, $param5 = null, $isParam = false, $t = '')
    {
        $xPageNav = 'new XoopsPageNav(';
        if ($isParam === false) {
            $ret = "{$t}\${$var} = {$xPageNav}\${$param1}, \${$param2}, \${$param3}, '{$param4}', {$param5});\n";
        } else {
            $ret = "{$xPageNav}\${$param1}, \${$param2}, \${$param3}, '{$param4}', {$param5})";
        }

        return $ret;
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
    public function getXoopsSimpleForm($left = '', $element = '', $elementsContent = '', $caption = '', $var = '', $filename = '', $type = 'post', $t = '')
    {
        $ret = "{$t}\${$left} = new XoopsSimpleForm({$caption}, '{$var}', '{$filename}.php', '{$type}');\n";
        if (!empty($elementsContent)) {
            $ret .= "{$elementsContent}";
        }
        $ret .= "{$t}\${$left}->addElement(\${$element});\n";
        $ret .= "{$t}\${$left}->display();\n";

        return $ret;
    }

    /*
     * @public function getClassXoopsThemeForm
     * @param $var
     * @param $param1
     * @param $param2
     * @param $param3
     * @param $param4
     * @param $isParam
     * @param $t = Indentation
     *  
     * @return string
     */
    public function getClassXoopsThemeForm($var, $param1, $param2 = null, $param3 = null, $param4 = null, $isParam = false, $t = "\t\t")
    {
        $themeForm = 'new XoopsThemeForm(';
        if ($isParam === false) {
            $ret = "{$t}\${$var} = {$themeForm}\${$param1}, '{$param2}', \${$param3}, '{$param4}', true);\n";
        } else {
            $ret = "{$themeForm}\${$param1}, '{$param2}', \${$param3}, '{$param4}', true)";
        }

        return $ret;
    }

    /*
     * @public function XoopsFormElementTray
     * @param $var
     * @param $param1
     * @param $param2
     *  
     * @return string
     */
    public function getClassXoopsFormElementTray($var, $param1, $param2 = '', $t = "\t\t")
    {
        return "{$t}\${$var} = new XoopsFormElementTray({$param1}, '{$param2}' );\n";
    }

    /*
     * @public function getClassXoopsFormLabel
     * @param $var
     * @param $param1
     * @param $param2
     *  
     * @return string
     */
    public function getClassXoopsFormLabel($var, $param1 = '', $param2 = null, $isParam = false, $t = "\t\t")
    {
        $label = 'new XoopsFormLabel(';
        $params = $param2 != null ? "{$param1}, {$param2}" : $param1;
        if ($isParam === false) {
            $ret = "{$t}\${$var} = {$label}{$params});\n";
        } else {
            $ret = "{$label}{$params})";
        }

        return $ret;
    }

    /*
     * @public function getClassXoopsFormFile
     * @param $var
     * @param $param1
     * @param $param2
     * @param $param3
     *  
     * @return string
     */
    public function getClassXoopsFormFile($var, $param1, $param2, $param3, $isParam = false, $t = "\t\t")
    {
        $file = 'new XoopsFormFile( ';
        if ($isParam === false) {
            $ret = "{$t}\${$var} = {$file}{$param1}, '{$param2}', {$param3} );\n";
        } else {
            $ret = "{$file}{$param1}, '{$param2}', {$param3} )";
        }

        return $ret;
    }

    /*
     * @public function getClassXoopsFormHidden
     * @param $var
     * @param $param1
     * @param $param2
     *  
     * @return string
     */
    public function getClassXoopsFormHidden($var, $param1, $param2, $isForm = false, $isParam = false, $t = "\t\t")
    {
        $hidden = 'new XoopsFormHidden( ';
        $getVarHidden = TDMCreateXoopsCode::getInstance()->getXcGetVar('', 'this', $param2, true);
        $ret = '';
        if ($isParam === false) {
            $ret .= "{$t}\${$var} = {$hidden}{$param1}, {$getVarHidden} );\n";
        } else {
            if ($isForm === false) {
                $ret .= "{$hidden}{$param1}, {$param2} )";
            } else {
                $ret .= "{$hidden}'{$param1}', '{$param2}' )";
            }
        }

        return $ret;
    }

    /*
     * @public function getClassXoopsFormText
     * @param $var
     * @param $param1
     * @param $param2
     * @param $param3
     * @param $param4
     * @param $param5
     *  
     * @return string
     */
    public function getClassXoopsFormText($var, $param1, $param2, $param3 = 75, $param4 = 255, $param5, $isParam = false, $t = "\t\t")
    {
        $text = 'new XoopsFormText( ';
        if ($isParam === false) {
            $ret = "{$t}\${$var} = {$text}{$param1}, '{$param2}', {$param3}, {$param4}, \${$param5} );\n";
        } else {
            $ret = "{$text}{$param1}, '{$param2}', {$param3}, {$param4}, \${$param5} )";
        }

        return $ret;
    }

    /*
     * @public function getClassXoopsFormTextArea
     * @param $var
     * @param $param1
     * @param $param2
     * @param $param3
     * @param $param4
     * @param $param5
     *  
     * @return string
     */
    public function getClassXoopsFormTextArea($var, $param1, $param2, $param3, $param4, $isParam = false, $t = "\t\t")
    {
        $area = 'new XoopsFormTextArea( ';
        $getVarTextArea = TDMCreateXoopsCode::getInstance()->getXcGetVar('', 'this', $param2, true);
        if ($isParam === false) {
            $ret = "{$t}\${$var} = {$area}{$param1}, '{$param2}', {$getVarTextArea}, {$param3}, {$param4} );\n";
        } else {
            $ret = "{$area}{$param1}, '{$param2}', {$getVarTextArea}, {$param3}, {$param4} )";
        }

        return $ret;
    }

    /*
     * @public function getClassXoopsFormColorPicker
     * @param $var
     * @param $param1
     * @param $param2
     * @param $param3
     *  
     * @return string
     */
    public function getClassXoopsFormColorPicker($var, $param1, $param2, $param3, $isParam = false, $t = "\t\t")
    {
        $picker = 'new XoopsFormColorPicker( ';
        if ($isParam === false) {
            $ret = "{$t}\${$var} = {$picker}{$param1}, '{$param2}', {$param3} );\n";
        } else {
            $ret = "{$picker}{$param1}, '{$param2}', {$param3} )";
        }

        return $ret;
    }

    /*
     * @public function getClassXoopsFormSelectUser
     * @param $var
     * @param $param1
     * @param $param2
     * @param $param3
     *  
     * @return string
     */
    public function getClassXoopsFormSelectUser($var, $param1, $param2, $param3 = 'false', $param4, $isParam = false, $t = "\t\t")
    {
        $user = 'new XoopsFormSelectUser( ';
        $getVarSelectUser = TDMCreateXoopsCode::getInstance()->getXcGetVar('', 'this', $param4, true);
        if ($isParam === false) {
            $ret = "{$t}\${$var} = {$user}{$param1}, '{$param2}', {$param3}, {$getVarSelectUser} );\n";
        } else {
            $ret = "{$user}{$param1}, '{$param2}', {$param3}, {$getVarSelectUser} )";
        }

        return $ret;
    }

    /*
     * @public function getClassXoopsFormTextDateSelect
     * @param $var
     * @param $param1
     * @param $param2
     * @param $param3
     *  
     * @return string
     */
    public function getClassXoopsFormTextDateSelect($var, $param1, $param2, $param3 = '', $param4, $isParam = false, $t = "\t\t")
    {
        $tdate = 'new XoopsFormTextDateSelect( ';
        $getVarTextDateSelect = TDMCreateXoopsCode::getInstance()->getXcGetVar('', 'this', $param4, true);
        if ($isParam === false) {
            $ret = "{$t}\${$var} = {$tdate}{$param1}, '{$param2}', {$param3}, {$getVarTextDateSelect} );\n";
        } else {
            $ret = "{$tdate}{$param1}, '{$param2}', {$param3}, {$getVarTextDateSelect} )";
        }

        return $ret;
    }

    /*
     * @public function getClassXoopsFormEditor
     * @param $var
     * @param $param1
     * @param $param2
     * @param $param3
     *  
     * @return string
     */
    public function getClassXoopsFormEditor($var, $param1, $param2, $param3, $isParam = false, $t = "\t\t")
    {
        $editor = 'new XoopsFormEditor( ';
        if ($isParam === false) {
            $ret = "{$t}\${$var} = {$editor}{$param1}, '{$param2}', \${$param3});\n";
        } else {
            $ret = "{$editor}{$param1}, '{$param2}', \${$param3})";
        }

        return $ret;
    }

    /*
     * @public function getClassXoopsFormCheckBox
     * @param $var
     * @param $param1
     * @param $param2
     * @param $param3
     *  
     * @return string
     */
    public function getClassXoopsFormCheckBox($var, $param1, $param2, $param3, $isParam = false, $t = "\t\t")
    {
        $checkBox = 'new XoopsFormCheckBox( ';
        if ($isParam === false) {
            $ret = "{$t}\${$var} = {$checkBox}{$param1}, '{$param2}', {$param3});\n";
        } else {
            $ret = "{$checkBox}{$param1}, '{$param2}', {$param3})";
        }

        return $ret;
    }

    /*
     * @public function getClassXoopsFormRadioYN
     * @param $var
     * @param $param1
     * @param $param2
     * @param $param3
     *  
     * @return string
     */
    public function getClassXoopsFormRadioYN($var, $param1, $param2, $param3, $isParam = false, $t = "\t\t")
    {
        $radioYN = 'new XoopsFormRadioYN( ';
        if ($isParam === false) {
            $ret = "{$t}\${$var} = {$radioYN}{$param1}, '{$param2}', \${$param3});\n";
        } else {
            $ret = "{$radioYN}{$param1}, '{$param2}', \${$param3})";
        }

        return $ret;
    }

    /*
     * @public function getClassXoopsFormSelect
     * @param $var
     * @param $param1
     * @param $param2
     * @param $param3
     * @param $param4
     * @param $param5
     *  
     * @return string
     */
    public function getClassXoopsFormSelect($var, $param1, $param2, $param3, $param4 = null, $param5 = null, $isParam = false, $t = "\t\t")
    {
        $otherParam = $param4 != null ? ", {$param4}" : ($param5 != null ? ", {$param5}" : '');
        $select = 'new XoopsFormSelect( ';
        if ($isParam === false) {
            $ret = "{$t}\${$var} = {$select}{$param1}, '{$param2}', \${$param3}{$otherParam});\n";
        } else {
            $ret = "{$select}{$param1}, '{$param2}', \${$param3}{$otherParam})";
        }

        return $ret;
    }

    /*
     * @public function getClassXoopsFormTag
     * @param $var
     * @param $param1
     * @param $param2
     * @param $param3
     * @param $param4
     * @param $param5
     * @param $isParam
     *  
     * @return string
     */
    public function getClassXoopsFormTag($var, $param1, $param2, $param3, $param4, $param5 = 0, $isParam = false, $t = "\t\t")
    {
        $tag = 'new XoopsFormTag( ';
        if ($isParam === false) {
            $ret = "{$t}\${$var} = {$tag}'{$param1}', {$param2}, {$param3}, \${$param4}, {$param5} );\n";
        } else {
            $ret = "{$tag}'{$param1}', {$param2}, {$param3}, \${$param4}, {$param5} )";
        }

        return $ret;
    }

    /*
     * @public function getClassXoopsFormButton
     * @param $var
     * @param $param1
     * @param $param2
     * @param $param3
     * @param $param4
     * @param $isParam
     *  
     * @return string
     */
    public function getClassXoopsFormButton($var, $param1, $param2, $param3, $param4, $isParam = false, $t = "\t\t")
    {
        $button = 'new XoopsFormButton( ';
        if ($isParam === false) {
            $ret = "{$t}\${$var} = {$button}'{$param1}', '{$param2}', {$param3}, '{$param4}');\n";
        } else {
            $ret = "{$button}'{$param1}', '{$param2}', {$param3}, '{$param4}')";
        }

        return $ret;
    }

    /**
     *  @public function getClassXoopsObjectTree
     *
     *  @param $var
     *  @param $tableName
     *  @param $fieldId
     *  @param $fieldParent
     *
     *  @return string
     */
    public function getClassXoopsObjectTree($var = 'mytree', $param1, $param2, $param3, $t = '')
    {
        $ret = "{$t}\${$var} = new XoopsObjectTree(\${$param1}, '{$param2}', '{$param3}');\n";

        return $ret;
    }

    /**
     *  @public function getClassXoopsMakeSelBox
     *
     *  @param $var
     *  @param $anchor
     *  @param $param1
     *  @param $param2
     *  @param $param3
     *  @param $param4
     *
     *  @return string
     */
    public function getClassXoopsMakeSelBox($var, $anchor, $param1, $param2, $param3 = '--', $param4, $t = '')
    {
        $getVar = TDMCreateXoopsCode::getInstance()->getXcGetVar('', 'this', $param4, true);
        $ret = "{$t}\${$var} = \${$anchor}->makeSelBox( '{$param1}', '{$param2}', '{$param3}', {$getVar}, true );\n";

        return $ret;
    }

    /*
     * @public function getClassAddOption
     * @param $var
     * @param $params
     *  
     * @return string
     */
    public function getClassAddOption($var, $params, $t = "\t\t")
    {
        return "{$t}\${$var}->addOption({$params});\n";
    }

    /*
     * @public function getClassAddOptionArray
     * @param $var
     * @param $params
     *  
     * @return string
     */
    public function getClassAddOptionArray($var, $params, $t = "\t\t")
    {
        return "{$t}\${$var}->addOptionArray({$params});\n";
    }

    /*
     * @public function getClassAddElement
     * @param $var
     * @param $params
     *  
     * @return string
     */
    public function getClassAddElement($var = '', $params = '', $t = "\t\t")
    {
        return "{$t}\${$var}->addElement({$params});\n";
    }

    /*
     * @public function getClassSetDescription
     * @param $var
     * @param $params
     *  
     * @return string
     */
    public function getClassSetDescription($var, $params, $t = "\t\t")
    {
        return "{$t}\${$var}->setDescription({$params});\n";
    }

    /*
     * @public function getClassSetExtra
     * @param $var
     * @param $params
     *  
     * @return string
     */
    public function getClassSetExtra($var, $params, $t = "\t\t")
    {
        return "{$t}\${$var}->setExtra({$params});\n";
    }
}
