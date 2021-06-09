<?php

namespace XoopsModules\Modulebuilder\Files\Language;

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
 * Class LanguageHelp.
 */
class LanguageHelp extends Files\CreateFile
{
    /**
     * @var mixed
     */
    private $ld = null;

    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
        $this->ld = LanguageDefines::getInstance();
    }

    /**
     * @static function getInstance
     * @param null
     * @return LanguageHelp
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
        $module        = $this->getModule();
        $filename      = $this->getFileName();
        $moduleName    = $module->getVar('mod_name');
        $moduleDirname = $module->getVar('mod_dirname');
        $language      = $GLOBALS['xoopsConfig']['language'];
        $content       = <<<EOT
<div id="help-template" class="outer">
    <h1 class="head">Help:
        <a class="ui-corner-all tooltip" href="<{\$xoops_url}>/modules/{$moduleDirname}/admin/index.php"
           title="Back to the administration of {$moduleName}"> {$moduleName} <img src="<{xoAdminIcons home.png}>"
                                                                       alt="Back to the Administration of {$moduleName}">
        </a></h1>
    <!-- -----Help Content ---------- -->
    <h4 class="odd">Description</h4>
    <p class="even">
        The {$moduleName} module can be used to modules in XOOPS<br><br>
    </p>
    <h4 class="odd">Install/uninstall</h4>
    <p class="even">
No special measures necessary, follow the standard installation process and extract the {$moduleDirname} folder into the ../modules directory. Install the module through Admin -> System Module -> Modules. <br><br>
Detailed instructions on installing modules are available in the <a href="http://goo.gl/adT2i">XOOPS Operations Manual</a>
    </p>
    <h4 class="odd">Features</h4>
    <p class="even">
        The ModuleBuilder module continues to expand, to get to the conditions to create modules, more and more sophisticated.<br>
        For this reason, I invite all developers to report and send in svn any changes or additions to this module, so that we can jointly contribute to the development <br><br>
    </p>
    <h4 class="odd">Tutorial</h4>
    <p class="even">
        You can find a more detailed to this Video Tutorial <a href="http://www.youtube.com/watch?v=dg7zGFCopxY" rel="external">here</a>
    </p>
    <!-- -----Help Content ---------- -->
</div>
EOT;
        if ('english' !== $language) {
            $this->create($moduleDirname, 'language/' . $language . '/help', $filename, $content, \_AM_MODULEBUILDER_FILE_CREATED, \_AM_MODULEBUILDER_FILE_NOTCREATED);
        }
        $this->create($moduleDirname, 'language/' . $GLOBALS['xoopsConfig']['language'] . '/help', $filename, $content, \_AM_MODULEBUILDER_FILE_CREATED, \_AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
