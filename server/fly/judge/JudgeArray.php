<?php 

    /**
     * 判断数组成员
     * $fpArray:要判断的数组
     * $fpMember:要判断的成员
     * $fpStrtolower:是否忽略大小写，到小写之后进行比较
     * 整理时间：2019-09-24 18:01:51
     * */
    function JudgeArrayMember($fpArray,$fpMember,$fpStrtolower=false){ 
        if(!is_array($fpArray)){return $fpArray==$fpMember;}
        if(!$fpStrtolower){
            for($i=0;$i<sizeof($fpArray);++$i){
                if($fpArray[$i] == $fpMember){
                    return true;
                }
            }
            return false;
        }else{
            for($i=0;$i<sizeof($fpArray);++$i){
                if(strtolower($fpArray[$i]) == strtolower($fpMember)){
                    return true;
                }
            }
            return false;
        }
    }
    
    