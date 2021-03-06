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
// $Id: ErrorText.php,v 1.1 2005/01/30 17:02:51 quipo Exp $
//
/**
 * @package Translation2
 * @version $Id: ErrorText.php,v 1.1 2005/01/30 17:02:51 quipo Exp $
 */
/**
 * Load Translation2 decorator base class
 */
require_once 'Translation2/Decorator.php';

/**
 * Decorator to provide a fallback text for empty strings.
 * @package Translation2
 */
class Translation2_Decorator_ErrorText extends Translation2_Decorator
{
    // {{{ get()

    /**
     * Get translated string
     *
     * If the string is empty, return the error message
     *
     * @param string $stringID
     * @param string $pageID
     * @param string $langID
     * @param string $defaultText Text to display when the string is empty
     * @return string
     */
    function get($stringID, $pageID=TRANSLATION2_DEFAULT_PAGEID, $langID=null, $defaultText='')
    {
        $str = $this->translation2->get($stringID, $pageID, $langID, $defaultText);
        if (empty($str)) {
            $str = $this->translation2->getLang(null, 'error_text');
        }
        return $str;
    }

    // }}}
    // {{{ getPage()

    /**
     * Same as getRawPage, but resort to fallback language and
     * replace parameters when needed
     *
     * @param string $pageID
     * @param string $langID
     * @return array
     */
    function getPage($pageID=TRANSLATION2_DEFAULT_PAGEID, $langID=null)
    {
        $data = $this->translation2->getPage($pageID, $langID);
        $error_text = str_replace('"', '\"', $this->translation2->getLang(null, 'error_text'));
        array_walk(
            $data,
            create_function('&$w', 'if (empty($w)) $w = "'.$error_text.'";')
        );
        return $data;
    }

    // }}}
}
?>