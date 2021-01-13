<?php 


	/** 为空返回none*/
	function HandleNull($fpJudge,$fpNullValue){
	    if(IsNull($fpJudge)){
	        return $fpNullValue;
	    }
	    return $fpJudge;
	}