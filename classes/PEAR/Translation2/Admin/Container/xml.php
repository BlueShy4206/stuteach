<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2004 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is available through the world-wide-web at                      |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Olivier Guilyardi <olivier at samalyse dot com>             |
// |          Lorenzo Alberton <l dot alberton at quipo dot it>           |
// +----------------------------------------------------------------------+
//
// $Id: xml.php,v 1.7 2005/01/26 13:01:13 quipo Exp $
//
/**
 * @package Translation2
 * @version $Id: xml.php,v 1.7 2005/01/26 13:01:13 quipo Exp $
 */

/**
 * require Translation2_Container_xml class
 */
require_once 'Translation2/Container/xml.php';

require_once 'XML/Util.php';

/**
 * Storage driver for storing/fetching data to/from an XML file
 *
 * @package  Translation2
 * @version  $Revision $
 */
class Translation2_Admin_Container_xml extends Translation2_Container_xml
{

    // {{{ class vars

    // }}}
    // {{{ addLang()

    /**
     * Does nothing (here for compatibility with the container interface)
     *
     * @param array $langData
     * @return true|PEAR_Error
     */
    function addLang($langData)
    {
        return true;
    }

    // }}}
    // {{{ addLangToList()

    /**
     * Creates a new entry in the <languages> section
     *
     * @param array $langData array('lang_id'    => 'en',
     *                              'name'       => 'english',
     *                              'meta'       => 'some meta info',
     *                              'error_text' => 'not available',
     *                              'encoding'   => 'iso-8859-1',
     *              );
     * @return true|PEAR_Error
     */
    function addLangToList($langData)
    {
        $validInput = array(
            'name'       => '',
            'meta'       => '',
            'error_text' => '',
            'encoding'   => 'iso-8859-1',
        );
        
        foreach ($validInput as $key => $val) {
            if (isset($langData[$key])) $validInput[$key] = $langData[$key];
        }
        
        $this->_data['languages'][$langData['lang_id']] = $validInput;
        return $this->_scheduleSaving();
    }

    // }}}
    // {{{ updateLang()

    /**
     * Update the lang info in the langsAvail table
     *
     * @param array  $langData
     * @return true|PEAR_Error
     */
    function updateLang($langData)
    {
        $allFields = array( //'lang_id',
            'name', 'meta', 'error_text', 'encoding',
        );
        foreach ($allFields as $field) {
            if (isset($this->_data['languages'][$langData['lang_id']][$field])) {
                $this->_data['languages'][$langData['lang_id']][$field] = $langData[$field];
            }
        }
        $success = $this->_scheduleSaving();
        $this->fetchLangs();  //update memory cache
        return $success;
    }

    // }}}
    // {{{ add()

    /**
     * Add a new entry in the strings table.
     *
     * @param string $stringID
     * @param string $pageID
     * @param array  $stringArray Associative array with string translations.
     *               Sample format:  array('en' => 'sample', 'it' => 'esempio')
     * @return true|PEAR_Error
     */
    function add($stringID, $pageID, $stringArray)
    {
        $langs = array_intersect(
            array_keys($stringArray),
            $this->getLangs('ids')
        );

        $pageID = is_null($pageID) ? '#NULL'  : $pageID;
        $pageID = empty($pageID)   ? '#EMPTY' : $pageID;

        if (!array_key_exists($pageID, $this->_data['pages'])) {
            $this->_data['pages'][$pageID] = array();
        }
        if (!array_key_exists($stringID, $this->_data['pages'][$pageID])) {
            $this->_data['pages'][$pageID][$stringID] = array();
        }
        foreach ($langs as $lang) {
            $this->_data['pages'][$pageID][$stringID][$lang] = $stringArray[$lang];
        }
        
        return $this->_scheduleSaving();
    }

    // }}}
    // {{{ update()

    /**
     * Update an existing entry in the strings table.
     *
     * @param string $stringID
     * @param string $pageID
     * @param array  $stringArray Associative array with string translations.
     *               Sample format:  array('en' => 'sample', 'it' => 'esempio')
     * @return true|PEAR_Error
     */
    function update($stringID, $pageID, $stringArray)
    {
        return $this->add($stringID, $pageID, $stringArray);
    }

    // }}}
    // {{{ remove()

    /**
     * Remove an entry from the strings table.
     *
     * @param string $stringID
     * @param string $pageID
     * @return true|PEAR_Error
     */
    function remove($stringID, $pageID)
    {
        $pageID = is_null($pageID) ? '#NULL' : $pageID;
        $pageID = empty($pageID) ? '#EMPTY' : $pageID;

        unset ($this->_data['pages'][$pageID][$stringID]);
        if (!count($this->_data['pages'][$pageID])) {
            unset ($this->_data['pages'][$pageID]);
        }

        return $this->_scheduleSaving();
    }

