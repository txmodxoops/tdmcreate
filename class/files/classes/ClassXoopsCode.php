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
     * @public function XoopsFormElementTray
     * @param $var
     * @param $param1
	 * @param $param2
     *  
     * @return string
     */
    public function getClassXoopsFormElementTray($var, $param1, $param2 = '')
    {
        return "\${$var} = new XoopsFormElementTray( {$param1}, '{$param2}' );\n";
    }
	
	/*
     * @public function getClassXoopsFormLabel
     * @param $var
     * @param $param1
	 * @param $param2
     *  
     * @return string
     */
    public function getClassXoopsFormLabel($var, $param1 = '')
    {
        return "\${$var} = new XoopsFormLabel('{$param1}');\n";
    }
	
	/*
     * @public function getClassXoopsFormHidden
     * @param $var
     * @param $param1
	 * @param $param2
	 * @param $param3
     *  
     * @return string
     */
    public function getClassXoopsFormHidden($var, $param1, $param2, $param3)
    {
        return "\${$var} = new XoopsFormFile( {$param1}, '{$param2}', {$param3} );\n";
    }
	
	/*
     * @public function getClassXoopsFormHidden
     * @param $var
     * @param $param1
	 * @param $param2
     *  
     * @return string
     */
    public function getClassXoopsFormHidden($var, $param1, $param2)
    {
        return "\${$var} = new XoopsFormHidden( '{$param1}', \$this->getVar('{$param2}') );\n";
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
    public function getClassXoopsFormText($var, $param1, $param2, $param3 = 75, $param4 = 255, $param5)
    {
        return "\${$var} = new XoopsFormText( {$param1}, '{$param2}', {$param3}, {$param4}, \${$param5} );\n";
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
    public function getClassXoopsFormTextArea($var, $param1, $param2, $param3, $param4, $param5)
    {
        return "\${$var} = new XoopsFormTextArea( {$param1}, '{$param2}', \$this->getVar('{$param3}'), {$param4}, {$param5} );\n";
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
    public function getClassXoopsFormColorPicker($var, $param1, $param2, $param3)
    {
        return "\${$var} = new XoopsFormColorPicker( {$param1}, '{$param2}', \${$param3});\n";
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
    public function getClassXoopsFormSelectUser($var, $param1, $param2, $param3 = 'false', $param4)
    {
        return "\${$var} = new XoopsFormSelectUser( {$param1}, '{$param2}', {$param3}, \$this->getVar('{$param4}'));\n";
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
    public function getClassXoopsFormTextDateSelect($var, $param1, $param2, $param3 = '', $param4)
    {
        return "\${$var} = new XoopsFormTextDateSelect( {$param1}, '{$param2}', {$param3}, \$this->getVar('{$param4}'));\n";
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
    public function getClassXoopsFormEditor($var, $param1, $param2, $param3)
    {
        return "\${$var} = new XoopsFormEditor( {$param1}, '{$param2}', \${$param3} );\n";
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
    public function getClassXoopsFormCheckBox($var, $param1, $param2, $param3)
    {
        return "\${$var} = new XoopsFormCheckBox( \${$param1}, '{$param2}', \${$param3} );\n";
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
    public function getClassXoopsFormRadioYN($var, $param1, $param2, $param3)
    {
        return "\${$var} = new XoopsFormRadioYN( {$param1}, '{$param2}', \${$param3});\n";
    }
	
	/*
     * @public function getClassXoopsFormCheckBox
     * @param $var
     * @param $param1
	 * @param $param2
	 * @param $param3
	 * @param $param4
	 * @param $param5
     *  
     * @return string
     */
    public function getClassXoopsFormCheckBox($var, $param1, $param2, $param3, $param4, $param5 = 5)
    {
        return "\${$var} = new XoopsFormSelect( {$param1}, \".{\${$param2}}/\", '{$param3}', \${$param4}, {$param5} );\n";
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
    public function getClassXoopsFormTag($var, $param1, $param2, $param3, $param4, $param5 = 0)
    {
        return "\${$var} = new XoopsFormTag( '{$param1}', {$param2}, {$param3}, \${$param4}, {$param5} );\n";
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
    public function getClassAddElement($var, $params)
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
