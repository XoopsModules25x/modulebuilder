<?php

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
 * @author          trabis <lusopoemas@gmail.com>
 *
 */

/**
 * Class ModuleBuilderHelper.
 */
class ModuleBuilderHelper
{
    /**
     * @var string
     */
    private $dirname;
    /**
     * @var string
     */
    private $module;
    /**
     * @var string
     */
    private $handler;
    /**
     * @var string
     */
    private $config;
    /**
     * @var string
     */
    private $debug;
    /*
    *  @protected function constructor class
    *  @param mixed $debug
    */

    /**
     * @param $debug
     */
    public function __construct($debug)
    {
        $this->debug   = $debug;
        $this->dirname = basename(dirname(__DIR__));
    }

    /*
    *  @static function getInstance
    *  @param mixed $debug
    */

    /**
     * @param bool $debug
     *
     * @return bool|\ModuleBuilderHelper
     */
    public static function getInstance($debug = false)
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self($debug);
        }

        return $instance;
    }

    /*
    *  @static function getModule
    *  @param null
    */

    /**
     * @return string
     */
    public function &getModule()
    {
        if (null === $this->module) {
            $this->initModule();
        }

        return $this->module;
    }

    /*
    *  @static function getConfig
    *  @param string $name
    */

    /**
     * @param null $name
     *
     * @return null|string
     */
    public function getConfig($name = null)
    {
        if (null === $this->config) {
            $this->initConfig();
        }
        if (!$name) {
            $this->addLog('Getting all config');

            return $this->config;
        }
        if (!isset($this->config[$name])) {
            $this->addLog("ERROR :: CONFIG '{$name}' does not exist");

            return false;
        }
        $this->addLog("Getting config '{$name}' : " . $this->config[$name]);

        return $this->config[$name];
    }

    /*
    *  @static function setConfig
    *  @param string $name
    *  @param mixed $value
    */

    /**
     * @param null $name
     * @param null $value
     *
     * @return mixed
     */
    public function setConfig($name = null, $value = null)
    {
        if (null === $this->config) {
            $this->initConfig();
        }
        $this->config[$name] = $value;
        $this->addLog("Setting config '{$name}' : " . $this->config[$name]);

        return $this->config[$name];
    }

    /*
    *  @static function getHandler
    *  @param string $name
    */

    /**
     * @param $name
     *
     * @return mixed
     */
    public function &getHandler($name)
    {
        if (!isset($this->handler[$name . 'Handler'])) {
            $this->initHandler($name);
        }
        $this->addLog("Getting handler '{$name}'");

        return $this->handler[$name . 'Handler'];
    }

    /*
    *  @static function initModule
    *  @param null
    */
    public function initModule()
    {
        global $xoopsModule;
        if (isset($xoopsModule) && is_object($xoopsModule) && $xoopsModule->getVar('dirname') == $this->dirname) {
            $this->module = $xoopsModule;
        } else {
            $hModule      = xoops_getHandler('module');
            $this->module = $hModule->getByDirname($this->dirname);
        }
        $this->addLog('INIT MODULE');
    }

    /*
    *  @static function initConfig
    *  @param null
    */
    public function initConfig()
    {
        $this->addLog('INIT CONFIG');
        $hModConfig   = xoops_getHandler('config');
        $this->config = $hModConfig->getConfigsByCat(0, $this->getModule()->getVar('mid'));
    }

    /*
    *  @static function initHandler
    *  @param string $name
    */

    /**
     * @param $name
     */
    public function initHandler($name)
    {
        $this->addLog('INIT ' . $name . ' HANDLER');
        $this->handler[$name . 'Handler'] = xoops_getModuleHandler($name, $this->dirname);
    }

    /*
    *  @static function addLog
    *  @param string $log
    */

    /**
     * @param $log
     */
    public function addLog($log)
    {
        if ($this->debug && is_object($GLOBALS['xoopsLogger'])) {
            $GLOBALS['xoopsLogger']->addExtra($this->module->name(), $log);
        }
    }
}
