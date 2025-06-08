<?php declare(strict_types=1);

namespace XoopsModules\Modulebuilder\Files\User;

use XoopsModules\Modulebuilder;
use XoopsModules\Modulebuilder\Files;

/**
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
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
 * Class UserRss.
 */
class UserRss extends Files\CreateFile
{
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
        $this->uxc = UserXoopsCode::getInstance();
    }

    /**
     * @static function getInstance
     * @param null
     * @return UserRss
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
     * @param mixed  $table
     * @param string $filename
     */
    public function write($module, $table, $filename): void
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /**
     * @public function getUserRss
     * @param string $moduleDirname
     * @return string
     */
    public function getUserRss($moduleDirname)
    {
        $table     = $this->getTable();
        $tableName = $table->getVar('table_name');
        $fppf      = '';
        $fpmf      = '';
        $fieldId   = '';
        $fields    = $this->getTableFields($table->getVar('table_mid'), $table->getVar('table_id'));
        foreach (\array_keys($fields) as $f) {
            $fieldName     = $fields[$f]->getVar('field_name');
            $fieldMain[]   = $fields[$f]->getVar('field_main');
            $fieldParent[] = $fields[$f]->getVar('field_parent');

            if (0 == $f) {
                $fieldId = $fieldName;
            }
            if (\in_array(1, $fieldMain)) {
                $fpmf = $fieldName;
            }
            if (\in_array(1, $fieldParent)) {
                $fppf = $fieldName;
            } else {
                $fppf = 'cid';
            }
        }

        $ret = $this->pc->getPhpCodeUseNamespace(['Xmf', 'Request']);
        $ret .= $this->getRequire();

        $ret .= <<<EOT

            \${$fppf} = Request::getInt('{$fppf}', 0, 'GET');
            require_once \XOOPS_ROOT_PATH.'/class/template.php';
            if (\\function_exists('mb_http_output')) {
                mb_http_output('pass');
            }
            //header ('Content-Type:text/xml; charset=UTF-8');
            \$xoopsModuleConfig['utf8'] = false;

            \$tpl = new \XoopsTpl();
            \$tpl->xoops_setCaching(2); //1 = Cache global, 2 = Cache individual (for template)
            \$tpl->xoops_setCacheTime(\$helper->getConfig('timecacherss')*60); // Time of the cache on seconds
            \$categories = {$moduleDirname}MyGetItemIds('{$moduleDirname}_view', '{$moduleDirname}');
            \$criteria = new \CriteriaCompo();

            \$criteria->add(new \Criteria('cat_status', 0, '!='));
            \$criteria->add(new \Criteria('{$fppf}', '(' . \implode(',', \$categories) . ')','IN'));
            if (0 != \${$fppf}){
                \$criteria->add(new \Criteria('{$fppf}', \${$fppf}));
                \${$tableName} = \${$tableName}Handler->get(\${$fppf});
                \$title = \$xoopsConfig['sitename'] . ' - ' . \$xoopsModule->getVar('name') . ' - ' . \${$tableName}->getVar('{$fpmf}');
            } else {
                \$title = \$xoopsConfig['sitename'] . ' - ' . \$xoopsModule->getVar('name');
            }
            \$criteria->setLimit(\$helper->getConfig('perpagerss'));
            \$criteria->setSort('date');
            \$criteria->setOrder('DESC');
            \${$tableName}Arr = \${$tableName}Handler->getAll(\$criteria);
            unset(\$criteria);

            if (!\$tpl->is_cached('db:{$moduleDirname}_rss.tpl', \${$fppf})) {
                \$tpl->assign('channel_title', \htmlspecialchars(\$title, ENT_QUOTES));
                \$tpl->assign('channel_link', \XOOPS_URL.'/');
                \$tpl->assign('channel_desc', \htmlspecialchars(\$xoopsConfig['slogan'], ENT_QUOTES));
                \$tpl->assign('channel_lastbuild', \\formatTimestamp(\time(), 'rss'));
                \$tpl->assign('channel_webmaster', \$xoopsConfig['adminmail']);
                \$tpl->assign('channel_editor', \$xoopsConfig['adminmail']);
                \$tpl->assign('channel_category', 'Event');
                \$tpl->assign('channel_generator', 'XOOPS - ' . \htmlspecialchars(\$xoopsModule->getVar('{$fpmf}'), ENT_QUOTES));
                \$tpl->assign('channel_language', _LANGCODE);
                if ( 'fr' == _LANGCODE ) {
                    \$tpl->assign('docs', 'https://www.scriptol.fr/rss/RSS-2.0.html');
                } else {
                    \$tpl->assign('docs', 'https://cyber.law.harvard.edu/rss/rss.html');
                }
                \$tpl->assign('image_url', \XOOPS_URL . \$xoopsModuleConfig['logorss']);
                \$dimention = \getimagesize(\XOOPS_ROOT_PATH . \$xoopsModuleConfig['logorss']);
                if (empty(\$dimention[0])) {
                    \$width = 88;
                } else {
                   \$width = (\$dimention[0] > 144) ? 144 : \$dimention[0];
                }
                if (empty(\$dimention[1])) {
                    \$height = 31;
                } else {
                    \$height = (\$dimention[1] > 400) ? 400 : \$dimention[1];
                }
                \$tpl->assign('image_width', \$width);
                \$tpl->assign('image_height', \$height);
                foreach (\array_keys(\${$tableName}Arr) as \$i) {
                    \$description = \${$tableName}Arr[\$i]->getVar('description');
                    //permet d'afficher uniquement la description courte
                    if (false === \strpos(\$description,'[pagebreak]')){
                        \$description_short = \$description;
                    } else {
                        \$description_short = \substr(\$description,0,\strpos(\$description,'[pagebreak]'));
                    }
                    \$tpl->append('items', ['title' => \htmlspecialchars(\${$tableName}Arr[\$i]->getVar('{$fpmf}'), ENT_QUOTES),
                                                'link' => \XOOPS_URL . '/modules/{$moduleDirname}/single.php?{$fppf}=' . \${$tableName}Arr[\$i]->getVar('{$fppf}') . '&amp;{$fieldId}=' . \${$tableName}Arr[\$i]->getVar('{$fieldId}'),
                                                'guid' => \XOOPS_URL . '/modules/{$moduleDirname}/single.php?{$fppf}=' . \${$tableName}Arr[\$i]->getVar('{$fppf}') . '&amp;{$fieldId}=' . \${$tableName}Arr[\$i]->getVar('{$fieldId}'),
                                                'pubdate' => \\formatTimestamp(\${$tableName}Arr[\$i]->getVar('date'), 'rss'),
                                                'description' => \htmlspecialchars(\$description_short, ENT_QUOTES)
                                            ]);
                }
            }
            header('Content-Type:text/xml; charset=' . _CHARSET);
            \$tpl->display('db:{$moduleDirname}_rss.tpl', \${$fppf});

            EOT;

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
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $content       = $this->getHeaderFilesComments($module);
        $content       .= $this->getUserRss($moduleDirname);
        $this->create($moduleDirname, '/', $filename, $content, \_AM_MODULEBUILDER_FILE_CREATED, \_AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
