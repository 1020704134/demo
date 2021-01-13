<?php 

    
    /** 
     * 判断是否为空
     * 整理时间：2019-09-24 18:12:28
     * */
	function IsNull($fpString){
	    return $fpString==''||!isset($fpString)||$fpString==null;
	}
	
	
	/** 
	 * 判断变量为none
	 * 整理时间：2019-09-24 18:13:13 
	 * */
	function IsNone($parameter){
	    return $parameter=='none';
	}

	
	/**
	 * 判断为空或为0
	 * 整理时间：2019-09-28 18:54:13
	 * */
	function IsNullZero($fpString){
	    return $fpString==''||!isset($fpString)||$fpString==null||$fpString=="0";
	}

	 