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
 * Class LanguageModinfo.
 */
class LanguageModinfo extends Files\CreateFile
{
    /**
     * @var mixed
     */
    private $ld = null;

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
        $this->ld = LanguageDefines::getInstance();
        $this->pc = Modulebuilder\Files\CreatePhpCode::getInstance();
    }

    /**
     * @static function getInstance
     * @param null
     * @return LanguageModinfo
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
     *
     * @param $module
     * @param $table
     * @param $filename
     *
     * @return null
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);

        return null;
    }

    /**
     * @private function getLanguageMain
     *
     * @param $language
     * @param $module
     *
     * @return string
     */
    private function getLanguageMain($language, $module)
    {
        $ret = $this->ld->getBlankLine();
        $ret .= $this->pc->getPhpCodeIncludeDir("'common.php'",'', true, true, 'include');
        $ret .= $this->ld->getBlankLine();
        $ret .= $this->ld->getAboveHeadDefines('Admin Main');
        $ret .= $this->ld->getDefine($language, 'NAME', (string)$module->getVar('mod_name'));
        $ret .= $this->ld->getDefine($language, 'DESC', (string)$module->getVar('mod_description'));

        return $ret;
    }

    /**
     * @private function getLanguageMenu
     *
     * @param $module
     * @param $language
     *
     * @return string
     */
    private function getLanguageMenu($module, $language)
    {
        $tables           = $this->getTableTables($module->getVar('mod_id'), 'table_order');
        $menu             = 1;
        $ret              = $this->ld->getAboveHeadDefines('Admin Menu');
        $ret              .= $this->ld->getDefine($language, "ADMENU{$menu}", 'Dashboard');
        $tablePermissions = [];
        $tableBroken      = [];
        foreach (\array_keys($tables) as $i) {
            ++$menu;
            $tablePermissions[] = $tables[$i]->getVar('table_permissions');
            $tableBroken[]      = $tables[$i]->getVar('table_broken');
            $ucfTableName       = \ucfirst($tables[$i]->getVar('table_name'));
            $ret                .= $this->ld->getDefine($language, "ADMENU{$menu}", $ucfTableName);
        }
        if (\in_array(1, $tableBroken)) {
            ++$menu;
            $ret    .= $this->ld->getDefine($language, "ADMENU{$menu}", 'Broken items');
        }
        if (\in_array(1, $tablePermissions)) {
            ++$menu;
            $ret .= $this->ld->getDefine($language, "ADMENU{$menu}", 'Permissions');
        }
        ++$menu;
        $ret .= $this->ld->getDefine($language, "ADMENU{$menu}", 'Feedback');
        $ret .= $this->ld->getDefine($language, 'ABOUT', 'About');
        unset($menu, $tablePermissions);

        return $ret;
    }

    /**
     * @private function getLanguageAdmin
     * @param $language
     *
     * @return string
     */
    private function getLanguageAdmin($language)
    {
        $ret = $this->ld->getAboveHeadDefines('Admin Nav');
        $ret .= $this->ld->getDefine($language, 'ADMIN_PAGER', 'Admin pager');
        $ret .= $this->ld->getDefine($language, 'ADMIN_PAGER_DESC', 'Admin per page list');

        return $ret;
    }

    /**
     * @private function getLanguageSubmenu
     * @param       $language
     * @param array $tables
     *
     * @return string
     */
    private function getLanguageSubmenu($language, $tables)
    {
        $ret         = $this->ld->getAboveDefines('Submenu');
        $ret         .= $this->ld->getDefine($language, 'SMNAME1', 'Index page');
        $i           = 1;
        $tableSubmit = [];
        $tableSearch = [];
        foreach (\array_keys($tables) as $t) {
            $tableName     = $tables[$t]->getVar('table_name');
            $tableSearch[] = $tables[$t]->getVar('table_search');
            $ucfTablename  = \ucfirst(mb_strtolower($tableName));
            if (1 == $tables[$t]->getVar('table_submenu')) {
                $ret .= $this->ld->getDefine($language, "SMNAME{$i}", $ucfTablename);
            }
            ++$i;
            if (1 == $tables[$t]->getVar('table_submit')) {
                $ret .= $this->ld->getDefine($language, "SMNAME{$i}", 'Submit ' . $ucfTablename);
                ++$i;
            }
        }

        if (\in_array(1, $tableSearch)) {
            $ret .= $this->ld->getDefine($language, "SMNAME{$i}", 'Search');
        }
        unset($i, $tableSubmit);

        return $ret;
    }

    /**
     * @private function getLanguageBlocks
     * @param       $language
     * @param array $tables
     *
     * @return string
     */
    private function getLanguageBlocks($tables, $language)
    {
        $ret = $this->ld->getAboveDefines('Blocks');
        foreach (\array_keys($tables) as $i) {
            if (1 == $tables[$i]->getVar('table_blocks')) {
                $tableName        = $tables[$i]->getVar('table_name');
                $stuTableName     = mb_strtoupper($tableName);
                $tableSoleName    = $tables[$i]->getVar('table_solename');
                $stuTableSoleName = mb_strtoupper($tableSoleName);
                $ucfTableName     = \ucfirst($tableName);
                $ucfTableSoleName = \ucfirst($stuTableSoleName);
                $ret .= $this->ld->getDefine($language, "{$stuTableName}_BLOCK", "{$ucfTableName} block");
                $ret .= $this->ld->getDefine($language, "{$stuTableName}_BLOCK_DESC", "{$ucfTableName} block description");
                if (1 == $tables[$i]->getVar('table_category')) {
                    $ret .= $this->ld->getDefine($language, "{$stuTableName}_BLOCK_{$stuTableSoleName}", "{$ucfTableName} block {$ucfTableSoleName}");
                    $ret .= $this->ld->getDefine($language, "{$stuTableName}_BLOCK_{$stuTableSoleName}_DESC", "{$ucfTableName} block {$ucfTableSoleName} description");
                } else {
                    $ret .= $this->ld->getDefine($language, "{$stuTableName}_BLOCK_{$stuTableSoleName}", "{$ucfTableName} block  {$ucfTableSoleName}");
                    $ret .= $this->ld->getDefine($language, "{$stuTableName}_BLOCK_{$stuTableSoleName}_DESC", "{$ucfTableName} block  {$ucfTableSoleName} description");
                    $ret .= $this->ld->getDefine($language, "{$stuTableName}_BLOCK_LAST", "{$ucfTableName} block last");
                    $ret .= $this->ld->getDefine($language, "{$stuTableName}_BLOCK_LAST_DESC", "{$ucfTableName} block last description");
                    $ret .= $this->ld->getDefine($language, "{$stuTableName}_BLOCK_NEW", "{$ucfTableName} block new");
                    $ret .= $this->ld->getDefine($language, "{$stuTableName}_BLOCK_NEW_DESC", "{$ucfTableName} block new description");
                    $ret .= $this->ld->getDefine($language, "{$stuTableName}_BLOCK_HITS", "{$ucfTableName} block hits");
                    $ret .= $this->ld->getDefine($language, "{$stuTableName}_BLOCK_HITS_DESC", "{$ucfTableName} block hits description");
                    $ret .= $this->ld->getDefine($language, "{$stuTableName}_BLOCK_TOP", "{$ucfTableName} block top");
                    $ret .= $this->ld->getDefine($language, "{$stuTableName}_BLOCK_TOP_DESC", "{$ucfTableName} block top description");
                    $ret .= $this->ld->getDefine($language, "{$stuTableName}_BLOCK_RANDOM", "{$ucfTableName} block random");
                    $ret .= $this->ld->getDefine($language, "{$stuTableName}_BLOCK_RANDOM_DESC", "{$ucfTableName} block random description");
                }
            }
        }

        return $ret;
    }

    /**
     * @private function getLanguageUser
     * @param $language
     *
     * @return string
     */
    private function getLanguageUser($language)
    {
        $ret = $this->ld->getAboveDefines('User');
        $ret .= $this->ld->getDefine($language, 'USER_PAGER', 'User pager');
        $ret .= $this->ld->getDefine($language, 'USER_PAGER_DESC', 'User per page list');

        return $ret;
    }

    /**
     * @private function getLanguageConfig
     * @param $language
     * @param $tables
     *
     * @return string
     */
    private function getLanguageConfig($language, $tables)
    {
        $ret         = $this->ld->getAboveDefines('Config');
        $fieldImage  = false;
        $fieldFile   = false;
        $useTag      = false;
        $fieldEditor = false;
        // $usePermissions = false;
        foreach (\array_keys($tables) as $i) {
            $fields = $this->getTableFields($tables[$i]->getVar('table_mid'), $tables[$i]->getVar('table_id'));
            foreach (\array_keys($fields) as $f) {
                $fieldElement = $fields[$f]->getVar('field_element');
                if (3 == $fieldElement) {
                    $fieldEditor = true;
                }
                if (4 == $fieldElement) {
                    $fieldEditor = true;
                }
                if (10 == $fieldElement) {
                    $fieldImage = true;
                }
                if (13 == $fieldElement) {
                    $fieldImage = true;
                }
				if (14 == $fieldElement) {
                    $fieldFile = true;
                }
            }
            if (0 != $tables[$i]->getVar('table_tag')) {
                $useTag = true;
            }
        }
        if ($fieldEditor) {
            $ret .= $this->ld->getDefine($language, 'EDITOR_ADMIN', 'Editor admin');
            $ret .= $this->ld->getDefine($language, 'EDITOR_ADMIN_DESC', 'Select the editor which should be used in admin area for text area fields');
            $ret .= $this->ld->getDefine($language, 'EDITOR_USER', 'Editor user');
            $ret .= $this->ld->getDefine($language, 'EDITOR_USER_DESC', 'Select the editor which should be used in user area for text area fields');
            $ret .= $this->ld->getDefine($language, 'EDITOR_MAXCHAR', 'Text max characters');
            $ret .= $this->ld->getDefine($language, 'EDITOR_MAXCHAR_DESC', 'Max characters for showing text of a textarea or editor field in admin area');
        }
        $ret .= $this->ld->getDefine($language, 'KEYWORDS', 'Keywords');
        $ret .= $this->ld->getDefine($language, 'KEYWORDS_DESC', 'Insert here the keywords (separate by comma)');

        if ($fieldImage || $fieldFile) {
            $ret .= $this->ld->getDefine($language, 'SIZE_MB', 'MB');
        }
        if ($fieldImage) {
            $ret .= $this->ld->getDefine($language, 'MAXSIZE_IMAGE', 'Max size image');
            $ret .= $this->ld->getDefine($language, 'MAXSIZE_IMAGE_DESC', 'Define the max size for uploading images');
            $ret .= $this->ld->getDefine($language, 'MIMETYPES_IMAGE', 'Mime types image');
            $ret .= $this->ld->getDefine($language, 'MIMETYPES_IMAGE_DESC', 'Define the allowed mime types for uploading images');
            $ret .= $this->ld->getDefine($language, 'MAXWIDTH_IMAGE', 'Max width image');
            $ret .= $this->ld->getDefine($language, 'MAXWIDTH_IMAGE_DESC', 'Set the max width which is allowed for uploading images (in pixel)<br>0 means that images keep original size<br>If original image is smaller the image will be not enlarged');
            $ret .= $this->ld->getDefine($language, 'MAXHEIGHT_IMAGE', 'Max height image');
            $ret .= $this->ld->getDefine($language, 'MAXHEIGHT_IMAGE_DESC', 'Set the max height which is allowed for uploading images (in pixel)<br>0 means that images keep original size<br>If original image is smaller the image will be not enlarged');
        }
		if ($fieldFile) {
            $ret .= $this->ld->getDefine($language, 'MAXSIZE_FILE', 'Max size file');
            $ret .= $this->ld->getDefine($language, 'MAXSIZE_FILE_DESC', 'Define the max size for uploading files');
            $ret .= $this->ld->getDefine($language, 'MIMETYPES_FILE', 'Mime types file');
            $ret .= $this->ld->getDefine($language, 'MIMETYPES_FILE_DESC', 'Define the allowed mime types for uploading files');
        }
        if ($useTag) {
            $ret .= $this->ld->getDefine($language, 'USE_TAG', 'Use TAG');
            $ret .= $this->ld->getDefine($language, 'USE_TAG_DESC', 'If you use tag module, check this option to yes');
        }
        $getDefinesConf = [
            'NUMB_COL'               => 'Number Columns',
            'NUMB_COL_DESC'          => 'Number Columns to View.',
            'DIVIDEBY'               => 'Divide By',
            'DIVIDEBY_DESC'          => 'Divide by columns number.',
            'TABLE_TYPE'             => 'Table Type',
            'TABLE_TYPE_DESC'        => 'Table Type is the bootstrap html table.',
            'PANEL_TYPE'             => 'Panel Type',
            'PANEL_TYPE_DESC'        => 'Panel Type is the bootstrap html div.',
            'IDPAYPAL'               => 'Paypal ID',
            'IDPAYPAL_DESC'          => 'Insert here your PayPal ID for donactions.',
            'ADVERTISE'              => 'Advertisement Code',
            'ADVERTISE_DESC'         => 'Insert here the advertisement code',
            'MAINTAINEDBY'           => 'Maintained By',
            'MAINTAINEDBY_DESC'      => 'Allow url of support site or community',
            'BOOKMARKS'              => 'Social Bookmarks',
            'BOOKMARKS_DESC'         => 'Show Social Bookmarks in the single page',
            'FACEBOOK_COMMENTS'      => 'Facebook comments',
            'FACEBOOK_COMMENTS_DESC' => 'Allow Facebook comments in the single page',
            'DISQUS_COMMENTS'        => 'Disqus comments',
            'DISQUS_COMMENTS_DESC'   => 'Allow Disqus comments in the single page',
        ];
        foreach ($getDefinesConf as $defc => $descc) {
            $ret .= $this->ld->getDefine($language, $defc, $descc);
        }

        return $ret;
    }

    /**
     * @private function getLanguageNotificationsGlobal
     * @param       $language
     * @param $tableBroken
     * @param $tableComment
     * @return string
     */
    private function getLanguageNotificationsGlobal($language, $tableBroken, $tableComment)
    {
        $ret              = $this->ld->getAboveDefines('Global notifications');
        $getDefinesNotif  = [
            'NOTIFY_GLOBAL'                  => 'Global notification',
            'NOTIFY_GLOBAL_NEW'              => 'Any new item',
            'NOTIFY_GLOBAL_NEW_CAPTION'      => 'Notify me about any new item',
            'NOTIFY_GLOBAL_NEW_SUBJECT'      => 'Notification about new item',
            'NOTIFY_GLOBAL_MODIFY'           => 'Any modified item',
            'NOTIFY_GLOBAL_MODIFY_CAPTION'   => 'Notify me about any item modification',
            'NOTIFY_GLOBAL_MODIFY_SUBJECT'   => 'Notification about modification',
            'NOTIFY_GLOBAL_DELETE'           => 'Any deleted item',
            'NOTIFY_GLOBAL_DELETE_CAPTION'   => 'Notify me about any deleted item',
            'NOTIFY_GLOBAL_DELETE_SUBJECT'   => 'Notification about deleted item',
            'NOTIFY_GLOBAL_APPROVE'          => 'Any item to approve',
            'NOTIFY_GLOBAL_APPROVE_CAPTION'  => 'Notify me about any item waiting for approvement',
            'NOTIFY_GLOBAL_APPROVE_SUBJECT'  => 'Notification about item waiting for approvement',
            //'CATEGORY_NOTIFY'                => 'Category notification',
            //'CATEGORY_NOTIFY_DESC'           => 'Category notification desc',
            //'CATEGORY_NOTIFY_CAPTION'        => 'Category notification caption',
            //'CATEGORY_NOTIFY_SUBJECT'        => 'Category notification Subject',
            //'CATEGORY_SUBMIT_NOTIFY'         => 'Category submit notification',
            //'CATEGORY_SUBMIT_NOTIFY_CAPTION' => 'Category submit notification caption',
            //'CATEGORY_SUBMIT_NOTIFY_DESC'    => 'Category submit notification desc',
            //'CATEGORY_SUBMIT_NOTIFY_SUBJECT' => 'Category submit notification subject',
        ];
        if ($tableBroken) {
            $getDefinesNotif['NOTIFY_GLOBAL_BROKEN']         = 'Any broken item';
            $getDefinesNotif['NOTIFY_GLOBAL_BROKEN_CAPTION'] = 'Notify me about any broken item';
            $getDefinesNotif['NOTIFY_GLOBAL_BROKEN_SUBJECT'] = 'Notification about broken item';
        }
        if ($tableComment) {
            $getDefinesNotif['NOTIFY_GLOBAL_COMMENT']         = 'Any comments';
            $getDefinesNotif['NOTIFY_GLOBAL_COMMENT_CAPTION'] = 'Notify me about any comment';
            $getDefinesNotif['NOTIFY_GLOBAL_COMMENT_SUBJECT'] = 'Notification about any comment';
        }
        foreach ($getDefinesNotif as $defn => $descn) {
            $ret .= $this->ld->getDefine($language, $defn, $descn);
        }

        return $ret;
    }

    /**
     * @private function getLanguageNotificationsTable
     * @param       $language
     * @param $tableName
     * @param mixed $tableSoleName
     *
     * @param $tableBroken
     * @param $tableComment
     * @return string
     */
    private function getLanguageNotificationsTable($language, $tableName, $tableSoleName, $tableBroken, $tableComment)
    {
        $stuTableSoleName = mb_strtoupper($tableSoleName);
        $ucfTableSoleName = \ucfirst($tableSoleName);
		$ret              = $this->ld->getAboveDefines($ucfTableSoleName . ' notifications');
        $getDefinesNotif  = [
            'NOTIFY_' . $stuTableSoleName                       => $ucfTableSoleName . ' notification',
            'NOTIFY_' . $stuTableSoleName . '_MODIFY'           => "{$ucfTableSoleName} modification",
            'NOTIFY_' . $stuTableSoleName . '_MODIFY_CAPTION'   => "Notify me about {$tableSoleName} modification",
            'NOTIFY_' . $stuTableSoleName . '_MODIFY_SUBJECT'   => "Notification about modification",
            'NOTIFY_' . $stuTableSoleName . '_DELETE'           => "{$ucfTableSoleName} deleted",
            'NOTIFY_' . $stuTableSoleName . '_DELETE_CAPTION'   => "Notify me about deleted {$tableName}",
            'NOTIFY_' . $stuTableSoleName . '_DELETE_SUBJECT'   => "Notification delete {$tableSoleName}",
            'NOTIFY_' . $stuTableSoleName . '_APPROVE'          => "{$ucfTableSoleName} approve",
            'NOTIFY_' . $stuTableSoleName . '_APPROVE_CAPTION'  => "Notify me about {$tableName} waiting for approvement",
            'NOTIFY_' . $stuTableSoleName . '_APPROVE_SUBJECT'  => "Notification {$tableSoleName} waiting for approvement",
        ];
        if (1 == $tableBroken) {
            $getDefinesNotif['NOTIFY_' . $stuTableSoleName . '_BROKEN']         = "{$ucfTableSoleName} broken";
            $getDefinesNotif['NOTIFY_' . $stuTableSoleName . '_BROKEN_CAPTION'] = "Notify me about broken {$tableSoleName}";
            $getDefinesNotif['NOTIFY_' . $stuTableSoleName . '_BROKEN_SUBJECT'] = "Notification about broken {$tableSoleName}";
        }
        if (1 == $tableComment) {
            $getDefinesNotif['NOTIFY_' . $stuTableSoleName . '_COMMENT']         = "{$ucfTableSoleName} comment";
            $getDefinesNotif['NOTIFY_' . $stuTableSoleName . '_COMMENT_CAPTION'] = "Notify me about comments for {$tableSoleName}";
            $getDefinesNotif['NOTIFY_' . $stuTableSoleName . '_COMMENT_SUBJECT'] = "Notification about comments for {$tableSoleName}";
        }
        foreach ($getDefinesNotif as $defn => $descn) {
            $ret .= $this->ld->getDefine($language, $defn, $descn);
        }

        return $ret;
    }

    /**
     * @private function getLanguagePermissionsGroups
     * @param $language
     *
     * @return string
     */
    private function getLanguagePermissionsGroups($language)
    {
        $ret = $this->ld->getAboveDefines('Permissions Groups');
        $ret .= $this->ld->getDefine($language, 'GROUPS', 'Groups access');
        $ret .= $this->ld->getDefine($language, 'GROUPS_DESC', 'Select general access permission for groups.');
        $ret .= $this->ld->getDefine($language, 'ADMIN_GROUPS', 'Admin Group Permissions');
        $ret .= $this->ld->getDefine($language, 'ADMIN_GROUPS_DESC', 'Which groups have access to tools and permissions page');
        $ret .= $this->ld->getDefine($language, 'UPLOAD_GROUPS', 'Upload Group Permissions');
        $ret .= $this->ld->getDefine($language, 'UPLOAD_GROUPS_DESC', 'Which groups have permissions to upload files');

        return $ret;
    }


    /**
     * @private function getLanguagePermissionsGroups
     * @param $language
     *
     * @return string
     */
    private function getLanguageRatingbars($language)
    {
        $ret = $this->ld->getAboveDefines('Rating bars');
        $ret .= $this->ld->getDefine($language, 'RATINGBAR_GROUPS', 'Groups with rating rights');
        $ret .= $this->ld->getDefine($language, 'RATINGBAR_GROUPS_DESC', 'Select groups which should have the right to rate');
        $ret .= $this->ld->getDefine($language, 'RATINGBARS', 'Allow rating');
        $ret .= $this->ld->getDefine($language, 'RATINGBARS_DESC', 'Define whether rating should be possible and which kind of rating should be used');
        $ret .= $this->ld->getDefine($language, 'RATING_NONE', 'Do not use rating');
        $ret .= $this->ld->getDefine($language, 'RATING_5STARS', 'Rating with 5 stars');
        $ret .= $this->ld->getDefine($language, 'RATING_10STARS', 'Rating with 10 stars');
        $ret .= $this->ld->getDefine($language, 'RATING_LIKES', 'Rating with likes and dislikes');
        $ret .= $this->ld->getDefine($language, 'RATING_10NUM', 'Rating with 10 points');

        return $ret;
    }

    /**
     * @private function getFooter
     * @param null
     * @return string
     */
    private function getLanguageFooter()
    {
        $ret = $this->ld->getBelowDefines('End');
        $ret .= $this->ld->getBlankLine();

        return $ret;
    }

    /**
     * @public function render
     * @param null
     * @return bool|string
     */
    public function render()
    {
        $module             = $this->getModule();
        $tables             = $this->getTableTables($module->getVar('mod_id'));
        $filename           = $this->getFileName();
        $moduleDirname      = $module->getVar('mod_dirname');
        $language           = $this->getLanguage($moduleDirname, 'MI');
        $tableAdmin         = [];
        $tableUser          = [];
        $tableSubmenu       = [];
        $tableBlocks        = [];
        $tableNotifications = [];
        $tablePermissions   = [];
        $notifTable         = '';
        $tableBrokens       = [];
        $tableComments      = [];
        $tableRate          = [];
        foreach (\array_keys($tables) as $t) {
            $tableName            = $tables[$t]->getVar('table_name');
            $tableSoleName        = $tables[$t]->getVar('table_solename');
            $tableAdmin[]         = $tables[$t]->getVar('table_admin');
            $tableUser[]          = $tables[$t]->getVar('table_user');
            $tableSubmenu[]       = $tables[$t]->getVar('table_submenu');
            $tableBlocks[]        = $tables[$t]->getVar('table_blocks');
            $tableNotifications[] = $tables[$t]->getVar('table_notifications');
            $tableBroken          = $tables[$t]->getVar('table_broken');
            $tableBrokens[]       = $tables[$t]->getVar('table_broken');
            $tableComment         = $tables[$t]->getVar('table_comments');
            $tableComments[]      = $tables[$t]->getVar('table_comments');
            $tableRate[]          = $tables[$t]->getVar('table_rate');
            $tablePermissions[]   = $tables[$t]->getVar('table_permissions');
            if (1 === (int)$tables[$t]->getVar('table_notifications')) {
                $notifTable .= $this->getLanguageNotificationsTable($language, $tableName, $tableSoleName, $tableBroken, $tableComment);
            }

        }

        $content       = $this->getHeaderFilesComments($module);
        $content       .= $this->getLanguageMain($language, $module);
        $content       .= $this->getLanguageMenu($module, $language);
        if (\in_array(1, $tableAdmin)) {
            $content .= $this->getLanguageAdmin($language);
        }
        if (\in_array(1, $tableUser)) {
            $content .= $this->getLanguageUser($language);
        }
        if (\in_array(1, $tableSubmenu)) {
            $content .= $this->getLanguageSubmenu($language, $tables);
        }
        if (\in_array(1, $tableRate)) {
            $content .= $this->getLanguageRatingbars($language);
        }

        if (\in_array(1, $tableBlocks)) {
            $content .= $this->getLanguageBlocks($tables, $language);
        }
        $content .= $this->getLanguageConfig($language, $tables);
        if (\in_array(1, $tableNotifications)) {
            $content .= $this->getLanguageNotificationsGlobal($language, \in_array(1, $tableBrokens), \in_array(1, $tableComments));
            $content .= $notifTable;
        }
        if (\in_array(1, $tablePermissions)) {
            $content .= $this->getLanguagePermissionsGroups($language);
        }
        $content .= $this->getLanguageFooter();

        $this->create($moduleDirname, 'language/' . $GLOBALS['xoopsConfig']['language'], $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
