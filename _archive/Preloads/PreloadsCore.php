<?php

namespace XoopsModules\Modulebuilder\Files\Preloads;

use XoopsModules\Modulebuilder;
use XoopsModules\Modulebuilder\Files;

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
defined('XOOPS_ROOT_PATH') || exit('Restricted access');

/**
 * Class PreloadsCore.
 */
class PreloadsCore extends Files\CreateFile
{
    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
        $this->tdmcfile = Modulebuilder\Files\CreateFile::getInstance();
    }

    /**
     * @static function getInstance
     * @param null
     * @return PreloadsCore
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
     * @public function write
     * @param $module
     * @param $filename
     */
    public function write($module, $filename)
    {
        $this->setModule($module);
        $this->setFileName($filename);
    }

    /**
     * @public function render
     * @param null
     * @return bool|string
     */
    public function render()
    {
        $module           = $this->getModule();
        $filename         = $this->getFileName();
        $moduleDirname    = $module->getVar('mod_dirname');
        $ucfModuleDirname = ucfirst($moduleDirname);
        $content          = $this->getHeaderFilesComments($module, $filename);

        $content .= <<<EOT
/**
 * {$ucfModuleDirname} core preloads
 *
 */
class {$ucfModuleDirname}CorePreload extends \XoopsPreloadItem
{
	// Here your functions method
	// Example:
	/**
     * @param \$args
     */
    function eventCoreYourNameStart(\$args)
    {
        // Here your event
        exit();
    }
}
EOT;

        $this->tdmcfile->create($moduleDirname, 'preloads', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->tdmcfile->renderFile();
    }
}
