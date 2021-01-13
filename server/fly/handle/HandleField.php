<?php

    
    /** 字段字符串字段删除 */
    function HandleFieldDelete($fieldString,$fieldName){
         
        $split = ",".$fieldName.",";
        if(strpos($fieldString,$split)>0){
            $array = explode($split,$fieldString);
            return $array[0] . "," . $array[1];
        }
         
        $split = ",".$fieldName;
        if(strpos($fieldString,$split)>0){
            $array = explode($split,$fieldString);
            return $array[0];
        }
         
        $split = $fieldName . ",";
        if(strpos($fieldString,$split)==0){
            return substr($fieldString,strlen($split));
        }
         
        return $fieldString;
    }
    

