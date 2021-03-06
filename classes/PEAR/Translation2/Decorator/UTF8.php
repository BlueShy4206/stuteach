<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2004 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Lorenzo Alberton <l dot alberton at quipo dot it>           |
// +----------------------------------------------------------------------+
//
// $Id: UTF8.php,v 1.3 2004/05/03 22:56:20 quipo Exp $
//
/**
 * @package Translation2
 * @version $Id: UTF8.php,v 1.3 2004/05/03 22:56:20 quipo Exp $
 */
/**
 * Load Translation2 decorator base class
 */
require_once 'Translation2/Decorator.php';

/**
 * Decorator to convert UTF-8 strings to ISO-8859-1
 * @package Translation2
 */
class Translation2_Decorator_UTF8 extends Translation2_Decorator
{
    // {{{ get()

    /**
     * Get translated string
     *
     * Decode the UTF-8 string to ISO-8859-1
     *
     * @param string $stringID
     * @param string $pageID
     * @param string $langID
     * @param string $defaultText Text to display when the strings in both
     *                            the default and the fallback lang are empty
     * @return string
     */
    function get($stringID, $pageID=TRANSLATION2_DEFAULT_PAGEID, $langID=null, $defaultText=null)
    {
        $str = $this->translation2->get($stringID, $pageID, $langID);
        if (!empty($str)) {
            $str = utf8_decode($str); //decodes an UTF-8 string to ISO-8859-1
        }
        return $str;
    }

    // }}}
    // {{{ getPage()

    /**
     * Same as getRawPage, but resort to fallback language and
     * replace parameters when needed
     *
     * Decode each UTF-8 string in the group to ISO-8859-1
     *
     * @param string $pageID
     * @param string $langID
     * @return array
     */
    function getPage($pageID=TRANSLATION2_DEFAULT_PAGEID, $langID=null)
    {
        $data = $this->translation2->getPage($pageID, $langID);
        foreach ($data as $key => $val) {
            if (!empty($val)) {
                $data[$key] = utf8_decode($val);
            }
        }
        return $data;
    }

    // }}}
}
?>