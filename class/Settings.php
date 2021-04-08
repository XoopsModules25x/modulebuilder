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
 * settings class.
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.7
 *
 * @author          TDM TEAM DEV MODULE
 *
 */
// include __DIR__ . '/autoload.php';
/*
*  @Class Settings
*  @extends \XoopsObject
*/

/**
 * Class Settings.
 */
class Settings extends \XoopsObject
{
    /**
     * Options.
     */
    public $options = [
        'admin',
        'user',
        'blocks',
        'search',
        'comments',
        'notifications',
        'permissions',
        //'inroot_copy',
    ];

    /**
     * @public function constructor class
     *
     * @param null
     */
    public function __construct()
    {
        $helper = Modulebuilder\Helper::getInstance();
        $this->initVar('set_id', XOBJ_DTYPE_INT);
        $this->initVar('set_name', XOBJ_DTYPE_TXTBOX, $helper->getConfig('name'));
        $this->initVar('set_dirname', XOBJ_DTYPE_TXTBOX, $helper->getConfig('dirname'));
        $this->initVar('set_version', XOBJ_DTYPE_TXTBOX, $helper->getConfig('version'));
        $this->initVar('set_since', XOBJ_DTYPE_TXTBOX, $helper->getConfig('since'));
        $this->initVar('set_min_php', XOBJ_DTYPE_TXTBOX, $helper->getConfig('min_php'));
        $this->initVar('set_min_xoops', XOBJ_DTYPE_TXTBOX, $helper->getConfig('min_xoops'));
        $this->initVar('set_min_admin', XOBJ_DTYPE_TXTBOX, $helper->getConfig('min_admin'));
        $this->initVar('set_min_mysql', XOBJ_DTYPE_TXTBOX, $helper->getConfig('min_mysql'));
        $this->initVar('set_description', XOBJ_DTYPE_TXTAREA, $helper->getConfig('description'));
        $this->initVar('set_author', XOBJ_DTYPE_TXTBOX, $helper->getConfig('author'));
        $this->initVar('set_author_mail', XOBJ_DTYPE_TXTBOX, $helper->getConfig('author_email'));
        $this->initVar('set_author_website_url', XOBJ_DTYPE_TXTBOX, $helper->getConfig('author_website_url'));
        $this->initVar('set_author_website_name', XOBJ_DTYPE_TXTBOX, $helper->getConfig('author_website_name'));
        $this->initVar('set_credits', XOBJ_DTYPE_TXTBOX, $helper->getConfig('credits'));
        $this->initVar('set_license', XOBJ_DTYPE_TXTBOX, $helper->getConfig('license'));
        $this->initVar('set_release_info', XOBJ_DTYPE_TXTBOX, $helper->getConfig('release_info'));
        $this->initVar('set_release_file', XOBJ_DTYPE_TXTBOX, $helper->getConfig('release_file'));
        $this->initVar('set_manual', XOBJ_DTYPE_TXTBOX, $helper->getConfig('manual'));
        $this->initVar('set_manual_file', XOBJ_DTYPE_TXTBOX, $helper->getConfig('manual_file'));
        $this->initVar('set_image', XOBJ_DTYPE_TXTBOX, $helper->getConfig('image'));
        $this->initVar('set_demo_site_url', XOBJ_DTYPE_TXTBOX, $helper->getConfig('demo_site_url'));
        $this->initVar('set_demo_site_name', XOBJ_DTYPE_TXTBOX, $helper->getConfig('demo_site_name'));
        $this->initVar('set_support_url', XOBJ_DTYPE_TXTBOX, $helper->getConfig('support_url'));
        $this->initVar('set_support_name', XOBJ_DTYPE_TXTBOX, $helper->getConfig('support_name'));
        $this->initVar('set_website_url', XOBJ_DTYPE_TXTBOX, $helper->getConfig('website_url'));
        $this->initVar('set_website_name', XOBJ_DTYPE_TXTBOX, $helper->getConfig('website_name'));
        $this->initVar('set_release', XOBJ_DTYPE_TXTBOX, $helper->getConfig('release_date'));
        $this->initVar('set_status', XOBJ_DTYPE_TXTBOX, $helper->getConfig('status'));
        $this->initVar('set_admin', XOBJ_DTYPE_INT, $helper->getConfig('display_admin'));
        $this->initVar('set_user', XOBJ_DTYPE_INT, $helper->getConfig('display_user'));
        $this->initVar('set_blocks', XOBJ_DTYPE_INT, $helper->getConfig('active_blocks'));
        $this->initVar('set_search', XOBJ_DTYPE_INT, $helper->getConfig('active_search'));
        $this->initVar('set_comments', XOBJ_DTYPE_INT, $helper->getConfig('active_comments'));
        $this->initVar('set_notifications', XOBJ_DTYPE_INT, $helper->getConfig('active_notifications'));
        $this->initVar('set_permissions', XOBJ_DTYPE_INT, $helper->getConfig('active_permissions'));
        $this->initVar('set_inroot_copy', XOBJ_DTYPE_INT, $helper->getConfig('inroot_copy'));
        $this->initVar('set_donations', XOBJ_DTYPE_TXTBOX, $helper->getConfig('donations'));
        $this->initVar('set_subversion', XOBJ_DTYPE_TXTBOX, $helper->getConfig('subversion'));
        $this->initVar('set_type', XOBJ_DTYPE_TXTBOX);
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
     *
     * @param null
     *
     * @return Settings
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
     * @public function getForm
     *
     * @param bool|mixed $action
     * @return \XoopsThemeForm
     */
    public function getFormSettings($action = false)
    {
        $helper = Modulebuilder\Helper::getInstance();
        if (false === $action) {
            $action = \Xmf\Request::getString('REQUEST_URI', '', 'SERVER');
        }

        $isNew = $this->isNew();
        $title = $isNew ? \sprintf(_AM_MODULEBUILDER_SETTING_NEW) : \sprintf(_AM_MODULEBUILDER_SETTING_EDIT);

        include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

        $form = new \XoopsThemeForm($title, 'settingform', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');

        $form->addElement(new \XoopsFormHidden('set_id', $this->getVar('set_id')));
        $form->addElement(new \XoopsFormText(_AM_MODULEBUILDER_SETTING_NAME, 'set_name', 50, 255, $this->getVar('set_name')));
        $form->addElement(new \XoopsFormText(_AM_MODULEBUILDER_SETTING_DIRNAME, 'set_dirname', 25, 255, $this->getVar('set_dirname')));
        $form->addElement(new \XoopsFormText(_AM_MODULEBUILDER_SETTING_VERSION, 'set_version', 10, 25, $this->getVar('set_version')));
        $form->addElement(new \XoopsFormText(_AM_MODULEBUILDER_SETTING_SINCE, 'set_since', 10, 25, $this->getVar('set_since')));
        $form->addElement(new \XoopsFormText(_AM_MODULEBUILDER_SETTING_MIN_PHP, 'set_min_php', 10, 25, $this->getVar('set_min_php')));
        $form->addElement(new \XoopsFormText(_AM_MODULEBUILDER_SETTING_MIN_XOOPS, 'set_min_xoops', 10, 25, $this->getVar('set_min_xoops')));
        $form->addElement(new \XoopsFormText(_AM_MODULEBUILDER_SETTING_MIN_ADMIN, 'set_min_admin', 10, 25, $this->getVar('set_min_admin')));
        $form->addElement(new \XoopsFormText(_AM_MODULEBUILDER_SETTING_MIN_MYSQL, 'set_min_mysql', 10, 25, $this->getVar('set_min_mysql')));
        $form->addElement(new \XoopsFormTextArea(_AM_MODULEBUILDER_SETTING_DESCRIPTION, 'set_description', $this->getVar('set_description'), 4, 25));
        $form->addElement(new \XoopsFormText(_AM_MODULEBUILDER_SETTING_AUTHOR, 'set_author', 50, 255, $this->getVar('set_author')));
        $form->addElement(new \XoopsFormText(_AM_MODULEBUILDER_SETTING_LICENSE, 'set_license', 50, 255, $this->getVar('set_license')));
        // Check All Settings Options
        $optionsTray     = new \XoopsFormElementTray(_OPTIONS, '<br>');
        $checkAllOptions = new \XoopsFormCheckBox('', 'settingbox', 1);
        $checkAllOptions->addOption('allbox', _AM_MODULEBUILDER_SETTING_ALL);
        $checkAllOptions->setExtra(' onclick="xoopsCheckAll(\'settingform\', \'settingbox\');" ');
        $checkAllOptions->setClass('xo-checkall');
        $optionsTray->addElement($checkAllOptions);
        // Options
        $settingOption = $this->getOptionsSettings();
        $checkbox      = new \XoopsFormCheckbox(' ', 'setting_option', $settingOption, '<br>');
        $checkbox->setDescription(_AM_MODULEBUILDER_OPTIONS_DESC);
        foreach ($this->options as $option) {
            $checkbox->addOption($option, self::getDefinedLanguage('_AM_MODULEBUILDER_SETTING_' . \mb_strtoupper($option)));
        }
        $optionsTray->addElement($checkbox);

        $form->addElement($optionsTray);

        $modImage = $this->getVar('set_image');
        //$modImage = $modImage ?: $set['image'];

        $uploadDirectory = 'uploads/' . $GLOBALS['xoopsModule']->dirname() . '/images/modules';
        $imgtray         = new \XoopsFormElementTray(_AM_MODULEBUILDER_SETTING_IMAGE, '<br>');
        $imgpath         = \sprintf(_AM_MODULEBUILDER_FORMIMAGE_PATH, './' . $uploadDirectory . '/');
        $imageSelect     = new \XoopsFormSelect($imgpath, 'set_image', $modImage);
        $modImage_array  = \XoopsLists::getImgListAsArray(TDMC_UPLOAD_IMGMOD_PATH);
        foreach ($modImage_array as $image) {
            $imageSelect->addOption($image, $image);
        }
        $imageSelect->setExtra("onchange='showImgSelected(\"image3\", \"set_image\", \"" . $uploadDirectory . '", "", "' . XOOPS_URL . "\")'");
        $imgtray->addElement($imageSelect);
        $imgtray->addElement(new \XoopsFormLabel('', "<br><img src='" . TDMC_UPLOAD_IMGMOD_URL . '/' . $modImage . "' id='image3' alt='' /><br>"));

        $fileseltray = new \XoopsFormElementTray('', '<br>');
        $fileseltray->addElement(new \XoopsFormFile(_AM_MODULEBUILDER_FORMUPLOAD, 'attachedfile', $helper->getConfig('maxsize_image')));
        $fileseltray->addElement(new \XoopsFormLabel(''));
        $imgtray->addElement($fileseltray);
        $form->addElement($imgtray);

        $form->addElement(new \XoopsFormText(_AM_MODULEBUILDER_SETTING_AUTHOR_MAIL, 'set_author_mail', 50, 255, $this->getVar('set_author_mail')));
        $form->addElement(new \XoopsFormText(_AM_MODULEBUILDER_SETTING_AUTHOR_WEBSITE_URL, 'set_author_website_url', 50, 255, $this->getVar('set_author_website_url')));
        $form->addElement(new \XoopsFormText(_AM_MODULEBUILDER_SETTING_AUTHOR_WEBSITE_NAME, 'set_author_website_name', 50, 255, $this->getVar('set_author_website_name')));
        $form->addElement(new \XoopsFormText(_AM_MODULEBUILDER_SETTING_CREDITS, 'set_credits', 50, 255, $this->getVar('set_credits')));
        $form->addElement(new \XoopsFormText(_AM_MODULEBUILDER_SETTING_RELEASE_INFO, 'set_release_info', 50, 255, $this->getVar('set_release_info')));
        $form->addElement(new \XoopsFormText(_AM_MODULEBUILDER_SETTING_RELEASE_FILE, 'set_release_file', 50, 255, $this->getVar('set_release_file')));
        $form->addElement(new \XoopsFormText(_AM_MODULEBUILDER_SETTING_MANUAL, 'set_manual', 50, 255, $this->getVar('set_manual')));
        $form->addElement(new \XoopsFormText(_AM_MODULEBUILDER_SETTING_MANUAL_FILE, 'set_manual_file', 50, 255, $this->getVar('set_manual_file')));
        $form->addElement(new \XoopsFormText(_AM_MODULEBUILDER_SETTING_DEMO_SITE_URL, 'set_demo_site_url', 50, 255, $this->getVar('set_demo_site_url')));
        $form->addElement(new \XoopsFormText(_AM_MODULEBUILDER_SETTING_DEMO_SITE_NAME, 'set_demo_site_name', 50, 255, $this->getVar('set_demo_site_name')));
        $form->addElement(new \XoopsFormText(_AM_MODULEBUILDER_SETTING_SUPPORT_URL, 'set_support_url', 50, 255, $this->getVar('set_support_url')));
        $form->addElement(new \XoopsFormText(_AM_MODULEBUILDER_SETTING_SUPPORT_NAME, 'set_support_name', 50, 255, $this->getVar('set_support_name')));
        $form->addElement(new \XoopsFormText(_AM_MODULEBUILDER_SETTING_WEBSITE_URL, 'set_website_url', 50, 255, $this->getVar('set_website_url')));
        $form->addElement(new \XoopsFormText(_AM_MODULEBUILDER_SETTING_WEBSITE_NAME, 'set_website_name', 50, 255, $this->getVar('set_website_name')));
        $form->addElement(new \XoopsFormTextDateSelect(_AM_MODULEBUILDER_SETTING_RELEASE, 'set_release', '', $this->getVar('set_release')));
        $form->addElement(new \XoopsFormText(_AM_MODULEBUILDER_SETTING_STATUS, 'set_status', 50, 255, $this->getVar('set_status')));
        $form->addElement(new \XoopsFormText(_AM_MODULEBUILDER_SETTING_PAYPAL_BUTTON, 'set_donations', 50, 255, $this->getVar('set_donations')));
        $form->addElement(new \XoopsFormText(_AM_MODULEBUILDER_SETTING_SUBVERSION, 'set_subversion', 50, 255, $this->getVar('set_subversion')));
        $setTypeRadio = new \XoopsFormRadio(_AM_MODULEBUILDER_SETTING_TYPE, 'set_type', $this->getVar('set_type'));
        $setTypeRadio->addOption(0, _AM_MODULEBUILDER_SETTING_TYPE_INACTIVE);
        $setTypeRadio->addOption(1, _AM_MODULEBUILDER_SETTING_TYPE_ACTIVE);
        $form->addElement($setTypeRadio);

        $buttonTray = new \XoopsFormElementTray(_REQUIRED . ' <sup class="red bold">*</sup>', '');
        $buttonTray->addElement(new \XoopsFormHidden('op', 'save'));
        $buttonTray->addElement(new \XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        $form->addElement($buttonTray);

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
    public function getValuesSettings($keys = null, $format = null, $maxDepth = null)
    {
        $ret = $this->getValues($keys, $format, $maxDepth);
        // Values
        $ret['id']      = $this->getVar('set_id');
        $ret['name']    = $this->getVar('set_name');
        $ret['version'] = $this->getVar('set_version');
        $ret['image']   = $this->getVar('set_image');
        $ret['release'] = $this->getVar('set_release');
        $ret['status']  = $this->getVar('set_status');
        $ret['type']    = $this->getVar('set_type');

        return $ret;
    }

    /**
     * Get Options Settings.
     * @return array
     */
    private function getOptionsSettings()
    {
        $retSet = [];
        foreach ($this->options as $option) {
            if (1 == $this->getVar('set_' . $option)) {
                $retSet[] = $option;
            }
        }

        return $retSet;
    }

    /**
     * Get Defined Language.
     *
     * @param $lang
     *
     * @return string
     */
    private static function getDefinedLanguage($lang)
    {
        if (\defined($lang)) {
            return \constant($lang);
        }

        return $lang;
    }
}
