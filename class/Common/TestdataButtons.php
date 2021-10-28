<?php

declare(strict_types=1);

namespace XoopsModules\Modulebuilder\Common;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.
 
 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 *
 * @category        Module
 * @author          XOOPS Development Team <https://xoops.org>
 * @copyright       {@link https://xoops.org/ XOOPS Project}
 * @license         GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 */

use Xmf\Request;
use Xmf\Yaml;
use XoopsModules\Modulebuilder\Helper;
/** @var Helper $helper */

/**
 * Class SysUtility
 */
class TestdataButtons
{

    //functions for import buttons
    public static function loadButtonConfig($adminObject)
    {
        $moduleDirName      = \basename(\dirname(__DIR__, 2));
        $moduleDirNameUpper = \mb_strtoupper($moduleDirName);
        $yamlFile           = \dirname(__DIR__, 2) . '/config/admin.yml';
        $config             = Yaml::readWrapped($yamlFile); // work with phpmyadmin YAML dumps
        $displaySampleButton = $config['displaySampleButton'];
        $helper = Helper::getInstance();

        if (1 == $displaySampleButton) {
            \xoops_loadLanguage('admin/modulesadmin', 'system');
            $adminObject->addItemButton(\constant('CO_' . $moduleDirNameUpper . '_' . 'LOAD_SAMPLEDATA'), $helper->url('testdata/index.php?op=load'), 'add');
            $adminObject->addItemButton(\constant('CO_' . $moduleDirNameUpper . '_' . 'SAVE_SAMPLEDATA'), $helper->url('testdata/index.php?op=save'), 'add');
            $adminObject->addItemButton(\constant('CO_' . $moduleDirNameUpper . '_' . 'CLEAR_SAMPLEDATA'), $helper->url('testdata/index.php?op=clear'), 'alert');
            //    $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'EXPORT_SCHEMA'), $helper->url( 'testdata/index.php?op=exportschema'), 'add');
            $adminObject->addItemButton(\constant('CO_' . $moduleDirNameUpper . '_' . 'HIDE_SAMPLEDATA_BUTTONS'), '?op=hide_buttons', 'delete');
        } else {
            $adminObject->addItemButton(\constant('CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLEDATA_BUTTONS'), '?op=show_buttons', 'add');
            // $displaySampleButton = $config['displaySampleButton'];
        }
    }

    public static function hideButtons()
    {
        $yamlFile            = \dirname(__DIR__, 2) . '/config/admin.yml';
        $app                        = [];
        $app['displaySampleButton'] = 0;
        Yaml::save($app, $yamlFile);
        \redirect_header('index.php', 0, '');
    }

    public static function showButtons()
    {
        $yamlFile            = \dirname(__DIR__, 2) . '/config/admin.yml';
        $app                        = [];
        $app['displaySampleButton'] = 1;
        Yaml::save($app, $yamlFile);
        \redirect_header('index.php', 0, '');
    }
}
