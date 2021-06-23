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
 * modulebuilder module.
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops https://xoops.org 
 *                  Goffy https://myxoops.org
 *
 */

use XoopsModules\Modulebuilder\Common\TableChecker;

// Define main template
$templateMain = 'modulebuilder_index.tpl';

require __DIR__ . '/header.php';

$tablechecker = new \XoopsModules\Modulebuilder\Common\TableChecker('modulebuilder');
$result = $tablechecker->processSQL();

/*
use XoopsModules\Wgdiaries\Common\TableChecker;
    $tablechecker = new \XoopsModules\Wgdiaries\Common\TableChecker('wgdiaries', 1);
    $result = $tablechecker->processSQL();
 */

var_dump($result);

require __DIR__ . '/footer.php';
