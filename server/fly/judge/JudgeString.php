<?php 

	/** 
	 * 判断字符串范围长度
	 * $fpString：字符串
	 * $fpMinLength：最小长度
	 * $fpMaxLength：最大长度
	 * 整理时间：2019-09-24 18:36:46
	 * */
	function JudgeStringLength($fpString,$fpMinLength,$fpMaxLength){
	    if(IsNull($fpString)){return false;}
	    $length = mb_strlen($fpString,'utf8');
	    if($length>=$fpMinLength && $length<=$fpMaxLength){return true;}
	    return false;
	}
	
	/** 
	 * 判断字符串长度相等
	 * $fpString：判断字符串
	 * $fpLength：字符串判断长度
	 * 整理时间：2019-09-24 18:36:54
	 * */
	function JudgeStringLengthEquest($fpString,$fpLength){
	    if(IsNull($fpString)){return false;}
	    $thisLength = mb_strlen($fpString,'utf8');
	    if($thisLength==$fpLength){return true;}
	    return false;
	}
	
	/** 
	 * 判断字符串相等
	 * $fpStringOne：比较字符串一
	 * $fpStringTwo：比较字符串二
	 * 整理时间：2019-09-24 18:38:45
	 * */
	function JudgeStringEquest($fpStringOne,$fpStringTwo){
    	return HandleStringToLowerCase($fpStringOne)==HandleStringToLowerCase($fpStringTwo);
	}

	
