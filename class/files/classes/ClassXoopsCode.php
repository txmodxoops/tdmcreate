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
    * @var mixed
    */
    private $phpcode = null;

    /*
    * @var mixed
    */
    private $xoopscode = null;

    /*
    *  @public function constructor
    *  @param null
    */
    /**
     *
     */
    public function __construct()
    {
        $this->phpcode = TDMCreatePhpCode::getInstance();
        $this->xoopscode = TDMCreateXoopsCode::getInstance();
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return ClassXoopsCode
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
     * @public function getClassCriteriaCompo
     * @param $var
     *  
     * @return string
     */
    public function getClassCriteriaCompo($var)
    {
        return "\${$var} = new CriteriaCompo();\n";
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
    public function getClassCriteria($var, $param1, $param2 = '', $param3 = '')
    {
        $params = ($param2 != '') ? ', '.$param2 : '';
        $params .= ($param3 != '') ? ', '.$param3 : '';

        return "\${$var} = new Criteria( {$param1}{$params} );\n";
    }

    /*
     * @public function getClassAdd
     * @param $var
     * @param $param
     *  
     * @return string
     */
    public function getClassAdd($var, $param)
    {
        return "\${$var}->add( {$param} );\n";
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
     * @public function XoopsFormElementTray
     * @param $var
     * @param $param1
     * @param $param2
     *  
     * @return string
     */
    public function getClassXoopsFormElementTray($var, $param1, $param2 = '', $isParam = false)
    {
        $tray = 'new XoopsFormElementTray(';
        if ($isParam === false) {
            $ret = "\${$var} = {$tray}{$param1}, '{$param2}' );\n";
        } else {
            $ret = "{$tray}{$param1}, '{$param2}' )";
        }

        return $ret;
    }

    /*
     * @public function getClassXoopsFormLabel
     * @param $var
     * @param $param1
     * @param $param2
     *  
     * @return string
     */
    public function getClassXoopsFormLabel($var, $param1 = '', $isParam = false)
    {
        $label = 'new XoopsFormLabel(';
        if ($isParam === false) {
            $ret = "\${$var} = {$label}{$param1});\n";
        } else {
            $ret = "{$label}{$param1})";
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
    public function getClassXoopsFormFile($var, $param1, $param2, $param3, $isParam = false)
    {
        $file = 'new XoopsFormFile( ';
        if ($isParam === false) {
            $ret = "\${$var} = {$file}{$param1}, '{$param2}', {$param3} );\n";
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
    public function getClassXoopsFormHidden($var, $param1, $param2, $isForm = false, $isParam = false)
    {
        $hidden = 'new XoopsFormHidden( ';
        $getVarHidden = $this->xoopscode->getXoopsCodeGetVar('', 'this', $param2, true);
        if ($isParam === false) {
            $ret = "\${$var} = {$hidden}{$param1}, {$getVarHidden} );\n";
        } else {
            if ($isForm === false) {
                $ret = "{$hidden}{$param1}, {$getVar} )";
            } else {
                $ret = "{$hidden}'{$param1}', '{$param2}' )";
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
    public function getClassXoopsFormText($var, $param1, $param2, $param3 = 75, $param4 = 255, $param5, $isParam = false)
    {
        $text = 'new XoopsFormText( ';
        if ($isParam === false) {
            $ret = "\${$var} = {$text}{$param1}, '{$param2}', {$param3}, {$param4}, \${$param5} );\n";
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
    public function getClassXoopsFormTextArea($var, $param1, $param2, $param3, $param4, $isParam = false)
    {
        $area = 'new XoopsFormTextArea( ';
        $getVarTextArea = $this->xoopscode->getXoopsCodeGetVar('', 'this', $param2, true);
        if ($isParam === false) {
            $ret = "\${$var} = {$area}{$param1}, '{$param2}', {$getVarTextArea}, {$param3}, {$param4} );\n";
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
    public function getClassXoopsFormColorPicker($var, $param1, $param2, $param3, $isParam = false)
    {
        $picker = 'new XoopsFormColorPicker( ';
        if ($isParam === false) {
            $ret = "\${$var} = {$picker}{$param1}, '{$param2}', {$param3} );\n";
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
    public function getClassXoopsFormSelectUser($var, $param1, $param2, $param3 = 'false', $param4, $isParam = false)
    {
        $user = 'new XoopsFormSelectUser( ';
        $getVarSelectUser = $this->xoopscode->getXoopsCodeGetVar('', 'this', $param4, true);
        if ($isParam === false) {
            $ret = "\${$var} = {$user}{$param1}, '{$param2}', {$param3}, {$getVarSelectUser} );\n";
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
    public function getClassXoopsFormTextDateSelect($var, $param1, $param2, $param3 = '', $param4, $isParam = false)
    {
        $tdate = 'new XoopsFormTextDateSelect( ';
        $getVarTextDateSelect = $this->xoopscode->getXoopsCodeGetVar('', 'this', $param4, true);
        if ($isParam === false) {
            $ret = "\${$var} = {$tdate}{$param1}, '{$param2}', {$param3}, {$getVarTextDateSelect} );\n";
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
    public function getClassXoopsFormEditor($var, $param1, $param2, $param3, $isParam = false)
    {
        $editor = 'new XoopsFormEditor( ';
        if ($isParam === false) {
            $ret = "\${$var} = {$editor}{$param1}, '{$param2}', \${$param3});\n";
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
    public function getClassXoopsFormCheckBox($var, $param1, $param2, $param3, $isParam = false)
    {
        $checkBox = 'new XoopsFormCheckBox( ';
        if ($isParam === false) {
            $ret = "\${$var} = {$checkBox}{$param1}, '{$param2}', {$param3});\n";
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
    public function getClassXoopsFormRadioYN($var, $param1, $param2, $param3, $isParam = false)
    {
        $radioYN = 'new XoopsFormRadioYN( ';
        if ($isParam === false) {
            $ret = "\${$var} = {$radioYN}{$param1}, '{$param2}', \${$param3});\n";
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
    public function getClassXoopsFormSelect($var, $param1, $param2, $param3, $param4, $param5 = 5, $isParam = false)
    {
        $select = 'new XoopsFormSelect( ';
        if ($isParam === false) {
            $ret = "\${$var} = {$select}{$param1}, \".{\${$param2}}/\", '{$param3}', \${$param4}, {$param5} );\n";
        } else {
            $ret = "{$select}{$param1}, \".{\${$param2}}/\", '{$param3}', \${$param4}, {$param5} )";
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
     *  
     * @return string
     */
    public function getClassXoopsFormTag($var, $param1, $param2, $param3, $param4, $param5 = 0, $isParam = false)
    {
        $tag = 'new XoopsFormTag( ';
        if ($isParam === false) {
            $ret = "\${$var} = {$tag}'{$param1}', {$param2}, {$param3}, \${$param4}, {$param5} );\n";
        } else {
            $ret = "{$tag}'{$param1}', {$param2}, {$param3}, \${$param4}, {$param5} )";
        }

        return $ret;
    }

    /*
     * @public function getClassAddOption
     * @param $var
     * @param $params
     *  
     * @return string
     */
    public function getClassAddOption($var, $params)
    {
        return "\${$var}->addOption({$params});\n";
    }

    /*
     * @public function getClassAddOptionArray
     * @param $var
     * @param $params
     *  
     * @return string
     */
    public function getClassAddOptionArray($var, $params)
    {
        return "\${$var}->addOptionArray({$params});\n";
    }

    /*
     * @public function getClassAddElement
     * @param $var
     * @param $params
     *  
     * @return string
     */
    public function getClassAddElement($var = '', $params = '')
    {
        return "\${$var}->addElement({$params});\n";
    }

    /*
     * @public function getClassSetDescription
     * @param $var
     * @param $params
     *  
     * @return string
     */
    public function getClassSetDescription($var, $params)
    {
        return "\${$var}->setDescription({$params});\n";
    }

    /*
     * @public function getClassSetExtra
     * @param $var
     * @param $params
     *  
     * @return string
     */
    public function getClassSetExtra($var, $params)
    {
        return "\${$var}->setExtra({$params});\n";
    }
}
