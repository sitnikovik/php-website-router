<?php

function env($name) {
    $path = DIR.'/../starlight.config.json';
    if (!file_exists($path)) return false;
    $env = json_decode(file_get_contents($path),true);
    if (!empty($env[$name])) return trim($env[$name]);
    else return false;
}

function pre($var1, $var2 = false, $var3 = false)
{
    echo "<pre style='padding: 0.5rem 1rem;
    background-color: #44475A;
    color:#21eff3;'>";
    var_export($var1);
    if (!empty($var2)) { echo ("<hr>"); var_export($var2); }
    if (!empty($var3)) { echo ("<hr>"); var_export($var3); }
    echo "</pre>";
}
function kill($var1, $var2 = false, $var3 = false)
{
    echo "<pre style='padding: 0.5rem 1rem;
    background-color: #44475A;
    color:#21eff3;'>";
    var_export($var1);
    if (!empty($var2)) { echo ("<hr>"); var_export($var2); }
    if (!empty($var3)) { echo ("<hr>"); var_export($var3); }
    echo "</pre>";
    exit;
}


function need($path,$data = [])
{
    require ( DIR.$path.".php");
    if (!empty($data)) return $data;
}
function inc($path,$data = [])
{
    include (DIR.$path.".php");
    if (!empty($data)) return $data;
}

function inc_here($path,$data = [])
{
    $trace = debug_backtrace();
    include ( dirname($trace[0]["file"]).$path.".php");
    if (!empty($data)) return $data;
}

//  file_get_contents аналог
function textfrom($path)
{
    if (!file_exists(DIR.$path)) return "";
    return file_get_contents(DIR.$path);
}

function csrf()
{
    echo '<input type="hidden" name="csrf" value="'.$_COOKIE["PHPSESSID"].'">';
}


function printif($true,$false,$method = "string")
{
    switch(trim(strtolower($method)))
    {
        case "string": case "str": case "is_string":
            echo (!empty($true) && VarHelper::is_string($true)) ? trim($true) : $false;
            break;
        case "int": case "integer": case "is_integer":
            break;
        default: 
            return NULL;
            break;
    }
}

// остановить вполнение кода переадресацией на другую стр (костыль ?)
function killpage($error = "500")
{
    // die(header('HTTP/1.0 404 not found',true));
    die(header("Location: http://".$_SERVER["HTTP_HOST"]."/error/".$error));
}

function error($code = "404") 
{
    $str = "Что-то не так. Запрос не выполнен. Обратитесь к администратору!";
    if (!empty($code)) {
        if (is_numeric($code)) {
            $code_str = SiteHelper::httpcode($code);
            $str =  (!empty($code_str)) ? trim($code_str)." Код ".trim($code) : "";
        }
        else $str = trim($code);
    }
    return array("error"=>$str,"errorCode"=>intval($code)) ;
}

function success($str = "Запрос выполнен без ошибок") 
{
    return array("success"=>$str);
}

function redirect($str,$msg="Запрос выполнен",$type="success") 
{
    return array("redirect"=>$str,$type=>$msg);
}

function ok($fetch) 
{
    if (isset($fetch["redirect"]) || isset($fetch["error"]) || empty($fetch)) {
        // if (!seslf::$dev_mode) self::log($fetch);
        return false;
    }
    return true;
}



// удалить/заменить строку в файле\везде
function replace_global($find="", $value="", $main_dir = false)
{
    echo "replace_global()<hr>";
  
    if (!empty($main_dir) && !file_exists($main_dir)) return error("нет такой папки");
    $main_dir = (!empty($main_dir)) ? $main_dir : DIR;
    echo "это main_dir $main_dir<br>";
    $main_dir = dir_shift(scandir($main_dir)); // удаляем все ненужное из массива scandir

    foreach ($main_dir as $i => $path) {
        $dirname = DIR."/".$path;
        if (substr($path,0,1) == ".") continue;
        if (is_dir($dirname)) {  // если это папка и не скрытая
            echo "это папка $path<br>";
            replace_global($find,$value,$dirname);
        }
        else {
            echo "это файл $path<br>";
            #file_put_contents($dirname,str_replace($find,$value,textfrom("/".$path)));
        }
      
    }

    return NULL;

}

function link_prepare($str)
{
    if (!empty($_GET))
    {
        $que = (preg_match("/\?/",$str)) ? "&" : "?";
        if (!empty($_GET["utm_source"])) return $str.$que."utm_source=".$_GET["utm_source"];
    }
    return $str; 
}

function dir_shift($path){
    $array = array();
    foreach ($path as $key => $value) {
        if (!in_array(trim($value),array(".","..",".DS_Store"))) $array[] = trim($value);
    }
    if (empty($array)) return $path;
    else return $array;
}

?>