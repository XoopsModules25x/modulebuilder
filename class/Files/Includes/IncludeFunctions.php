<?php

namespace XoopsModules\Modulebuilder\Files\Includes;

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
 * Class IncludeFunctions.
 */
class IncludeFunctions extends Files\CreateFile
{
    /**
     * @var string
     */
    private $xc = null;

    /**
     * @var string
     */
    private $pc = null;

    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
        $this->xc = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $this->pc = Modulebuilder\Files\CreatePhpCode::getInstance();
    }

    /**
     * @static function getInstance
     * @param null
     * @return IncludeFunctions
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
     * @param $filename
     */
    public function write($module, $filename)
    {
        $this->setModule($module);
        $this->setFileName($filename);
    }

    /**
     * @private function getFunctionBlock
     * @param string $moduleDirname
     *
     * @return string
     */
    private function getFunctionBlock($moduleDirname)
    {
        $t      = "\t";
        $ret    = $this->pc->getPhpCodeCommentMultiLine(['function' => 'add selected cats to block', '' => '', '@param  $cats' => '', '@return' => 'string']);
        $func   = $this->xc->getXcEqualsOperator('$cat_sql', "'('", '', $t);
        $contIf = $this->xc->getXcEqualsOperator('$cat_sql', "current(\$cats)", '.',$t. "\t");
        $contIf .= $this->getSimpleString("array_shift(\$cats);", $t. "\t");
        $contFe = $this->getSimpleString("\$cat_sql .= ',' . \$cat;", $t . "\t\t");
        $contIf .= $this->pc->getPhpCodeForeach('cats', false,false,'cat', $contFe, $t. "\t");
        $func .= $this->pc->getPhpCodeConditions('is_array($cats)','','', $contIf, false, $t);
        $func   .= $this->xc->getXcEqualsOperator('$cat_sql', "')'", '.', $t);
        $func .= $this->getSimpleString('return $cat_sql;', $t);
        $ret  .= $this->pc->getPhpCodeFunction("{$moduleDirname}_block_addCatSelect", '$cats', $func);

        return $ret;
    }

    /**
     * @private function getFunctionGetMyItemIds
     * @param string $moduleDirname
     *
     * @return string
     */
    private function getFunctionGetMyItemIds($moduleDirname)
    {
        $t      = "\t";
        $ret    = $this->pc->getPhpCodeCommentMultiLine(['Get the permissions ids' => '', '' => '', '@param  $permtype' => '', '@param  $dirname' => '', '@return' => 'mixed $itemIds']);
        $func = $this->xc->getXcGetGlobal(['xoopsUser'], $t);
        $func .= $this->xc->getXcEqualsOperator('static $permissions', "[]", '', $t);
        $contIf = $this->getSimpleString('return $permissions[$permtype];', $t . "\t");
        $func .= $this->pc->getPhpCodeConditions('is_array($permissions) && array_key_exists($permtype, $permissions)','','', $contIf, false, $t);
        $func .= $this->xc->getXcXoopsHandler('module', $t);
        $func .= $this->xc->getXcEqualsOperator("\${$moduleDirname}Module", '$moduleHandler->getByDirname($dirname)', '', $t);
        $func .= $this->pc->getPhpCodeTernaryOperator('groups', 'is_object($xoopsUser)', '$xoopsUser->getGroups()', 'XOOPS_GROUP_ANONYMOUS', $t);
        $func .= $this->xc->getXcXoopsHandler('groupperm', $t);
        $func .= $this->xc->getXcEqualsOperator('$itemIds', "\$grouppermHandler->getItemIds(\$permtype, \$groups, \${$moduleDirname}Module->getVar('mid'))", '', $t);
        $func .= $this->getSimpleString('return $itemIds;', $t);
        $ret  .= $this->pc->getPhpCodeFunction("{$moduleDirname}GetMyItemIds", '$permtype, $dirname', $func);

        return $ret;
    }

    /**
     * @private function getFunctionNumbersOfEntries
     *
     * @param string $moduleDirname
     * @param        $tableMid
     * @param        $tableId
     * @param        $tableName
     *
     * @return string
     */
    private function getFunctionNumbersOfEntries($moduleDirname, $tableMid, $tableId, $tableName)
    {
        $fields = $this->getTableFields($tableMid, $tableId);
        $fieldId = '';
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (0 == $f) {
                $fieldId = $fieldName; // fieldMain = fields parameters main field
            }
        }
        $ret = <<<EOT
\n/**
 * Get the number of {$tableName} from the sub categories of a category or sub topics of or topic
 * @param \$mytree
 * @param \${$tableName}
 * @param \$entries
 * @param \$cid
 * @return int
 */
