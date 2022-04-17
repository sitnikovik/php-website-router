<?php

function getroutes()
{
    return [
        // pages
        "main" => [
            "url" => "/",
            "route" => "/view/home",
            "layout"=>"/view/layout"
        ],
        

        // errors
        "404" => [
            'url'=>'/404/',
            "route" => "/view/404.php",
        ],
        "500" => [
            'url'=>'/500/',
            "route" => "/view/500.php",
        ]
    ];
}

// вовзращает путь к нужной странице для адресной строке
function route($needle, $get = "")
{
    $get = (!empty($get) && gettype($get) == "string") ? trim($get) : "";
    $routes = getroutes();
    if (!empty($routes[$needle])) {
        $url = $routes[$needle]["url"];
        /**
         * передать в URL красивый параметр GET если есть в {{}} в url
         *
        if (!empty($get)) {
            $get = str_replace(["/","?"],"",$get);
            $_get = explode("&",$get);
            $isset_get = $_get; 
            foreach ($_get as $i=>$str) {
                $arr = explode("=",$str);
                if (strpos($url,"{{".$arr[0]."}}")) { // такой параметр есть в урле
                    $url = str_replace("{{".$arr[0]."}}",$arr[1],$url);
                    // + тогда удалить это пар-р из GET. он уже там не нужен
                    $get = str_replace(["&$str&","&$str","$str&",$str],"",$get);
                    unset($isset_get[$i]);
                }
            }
            $get = ((!empty($isset_get)) ? "/?" : "").$get;
        }
        /** */
       return $url.$get;
    }
    else return "";
}
// вовзращает реальный путь из url адресной строки 
function realroute($url, $get = "")
{
    $url = explode("?",$url)[0];
    foreach (getroutes() as $key => $array) if ($array["url"] == $url) return (!empty($array["route"])) ? $array["route"] : $array["url"];
}

?>