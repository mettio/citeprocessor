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
 * Class ResourceLoader
 *
 * @author  Jo Brunner <jo@mett.io>
 * @package Mett\CiteProc
 */
class ResourceLoader
{
    /**
     * Loads an independent citation language style file
     * And it's still a hack.
     *
     * @param string     $name    Name of style
     * @param null      $config
     *
     * @return string   Style definition
     */
    public function loadStyle($name, $config = null)
    {
        if (empty($config['stylePath'])) {
            $config = include(__DIR__ . "/../etc/resources.php");
        }

        return file_get_contents($config['stylePath'] . $name.'.csl');
    }
}