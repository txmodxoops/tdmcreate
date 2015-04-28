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
 * @since           2.5.5
 * @author          Txmod Xoops <support@txmodxoops.org>
 * @version         $Id: 1.91 htmlform.php 11297 2014-03-22 10:19:18Z timgno $
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

require_once 'htmltable.php';

/**
 * Form HTML
 *
 */
class TDMCreateHtmlForm
{	
	/**
     * name attribute
     *
     * @var string
     */
    private $name = null;
	
	/**
     * action attribute
     *
     * @var mixed
     */
    private $action = null;
	
	/**
     * method attribute
     *
     * @var string
     */
    private $method = null;
	
		/**
     * extra attribute
     *
     * @var mixed
     */
    private $extra = null;
	
	/**
     * constructor
     *
     */
    public function __construct()
    {
    }
	
	/*
	*  @static function &getInstance
	*  @param null
	*/
	public static function &getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }
        return $instance;
    }
	
	/**
     * Function getHtmlForm
     *
     * @param string $name "name" attribute for the <form> tag
     * @param string $action "action" attribute for the <form> tag
     * @param string $method "method" attribute for the <form> tag
     * @param bool $extra whether to add a javascript to the form
     */
    public function getInitForm($name = null, $action = false, $method = 'post', $extra = false)
    {
		$this->name = $name;
		$this->action = $action;
		$this->method = $method;
		$this->extra = $extra;
    }
	
	/**
     * Function getHeaderForm
     *
     * @param string $title
     */
    public function getHeaderForm($title)
    {
        $ret = ($title ? '<div class=" center head ">' . $title . '</div>' . NWLINE : '');
		return $ret;
    }
	
	/**
     * Function getOpenForm
     *
     * @param null
     */
    public function getOpenForm()
    {
        $ret = '<form name="' . $this->name . '" id="' . $this->name . '" action="' . $this->action . '" method="' . $this->method . '" onsubmit="return xoopsFormValidate_' . $this->name . '();"' . $this->extra . '>' . NWLINE;
		return $ret;
    }
	
	/**
     * Function getContentForm
     *
     * @param null
     */
    public function getContentForm($class, $caption, $content)
    {
        $ret = '<div class="' . $class . '"><strong>' . $caption . '</strong>' . $content . '</div>' . NWLINE;
		return $ret;
    }
	
	/**
     * Function CloseForm
     *
     * @param null
     */
    public function getCloseForm()
    {
		$ret = '</form>' . NWLINE;
        return $ret;
    }    
}