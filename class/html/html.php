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
 * @version         $Id: html.php 12258 2014-01-02 09:33:29Z timgno $
*/
defined('XOOPS_ROOT_PATH') or die('Restricted access');
 
/**
 * Base class for html tags
 *
 * @author 		TXMod Xoops <info@txmodxoops.org>
 * @package 	tdmcreate
 * @access 		public
 */
class TDMCreateHtml 
{
	private static $_instance = null;
    /**
     * string of $_ele
     *
     * @var string
     */
    private $_ele = null;
    /**
     * string of $tag
     *
     * @var string
     */
    private $tag = null;
	/**
     * string of $attributes
     *
     * @var mixed
     */
    private $attributes = null;
	/**
     * string of $class
     *
     * @var string
     */
    private $_class = null;
	/**
     * string of $text
     *
     * @var string
     */
    private $text = '';
    /**
     * string of $content
     *
     * @var string
     */
    private $content = null;
    /**
     * string of $autoclosed
     *
     * @var mixed
     */
    private $autoclosed = true;
	/**
     * string of $textFirst
     *
     * @var mixed
     */
    private $textFirst = false;
    /**
     * constructor
     *    
     * @param string $tag
	 * @param string $ele
     */
    private function __construct($tag, $ele = null){
        $this->tag = $tag;
        $this->_ele =& $ele;
		return $this;
    }	
	public static function createElement($tag = ''){
        self::$_instance = new TDMCreateHtml($tag);
        return self::$_instance;
    }
	/*
	*  @public static function &getInstance
	*  @param string $tag
	*/
	public static function &getInstance($tag = '')
    {            
		static $instance = false;
        if (!$instance) {
            $instance = new self($tag);
        }
        return $instance;
    }
	/**
     * Function getChr
     *    
     * @param string $value
     */
    public function getChr($value){
        return chr($value);
    }
    /**
     * Function addElement
     *    
     * @param string $tag
     */
    public function addElement($tag){
        $ret = null;
        if(is_null($this->content)){
            $this->content = array();
            $this->autoclosed = false;
        }
        if(is_object($tag) && get_class($tag) == get_class($this)){
            $ret = $tag;
            $ret->_ele = $this->_ele;
            $this->content[] = $ret;
        }
        else{
            $ret = self::getInstance($tag, (is_null($this->_ele) ? $this : $this->_ele ));
            $this->content[] = $ret;
        }
		return $ret;
    }	
    /**
     * Function setAttributes
     *    
     * @param string $name
	 * @param string $value
     */
    public function setAttributes($name, $value){
        if(is_null($this->attributes)) {
			$this->attributes = array();
		}
        $this->attributes[$name] = $value;
		return $this;
    }
    /**
     * Function getId
     *    
     * @param string $value
     */
    public function getId($value){
        return $this->setTag('id', $value);
    }
    /**
     * Function addClass
     *    
     * @param string $value
     */
    public function addClass($value){
        if(is_null($this->_class)) {
            $this->_class = array();
		}
        $this->_class[] = $value;
		return $this;
    }
	/**
     * Function removeClass
     *    
     * @param string $class
     */
    public function removeClass($class){
        if(!is_null($this->_class)){
            unset($this->_class[array_search($class, $this->_class)]);
            // foreach($this->_class as $key=>$value){
                // if($class == $value)
                    // $this->_class[$key] = '';
            // }
        }
        return $this;
    }
    /**
     * Function setText
     *    
     * @param string $value
     */
    public function setText($value){
        $this->text = $value;
		return $this;
    }
    /**
     * Function showTextBeforeContent
     *    
     * @param boolean $bool
     */
    public function showTextBeforeContent($bool){
        $this->textFirst = $bool;
    }
    /**
     * Function magic __toString
     *    
     * @param null
     */
    public function __toString(){
        return (is_null($this->_ele) ? $this->toString() : $this->_ele->toString() );
    }
    /**
     * Function toString
     *    
     * @param null
     */
    public function toString(){
        $string = '';
        if(!empty($this->tag)){
            $string .=  '<' . $this->tag;
            $string .= $this->getAttributesToString();
            if($this->autoclosed && empty($this->text)) $string .= '/>' . $this->getChr(13) . $this->getChr(10) . $this->getChr(9);
            else $string .= '>' . ($this->textFirst ?  $this->text.$this->getContentToString() : $this->getContentToString().$this->text ). '</' . $this->tag . '>';
        }
        else{
            $string .= $this->getContentToString();
        }        
        return $string;
    }
    /**
     * Function getAttributesToString
     *    
     * @param null
     */
    private function getAttributesToString(){
        $string = '';
        if(!is_null($this->attributes)){
            foreach($this->attributes as $key => $value){
                if(!empty($value))
                    $string .= ' ' . $key . '="' . $value . '"';
            }
        }
        if(!is_null($this->class) && count($this->class) > 0 ){
            $string .= ' class="' . implode(' ', $this->class) . '"';
        }
        return $string;
    }
    /**
     * Function getContentToString
     *    
     * @param null
     */
    private function getContentToString(){
        $string = '';
        if(!is_null($this->content)){
            foreach($this->content as $cont){
                $string .= $this->getChr(13) . $this->getChr(10) . $this->getChr(9) . $cont->toString();
            }
        }
        return $string;
    }
	/**
     * Function render
     *    
     * @param null
     */
    public function render() {
        return;
    }
}