    // }}}
    // {{{ removeLang()

    /**
     * Remove all the entries for the given lang from the strings table.
     *
     * @param string  $langID
     * @param boolean $force (ignored)
     * @return true|PEAR_Error
     */
    function removeLang($langID, $force = true)
    {
        // remove lang metadata 
        unset($this->_data['languages'][$langID]);

        // remove the entries
        foreach (array_keys($this->_data['pages']) as $pageID) {
            foreach (array_keys($this->_data['pages'][$pageID]) as $stringID) {
                if (array_key_exists($langID, $this->_data['pages'][$pageID][$stringID])) {
                    unset($this->_data['pages'][$pageID][$stringID]);
                }
            }
        }
        return $this->_scheduleSaving();
    }

    // }}}
    // {{{ getPageNames()

    /**
     * Get a list of all the pageIDs.
     *
     * @return array
     */
    function getPageNames()
    {
        $pages = array_keys($this->_data['pages']);
        $k = array_search('#NULL', $pages);
        if ($k !== false && !is_null($k)) {
            $pages[$k] = null;
        }
        $k = array_search('#EMPTY', $pages);
        if ($k !== false && !is_null($k)) {
            $pages[$k] = '';
        }
        return $pages;
    }

    // }}}
    // {{{ _scheduleSaving()
    
    /**
     * Prepare data saving
     *
     * This methods registers _saveData() as a PEAR shutdown function. This
     * is to avoid saving multiple times if the programmer makes several 
     * changes.
     * 
     * @return true|PEAR_Error
     * @access private
     * @see Translation2_Admin_Container_xml::_saveData()
     */
    function _scheduleSaving()
    {
        if ($this->options['save_on_shutdown']) {
            // save the changes on shutdown
            register_shutdown_function(array(&$this, '_saveData'));
            return true;
        }
        
        // save the changes now
        if (PEAR::isError($e = $this->_saveData())) {
            return $e;
        }
        // refresh memory cache
        return $this->_loadFile();
    }

    // }}}
    // {{{ _saveData()
    
    /**
     * Serialize and save the updated tranlation data to the XML file
     *
     * @return boolean | PEAR_Error
     * @access private
     * @see Translation2_Admin_Container_xml::_scheduleSaving()
     */
    function _saveData()
    {
        $this->_convertEncodings('to_xml');
        $this->_convertLangEncodings('to_xml');
        
        // Serializing
        
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n\n" .
               "<!DOCTYPE translation2 [\n" . TRANSLATION2_DTD . "]>\n\n" .
               "<translation2>\n" .
               "  <languages>\n";

        foreach ($this->_data['languages'] as $lang => $spec) {
            extract ($spec);
            $xml .= "    <lang id=\"$lang\">\n" .
                    "      <name>" . 
                    ($name ? ' ' . XML_Util::replaceEntities($name) . ' ' : '') . 
                    "</name>\n" .
                    "      <meta>" . 
                    ($meta ? ' ' . XML_Util::replaceEntities($meta) . ' ' : "") . 
                    "</meta>\n" .
                    "      <error_text>" . 
                    ($error_text 
                        ? ' ' . XML_Util::replaceEntities($error_text) . ' ' 
                        : "") . 
                    "</error_text>\n" .
                    "      <encoding>" . ($encoding ? " $encoding " : "") . 
                    "</encoding>\n" .  
                    "    </lang>\n";
        }

        $xml .= "  </languages>\n" .
                "  <pages>\n";

        foreach ($this->_data['pages'] as $page => $strings) {
            $xml .= "    <page key=\"" . XML_Util::replaceEntities($page) . 
                    "\">\n";
            foreach ($strings as $str_id => $translations) {
                $xml .= "      <string key=\"" . 
                        XML_Util::replaceEntities($str_id) . "\">\n";
                foreach ($translations as $lang => $str) {
                    $xml .= "        <tr lang=\"$lang\"> " .
                            XML_Util::replaceEntities($str) . " </tr>\n";
                }
                $xml .= "      </string>\n";
            }
            $xml .= "    </page>\n";
        }

        $xml .= "  </pages>\n" .
                "</translation2>\n";

        // Saving

        if (!$f = fopen ($this->_filename, 'w')) {
            return $this->raiseError(sprintf(
                    'Unable to open the XML file ("%s") for writing',
                    $this->_filename
                ),
                TRANSLATION2_ERROR_CANNOT_WRITE_FILE,
                PEAR_ERROR_TRIGGER,
                E_USER_ERROR
            );
        }
        @flock($f, LOCK_EX);
        fwrite ($f, $xml);
        @flock($f, LOCK_UN);
        fclose ($f);
        return true;
    }

    // }}}
}
?>
