<?php
/*
 * Copyright (C) 2015 
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace Mett\CiteProc;

/**
 * Description of csl_bibliography
 *
 * @author sebastian
 */
class Bibliography extends Format
{
    private $layout = NULL;

    function init($dom_node, $citeproc) {
        $hier_name_attr = $this->get_hier_attributes();
        $options = $dom_node->getElementsByTagName('option');
        foreach ($options as $option) {
            $value = $option->getAttribute('value');
            $name = $option->getAttribute('name');
            $this->attributes[$name] = $value;
        }

        $layouts = $dom_node->getElementsByTagName('layout');
        foreach ($layouts as $layout) {
            $this->layout = new Layout($layout, $citeproc);
        }
    }

    function init_formatting() {
        $this->div_class = 'csl-bib-body';
        parent::init_formatting();
    }

    function render($data, $mode = NULL) {
        $this->citeproc->quash = array();
        $text = $this->layout->render($data, 'bibliography');
        if ($this->{'hanging-indent'} == 'true') {
            $text = '<div style="text-indent: -25px; padding-left: 25px;">' . $text . '</div>';
        }
        $text = str_replace('?.', '?', str_replace('..', '.', $text));
        return $this->format($text);
    }

}
