<?php 


	/** 删除最后一个字符*/
	function HandleStringDeleteLast($string){return substr($string,0,strlen($string)-1);}
	/** 删除最后两个字符*/
	function HandleStringDeleteLastTwo($string){return substr($string,0,strlen($string)-2);}
	/** 删除第一个字符*/
	function HandleStringDeleteFirst($string){return substr($string,1);}
	/** 删除空格*/
	function HandleStringDeleteSpace($string){ return trim($string); }
	
	/** 到小写*/
	function HandleStringToLowerCase($string){return strtolower($string);}
	/** 到大写*/
	function HandleStringToUpperCase($string){return strtoupper($string);}
	/** 到整数*/
	function HandleStringToInt($string){return intval($string);}
	/** 到数值*/
	function HandleStringToNumber($string){return floatval($string);}
	
	
	/** 添加单引号*/
	function HandleStringAddQuotation($string){return "'".$string."'";}
	/** 添加双引号*/
	function HandleStringAddQuotationDouble($string){return '"'.$string.'"';}
	/** 添加圆括号*/
	function HandleStringAddBrackets($string){return '('.$string.')';}
	
	/** 字符串转义*/
	function HandleStringAddslashes($string){return addslashes($string);}
	
	
	/** 匹配替换:str_replace*/
	function HandleStringReplace($string,$regular,$newString){return str_replace($regular,$newString,$string);}
	/** 匹配替换:preg_replace*/
	function HandleStringPregReplace($string,$regular,$newString){return preg_replace($regular,$newString,$string);}
	
	
	/** 编码转化 */
	function HandleStringCodeConvert($str,$strType,$toType){
	    return mb_convert_encoding($str, $strType, $toType);
	}
	
	
	/** MD5加密 */
	function HandleStringMD5($password){
	    return md5(md5($password).md5($password));
	}
	
	/**
	 * 函数:字符串编码
	 * 说明:对字符串进行指定内容的替换，替换成为Fly自定义的HTML实体
	 * 时间:December 28, 2018 18:13:00
	 * */
	function HandleStringFlyHtmlEncode($str){
		$str = HandleStringPregReplace($str,'/[\s]+/i',' ');
		$str = HandleStringReplace($str,'&','^amp;');
		$str = HandleStringReplace($str,array("/r", "/n", "/r/n"),'^nr;');
		$str = HandleStringReplace($str,'/','^sla;');
		$str = HandleStringReplace($str,'\\','^bsl;');
		$str = HandleStringReplace($str,'"','^quot;');
		$str = HandleStringReplace($str,"'",'^apos;');
		$str = HandleStringReplace($str,'{','^lcb;');
		$str = HandleStringReplace($str,'}','^rcb;');
		$str = HandleStringReplace($str,'[','^lb;');
		$str = HandleStringReplace($str,']','^rb;');
		$str = HandleStringReplace($str,'$','^usd;');
		$str = HandleStringReplace($str,',','^com;');
		$str = HandleStringReplace($str,'?','^qm;');
		$str = HandleStringReplace($str,':','^col;');
		$str = HandleStringReplace($str,'=','^eq;');
		//$str = HandleStringReplace($str,'<','^lt;');
		//$str = HandleStringReplace($str,'>','^gt;');
		//$str = HandleStringReplace($str,'(','$lp;');
		//$str = HandleStringReplace($str,')','$rp;');
		return $str;
	}

	
	/**
	 * 函数:字符串编码
	 * 说明:对字符串进行指定内容的替换，由Fly自定义的HTML实体转化成HTML实体
	 * 时间:December 28, 2018 18:13:00
	 * */
	function HandleStringFlyHtmlDecode($str){
	    $str = HandleStringReplace($str,'^col;',':');
	    $str = HandleStringReplace($str,'^qm;','?');
	    $str = HandleStringReplace($str,'^com;',',');
	    $str = HandleStringReplace($str,'^usd;','$');
	    $str = HandleStringReplace($str,'^rb;',']');
	    $str = HandleStringReplace($str,'^lb;','[');
	    $str = HandleStringReplace($str,'^rcb;','}');
	    $str = HandleStringReplace($str,'^lcb;','{');
	    $str = HandleStringReplace($str,'^apos;',"'");
	    $str = HandleStringReplace($str,'^quot;','"');
	    $str = HandleStringReplace($str,'^bsl;','\\');
	    $str = HandleStringReplace($str,'^sla;','/');
	    $str = HandleStringReplace($str,'^nr;',' ');
	    $str = HandleStringReplace($str,'^amp;','&');
	    $str = HandleStringReplace($str,'^eq;','=');
	    //$str = HandleStringReplace($str,'<','^lt;');
	    //$str = HandleStringReplace($str,'>','^gt;');
	    //$str = HandleStringReplace($str,'(','$lp;');
	    //$str = HandleStringReplace($str,')','$rp;');
	    return $str;
	}


	/** 处理参数 */
	function HandleStringParameter($fpParameter){
        if(strpos($fpParameter,",")>-1){ return str_replace(",","^com;",$fpParameter); }
        return $fpParameter;
	}
	
	
	/** 为空返回none*/
	function HandleStringNone($string){
	    if(IsNull($string)){
	        $string = "none";
	    }
	    return $string;
	}
	
	
	/** 下划线转小驼峰命名*/
	function HandleStringUnderlineToHump($fpString){
	    $fpStringArray = explode("_",$fpString);
	    $vStringHump = "";
	    for($i=0;$i<sizeof($fpStringArray);$i++){
	        if($i==0){
	            $vStringHump .= $fpStringArray[$i]; 
	        }else{
	            $vStringHump .= ucfirst($fpStringArray[$i]);
	        }
	    }
	    return $vStringHump;
	}

	
	/** 处理路径*/
	function HandleStringPath($fpPath){
	    if(IsNull($fpPath)){
	        $string = "";
	    }
	    $fpPath = HandleStringReplace($fpPath, "\\", "/");
	    return $fpPath;
	}
	
	/** 处理数据库记录特殊符号*/
	function HandleStringDbRecodeValue($fpValue){
	    if(IsNull($fpValue)){ $string = ""; }
	    $fpValue = HandleStringReplace($fpValue, "\\", "/");
	    $fpValue = HandleStringReplace($fpValue, "\"", "^quot;");
	    return $fpValue;
	}
	
	
	/**
	 * Unicode编码
	 * 2020-04-07 19:27:42
	 * */
	function UnicodeEncode($fpString){
	    $name = iconv('UTF-8', 'UCS-2', $fpString);  
        $len  = strlen($name);  
        $str  = '';  
        for ($i = 0; $i < $len - 1; $i = $i + 2){  
            $c  = $name[$i];  
            $c2 = $name[$i + 1];  
            if (ord($c) > 0){   //两个字节的文字  
                $str .= '\u'.base_convert(ord($c), 10, 16).str_pad(base_convert(ord($c2), 10, 16), 2, 0, STR_PAD_LEFT);  
            } else {  
                $str .= '\u'.str_pad(base_convert(ord($c2), 10, 16), 4, 0, STR_PAD_LEFT);  
            }  
        }  
        $str = strtoupper($str);//转换为大写  
        return $str;  
	}	
	
	
	/**
	 * Unicode编码
	 * 2020-04-07 19:27:42
	 * */
	function UnicodeDecode($fpString){
	    $name = strtolower($fpString);
	    // 转换编码，将Unicode编码转换成可以浏览的utf-8编码
	    $pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
	    preg_match_all($pattern, $name, $matches);
	    if (!empty($matches))
	    {
	        $name = '';
	        for ($j = 0; $j < count($matches[0]); $j++)
	        {
	            $str = $matches[0][$j];
	            if (strpos($str, '\\u') === 0)
	            {
	                $code = base_convert(substr($str, 2, 2), 16, 10);
	                $code2 = base_convert(substr($str, 4), 16, 10);
	                $c = chr($code).chr($code2);
	                $c = iconv('UCS-2', 'UTF-8', $c);
	                $name .= $c;
	            }
	            else
	            {
	                $name .= $str;
	            }
	        }
	    }
	    return $name;
	}