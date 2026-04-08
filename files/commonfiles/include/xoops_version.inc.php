<?php declare(strict_types=1);
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project (https://xoops.org)
 * @license      GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author       XOOPS Development Team
 * @param mixed $val
 */

/**
 * @param $val
 * @return int
 */

function modulebuilderReturnBytes($val): int
{

    $raw = \trim((string)$val);
    if ($raw === '') {
        return 0;
    }
    if ($raw === '-1') {
        return -1;
    }
    if (\is_numeric($raw)) {
        return (int)$raw;
    }

    $unit = \strtoupper(\substr($raw, -1));
    $number = (float)\substr($raw, 0, -1);

    switch ($unit) {
    case 'K':
            return (int)\round($number * 1024);
         case 'M':
            return (int)\round($number * 1048576);
         case 'G':

            return (int)\round($number * 1073741824);
         default:
            return (int)$raw;
     }
 }