function {$moduleDirname}NumbersOfEntries(\$mytree, \${$tableName}, \$entries, \$cid)
{
    \$count = 0;
    if(in_array(\$cid, \${$tableName})) {
        \$child = \$mytree->getAllChild(\$cid);
        foreach (array_keys(\$entries) as \$i) {
            if (\$entries[\$i]->getVar('{$fieldId}') == \$cid){
                \$count++;
            }
            foreach (array_keys(\$child) as \$j) {
                if (\$entries[\$i]->getVar('{$fieldId}') == \$j){
                    \$count++;
                }
            }
        }
    }
    return \$count;
}\n
EOT;

        return $ret;
    }

    /**
     * @private function getFunctionMetaKeywords
     *
     * @param string $moduleDirname
     *
     * @return string
     */
    private function getFunctionMetaKeywords($moduleDirname)
    {
        $ret = <<<EOT
\n/**
 * Add content as meta tag to template
 * @param \$content
 * @return void
 */
\nfunction {$moduleDirname}MetaKeywords(\$content)
{
    global \$xoopsTpl, \$xoTheme;
    \$myts = MyTextSanitizer::getInstance();
    \$content= \$myts->undoHtmlSpecialChars(\$myts->displayTarea(\$content));
    if(isset(\$xoTheme) && is_object(\$xoTheme)) {
        \$xoTheme->addMeta( 'meta', 'keywords', strip_tags(\$content));
    } else {    // Compatibility for old Xoops versions
        \$xoopsTpl->assign('xoops_meta_keywords', strip_tags(\$content));
    }
}\n
EOT;

        return $ret;
    }

    /**
     * @private function getFunctionDescription
     *
     * @param string $moduleDirname
     *
     * @return string
     */
    private function getFunctionMetaDescription($moduleDirname)
    {
        $ret = <<<EOT
\n/**
 * Add content as meta description to template
 * @param \$content
 * @return void
 */
 \nfunction {$moduleDirname}MetaDescription(\$content)
{
    global \$xoopsTpl, \$xoTheme;
    \$myts = MyTextSanitizer::getInstance();
    \$content = \$myts->undoHtmlSpecialChars(\$myts->displayTarea(\$content));
    if(isset(\$xoTheme) && is_object(\$xoTheme)) {
        \$xoTheme->addMeta( 'meta', 'description', strip_tags(\$content));
    } else {    // Compatibility for old Xoops versions
        \$xoopsTpl->assign('xoops_meta_description', strip_tags(\$content));
    }
}\n
EOT;

        return $ret;
    }

    /**
     * @private function getRewriteUrl
     *
     * @param string $moduleDirname
     * @param string $tableName
     *
     * @return string
     */
    private function getRewriteUrl($moduleDirname, $tableName)
    {
        $ucfModuleDirname = ucfirst($moduleDirname);
        $ret              = <<<EOT
\n/**
 * Rewrite all url
 *
 * @param string  \$module  module name
 * @param array   \$array   array
 * @param string  \$type    type
 * @return null|string \$type    string replacement for any blank case
 */
function {$moduleDirname}_RewriteUrl(\$module, \$array, \$type = 'content')
{
    \$comment = '';
    \$helper = \XoopsModules\\{$ucfModuleDirname}\Helper::getInstance();
    \${$tableName}Handler = \$helper->getHandler('{$tableName}');
    \$lenght_id = \$helper->getConfig('lenght_id');
    \$rewrite_url = \$helper->getConfig('rewrite_url');

    if (\$lenght_id != 0) {
        \$id = \$array['content_id'];
        while (strlen(\$id) < \$lenght_id) {
            \$id = '0' . \$id;
        }
    } else {
        \$id = \$array['content_id'];
    }

    if (isset(\$array['topic_alias']) && \$array['topic_alias']) {
        \$topic_name = \$array['topic_alias'];
    } else {
        \$topic_name = {$moduleDirname}_Filter(xoops_getModuleOption('static_name', \$module));
    }

    switch (\$rewrite_url) {

        case 'none':
            if(\$topic_name) {
                 \$topic_name = 'topic=' . \$topic_name . '&amp;';
            }
            \$rewrite_base = '/modules/';
            \$page = 'page=' . \$array['content_alias'];
            return XOOPS_URL . \$rewrite_base . \$module . '/' . \$type . '.php?' . \$topic_name . 'id=' . \$id . '&amp;' . \$page . \$comment;
            break;

        case 'rewrite':
            if(\$topic_name) {
                \$topic_name .= '/';
            }
            \$rewrite_base = xoops_getModuleOption('rewrite_mode', \$module);
            \$rewrite_ext = xoops_getModuleOption('rewrite_ext', \$module);
            \$module_name = '';
            if(xoops_getModuleOption('rewrite_name', \$module)) {
                \$module_name = xoops_getModuleOption('rewrite_name', \$module) . '/';
            }
            \$page = \$array['content_alias'];
            \$type .= '/';
            \$id .= '/';
            if (\$type === 'content/') {
                \$type = '';
            }
            if (\$type === 'comment-edit/' || \$type === 'comment-reply/' || \$type === 'comment-delete/') {
                return XOOPS_URL . \$rewrite_base . \$module_name . \$type . \$id . '/';
            }

            return XOOPS_URL . \$rewrite_base . \$module_name . \$type . \$topic_name  . \$id . \$page . \$rewrite_ext;
            break;

         case 'short':
            if(\$topic_name) {
                \$topic_name .= '/';
            }
            \$rewrite_base = xoops_getModuleOption('rewrite_mode', \$module);
            \$rewrite_ext = xoops_getModuleOption('rewrite_ext', \$module);
            \$module_name = '';
            if(xoops_getModuleOption('rewrite_name', \$module)) {
                \$module_name = xoops_getModuleOption('rewrite_name', \$module) . '/';
            }
            \$page = \$array['content_alias'];
            \$type .= '/';
            if (\$type === 'content/') {
                \$type = '';
            }
            if (\$type === 'comment-edit/' || \$type === 'comment-reply/' || \$type === 'comment-delete/') {
                return XOOPS_URL . \$rewrite_base . \$module_name . \$type . \$id . '/';
            }

            return XOOPS_URL . \$rewrite_base . \$module_name . \$type . \$topic_name . \$page . \$rewrite_ext;
            break;
    }
    return null;
}
EOT;

        return $ret;
    }

    /**
     * @private function getRewriteFilter
     *
     * @param string $moduleDirname
     * @param string $tableName
     *
     * @return string
     */
    private function getRewriteFilter($moduleDirname, $tableName)
    {
        $ucfModuleDirname = ucfirst($moduleDirname);
        $ret              = <<<EOT
\n/**
 * Replace all escape, character, ... for display a correct url
 *
 * @param string \$url      string to transform
 * @param string \$type     string replacement for any blank case
 * @return string \$url
 */
function {$moduleDirname}_Filter(\$url, \$type = '') {

    // Get regular expression from module setting. default setting is : `[^a-z0-9]`i
    \$helper = \XoopsModules\\{$ucfModuleDirname}\Helper::getInstance();
    \${$tableName}Handler = \$helper->getHandler('{$tableName}');
    \$regular_expression = \$helper->getConfig('regular_expression');

    \$url = strip_tags(\$url);
    \$url .= preg_replace("`\[.*\]`U", '', \$url);
    \$url .= preg_replace('`&(amp;)?#?[a-z0-9]+;`i', '-', \$url);
    \$url .= htmlentities(\$url, ENT_COMPAT, 'utf-8');
    \$url .= preg_replace("`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i", "\\1", \$url);
    \$url .= preg_replace(array(\$regular_expression, "`[-]+`"), '-', \$url);
    \$url = (\$url == '') ? \$type : strtolower(trim(\$url, '-'));
    return \$url;
}
EOT;

        return $ret;
    }

    /**
     * @public function render
     *
     * @param null
     *
     * @return bool|string
     */
    public function render()
    {
        $module           = $this->getModule();
        $tables           = $this->getTableTables($module->getVar('mod_id'), 'table_order');
        $tableId          = null;
        $tableMid         = null;
        $tableName        = null;
        $tableBlocks      = null;
        $tablePermissions = null;
        $tableCategory    = null;
        foreach (array_keys($tables) as $i) {
            $tableId            = $tables[$i]->getVar('table_id');
            $tableMid           = $tables[$i]->getVar('table_mid');
            $tableName          = $tables[$i]->getVar('table_name');
            $tableBlocks[]      = $tables[$i]->getVar('table_blocks');
            $tablePermissions[] = $tables[$i]->getVar('table_permissions');
            $tableCategory[]    = $tables[$i]->getVar('table_category');
        }
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $content       = $this->getHeaderFilesComments($module);
        if (in_array(1, $tableBlocks)) {
            $content .= $this->getFunctionBlock($moduleDirname);
        }
        if (in_array(1, $tablePermissions)) {
            $content .= $this->getFunctionGetMyItemIds($moduleDirname);
        }
        if (in_array(1, $tableCategory)) {
            $content .= $this->getFunctionNumbersOfEntries($moduleDirname, $tableMid, $tableId, $tableName);
        }
        $content .= $this->getFunctionMetaKeywords($moduleDirname);
        $content .= $this->getFunctionMetaDescription($moduleDirname);
        $content .= $this->getRewriteUrl($moduleDirname, $tableName);
        $content .= $this->getRewriteFilter($moduleDirname, $tableName);

        $this->create($moduleDirname, 'include', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
