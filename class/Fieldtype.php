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
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.5
 *
 * @author          Txmod Xoops <support@txmodxoops.org>
 *
 */

/**
 * Class Fieldtype.
 */
class Fieldtype extends \XoopsObject
{
    /**
     * @public function constructor class
     * @param null
     */
    public function __construct()
    {
        $this->initVar('fieldtype_id', XOBJ_DTYPE_INT);
        $this->initVar('fieldtype_name', XOBJ_DTYPE_TXTBOX);
        $this->initVar('fieldtype_value', XOBJ_DTYPE_TXTBOX);
    }

    /**
     * @static function getInstance
     * @param null
     * @return Fieldtype
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
    public function getValuesFieldtype($keys = null, $format = null, $maxDepth = null)
    {
        $ret = $this->getValues($keys, $format, $maxDepth);
        // Values
        $ret['id']    = $this->getVar('fieldtype_id');
        $ret['name']  = $this->getVar('fieldtype_name');
        $ret['value'] = $this->getVar('fieldtype_value');

        return $ret;
    }
}
