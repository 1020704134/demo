<?php 


	/** 
	 * 数组组合
	 * $fpArray：要拼接的数组
	 * $fpConnector：成员拼接符
	 * 整理时间：2019-09-25 12:27:05
	 * */
	function HandleArrayConnect($fpArray,$fpConnector){
        $string = "";
        for($i=0;$i<sizeof($fpArray);++$i){
            $string .= $fpArray[$i].$fpConnector;
        }
        if(!IsNull($string)){
            $string = substr($string,0,strlen($string)-strlen($fpConnector));
        }
        return $string;
	}
	

    /** 
     * 将一个Json数组字符串组合成一个字符串 
     * $fpJsonArrayString：Json数组字符串
     * $fpField：要获取的Json对象字段
     * $fpConnector：拼接符
	 * 整理时间：2019-09-25 15:09:46
     * */
	function HandleArrayJsonConnect($fpJsonArrayString,$fpField,$fpConnector){
	    $string = "";
	    $fpJson = json_decode($fpJsonArrayString);
	    foreach($fpJson as $member){
	        if(IsNull($fpField)){
	            $string .= $member . $fpConnector;
	        }else{
	            $string .= $member->$fpField . $fpConnector;
	        }
	    }
	    if(!IsNull($string)){
	        return substr($string,0,-1);
	    }
	    return $string;
	}
	
	
	/**
	 * 处理数组
	 * 数组形式为 key=>value 的方式，组合成 key=value; 的方式 
	 * */
	function HandleArrayKeyValue($fpArray){
	    $vString = "";
	    foreach($fpArray as $vKey => $vValue){
	        $vString .= $vKey . "=" . $vValue .";";
	    }
	    if(!IsNull($vString)){
	        $vString = HandleStringDeleteLast($vString);
	    }
	    return $vString;
	}
	
	/** 将两个数组合并到一起 */
	function HandleArrayMerge($fpArrayOne,$fpArrayTwo){
	    if(IsNull($fpArrayOne)&&!IsNull($fpArrayTwo)){         //A数组为空，B数组不为空，返回B数组
	        return $fpArrayTwo;
	    }else if(!IsNull($fpArrayOne)&&IsNull($fpArrayTwo)){   //A数组不为空，B数组为空，返回A数组
	        return $fpArrayOne;
	    }else if(IsNull($fpArrayOne)&&IsNull($fpArrayTwo)){    //A数组为空，B数组为空，返回空
	        return "";
	    }
	    foreach($fpArrayTwo as $memberB){                      //A数组不为空，B数组不为空，返回A数组与B数组的合并数组
	        array_push($fpArrayOne, $memberB);
	    }
	    return $fpArrayOne;
	}
		