<?php

final class VarHelper
{
    
	static function phone_valid($phone)
    {
        $phone = strval($phone);
        if ($phone[0] == "9" && strlen($phone) > 9) return false;
        if (strlen($phone < 10 && $phone > 10)) return false;
        if ($phone[0] != "7" && $phone[0] != "+" && $phone[0] != "8") return false;
        return true;
    }

	static function prepare_phone($phone)
	{
		$phone = strval($phone);
		if (preg_match("/\(/",$phone)) return $phone;
		$result = $phone;
		if ($phone[0] == "8" || $phone[0] == "7") {
			$result = "+7 (".substr($phone,1,3).") ".substr($phone,4,3)."-".substr($phone,7,2)."-".substr($phone,9,2);
		}
		return $result;
	}


    static function email_valid($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) return true;
        else return false;
    }


    static function post_valid($post,$haystack)
    {
        foreach ($post as $key => $value) {
        if (!in_array($key,$haystack)) return false;
        }
        return true;
    }
    
    static function date_text($timestamp=false,$type='full')
	{ 
		if(!$timestamp){ $timestamp=date('d.m.Y'); }
		$timestamp=(is_numeric($timestamp))?$timestamp:strtotime($timestamp);
		$monthArray_p1=array(1=>'январь','февраль','март','апрель','май','июнь','июль','август','сентябрь','октябрь','ноябрь','декабрь');
		$monthArray=array(1=>'января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря');
		$monthArrayShort=array(1=>'янв','фев','мар','апр','мая','июн','июл','авг','сен','окт','нояб','дек');
		switch ($type) {
			case 'full':
				// $date='&laquo;'.date('d',$timestamp).'&raquo; '.$monthArray[date('n',$timestamp)].' '.date('Y',$timestamp).' г.';
				$date=date('d',$timestamp).' '.$monthArray[date('n',$timestamp)].' '.date('Y',$timestamp).' г.';
				break;
			case 'full-h':
				// $date='&laquo;'.date('d',$timestamp).'&raquo; '.$monthArray[date('n',$timestamp)].' '.date('Y',$timestamp).' г.';
				$date=date('d',$timestamp).' '.$monthArray[date('n',$timestamp)].' '.date('Y',$timestamp).' г. '.date("H:i:s",$timestamp);
				break;
			case 'short':
				$date=date('j',$timestamp).' '.$monthArrayShort[date('n',$timestamp)];
				break;
			case 'short-y':
				$date=date('j',$timestamp).' '.$monthArrayShort[date('n',$timestamp)].' '.date('y',$timestamp);
				break;
			case 'short-y-int':
				$date=date('j',$timestamp).'.'.date('n',$timestamp).'.'.date('y',$timestamp);
				break;
			case 'short-Y':
				$date=date('j',$timestamp).' '.$monthArrayShort[date('n',$timestamp)].' '.date('Y',$timestamp);
				break;
			case 'month-y':
				$date=$monthArray_p1[date('n',$timestamp)].' '.date('y',$timestamp);
				break;
			case 'short his':
				$date=date('j',$timestamp).' '.$monthArrayShort[date('n',$timestamp)].' '.date('H:i:s',$timestamp);
				break;
			case 'short hi':
				$date=date('j',$timestamp).' '.$monthArrayShort[date('n',$timestamp)].' '.date('H:i',$timestamp);
				break;
			case 'dynamic':
				$diff=time()-$timestamp;
				//дата до 3 минут
				if($diff < 60*1){ return 'Только что'; }
				//дата до часу
				if($diff < 60*60){ return ceil($diff/60).' мин назад'; }
				//сегодня
				if($diff<60*60*24 && date('j',$timestamp) == date('j')){ return 'Сегодня '.date('H:i', $timestamp); }
				//Вчера
				if($diff<60*60*24*2 && ( date('j') - date('j',$timestamp) )==1 ){ return 'Вчера '.date('H:i', $timestamp); }
				//В этом году
				if(date('Y',$timestamp) == date('Y')){ return date('j',$timestamp).' '.$monthArrayShort[date('n',$timestamp)].', '.date('H:i',$timestamp); }
				//В других случаях полную дату вернем
				return date('j',$timestamp).' '.$monthArrayShort[date('n',$timestamp)].' '.date('Y',$timestamp);
				break;
			default:
				$date='ТАКОЙ ДАТЫ НЕТ';
				break;
		}
		return $date;
	}
	
	/* Сумма прописью */
	static function price_text($num, $price=true, $onlyText=false)
	{
		$nul='ноль';
		$ten=array(
			array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
			array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
		);
		$a20=array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
		$tens=array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
		$hundred=array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
		$unit=array( // Units
			array('копейка' ,'копейки' ,'копеек',	 1),
			array('рубль'   ,'рубля'   ,'рублей'    ,0),
			array('тысяча'  ,'тысячи'  ,'тысяч'     ,1),
			array('миллион' ,'миллиона','миллионов' ,0),
			array('миллиард','милиарда','миллиардов',0),
		);
		//
		list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
		$out = array();
		if (intval($rub)>0) {
			foreach(str_split($rub,3) as $uk=>$v) { // by 3 symbols
				if (!intval($v)) continue;
				$uk = sizeof($unit)-$uk-1; // unit key
				$gender = $unit[$uk][3];
				list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
				// mega-logic
				$out[] = $hundred[$i1]; # 1xx-9xx
				if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; # 20-99
				else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
				// units without rub & kop
				if ($uk>1) $out[]= self::morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
			} //foreach
		}
		else $out[] = $nul;
		$text_out=array(); $num=ceil($num);
		$text_out[]=number_format($num, 0, ',', " ");
		$text_out[]='('.trim(preg_replace('/ {2,}/', ' ', join(' ',$out))).')';
		if($price){
			$text_out[] = self::morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]); // rub
			$text_out[] = $kop.' '.self::morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]); // kop
		}
		if($onlyText){ $text_out=array();
			$text_out[]=trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
		}
		return trim(preg_replace('/ {2,}/', ' ', join(' ',$text_out)));
	}


	static function price_format($int,$ruble = true){
		// $int = floatval($int);
		$float_part =  (is_float($int)) ? 2 : 0;
		if ($int == 0) return 0;
		$ruble = ($ruble) ? "₽" : "";
		 return number_format(floatval($int) , $float_part , "," ," " )." ".$ruble;
	}

    static function is_string($str) 
    {
         if (!empty($str) && !empty(trim($str)) && (gettype($str) == "string" || gettype($str) == "integer")) return true;
         return false;
    }
    

    static function echo($true,$false,$method)
    {
        switch(trim(strtolower($method)))
        {
            case "string": case "str": case "is_string":
                echo (!empty($true) && !self::is_string($true)) ? trim($true) : $false;
                break;
            case "int": case "integer": case "is_integer":
                echo (!empty($true) && !self::is_integer($true)) ? intval($true) : $false;
                break;
            default: 
                return NULL;
                break;
        }
    }

    static function sqlstr($key,$value) 
    {
        return (($key == "id" || preg_match("/\_id/",$key)) || preg_match("/\_int/",$key)) || in_array($key,["hidden","sum","cost","price"]) ? (is_float($value)) ? floatval($value) : intval($value) : trim($value);
    }

    # 100% int
    static function is_integer($var)
    {
        $var = (preg_replace('/,/i',".",$var)) ; 
        if ( 
            empty($var) ||
            (gettype($var) != "string" && gettype($var) != "integer") ||
            gettype($var) == "string" && empty(trim($var)) ||
            !is_numeric($var) ||
            intval($var) == 0			
        )  return false;
        else return true;
    }
    // преобразует CSV в массив
    static function convert_csv($url)
    {
        $result = [];
        $value = fopen($url, "r") ; $i = 0;
        if ($value === FALSE) return NULL;
        while (($data = fgetcsv($value)) !== FALSE) {
            foreach ($data as $key => $val) {
                if (preg_match('/\;/',$val) ) {
                    foreach (explode(';',$val) as $j => $_val){
                        if (empty($_val)) continue;
                        $result[$i][] = trim(strval($_val));
                    }
                }
                else $result[$i][] = trim(strval($val));
            }
            $i++;
        }
        fclose($value);
        if (count($result > 1)) return $result; // если больше одной таблицы
        else return $result[0]; 
    }

    static function options($array){
        if (!ok($array)) return $array;
        $options = array();
        $options[] = array("text"=>"Не выбрано","value"=>"none");
        foreach ($array as $i => $row) {
            if (!isset($row["value"])){
                if (empty($row["id"])) continue;
                else $val = $row["id"];
            }
            else $val = $row["value"];
            // if (empty($row["id"])) continue;
            if (empty($row["title"])) {
                if (!empty($row["fio"])) $title = $row["fio"];
                else if (!empty($row["name"])) {
                    $title = "";
                    if (!empty($row["surname"])) $title .= $row["surname"]." ";
                    $title .= $row["name"];
                    if (!empty($row["patronym"])) $title .= " ".$row["patronym"];
    
                } 
                else if (!empty($row["text"])) $title = $row["text"];
                // else continue;
                else $title = "";
            }
            
            else $title = $row["title"];
    
            if (isset($val)) $options[] = array("value"=>$val,"text"=>$title);
        }
        return $options;
	}
		//Функции преобразования массивов (обработка json)
        static function json_encode($str)
        {
            try {
                $arr_replace_utf = array('\u0410', '\u0430', '\u0411', '\u0431', '\u0412', '\u0432', '\u0413', '\u0433', '\u0414', '\u0434', '\u0415', '\u0435', '\u0401', '\u0451', '\u0416', '\u0436', '\u0417', '\u0437', '\u0418', '\u0438', '\u0419', '\u0439', '\u041a', '\u043a', '\u041b', '\u043b', '\u041c', '\u043c', '\u041d', '\u043d', '\u041e', '\u043e', '\u041f', '\u043f', '\u0420', '\u0440', '\u0421', '\u0441', '\u0422', '\u0442', '\u0423', '\u0443', '\u0424', '\u0444', '\u0425', '\u0445', '\u0426', '\u0446', '\u0427', '\u0447', '\u0428', '\u0448', '\u0429', '\u0449', '\u042a', '\u044a', '\u042b', '\u044b', '\u042c', '\u044c', '\u042d', '\u044d', '\u042e', '\u044e', '\u042f', '\u044f', '\u2116');
                $arr_replace_cyr = array('А', 'а', 'Б', 'б', 'В', 'в', 'Г', 'г', 'Д', 'д', 'Е', 'е', 'Ё', 'ё', 'Ж', 'ж', 'З', 'з', 'И', 'и', 'Й', 'й', 'К', 'к', 'Л', 'л', 'М', 'м', 'Н', 'н', 'О', 'о', 'П', 'п', 'Р', 'р', 'С', 'с', 'Т', 'т', 'У', 'у', 'Ф', 'ф', 'Х', 'х', 'Ц', 'ц', 'Ч', 'ч', 'Ш', 'ш', 'Щ', 'щ', 'Ъ', 'ъ', 'Ы', 'ы', 'Ь', 'ь', 'Э', 'э', 'Ю', 'ю', 'Я', 'я', '№');
                $json = defined('JSON_UNESCAPED_UNICODE')
                ? json_encode($str, JSON_UNESCAPED_UNICODE)
                : str_replace($arr_replace_utf, $arr_replace_cyr, json_encode($str));
                return $json;
                //code...
            } catch (\Throwable $th) {
                //throw $th;
                return array("error"=>"Системная ошибка при JSON-кодировании");
            }
        }
        static function json_decode($obj_decode)
        {
            try {
                if(is_array($obj_decode)){ die('Передан неправильный параметр: '.var_export($obj_decode,true)); }
                $obj = json_decode($obj_decode, true);
                return (array)$obj;
            } catch (\Throwable $th) {
                return array("error"=>"Системная ошибка при JSON-декодировании");
                //throw $th;
            }
        }
}

?>