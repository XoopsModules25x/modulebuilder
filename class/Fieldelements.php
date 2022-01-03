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
 * modulebuilder module.
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.5
 *
 * @author          Txmod Xoops <support@txmodxoops.org>
 *
 */

/**
 * Class Fieldelements.
 */
class Fieldelements extends \XoopsObject
{
    /**
     * @public function constructor class
     * @param null
     */
    public function __construct()
    {
        $this->initVar('fieldelement_id', XOBJ_DTYPE_INT);
        $this->initVar('fieldelement_mid', XOBJ_DTYPE_INT);
        $this->initVar('fieldelement_tid', XOBJ_DTYPE_INT);
        $this->initVar('fieldelement_name', XOBJ_DTYPE_TXTBOX);
        $this->initVar('fieldelement_value', XOBJ_DTYPE_TXTBOX);
        $this->initVar('fieldelement_sort', XOBJ_DTYPE_INT);
        $this->initVar('fieldelement_deftype', XOBJ_DTYPE_TXTBOX);
        $this->initVar('fieldelement_defvalue', XOBJ_DTYPE_TXTBOX);
        $this->initVar('fieldelement_deffield', XOBJ_DTYPE_TXTBOX);
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
     * @return Fieldelements
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
     * Get Values.
     * @param null $keys
     * @param null $format
     * @param null $maxDepth
     * @return array
     */
    public function getValuesFieldelements($keys = null, $format = null, $maxDepth = null)
    {
        $ret = $this->getValues($keys, $format, $maxDepth);
        // Values
        $ret['id']       = $this->getVar('fieldelement_id');
        $ret['mid']      = $this->getVar('fieldelement_mid');
        $ret['tid']      = $this->getVar('fieldelement_tid');
        $ret['name']     = $this->getVar('fieldelement_name');
        $ret['value']    = $this->getVar('fieldelement_value');
        $ret['sort']     = $this->getVar('fieldelement_sort');
        $ret['deftype']  = $this->getVar('fieldelement_deftype');
        $ret['defvalue'] = $this->getVar('fieldelement_defvalue');
        $ret['deffield'] = $this->getVar('fieldelement_deffield');

        return $ret;
    }
}
