<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace academicpuma\citeproc;

/**
 * Description of csl_option
 *
 * @author sebastian
 */

class Option {

    private $name;
    private $value;

    function get() {
        return array($this->name => $this->value);
    }

}
