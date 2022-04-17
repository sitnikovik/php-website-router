<?php

define("DIR",$_SERVER["DOCUMENT_ROOT"].'/..');

if ($_SERVER["HTTP_HOST"]== "example.com")
{
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(~E_DEPRECATED); // отключает вывод ошибок при использовании устаревших функция
    error_reporting(0); 
}
    

require_once __DIR__.'/helpers/functions.php';

require_once __DIR__.'/routes.php';



?>