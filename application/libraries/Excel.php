<?php 
if (!defined('BASEPATH')) exit('No permitir el acceso directo al script');
/* 
 *  ======================================= 
 *  Author     : Muhammad Surya Ikhsanudin 
 *  License    : Protegida 
 *  Email      : mutofiyah@gmail.com 
 *   
 *  Prohibido su cambio, modificacion y 
 *  redistribucion sin conocimiento del Autor 
 *  ======================================= 
 */  
require_once APPPATH."/libraries/PHPExcel.php"; 
 
class Excel extends PHPExcel { 
    public function __construct() { 
        parent::__construct(); 
    } 
}
//class Iofactory extends PHPExcel { 
//    public function __construct() { 
//        parent::__construct(); 
//    } 
//}