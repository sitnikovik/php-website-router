<?php
require_once __DIR__.'/../app/app.php'; // основной файл приложения


/**
 * отправка форм
 * https://github.com/sitnikovik/PHPSendForm
 */
if (isset($_GET["form"])) 
{
    
}
/** */

session_start();

/**
 * роутинг
 */
$route = realroute($_SERVER["REQUEST_URI"]);

// если ajax запрос
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
{
    $response = [];
    //Включаем буфер вывода
    ob_start(); //ob_start("ob_gzhandler");
    $request = explode("?",$_SERVER["REQUEST_URI"])[0];
    switch ($request) {

        default:
            $response["error"] = "Ошибка. Данный запрос не модет быть обработан.";
        break;
    }
    
    ob_end_clean();
    echo json_encode($response);
    if (ob_get_level() > 0) ob_flush();
    
    die();
}
// else 
// {
//     if (!$_SERVER["REQUEST_URI"]) include_once DIR.realroute(route('main')).'/index.php';
//     else {
//         if (!empty($route)) {
//             //Включаем буфер вывода
//             ob_start(); //ob_start("ob_gzhandler");
//             include DIR.realroute(route('main')).'/index.php';
//             $content = ob_get_contents();
//             ob_end_clean();
//         }
//         else {
//             include_once DIR.realroute(route('404')) ;
//         }
//          () ? DIR.$route.'/index.php' :
//     }
// }
else 
{
    if (!$_SERVER["REQUEST_URI"]) include_once DIR.realroute(route('main')).'/index.php';
    else include_once (!empty($route)) ? DIR.$route.'/index.php' : DIR.realroute(route('404')) ;
}
/** */


?>