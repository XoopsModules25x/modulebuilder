<?php

namespace XoopsModules\Modulebuilder\Files\Templates\Admin;

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

/**
 * Class TemplatesAdminHeader.
 */
class TemplatesAdminHeader extends Files\CreateFile
{
    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @static   function getInstance
     * @return TemplatesAdminHeader
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
     * @param string $module
     * @param string $filename
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
        $hc            = Modulebuilder\Files\CreateHtmlCode::getInstance();
        $sc            = Modulebuilder\Files\CreateSmartyCode::getInstance();
        $module        = $this->getModule();
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');

        $navigation = $sc->getSmartySingleVar('navigation');
        $due        = $hc->getHtmlSpan($navigation, 'left', "\t") ;
        $buttons    = $sc->getSmartySingleVar('buttons');
        $right      = $hc->getHtmlSpan($buttons, 'left', "\t\t", "\n");
        $due        .= $sc->getSmartyConditions('buttons', '', '', $right, '', '', '', "\t", "\n");
        $content    = $hc->getHtmlDiv($due, 'top');

        $this->create($moduleDirname, 'templates/admin', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
