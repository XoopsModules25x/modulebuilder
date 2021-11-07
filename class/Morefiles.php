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
 * morefiles class.
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.7
 *
 * @author          Txmod Xoops <webmaster@txmodxoops.org> - <https://xoops.org/>
 *
 */

/*
*  @Class Morefiles
*  @extends \XoopsObject
*/

/**
 * Class Morefiles.
 */
class Morefiles extends \XoopsObject
{
    /**
     * Settings.
     *
     * @var mixed
     */
    private $settings;

    /**
     * @public function constructor class
     *
     * @param null
     */
    public function __construct()
    {
        $this->initVar('file_id', XOBJ_DTYPE_INT);
        $this->initVar('file_mid', XOBJ_DTYPE_INT);
        $this->initVar('file_type', XOBJ_DTYPE_INT);
        $this->initVar('file_name', XOBJ_DTYPE_TXTBOX);
        $this->initVar('file_extension', XOBJ_DTYPE_TXTBOX);
        $this->initVar('file_upload', XOBJ_DTYPE_TXTBOX);
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
        $arg = $args[0] ?? null;

        return $this->getVar($method, $arg);
    }

    /**
     * @static function getInstance
     *
     * @param null
     *
     * @return Morefiles
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
     *
     * @param bool|mixed $action
     * @return \XoopsThemeForm
     */
    public function getFormMorefiles($action = false)
    {
        $helper = Modulebuilder\Helper::getInstance();
        if (false === $action) {
            $action = \Xmf\Request::getString('REQUEST_URI', '', 'SERVER');
        }

        $isNew = $this->isNew();
        $title = $isNew ? \sprintf(\_AM_MODULEBUILDER_MORE_FILES_NEW) : \sprintf(\_AM_MODULEBUILDER_MORE_FILES_EDIT);

        \xoops_load('XoopsFormLoader');

        $form = new \XoopsThemeForm($title, 'morefilesform', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');

        $modules       = $helper->getHandler('Modules')->getObjects(null);
        $modulesSelect = new \XoopsFormSelect(\_AM_MODULEBUILDER_MORE_FILES_MODULES, 'file_mid', $this->getVar('file_mid'));
        $modulesSelect->addOption('', \_AM_MODULEBUILDER_MORE_FILES_MODULE_SELECT);
        foreach ($modules as $mod) {
            //$modulesSelect->addOptionArray();
            $modulesSelect->addOption($mod->getVar('mod_id'), $mod->getVar('mod_name'));
        }
        $form->addElement($modulesSelect, true);

        $typeSelect = new \XoopsFormSelect(\_AM_MODULEBUILDER_MORE_FILES_TYPE, 'file_type', $this->getVar('file_type'));
        $typeSelect->addOption(0, ' ');
        $typeSelect->addOption(Constants::MORE_FILES_TYPE_EMPTY, \_AM_MODULEBUILDER_MORE_FILES_TYPE_EMPTY);
        $typeSelect->addOption(Constants::MORE_FILES_TYPE_COPY, \_AM_MODULEBUILDER_MORE_FILES_TYPE_COPY);
        $form->addElement($typeSelect, true);

        $fileName = new \XoopsFormText(\_AM_MODULEBUILDER_MORE_FILES_NAME, 'file_name', 50, 255, $this->getVar('file_name'));
        $fileName->setDescription(\_AM_MODULEBUILDER_MORE_FILES_NAME_DESC);
        $form->addElement($fileName);

        $fileExtension = new \XoopsFormText(\_AM_MODULEBUILDER_MORE_FILES_EXTENSION, 'file_extension', 50, 255, $this->getVar('file_extension'));
        $fileExtension->setDescription(\_AM_MODULEBUILDER_MORE_FILES_EXTENSION_DESC);
        $form->addElement($fileExtension);


        $fileUpload = $this->isNew() ? '' : $this->getVar('file_upload');
        $fileTray = new \XoopsFormElementTray(\_AM_MODULEBUILDER_MORE_FILES_UPLOAD, '<br>' );
        $fileDirectory = '/uploads/modulebuilder/files';
        $fileSelect = new \XoopsFormSelect( ".{$fileDirectory}/", 'file_upload', $fileUpload, 5);
        $filesArray = \XoopsLists::getFileListAsArray( TDMC_UPLOAD_FILES_PATH);
        $fileSelect->addOption('', ' - ');
        foreach($filesArray as $file1) {
            if ('index.html' !== $file1 && 'index.php' !== $file1) {
                $fileSelect->addOption("{$file1}", $file1);
            }
        }
        $fileTray->addElement($fileSelect, false);
        $form->addElement($fileTray);


        $fileInfolder = new \XoopsFormText(\_AM_MODULEBUILDER_MORE_FILES_INFOLDER, 'file_infolder', 50, 255, $this->getVar('file_infolder'));
        $fileInfolder->setDescription(\_AM_MODULEBUILDER_MORE_FILES_INFOLDER_DESC);
        $form->addElement($fileInfolder, true);

        $form->addElement(new \XoopsFormHidden('op', 'save'));
        $form->addElement(new \XoopsFormButton(_REQUIRED . ' <sup class="red bold">*</sup>', 'submit', \_SUBMIT, 'submit'));

        return $form;
    }

    /**
     * Get Values.
     *
     * @param null $keys
     * @param null $format
     * @param null $maxDepth
     *
     * @return array
     */
    public function getValuesMorefiles($keys = null, $format = null, $maxDepth = null)
    {
        $helper = Modulebuilder\Helper::getInstance();
        $ret    = $this->getValues($keys, $format, $maxDepth);
        // Values
        $ret['id']        = $this->getVar('file_id');
        $ret['mid']       = $helper->getHandler('Modules')->get($this->getVar('file_mid'))->getVar('mod_name');
        $ret['type']      = $this->getVar('file_type') == Constants::MORE_FILES_TYPE_EMPTY ? \_AM_MODULEBUILDER_MORE_FILES_TYPE_EMPTY : \_AM_MODULEBUILDER_MORE_FILES_TYPE_COPY;
        $ret['name']      = $this->getVar('file_name');
        $ret['extension'] = $this->getVar('file_extension');
        $ret['upload']      = $this->getVar('file_upload');
        $ret['infolder']  = $this->getVar('file_infolder');

        return $ret;
    }
}
