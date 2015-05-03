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
 * @version         $Id: LanguageDefines.php 12258 2014-01-02 09:33:29Z timgno $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class LanguageDefines
 */
class LanguageDefines
{
    /*
    *  @public function constructor
    *  @param null
    */
    /**
     *
     */
    public function __construct()
    {
    }

    /*
    *  @static function &getInstance
    *  @param null
    */
    /**
     * @return LanguageDefines
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
    *  @public function getAboveHeadDefines
    *  @param string $string
    *  @return string
    */
    public function getAboveHeadDefines($string)
    {
        $ret = <<<EOT
// ---------------- {$string} ----------------\n
EOT;
        return $ret;
    }

    /*
    *  @public function getAboveDefines
    *  @param string $string
    *  @return string
    */
    public function getAboveDefines($string)
    {
        $ret = <<<EOT
// {$string}\n
EOT;
        return $ret;
    }

    /*
    *  @public function getDefine
    *  @param string $language
    *  @param string $defined
    *  @param string $description
    *  @return string
    */
    public function getDefine($language, $defined, $description)
    {
        $defined = strtoupper($defined);
		$ret = <<<EOT
define('{$language}{$defined}', "{$description}");\n
EOT;
        return $ret;
    }

    /*
    *  @public function getBelowDefines
    *  @param string $string
    */
    /**
     * @param $string
     * @return string
     */
    public function getBelowDefines($string)
    {
        $ret = <<<EOT
// ---------------- {$string} ----------------
EOT;
        return $ret;
    }
}