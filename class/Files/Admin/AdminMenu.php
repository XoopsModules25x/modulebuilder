<?php

namespace XoopsModules\Modulebuilder\Files\Admin;

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
 * Class AdminMenu.
 */
class AdminMenu extends Files\CreateFile
{
    /**
     * @var mixed
     */
    private $xc = null;

    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
        $this->xc  = Modulebuilder\Files\CreateXoopsCode::getInstance();
    }

    /**
     * @static function getInstance
     * @param null
     * @return AdminMenu
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
     * @private function getAdminMenuArray
     * @param array $param
     * @param bool $adminObject
     * @return string
     */
    private function getAdminMenuArray($param = [], $adminObject = false)
    {
        $ret = '';
        if ($adminObject) {
            $ret .= $this->getSimpleString("\$adminmenu[] = [");
            foreach ($param as $key => $value) {
                $ret .= $this->getSimpleString("\t'{$key}' => {$value},");
            }
            $ret .= $this->getSimpleString("];");
        } else {
            foreach ($param as $key => $value) {
                $ret .= $this->xc->getXcEqualsOperator((string)$key, (string)$value);
            }
        }

        return $ret;
    }

    /**
     * @private function getAdminMenuHeader
     * @param null
     * @return string
     */
    private function getAdminMenuHeader()
    {
        $ret = $this->getSimpleString('');
        $mod     = [
            '$dirname      ' => '\basename(\dirname(__DIR__))',
            '$moduleHandler' => "\xoops_getHandler('module')",
            '$xoopsModule  ' => 'XoopsModule::getByDirname($dirname)',
            '$moduleInfo   ' => "\$moduleHandler->get(\$xoopsModule->getVar('mid'))",
            '$sysPathIcon32' => "\$moduleInfo->getInfo('sysicons32')",
        ];
        $ret .= $this->getAdminMenuArray($mod);
        $ret .= $this->getSimpleString('');

        return $ret;
    }

    /**
     * @private function getAdminMenuDashboard
     * @param string $language
     * @param int    $menu
     *
     * @return string
     */
    private function getAdminMenuDashboard($language, $menu)
    {
        $param = ['title' => "{$language}{$menu}", 'link' => "'admin/index.php'", 'icon' => "\$sysPathIcon32.'/dashboard.png'"];
        $ret   = $this->getAdminMenuArray($param, true);

        return $ret;
    }

    /**
     * @private function getAdminMenuList
     * @param string $module
     * @param string $language
     * @param string $langAbout
     * @param int    $menu
     *
     * @return string
     */
    private function getAdminMenuList($module, $language, $langAbout, $menu)
    {
        $ret    = '';
        $tables = $this->getTableTables($module->getVar('mod_id'), 'table_order');
        $tablePermissions = [];
        $tableBroken      = [];
        foreach (\array_keys($tables) as $t) {
            $tablePermissions[] = $tables[$t]->getVar('table_permissions');
            $tableBroken[]      = $tables[$t]->getVar('table_broken');
            if (1 == $tables[$t]->getVar('table_admin')) {
                ++$menu;
                $param1 = ['title' => "{$language}{$menu}", 'link' => "'admin/{$tables[$t]->getVar('table_name')}.php'", 'icon' => "'assets/icons/32/{$tables[$t]->getVar('table_image')}'"];
                $ret    .= $this->getAdminMenuArray($param1, true);
            }
        }
        if (\in_array(1, $tableBroken)) {
            ++$menu;
            $param2 = ['title' => "{$language}{$menu}", 'link' => "'admin/broken.php'", 'icon' => "\$sysPathIcon32.'/brokenlink.png'"];
            $ret    .= $this->getAdminMenuArray($param2, true);
        }
        if (\in_array(1, $tablePermissions)) {
            ++$menu;
            $param2 = ['title' => "{$language}{$menu}", 'link' => "'admin/permissions.php'", 'icon' => "\$sysPathIcon32.'/permissions.png'"];
            $ret    .= $this->getAdminMenuArray($param2, true);
        }
        ++$menu;
        $param3 = ['title' => "{$language}{$menu}", 'link' => "'admin/feedback.php'", 'icon' => "\$sysPathIcon32.'/mail_foward.png'"];
        $ret    .= $this->getAdminMenuArray($param3, true);
        unset($menu);
        $param3 = ['title' => (string)$langAbout, 'link' => "'admin/about.php'", 'icon' => "\$sysPathIcon32.'/about.png'"];
        $ret    .= $this->getAdminMenuArray($param3, true);

        return $ret;
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
        $language      = $this->getLanguage($moduleDirname, 'MI', 'ADMENU');
        $langAbout     = $this->getLanguage($moduleDirname, 'MI', 'ABOUT');
        $menu          = 1;
        $content       = $this->getHeaderFilesComments($module);
        $content       .= $this->getAdminMenuHeader();
        $content       .= $this->getAdminMenuDashboard($language, $menu);
        $content       .= $this->getAdminMenuList($module, $language, $langAbout, $menu);

        $this->create($moduleDirname, 'admin', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
