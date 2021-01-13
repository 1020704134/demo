<?php


class DBMySQLService{
    
    
	/**
	 * 添加数据
     * 更新时间：November 29,2018 18:09
	 * 说明：添加数据记录，成功返回记录onlyKey，可添加的记录进行再次操作
	 * 检测：Post提交、区域注释、固参判断
	 * */
	public static function ServiceDataInsert($json){
	    
	    //--- 参数获取区 ---
	    //参数:数据表名称
	    $tablename = GetParameter("tablename",$json);
	    if(IsNull($tablename)){return JsonModelParameterNull("tablename");}
	    //参数:要添加数据不字段
	    $insertfield = GetParameter("insertfield",$json);
	    if(IsNull($insertfield)){return JsonModelParameterNull("insertfield");}
	    
	    //处理:添加字段的参数值
	    $insertfieldValue = "";
	    $insertfieldArray = GetArray($insertfield, ",");
	    //值字符串
	    for($i=0;$i<GetArraySize($insertfieldArray);$i++){
	        $field = $insertfieldArray[$i];
	        $value = GetParameter($field,$json);
	        if(IsNull($value)){
	            return JsonModelParameterNull($field);
	        }
	        $insertfieldValue .= "'".$value."'".",";
	    }
	    if(!IsNull($insertfieldValue)){
	        $insertfieldValue = HandleStringDeleteLast($insertfieldValue);
	    }
	    
	    //--- 请求区 ---
	    $sql = "INSERT INTO ? (?) values(?);";
	    return DBHelper::DataSubmit($sql, array($tablename,$insertfield,$insertfieldValue));
	}
    
	/**
	 * 函数说明：修改数据表指定字段+1
	 * 添加时间：2020-10-15 11:56:15
	 * */
	public static function ServiceDataPlusOne($fpTable,$fpId,$fpField){
	    $sql = "UPDATE {$fpTable} SET {$fpField}={$fpField}+1 WHERE id='{$fpId}';";
	    DBHelper::DataSubmit($sql, null);
	}
    
}

