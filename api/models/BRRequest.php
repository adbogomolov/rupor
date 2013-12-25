<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BRRequest
 *
 * @author irina
 */
class BRRequest extends Request {

    public $distance;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}

?>
