<?php
/**
 * XOOPS form element
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         kernel
 * @subpackage      form
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id: simplelabel.php 12562 2014-04-03 10:57:12Z timgno $
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * A text label
 */
class TDMCreateFormLabel extends XoopsFormElement
{
    /**
     * Constructor
     *
     * @param string $caption
     */
    function TDMCreateFormLabel($caption = '')
    {
        $this->setCaption($caption);
    }

    /**
     * Prepare HTML for output
     *
     * @return string
     */
    function render()
    {
        return $this->getCaption();
    }
}