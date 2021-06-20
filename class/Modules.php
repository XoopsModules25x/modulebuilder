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
 * modules class.
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.7
 *
 * @author          Txmod Xoops <webmaster@txmodxoops.org> - <http://www.txmodxoops.org/>
 *
 */

/**
 * Class Modules.
 */
class Modules extends \XoopsObject
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
     * @param null
     */
    public function __construct()
    {
        $helper   = Modulebuilder\Helper::getInstance();
        $setId    = \Xmf\Request::getInt('set_id');
        $settings = $helper->getHandler('Settings')->get($setId);

        $this->initVar('mod_id', XOBJ_DTYPE_INT);
        $this->initVar('mod_name', XOBJ_DTYPE_TXTBOX, $settings->getVar('set_name'));
        $this->initVar('mod_dirname', XOBJ_DTYPE_TXTBOX, $settings->getVar('set_dirname'));
        $this->initVar('mod_version', XOBJ_DTYPE_TXTBOX, $settings->getVar('set_version'));
        $this->initVar('mod_since', XOBJ_DTYPE_TXTBOX, $settings->getVar('set_since'));
        $this->initVar('mod_min_php', XOBJ_DTYPE_TXTBOX, $settings->getVar('set_min_php'));
        $this->initVar('mod_min_xoops', XOBJ_DTYPE_TXTBOX, $settings->getVar('set_min_xoops'));
        $this->initVar('mod_min_admin', XOBJ_DTYPE_TXTBOX, $settings->getVar('set_min_admin'));
        $this->initVar('mod_min_mysql', XOBJ_DTYPE_TXTBOX, $settings->getVar('set_min_mysql'));
        $this->initVar('mod_description', XOBJ_DTYPE_TXTAREA, $settings->getVar('set_description'));
        $this->initVar('mod_author', XOBJ_DTYPE_TXTBOX, $settings->getVar('set_author'));
        $this->initVar('mod_author_mail', XOBJ_DTYPE_TXTBOX, $settings->getVar('set_author_mail'));
        $this->initVar('mod_author_website_url', XOBJ_DTYPE_TXTBOX, $settings->getVar('set_author_website_url'));
        $this->initVar('mod_author_website_name', XOBJ_DTYPE_TXTBOX, $settings->getVar('set_author_website_name'));
        $this->initVar('mod_credits', XOBJ_DTYPE_TXTBOX, $settings->getVar('set_credits'));
        $this->initVar('mod_license', XOBJ_DTYPE_TXTBOX, $settings->getVar('set_license'));
        $this->initVar('mod_release_info', XOBJ_DTYPE_TXTBOX, $settings->getVar('set_release_info'));
        $this->initVar('mod_release_file', XOBJ_DTYPE_TXTBOX, $settings->getVar('set_release_file'));
        $this->initVar('mod_manual', XOBJ_DTYPE_TXTBOX, $settings->getVar('set_manual'));
        $this->initVar('mod_manual_file', XOBJ_DTYPE_TXTBOX, $settings->getVar('set_manual_file'));
        $this->initVar('mod_image', XOBJ_DTYPE_TXTBOX, $settings->getVar('set_image'));
        $this->initVar('mod_demo_site_url', XOBJ_DTYPE_TXTBOX, $settings->getVar('set_demo_site_url'));
        $this->initVar('mod_demo_site_name', XOBJ_DTYPE_TXTBOX, $settings->getVar('set_demo_site_name'));
        $this->initVar('mod_support_url', XOBJ_DTYPE_TXTBOX, $settings->getVar('set_support_url'));
        $this->initVar('mod_support_name', XOBJ_DTYPE_TXTBOX, $settings->getVar('set_support_name'));
        $this->initVar('mod_website_url', XOBJ_DTYPE_TXTBOX, $settings->getVar('set_website_url'));
        $this->initVar('mod_website_name', XOBJ_DTYPE_TXTBOX, $settings->getVar('set_website_name'));
        $this->initVar('mod_release', XOBJ_DTYPE_TXTBOX, $settings->getVar('set_release'));
        $this->initVar('mod_status', XOBJ_DTYPE_TXTBOX, $settings->getVar('set_status'));
        $this->initVar('mod_admin', XOBJ_DTYPE_INT, $settings->getVar('set_admin'));
        $this->initVar('mod_user', XOBJ_DTYPE_INT, $settings->getVar('set_user'));
        $this->initVar('mod_blocks', XOBJ_DTYPE_INT, $settings->getVar('set_blocks'));
        $this->initVar('mod_search', XOBJ_DTYPE_INT, $settings->getVar('set_search'));
        $this->initVar('mod_comments', XOBJ_DTYPE_INT, $settings->getVar('set_comments'));
        $this->initVar('mod_notifications', XOBJ_DTYPE_INT, $settings->getVar('set_notifications'));
        $this->initVar('mod_permissions', XOBJ_DTYPE_INT, $settings->getVar('set_permissions'));
        //$this->initVar('mod_inroot_copy', XOBJ_DTYPE_INT, $settings->getVar('set_inroot_copy'));
        $this->initVar('mod_donations', XOBJ_DTYPE_TXTBOX, $settings->getVar('set_donations'));
        $this->initVar('mod_subversion', XOBJ_DTYPE_TXTBOX, $settings->getVar('set_subversion'));
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
     * @return Modules
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
     * @public function getFormModules
     * @param mixed $action
     *
     * @return \XoopsThemeForm
     */
    public function getFormModules($action = false)
    {
        $helper = Modulebuilder\Helper::getInstance();
        if (false === $action) {
            $action = \Xmf\Request::getString('REQUEST_URI', '', 'SERVER');
        }
        $set      = [];
        $settings = $helper->getHandler('Settings')->getActiveSetting();
        foreach ($settings as $setting) {
            $set['name']                = $setting->getVar('set_name');
            $set['dirname']             = $setting->getVar('set_dirname');
            $set['version']             = $setting->getVar('set_version');
            $set['since']               = $setting->getVar('set_since');
            $set['min_php']             = $setting->getVar('set_min_php');
            $set['min_xoops']           = $setting->getVar('set_min_xoops');
            $set['min_admin']           = $setting->getVar('set_min_admin');
            $set['min_mysql']           = $setting->getVar('set_min_mysql');
            $set['description']         = $setting->getVar('set_description');
            $set['author']              = $setting->getVar('set_author');
            $set['license']             = $setting->getVar('set_license');
            $set['admin']               = $setting->getVar('set_admin');
            $set['user']                = $setting->getVar('set_user');
            $set['blocks']              = $setting->getVar('set_blocks');
            $set['search']              = $setting->getVar('set_search');
            $set['comments']            = $setting->getVar('set_comments');
            $set['notifications']       = $setting->getVar('set_notifications');
            $set['permissions']         = $setting->getVar('set_permissions');
            $set['inroot']              = $setting->getVar('set_inroot_copy');
            $set['image']               = $setting->getVar('set_image');
            $set['author_mail']         = $setting->getVar('set_author_mail');
            $set['author_website_url']  = $setting->getVar('set_author_website_url');
            $set['author_website_name'] = $setting->getVar('set_author_website_name');
            $set['credits']             = $setting->getVar('set_credits');
            $set['release_info']        = $setting->getVar('set_release_info');
            $set['release_file']        = $setting->getVar('set_release_file');
            $set['manual']              = $setting->getVar('set_manual');
            $set['manual_file']         = $setting->getVar('set_manual_file');
            $set['demo_site_url']       = $setting->getVar('set_demo_site_url');
            $set['demo_site_name']      = $setting->getVar('set_demo_site_name');
            $set['support_url']         = $setting->getVar('set_support_url');
            $set['support_name']        = $setting->getVar('set_support_name');
            $set['website_url']         = $setting->getVar('set_website_url');
            $set['website_name']        = $setting->getVar('set_website_name');
            $set['release']             = $setting->getVar('set_release');
            $set['status']              = $setting->getVar('set_status');
            $set['donations']           = $setting->getVar('set_donations');
            $set['subversion']          = $setting->getVar('set_subversion');
        }

        $isNew = $this->isNew();
        $title = $isNew ? \sprintf(\_AM_MODULEBUILDER_MODULE_NEW) : \sprintf(\_AM_MODULEBUILDER_MODULE_EDIT);

        require_once \XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

        $form = new \XoopsThemeForm($title, 'moduleform', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');

        $modName = $isNew ? $set['name'] : $this->getVar('mod_name');
        $modNameText = new \XoopsFormText(\_AM_MODULEBUILDER_MODULE_NAME, 'mod_name', 50, 255, $modName);
        $modNameText->setDescription(\_AM_MODULEBUILDER_MODULE_NAME_DESC);
        $form->addElement($modNameText, true);

        $modDirname = $isNew ? $set['dirname'] : $this->getVar('mod_dirname');
        $modDirnameText = new \XoopsFormText(\_AM_MODULEBUILDER_MODULE_DIRNAME, 'mod_dirname', 25, 255, $modDirname);
        $modDirnameText->setDescription(\_AM_MODULEBUILDER_MODULE_DIRNAME_DESC);
        $form->addElement($modDirnameText, true);

        $modVersion = $isNew ? $set['version'] : $this->getVar('mod_version');
        $form->addElement(new \XoopsFormText(\_AM_MODULEBUILDER_MODULE_VERSION, 'mod_version', 10, 25, $modVersion), true);

        $modSince = $isNew ? $set['since'] : $this->getVar('mod_since');
        $form->addElement(new \XoopsFormText(\_AM_MODULEBUILDER_MODULE_SINCE, 'mod_since', 10, 25, $modSince), true);

        $modMinPhp = $isNew ? $set['min_php'] : $this->getVar('mod_min_php');
        $form->addElement(new \XoopsFormText(\_AM_MODULEBUILDER_MODULE_MIN_PHP, 'mod_min_php', 10, 25, $modMinPhp), true);

        $modMinXoops = $isNew ? $set['min_xoops'] : $this->getVar('mod_min_xoops');
        $form->addElement(new \XoopsFormText(\_AM_MODULEBUILDER_MODULE_MIN_XOOPS, 'mod_min_xoops', 10, 25, $modMinXoops), true);

        $modMinAdmin = $isNew ? $set['min_admin'] : $this->getVar('mod_min_admin');
        $form->addElement(new \XoopsFormText(\_AM_MODULEBUILDER_MODULE_MIN_ADMIN, 'mod_min_admin', 10, 25, $modMinAdmin), true);

        $modMinMysql = $isNew ? $set['min_mysql'] : $this->getVar('mod_min_mysql');
        $form->addElement(new \XoopsFormText(\_AM_MODULEBUILDER_MODULE_MIN_MYSQL, 'mod_min_mysql', 10, 25, $modMinMysql), true);
        // Name description
        $editorConfigs           = [];
        $editorConfigs['name']   = 'mod_description';
        $editorConfigs['value']  = $isNew ? $set['description'] : $this->getVar('mod_description', 'e');
        $editorConfigs['rows']   = 5;
        $editorConfigs['cols']   = 100;
        $editorConfigs['width']  = '50%';
        $editorConfigs['height'] = '100px';
        $editorConfigs['editor'] = $helper->getConfig('modulebuilder_editor');
        $form->addElement(new \XoopsFormEditor(\_AM_MODULEBUILDER_MODULE_DESCRIPTION, 'mod_description', $editorConfigs), true);
        // Author
        $modAuthor = $isNew ? $set['author'] : $this->getVar('mod_author');
        $form->addElement(new \XoopsFormText(\_AM_MODULEBUILDER_MODULE_AUTHOR, 'mod_author', 50, 255, $modAuthor), true);
        $modLicense = $isNew ? $set['license'] : $this->getVar('mod_license');
        $form->addElement(new \XoopsFormText(\_AM_MODULEBUILDER_MODULE_LICENSE, 'mod_license', 50, 255, $modLicense), true);

        $optionsTray = new \XoopsFormElementTray(_OPTIONS, '<br>');
        $optionsTray->setDescription(\_AM_MODULEBUILDER_OPTIONS_DESC);
        // Check All Modules Options
        $checkAllOptions = new \XoopsFormCheckBox('', 'modulebox', 1);
        $checkAllOptions->addOption('allbox', \_AM_MODULEBUILDER_MODULE_ALL);
        $checkAllOptions->setExtra(' onclick="xoopsCheckAll(\'moduleform\', \'modulebox\');" ');
        $checkAllOptions->setClass('xo-checkall');
        $optionsTray->addElement($checkAllOptions);
        // Options
        $checkbox = new \XoopsFormCheckbox(' ', 'module_option', $this->getOptionsModules(), '<br>');
        foreach ($this->options as $option) {
            $checkbox->addOption($option, self::getDefinedLanguage('\_AM_MODULEBUILDER_MODULE_' . \mb_strtoupper($option)));
        }
        $optionsTray->addElement($checkbox);

        $form->addElement($optionsTray);

        $modImage = $this->getVar('mod_image');
        $modImage = $modImage ?: $set['image'];

        $uploadDirectory = 'uploads/' . $GLOBALS['xoopsModule']->dirname() . '/images/modules';
        $imgtray         = new \XoopsFormElementTray(\_AM_MODULEBUILDER_MODULE_IMAGE, '<br>');
        $imgpath         = \sprintf(\_AM_MODULEBUILDER_FORMIMAGE_PATH, './' . $uploadDirectory . '/');
        $imageselect     = new \XoopsFormSelect($imgpath, 'mod_image', $modImage);
        $modImageArray   = \XoopsLists::getImgListAsArray(TDMC_UPLOAD_IMGMOD_PATH);
        foreach ($modImageArray as $image) {
            $imageselect->addOption($image, $image);
        }
        $imageselect->setExtra("onchange='showImgSelected(\"image3\", \"mod_image\", \"" . $uploadDirectory . '", "", "' . \XOOPS_URL . "\")'");
        $imgtray->addElement($imageselect);
        $imgtray->addElement(new \XoopsFormLabel('', "<br><img src='" . TDMC_UPLOAD_IMGMOD_URL . '/' . $modImage . "' id='image3' alt='' /><br>"));

        $fileseltray = new \XoopsFormElementTray('', '<br>');
        $fileseltray->addElement(new \XoopsFormFile(\_AM_MODULEBUILDER_FORMUPLOAD, 'attachedfile', $helper->getConfig('maxsize_image')));
        $fileseltray->addElement(new \XoopsFormLabel(''));
        $imgtray->addElement($fileseltray);
        $form->addElement($imgtray);
        //---------- START LOGO GENERATOR -----------------
        $tables_img = $this->getVar('table_image') ?: 'about.png';
        $iconsdir   = '/Frameworks/moduleclasses/icons/32';
        if (\is_dir(\XOOPS_ROOT_PATH . $iconsdir)) {
            $uploadDirectory = $iconsdir;
            $imgpath         = \sprintf(\_AM_MODULEBUILDER_FORMIMAGE_PATH, ".{$iconsdir}/");
        } else {
            $uploadDirectory = '/uploads/' . $GLOBALS['xoopsModule']->dirname() . '/images/tables';
            $imgpath         = \sprintf(\_AM_MODULEBUILDER_FORMIMAGE_PATH, './uploads/' . $GLOBALS['xoopsModule']->dirname() . '/images/tables');
        }
        $createLogoTray    = new \XoopsFormElementTray(\_AM_MODULEBUILDER_MODULE_CREATENEWLOGO, '<br>');
        $iconSelect        = new \XoopsFormSelect($imgpath, 'tables_img', $tables_img, 8);
        $tablesImagesArray = \XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH . $uploadDirectory);
        foreach ($tablesImagesArray as $image) {
            $iconSelect->addOption($image, $image);
        }
        $iconSelect->setExtra(" onchange='showImgSelected2(\"image4\", \"tables_img\", \"" . $uploadDirectory . '", "", "' . XOOPS_URL . "\")' ");
        $createLogoTray->addElement($iconSelect);
        $createLogoTray->addElement(new \XoopsFormLabel('', "<br><img src='" . XOOPS_URL . '/' . $uploadDirectory . '/' . $tables_img . "' id='image4' alt='' />"));
        // Create preview and submit buttons
        $buttonLogoGenerator4 = new \XoopsFormButton('', 'button4', \_AM_MODULEBUILDER_MODULE_CREATENEWLOGO, 'button');
        $buttonLogoGenerator4->setExtra(" onclick='createNewModuleLogo(\"" . TDMC_URL . "\")' ");
        $createLogoTray->addElement($buttonLogoGenerator4);

        $form->addElement($createLogoTray);
        //------------ END LOGO GENERATOR --------------------

        $modAuthorMail = $isNew ? $set['author_mail'] : $this->getVar('mod_author_mail');
        $form->addElement(new \XoopsFormText(\_AM_MODULEBUILDER_MODULE_AUTHOR_MAIL, 'mod_author_mail', 50, 255, $modAuthorMail));

        $modAuthorWebsiteUrl = $isNew ? $set['author_website_url'] : $this->getVar('mod_author_website_url');
        $form->addElement(new \XoopsFormText(\_AM_MODULEBUILDER_MODULE_AUTHOR_WEBSITE_URL, 'mod_author_website_url', 50, 255, $modAuthorWebsiteUrl));

        $modAuthorWebsiteName = $isNew ? $set['author_website_name'] : $this->getVar('mod_author_website_name');
        $form->addElement(new \XoopsFormText(\_AM_MODULEBUILDER_MODULE_AUTHOR_WEBSITE_NAME, 'mod_author_website_name', 50, 255, $modAuthorWebsiteName));

        $modCredits = $isNew ? $set['credits'] : $this->getVar('mod_credits');
        $form->addElement(new \XoopsFormText(\_AM_MODULEBUILDER_MODULE_CREDITS, 'mod_credits', 50, 255, $modCredits));

        $modReleaseInfo = $isNew ? $set['release_info'] : $this->getVar('mod_release_info');
        $form->addElement(new \XoopsFormText(\_AM_MODULEBUILDER_MODULE_RELEASE_INFO, 'mod_release_info', 50, 255, $modReleaseInfo));

        $modReleaseFile = $isNew ? $set['release_file'] : $this->getVar('mod_release_file');
        $form->addElement(new \XoopsFormText(\_AM_MODULEBUILDER_MODULE_RELEASE_FILE, 'mod_release_file', 50, 255, $modReleaseFile));

        $modManual = $isNew ? $set['manual'] : $this->getVar('mod_manual');
        $form->addElement(new \XoopsFormText(\_AM_MODULEBUILDER_MODULE_MANUAL, 'mod_manual', 50, 255, $modManual));

        $modManualFile = $isNew ? $set['manual_file'] : $this->getVar('mod_manual_file');
        $form->addElement(new \XoopsFormText(\_AM_MODULEBUILDER_MODULE_MANUAL_FILE, 'mod_manual_file', 50, 255, $modManualFile));

        $modDemoSiteUrl = $isNew ? $set['demo_site_url'] : $this->getVar('mod_demo_site_url');
        $form->addElement(new \XoopsFormText(\_AM_MODULEBUILDER_MODULE_DEMO_SITE_URL, 'mod_demo_site_url', 50, 255, $modDemoSiteUrl));

        $modDemoSiteName = $isNew ? $set['demo_site_name'] : $this->getVar('mod_demo_site_name');
        $form->addElement(new \XoopsFormText(\_AM_MODULEBUILDER_MODULE_DEMO_SITE_NAME, 'mod_demo_site_name', 50, 255, $modDemoSiteName));

        $modSupportUrl = $isNew ? $set['support_url'] : $this->getVar('mod_support_url');
        $form->addElement(new \XoopsFormText(\_AM_MODULEBUILDER_MODULE_SUPPORT_URL, 'mod_support_url', 50, 255, $modSupportUrl));

        $modSupportName = $isNew ? $set['support_name'] : $this->getVar('mod_support_name');
        $form->addElement(new \XoopsFormText(\_AM_MODULEBUILDER_MODULE_SUPPORT_NAME, 'mod_support_name', 50, 255, $modSupportName));

        $modWebsiteUrl = $isNew ? $set['website_url'] : $this->getVar('mod_website_url');
        $form->addElement(new \XoopsFormText(\_AM_MODULEBUILDER_MODULE_WEBSITE_URL, 'mod_website_url', 50, 255, $modWebsiteUrl));

        $modWebsiteName = $isNew ? $set['website_name'] : $this->getVar('mod_website_name');
        $form->addElement(new \XoopsFormText(\_AM_MODULEBUILDER_MODULE_WEBSITE_NAME, 'mod_website_name', 50, 255, $modWebsiteName));

        $modRelease = $isNew ? $set['release'] : $this->getVar('mod_release');
        $form->addElement(new \XoopsFormText(\_AM_MODULEBUILDER_MODULE_RELEASE, 'mod_release', 50, 255, $modRelease));

        $modStatus = $isNew ? $set['status'] : $this->getVar('mod_status');
        $form->addElement(new \XoopsFormText(\_AM_MODULEBUILDER_MODULE_STATUS, 'mod_status', 50, 255, $modStatus));

        $modDonations = $isNew ? $set['donations'] : $this->getVar('mod_donations');
        $form->addElement(new \XoopsFormText(\_AM_MODULEBUILDER_MODULE_PAYPAL_BUTTON, 'mod_donations', 50, 255, $modDonations));

        $modSubversion = $isNew ? $set['subversion'] : $this->getVar('mod_subversion');
        $form->addElement(new \XoopsFormText(\_AM_MODULEBUILDER_MODULE_SUBVERSION, 'mod_subversion', 50, 255, $modSubversion));

        $buttonTray = new \XoopsFormElementTray(_REQUIRED . ' <sup class="red bold">*</sup>', '');
        $buttonTray->addElement(new \XoopsFormHidden('op', 'save'));
        $buttonTray->addElement(new \XoopsFormButton('', 'submit', \_SUBMIT, 'submit'));
        $form->addElement($buttonTray);

        return $form;
    }

    /**
     * @private static function createLogo
     * @param mixed  $logoIcon
     * @param string $moduleDirname
     *
     * @return bool|string
     */
    private static function createLogo($logoIcon, $moduleDirname)
    {
        if (!\extension_loaded('gd')) {
            return false;
        }
        $requiredFunctions = ['imagecreatefrompng', 'imagefttext', 'imagecopy', 'imagepng', 'imagedestroy', 'imagecolorallocate'];
        foreach ($requiredFunctions as $func) {
            if (!\function_exists($func)) {
                return false;
            }
        }

        if (!\file_exists($imageBase = TDMC_IMAGES_LOGOS_PATH . '/empty.png')
            || !\file_exists($font = TDMC_FONTS_PATH . '/VeraBd.ttf')
            || !\file_exists($iconFile = XOOPS_ICONS32_PATH . '/' . \basename($logoIcon))) {
            return false;
        }
        $imageModule = \imagecreatefrompng($imageBase);
        $imageIcon   = \imagecreatefrompng($iconFile);
        // Write text
        $textColor   = imagecolorallocate($imageModule, 0, 0, 0);
        $spaceBorder = (92 - mb_strlen($moduleDirname) * 7.5) / 2;
        imagefttext($imageModule, 8.5, 0, $spaceBorder, 45, $textColor, $font, \ucfirst($moduleDirname), []);
        imagecopy($imageModule, $imageIcon, 29, 2, 0, 0, 32, 32);
        $logoImg = '/' . 'logoModule.png';
        \imagepng($imageModule, TDMC_UPLOAD_IMGMOD_PATH . $logoImg);
        \imagedestroy($imageModule);
        \imagedestroy($imageIcon);

        return TDMC_UPLOAD_IMGMOD_URL . $logoImg;
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
    public function getValuesModules($keys = null, $format = null, $maxDepth = null)
    {
        $ret = $this->getValues($keys, $format, $maxDepth);
        // Values
        $ret['id']            = $this->getVar('mod_id');
        $ret['name']          = $this->getVar('mod_name');
        $ret['version']       = $this->getVar('mod_version');
        $ret['image']         = $this->getVar('mod_image');
        $ret['release']       = $this->getVar('mod_release');
        $ret['status']        = $this->getVar('mod_status');
        $ret['admin']         = $this->getVar('mod_admin');
        $ret['user']          = $this->getVar('mod_user');
        $ret['blocks']        = $this->getVar('mod_blocks');
        $ret['search']        = $this->getVar('mod_search');
        $ret['comments']      = $this->getVar('mod_comments');
        $ret['notifications'] = $this->getVar('mod_notifications');
        $ret['permissions']   = $this->getVar('mod_permissions');

        return $ret;
    }

    /**
     * Get getOptionsModules.
     *
     * @return array
     */
    private function getOptionsModules()
    {
        $retModules = [];
        foreach ($this->options as $option) {
            if (1 == $this->getVar('mod_' . $option)) {
                $retModules[] = $option;
            }
        }

        return $retModules;
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
