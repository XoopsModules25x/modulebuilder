<?php declare(strict_types=1);

namespace XoopsModules\Modulebuilder;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Modulebuilder module for xoops
 *
 * @copyright     2020 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @since          1.0
 * @min_xoops      2.5.11
 * @author         TDM XOOPS - Email:<info@email.com> - Website:<https://xoops.org>
 */

/**
 * Interface  Constants
 */
interface Constants
{
	// Constants for morefiles
	public const MORE_FILES_TYPE_EMPTY = 1;
	public const MORE_FILES_TYPE_COPY = 2;

	public const FIRST_FIELDELEMENT_TABLE = 31;

    // ------------------- Field elements ---------------------------------
    // --------- The values MUST BE IDENTICAL to fieldelement_id ----------
    public const FIELD_ELE_TEXT           = 2;
    public const FIELD_ELE_TEXTAREA       = 3;
    public const FIELD_ELE_DHTMLTEXTAREA  = 4;
    public const FIELD_ELE_CHECKBOX       = 5;
    public const FIELD_ELE_RADIOYN        = 6;
    public const FIELD_ELE_SELECTBOX      = 7;
    public const FIELD_ELE_SELECTUSER     = 8;
    public const FIELD_ELE_COLORPICKER    = 9;
    public const FIELD_ELE_IMAGELIST      = 10;
    public const FIELD_ELE_SELECTFILE     = 11;
    public const FIELD_ELE_URLFILE        = 12;
    public const FIELD_ELE_UPLOADIMAGE    = 13;
    public const FIELD_ELE_UPLOADFILE     = 14;
    public const FIELD_ELE_TEXTDATESELECT = 15;
    public const FIELD_ELE_SELECTSTATUS   = 16;
    public const FIELD_ELE_PASSWORD       = 17;
    public const FIELD_ELE_SELECTCOUNTRY  = 18;
    public const FIELD_ELE_SELECTLANG     = 19;
    public const FIELD_ELE_RADIO          = 20;
    public const FIELD_ELE_DATETIME       = 21;
    public const FIELD_ELE_SELECTCOMBO    = 22;
    public const FIELD_ELE_TEXTUUID       = 23;
    public const FIELD_ELE_TEXTIP         = 24;
    public const FIELD_ELE_TEXTCOMMENTS   = 25;
    public const FIELD_ELE_TEXTRATINGS    = 26;
    public const FIELD_ELE_TEXTVOTES      = 27;
    public const FIELD_ELE_TEXTREADS      = 28;
    public const FIELD_ELE_TEXTINTEGER    = 29;
    public const FIELD_ELE_TEXTFLOAT      = 30;

}
