<?php declare(strict_types=1);

namespace XoopsModules\Modulebuilder;

use XoopsModules\Modulebuilder;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * modulebuilder module.
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Xoops Team Developement Modules - https://xoops.org
 */
require_once \dirname(__DIR__, 3) . '/mainfile.php';

/**
 * @param string $val
 */
function phpFunction($val = ''): void
{
    // create php function here
    echo $val;
}

$myfunction = '\\XoopsModules\\Modulebuilder\\' . $_GET['f'];

if (\function_exists($myfunction)) {
    $ret = \XoopsModules\Modulebuilder\LogoGenerator::createLogo($_GET['iconName'], $_GET['caption']);
    phpFunction($ret);
} else {
    echo 'Method Not Exist';
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
        if (!\extension_loaded('gd')) {
            return false;
        }
        $requiredFunctions = ['imagecreatefrompng', 'imagefttext', 'imagecopy', 'imagepng', 'imagedestroy', 'imagecolorallocate'];
        foreach ($requiredFunctions as $func) {
            if (!\function_exists($func)) {
                return false;
            }
        }

        $dirname      = 'modulebuilder';
        $iconFileName = \XOOPS_ROOT_PATH . '/Frameworks/moduleclasses/icons/32/' . \basename($logoIcon);

        //$dirFonts = TDMC_PATH . "/assets/fonts";
        //$dirLogos = TDMC_PATH . "/assets/images/logos";
        $dirFonts = \XOOPS_ROOT_PATH . '/modules/' . $dirname . '/assets/fonts';
        $dirLogos = \XOOPS_ROOT_PATH . '/modules/' . $dirname . '/assets/images/logos';

        if (!\file_exists($imageBase = $dirLogos . '/empty.png')
            || !\file_exists($font = $dirFonts . '/VeraBd.ttf')
            || !\file_exists($iconFile = $iconFileName)) {
            return false;
        }

        $imageModule = \imagecreatefrompng($imageBase);
        $imageIcon   = \imagecreatefrompng($iconFile);

        // Write text
        $textColor     = imagecolorallocate($imageModule, 0, 0, 0);
        $spaceToBorder = (92 - mb_strlen($moduleName) * 7.5) / 2;
        imagefttext($imageModule, 8.5, 0, $spaceToBorder, 45, $textColor, $font, $moduleName, []);

        imagecopy($imageModule, $imageIcon, 29, 2, 0, 0, 32, 32);

        //$targetImage = TDMC_UPLOAD_IMGMOD_URL . "/" . $moduleName . "_logo.png";
        $targetImage = '/uploads/' . $dirname . '/images/modules/' . $moduleName . '_logo.png';

        \imagepng($imageModule, \XOOPS_ROOT_PATH . $targetImage);

        \imagedestroy($imageModule);
        \imagedestroy($imageIcon);

        return \XOOPS_URL . $targetImage;
    }
}
