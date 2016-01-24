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
 * Description of csl_info
 *
 * @author sebastian
 */
class Info
{
    public $id             = null;
    public $title          = null;
    public $authors        = [];
    public $contributor    = [];
    public $links          = [];
    public $categories     = [];
    public $citationFormat = null;

    function __construct($domNode)
    {
        foreach ($domNode->childNodes as $node) {
            switch ($node->nodeName) {
                case 'author':
                    $items = [];
                    foreach ($node->childNodes as $childNode) {
                        if (in_array($childNode->nodeName, ['name', 'email', 'uri'])) {
                            $items[$childNode->nodeName] = $childNode->nodeValue;
                        }
                    }
                    $this->authors[] = $items;
                    break;

                case 'contributor':
                    $items = [];
                    foreach ($node->childNodes as $childNode) {
                        if (in_array($childNode->nodeName, ['name', 'email', 'uri'])) {
                            $items[$childNode->nodeName] = $childNode->nodeValue;
                        }
                    }
                    $this->contributor[] = $items;
                    break;

                case 'link':
                    $item = [];
                    foreach ($node->attributes as $attribute) {
                        if (in_array($attribute->name, ['href', 'rel', 'lang'])) {
                            $item[$attribute->name] = $attribute->value;
                        }
                    }
                    $this->links[] = $item;
                    break;

                case 'category':
                    foreach ($node->attributes as $attribute) {
                        if ($attribute->name == 'citation-format') {
                            $this->citationFormat = $attribute->value;
                        } else {
                            $this->categories[]   = $attribute->value;
                        }
                    }
                    break;

                case 'rights':
                    $item = ['title' => $node->nodeValue];
                    foreach ($node->attributes as $attribute) {
                        if (in_array($attribute->name, ['license', 'lang'])) {
                            $item[$attribute->name] = $attribute->value;
                        }
                    }
                    $this->rights = $item;
                    break;

                case 'id':
                case 'issn':
                case 'eissn':
                case 'issnl':
                case 'published':
                case 'summary':
                case 'title':
                case 'title-short':
                case 'updated':
                default:
                    $this->{$node->nodeName} = $node->nodeValue;
            }
        }
    }
}