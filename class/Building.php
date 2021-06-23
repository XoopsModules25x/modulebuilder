<?php

namespace XoopsModules\Modulebuilder;

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
 * Building class.
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.x
 *
 * @author          TDM TEAM DEV MODULE
 *
 */

/**
 * Class Building.
 */
class Building
{
    /**
     * @static function getInstance
     *
     * @param null
     *
     * @return Building
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
     * @param bool $action
     *
     * @return \XoopsThemeForm
     */
    public function getForm($action = false)
    {
        $helper = Modulebuilder\Helper::getInstance();
        if (false === $action) {
            $action = \Xmf\Request::getString('REQUEST_URI', '', 'SERVER');
        }
        \xoops_load('XoopsFormLoader');
        $form = new \XoopsThemeForm(\_AM_MODULEBUILDER_ADMIN_CONST, 'buildform', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        $moduleObj  = $helper->getHandler('Modules')->getObjects(null);
        $mod_select = new \XoopsFormSelect(\_AM_MODULEBUILDER_CONST_MODULES, 'mod_id', 'mod_id');
        $mod_select->addOption('', \_AM_MODULEBUILDER_BUILD_MODSELOPT);
        foreach ($moduleObj as $mod) {
            $mod_select->addOption($mod->getVar('mod_id'), $mod->getVar('mod_name'));
        }
        $form->addElement($mod_select, true);

        $form->addElement(new \XoopsFormRadioYN(\_AM_MODULEBUILDER_BUILDING_INROOT_COPY, 'inroot_copy', $helper->getConfig('inroot_copy')));
        $form->addElement(new \XoopsFormRadioYN(\_AM_MODULEBUILDER_BUILDING_TEST . \_AM_MODULEBUILDER_BUILDING_TEST_DESC, 'testdata_restore', 0));

        $form->addElement(new \XoopsFormHidden('op', 'build'));
        $btnTray = new \XoopsFormElementTray(\_REQUIRED . ' <sup class="red bold">*</sup>', '&nbsp;');
        $btnTray->addElement(new \XoopsFormButton('', 'submit', \_SUBMIT, 'submit'));
        $btnTray->addElement(new \XoopsFormButton('', 'check_data', \_AM_MODULEBUILDER_BUILDING_CHECK, 'submit'));
        $form->addElement($btnTray);

        return $form;
    }

    /**
     * @param string $dir
     * @param string $pattern
     */
    public function clearDir($dir, $pattern = '*')
    {
        // Find all files and folders matching pattern
        $files = glob($dir . "/$pattern");
        // Interate thorugh the files and folders
        foreach ($files as $file) {
            // if it's a directory then re-call clearDir function to delete files inside this directory
            if (\is_dir($file) && !\in_array($file, ['..', '.'])) {
                // Remove the directory itself
                $this->clearDir($file, $pattern);
            } elseif ((__FILE__ != $file) && is_file($file)) {
                // Make sure you don't delete the current script
                \unlink($file);
            }
        }
        if (\is_dir($dir)) {
            \rmdir($dir);
        }
    }

    /**
     * @param string $src
     * @param string $dst
     */
    public function copyDir($src, $dst)
    {
        $dir = \opendir($src);
        if (!\mkdir($dst) && !\is_dir($dst)) {
            throw new \RuntimeException(\sprintf('Directory "%s" was not created', $dst));
        }
        while (false !== ($file = \readdir($dir))) {
            if (('.' !== $file) && ('..' !== $file)) {
                if (\is_dir($src . '/' . $file)) {
                    // Copy the directory itself
                    $this->copyDir($src . '/' . $file, $dst . '/' . $file);
                } else {
                    // Make sure you copy the current script
                    \copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        \closedir($dir);
    }
}
