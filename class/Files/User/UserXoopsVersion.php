<?php

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
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
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
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
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
    public function write($module, $table, $tables, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setTables($tables);
        $this->setFileName($filename);
        foreach (array_keys($tables) as $t) {
            $tableName = $tables[$t]->getVar('table_name');
            $this->setKeywords($tableName);
        }
    }

    /**
     * @public function setKeywords
     * @param mixed $keywords
     */
    public function setKeywords($keywords)
    {
        if (is_array($keywords)) {
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
        $xc   = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $uxc  = UserXoopsCode::getInstance();
        $date = date('Y/m/d');
        $ret  = $this->getSimpleString('');
        $ret  .= Modulebuilder\Files\CreatePhpCode::getInstance()->getPhpCodeCommentLine();
        $ret  .= $xc->getXcEqualsOperator('$moduleDirName     ', 'basename(__DIR__)');
        $ret  .= $xc->getXcEqualsOperator('$moduleDirNameUpper', 'mb_strtoupper($moduleDirName)');
        $ret  .= $this->getDashComment('Informations');
        $ha   = (1 == $module->getVar('mod_admin')) ? 1 : 0;
        $hm   = (1 == $module->getVar('mod_user')) ? 1 : 0;

        $descriptions = [
            'name'                => "{$language}NAME",
            'version'             => (string)$module->getVar('mod_version'),
            'description'         => "{$language}DESC",
            'author'              => "'{$module->getVar('mod_author')}'",
            'author_mail'         => "'{$module->getVar('mod_author_mail')}'",
            'author_website_url'  => "'{$module->getVar('mod_author_website_url')}'",
            'author_website_name' => "'{$module->getVar('mod_author_website_name')}'",
            'credits'             => "'{$module->getVar('mod_credits')}'",
            'license'             => "'{$module->getVar('mod_license')}'",
            'license_url'         => "'http://www.gnu.org/licenses/gpl-3.0.en.html'",
            'help'                => "'page=help'",
            'release_info'        => "'{$module->getVar('mod_release_info')}'",
            'release_file'        => "XOOPS_URL . '/modules/{$module->getVar('mod_dirname')}/docs/{$module->getVar('mod_release_file')}'",
            'release_date'        => "'{$date}'",
            'manual'              => "'{$module->getVar('mod_manual')}'",
            'manual_file'         => "XOOPS_URL . '/modules/{$module->getVar('mod_dirname')}/docs/{$module->getVar('mod_manual_file')}'",
            'min_php'             => "'{$module->getVar('mod_min_php')}'",
            'min_xoops'           => "'{$module->getVar('mod_min_xoops')}'",
            'min_admin'           => "'{$module->getVar('mod_min_admin')}'",
            'min_db'              => "array('mysql' => '{$module->getVar('mod_min_mysql')}', 'mysqli' => '{$module->getVar('mod_min_mysql')}')",
            'image'               => "'assets/images/logoModule.png'",
            'dirname'             => 'basename(__DIR__)',
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

        $ret .= $uxc->getUserModVersionArray(0, $descriptions);

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
        $uxc       = UserXoopsCode::getInstance();
        $tableName = $table->getVar('table_name');
        $n         = 1;
        $ret       = '';
        $items     = [];
        if (!empty($tableName)) {
            $ret         .= $this->getDashComment('Mysql');
            $description = "'sql/mysql.sql'";
            $ret         .= $uxc->getUserModVersionText(2, $description, 'sqlfile', "'mysql'");
            $ret         .= Modulebuilder\Files\CreatePhpCode::getInstance()->getPhpCodeCommentLine('Tables');

            foreach (array_keys($tables) as $t) {
                $items[] = "'{$moduleDirname}_{$tables[$t]->getVar('table_name')}'";
                ++$n;
            }
            $ret .= $uxc->getUserModVersionArray(11, $items, 'tables', $n);
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
        $uxc   = UserXoopsCode::getInstance();
        $ret   = $this->getDashComment('Search');
        $ret   .= $uxc->getUserModVersionText(1, 1, 'hasSearch');
        $items = ['file' => "'include/search.inc.php'", 'func' => "'{$moduleDirname}_search'"];
        $ret   .= $uxc->getUserModVersionArray(1, $items, 'search');

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
        $uxc = UserXoopsCode::getInstance();
        $tableName = '';
        $fieldId = '';
        foreach (array_keys($tables) as $t) {
            if (1 == $tables[$t]->getVar('table_comments')) {
                $tableName = $tables[$t]->getVar('table_name');
                $fields = $this->getTableFields($tables[$t]->getVar('table_mid'), $tables[$t]->getVar('table_id'));
                foreach (array_keys($fields) as $f) {
                    $fieldName = $fields[$f]->getVar('field_name');
                    if (0 == $f) {
                        $fieldId = $fieldName;
                    }
                }
            }
        }
        $ret          = $this->getDashComment('Comments');
        $ret          .= $uxc->getUserModVersionText(1, "1", 'hasComments');
        $ret          .= $uxc->getUserModVersionText(2, "'{$tableName}.php'", 'comments', "'pageName'");
        $ret          .= $uxc->getUserModVersionText(2, "'{$fieldId}'", 'comments', "'itemName'");
        $ret          .= Modulebuilder\Files\CreatePhpCode::getInstance()->getPhpCodeCommentLine('Comment callback functions');
        $ret          .= $uxc->getUserModVersionText(2, "'include/comment_functions.php'", 'comments', "'callbackFile'");
        $descriptions = ['approve' => "'{$moduleDirname}CommentsApprove'", 'update' => "'{$moduleDirname}CommentsUpdate'"];
        $ret          .= $uxc->getUserModVersionArray(2, $descriptions, 'comments', "'callback'");

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
        $uxc  = UserXoopsCode::getInstance();
        $pc   = Modulebuilder\Files\CreatePhpCode::getInstance();
        $ret  = $this->getDashComment('Templates');
        $item = [];
        if ($admin) {
            $item[] = $pc->getPhpCodeCommentLine('Admin templates');
            $item[] = $this->getXoopsVersionTemplatesLine($moduleDirname, 'about', '', true);
            $item[] = $this->getXoopsVersionTemplatesLine($moduleDirname, 'header', '', true);
            $item[] = $this->getXoopsVersionTemplatesLine($moduleDirname, 'index', '', true);
            $tablePermissions = [];
            $tableBroken      = [];
            foreach (array_keys($tables) as $t) {
                $tableName          = $tables[$t]->getVar('table_name');
                $tablePermissions[] = $tables[$t]->getVar('table_permissions');
                $tableBroken[]      = $tables[$t]->getVar('table_broken');
                $item[]             .= $this->getXoopsVersionTemplatesLine($moduleDirname, $tableName, '', true);
            }
            if (in_array(1, $tableBroken)) {
                $item[] = $this->getXoopsVersionTemplatesLine($moduleDirname, 'broken', '', true);
            }
            if (in_array(1, $tablePermissions)) {
                $item[] = $this->getXoopsVersionTemplatesLine($moduleDirname, 'permissions', '', true);
            }
            $item[] = $this->getXoopsVersionTemplatesLine($moduleDirname, 'footer', '', true);
        }

        if ($user) {
            $item[]      = $pc->getPhpCodeCommentLine('User templates');
            $item[]      = $this->getXoopsVersionTemplatesLine($moduleDirname, 'header', '');
            $item[]      = $this->getXoopsVersionTemplatesLine($moduleDirname, 'index', '');
            $tableBroken = [];
            $tablePdf    = [];
            $tablePrint  = [];
            $tableRate   = [];
            $tableRss    = [];
            $tableSearch = [];
            $tableSingle = [];
            $tableSubmit = [];
            foreach (array_keys($tables) as $t) {
                $tableName     = $tables[$t]->getVar('table_name');
                $tableBroken[] = $tables[$t]->getVar('table_broken');
                $tablePdf[]    = $tables[$t]->getVar('table_pdf');
                $tablePrint[]  = $tables[$t]->getVar('table_print');
                $tableRate[]   = $tables[$t]->getVar('table_rate');
                $tableRss[]    = $tables[$t]->getVar('table_rss');
                $tableSearch[] = $tables[$t]->getVar('table_search');
                $tableSingle[] = $tables[$t]->getVar('table_single');
                $tableSubmit[] = $tables[$t]->getVar('table_submit');
                $item[]        = $this->getXoopsVersionTemplatesLine($moduleDirname, $tableName, '');
                $item[]        = $this->getXoopsVersionTemplatesLine($moduleDirname, $tableName, 'list');
                $item[]        = $this->getXoopsVersionTemplatesLine($moduleDirname, $tableName, 'item');
            }
            $item[]  = $this->getXoopsVersionTemplatesLine($moduleDirname, 'breadcrumbs', '');
            if (in_array(1, $tablePdf)) {
                $item[] = $this->getXoopsVersionTemplatesLine($moduleDirname, 'pdf', '');
            }
            if (in_array(1, $tablePrint)) {
                $item[] = $this->getXoopsVersionTemplatesLine($moduleDirname, 'print', '');
            }
            if (in_array(1, $tableRate)) {
                $item[] = $this->getXoopsVersionTemplatesLine($moduleDirname, 'rate', '');
            }
            if (in_array(1, $tableRss)) {
                $item[] = $this->getXoopsVersionTemplatesLine($moduleDirname, 'rss', '');
            }
            if (in_array(1, $tableSearch)) {
                $item[] = $this->getXoopsVersionTemplatesLine($moduleDirname, 'search', '');
            }
            if (in_array(1, $tableSingle)) {
                $item[] = $this->getXoopsVersionTemplatesLine($moduleDirname, 'single', '');
            }
            $item[] = $this->getXoopsVersionTemplatesLine($moduleDirname, 'footer', '');
        }

        $ret .= $uxc->getUserModVersionArray(11, $item, "templates");

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
        $pc = Modulebuilder\Files\CreatePhpCode::getInstance();
        $uxc = UserXoopsCode::getInstance();

        $ret     = $this->getDashComment('Menu');
        $xModule = $pc->getPhpCodeGlobals('xoopsModule');
        $cond    = 'isset(' . $xModule . ') && is_object(' . $xModule . ')';
        $one     =  $pc->getPhpCodeGlobals('xoopsModule') . "->getVar('dirname')";
        $ret     .= $pc->getPhpCodeTernaryOperator('currdirname ', $cond, $one, "'system'");

        $i          = 1;
        $descriptions = [
            'name' => "{$language}SMNAME{$i}",
            'url'  => "'index.php'",
        ];
        $contentIf  = $uxc->getUserModVersionArray(2, $descriptions, 'sub', '','', "\t");

        $tableSearch = [];
        foreach (array_keys($tables) as $t) {
            $tableName     = $tables[$t]->getVar('table_name');
            $tableSearch[] = $tables[$t]->getVar('table_search');
            if (1 == $tables[$t]->getVar('table_submenu')) {
                $contentIf .= $pc->getPhpCodeCommentLine('Sub', $tableName, "\t");
                $descriptions = [
                    'name' => "{$language}SMNAME{$i}",
                    'url'  => "'{$tableName}.php'",
                ];
                $contentIf  .= $uxc->getUserModVersionArray(2, $descriptions, 'sub', '','', "\t");
                unset($item);
            }
            ++$i;
            if (1 == $tables[$t]->getVar('table_submit')) {
                $contentIf .= $pc->getPhpCodeCommentLine('Sub', 'Submit', "\t");
                $descriptions = [
                    'name' => "{$language}SMNAME{$i}",
                    'url'  => "'{$tableName}.php?op=new'",
                ];
                $contentIf  .= $uxc->getUserModVersionArray(2, $descriptions, 'sub', '','', "\t");
                ++$i;
            }
        }

        //TODO: after finalizing creation of search.php by User/UserSearch.php this sub menu item can be activated
        /*
        if (in_array(1, $tableSearch)) {
            $contentIf .= $cpc->getPhpCodeCommentLine('Sub', 'Search', "\t");
            $descriptions = [
                'name' => "{$language}SMNAME{$i}",
                'url'  => "'search.php'",
            ];
            $contentIf  .= $uxc->getUserModVersionArray(2, $descriptions, 'sub', '','', "\t");
        }
        */
        unset($i);

        $ret .= $pc->getPhpCodeConditions('$moduleDirName', ' == ', '$currdirname', $contentIf);

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
        $ret           = $this->getDashComment('Blocks');
        $tableCategory = [];
        foreach (array_keys($tables) as $i) {
            $tableName        = $tables[$i]->getVar('table_name');
            $tableCategory[]  = $tables[$i]->getVar('table_category');
            if (0 == $tables[$i]->getVar('table_category')) {
                $ret .= $this->getXoopsVersionTypeBlocks($moduleDirname, $tableName, 'LAST', $language, 'last');
                $ret .= $this->getXoopsVersionTypeBlocks($moduleDirname, $tableName, 'NEW', $language, 'new');
                $ret .= $this->getXoopsVersionTypeBlocks($moduleDirname, $tableName, 'HITS', $language, 'hits');
                $ret .= $this->getXoopsVersionTypeBlocks($moduleDirname, $tableName, 'TOP', $language, 'top');
                $ret .= $this->getXoopsVersionTypeBlocks($moduleDirname, $tableName, 'RANDOM', $language, 'random');
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
        $pc  = Modulebuilder\Files\CreatePhpCode::getInstance();
        $uxc = UserXoopsCode::getInstance();
        $stuTableName    = mb_strtoupper($tableName);
        $ucfTableName    = ucfirst($tableName);
        $ret             = $pc->getPhpCodeCommentLine($ucfTableName . ' ' . $type);
        $blocks          = [
            'file'        => "'{$tableName}.php'",
            'name'        => "{$language}{$stuTableName}_BLOCK_{$stuTableSoleName}",
            'description' => "{$language}{$stuTableName}_BLOCK_{$stuTableSoleName}_DESC",
            'show_func'   => "'b_{$moduleDirname}_{$tableName}_show'",
            'edit_func'   => "'b_{$moduleDirname}_{$tableName}_edit'",
            'template'    => "'{$moduleDirname}_block_{$tableName}.tpl'",
            'options'     => "'{$type}|5|25|0'",
        ];
        $ret             .= $uxc->getUserModVersionArray(2, $blocks, 'blocks');

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
        $pc  = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc  = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $uxc = UserXoopsCode::getInstance();
        $moduleDirname  = $module->getVar('mod_dirname');
        $ret            = $this->getDashComment('Config');

        $table_editors     = 0;
        $table_permissions = 0;
        $table_admin       = 0;
        $table_user        = 0;
        $table_tag         = 0;
        $table_uploadimage = 0;
        $table_uploadfile  = 0;
        foreach ($tables as $table) {
            $fields = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
            //$stuTablename    = mb_strtoupper($table->getVar('table_name'));
            foreach (array_keys($fields) as $f) {
                $fieldElement = (int)$fields[$f]->getVar('field_element');
                switch ($fieldElement) {
                    case 3:
                        $table_editors = 1;
                        break;
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
        }
        if (1 === $table_editors) {
            //$fieldName    = $fields[$f]->getVar('field_name');
            //$rpFieldName  = $this->getRightString($fieldName);
            //$ucfFieldName = ucfirst($rpFieldName);
            //$stuFieldName = mb_strtoupper($rpFieldName);
            $ret          .= $pc->getPhpCodeCommentLine('Editor Admin', '');
            $ret          .= $xc->getXcXoopsLoad('xoopseditorhandler');
            $ret          .= $xc->getXcEqualsOperator('$editorHandler', 'XoopsEditorHandler::getInstance()');
            $editor       = [
                'name'        => "'editor_admin'",
                'title'       => "'{$language}EDITOR_ADMIN'",
                'description' => "'{$language}EDITOR_ADMIN_DESC'",
                'formtype'    => "'select'",
                'valuetype'   => "'text'",
                'default'     => "'dhtml'",
                'options'     => 'array_flip($editorHandler->getList())',
            ];
            $ret          .= $uxc->getUserModVersionArray(2, $editor, 'config');
            $ret          .= $pc->getPhpCodeCommentLine('Editor User', '');
            $ret          .= $xc->getXcXoopsLoad('xoopseditorhandler');
            $ret          .= $xc->getXcEqualsOperator('$editorHandler', 'XoopsEditorHandler::getInstance()');
            $editor       = [
                'name'        => "'editor_user'",
                'title'       => "'{$language}EDITOR_USER'",
                'description' => "'{$language}EDITOR_USER_DESC'",
                'formtype'    => "'select'",
                'valuetype'   => "'text'",
                'default'     => "'dhtml'",
                'options'     => 'array_flip($editorHandler->getList())',
            ];
            $ret          .= $uxc->getUserModVersionArray(2, $editor, 'config');
            $ret .= $pc->getPhpCodeCommentLine('Editor : max characters admin area');
            $maxsize_image    = [
                'name'        => "'editor_maxchar'",
                'title'       => "'{$language}EDITOR_MAXCHAR'",
                'description' => "'{$language}EDITOR_MAXCHAR_DESC'",
                'formtype'    => "'textbox'",
                'valuetype'   => "'int'",
                'default'     => '50',
            ];
            $ret .= $uxc->getUserModVersionArray(2, $maxsize_image, 'config');
        }
        if (1 === $table_permissions) {
            $ret    .= $pc->getPhpCodeCommentLine('Get groups');
            $ret    .= $xc->getXcXoopsHandler('member');
            $ret    .= $xc->getXcEqualsOperator('$xoopsGroups ', '$memberHandler->getGroupList()');
            $ret    .= $xc->getXcEqualsOperator('$groups', '[]');
            $group  = $xc->getXcEqualsOperator('$groups[$group] ', '$key', null, "\t");
            $ret    .= $pc->getPhpCodeForeach('xoopsGroups', false, 'key', 'group', $group);
            $ret    .= $pc->getPhpCodeCommentLine('General access groups');
            $groups = [
                'name'        => "'groups'",
                'title'       => "'{$language}GROUPS'",
                'description' => "'{$language}GROUPS_DESC'",
                'formtype'    => "'select_multi'",
                'valuetype'   => "'array'",
                'default'     => '$groups',
                'options'     => '$groups',
            ];
            $ret .= $uxc->getUserModVersionArray(2, $groups, 'config');
            $ret .= $pc->getPhpCodeCommentLine('Upload groups');
            $uplgroups  = [
                'name'        => "'upload_groups'",
                'title'       => "'{$language}UPLOAD_GROUPS'",
                'description' => "'{$language}UPLOAD_GROUPS_DESC'",
                'formtype'    => "'select_multi'",
                'valuetype'   => "'array'",
                'default'     => '$groups',
                'options'     => '$groups',
            ];
            $ret         .= $uxc->getUserModVersionArray(2, $uplgroups, 'config');

            $ret         .= $pc->getPhpCodeCommentLine('Get Admin groups');
            $ret         .= $xc->getXcCriteriaCompo('crGroups');
            $crit        = $xc->getXcCriteria('', "'group_type'", "'Admin'", '', true);
            $ret         .= $xc->getXcCriteriaAdd('crGroups', $crit, '', "\n");
            $ret         .= $xc->getXcXoopsHandler('member');
            $ret         .= $xc->getXcEqualsOperator('$adminXoopsGroups ', '$memberHandler->getGroupList($crGroups)');
            $ret         .= $xc->getXcEqualsOperator('$adminGroups', '[]');
            $adminGroup  = $xc->getXcEqualsOperator('$adminGroups[$adminGroup] ', '$key', null, "\t");
            $ret         .= $pc->getPhpCodeForeach('adminXoopsGroups', false, 'key', 'adminGroup', $adminGroup);
            $adminGroups = [
                'name'        => "'admin_groups'",
                'title'       => "'{$language}ADMIN_GROUPS'",
                'description' => "'{$language}ADMIN_GROUPS_DESC'",
                'formtype'    => "'select_multi'",
                'valuetype'   => "'array'",
                'default'     => '$adminGroups',
                'options'     => '$adminGroups',
            ];
            $ret         .= $uxc->getUserModVersionArray(2, $adminGroups, 'config');
			$ret         .= $pc->getPhpCodeUnset('crGroups');
        }
        $keyword      = implode(', ', $this->getKeywords());
        $ret          .= $pc->getPhpCodeCommentLine('Keywords');
        $arrayKeyword = [
            'name'        => "'keywords'",
            'title'       => "'{$language}KEYWORDS'",
            'description' => "'{$language}KEYWORDS_DESC'",
            'formtype'    => "'textbox'",
            'valuetype'   => "'text'",
            'default'     => "'{$moduleDirname}, {$keyword}'",
        ];
        $ret .= $uxc->getUserModVersionArray(2, $arrayKeyword, 'config');
        unset($this->keywords);

        if (1 === $table_uploadimage || 1 === $table_uploadfile) {
            $ret       .= $this->getXoopsVersionSelectSizeMB($moduleDirname);
        }
        if (1 === $table_uploadimage) {
            $ret .= $pc->getPhpCodeCommentLine('Uploads : maxsize of image');
            $maxsize_image    = [
                'name'        => "'maxsize_image'",
                'title'       => "'{$language}MAXSIZE_IMAGE'",
                'description' => "'{$language}MAXSIZE_IMAGE_DESC'",
                'formtype'    => "'select'",
                'valuetype'   => "'int'",
                'default'     => '3145728',
                'options'     => '$optionMaxsize',
            ];
            $ret .= $uxc->getUserModVersionArray(2, $maxsize_image, 'config');
            $ret .= $pc->getPhpCodeCommentLine('Uploads : mimetypes of image');
            $mimetypes_image  = [
                'name'        => "'mimetypes_image'",
                'title'       => "'{$language}MIMETYPES_IMAGE'",
                'description' => "'{$language}MIMETYPES_IMAGE_DESC'",
                'formtype'    => "'select_multi'",
                'valuetype'   => "'array'",
                'default'     => "['image/gif', 'image/jpeg', 'image/png']",
                'options'     => "['bmp' => 'image/bmp','gif' => 'image/gif','pjpeg' => 'image/pjpeg', 'jpeg' => 'image/jpeg','jpg' => 'image/jpg','jpe' => 'image/jpe', 'png' => 'image/png']",
            ];
            $ret .= $uxc->getUserModVersionArray(2, $mimetypes_image, 'config');
            $maxwidth_image   = [
                'name'        => "'maxwidth_image'",
                'title'       => "'{$language}MAXWIDTH_IMAGE'",
                'description' => "'{$language}MAXWIDTH_IMAGE_DESC'",
                'formtype'    => "'textbox'",
                'valuetype'   => "'int'",
                'default'     => '8000',
            ];
            $ret .= $uxc->getUserModVersionArray(2, $maxwidth_image, 'config');
            $maxheight_image   = [
                'name'        => "'maxheight_image'",
                'title'       => "'{$language}MAXHEIGHT_IMAGE'",
                'description' => "'{$language}MAXHEIGHT_IMAGE_DESC'",
                'formtype'    => "'textbox'",
                'valuetype'   => "'int'",
                'default'     => '8000',
            ];
            $ret .= $uxc->getUserModVersionArray(2, $maxheight_image, 'config');
        }
        if (1 === $table_uploadfile) {
            $ret .= $pc->getPhpCodeCommentLine('Uploads : maxsize of file');
            $maxsize_file     = [
                'name'        => "'maxsize_file'",
                'title'       => "'{$language}MAXSIZE_FILE'",
                'description' => "'{$language}MAXSIZE_FILE_DESC'",
                'formtype'    => "'select'",
                'valuetype'   => "'int'",
                'default'     => '3145728',
                'options'     => '$optionMaxsize',
            ];
            $ret .= $uxc->getUserModVersionArray(2, $maxsize_file, 'config');
            $ret .= $pc->getPhpCodeCommentLine('Uploads : mimetypes of file');
            $mimetypes_file   = [
                'name'        => "'mimetypes_file'",
                'title'       => "'{$language}MIMETYPES_FILE'",
                'description' => "'{$language}MIMETYPES_FILE_DESC'",
                'formtype'    => "'select_multi'",
                'valuetype'   => "'array'",
                'default'     => "['application/pdf', 'application/zip', 'text/comma-separated-values', 'text/plain', 'image/gif', 'image/jpeg', 'image/png']",
                'options'     => "['gif' => 'image/gif','pjpeg' => 'image/pjpeg', 'jpeg' => 'image/jpeg','jpg' => 'image/jpg','jpe' => 'image/jpe', 'png' => 'image/png', 'pdf' => 'application/pdf','zip' => 'application/zip','csv' => 'text/comma-separated-values', 'txt' => 'text/plain', 'xml' => 'application/xml', 'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']",
            ];
            $ret .= $uxc->getUserModVersionArray(2, $mimetypes_file, 'config');
        }
        if (1 === $table_admin) {
            $ret .= $pc->getPhpCodeCommentLine('Admin pager');
            $adminPager = [
                'name'        => "'adminpager'",
                'title'       => "'{$language}ADMIN_PAGER'",
                'description' => "'{$language}ADMIN_PAGER_DESC'",
                'formtype'    => "'textbox'",
                'valuetype'   => "'int'",
                'default'     => '10',
            ];
            $ret .= $uxc->getUserModVersionArray(2, $adminPager, 'config');
        }
        if (1 === $table_user) {
            $ret .= $pc->getPhpCodeCommentLine('User pager');
            $userPager = [
                'name'        => "'userpager'",
                'title'       => "'{$language}USER_PAGER'",
                'description' => "'{$language}USER_PAGER_DESC'",
                'formtype'    => "'textbox'",
                'valuetype'   => "'int'",
                'default'     => '10',
            ];
            $ret .= $uxc->getUserModVersionArray(2, $userPager, 'config');
        }
        if (1 === $table_tag) {
            $ret .= $pc->getPhpCodeCommentLine('Use tag');
            $useTag = [
                'name'        => "'usetag'",
                'title'       => "'{$language}USE_TAG'",
                'description' => "'{$language}USE_TAG_DESC'",
                'formtype'    => "'yesno'",
                'valuetype'   => "'int'",
                'default'     => '0',
            ];
            $ret .= $uxc->getUserModVersionArray(2, $useTag, 'config');
        }
        $ret .= $pc->getPhpCodeCommentLine('Number column');
        $numbCol          = [
            'name'        => "'numb_col'",
            'title'       => "'{$language}NUMB_COL'",
            'description' => "'{$language}NUMB_COL_DESC'",
            'formtype'    => "'select'",
            'valuetype'   => "'int'",
            'default'     => '1',
            'options'     => "[1 => '1', 2 => '2', 3 => '3', 4 => '4']",
        ];
        $ret .= $uxc->getUserModVersionArray(2, $numbCol, 'config');

        $ret .= $pc->getPhpCodeCommentLine('Divide by');
        $divideby         = [
            'name'        => "'divideby'",
            'title'       => "'{$language}DIVIDEBY'",
            'description' => "'{$language}DIVIDEBY_DESC'",
            'formtype'    => "'select'",
            'valuetype'   => "'int'",
            'default'     => '1',
            'options'     => "[1 => '1', 2 => '2', 3 => '3', 4 => '4']",
        ];
        $ret .= $uxc->getUserModVersionArray(2, $divideby, 'config');

        $ret .= $pc->getPhpCodeCommentLine('Table type');
        $tableType        = [
            'name'        => "'table_type'",
            'title'       => "'{$language}TABLE_TYPE'",
            'description' => "'{$language}DIVIDEBY_DESC'",
            'formtype'    => "'select'",
            'valuetype'   => "'int'",
            'default'     => "'bordered'",
            'options'     => "['bordered' => 'bordered', 'striped' => 'striped', 'hover' => 'hover', 'condensed' => 'condensed']",
        ];
        $ret              .= $uxc->getUserModVersionArray(2, $tableType, 'config');

        $ret              .= $pc->getPhpCodeCommentLine('Panel by');
        $panelType        = [
            'name'        => "'panel_type'",
            'title'       => "'{$language}PANEL_TYPE'",
            'description' => "'{$language}PANEL_TYPE_DESC'",
            'formtype'    => "'select'",
            'valuetype'   => "'text'",
            'default'     => "'default'",
            'options'     => "['default' => 'default', 'primary' => 'primary', 'success' => 'success', 'info' => 'info', 'warning' => 'warning', 'danger' => 'danger']",
        ];
        $ret              .= $uxc->getUserModVersionArray(2, $panelType, 'config');

        $ret              .= $pc->getPhpCodeCommentLine('Advertise');
        $advertise        = [
            'name'        => "'advertise'",
            'title'       => "'{$language}ADVERTISE'",
            'description' => "'{$language}ADVERTISE_DESC'",
            'formtype'    => "'textarea'",
            'valuetype'   => "'text'",
            'default'     => "''",
        ];
        $ret              .= $uxc->getUserModVersionArray(2, $advertise, 'config');

        $ret              .= $pc->getPhpCodeCommentLine('Bookmarks');
        $bookmarks        = [
            'name'        => "'bookmarks'",
            'title'       => "'{$language}BOOKMARKS'",
            'description' => "'{$language}BOOKMARKS_DESC'",
            'formtype'    => "'yesno'",
            'valuetype'   => "'int'",
            'default'     => '0',
        ];
        $ret              .= $uxc->getUserModVersionArray(2, $bookmarks, 'config');

        /*
         * removed, as there are no system templates in xoops core for fb or disqus comments
         * modulebuilder currently is also not creatings tpl files for this
        $ret              .= $pc->getPhpCodeCommentLine('Facebook Comments');
        $facebookComments = [
            'name'        => "'facebook_comments'",
            'title'       => "'{$language}FACEBOOK_COMMENTS'",
            'description' => "'{$language}FACEBOOK_COMMENTS_DESC'",
            'formtype'    => "'yesno'",
            'valuetype'   => "'int'",
            'default'     => '0',
        ];
        $ret              .= $uxc->getUserModVersion(3, $facebookComments, 'config', '$c');
        $ret              .= $this->getSimpleString('++$c;');
        $ret              .= $pc->getPhpCodeCommentLine('Disqus Comments');
        $disqusComments   = [
            'name'        => "'disqus_comments'",
            'title'       => "'{$language}DISQUS_COMMENTS'",
            'description' => "'{$language}DISQUS_COMMENTS_DESC'",
            'formtype'    => "'yesno'",
            'valuetype'   => "'int'",
            'default'     => '0',
        ];
        $ret              .= $uxc->getUserModVersion(3, $disqusComments, 'config', '$c');
        $ret              .= $this->getSimpleString('++$c;');
        */

        $ret              .= $pc->getPhpCodeCommentLine('Make Sample button visible?');
        $maintainedby     = [
            'name'        => "'displaySampleButton'",
            'title'       => "'CO_' . \$moduleDirNameUpper . '_' . 'SHOW_SAMPLE_BUTTON'",
            'description' => "'CO_' . \$moduleDirNameUpper . '_' . 'SHOW_SAMPLE_BUTTON_DESC'",
            'formtype'    => "'yesno'",
            'valuetype'   => "'int'",
            'default'     => '1',
        ];
        $ret              .= $uxc->getUserModVersionArray(2, $maintainedby, 'config');

        $ret              .= $pc->getPhpCodeCommentLine('Maintained by');
        $maintainedby     = [
            'name'        => "'maintainedby'",
            'title'       => "'{$language}MAINTAINEDBY'",
            'description' => "'{$language}MAINTAINEDBY_DESC'",
            'formtype'    => "'textbox'",
            'valuetype'   => "'text'",
            'default'     => "'{$module->getVar('mod_support_url')}'",
        ];
        $ret              .= $uxc->getUserModVersionArray(2, $maintainedby, 'config');

        return $ret;
    }

    /**
     * @private function getNotificationsType
     * @param $language
     * @param $type
     * @param $tableName
     * @param $notifyFile
     * @param $item
     * @param $typeOfNotify
     *
     * @return string
     */
    private function getNotificationsType($language, $type, $tableName, $notifyFile, $item, $typeOfNotify)
    {
        $pc              = Modulebuilder\Files\CreatePhpCode::getInstance();
        $uxc             = UserXoopsCode::getInstance();
        $stuTableName    = mb_strtoupper($tableName);
        $stuTypeOfNotify = mb_strtoupper($typeOfNotify);
        $notifyFile      = explode(', ', $notifyFile);
        $notifyFile      = implode(', ', $notifyFile);
        $ret             = '';
        switch ($type) {
            case 'category':
                $ret      .= $pc->getPhpCodeCommentLine('Category Notify');
                $category = [
                    'name'             => "'category'",
                    'title'            => "'{$language}NOTIFY_{$stuTableName}'",
                    'description'      => "''",
                    'subscribe_from'   => "['index.php',{$notifyFile}]",
                    'item_name'        => "'{$item}'",
                    "'allow_bookmark'" => '1',
                ];
                $ret      .= $uxc->getUserModVersionArray(2, $category, 'notification', "'{$type}'");
                break;
            case 'event':
                $ret   .= $pc->getPhpCodeCommentLine('Event Notify');
                $event = [
                    'name'          => "'{$typeOfNotify}'",
                    'category'      => "'{$tableName}'",
                    'admin_only'    => '1',
                    "'title'"       => "'{$language}NOTIFY_{$stuTableName}_{$stuTypeOfNotify}'",
                    'caption'       => "'{$language}NOTIFY_{$stuTableName}_{$stuTypeOfNotify}_CAPTION'",
                    'description'   => "''",
                    'mail_template' => "'notify_{$tableName}_{$typeOfNotify}'",
                    'mail_subject'  => "'{$language}NOTIFY_{$stuTableName}_{$stuTypeOfNotify}_SUBJECT'",
                ];
                $ret   .= $uxc->getUserModVersionArray(2, $event, 'notification', "'{$type}'");
                break;
        }

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
        $pc  = Modulebuilder\Files\CreatePhpCode::getInstance();
        $uxc = UserXoopsCode::getInstance();

        $moduleDirname = $module->getVar('mod_dirname');
        $ret           = $this->getDashComment('Notifications');
        $ret           .= $uxc->getUserModVersionText(1, 1, 'hasNotification');
        $notifications = ['lookup_file' => "'include/notification.inc.php'", 'lookup_func' => "'{$moduleDirname}_notify_iteminfo'"];
        $ret           .= $uxc->getUserModVersionArray(1, $notifications, 'notification');

        $notifyFiles       = [];
        $tables            = $this->getTableTables($module->getVar('mod_id'), 'table_order');
        $tableCategory     = [];
        $tableBroken       = [];
        $tableComments     = [];
        $tableSubmit       = [];
        $tableId           = null;
        $tableMid          = null;
        $notifyCategory    = '';
        $notifyEventGlobal = $pc->getPhpCodeCommentLine('Global events notification');
        $notifyEventTable  = $pc->getPhpCodeCommentLine('Event notifications for items');

        //global events
        $notifyEventGlobal .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'global_new', 'global', 0, 'global_new', 'global_new_notify');
        $notifyEventGlobal .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'global_modify', 'global', 0, 'global_modify', 'global_modify_notify');
        $notifyEventGlobal .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'global_delete', 'global', 0, 'global_delete', 'global_delete_notify');
        $notifyEventGlobal .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'global_approve', 'global', 0, 'global_approve', 'global_approve_notify');
        foreach (array_keys($tables) as $t) {
            $tableBroken[]   = $tables[$t]->getVar('table_broken');
            $tableComments[] = $tables[$t]->getVar('table_comments');
        }
        if (in_array(1, $tableBroken)) {
            $notifyEventGlobal .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'global_broken', 'global', 0, 'global_broken', 'global_broken_notify');
        }
        if (in_array(1, $tableComments)) {
            $notifyEventGlobal .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'global_comment', 'global', 0, 'global_comment', 'global_comment_notify');
        }

        foreach (array_keys($tables) as $t) {
            $tableId         = $tables[$t]->getVar('table_id');
            $tableMid        = $tables[$t]->getVar('table_mid');
            $tableName       = $tables[$t]->getVar('table_name');
            $tableSoleName   = $tables[$t]->getVar('table_solename');
            $tableCategory[] = $tables[$t]->getVar('table_category');
            $tableSubmit[]   = $tables[$t]->getVar('table_submit');
            $fields      = $this->getTableFields($tableMid, $tableId);
            $fieldId     = 0;
            foreach (array_keys($fields) as $f) {
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
        $ret .= $pc->getPhpCodeCommentLine('Categories of notification');
        $ret .= $this->getXoopsVersionNotificationGlobal($language, 'category', 'global', 'global', $notifyFiles);

        //$ret .= $this->getXoopsVersionNotificationCategory($language, 'category', 'category', 'category', $notifyFiles, $fieldParent, '1');

        $ret .= $notifyCategory . $notifyEventGlobal . $notifyEventTable;

        /*
        $num = 1;
        if (in_array(1, $tableCategory)) {
            $ret .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'new_category', 'global', 0, 'global_new_category', 'global_newcategory_notify');
            ++$num;
        }
        $ret .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'modify', 'global', 1, 'global_modify', 'global_' . 'modify_notify');
        if (in_array(1, $tableBroken)) {
            ++$num;
            $ret .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'broken', 'global', 1, 'global_broken', 'global_' . 'broken_notify');
        }
        if (in_array(1, $tableSubmit)) {
            ++$num;
            $ret .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'submit', 'global', 1, 'global_submit', 'global_' . 'submit_notify');
        }
        ++$num;
        $ret .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'new_' . $tableSoleName, 'global', 0, 'global_new',  'global_new' . $tableSoleName . '_notify');
        if (in_array(1, $tableCategory)) {
            ++$num;
            $ret .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'submit', 'category', 1, 'category_submit', 'category_' . $tableSoleName . 'submit_notify');
            ++$num;
            $ret .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'new_category', 'category', 0, 'category', 'category_new' . $tableSoleName . '_notify');
        }
        ++$num;
        $ret .= $this->getXoopsVersionNotificationCodeComplete($language, 'event', 'approve', $tableSoleName, 1, $tableSoleName, $tableSoleName . '_approve_notify');
        unset($num);
        */

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
        $pc          = Modulebuilder\Files\CreatePhpCode::getInstance();
        $uxc         = UserXoopsCode::getInstance();
        $title       = mb_strtoupper($title);
        $implodeFrom = implode(".php', '", $from);
        $ret         = $pc->getPhpCodeCommentLine('Global Notify');
        $global      = [
            'name'           => "'{$name}'",
            'title'          => "{$language}NOTIFY_{$title}",
            'description'    => "''",
            'subscribe_from' => "['index.php', '{$implodeFrom}.php']",
        ];
        $ret         .= $uxc->getUserModVersionArray(3, $global, 'notification', "'{$type}'");

        return $ret;
    }

    /**
     * @private function getXoopsVersionNotificationCategory
     * @param $language
     * @param $type
     * @param $name
     * @param $title
     * @param $file
     * @param $item
     * @param $allow
     * @return string
     */
    /*
    private function getXoopsVersionNotificationCategory($language, $type, $name, $title, $file, $item, $allow)
    {
        $pc     = Modulebuilder\Files\CreatePhpCode::getInstance();
        $uxc    = UserXoopsCode::getInstance();
        $title  = mb_strtoupper($title);
        $impFile = implode(".php', '", $file);
        $ret    = $pc->getPhpCodeCommentLine('Category Notify');
        $global = [
            'name'           => "'{$name}'",
            'title'          => "{$language}{$title}_NOTIFY",
            'description'    => "{$language}{$title}_NOTIFY_DESC",
            'subscribe_from' => "['{$impFile}.php']",
            'item_name'      => "'{$item}'",
            'allow_bookmark' => (string)$allow,
        ];
        $ret .= $uxc->getUserModVersionArray(3, $global, 'notification', "'{$type}'");

        return $ret;
    }
    */

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
        $pc       = Modulebuilder\Files\CreatePhpCode::getInstance();
        $uxc      = UserXoopsCode::getInstance();
        $stuTitle = mb_strtoupper($title);
        $ucfTitle = ucfirst($title);
        $ret      = $pc->getPhpCodeCommentLine($ucfTitle . ' Notify');
        $table    = [
            'name'           => "'{$name}'",
            'title'          => "{$language}NOTIFY_{$stuTitle}",
            'description'    => "''",
            'subscribe_from' => "'{$file}.php'",
            'item_name'      => "'{$item}'",
            'allow_bookmark' => (string)$allow,
        ];
        $ret .= $uxc->getUserModVersionArray(3, $table, 'notification', "'{$type}'");

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
        $pc       = Modulebuilder\Files\CreatePhpCode::getInstance();
        $uxc      = UserXoopsCode::getInstance();
        $title    = mb_strtoupper($title);
        $ucfTitle = ucfirst($title);
        $ret      = $pc->getPhpCodeCommentLine($ucfTitle . ' Notify');
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
        $ret .= $uxc->getUserModVersionArray(3, $event, 'notification', "'{$type}'");

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
        $pc = Modulebuilder\Files\CreatePhpCode::getInstance();
        $xc  = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $ucModuleDirname       = mb_strtoupper($moduleDirname);

        $ret  = $pc->getPhpCodeCommentLine('create increment steps for file size');
        $ret  .= $pc->getPhpCodeIncludeDir("__DIR__ . '/include/xoops_version.inc.php'", '',true,true);
        $ret  .= $xc->getXcEqualsOperator('$iniPostMaxSize      ', "{$moduleDirname}ReturnBytes(ini_get('post_max_size'))");
        $ret  .= $xc->getXcEqualsOperator('$iniUploadMaxFileSize', "{$moduleDirname}ReturnBytes(ini_get('upload_max_filesize'))");
        $ret  .= $xc->getXcEqualsOperator('$maxSize             ', "min(\$iniPostMaxSize, \$iniUploadMaxFileSize)");
        $cond = $xc->getXcEqualsOperator('$increment', '500', null, $t . "\t");
        $ret  .= $pc->getPhpCodeConditions('$maxSize', ' > ', '10000 * 1048576', $cond, false, $t);
        $cond = $xc->getXcEqualsOperator('$increment', '200', null, $t . "\t");
        $ret  .= $pc->getPhpCodeConditions('$maxSize', ' <= ', '10000 * 1048576', $cond, false, $t);
        $cond  = $xc->getXcEqualsOperator('$increment', '100', null, $t . "\t");
        $ret   .= $pc->getPhpCodeConditions('$maxSize', ' <= ', '5000 * 1048576', $cond, false, $t);
        $cond  = $xc->getXcEqualsOperator('$increment', '50', null, $t . "\t");
        $ret   .= $pc->getPhpCodeConditions('$maxSize', ' <= ', '2500 * 1048576', $cond, false, $t);
        $cond  = $xc->getXcEqualsOperator('$increment', '10', null, $t . "\t");
        $ret   .= $pc->getPhpCodeConditions('$maxSize', ' <= ', '1000 * 1048576', $cond, false, $t);
        $cond  = $xc->getXcEqualsOperator('$increment', '5', null, $t . "\t");
        $ret   .= $pc->getPhpCodeConditions('$maxSize', ' <= ', '500 * 1048576', $cond, false, $t);
        $cond  = $xc->getXcEqualsOperator('$increment', '2', null, $t . "\t");
        $ret   .= $pc->getPhpCodeConditions('$maxSize', ' <= ', '100 * 1048576', $cond, false, $t);
        $cond  = $xc->getXcEqualsOperator('$increment', '1', null, $t . "\t");
        $ret   .= $pc->getPhpCodeConditions('$maxSize', ' <= ', '50 * 1048576', $cond, false, $t);
        $cond  = $xc->getXcEqualsOperator('$increment', '0.5', null, $t . "\t");
        $ret   .= $pc->getPhpCodeConditions('$maxSize', ' <= ', '25 * 1048576', $cond, false, $t);
        $ret   .= $xc->getXcEqualsOperator('$optionMaxsize', '[]');
        $ret   .= $xc->getXcEqualsOperator('$i', '$increment');
        $while = $xc->getXcEqualsOperator("\$optionMaxsize[\$i . ' ' . _MI_{$ucModuleDirname}_SIZE_MB]", '$i * 1048576', null, $t . "\t");
        $while .= $xc->getXcEqualsOperator('$i', '$increment', '+',$t . "\t");
        $ret   .= $pc->getPhpCodeWhile('i * 1048576', $while, '$maxSize', ' <= ');

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
        $content       .= $this->getXoopsVersionMySQL($moduleDirname, $table, $tables);
        $tableSearch        = [];
        $tableComments      = [];
        $tableComment       = null;
        $tableSubmenu       = [];
        $tableBlocks        = [];
        $tableNotifications = [];
        foreach (array_keys($tables) as $t) {
            $tableSearch[]        = $tables[$t]->getVar('table_search');
            $tableComments[]      = $tables[$t]->getVar('table_comments');
            $tableSubmenu[]       = $tables[$t]->getVar('table_submenu');
            $tableBlocks[]        = $tables[$t]->getVar('table_blocks');
            $tableNotifications[] = $tables[$t]->getVar('table_notifications');
        }
        if (in_array(1, $tableSearch)) {
            $content .= $this->getXoopsVersionSearch($moduleDirname);
        }
        if (in_array(1, $tableComments)) {
            $content .= $this->getXoopsVersionComments($moduleDirname, $tables);
        }
        if (in_array(1, $tableSubmenu)) {
            $content .= $this->getXoopsVersionSubmenu($language, $tables);
        }
        if (in_array(1, $tableBlocks)) {
            $content .= $this->getXoopsVersionBlocks($moduleDirname, $tables, $language);
        }
        $content .= $this->getXoopsVersionConfig($module, $tables, $language);
        if (in_array(1, $tableNotifications)) {
            $content .= $this->getXoopsVersionNotifications($module, $language);
        }
        $this->create($moduleDirname, '/', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
