<?php
require_once "vendor/autoload.php";
 
use thiagoalessio\TesseractOCR\TesseractOCR;
 
try {
    echo (new TesseractOCR('images/text.png'))
        ->run();
} catch(Exception $e) {
    echo $e->getMessage();
}
?>