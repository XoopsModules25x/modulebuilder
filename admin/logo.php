<?php

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
 * @since           2.5.5
 *
 * @author          Txmod Xoops <support@txmodxoops.org>
 *
 */
require __DIR__ . '/header.php';
$funct    = \Xmf\Request::getString('funct', '', 'GET');
$iconName = \Xmf\Request::getString('iconName', '', 'GET');
$caption  = \Xmf\Request::getString('caption', '', 'GET');
if (\function_exists($funct)) {
    $ret = Modulebuilder\Logo::getInstance()->createLogo($iconName, $caption);
    phpFunction($ret);
} else {
    \redirect_header('logo.php', 3, 'Method Not Exist');
}
// phpFunction
/**
 * @param string $val
 */
function phpFunction($val = '')
{
    // create php function here
    echo $val;
}
