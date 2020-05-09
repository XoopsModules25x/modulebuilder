<?php

namespace XoopsModules\Modulebuilder\Form;

/**
 * XOOPS form radio compo.
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright   The XOOPS project https://xoops.org/
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since       1.91
 *
 * @author      Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @author      Taiwen Jiang <phppp@users.sourceforge.net>
 *
 */
class FormRadio extends \XoopsFormRadio
{
    /**
     * Prepare HTML for output.
     *
     * @return string HTML
     */
    public function render()
    {
        $ret           = '';
        $ele_name      = $this->getName();
        $ele_title     = $this->getTitle();
        $ele_value     = $this->getValue();
        $ele_options   = $this->getOptions();
        $ele_extra     = $this->getExtra();
        $ele_delimeter = empty($this->columns) ? $this->getDelimeter() : '';
        if (!empty($this->columns)) {
            $ret .= '<table class="table table-bordered"><tr>';
        }
        $i      = 0;
        $id_ele = 0;
        foreach ($ele_options as $value => $name) {
            ++$id_ele;
            if (!empty($this->columns)) {
                if (0 == $i % $this->columns) {
                    $ret .= '<tr>';
                }
                $ret .= '<td class="radio">';
            }
            $ret .= '<input type="radio" name="' . $ele_name . '" id="' . $ele_name . '[' . $value . ']' . $id_ele . '" title = "' . htmlspecialchars($ele_title, ENT_QUOTES) . '" value="' . htmlspecialchars($value, ENT_QUOTES) . '"';
            if (isset($ele_value) && $value == $ele_value) {
                $ret .= ' checked';
            }
            $ret .= $ele_extra . ' />' . "<label name='xolb_{$ele_name}' for='" . $ele_name . '[' . $value . ']' . $id_ele . "'><span><span></span></span>" . $name . '</label>' . $ele_delimeter;
            if (!empty($this->columns)) {
                $ret .= '</td>';
                if (0 == ++$i % $this->columns) {
                    $ret .= '</tr>';
                }
            }
        }
        if (!empty($this->columns)) {
            $span = $i % $this->columns;
            if ($span) {
                $ret .= '<td colspan="' . ($this->columns - $span) . '"></td></tr>';
            }
            $ret .= '</table>';
        }

        return $ret;
    }
}
