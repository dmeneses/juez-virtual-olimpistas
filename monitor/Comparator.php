<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Comparator
 *
 * @author dann
 */
class Comparator {
    public static function compare($file1, $file2) {
         return (sha1_file($file1) == sha1_file($file2));
    }
}
