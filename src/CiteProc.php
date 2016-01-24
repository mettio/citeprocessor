<?php
/**
 *   CiteProc-PHP
 *
 *   Copyright (C) 2010 - 2011  Ron Jerome, all rights reserved
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
namespace Mett\CiteProc;

use \DOMDocument;

/**
 * Class CiteProc
 *
 * @package Mett\CiteProc
 */
class CiteProc
{
    public $mapper       = null;
    public $citation     = null;
    public $bibliography = null;

    public $style        = null;
    public $quash        = null;

    public $macros       = null;
    public $locale       = null;
    public $style_locale = null;

    public $info         = null;


    /**
     * Singleton
     *
     * @return CiteProc
     */
    public static function getInstance($xml)
    {
        static $instance = null;

        if (null == $instance) {

            $instance = new CiteProc($xml);
        }

        return $instance;
    }


    public function __construct($csl = null, $lang = 'en')
    {
        if ($csl) {
	        $this->init($csl, $lang);
        }
    }

    public function init($csl, $lang)
    {
        // define field values appropriate to your data in the csl_mapper class and un-comment the next line.
        $this->mapper = new Mapper();
        $this->quash  = array();

        $csl_doc = new DOMDocument();

        // create DOM tree
        if (!$csl_doc->loadXML($csl)) {

            return;
        }

        // init style instance
        $style_nodes = $csl_doc->getElementsByTagName('style');

        if ($style_nodes) {
            foreach ($style_nodes as $style) {
                $this->style = new Style($style);
            }
        }

        // init info instance
        $info_nodes = $csl_doc->getElementsByTagName('info');
        if ($info_nodes) {
            foreach ($info_nodes as $info) {
                $this->info = new Info($info);
            }
        }

        // init locale instance
        $this->locale = new Locale($lang);
        $this->locale->set_style_locale($csl_doc);


        // init macros instance
        $macro_nodes = $csl_doc->getElementsByTagName('macro');
        if ($macro_nodes) {
            $this->macros = new Macros($macro_nodes, $this);
        }

        // init citation instance
        $citation_nodes = $csl_doc->getElementsByTagName('citation');
        foreach ($citation_nodes as $citation) {
            $this->citation = new Citation($citation, $this);
        }

        // init bibliography instance
        $bibliography_nodes = $csl_doc->getElementsByTagName('bibliography');
        foreach ($bibliography_nodes as $bibliography) {
            $this->bibliography = new Bibliography($bibliography, $this);
        }
    }

    /**
     * Renders a citation or bibliography dependend on $mode in html.
     *
     * @param      $data    Sometimes more documentation is better that less.
     * @param null $mode    Either "citation" or "bibliography". Defaults to "bibliography"
     *
     * @return string
     */
    function render($data, $mode = null)
    {
        $text = '';
        switch ($mode) {
            case 'citation':
                $text .= (isset($this->citation)) ? $this->citation->render($data) : '';
                break;
            case 'bibliography':
            default:
                $text .= (isset($this->bibliography)) ? $this->bibliography->render($data) : '';
                break;
        }

        return $text;
    }

    function render_macro($name, $data, $mode)
    {
        return $this->macros->render_macro($name, $data, $mode);
    }

    function get_locale($type, $arg1, $arg2 = null, $arg3 = null)
    {
        return $this->locale->get_locale($type, $arg1, $arg2, $arg3);
    }

    function map_field($field)
    {
        if ($this->mapper) {

            return $this->mapper->map_field($field);
        }

        return ($field);
    }

    function map_type($field)
    {
        if ($this->mapper) {

            return $this->mapper->map_type($field);
        }

	/**
	 * @param $name
	 *
	 * @return string
	 * @throws \Exception
	 */
    public static function loadStyleSheet($name) {
	    include_once __DIR__.'/../vendorPath.php';

	    if (!($vendorPath = vendorPath())) {
		    throw new \Exception('Error: vendor path not found. Use composer to initialize your project');
	    }

        return file_get_contents($vendorPath.'/academicpuma/styles/'.$name.'.csl');
    }
}
