<?php

namespace XoopsModules\Tdmcreate\Files\Language;

use XoopsModules\Tdmcreate;

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
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 * @version         $Id: LanguageDefines.php 12258 2014-01-02 09:33:29Z timgno $
 */

/**
 * Class LanguageDefines.
 */
class LanguageDefines
{
    /**
     * @var mixed
     */
    protected $defines;

    /**
     *  @public function constructor
     *  @param null
     */
    public function __construct()
    {
        $this->phpcode = Tdmcreate\Files\CreatePhpCode::getInstance();
    }

    /**
     *  @static function getInstance
     *  @param null
     * @return LanguageDefines
     */
    public static function getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     *  @public function getAboveHeadDefines
     *  @param string $string
     *  @return string
     */
    public function getAboveHeadDefines($string)
    {
        return "// ---------------- {$string} ----------------\n";
    }

    /**
     *  @public function getAboveDefines
     *  @param string $string
     *  @return string
     */
    public function getAboveDefines($string)
    {
        return "// {$string}\n";
    }

    /**
     *  @public function getDefine
     *  @param string $language
     *  @param string $defined
     *  @param string $description
     *  @param bool   $usedoubleqoute
     *  @return string
     */
    public function getDefine($language, $defined, $description, $usedoubleqoute = false)
    {
        $defined = mb_strtoupper($defined);

        if ($usedoubleqoute) {
            $ret = $this->phpcode->getPhpCodeDefine("{$language}{$defined}", "\"{$description}\"");
        } else {
            $ret = $this->phpcode->getPhpCodeDefine("{$language}{$defined}", "'" . $description . "'");
        }

        return $ret;
    }

    /**
     *  @public function getBelowDefines
     *  @param string $string
     *
     * @return string
     */
    public function getBelowDefines($string)
    {
        return "// ---------------- {$string} ----------------";
    }
}
