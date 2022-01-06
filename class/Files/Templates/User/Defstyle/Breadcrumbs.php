<?php

namespace XoopsModules\Modulebuilder\Files\Templates\User\Defstyle;

use XoopsModules\Modulebuilder;
use XoopsModules\Modulebuilder\Files;
use XoopsModules\Modulebuilder\Files\Templates\User;

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

/**
 * Class Breadcrumbs.
 */
class Breadcrumbs extends Files\CreateFile
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
     * @var mixed
     */
    private $cf = null;

    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
        $this->hc = Modulebuilder\Files\CreateHtmlCode::getInstance();
        $this->sc = Modulebuilder\Files\CreateSmartyCode::getInstance();
        $this->cf = Modulebuilder\Files\CreateFile::getInstance();
    }

    /**
     * @static function getInstance
     * @return bool|Breadcrumbs
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
     * @param        $module
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
        $module        = $this->getModule();
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');

        $title      = $this->sc->getSmartyDoubleVar('itm', 'title');
        $titleElse  = $this->sc->getSmartyDoubleVar('itm', 'title', "\t\t\t", "\n");
        $link       = $this->sc->getSmartyDoubleVar('itm', 'link');
        $glyph      = $this->hc->getHtmlTag('i', ['class' => 'glyphicon glyphicon-home fa fa-home'], '', false, '', '');
        $anchor     = $this->hc->getHtmlAnchor('<{xoAppUrl index.php}>', $glyph, 'home');
        $into       = $this->hc->getHtmlLi($anchor, 'breadcrumb-item', "\t");
        $anchorIf   = $this->hc->getHtmlAnchor($link, $title, $title, '', '', '', "\t\t\t", "\n");
        $breadcrumb = $this->sc->getSmartyConditions('itm.link', '', '', $anchorIf, $titleElse, false, false, "\t\t");
        $foreach    = $this->hc->getHtmlLi($breadcrumb, 'breadcrumb-item', "\t", "\n", true);
        $into       .= $this->sc->getSmartyForeach('itm', 'xoBreadcrumbs', $foreach, 'bcloop', '', "\t");

        $content = $this->hc->getHtmlOl($into, 'breadcrumb');

        $this->cf->create($moduleDirname, 'templates', $filename, $content, \_AM_MODULEBUILDER_FILE_CREATED, \_AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->cf->renderFile();
    }
}
