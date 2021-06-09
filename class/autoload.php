<?php

namespace XoopsModules\Modulebuilder;

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
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 */
\defined('XOOPS_ROOT_PATH') || die('Restricted access');
/*
 * @since 1.91
 */
// Autoload Function
ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);

if (!\function_exists('application_autoloader')) {
    /**
     * @param $class
     */
    function application_autoloader($class)
    {
        $classFilename = $class . '.php';
        $cachePath     = \XOOPS_VAR_PATH . '/caches/modulebuilder_cache';
        if (!\is_dir($cachePath)) {
            if (!\mkdir($cachePath, 0777) && !\is_dir($cachePath)) {
                throw new \RuntimeException(\sprintf('Directory "%s" was not created', $cachePath));
            }
            chmod($cachePath, 0777);
        }
        $pathCache = \file_exists($cacheFile = $cachePath . '/classpaths.cache') ? unserialize(file_get_contents($cacheFile)) : [];
        if (!\is_array($pathCache)) {
            $pathCache = [];
        }

        if (\array_key_exists($class, $pathCache)) {
            /* Load class using path from cache file (if the file still exists) */
            if (\file_exists($pathCache[$class])) {
                require_once $pathCache[$class];
            }
        } else {
            /* Determine the location of the file within the $class_root and, if found, load and cache it */
            $directories = new \RecursiveDirectoryIterator(__DIR__);
            foreach (new \RecursiveIteratorIterator($directories) as $file) {
                if ($file->getFilename() == $classFilename) {
                    $fullPath          = $file->getRealPath();
                    $pathCache[$class] = $fullPath;
                    require_once $fullPath;
                    break;
                }
            }
        }

        $serialized_paths = serialize($pathCache);
        if ($serialized_paths != $pathCache) {
            file_put_contents($cacheFile, serialize($pathCache));
        }
    }

    spl_autoload_register('application_autoloader');
}
