<?php declare(strict_types=1);

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
 * modulebuilder module.
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.5
 *
 * @author          Txmod Xoops <support@txmodxoops.org>
 */
defined('XOOPS_ROOT_PATH') || die('Restricted access');

\XoopsLoad::load('XoopsFormLoader');

/**
 * Form that will output as a theme-enabled HTML table.
 *
 * Also adds JavaScript to validate required fields
 */
class ThemeForm extends \XoopsForm
{
    /**
     * create HTML to output the form as a theme-enabled table with validation.
     *
     * YOU SHOULD AVOID TO USE THE FOLLOWING Nocolspan METHOD, IT WILL BE REMOVED
     *
     * To use the noColspan simply use the following example:
     *
     * $colspan = new \XoopsFormDhtmlTextArea( '', 'key', $value, '100%', '100%' );
     * $colspan->setNocolspan();
     * $form->addElement( $colspan );
     *
     * @return string
     */
    public function render()
    {
        $ele_name = $this->getName();
        //$ret = ($this->getTitle() ? '<div class=" center head ">' . $this->getTitle() . '</div>' : '');
        $ret    = NWLINE . '<form name="' . $ele_name . '" id="' . $ele_name . '" action="' . $this->getAction() . '" method="' . $this->getMethod() . '" onsubmit="return xoopsFormValidate_' . $ele_name . '();"' . $this->getExtra() . '>' . NWLINE;
        $hidden = '';
        $class  = 'even';
        foreach ($this->getElements() as $ele) {
            if (!is_object($ele)) {
                $ret .= $ele;
            } else {
                if (!$ele->isHidden()) {
                    $ret .= $ele->render();
                } else {
                    $hidden .= $ele->render();
                }
            }
        }
        $ret .= NWLINE . ' ' . $hidden . NWLINE . '</form>';
        $ret .= $this->renderValidationJS(true);

        return $ret;
    }
}
