<?php namespace XoopsModules\Tdmcreate;

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
 * @author          Xoops Team Developement Modules - https://xoops.org
 *
 * @version         $Id: LogoGenerator.php 12258 2014-01-02 09:33:29Z timgno $
 */
include_once dirname(dirname(dirname(__DIR__))) . '/mainfile.php';
//include_once XOOPS_ROOT_PATH . '/modules/TDMCreate/include/common.php';

if (function_exists($_GET['f'])) { // get function name and parameter  $_GET['f']($_GET["p"]);
    include_once __DIR__ . '/LogoGenerator.php';
    $ret = LogoGenerator::createLogo($_GET['iconName'], $_GET['caption']);
    phpFunction($ret);
} else {
    echo 'Method Not Exist';
}

/**
 * @param string $val
 */
function phpFunction($val = '')
{      // create php function here
    echo $val;
}

/**
 * Class LogoGenerator.
 */
class LogoGenerator
{
    /**
     * @param $logoIcon
     * @param $moduleName
     *
     * @return bool|string
     */
    public static function createLogo($logoIcon, $moduleName)
    {
        if (!extension_loaded('gd')) {
            return false;
        }
        $requiredFunctions = ['imagecreatefrompng', 'imagefttext', 'imagecopy', 'imagepng', 'imagedestroy', 'imagecolorallocate'];
        foreach ($requiredFunctions as $func) {
            if (!function_exists($func)) {
                return false;
            }
        }

        $dirname      = 'tdmcreate';
        $iconFileName = XOOPS_ROOT_PATH . '/Frameworks/moduleclasses/icons/32/' . basename($logoIcon);

        //$dirFonts = TDMC_PATH . "/assets/fonts";
        //$dirLogos = TDMC_PATH . "/assets/images/logos";
        $dirFonts = XOOPS_ROOT_PATH . '/modules/' . $dirname . '/assets/fonts';
        $dirLogos = XOOPS_ROOT_PATH . '/modules/' . $dirname . '/assets/images/logos';

        if (!file_exists($imageBase = $dirLogos . '/empty.png')
            || !file_exists($font = $dirFonts . '/VeraBd.ttf')
            || !file_exists($iconFile = $iconFileName)) {
            return false;
        }

        $imageModule = imagecreatefrompng($imageBase);
        $imageIcon   = imagecreatefrompng($iconFile);

        // Write text
        $textColor     = imagecolorallocate($imageModule, 0, 0, 0);
        $spaceToBorder = (92 - mb_strlen($moduleName) * 7.5) / 2;
        imagefttext($imageModule, 8.5, 0, $spaceToBorder, 45, $textColor, $font, $moduleName, []);

        imagecopy($imageModule, $imageIcon, 29, 2, 0, 0, 32, 32);

        //$targetImage = TDMC_UPLOAD_IMGMOD_URL . "/" . $moduleName . "_logo.png";
        $targetImage = '/uploads/' . $dirname . '/images/modules/' . $moduleName . '_logo.png';

        imagepng($imageModule, XOOPS_ROOT_PATH . $targetImage);

        imagedestroy($imageModule);
        imagedestroy($imageIcon);

        return XOOPS_URL . $targetImage;
    }
}
