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
 * Class Element
 *
 * @author sebastian
 * @package Mett\CiteProc
 */
class Element extends Collection
{
    protected $attributes = array();
    protected $citeproc;
    protected $dom_node;

    function __construct($domNode = null, $citeproc = null)
    {
        $this->dom_node = $domNode;
        $this->citeproc = $citeproc;

        $this->set_attributes($domNode);
        $this->init($domNode, $citeproc);
    }

    function init($dom_node, $citeproc)
    {
        if (!$dom_node) {

            return;
        }

        foreach ($dom_node->childNodes as $node) {
            if ($node->nodeType == 1) {
                $this->addElement(Factory::create($node, $citeproc));
            }
        }
    }

    function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    function __isset($name)
    {
        return isset($this->attributes[$name]);
    }

    function __unset($name)
    {
        unset($this->attributes[$name]);
    }

    function &__get($name = null)
    {
        $null = null;

        if (array_key_exists($name, $this->attributes)) {

            return $this->attributes[$name];
        }
        if (isset($this->{$name})) {

            return $this->{$name};
        }

        return $null;
    }

    function set_attributes($dom_node)
    {
        $att          = array();
        $element_name = $dom_node->nodeName;
        if (isset($dom_node->attributes->length)) {
            for ($i = 0; $i < $dom_node->attributes->length; $i++) {
                $value = $dom_node->attributes->item($i)->value;
                $name  = str_replace(' ', '_', $dom_node->attributes->item($i)->name);
                if ($name == 'type') {
                    $value = $this->citeproc->map_type($value);
                }

                if (($name == 'variable' || $name == 'is-numeric') && $element_name != 'label') {
                    $value = $this->citeproc->map_field($value);
                }
                $this->{$name} = $value;
            }
        }
    }

    function get_attributes()
    {
        return $this->attributes;
    }

    function get_hier_attributes()
    {
        $hier_attr  = array();
        $hier_names = array('and', 'delimiter-precedes-last', 'et-al-min', 'et-al-use-first', 'et-al-subsequent-min', 'et-al-subsequent-use-first', 'initialize-with', 'name-as-sort-order', 'sort-separator', 'name-form', 'name-delimiter', 'names-delimiter');

        foreach ($hier_names as $name) {
            if (isset($this->attributes[$name])) {
                $hier_attr[$name] = $this->attributes[$name];
            }
        }

        return $hier_attr;
    }

    /**
     * Generic getter/setter...
     *
     * @param string|null $name
     *
     * @return string|void
     */
    function name($name = null)
    {
        if ($name) {
            $this->name = $name;

        } else {

            return str_replace(' ', '_', $this->name);
        }
    }

    function getDomNode()
    {
        return $this->dom_node;
    }
}
