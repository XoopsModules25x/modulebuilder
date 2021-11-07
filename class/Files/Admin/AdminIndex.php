<?php

namespace XoopsModules\Modulebuilder\Files\Admin;

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
 *
 */

/**
 * Class AdminIndex.
 */
class AdminIndex extends Files\CreateFile
{
    /**
     * @var mixed
     */
    private $axc = null;

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
        $this->axc = Modulebuilder\Files\Admin\AdminXoopsCode::getInstance();
    }

    /**
     * @static function getInstance
     * @param null
     * @return AdminIndex
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
     * @param string $module
     * @param mixed  $tables
     * @param string $filename
     */
    public function write($module, $tables, $filename)
    {
        $this->setModule($module);
        $this->setTables($tables);
        $this->setFileName($filename);
    }

    /**
     * @private function render
     * @param $module
     *
     * @return string
     */
    private function getAdminIndex($module)
    {

        $moduleDirname    = $module->getVar('mod_dirname');
        $tables           = $this->getTableTables($module->getVar('mod_id'), 'table_order');
        $language         = $this->getLanguage($moduleDirname, 'AM');
        $languageThereAre = $this->getLanguage($moduleDirname, 'AM', 'THEREARE_');

        $ret              = $this->getSimpleString('');
        $ret              .= $this->pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname, 'Common']);
        $ret              .= $this->pc->getPhpCodeIncludeDir('\dirname(__DIR__)', 'preloads/autoloader', true);
        $ret              .= $this->getRequire();
        $ret              .= $this->pc->getPhpCodeBlankLine();
        $ret              .= $this->pc->getPhpCodeCommentLine('Template Index');
        $ret              .= $this->axc->getAdminTemplateMain((string)$moduleDirname, 'index');
        $ret              .= $this->pc->getPhpCodeBlankLine();
        $ret              .= $this->pc->getPhpCodeCommentLine('Count elements');
        $tableName        = null;
        foreach (\array_keys($tables) as $i) {
            $tableName    = $tables[$i]->getVar('table_name');
            $ucfTableName = \ucfirst($tableName);
            $ret          .= $this->xc->getXcEqualsOperator("\$count{$ucfTableName}", "\${$tableName}Handler->getCount()");
        }
        $ret .= $this->pc->getPhpCodeBlankLine();
        $ret .= $this->pc->getPhpCodeCommentLine('InfoBox Statistics');
        $ret .= $this->axc->getAxcAddInfoBox($language . 'STATISTICS');
        $ret .= $this->pc->getPhpCodeCommentLine('Info elements');
        $tableInstall = [];
        foreach (\array_keys($tables) as $i) {
            $tableName      = $tables[$i]->getVar('table_name');
            $tableInstall[] = $tables[$i]->getVar('table_install');
            $stuTableName   = $languageThereAre . \mb_strtoupper($tableName);
            $ucfTableName   = \ucfirst($tableName);
            $ret            .= $this->axc->getAxcAddInfoBoxLine($stuTableName, "\$count{$ucfTableName}");
        }

        if (null === $tableName) {
            $ret .= $this->axc->getAxcAddInfoBoxLine('No statistics', '0');
        }

        if (\is_array($tables) && \in_array(1, $tableInstall)) {
            $ret       .= $this->pc->getPhpCodeBlankLine();
            $ret       .= $this->pc->getPhpCodeCommentLine('Upload Folders');
            $ret       .= $this->xc->getXcEqualsOperator('$configurator', 'new Common\Configurator()');
            $cond      = '$configurator->uploadFolders && \is_array($configurator->uploadFolders)';
            $fe_action = $this->xc->getXcEqualsOperator('$folder[]', '$configurator->uploadFolders[$i]', '',"\t\t");
            $condIf    = $this->pc->getPhpCodeForeach('configurator->uploadFolders', true, false, 'i', $fe_action, "\t");
            $ret       .= $this->pc->getPhpCodeConditions($cond, '', '', $condIf, false);

            $ret       .= $this->pc->getPhpCodeCommentLine('Uploads Folders Created');
            $boxLine   = $this->axc->getAxcAddConfigBoxLine('$folder[$i]', 'folder', '', "\t");
            $boxLine   .= $this->axc->getAxcAddConfigBoxLine("[\$folder[\$i], '777']", 'chmod', '', "\t");
            $ret       .= $this->pc->getPhpCodeForeach('folder', true, false, 'i', $boxLine, '') . PHP_EOL;
        }
        $ret    .= $this->pc->getPhpCodeCommentLine('Render Index');
        $ret    .= $this->xc->getXcXoopsTplAssign('navigation', "\$adminObject->displayNavigation('index.php')");
        $ret    .= $this->pc->getPhpCodeCommentLine('Test Data');
        $condIf = $this->xc->getXcXoopsLoadLanguage('admin/modulesadmin',"\t", 'system');
        $condIf .= $this->pc->getPhpCodeIncludeDir('\dirname(__DIR__)', 'testdata/index', true, '','',"\t");
        $condIf .= $this->axc->getAdminItemButton("\constant('CO_' . \$moduleDirNameUpper . '_ADD_SAMPLEDATA')", '', '', $op = '__DIR__ . /../../testdata/index.php?op=load', $type = 'samplebutton', $t = "\t");
        $condIf .= $this->axc->getAdminItemButton("\constant('CO_' . \$moduleDirNameUpper . '_SAVE_SAMPLEDATA')", '', '', $op = '__DIR__ . /../../testdata/index.php?op=save', $type = 'samplebutton', $t = "\t");
        $condIf .= '//' . $this->axc->getAdminItemButton("\constant('CO_' . \$moduleDirNameUpper . '_EXPORT_SCHEMA')", '', '', $op = '__DIR__ . /../../testdata/index.php?op=exportschema', $type = 'samplebutton', $t = "\t");
        $condIf .= $this->axc->getAdminDisplayButton('left', "\t");
        $cond   = $this->xc->getXcGetConfig('displaySampleButton');
        $ret    .= $this->pc->getPhpCodeConditions($cond, '', '', $condIf, false);
        $ret    .= $this->xc->getXcXoopsTplAssign('index', '$adminObject->displayIndex()');
        $ret    .= $this->pc->getPhpCodeCommentLine('End Test Data');

        $ret    .= $this->getRequire('footer');

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
        $moduleDirname = $module->getVar('mod_dirname');
        $filename      = $this->getFileName();
        $content       = $this->getHeaderFilesComments($module);
        $content       .= $this->getAdminIndex($module);

        $this->create($moduleDirname, 'admin', $filename, $content, \_AM_MODULEBUILDER_FILE_CREATED, \_AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
