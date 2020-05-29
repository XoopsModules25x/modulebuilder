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
 * Class TemplatesAdminPermissions.
 */
class TemplatesAdminPermissions extends Files\CreateFile
{
    /**
     * @var mixed
     */
    private $hc = null;

    /**
     * @var mixed
     */
    private $sc = null;

    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
        $this->hc = Modulebuilder\Files\CreateHtmlCode::getInstance();
        $this->sc = Modulebuilder\Files\CreateSmartyCode::getInstance();
    }

    /**
     * @static function getInstance
     * @param null
     * @return TemplatesAdminPermissions
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
     * @param        $filename
     */
    public function write($module, $filename)
    {
        $this->setModule($module);
        $this->setFileName($filename);
    }

    /**
     * @private function getTemplatesAdminPermissionsHeader
     * @param string $moduleDirname
     *
     * @return string
     */
    private function getTemplatesAdminPermissionsHeader($moduleDirname)
    {

        return $this->sc->getSmartyIncludeFile($moduleDirname, 'header', true, '', '', "\n\n");
    }

    /**
     * @private function getTemplatesAdminPermissions
     * @param null
     * @return string
     */
    private function getTemplatesAdminPermissions()
    {
        $form = $this->sc->getSmartySingleVar('form');
        $ret  = $this->hc->getHtmlTag('div', ['class' => 'spacer'], $form, '', '', "\n\n");
        return $ret;
    }

    /**
     * @private function getTemplatesAdminPermissionsFooter
     * @param string $moduleDirname
     *
     * @return string
     */
    private function getTemplatesAdminPermissionsFooter($moduleDirname)
    {
        return $this->sc->getSmartyIncludeFile($moduleDirname, 'footer', true);
    }

    /**
     * @public function render
     * @param null
     * @return bool|string
     */
    public function render()
    {
        $module        = $this->getModule();
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $content       = $this->getTemplatesAdminPermissionsHeader($moduleDirname);
        $content       .= $this->getTemplatesAdminPermissions();
        $content       .= $this->getTemplatesAdminPermissionsFooter($moduleDirname);

        $this->create($moduleDirname, 'templates/admin', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
