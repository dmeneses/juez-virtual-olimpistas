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

    public static function compare($file1, $file2, $compareType, $symbolToAvoid) {
        switch ($compareType) {
            case 'STRICT': return (sha1_file($file1) == sha1_file($file2));
            case 'TOKEN': return Comparator::compareByLine($file1, $file2, $symbolToAvoid);
            default : echo "Compare type not defined.";
                return false;
        }
    }

    public static function compareByLine($file2, $file1, $symbolToAvoid) {
        $fileHandler1 = fopen($file1, "r");
        $fileHandler2 = fopen($file2, "r");
        if (!$fileHandler1) {
            echo "The file $file1 can't be open." . PHP_EOL;
            return false;
        }
        if (!$fileHandler2) {
            echo "The file $file2 can't be open." . PHP_EOL;
            return false;
        }
        $inputLine = '';
        $processedLine = '';
        $expectedLine = '';
        while (($inputLine = fgets($fileHandler1)) !== false) {
            if (($expectedLine = fgets($fileHandler2)) == false) {
                return false;
            }
            echo "Processing -$inputLine-" . PHP_EOL;
            $processedLine = Comparator::takeOutSymbol($inputLine, $symbolToAvoid . PHP_EOL);
            echo "Comparing file -$processedLine- and -$expectedLine-" . PHP_EOL;
            if ($processedLine != $expectedLine) {
                return false;
            }
        }
        fclose($fileHandler1);
        fclose($fileHandler2);
        return true;
    }

    public static function takeOutSymbol($string, $symbol) {
        $pos = strrpos($string, PHP_EOL);
        
        if ($pos === false) { 
            return rtrim($string, $symbol);
        }
        
        $string = rtrim($string, PHP_EOL);
        return rtrim($string, $symbol) . PHP_EOL;
    }

}
