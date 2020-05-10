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
 * AddFiles class.
 *
 * @copyright       The XOOPS Project http:sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http:www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.7
 *
 * @author          Txmod Xoops <webmaster@txmodxoops.org> - <http:www.txmodxoops.org/>
 *
 */
//include __DIR__.'/autoload.php';

/**
 * Class AddFiles.
 */
class AddFiles extends \XoopsObject
{
    /**
     * Modulebuilder.
     *
     * @var mixed
     */
    private $helper;

    /**
     * Settings.
     *
     * @var mixed
     */
    private $settings;

    /**
     * @public function constructor class
     * @param null
     */
    public function __construct()
    {
        $this->helper = Modulebuilder\Helper::getInstance();

        $this->initVar('file_id', XOBJ_DTYPE_INT);
        $this->initVar('file_mid', XOBJ_DTYPE_INT);
        $this->initVar('file_name', XOBJ_DTYPE_TXTBOX);
        $this->initVar('file_extension', XOBJ_DTYPE_TXTBOX);
        $this->initVar('file_infolder', XOBJ_DTYPE_TXTBOX);
    }

    /**
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        $arg = isset($args[0]) ? $args[0] : null;

        return $this->getVar($method, $arg);
    }

    /**
     * @static function getInstance
     * @param null
     * @return AddFiles
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
     * @public function getFormMorefiles
     * @param mixed $action
     *
     * @return \XoopsThemeForm
     */
    public function getFormAddFiles($action = false)
    {
        if (false === $action) {
            $action = \Xmf\Request::getString('REQUEST_URI', '', 'SERVER');
        }

        $isNew = $this->isNew();
        $title = $isNew ? sprintf(_AM_MODULEBUILDER_ADDFILES_NEW) : sprintf(_AM_MODULEBUILDER_ADDFILES_EDIT);

        xoops_load('XoopsFormLoader');

        $form = new \XoopsThemeForm($title, 'addfilesform', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');

        $modules       = $this->helper->getHandler('modules')->getObjects(null);
        $modulesSelect = new \XoopsFormSelect(_AM_MODULEBUILDER_ADDFILES_MODULES, 'file_mid', $this->getVar('file_mid'));
        $modulesSelect->addOption('', _AM_MODULEBUILDER_ADDFILES_MODULE_SELECT);
        foreach ($modules as $mod) {
            $modulesSelect->addOption($mod->getVar('mod_id'), $mod->getVar('mod_name'));
        }
        $form->addElement($modulesSelect, true);

        $modName = new \XoopsFormText(_AM_MODULEBUILDER_ADDFILES_NAME, 'file_name', 50, 255, $this->getVar('file_name'));
        $modName->setDescription(_AM_MODULEBUILDER_ADDFILES_NAME_DESC);
        $form->addElement($modName, true);

        $fileEstension = new \XoopsFormText(_AM_MODULEBUILDER_ADDFILES_EXTENSION, 'file_extension', 50, 255, $this->getVar('file_extension'));
        $fileEstension->setDescription(_AM_MODULEBUILDER_ADDFILES_EXTENSION_DESC);
        $form->addElement($fileEstension, true);

        $fileInfolder = new \XoopsFormText(_AM_MODULEBUILDER_ADDFILES_INFOLDER, 'file_infolder', 50, 255, $this->getVar('file_infolder'));
        $fileInfolder->setDescription(_AM_MODULEBUILDER_ADDFILES_INFOLDER_DESC);
        $form->addElement($fileInfolder, true);

        $form->addElement(new \XoopsFormHidden('op', 'save'));
        $form->addElement(new \XoopsFormButton(_REQUIRED . ' <sup class="red bold">*</sup>', 'submit', _SUBMIT, 'submit'));

        return $form;
    }

    /**
     * Get AddFiles Values.
     * @param null $keys
     * @param null $format
     * @param null $maxDepth
     * @return array
     */
    public function getAddFilesValues($keys = null, $format = null, $maxDepth = null)
    {
        $ret = $this->getValues($keys, $format, $maxDepth);
        // Values
        $ret['id']        = $this->getVar('file_id');
        $ret['mid']       = $this->helper->getHandler('modules')->get($this->getVar('file_mid'))->getVar('mod_name');
        $ret['name']      = $this->getVar('file_name');
        $ret['extension'] = $this->getVar('file_extension');
        $ret['infolder']  = $this->getVar('file_infolder');

        return $ret;
    }
}
