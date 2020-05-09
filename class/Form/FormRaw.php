<?php

namespace XoopsModules\Modulebuilder\Form;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * FormRaw - raw form element.
 *
 * This class has special treatment by xoopsforms, it will render the raw
 * value provided without wrapping in HTML
 *
 * @category  Xoops\Form\Raw
 *
 * @author    trabis <trabisdementia@gmail.com>
 * @copyright 2012-2014 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 *
 * @link      https://xoops.org
 * @since     2.6.0
 */
class FormRaw extends \XoopsFormElement
{
    /**
     * __construct.
     *
     * @param string $value value
     */
    public function __construct($value = '')
    {
        $this->setValue($value);
    }

    /**
     * render.
     *
     * @return string rendered form element
     */
    public function render()
    {
        return $this->getValue();
    }
}
