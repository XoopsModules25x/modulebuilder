<?php declare(strict_types=1);

namespace XoopsModules\Modulebuilder\Files\User;

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
 * @license         GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops https://xoops.org 
 *                  Goffy https://myxoops.org
 */

/**
 * Class UserXoopsVersion.
 */
class UserXoopsVersion extends Files\CreateFile
{
    /**
     * @var array
     */
    private $kw = [];
    /**
     * @var mixed
     */
    private $uxc = null;
    /**
     * @var mixed
     */
    private $xc = null;
    /**
     * @var mixed
     */
    private $pc = null;

    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
        $this->xc  = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $this->pc  = Modulebuilder\Files\CreatePhpCode::getInstance();
        $this->uxc = Modulebuilder\Files\User\UserXoopsCode::getInstance();
    }

    /**
     * @static function getInstance
     * @param null
     * @return UserXoopsVersion
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
     * @param       $module
     * @param mixed $table
     * @param mixed $tables
     * @param       $filename
     */
    public function write($module, $table, $tables, $filename): void
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setTables($tables);
        $this->setFileName($filename);
        foreach (\array_keys($tables) as $t) {
            $tableName = $tables[$t]->getVar('table_name');
            $this->setKeywords($tableName);
        }
    }

    /**
     * @public function setKeywords
     * @param mixed $keywords
     */
    public function setKeywords($keywords): void
    {
        if (\is_array($keywords)) {
            $this->kw = $keywords;
        } else {
            $this->kw[] = $keywords;
        }
    }

    /**
     * @public function getKeywords
     * @param null
     * @return array
     */
    public function getKeywords()
    {
        return $this->kw;
    }

    /**
     * @private function getXoopsVersionHeader
     * @param $module
     * @param $language
     *
     * @return string
     */
    private function getXoopsVersionHeader($module, $language)
    {
        $date = date('Y/m/d');
        $ret  = $this->getSimpleString('');
        $ret  .= Modulebuilder\Files\CreatePhpCode::getInstance()->getPhpCodeCommentLine();
        $ret  .= $this->xc->getXcEqualsOperator('$moduleDirName     ', '\basename(__DIR__)');
        $ret  .= $this->xc->getXcEqualsOperator('$moduleDirNameUpper', '\mb_strtoupper($moduleDirName)');
        $ret  .= $this->getDashComment('Informations');
        $ha   = (1 == $module->getVar('mod_admin')) ? '1' : '0';
        $hm   = (1 == $module->getVar('mod_user')) ? '1' : '0';

        $descriptions = [
            'name'                => "{$language}NAME",
            'version'             => "'" . (string)$module->getVar('mod_version') . "'",
            'description'         => "{$language}DESC",
            'author'              => "'{$module->getVar('mod_author')}'",
            'author_mail'         => "'{$module->getVar('mod_author_mail')}'",
            'author_website_url'  => "'{$module->getVar('mod_author_website_url')}'",
            'author_website_name' => "'{$module->getVar('mod_author_website_name')}'",
            'credits'             => "'{$module->getVar('mod_credits')}'",
            'license'             => "'{$module->getVar('mod_license')}'",
            'license_url'         => "'https://www.gnu.org/licenses/gpl-3.0.en.html'",
            'help'                => "'page=help'",
            'release_info'        => "'{$module->getVar('mod_release_info')}'",
            'release_file'        => "\XOOPS_URL . '/modules/{$module->getVar('mod_dirname')}/docs/{$module->getVar('mod_release_file')}'",
            'release_date'        => "'{$date}'",
            'manual'              => "'{$module->getVar('mod_manual')}'",
            'manual_file'         => "\XOOPS_URL . '/modules/{$module->getVar('mod_dirname')}/docs/{$module->getVar('mod_manual_file')}'",
            'min_php'             => "'{$module->getVar('mod_min_php')}'",
            'min_xoops'           => "'{$module->getVar('mod_min_xoops')}'",
            'min_admin'           => "'{$module->getVar('mod_min_admin')}'",
            'min_db'              => "['mysql' => '{$module->getVar('mod_min_mysql')}', 'mysqli' => '{$module->getVar('mod_min_mysql')}']",
            'image'               => "'assets/images/logoModule.png'",
            'dirname'             => '\basename(__DIR__)',
            'dirmoduleadmin'      => "'Frameworks/moduleclasses/moduleadmin'",
            'sysicons16'          => "'../../Frameworks/moduleclasses/icons/16'",
            'sysicons32'          => "'../../Frameworks/moduleclasses/icons/32'",
            'modicons16'          => "'assets/icons/16'",
            'modicons32'          => "'assets/icons/32'",
            'demo_site_url'       => "'{$module->getVar('mod_demo_site_url')}'",
            'demo_site_name'      => "'{$module->getVar('mod_demo_site_name')}'",
            'support_url'         => "'{$module->getVar('mod_support_url')}'",
            'support_name'        => "'{$module->getVar('mod_support_name')}'",
            'module_website_url'  => "'{$module->getVar('mod_website_url')}'",
            'module_website_name' => "'{$module->getVar('mod_website_name')}'",
            'release'             => "'{$module->getVar('mod_release')}'",
            'module_status'       => "'{$module->getVar('mod_status')}'",
            'system_menu'         => '1',
            'hasAdmin'            => $ha,
            'hasMain'             => $hm,
            'adminindex'          => "'admin/index.php'",
            'adminmenu'           => "'admin/menu.php'",
            'onInstall'           => "'include/install.php'",
            'onUninstall'         => "'include/uninstall.php'",
            'onUpdate'            => "'include/update.php'",
        ];

        $ret .= $this->uxc->getUserModVersionArray(0, $descriptions);

        return $ret;
    }

    /**
     * @private function getXoopsVersionMySQL
     * @param $moduleDirname
     * @param $table
     * @param $tables
     * @return string
     */
    private function getXoopsVersionMySQL($moduleDirname, $table, $tables)
    {
        $tableName = $table->getVar('table_name');
        $n         = 1;
        $ret       = '';
        $items     = [];
        $tableRate = 0;
        if (!empty($tableName)) {
            $ret         .= $this->getDashComment('Mysql');
            $description = "'sql/mysql.sql'";
            $ret         .= $this->uxc->getUserModVersionText(2, $description, 'sqlfile', "'mysql'");
            $ret         .= Modulebuilder\Files\CreatePhpCode::getInstance()->getPhpCodeCommentLine('Tables');

            foreach (\array_keys($tables) as $t) {
                $items[] = "'{$moduleDirname}_{$tables[$t]->getVar('table_name')}'";
                if (1 === (int)$tables[$t]->getVar('table_rate')) {
                    $tableRate = 1;
                }
                ++$n;
            }
            if (1 === $tableRate) {
                $items[] = "'{$moduleDirname}_ratings'";
                ++$n;
            }
            $ret .= $this->uxc->getUserModVersionArray(11, $items, 'tables', $n);
            unset($n);
        }

        return $ret;
    }

    /**
     * @private function getXoopsVersionSearch
     * @param $moduleDirname
     *
     * @return string
     */
    private function getXoopsVersionSearch($moduleDirname)
    {
        $ret   = $this->getDashComment('Search');
        $ret   .= $this->uxc->getUserModVersionText(1, 1, 'hasSearch');
        $items = ['file' => "'include/search.inc.php'", 'func' => "'{$moduleDirname}_search'"];
        $ret   .= $this->uxc->getUserModVersionArray(1, $items, 'search');

        return $ret;
    }

    /**
     * @private function getXoopsVersionComments
     * @param $moduleDirname
     *
     * @param $tables
     * @return string
     */
    private function getXoopsVersionComments($moduleDirname, $tables)
    {
        $tableName = '';
        $fieldId = '';
        foreach (\array_keys($tables) as $t) {
            if (1 == $tables[$t]->getVar('table_comments')) {
                $tableName = $tables[$t]->getVar('table_name');
                $fields = $this->getTableFields($tables[$t]->getVar('table_mid'), $tables[$t]->getVar('table_id'));
                foreach (\array_keys($fields) as $f) {
                    $fieldName = $fields[$f]->getVar('field_name');
                    if (0 == $f) {
                        $fieldId = $fieldName;
                    }
                }
            }
        }
        $ret          = $this->getDashComment('Comments');
        $ret          .= $this->uxc->getUserModVersionText(1, '1', 'hasComments');
        $ret          .= $this->uxc->getUserModVersionText(2, "'{$tableName}.php'", 'comments', "'pageName'");
        $ret          .= $this->uxc->getUserModVersionText(2, "'{$fieldId}'", 'comments', "'itemName'");
        $ret          .= Modulebuilder\Files\CreatePhpCode::getInstance()->getPhpCodeCommentLine('Comment callback functions');
        $ret          .= $this->uxc->getUserModVersionText(2, "'include/comment_functions.php'", 'comments', "'callbackFile'");
        $descriptions = ['approve' => "'{$moduleDirname}CommentsApprove'", 'update' => "'{$moduleDirname}CommentsUpdate'"];
        $ret          .= $this->uxc->getUserModVersionArray(2, $descriptions, 'comments', "'callback'");

        return $ret;
    }

    /**
     * @private function getXoopsVersionTemplatesAdminUser
     * @param $moduleDirname
     * @param $tables
     *
     * @param $admin
     * @param $user
     * @return string
     */
    private function getXoopsVersionTemplatesAdminUser($moduleDirname, $tables, $admin, $user)
    {
        $ret  = $this->getDashComment('Templates');
        $item = [];
        if ($admin) {
            $item[] = $this->pc->getPhpCodeCommentLine('Admin templates');
            $item[] = $this->getXoopsVersionTemplatesLine($moduleDirname, 'about', '', true);
            $item[] = $this->getXoopsVersionTemplatesLine($moduleDirname, 'header', '', true);
            $item[] = $this->getXoopsVersionTemplatesLine($moduleDirname, 'index', '', true);
            $tablePermissions = [];
            $tableBroken      = [];
            foreach (\array_keys($tables) as $t) {
                $tableName          = $tables[$t]->getVar('table_name');
                $tablePermissions[] = $tables[$t]->getVar('table_permissions');
                $tableBroken[]      = $tables[$t]->getVar('table_broken');
                $item[]             .= $this->getXoopsVersionTemplatesLine($moduleDirname, $tableName, '', true);
            }
            if (\in_array(1, $tableBroken)) {
                $item[] = $this->getXoopsVersionTemplatesLine($moduleDirname, 'broken', '', true);
            }
            if (\in_array(1, $tablePermissions)) {
                $item[] = $this->getXoopsVersionTemplatesLine($moduleDirname, 'permissions', '', true);
            }
            $item[] = $this->getXoopsVersionTemplatesLine($moduleDirname, 'clone', '', true);
            $item[] = $this->getXoopsVersionTemplatesLine($moduleDirname, 'footer', '', true);
        }

        if ($user) {
            $item[]      = $this->pc->getPhpCodeCommentLine('User templates');
            $item[]      = $this->getXoopsVersionTemplatesLine($moduleDirname, 'header');
            $item[]      = $this->getXoopsVersionTemplatesLine($moduleDirname, 'index');
            $tableBroken = [];
            $tablePdf    = [];
            $tablePrint  = [];
            $tableRate   = [];
            $tableRss    = [];
            $tableSearch = [];
            $tableSingle = [];
            $tableSubmit = [];
            foreach (\array_keys($tables) as $t) {
                if (1 == $tables[$t]->getVar('table_user')) {
                    $tableName     = $tables[$t]->getVar('table_name');
                    $tableBroken[] = $tables[$t]->getVar('table_broken');
                    $tablePdf[]    = $tables[$t]->getVar('table_pdf');
                    $tablePrint[]  = $tables[$t]->getVar('table_print');
                    $tableRate[]   = $tables[$t]->getVar('table_rate');
                    $tableRss[]    = $tables[$t]->getVar('table_rss');
                    $tableSearch[] = $tables[$t]->getVar('table_search');
                    $tableSingle[] = $tables[$t]->getVar('table_single');
                    $tableSubmit[] = $tables[$t]->getVar('table_submit');
                    $tableRate[]   = $tables[$t]->getVar('table_rate');
                    $item[]        = $this->getXoopsVersionTemplatesLine($moduleDirname, $tableName);
                    $item[]        = $this->getXoopsVersionTemplatesLine($moduleDirname, $tableName, 'list');
                    $item[]        = $this->getXoopsVersionTemplatesLine($moduleDirname, $tableName, 'item');
                }
            }
            $item[]  = $this->getXoopsVersionTemplatesLine($moduleDirname, 'breadcrumbs');
            if (\in_array(1, $tablePdf)) {
                foreach (\array_keys($tables) as $t) {
                    if ($tables[$t]->getVar('table_pdf')) {
                        $tableName = $tables[$t]->getVar('table_name');
                        $item[] = $this->getXoopsVersionTemplatesLine($moduleDirname, $tableName . '_pdf');
                    }
                }
            }
            if (\in_array(1, $tablePrint)) {
                foreach (\array_keys($tables) as $t) {
                    if ($tables[$t]->getVar('table_print')) {
                        $tableName = $tables[$t]->getVar('table_name');
                        $item[] = $this->getXoopsVersionTemplatesLine($moduleDirname, $tableName . '_print');
                    }
                }
            }
            if (\in_array(1, $tableRate)) {
                $item[] = $this->getXoopsVersionTemplatesLine($moduleDirname, 'rate');
            }
            if (\in_array(1, $tableRss)) {
                $item[] = $this->getXoopsVersionTemplatesLine($moduleDirname, 'rss');
            }
            if (\in_array(1, $tableSearch)) {
                $item[] = $this->getXoopsVersionTemplatesLine($moduleDirname, 'search');
            }
            if (\in_array(1, $tableSingle)) {
                $item[] = $this->getXoopsVersionTemplatesLine($moduleDirname, 'single');
            }
            $item[] = $this->getXoopsVersionTemplatesLine($moduleDirname, 'footer');
        }

        $ret .= $this->uxc->getUserModVersionArray(11, $item, 'templates');

        return $ret;
    }

    /**
     * @private function getXoopsVersionTemplatesLine
     * @param        $moduleDirname
     * @param        $type
     * @param string $extra
     * @param bool   $isAdmin
     * @return string
     */
    private function getXoopsVersionTemplatesLine($moduleDirname, $type, $extra = '', $isAdmin = false)
    {
        $ret         = '';
        $desc        = "'description' => ''";
        $arrayFile   = "['file' =>";
        if ($isAdmin) {
            $ret .= "{$arrayFile} '{$moduleDirname}_admin_{$type}.tpl', {$desc}, 'type' => 'admin']";
        } else {
            if ('' !== $extra) {
                $ret .= "{$arrayFile} '{$moduleDirname}_{$type}_{$extra}.tpl', {$desc}]";
            } else {
                $ret .= "{$arrayFile} '{$moduleDirname}_{$type}.tpl', {$desc}]";
            }
        }

        return $ret;
    }

    /**
     * @private function getXoopsVersionSubmenu
     * @param $language
     * @param $tables
     * @return string
     */
    private function getXoopsVersionSubmenu($language, $tables)
    {
        $ret     = $this->getDashComment('Menu');
        $xModule = $this->pc->getPhpCodeGlobals('xoopsModule');
        $cond    = 'isset(' . $xModule . ') && \is_object(' . $xModule . ')';
        $one     =  $this->pc->getPhpCodeGlobals('xoopsModule') . "->getVar('dirname')";
        $ret     .= $this->pc->getPhpCodeTernaryOperator('currdirname ', $cond, $one, "'system'");

        $i          = 1;
        $descriptions = [
            'name' => "{$language}SMNAME{$i}",
            'url'  => "'index.php'",
        ];
        $contentIf  = $this->uxc->getUserModVersionArray(2, $descriptions, 'sub', '','', "\t");
        ++$i;

        $tableSearch = [];
        foreach (\array_keys($tables) as $t) {
            $tableName     = $tables[$t]->getVar('table_name');
            $tableSearch[] = $tables[$t]->getVar('table_search');
            if (1 == $tables[$t]->getVar('table_submenu')) {
                $contentIf .= $this->pc->getPhpCodeCommentLine('Sub', $tableName, "\t");
                $descriptions = [
                    'name' => "{$language}SMNAME{$i}",
                    'url'  => "'{$tableName}.php'",
                ];
                $contentIf  .= $this->uxc->getUserModVersionArray(2, $descriptions, 'sub', '','', "\t");
                unset($item);
            }
            ++$i;
            if (1 == $tables[$t]->getVar('table_submit')) {
                $contentIf .= $this->pc->getPhpCodeCommentLine('Sub', 'Submit', "\t");
                $descriptions = [
                    'name' => "{$language}SMNAME{$i}",
                    'url'  => "'{$tableName}.php?op=new'",
                ];
                $contentIf  .= $this->uxc->getUserModVersionArray(2, $descriptions, 'sub', '','', "\t");
                ++$i;
            }
        }

        //TODO: after finalizing creation of search.php by User/UserSearch.php this sub menu item can be activated
        /*
        if (\in_array(1, $tableSearch)) {
            $contentIf .= $cpc->getPhpCodeCommentLine('Sub', 'Search', "\t");
            $descriptions = [
                'name' => "{$language}SMNAME{$i}",
                'url'  => "'search.php'",
            ];
            $contentIf  .= $this->uxc->getUserModVersionArray(2, $descriptions, 'sub', '','', "\t");
        }
        */
        unset($i);

        $ret .= $this->pc->getPhpCodeConditions('$moduleDirName', ' == ', '$currdirname', $contentIf);

        return $ret;
    }

    /**
     * @private function getXoopsVersionBlocks
     * @param $moduleDirname
     * @param $tables
     * @param $language
     * @return string
     */
    private function getXoopsVersionBlocks($moduleDirname, $tables, $language)
    {
        $ret = $this->getDashComment('Default Blocks');
        foreach (\array_keys($tables) as $i) {
            $tableName        = $tables[$i]->getVar('table_name');
            if (0 == $tables[$i]->getVar('table_category') && 1 == $tables[$i]->getVar('table_blocks')) {
                $ret .= $this->getXoopsVersionTypeBlocks($moduleDirname, $tableName, 'LAST', $language, 'last');
                $ret .= $this->getXoopsVersionTypeBlocks($moduleDirname, $tableName, 'NEW', $language, 'new');
                $ret .= $this->getXoopsVersionTypeBlocks($moduleDirname, $tableName, 'HITS', $language, 'hits');
                $ret .= $this->getXoopsVersionTypeBlocks($moduleDirname, $tableName, 'TOP', $language, 'top');
                $ret .= $this->getXoopsVersionTypeBlocks($moduleDirname, $tableName, 'RANDOM', $language, 'random');
            }
        }
        $ret .= $this->getDashComment('Spotlight Blocks');
        foreach (\array_keys($tables) as $i) {
            $tableName        = $tables[$i]->getVar('table_name');
            if (0 == $tables[$i]->getVar('table_category') && 1 == $tables[$i]->getVar('table_blocks')) {
                $ret .= $this->getXoopsVersionSpotlightBlocks($moduleDirname, $tableName, $language, 'spotlight');
            }
        }

        return $ret;
    }

    /**
     * @private function getXoopsVersionTypeBlocks
     * @param $moduleDirname
     * @param $tableName
     * @param $stuTableSoleName
     * @param $language
     * @param $type
     * @return string
     */
    private function getXoopsVersionTypeBlocks($moduleDirname, $tableName, $stuTableSoleName, $language, $type)
    {
        $stuTableName    = \mb_strtoupper($tableName);
        $ucfTableName    = \ucfirst($tableName);
        $ret             = $this->pc->getPhpCodeCommentLine($ucfTableName . ' ' . $type);
        $blocks          = [
            'file'        => "'{$tableName}.php'",
            'name'        => "{$language}{$stuTableName}_BLOCK_{$stuTableSoleName}",
            'description' => "{$language}{$stuTableName}_BLOCK_{$stuTableSoleName}_DESC",
            'show_func'   => "'b_{$moduleDirname}_{$tableName}_show'",
            'edit_func'   => "'b_{$moduleDirname}_{$tableName}_edit'",
            'template'    => "'{$moduleDirname}_block_{$tableName}.tpl'",
            'options'     => "'{$type}|5|25|0'",
        ];
        $ret             .= $this->uxc->getUserModVersionArray(2, $blocks, 'blocks');

        return $ret;
    }

    /**
     * @private function getXoopsVersionTypeBlocks
     * @param $moduleDirname
     * @param $tableName
     * @param $language
     * @param $type
     * @return string
     */
    private function getXoopsVersionSpotlightBlocks($moduleDirname, $tableName, $language, $type)
    {
        $stuTableName    = \mb_strtoupper($tableName);
        $ucfTableName    = \ucfirst($tableName);
        $ret             = $this->pc->getPhpCodeCommentLine($ucfTableName . ' ' . $type);
        $blocks          = [
            'file'        => "'{$tableName}_spotlight.php'",
            'name'        => "{$language}{$stuTableName}_BLOCK_SPOTLIGHT",
            'description' => "{$language}{$stuTableName}_BLOCK_SPOTLIGHT_DESC",
            'show_func'   => "'b_{$moduleDirname}_{$tableName}_spotlight_show'",
            'edit_func'   => "'b_{$moduleDirname}_{$tableName}_spotlight_edit'",
            'template'    => "'{$moduleDirname}_block_{$tableName}_spotlight.tpl'",
            'options'     => "'{$type}|5|25|0'",
        ];
        $ret             .= $this->uxc->getUserModVersionArray(2, $blocks, 'blocks');

        return $ret;
    }

    /**
     * @private function getXoopsVersionConfig
     * @param $module
     * @param $tables
     * @param $language
     *
     * @return string
     */
    private function getXoopsVersionConfig($module, $tables, $language)
    {
        $moduleDirname  = $module->getVar('mod_dirname');
        $ret            = $this->getDashComment('Config');

        $table_editors     = 0;
        $table_permissions = 0;
        $table_admin       = 0;
        $table_user        = 0;
        $table_tag         = 0;
        $table_uploadimage = 0;
        $table_uploadfile  = 0;
        $table_rate        = 0;
        foreach ($tables as $table) {
            $fields = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
            foreach (\array_keys($fields) as $f) {
                $fieldElement = (int)$fields[$f]->getVar('field_element');
                switch ($fieldElement) {
                    case 3:
                    case 4:
                        $table_editors = 1;
                        break;
                    case 10:
                    case 11:
                    case 12:
                    case 13:
                        $table_uploadimage = 1;
                        break;
                    case 14:
                        $table_uploadfile = 1;
                        break;
                    case 'else':
                    default:
                        break;
                }
            }
            if (1 == $table->getVar('table_permissions')) {
                $table_permissions = 1;
            }
            if (1 == $table->getVar('table_admin')) {
                $table_admin = 1;
            }
            if (1 == $table->getVar('table_user')) {
                $table_user = 1;
            }
            if (1 == $table->getVar('table_tag')) {
                $table_tag = 1;
            }
            if (1 == $table->getVar('table_rate')) {
                $table_rate = 1;
            }
        }
        if (1 === $table_editors) {
            $ret          .= $this->pc->getPhpCodeCommentLine('Editor Admin', '');
            $ret          .= $this->xc->getXcXoopsLoad('xoopseditorhandler');
            $ret          .= $this->xc->getXcEqualsOperator('$editorHandler', 'XoopsEditorHandler::getInstance()');
            $editor       = [
                'name'        => "'editor_admin'",
                'title'       => "'{$language}EDITOR_ADMIN'",
                'description' => "'{$language}EDITOR_ADMIN_DESC'",
                'formtype'    => "'select'",
                'valuetype'   => "'text'",
                'default'     => "'dhtml'",
                'options'     => 'array_flip($editorHandler->getList())',
            ];
            $ret          .= $this->uxc->getUserModVersionArray(2, $editor, 'config');
            $ret          .= $this->pc->getPhpCodeCommentLine('Editor User', '');
            $ret          .= $this->xc->getXcXoopsLoad('xoopseditorhandler');
            $ret          .= $this->xc->getXcEqualsOperator('$editorHandler', 'XoopsEditorHandler::getInstance()');
            $editor       = [
                'name'        => "'editor_user'",
                'title'       => "'{$language}EDITOR_USER'",
                'description' => "'{$language}EDITOR_USER_DESC'",
                'formtype'    => "'select'",
                'valuetype'   => "'text'",
                'default'     => "'dhtml'",
                'options'     => 'array_flip($editorHandler->getList())',
            ];
            $ret          .= $this->uxc->getUserModVersionArray(2, $editor, 'config');
            $ret .= $this->pc->getPhpCodeCommentLine('Editor : max characters admin area');
            $maxsize_image    = [
                'name'        => "'editor_maxchar'",
                'title'       => "'{$language}EDITOR_MAXCHAR'",
                'description' => "'{$language}EDITOR_MAXCHAR_DESC'",
                'formtype'    => "'textbox'",
                'valuetype'   => "'int'",
                'default'     => '50',
            ];
            $ret .= $this->uxc->getUserModVersionArray(2, $maxsize_image, 'config');
        }
        if (1 === $table_permissions) {
            $ret    .= $this->pc->getPhpCodeCommentLine('Get groups');
            $ret    .= $this->xc->getXcXoopsHandler('member');
            $ret    .= $this->xc->getXcEqualsOperator('$xoopsGroups ', '$memberHandler->getGroupList()');
            $ret    .= $this->xc->getXcEqualsOperator('$groups', '[]');
            $group  = $this->xc->getXcEqualsOperator('$groups[$group] ', '$key', null, "\t");
            $ret    .= $this->pc->getPhpCodeForeach('xoopsGroups', false, 'key', 'group', $group);
            $ret    .= $this->pc->getPhpCodeCommentLine('General access groups');
            $groups = [
                'name'        => "'groups'",
                'title'       => "'{$language}GROUPS'",
                'description' => "'{$language}GROUPS_DESC'",
                'formtype'    => "'select_multi'",
                'valuetype'   => "'array'",
                'default'     => '$groups',
                'options'     => '$groups',
            ];
            $ret .= $this->uxc->getUserModVersionArray(2, $groups, 'config');
            $ret .= $this->pc->getPhpCodeCommentLine('Upload groups');
            $uplgroups  = [
                'name'        => "'upload_groups'",
                'title'       => "'{$language}UPLOAD_GROUPS'",
                'description' => "'{$language}UPLOAD_GROUPS_DESC'",
                'formtype'    => "'select_multi'",
                'valuetype'   => "'array'",
                'default'     => '$groups',
                'options'     => '$groups',
            ];
            $ret         .= $this->uxc->getUserModVersionArray(2, $uplgroups, 'config');

            $ret         .= $this->pc->getPhpCodeCommentLine('Get Admin groups');
            $ret         .= $this->xc->getXcCriteriaCompo('crGroups');
            $crit        = $this->xc->getXcCriteria('', "'group_type'", "'Admin'", '', true);
            $ret         .= $this->xc->getXcCriteriaAdd('crGroups', $crit);
            $ret         .= $this->xc->getXcXoopsHandler('member');
            $ret         .= $this->xc->getXcEqualsOperator('$adminXoopsGroups ', '$memberHandler->getGroupList($crGroups)');
            $ret         .= $this->xc->getXcEqualsOperator('$adminGroups', '[]');
            $adminGroup  = $this->xc->getXcEqualsOperator('$adminGroups[$adminGroup] ', '$key', null, "\t");
            $ret         .= $this->pc->getPhpCodeForeach('adminXoopsGroups', false, 'key', 'adminGroup', $adminGroup);
            $adminGroups = [
                'name'        => "'admin_groups'",
                'title'       => "'{$language}ADMIN_GROUPS'",
                'description' => "'{$language}ADMIN_GROUPS_DESC'",
                'formtype'    => "'select_multi'",
                'valuetype'   => "'array'",
                'default'     => '$adminGroups',
                'options'     => '$adminGroups',
            ];
            $ret         .= $this->uxc->getUserModVersionArray(2, $adminGroups, 'config');
			$ret         .= $this->pc->getPhpCodeUnset('crGroups');
        }

        if (1 === $table_rate) {
            $ret    .= $this->pc->getPhpCodeCommentLine('Get groups');
            $ret    .= $this->xc->getXcXoopsHandler('member');
            $ret    .= $this->xc->getXcEqualsOperator('$xoopsGroups ', '$memberHandler->getGroupList()');
            $ret    .= $this->xc->getXcEqualsOperator('$ratingbar_groups', '[]');
            $group  = $this->xc->getXcEqualsOperator('$ratingbar_groups[$group] ', '$key', null, "\t");
            $ret    .= $this->pc->getPhpCodeForeach('xoopsGroups', false, 'key', 'group', $group);
            $ret    .= $this->pc->getPhpCodeCommentLine('Rating: Groups with rating permissions');
            $groups = [
                'name'        => "'ratingbar_groups'",
                'title'       => "'{$language}RATINGBAR_GROUPS'",
                'description' => "'{$language}RATINGBAR_GROUPS_DESC'",
                'formtype'    => "'select_multi'",
                'valuetype'   => "'array'",
                'default'     => '[1]',
                'options'     => '$ratingbar_groups',
            ];
            $ret .= $this->uxc->getUserModVersionArray(2, $groups, 'config');

            $ret .= $this->pc->getPhpCodeCommentLine('Rating : used ratingbar');
            $mimetypes_image  = [
                'name'        => "'ratingbars'",
                'title'       => "'{$language}RATINGBARS'",
                'description' => "'{$language}RATINGBARS_DESC'",
                'formtype'    => "'select'",
                'valuetype'   => "'int'",
                'default'     => '0',
                'options'     => "['{$language}RATING_NONE' => 0, '{$language}RATING_5STARS' => 1, '{$language}RATING_10STARS' => 2, '{$language}RATING_LIKES' => 3, '{$language}RATING_10NUM' => 4]",
            ];
            $ret .= $this->uxc->getUserModVersionArray(2, $mimetypes_image, 'config');
        }

        $keyword      = \implode(', ', $this->getKeywords());
        $ret          .= $this->pc->getPhpCodeCommentLine('Keywords');
        $arrayKeyword = [
            'name'        => "'keywords'",
            'title'       => "'{$language}KEYWORDS'",
            'description' => "'{$language}KEYWORDS_DESC'",
            'formtype'    => "'textbox'",
            'valuetype'   => "'text'",
            'default'     => "'{$moduleDirname}, {$keyword}'",
        ];
        $ret .= $this->uxc->getUserModVersionArray(2, $arrayKeyword, 'config');
        unset($this->keywords);

        if (1 === $table_uploadimage || 1 === $table_uploadfile) {
            $ret       .= $this->getXoopsVersionSelectSizeMB($moduleDirname);
        }
        if (1 === $table_uploadimage) {
            $ret .= $this->pc->getPhpCodeCommentLine('Uploads : maxsize of image');
            $maxsize_image    = [
                'name'        => "'maxsize_image'",
                'title'       => "'{$language}MAXSIZE_IMAGE'",
                'description' => "'{$language}MAXSIZE_IMAGE_DESC'",
                'formtype'    => "'select'",
                'valuetype'   => "'int'",
                'default'     => '3145728',
                'options'     => '$optionMaxsize',
            ];
            $ret .= $this->uxc->getUserModVersionArray(2, $maxsize_image, 'config');
            $ret .= $this->pc->getPhpCodeCommentLine('Uploads : mimetypes of image');
            $mimetypes_image  = [
                'name'        => "'mimetypes_image'",
                'title'       => "'{$language}MIMETYPES_IMAGE'",
                'description' => "'{$language}MIMETYPES_IMAGE_DESC'",
                'formtype'    => "'select_multi'",
                'valuetype'   => "'array'",
                'default'     => "['image/gif', 'image/jpeg', 'image/png']",
                'options'     => "['bmp' => 'image/bmp','gif' => 'image/gif','pjpeg' => 'image/pjpeg', 'jpeg' => 'image/jpeg','jpg' => 'image/jpg','jpe' => 'image/jpe', 'png' => 'image/png']",
            ];
            $ret .= $this->uxc->getUserModVersionArray(2, $mimetypes_image, 'config');
            $maxwidth_image   = [
                'name'        => "'maxwidth_image'",
                'title'       => "'{$language}MAXWIDTH_IMAGE'",
                'description' => "'{$language}MAXWIDTH_IMAGE_DESC'",
                'formtype'    => "'textbox'",
                'valuetype'   => "'int'",
                'default'     => '800',
            ];
            $ret .= $this->uxc->getUserModVersionArray(2, $maxwidth_image, 'config');
            $maxheight_image   = [
                'name'        => "'maxheight_image'",
                'title'       => "'{$language}MAXHEIGHT_IMAGE'",
                'description' => "'{$language}MAXHEIGHT_IMAGE_DESC'",
                'formtype'    => "'textbox'",
                'valuetype'   => "'int'",
                'default'     => '800',
            ];
            $ret .= $this->uxc->getUserModVersionArray(2, $maxheight_image, 'config');
        }
        if (1 === $table_uploadfile) {
            $ret .= $this->pc->getPhpCodeCommentLine('Uploads : maxsize of file');
            $maxsize_file     = [
                'name'        => "'maxsize_file'",
                'title'       => "'{$language}MAXSIZE_FILE'",
                'description' => "'{$language}MAXSIZE_FILE_DESC'",
                'formtype'    => "'select'",
                'valuetype'   => "'int'",
                'default'     => '3145728',
                'options'     => '$optionMaxsize',
            ];
            $ret .= $this->uxc->getUserModVersionArray(2, $maxsize_file, 'config');
            $ret .= $this->pc->getPhpCodeCommentLine('Uploads : mimetypes of file');
            $mimetypes_file   = [
                'name'        => "'mimetypes_file'",
                'title'       => "'{$language}MIMETYPES_FILE'",
                'description' => "'{$language}MIMETYPES_FILE_DESC'",
                'formtype'    => "'select_multi'",
                'valuetype'   => "'array'",
                'default'     => "['application/pdf', 'application/zip', 'text/comma-separated-values', 'text/plain', 'image/gif', 'image/jpeg', 'image/png']",
                'options'     => "['gif' => 'image/gif','pjpeg' => 'image/pjpeg', 'jpeg' => 'image/jpeg','jpg' => 'image/jpg','jpe' => 'image/jpe', 'png' => 'image/png', 'pdf' => 'application/pdf','zip' => 'application/zip','csv' => 'text/comma-separated-values', 'txt' => 'text/plain', 'xml' => 'application/xml', 'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']",
            ];
            $ret .= $this->uxc->getUserModVersionArray(2, $mimetypes_file, 'config');
        }
        if (1 === $table_admin) {
            $ret .= $this->pc->getPhpCodeCommentLine('Admin pager');
            $adminPager = [
                'name'        => "'adminpager'",
                'title'       => "'{$language}ADMIN_PAGER'",
                'description' => "'{$language}ADMIN_PAGER_DESC'",
                'formtype'    => "'textbox'",
                'valuetype'   => "'int'",
                'default'     => '10',
            ];
            $ret .= $this->uxc->getUserModVersionArray(2, $adminPager, 'config');
        }
        if (1 === $table_user) {
            $ret .= $this->pc->getPhpCodeCommentLine('User pager');
            $userPager = [
                'name'        => "'userpager'",
                'title'       => "'{$language}USER_PAGER'",
                'description' => "'{$language}USER_PAGER_DESC'",
                'formtype'    => "'textbox'",
                'valuetype'   => "'int'",
                'default'     => '10',
            ];
            $ret .= $this->uxc->getUserModVersionArray(2, $userPager, 'config');
        }
        if (1 === $table_tag) {
            $ret .= $this->pc->getPhpCodeCommentLine('Use tag');
            $useTag = [
                'name'        => "'usetag'",
                'title'       => "'{$language}USE_TAG'",
                'description' => "'{$language}USE_TAG_DESC'",
                'formtype'    => "'yesno'",
                'valuetype'   => "'int'",
                'default'     => '0',
            ];
            $ret .= $this->uxc->getUserModVersionArray(2, $useTag, 'config');
        }
        $ret .= $this->pc->getPhpCodeCommentLine('Number column');
        $numbCol          = [
            'name'        => "'numb_col'",
            'title'       => "'{$language}NUMB_COL'",
            'description' => "'{$language}NUMB_COL_DESC'",
            'formtype'    => "'select'",
            'valuetype'   => "'int'",
            'default'     => '1',
            'options'     => "[1 => '1', 2 => '2', 3 => '3', 4 => '4']",
        ];
        $ret .= $this->uxc->getUserModVersionArray(2, $numbCol, 'config');

        $ret .= $this->pc->getPhpCodeCommentLine('Divide by');
        $divideby         = [
            'name'        => "'divideby'",
            'title'       => "'{$language}DIVIDEBY'",
            'description' => "'{$language}DIVIDEBY_DESC'",
            'formtype'    => "'select'",
            'valuetype'   => "'int'",
            'default'     => '1',
            'options'     => "[1 => '1', 2 => '2', 3 => '3', 4 => '4']",
        ];
        $ret .= $this->uxc->getUserModVersionArray(2, $divideby, 'config');

        $ret .= $this->pc->getPhpCodeCommentLine('Table type');
        $tableType        = [
            'name'        => "'table_type'",
            'title'       => "'{$language}TABLE_TYPE'",
            'description' => "'{$language}DIVIDEBY_DESC'",
            'formtype'    => "'select'",
            'valuetype'   => "'int'",
            'default'     => "'bordered'",
            'options'     => "['bordered' => 'bordered', 'striped' => 'striped', 'hover' => 'hover', 'condensed' => 'condensed']",
        ];
        $ret              .= $this->uxc->getUserModVersionArray(2, $tableType, 'config');

        $ret              .= $this->pc->getPhpCodeCommentLine('Panel by');
        $panelType        = [
            'name'        => "'panel_type'",
            'title'       => "'{$language}PANEL_TYPE'",
            'description' => "'{$language}PANEL_TYPE_DESC'",
            'formtype'    => "'select'",
            'valuetype'   => "'text'",
            'default'     => "'default'",
            'options'     => "['default' => 'default', 'primary' => 'primary', 'success' => 'success', 'info' => 'info', 'warning' => 'warning', 'danger' => 'danger']",
        ];
        $ret              .= $this->uxc->getUserModVersionArray(2, $panelType, 'config');
        $ret              .= $this->pc->getPhpCodeCommentLine('Paypal ID');
        $paypal           = [
            'name'        => "'donations'",
            'title'       => "'{$language}IDPAYPAL'",
            'description' => "'{$language}IDPAYPAL_DESC'",
            'formtype'    => "'textbox'",
            'valuetype'   => "'textbox'",
            'default'     => "'XYZ123'",
        ];
        $ret              .= $this->uxc->getUserModVersionArray(2, $paypal, 'config');
        $ret              .= $this->pc->getPhpCodeCommentLine('Show Breadcrumbs');
        $breadcrumbs      = [
            'name'        => "'show_breadcrumbs'",
            'title'       => "'{$language}SHOW_BREADCRUMBS'",
            'description' => "'{$language}SHOW_BREADCRUMBS_DESC'",
            'formtype'    => "'yesno'",
            'valuetype'   => "'int'",
            'default'     => '1',
        ];
        $ret              .= $this->uxc->getUserModVersionArray(2, $breadcrumbs, 'config');
        $ret              .= $this->pc->getPhpCodeCommentLine('Advertise');
        $advertise        = [
            'name'        => "'advertise'",
            'title'       => "'{$language}ADVERTISE'",
            'description' => "'{$language}ADVERTISE_DESC'",
            'formtype'    => "'textarea'",
            'valuetype'   => "'text'",
            'default'     => "''",
        ];
        $ret              .= $this->uxc->getUserModVersionArray(2, $advertise, 'config');
        $ret              .= $this->pc->getPhpCodeCommentLine('Bookmarks');
        $bookmarks        = [
            'name'        => "'bookmarks'",
            'title'       => "'{$language}BOOKMARKS'",
            'description' => "'{$language}BOOKMARKS_DESC'",
            'formtype'    => "'yesno'",
            'valuetype'   => "'int'",
            'default'     => '0',
        ];
        $ret              .= $this->uxc->getUserModVersionArray(2, $bookmarks, 'config');

        /*
         * removed, as there are no system templates in xoops core for fb or disqus comments
         * modulebuilder currently is also not creatings tpl files for this
        $ret              .= $this->pc->getPhpCodeCommentLine('Facebook Comments');
        $facebookComments = [
            'name'        => "'facebook_comments'",
            'title'       => "'{$language}FACEBOOK_COMMENTS'",
            'description' => "'{$language}FACEBOOK_COMMENTS_DESC'",
            'formtype'    => "'yesno'",
            'valuetype'   => "'int'",
            'default'     => '0',
        ];
        $ret              .= $this->uxc->getUserModVersion(3, $facebookComments, 'config', '$c');
        $ret              .= $this->getSimpleString('++$c;');
        $ret              .= $this->pc->getPhpCodeCommentLine('Disqus Comments');
        $disqusComments   = [
            'name'        => "'disqus_comments'",
            'title'       => "'{$language}DISQUS_COMMENTS'",
            'description' => "'{$language}DISQUS_COMMENTS_DESC'",
            'formtype'    => "'yesno'",
            'valuetype'   => "'int'",
            'default'     => '0',
        ];
        $ret              .= $this->uxc->getUserModVersion(3, $disqusComments, 'config', '$c');
        $ret              .= $this->getSimpleString('++$c;');
        */

        $ret              .= $this->pc->getPhpCodeCommentLine('Make Sample button visible?');
        $maintainedby     = [
            'name'        => "'displaySampleButton'",
            'title'       => "'CO_' . \$moduleDirNameUpper . '_' . 'SHOW_SAMPLE_BUTTON'",
            'description' => "'CO_' . \$moduleDirNameUpper . '_' . 'SHOW_SAMPLE_BUTTON_DESC'",
            'formtype'    => "'yesno'",
            'valuetype'   => "'int'",
            'default'     => '1',
        ];
        $ret              .= $this->uxc->getUserModVersionArray(2, $maintainedby, 'config');

        $ret              .= $this->pc->getPhpCodeCommentLine('Maintained by');
        $maintainedby     = [
            'name'        => "'maintainedby'",
            'title'       => "'{$language}MAINTAINEDBY'",
            'description' => "'{$language}MAINTAINEDBY_DESC'",
            'formtype'    => "'textbox'",
            'valuetype'   => "'text'",
            'default'     => "'{$module->getVar('mod_support_url')}'",
        ];
        $ret              .= $this->uxc->getUserModVersionArray(2, $maintainedby, 'config');

        return $ret;
    }

    /**
     * @private function getXoopsVersionNotifications
     * @param $module
     * @param $language
     * @return string
     */
    private function getXoopsVersionNotifications($module, $language)
    {
        $moduleDirname = $module->getVar('mod_dirname');
        $ret           = $this->getDashComment('Notifications');
        $ret           .= $this->uxc->getUserModVersionText(1, 1, 'hasNotification');
        $notifications = ['lookup_file' => "'include/notification.inc.php'", 'lookup_func' => "'{$moduleDirname}_notify_iteminfo'"];
        $ret           .= $this->uxc->getUserModVersionArray(1, $notifications, 'notification');

        $notifyFiles       = [];
        $tables            = $this->getTableTables($module->getVar('mod_id'), 'table_order');
        $tableCategory     = [];
        $tableBroken       = [];
        $tableComments     = [];
        $tableSubmit       = [];
        $tableId           = null;
        $tableMid          = null;
        $notifyCategory    = '';
        $notifyEventGlobal = $this->pc->getPhpCodeCommentLine('Global events notification');
        $notifyEventTable  = $this->pc->getPhpCodeCommentLine('Event notifications for items');

        //global events
        $notifyEventGlobal .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'global_new', 'global', 0, 'global_new', 'global_new_notify');
        $notifyEventGlobal .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'global_modify', 'global', 0, 'global_modify', 'global_modify_notify');
        $notifyEventGlobal .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'global_delete', 'global', 0, 'global_delete', 'global_delete_notify');
        $notifyEventGlobal .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'global_approve', 'global', 1, 'global_approve', 'global_approve_notify');
        foreach (\array_keys($tables) as $t) {
            $tableBroken[]   = $tables[$t]->getVar('table_broken');
            $tableComments[] = $tables[$t]->getVar('table_comments');
        }
        if (\in_array(1, $tableBroken)) {
            $notifyEventGlobal .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'global_broken', 'global', 1, 'global_broken', 'global_broken_notify');
        }
        if (\in_array(1, $tableComments)) {
            $notifyEventGlobal .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'global_comment', 'global', 0, 'global_comment', 'global_comment_notify');
        }

        foreach (\array_keys($tables) as $t) {
            $tableId         = $tables[$t]->getVar('table_id');
            $tableMid        = $tables[$t]->getVar('table_mid');
            $tableName       = $tables[$t]->getVar('table_name');
            $tableSoleName   = $tables[$t]->getVar('table_solename');
            $tableCategory[] = $tables[$t]->getVar('table_category');
            $tableSubmit[]   = $tables[$t]->getVar('table_submit');
            $fields      = $this->getTableFields($tableMid, $tableId);
            $fieldId     = 0;
            foreach (\array_keys($fields) as $f) {
                $fieldName    = $fields[$f]->getVar('field_name');
                if (0 == $f) {
                    $fieldId = $fieldName;
                }
            }
            if (1 == $tables[$t]->getVar('table_notifications')) {
                $notifyFiles[] = $tableName;
                $notifyCategory .= $this->getXoopsVersionNotificationTableName($language, 'category', $tableName, $tableSoleName, $tableName, $fieldId, 1);
                //$notifyEvent .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', $tableSoleName . '_new', $tableName, 0, $tableSoleName, $tableSoleName . '_new_notify');
                $notifyEventTable .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', $tableSoleName . '_modify', $tableName, 0, $tableSoleName . '_modify', $tableSoleName . '_modify_notify');
                $notifyEventTable .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', $tableSoleName . '_delete', $tableName, 0, $tableSoleName . '_delete', $tableSoleName . '_delete_notify');
                $notifyEventTable .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', $tableSoleName . '_approve', $tableName, 0, $tableSoleName . '_approve', $tableSoleName . '_approve_notify');
                if (1 == $tables[$t]->getVar('table_broken')) {
                    $notifyEventTable .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', $tableSoleName . '_broken', $tableName, 0, $tableSoleName . '_broken', $tableSoleName . '_broken_notify');
                }
                /*event will be added by xoops
                if (1 == $tables[$t]->getVar('table_comments')) {
                    $notifyEventTable .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', $tableSoleName . '_comment', $tableName, 0, $tableSoleName . '_comment', $tableSoleName . '_comment_notify');
                }*/
            }
        }
        $ret .= $this->pc->getPhpCodeCommentLine('Categories of notification');
        $ret .= $this->getXoopsVersionNotificationGlobal($language, 'category', 'global', 'global', $notifyFiles);

        //$ret .= $this->getXoopsVersionNotificationCategory($language, 'category', 'category', 'category', $notifyFiles, $fieldParent, '1');

        $ret .= $notifyCategory . $notifyEventGlobal . $notifyEventTable;

        return $ret;
    }

    /**
     * @private function getXoopsVersionNotificationGlobal
     * @param $language
     * @param $type
     * @param $name
     * @param $title
     * @param $from
     *
     * @return string
     */
    private function getXoopsVersionNotificationGlobal($language, $type, $name, $title, $from)
    {
        $title       = \mb_strtoupper($title);
        $implodeFrom = \implode(".php', '", $from);
        $ret         = $this->pc->getPhpCodeCommentLine('Global Notify');
        $global      = [
            'name'           => "'{$name}'",
            'title'          => "{$language}NOTIFY_{$title}",
            'description'    => "''",
            'subscribe_from' => "['index.php', '{$implodeFrom}.php']",
        ];
        $ret         .= $this->uxc->getUserModVersionArray(3, $global, 'notification', "'{$type}'");

        return $ret;
    }

    /**
     * @private function getXoopsVersionNotificationTableName
     * @param $language
     * @param $type
     * @param $name
     * @param $title
     * @param $file
     * @param $item
     * @param $allow
     *
     * @return string
     */
    private function getXoopsVersionNotificationTableName($language, $type, $name, $title, $file, $item, $allow)
    {
        $stuTitle = \mb_strtoupper($title);
        $ucfTitle = \ucfirst($title);
        $ret      = $this->pc->getPhpCodeCommentLine($ucfTitle . ' Notify');
        $table    = [
            'name'           => "'{$name}'",
            'title'          => "{$language}NOTIFY_{$stuTitle}",
            'description'    => "''",
            'subscribe_from' => "'{$file}.php'",
            'item_name'      => "'{$item}'",
            'allow_bookmark' => (string)$allow,
        ];
        $ret .= $this->uxc->getUserModVersionArray(3, $table, 'notification', "'{$type}'");

        return $ret;
    }

    /**
     * @private function getXoopsVersionNotifications
     * @param $language
     * @param $type
     * @param $name
     * @param $category
     * @param $admin
     * @param $title
     * @param $mail
     *
     * @return string
     */
    private function getXoopsVersionNotificationCodeComplete($language, $type, $name, $category, $admin, $title, $mail)
    {
        $title    = \mb_strtoupper($title);
        $ucfTitle = \ucfirst($title);
        $ret      = $this->pc->getPhpCodeCommentLine($ucfTitle . ' Notify');
        $event    = [
            'name'          => "'{$name}'",
            'category'      => "'{$category}'",
            'admin_only'    => (string)$admin,
            'title'         => "{$language}NOTIFY_{$title}",
            'caption'       => "{$language}NOTIFY_{$title}_CAPTION",
            'description'   => "''",
            'mail_template' => "'{$mail}'",
            'mail_subject'  => "{$language}NOTIFY_{$title}_SUBJECT",
        ];
        $ret .= $this->uxc->getUserModVersionArray(3, $event, 'notification', "'{$type}'");

        return $ret;
    }

    /**
     * @private function getXoopsVersionNotifications
     * @param $moduleDirname
     * @param string $t
     * @return string
     */
    private function getXoopsVersionSelectSizeMB($moduleDirname, $t = '')
    {
        $ucModuleDirname       = \mb_strtoupper($moduleDirname);

        $ret  = $this->pc->getPhpCodeCommentLine('create increment steps for file size');
        $ret  .= $this->pc->getPhpCodeIncludeDir("__DIR__ . '/include/xoops_version.inc.php'", '',true,true);
        $ret  .= $this->xc->getXcEqualsOperator('$iniPostMaxSize      ', "{$moduleDirname}ReturnBytes(\ini_get('post_max_size'))");
        $ret  .= $this->xc->getXcEqualsOperator('$iniUploadMaxFileSize', "{$moduleDirname}ReturnBytes(\ini_get('upload_max_filesize'))");
        $ret   .= $this->xc->getXcEqualsOperator('$maxSize             ', 'min($iniPostMaxSize, $iniUploadMaxFileSize)');
        $cond = $this->xc->getXcEqualsOperator('$increment', '500', null, $t . "\t");
        $ret  .= $this->pc->getPhpCodeConditions('$maxSize', ' > ', '10000 * 1048576', $cond, false, $t);
        $cond = $this->xc->getXcEqualsOperator('$increment', '200', null, $t . "\t");
        $ret  .= $this->pc->getPhpCodeConditions('$maxSize', ' <= ', '10000 * 1048576', $cond, false, $t);
        $cond  = $this->xc->getXcEqualsOperator('$increment', '100', null, $t . "\t");
        $ret   .= $this->pc->getPhpCodeConditions('$maxSize', ' <= ', '5000 * 1048576', $cond, false, $t);
        $cond  = $this->xc->getXcEqualsOperator('$increment', '50', null, $t . "\t");
        $ret   .= $this->pc->getPhpCodeConditions('$maxSize', ' <= ', '2500 * 1048576', $cond, false, $t);
        $cond  = $this->xc->getXcEqualsOperator('$increment', '10', null, $t . "\t");
        $ret   .= $this->pc->getPhpCodeConditions('$maxSize', ' <= ', '1000 * 1048576', $cond, false, $t);
        $cond  = $this->xc->getXcEqualsOperator('$increment', '5', null, $t . "\t");
        $ret   .= $this->pc->getPhpCodeConditions('$maxSize', ' <= ', '500 * 1048576', $cond, false, $t);
        $cond  = $this->xc->getXcEqualsOperator('$increment', '2', null, $t . "\t");
        $ret   .= $this->pc->getPhpCodeConditions('$maxSize', ' <= ', '100 * 1048576', $cond, false, $t);
        $cond  = $this->xc->getXcEqualsOperator('$increment', '1', null, $t . "\t");
        $ret   .= $this->pc->getPhpCodeConditions('$maxSize', ' <= ', '50 * 1048576', $cond, false, $t);
        $cond  = $this->xc->getXcEqualsOperator('$increment', '0.5', null, $t . "\t");
        $ret   .= $this->pc->getPhpCodeConditions('$maxSize', ' <= ', '25 * 1048576', $cond, false, $t);
        $ret   .= $this->xc->getXcEqualsOperator('$optionMaxsize', '[]');
        $ret   .= $this->xc->getXcEqualsOperator('$i', '$increment');
        $while = $this->xc->getXcEqualsOperator("\$optionMaxsize[\$i . ' ' . _MI_{$ucModuleDirname}_SIZE_MB]", '$i * 1048576', null, $t . "\t");
        $while .= $this->xc->getXcEqualsOperator('$i', '$increment', '+',$t . "\t");
        $ret   .= $this->pc->getPhpCodeWhile('i * 1048576', $while, '$maxSize', '<=');

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
        $table         = $this->getTable();
        $tables        = $this->getTables();
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $language      = $this->getLanguage($moduleDirname, 'MI');
        $content       = $this->getHeaderFilesComments($module);
        $content       .= $this->getXoopsVersionHeader($module, $language);
        $content       .= $this->getXoopsVersionTemplatesAdminUser($moduleDirname, $tables, $module->getVar('mod_admin'), $module->getVar('mod_user'));
        if (\count($tables) > 0) {
            $content .= $this->getXoopsVersionMySQL($moduleDirname, $table, $tables);
        }
        $tableSearch        = [];
        $tableComments      = [];
        $tableSubmenu       = [];
        $tableBlocks        = [];
        $tableNotifications = [];
        foreach (\array_keys($tables) as $t) {
            $tableSearch[]        = $tables[$t]->getVar('table_search');
            $tableComments[]      = $tables[$t]->getVar('table_comments');
            $tableSubmenu[]       = $tables[$t]->getVar('table_submenu');
            $tableBlocks[]        = $tables[$t]->getVar('table_blocks');
            $tableNotifications[] = $tables[$t]->getVar('table_notifications');
        }
        if (\in_array(1, $tableSearch)) {
            $content .= $this->getXoopsVersionSearch($moduleDirname);
        }
        if (\in_array(1, $tableComments)) {
            $content .= $this->getXoopsVersionComments($moduleDirname, $tables);
        }
        if (\in_array(1, $tableSubmenu)) {
            $content .= $this->getXoopsVersionSubmenu($language, $tables);
        }
        if (\in_array(1, $tableBlocks)) {
            $content .= $this->getXoopsVersionBlocks($moduleDirname, $tables, $language);
        }
        $content .= $this->getXoopsVersionConfig($module, $tables, $language);
        if (\in_array(1, $tableNotifications)) {
            $content .= $this->getXoopsVersionNotifications($module, $language);
        }
        $this->create($moduleDirname, '/', $filename, $content, \_AM_MODULEBUILDER_FILE_CREATED, \_AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
