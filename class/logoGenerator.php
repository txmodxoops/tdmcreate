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
 * @author          Xoops Team Developement Modules - http://www.xoops.org
 * @version         $Id: logoGenerator.php 12258 2014-01-02 09:33:29Z timgno $
 */
/*include_once dirname(dirname(dirname(__DIR__))) . '/mainfile.php';
include_once XOOPS_ROOT_PATH . '/modules/tdmcreate/include/common.php';

if(function_exists($_GET['f'])) { // get function name and parameter  $_GET['f']($_GET["p"]);
    include_once 'logoGenerator.php';
    $ret = logoGenerator::createLogo($_GET["iconName"],$_GET["caption"]);
    phpFunction($ret);
} else {
    echo 'Method Not Exist';
}

function phpFunction($val='')
{      // create php function here
    echo $val;
}

class logoGenerator
{
    static function createLogo($logoIcon, $moduleDirname)
    {
        if (!extension_loaded("gd")) {
            return false;
        } else {
            $required_functions = array("imagecreatefrompng", "imagefttext", "imagecopy", "imagepng", "imagedestroy", "imagecolorallocate");
            foreach ($required_functions as $func) {
                if (!function_exists($func)) {
                    return false;
                }
            }
        }
        if (!file_exists($imageBase = TDMC_IMAGE_LOGOS_PATH . "/empty.png") ||
            !file_exists($font = TDMC_FONTS_PATH . "/VeraBd.ttf") ||
            !file_exists($iconFile = XOOPS_ICONS32_PATH . '/' . basename($logoIcon))) {
            return false;
        }
        $imageModule = imagecreatefrompng($imageBase);
        $imageIcon = imagecreatefrompng($iconFile);
        // Write text
        $textColor = imagecolorallocate($imageModule, 0, 0, 0);
        $spaceBorder = (92 - strlen($moduleDirname) * 7.5) / 2;
        imagefttext($imageModule, 8.5, 0, $spaceBorder, 45, $textColor, $font, ucfirst($moduleDirname), array());
        imagecopy($imageModule, $imageIcon, 29, 2, 0, 0, 32, 32);
        $targetImagePath = TDMC_UPLOAD_IMGMOD_PATH . "/" . $moduleDirname . "_logo.png";
        $targetImageUrl = TDMC_UPLOAD_IMGMOD_URL . "/" . $moduleDirname . "_logo.png";
        imagepng($imageModule, $targetImagePath );
        imagedestroy($imageModule);
        imagedestroy($imageIcon);

        return $targetImageUrl;
    }
}*/
include_once dirname(dirname(dirname(__DIR__))) . '/mainfile.php';
//include_once XOOPS_ROOT_PATH . '/modules/TDMCreate/include/common.php';

if (function_exists($_GET['f'])) { // get function name and parameter  $_GET['f']($_GET["p"]);
    include_once 'logoGenerator.php';
    $ret = logoGenerator::createLogo($_GET['iconName'], $_GET['caption']);
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
 * Class logoGenerator
 */
class logoGenerator
{
    /**
     * @param $logoIcon
     * @param $moduleDirname
     * @return bool|string
     */
    public static function createLogo($logoIcon, $moduleDirname)
    {
        if (!extension_loaded("gd")) {
            return false;
        } else {
            $requiredFunctions = array("imagecreatefrompng", "imagefttext", "imagecopy", "imagepng", "imagedestroy", "imagecolorallocate");
            foreach ($requiredFunctions as $func) {
                if (!function_exists($func)) {
                    return false;
                }
            }
        }

        $dirname      = 'tdmcreate';
        $iconFileName = XOOPS_ROOT_PATH . "/Frameworks/moduleclasses/icons/32/" . basename($logoIcon);

        //$dirFonts = TDMC_PATH . "/assets/fonts";
        //$dirLogos = TDMC_PATH . "/assets/images/logos";
        $dirFonts = XOOPS_ROOT_PATH . "/modules/" . $dirname . "/assets/fonts";
        $dirLogos = XOOPS_ROOT_PATH . "/modules/" . $dirname . "/assets/images/logos";

        if (!file_exists($imageBase = $dirLogos . "/empty.png") ||
            !file_exists($font = $dirFonts . "/VeraBd.ttf") ||
            !file_exists($iconFile = $iconFileName) ) {	return false; }

        $imageModule = imagecreatefrompng($imageBase);
        $imageIcon   = imagecreatefrompng($iconFile);

        // Write text
        $textColor     = imagecolorallocate($imageModule, 0, 0, 0);
        $spaceToBorder = (92 - strlen($moduleDirname) * 7.5) / 2;
        imagefttext($imageModule, 8.5, 0, $spaceToBorder, 45, $textColor, $font, ucfirst($moduleDirname), array());

        imagecopy($imageModule, $imageIcon, 29, 2, 0, 0, 32, 32);

        //$targetImage = TDMC_UPLOAD_IMGMOD_URL . "/" . $moduleDirname . "_logo.png";
        $targetImage = "/uploads/" . $dirname . "/images/modules/" . $moduleDirname . "_logo.png";

        imagepng($imageModule, XOOPS_ROOT_PATH . $targetImage);

        imagedestroy($imageModule);
        imagedestroy($imageIcon);

        return XOOPS_URL . $targetImage;
    }
}
