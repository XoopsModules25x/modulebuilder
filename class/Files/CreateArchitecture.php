<?php

namespace XoopsModules\Modulebuilder\Files;

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

//include dirname(__DIR__) . '/autoload.php';

/**
 * Class Architecture.
 */
class CreateArchitecture extends CreateStructure
{
    /**
     * @var mixed
     */
    private $cf = null;

    /**
     * @var mixed
     */
    private $helper = null;

    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
        $this->helper = Modulebuilder\Helper::getInstance();
        $this->cf     = Modulebuilder\Files\CreateFile::getInstance();
        $this->setUploadPath(TDMC_UPLOAD_REPOSITORY_PATH);
    }

    /**
     * @static function getInstance
     *
     * @param null
     *
     * @return Modulebuilder\Files\CreateArchitecture
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
     * @public function setBaseFoldersFiles
     *
     * @param $module
     */
    public function setBaseFoldersFiles($module)
    {
        // Module
        $modId = $module->getVar('mod_id');
        // Id of tables
        $tables = $this->cf->getTableTables($modId);

        $table = null;
        foreach (array_keys($tables) as $t) {
            $tableId   = $tables[$t]->getVar('table_id');
            $tableName = $tables[$t]->getVar('table_name');
            $table     = $this->helper->getHandler('Tables')->get($tableId);
        }

        $indexFile       = XOOPS_UPLOAD_PATH . '/index.html';
        $stlModuleAuthor = str_replace(' ', '', mb_strtolower($module->getVar('mod_author')));
        $this->setModuleName($module->getVar('mod_dirname'));
        $uploadPath = $this->getUploadPath();
        // Creation of "module" folder in the Directory repository
        $this->makeDir($uploadPath . '/' . $this->getModuleName());
        if (1 != $module->getVar('mod_user')) {
            // Copied of index.html file in "root module" folder
            $this->copyFile('', $indexFile, 'index.html');
        }
        if (1 == $module->getVar('mod_admin')) {
            // Creation of "admin" folder and index.html file
            $this->makeDirAndCopyFile('admin', $indexFile, 'index.html');
        }
        if (1 == $module->getVar('mod_blocks')) {
            // Creation of "blocks" folder and index.html file
            $this->makeDirAndCopyFile('blocks', $indexFile, 'index.html');
        }
        $language  = ('english' !== $GLOBALS['xoopsConfig']['language']) ? $GLOBALS['xoopsConfig']['language'] : 'english';
        $copyFiles = [
            'class'                           => $indexFile,
            'include'                         => $indexFile,
            'config'                          => $indexFile,
            'language'                        => $indexFile,
            'assets'                          => $indexFile,
            'assets/css'                      => $indexFile,
            'assets/css/admin'                => $indexFile,
            'assets/icons'                    => $indexFile,
            'assets/icons/16'                 => $indexFile,
            'assets/icons/32'                 => $indexFile,
            'docs'                            => $indexFile,
            'assets/images'                   => $indexFile,
            'assets/js'                       => $indexFile,
            'language/' . $language           => $indexFile,
            'language/' . $language . '/help' => $indexFile,
            'preloads'                        => $indexFile,
        ];
        foreach ($copyFiles as $k => $v) {
            // Creation of folders and index.html file
            $this->makeDirAndCopyFile($k, $v, 'index.html');
        }
        //Copy the logo of the module
        $modImage = str_replace(' ', '', $module->getVar('mod_image'));
        $targetImage = 'logoModule.png';
        $this->copyFile('assets/images', TDMC_UPLOAD_IMGMOD_PATH . '/' . $modImage, $targetImage);

        //Copy blank files
        $targetImage = 'blank.gif';
        $this->copyFile('assets/images', TDMC_IMAGE_PATH . '/' . $targetImage, $targetImage);
        $targetImage = 'blank.png';
        $this->copyFile('assets/images', TDMC_IMAGE_PATH . '/' . $targetImage , $targetImage);

        // Copy of 'module_author_logo.png' file in uploads dir
        $logoPng     = $stlModuleAuthor . '_logo.png';
        $logoGifFrom = TDMC_UPLOAD_IMGMOD_PATH . '/' . $logoPng;
        // If file exists
        if (!file_exists($logoGifFrom)) {
            // Rename file
            $copyFile    = TDMC_IMAGES_LOGOS_URL . '/xoopsdevelopmentteam_logo.gif';
            $copyNewFile = $logoGifFrom;
            copy($copyFile, $copyNewFile);
        } else {
            // Copy file
            $copyFile    = TDMC_IMAGES_LOGOS_URL . '/' . $logoPng;
            $copyNewFile = $logoGifFrom;
            copy($copyFile, $copyNewFile);
        }

        // Creation of 'module_author_logo.gif' file
        $this->copyFile('assets/images', $copyNewFile, $logoPng);
        $docs = [
            '/credits.txt' => 'credits.txt',
            '/install.txt' => 'install.txt',
            '/lang.diff'   => 'lang.diff',
            '/license.txt' => 'license.txt',
            '/readme.txt'  => 'readme.txt',
        ];
        foreach ($docs as $k => $v) {
            // Creation of folder docs and .txt files
            $this->makeDirAndCopyFile('docs', TDMC_DOCS_PATH . $k, $v);
        }
        if (!empty($tableName)) {
            if (1 == $module->getVar('mod_admin') || 1 == $module->getVar('mod_user')) {
                // Creation of "templates" folder and index.html file
                $this->makeDirAndCopyFile('templates', $indexFile, 'index.html');
            }
            if (1 == $module->getVar('mod_admin')) {
                // Creation of "templates/admin" folder and index.html file
                $this->makeDirAndCopyFile('templates/admin', $indexFile, 'index.html');
            }
            if ((1 == $module->getVar('mod_blocks')) && (1 == $table->getVar('table_blocks'))) {
                // Creation of "templates/blocks" folder and index.html file
                $this->makeDirAndCopyFile('templates/blocks', $indexFile, 'index.html');
            }
            // Creation of "sql" folder and index.html file
            $this->makeDirAndCopyFile('sql', $indexFile, 'index.html');
            if ((1 == $module->getVar('mod_notifications')) && (1 == $table->getVar('table_notifications'))) {
                // Creation of "language/local_language/mail_template" folder and index.html file
                $this->makeDirAndCopyFile('language/' . $language . '/mail_template', $indexFile, 'index.html');
            }
        }
    }

    /**
     * @public function setFilesToBuilding
     *
     * @param string $module
     *
     * @return array
     */
    public function setFilesToBuilding($module)
    {
        // Module
        $modId         = $module->getVar('mod_id');
        $moduleDirname = $module->getVar('mod_dirname');
        $icon32        = 'assets/icons/32';
        $tables        = $this->cf->getTableTables($modId);
        $files         = $this->cf->getTableMorefiles($modId);
        $ret           = [];
        $templateType  = 'defstyle';

        $table              = null;
        $tableCategory      = [];
        //$tableName          = [];
        $tableAdmin         = [];
        $tableUser          = [];
        $tableBlocks        = [];
        $tableSearch        = [];
        $tableComments      = [];
        $tableNotifications = [];
        $permTables         = [];
        $tablePermissions   = [];
        $tableBroken        = [];
        $tablePdf           = [];
        $tablePrint         = [];
        $tableRate          = [];
        $tableRss           = [];
        $tableSingle        = [];
        $tableSubmit        = [];
        $tableVisit         = [];
        $tableTag           = [];
        foreach (array_keys($tables) as $t) {
            $tableId              = $tables[$t]->getVar('table_id');
            $tableName            = $tables[$t]->getVar('table_name');
            $tableSoleName        = $tables[$t]->getVar('table_solename');
            $tableCategory[]      = $tables[$t]->getVar('table_category');
            $tableImage           = $tables[$t]->getVar('table_image');
            $tableAdmin[]         = $tables[$t]->getVar('table_admin');
            $tableUser[]          = $tables[$t]->getVar('table_user');
            $tableBlocks[]        = $tables[$t]->getVar('table_blocks');
            $tableSearch[]        = $tables[$t]->getVar('table_search');
            $tableComments[]      = $tables[$t]->getVar('table_comments');
            $tableNotifications[] = $tables[$t]->getVar('table_notifications');
            $tablePermissions[]   = $tables[$t]->getVar('table_permissions');
            $permTables[]         = $tables[$t]->getVar('table_name');
            $tableBroken[]        = $tables[$t]->getVar('table_broken');
            $tablePdf[]           = $tables[$t]->getVar('table_pdf');
            $tablePrint[]         = $tables[$t]->getVar('table_print');
            $tableRate[]          = $tables[$t]->getVar('table_rate');
            $tableRss[]           = $tables[$t]->getVar('table_rss');
            $tableSingle[]        = $tables[$t]->getVar('table_single');
            $tableSubmit[]        = $tables[$t]->getVar('table_submit');
            $tableVisit[]         = $tables[$t]->getVar('table_visit');
            $tableTag[]           = $tables[$t]->getVar('table_tag');

            // Get Table Object
            $table = $this->helper->getHandler('Tables')->get($tableId);
            // Copy of tables images file
            if (file_exists($uploadTableImage = TDMC_UPLOAD_IMGTAB_PATH . '/' . $tableImage)) {
                $this->copyFile($icon32, $uploadTableImage, $tableImage);
            } elseif (file_exists($uploadTableImage = XOOPS_ICONS32_PATH . '/' . $tableImage)) {
                $this->copyFile($icon32, $uploadTableImage, $tableImage);
            }
            // Creation of admin files
            if (1 === (int)$tables[$t]->getVar('table_admin')) {
                // Admin Pages File
                $adminPages = Modulebuilder\Files\Admin\AdminPages::getInstance();
                $adminPages->write($module, $table, $tableName . '.php');
                $ret[] = $adminPages->render();
                // Admin Templates File
                $adminTemplatesPages = Modulebuilder\Files\Templates\Admin\TemplatesAdminPages::getInstance();
                $adminTemplatesPages->write($module, $table, $moduleDirname . '_admin_' . $tableName . '.tpl');
                $ret[] = $adminTemplatesPages->render();
            }
            // Creation of blocks
            if (1 === (int)$tables[$t]->getVar('table_blocks')) {
                // Blocks Files
                $blocksFiles = Modulebuilder\Files\Blocks\BlocksFiles::getInstance();
                $blocksFiles->write($module, $table, $tableName . '.php');
                $ret[] = $blocksFiles->render();
                // Templates Blocks Files
                if ($templateType  == 'bootstrap') {
                    $templatesBlocks = Modulebuilder\Files\Templates\Blocks\Bootstrap\TemplatesBlocks::getInstance();
                } else {
                    $templatesBlocks = Modulebuilder\Files\Templates\Blocks\Defstyle\TemplatesBlocks::getInstance();
                }
                $templatesBlocks->write($module, $table, $moduleDirname . '_block_' . $tableName . '.tpl');
                $ret[] = $templatesBlocks->render();
            }
            // Creation of classes
            if (1 === (int)$tables[$t]->getVar('table_admin') || 1 === (int)$tables[$t]->getVar('table_user')) {
                // Class Files
                $classFiles = Modulebuilder\Files\Classes\ClassFiles::getInstance();
                $classFiles->write($module, $table, $tables, ucfirst($tableName) . '.php');
                $ret[] = $classFiles->render();
            }
            // Creation of classhandlers
            if (1 === (int)$tables[$t]->getVar('table_admin') || 1 === (int)$tables[$t]->getVar('table_user')) {
                // Class Files
                $classFiles = Modulebuilder\Files\Classes\ClassHandlerFiles::getInstance();
                $classFiles->write($module, $table, $tables, ucfirst($tableName) . 'Handler.php');
                $ret[] = $classFiles->render();
            }
            // Creation of user files
            if (1 === (int)$tables[$t]->getVar('table_user')) {
                // User Pages File
                $userPages = Modulebuilder\Files\User\UserPages::getInstance();
                $userPages->write($module, $table, $tableName . '.php');
                $ret[] = $userPages->render();
                // User Templates File
                if ($templateType  == 'bootstrap') {
                    $userTemplatesPages = Modulebuilder\Files\Templates\User\Bootstrap\Pages::getInstance();
                } else {
                    $userTemplatesPages = Modulebuilder\Files\Templates\User\Defstyle\Pages::getInstance();
                }
                $userTemplatesPages->write($module, $table, $moduleDirname . '_' . $tableName . '.tpl');
                $ret[] = $userTemplatesPages->render();
                // User List Templates File
                if ($templateType  == 'bootstrap') {
                    $userTemplatesPagesList = Modulebuilder\Files\Templates\User\Bootstrap\PagesList::getInstance();
                } else {
                    $userTemplatesPagesList = Modulebuilder\Files\Templates\User\Defstyle\PagesList::getInstance();
                }
                $userTemplatesPagesList->write($module, $table, $tables, $moduleDirname . '_' . $tableName . '_list' . '.tpl');
                $ret[] = $userTemplatesPagesList->render();
                // User Item Templates File
                if ($templateType  == 'bootstrap') {
                    $userTemplatesPagesItem = Modulebuilder\Files\Templates\User\Bootstrap\PagesItem::getInstance();
                } else {
                    $userTemplatesPagesItem = Modulebuilder\Files\Templates\User\Defstyle\PagesItem::getInstance();
                }
                $userTemplatesPagesItem->write($module, $table, $tables, $moduleDirname . '_' . $tableName . '_item' . '.tpl');
                $ret[] = $userTemplatesPagesItem->render();
                if (1 === (int)$tables[$t]->getVar('table_category')) {
                    // User List Templates File
                    $userTemplatesCategories = Templates\User\Defstyle\Categories::getInstance();
                    $userTemplatesCategories->write($module, $table, $moduleDirname . '_' . $tableName . '_cat.tpl');
                    $ret[] = $userTemplatesCategories->render();
                    // User List Templates File
                    $userTemplatesCategoriesList = Templates\User\Defstyle\CategoriesList::getInstance();
                    $userTemplatesCategoriesList->write($module, $table, $moduleDirname . '_' . $tableName . '_cat_list' . '.tpl');
                    $ret[] = $userTemplatesCategoriesList->render();
                }
                // Creation of notifications files
                if (1 === (int)$tables[$t]->getVar('table_notifications')) {
                    $languageMailTpl = Modulebuilder\Files\Language\LanguageMailTpl::getInstance();
                    // Language Mail Template Modify File
                    $languageMailTpl->write($module, $table,  $tableSoleName . '_modify_notify.tpl');
                    $ret[] = $languageMailTpl->render();
                    // Language Mail Template Delete File
                    $languageMailTpl->write($module, $table,  $tableSoleName . '_delete_notify.tpl');
                    $ret[] = $languageMailTpl->render();
                    // Language Mail Template Approve File
                    $languageMailTpl->write($module, $table,  $tableSoleName . '_approve_notify.tpl');
                    $ret[] = $languageMailTpl->render();
                    if (1 === (int)$tables[$t]->getVar('table_broken')) {
                        // Language Mail Template Category File
                        $languageMailTpl = Modulebuilder\Files\Language\LanguageMailTpl::getInstance();
                        $languageMailTpl->write($module, $table,  $tableSoleName . '_broken_notify.tpl');
                        $ret[] = $languageMailTpl->render();
                    }
                    // Creation of notifications files
                    if (1 === (int)$tables[$t]->getVar('table_comments')) {
                        // Language Mail Template Category File
                        $languageMailTpl = Modulebuilder\Files\Language\LanguageMailTpl::getInstance();
                        $languageMailTpl->write($module, $table,  $tableSoleName . '_comment_notify.tpl');
                        $ret[] = $languageMailTpl->render();
                    }
                }
            }
        }

        // Creation of constants
        $classSpecialFiles = Modulebuilder\Files\Classes\ClassSpecialFiles::getInstance();
        $classSpecialFiles->write($module, '', $tables, ucfirst('constants') . '.php');
        $classSpecialFiles->className = 'Constants';
        $ret[] = $classSpecialFiles->renderConstantsInterface();

        // Creation of permissions
        if (in_array(1, $tablePermissions)) {
            // Creation of classes
            $classSpecialFiles = Modulebuilder\Files\Classes\ClassSpecialFiles::getInstance();
            $classSpecialFiles->write($module, '', null, ucfirst('permissions') . '.php');
            $classSpecialFiles->className = 'Permissions';
            $ret[] = $classSpecialFiles->renderClass();

            // Creation of classhandlers
            $classSpecialFiles = Modulebuilder\Files\Classes\ClassSpecialFiles::getInstance();
            $classSpecialFiles->write($module, '', $permTables, ucfirst('PermissionsHandler') . '.php');
            $classSpecialFiles->className = 'PermissionsHandler';
            $ret[] = $classSpecialFiles->renderPermissionsHandler();

        }
        foreach (array_keys($files) as $t) {
            $fileName      = $files[$t]->getVar('file_name');
            $fileExtension = $files[$t]->getVar('file_extension');
            $fileInfolder  = $files[$t]->getVar('file_infolder');
            // More File
            $moreFiles = Modulebuilder\Morefiles::getInstance();
            $moreFiles->write($module, $fileName, $fileInfolder, $fileExtension);
            $ret[] = $moreFiles->render();
        }
        // Language Modinfo File
        $languageModinfo = Modulebuilder\Files\Language\LanguageModinfo::getInstance();
        $languageModinfo->write($module, $table, 'modinfo.php');
        $ret[] = $languageModinfo->render();
        if (1 == $module->getVar('mod_admin')) {
            // Admin Header File
            $adminHeader = Modulebuilder\Files\Admin\AdminHeader::getInstance();
            $adminHeader->write($module, $table, $tables, 'header.php');
            $ret[] = $adminHeader->render();
            // Admin Index File
            $adminIndex = Modulebuilder\Files\Admin\AdminIndex::getInstance();
            $adminIndex->write($module, $tables, 'index.php');
            $ret[] = $adminIndex->render();
            // Admin Menu File
            $adminObject = Modulebuilder\Files\Admin\AdminMenu::getInstance();
            $adminObject->write($module, 'menu.php');
            $ret[] = $adminObject->render();
            // Admin About File
            $adminAbout = Modulebuilder\Files\Admin\AdminAbout::getInstance();
            $adminAbout->write($module, 'about.php');
            $ret[] = $adminAbout->render();
            // Admin Footer File
            $adminFooter = Modulebuilder\Files\Admin\AdminFooter::getInstance();
            $adminFooter->write($module, 'footer.php');
            $ret[] = $adminFooter->render();
            // Templates Admin About File
            $adminTemplatesAbout = Modulebuilder\Files\Templates\Admin\TemplatesAdminAbout::getInstance();
            $adminTemplatesAbout->write($module, $moduleDirname . '_admin_about.tpl');
            $ret[] = $adminTemplatesAbout->render();
            // Templates Admin Index File
            $adminTemplatesIndex = Modulebuilder\Files\Templates\Admin\TemplatesAdminIndex::getInstance();
            $adminTemplatesIndex->write($module, $moduleDirname . '_admin_index.tpl');
            $ret[] = $adminTemplatesIndex->render();
            // Templates Admin Footer File
            $adminTemplatesFooter = Modulebuilder\Files\Templates\Admin\TemplatesAdminFooter::getInstance();
            $adminTemplatesFooter->write($module, $moduleDirname . '_admin_footer.tpl');
            $ret[] = $adminTemplatesFooter->render();
            // Templates Admin Header File
            $adminTemplatesHeader = Modulebuilder\Files\Templates\Admin\TemplatesAdminHeader::getInstance();
            $adminTemplatesHeader->write($module, $moduleDirname . '_admin_header.tpl');
            $ret[] = $adminTemplatesHeader->render();
            // Language Admin File
            $languageAdmin = Modulebuilder\Files\Language\LanguageAdmin::getInstance();
            $languageAdmin->write($module, $table, $tables, 'admin.php');
            $ret[] = $languageAdmin->render();
        }

        // Class Helper File ==> setCommonFiles

        // Include Functions File
        $includeFunctions = Modulebuilder\Files\Includes\IncludeFunctions::getInstance();
        $includeFunctions->write($module, 'functions.php');
        $ret[] = $includeFunctions->render();

        // Include Install File  ==>  setCommonFiles
        // Include Uninstall File  ==>  setCommonFiles
        // Include Update File  ==>  setCommonFiles

        if (in_array(1, $tableBlocks)) {
            // Language Blocks File
            $languageBlocks = Modulebuilder\Files\Language\LanguageBlocks::getInstance();
            $languageBlocks->write($module, $tables, 'blocks.php');
            $ret[] = $languageBlocks->render();
        }
        // Creation of admin broken files
        if (in_array(1, $tableBroken)) {
            // Admin broken File
            $adminPermissions = Modulebuilder\Files\Admin\AdminBroken::getInstance();
            $adminPermissions->write($module, $tables, 'broken.php');
            $ret[] = $adminPermissions->render();
            // Templates Admin broken File
            $adminTemplatesPermissions = Modulebuilder\Files\Templates\Admin\TemplatesAdminBroken::getInstance();
            $adminTemplatesPermissions->write($module, $tables, $moduleDirname . '_admin_broken.tpl');
            $ret[] = $adminTemplatesPermissions->render();
        }
        // Creation of admin permission files
        if (in_array(1, $tablePermissions)) {
            // Admin Permissions File
            $adminPermissions = Modulebuilder\Files\Admin\AdminPermissions::getInstance();
            $adminPermissions->write($module, $tables, 'permissions.php');
            $ret[] = $adminPermissions->render();
            // Templates Admin Permissions File
            $adminTemplatesPermissions = Modulebuilder\Files\Templates\Admin\TemplatesAdminPermissions::getInstance();
            $adminTemplatesPermissions->write($module, $moduleDirname . '_admin_permissions.tpl');
            $ret[] = $adminTemplatesPermissions->render();
        }
        // Creation of notifications files
        if (in_array(1, $tableNotifications)) {
            // Include Notifications File
            $includeNotifications = Modulebuilder\Files\Includes\IncludeNotifications::getInstance();
            $includeNotifications->write($module, $tables, 'notification.inc.php');
            $ret[] = $includeNotifications->render();
            $languageMailTpl = Modulebuilder\Files\Language\LanguageMailTpl::getInstance();
            // Language Mail Template Category File
            //$languageMailTpl->write($module, $table, 'category_new_notify.tpl');
            //$ret[] = $languageMailTpl->render();
            // Language Mail Template New File
            $languageMailTpl->write($module, $table, 'global_new_notify.tpl');
            $ret[] = $languageMailTpl->render();
            // Language Mail Template Modify File
            $languageMailTpl->write($module, $table, 'global_modify_notify.tpl');
            $ret[] = $languageMailTpl->render();
            // Language Mail Template Delete File
            $languageMailTpl->write($module, $table, 'global_delete_notify.tpl');
            $ret[] = $languageMailTpl->render();
            // Language Mail Template Approve File
            $languageMailTpl->write($module, $table, 'global_approve_notify.tpl');
            $ret[] = $languageMailTpl->render();
            if (in_array(1, $tableBroken)) {
                // Language Mail Template Broken File
                $languageMailTpl->write($module, $table, 'global_broken_notify.tpl');
                $ret[] = $languageMailTpl->render();
            }
            if (in_array(1, $tableComments)) {
                // Language Mail Template Broken File
                $languageMailTpl->write($module, $table, 'global_comment_notify.tpl');
                $ret[] = $languageMailTpl->render();
            }
        }
        // Creation of sql file
        if (null != $table->getVar('table_name')) {
            // Sql File
            $sqlFile = Modulebuilder\Files\Sql\SqlFile::getInstance();
            $sqlFile->write($module, 'mysql.sql');
            $ret[] = $sqlFile->render();
        }
        // Creation of search file
        if (in_array(1, $tableSearch)) {
            // Search File
            //TODO: UserSearch has to be adapted
            /*
            $userSearch = Modulebuilder\Files\User\UserSearch::getInstance();
            $userSearch->write($module, $table, 'search.php');
            $ret[] = $userSearch->render();
            */
            // Include Search File
            $includeSearch = Modulebuilder\Files\Includes\IncludeSearch::getInstance();
            $includeSearch->write($module, $tables, 'search.inc.php');
            $ret[] = $includeSearch->render();
        }
        // Creation of comments files
        if (in_array(1, $tableComments)) {
            // Include Comments File
            $includeComments = Modulebuilder\Files\Includes\IncludeComments::getInstance();
            $includeComments->write($module, $table);
            $ret[] = $includeComments->renderCommentsIncludes($module, 'comment_edit');
            // Include Comments File
            $includeComments = Modulebuilder\Files\Includes\IncludeComments::getInstance();
            $includeComments->write($module, $table);
            $ret[] = $includeComments->renderCommentsIncludes($module, 'comment_delete');
            // Include Comments File
            $includeComments = Modulebuilder\Files\Includes\IncludeComments::getInstance();
            $includeComments->write($module, $table);
            $ret[] = $includeComments->renderCommentsIncludes($module, 'comment_post');
            // Include Comments File
            $includeComments = Modulebuilder\Files\Includes\IncludeComments::getInstance();
            $includeComments->write($module, $table);
            $ret[] = $includeComments->renderCommentsIncludes($module, 'comment_reply');
            // Include Comments File
            //$includeComments = Modulebuilder\Files\Includes\IncludeComments::getInstance();
            //$includeComments->write($module, $table);
            //$ret[] = $includeComments->renderCommentsNew($module, 'comment_new');
            // Include Comment Functions File
            $includeCommentFunctions = Modulebuilder\Files\Includes\IncludeCommentFunctions::getInstance();
            $includeCommentFunctions->write($module, $table, 'comment_functions.php');
            $ret[] = $includeCommentFunctions->render();
        }

        if ((1 == $module->getVar('mod_user')) && in_array(1, $tableUser)) {
            // Creation of user template files
            // Templates Index File
            if ($templateType  == 'bootstrap') {
                $userTemplatesIndex = Modulebuilder\Files\Templates\User\Bootstrap\Index::getInstance();
            } else {
                $userTemplatesIndex = Modulebuilder\Files\Templates\User\Defstyle\Index::getInstance();
            }
            $userTemplatesIndex->write($module, $table, $tables, $moduleDirname . '_index.tpl');
            $ret[] = $userTemplatesIndex->render();
            // Templates Footer File
            if ($templateType  == 'bootstrap') {
                $userTemplatesFooter = Modulebuilder\Files\Templates\User\Bootstrap\Footer::getInstance();
            } else {
                $userTemplatesFooter = Modulebuilder\Files\Templates\User\Defstyle\Footer::getInstance();
            }
            $userTemplatesFooter->write($module, $table, $moduleDirname . '_footer.tpl');
            $ret[] = $userTemplatesFooter->render();
            // Templates Header File
            if ($templateType  == 'bootstrap') {
                $userTemplatesHeader = Modulebuilder\Files\Templates\User\Bootstrap\Header::getInstance();
            } else {
                $userTemplatesHeader = Modulebuilder\Files\Templates\User\Defstyle\Header::getInstance();
            }
            $userTemplatesHeader->write($module, $moduleDirname . '_header.tpl');
            $ret[] = $userTemplatesHeader->render();

            // Creation of user files
            // User Footer File
            $userFooter = Modulebuilder\Files\User\UserFooter::getInstance();
            $userFooter->write($module, 'footer.php');
            $ret[] = $userFooter->render();
            // User Header File
            $userHeader = Modulebuilder\Files\User\UserHeader::getInstance();
            $userHeader->write($module, $table, $tables, 'header.php');
            $ret[] = $userHeader->render();
            // User Notification Update File
            if ((1 == $module->getVar('mod_notifications')) && in_array(1, $tableNotifications)) {
                $userNotificationUpdate = Modulebuilder\Files\User\UserNotificationUpdate::getInstance();
                $userNotificationUpdate->write($module, 'notification_update.php');
                $ret[] = $userNotificationUpdate->render();
            }
            // User Pdf File
            if (in_array(1, $tablePdf)) {
                $userPdf = Modulebuilder\Files\User\UserPdf::getInstance();
                $userPdf->write($module, $table, 'pdf.php');
                $ret[] = $userPdf->render();
                // User Templates Pdf File
                if ($templateType  == 'bootstrap') {
                    $userTemplatesPdf = Modulebuilder\Files\Templates\User\Bootstrap\Pdf::getInstance();
                } else {
                    $userTemplatesPdf = Modulebuilder\Files\Templates\User\Defstyle\Pdf::getInstance();
                }
                $userTemplatesPdf->write($module, $moduleDirname . '_pdf.tpl');
                $ret[] = $userTemplatesPdf->render();
            }
            // User Print File
            if (in_array(1, $tablePrint)) {
                $userPrint = Modulebuilder\Files\User\UserPrint::getInstance();
                $userPrint->write($module, $table, 'print.php');
                $ret[] = $userPrint->render();
                // User Templates Print File
                if ($templateType  == 'bootstrap') {
                    $userTemplatesPrint = Modulebuilder\Files\Templates\User\Bootstrap\UserPrint::getInstance();
                } else {
                    $userTemplatesPrint = Modulebuilder\Files\Templates\User\Defstyle\UserPrint::getInstance();
                }
                $userTemplatesPrint->write($module, $table, $moduleDirname . '_print.tpl');
                $ret[] = $userTemplatesPrint->render();
            }
            // User Rate File
            //TODO: UserSearch has to be adapted
            if (in_array(1, $tableRate)) {
                $userRate = Modulebuilder\Files\User\UserRate::getInstance();
                $userRate->write($module, $table, 'rate.php');
                $ret[] = $userRate->render();
                // User Templates Rate File
                if ($templateType  == 'bootstrap') {
                    $userTemplatesRate = Modulebuilder\Files\Templates\User\Bootstrap\Rate::getInstance();
                } else {
                    $userTemplatesRate = Modulebuilder\Files\Templates\User\Defstyle\Rate::getInstance();
                }
                $userTemplatesRate->write($module, $table, $moduleDirname . '_rate.tpl');
                $ret[] = $userTemplatesRate->render();
            }

            // User Rss File
            if (in_array(1, $tableRss)) {
                $userRss = Modulebuilder\Files\User\UserRss::getInstance();
                $userRss->write($module, $table, 'rss.php');
                $ret[] = $userRss->render();
                // User Templates Rss File
                if ($templateType  == 'bootstrap') {
                    $userTemplatesRss = Modulebuilder\Files\Templates\User\Bootstrap\Rss::getInstance();
                } else {
                    $userTemplatesRss = Modulebuilder\Files\Templates\User\Defstyle\Rss::getInstance();
                }
                $userTemplatesRss->write($module, $moduleDirname . '_rss.tpl');
                $ret[] = $userTemplatesRss->render();
            }

            // User Tag Files
            if (in_array(1, $tableTag)) {
                $userListTag = Modulebuilder\Files\User\UserListTag::getInstance();
                $userListTag->write($module, 'list.tag.php');
                $ret[]       = $userListTag->render();
                $userViewTag = Modulebuilder\Files\User\UserViewTag::getInstance();
                $userViewTag->write($module, 'view.tag.php');
                $ret[] = $userViewTag->render();
            }
            // User Index File
            $userIndex = Modulebuilder\Files\User\UserIndex::getInstance();
            $userIndex->write($module, $table, 'index.php');
            $ret[] = $userIndex->render();
            // Language Main File
            $languageMain = Modulebuilder\Files\Language\LanguageMain::getInstance();
            $languageMain->write($module, $tables, 'main.php');
            $ret[] = $languageMain->render();
            // User Templates Breadcrumbs File
            if ($templateType  == 'bootstrap') {
                $userTemplatesUserBreadcrumbs = Modulebuilder\Files\Templates\User\Bootstrap\Breadcrumbs::getInstance();
            } else {
                $userTemplatesUserBreadcrumbs = Modulebuilder\Files\Templates\User\Defstyle\Breadcrumbs::getInstance();
            }
            $userTemplatesUserBreadcrumbs->write($module, $moduleDirname . '_breadcrumbs.tpl');
            $ret[] = $userTemplatesUserBreadcrumbs->render();
        }
        // Css Admin Styles File
        $cssStyles = Modulebuilder\Files\Assets\Css\Admin\CssAdminStyles::getInstance();
        $cssStyles->write($module, 'style.css');
        $ret[] = $cssStyles->render();
        // Css Styles File
        $cssStyles = Modulebuilder\Files\Assets\Css\CssStyles::getInstance();
        $cssStyles->write($module, 'style.css');
        $ret[] = $cssStyles->render();
        // Include Jquery File
        $JavascriptJQuery = Modulebuilder\Files\Assets\Js\JavascriptJQuery::getInstance();
        $JavascriptJQuery->write($module, 'functions.js');
        $ret[] = $JavascriptJQuery->render();
        // Include Common File
        $includeCommon = Modulebuilder\Files\Includes\IncludeCommon::getInstance();
        $includeCommon->write($module, $table, 'common.php');
        $ret[] = $includeCommon->render();
        // Common Config File
        $includeConfig = Modulebuilder\Files\Config\ConfigConfig::getInstance();
        $includeConfig->write($module, $tables, 'config.php');
        $ret[] = $includeConfig->render();
        // Docs Changelog File
        $docsChangelog = Modulebuilder\Files\Docs\DocsChangelog::getInstance();
        $docsChangelog->write($module, 'changelog.txt');
        $ret[] = $docsChangelog->render();
        // Language Help File
        $languageHelp = Modulebuilder\Files\Language\LanguageHelp::getInstance();
        $languageHelp->write($module, 'help.html');
        $ret[] = $languageHelp->render();
        // User Xoops Version File
        $userXoopsVersion = Modulebuilder\Files\User\UserXoopsVersion::getInstance();
        $userXoopsVersion->write($module, $table, $tables, 'xoops_version.php');
        $ret[] = $userXoopsVersion->render();

        // Return Array
        return $ret;
    }

    /**
     * @public function setCommonFiles
     *
     * @param $module
     */
    public function setCommonFiles($module)
    {

        $moduleName = $module->getVar('mod_dirname');
        $upl_path   = TDMC_UPLOAD_REPOSITORY_PATH . '/' . mb_strtolower($moduleName);

        $patterns = [
            mb_strtolower('modulebuilder')          => mb_strtolower($moduleName),
            mb_strtoupper('modulebuilder')          => mb_strtoupper($moduleName),
            ucfirst(mb_strtolower('modulebuilder')) => ucfirst(mb_strtolower($moduleName)),
        ];

        $patKeys   = array_keys($patterns);
        $patValues = array_values($patterns);

        /* clone complete missing folders */
        $cloneFolders[] = [
            'src'   => TDMC_PATH . '/commonfiles',
            'dst'   => $upl_path,
            'rcode' => true
        ];
        foreach ($cloneFolders as $folder) {
            Modulebuilder\Files\CreateClone::cloneFileFolder($folder['src'], $folder['dst'], $folder['rcode'], $patKeys, $patValues);
        }
        unset($cloneFolders);

        /* create missing folders for common files */
        $createFolders[] = $upl_path . '/testdata/' . $GLOBALS['xoopsConfig']['language'];
        if ('english' !== $GLOBALS['xoopsConfig']['language']) {
            $createFolders[] = $upl_path . '/testdata/english';
            $createFolders[] = $upl_path . '/language/english';
        }
        foreach ($createFolders as $folder) {
            @mkdir($folder);
        }
        unset($createFolders);

        if ('english' !== $GLOBALS['xoopsConfig']['language']) {
            $cloneFolders[] = [
                'src'   => TDMC_PATH . '/commonfiles/language/english',
                'dst'   => $upl_path . '/language/' . $GLOBALS['xoopsConfig']['language'],
                'rcode' => true
            ];
            //copy back all language files to english language folder
            $cloneFolders[] = [
                'src'   => $upl_path . '/language/' . $GLOBALS['xoopsConfig']['language'],
                'dst'   => $upl_path . '/language/english',
                'rcode' => false
            ];
            foreach ($cloneFolders as $folder) {
                Modulebuilder\Files\CreateClone::cloneFileFolder($folder['src'], $folder['dst'], $folder['rcode'], $patKeys, $patValues);
            }
            unset($cloneFolders);
        }

        /* clone single missing files*/
        $cloneFiles[] = [
            'src'   => TDMC_PATH . '/config/',
            'dst'   => $upl_path . '/config/',
            'file'  => 'admin.yml',
            'rcode' => true
        ];
        $cloneFiles[] = [
            'src'   => TDMC_PATH . '/config/',
            'dst'   => $upl_path . '/config/',
            'file'  => 'icons.php',
            'rcode' => true
        ];
        $cloneFiles[] = [
            'src'   => TDMC_PATH . '/config/',
            'dst'   => $upl_path . '/config/',
            'file'  => 'paths.php',
            'rcode' => true
        ];
        foreach ($cloneFiles as $file) {
            Modulebuilder\Files\CreateClone::cloneFile($file['src'] . $file['file'], $file['dst'] . $file['file'], $file['rcode'], $patKeys, $patValues);
        }
        unset($cloneFiles);
    }
}
