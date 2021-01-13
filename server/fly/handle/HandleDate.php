<?php 

	/**
	 * 在原有日期上加分钟数得到信息日期
	 * $fpDate：要计算的基础时间
	 * $fpTimeMinute：加或减的分钟数
	 * $fpPlusBo：默认为在原有时间基础上增加分钟数，false为减去
	 * 整理时间：2019-09-25 15:16:53
	 * DEMO：HandleDatePlusMinute(HandleDateToStrtotime($vThisTime), "30",false);
	 * */
	function HandleDatePlusMinute($fpDate,$fpTimeMinute,$fpPlusBo=true){
	    if($fpPlusBo){
	        return date('Y-m-d H:i:s',strtotime("{$fpTimeMinute} minutes", $fpDate));
	    }else{
	        return date('Y-m-d H:i:s',strtotime("-{$fpTimeMinute} minutes", $fpDate));
	    }
	}
	
	
	/**
	 * 在原有日期上加减年单位
	 * $fpDate：要计算的基础时间
	 * $fpDay：加或减的天数
	 * $fpPlusBo：默认为在原有时间基础上增加天数，false为减去
	 * 整理时间：2019-09-25 17:42:10
	 * */
	function HandleDatePlusDay($fpDate,$fpDay,$fpPlusBo=true){
	    $fpDateStrtotime = HandleDateToStrtotime($fpDate);
	    if($fpPlusBo){
	        return date('Y-m-d H:i:s',strtotime("{$fpDay}day", $fpDateStrtotime));
	    }else{
	        return date('Y-m-d H:i:s',strtotime("-{$fpDay}day", $fpDateStrtotime));
	    }
	}
	
	/**
	 * 在原有日期上加减年单位
	 * $fpDate：要计算的基础时间
	 * $fpYear：加或减的年数
	 * $fpPlusBo：默认为在原有时间基础上增加年数，false为减去
	 * 整理时间：2019-09-25 17:44:18
	 * */
	function HandleDatePlusYear($fpDate,$fpYear,$fpPlusBo=true){
	    if($fpPlusBo){
	        return date('Y-m-d H:i:s',strtotime("{$fpYear}year", $fpDate));
	    }else{
	        return date('Y-m-d H:i:s',strtotime("-{$fpYear}year", $fpDate));
	    }
	}
	
	/**
	 * 时间格式转化
	 * $fpDate：要转化的日期字符串
	 * $fpFormat：要转化的时间格式
	 * 整理时间：2019-09-25 17:55:33
	 * */
	function HandleDateFormat($fpDate,$fpFormat){
	    return date($fpFormat,strtotime($fpDate));
	}
	
	
	/**
	 * 日期数据转时间戳
	 * $fpDate：要转化的日期字符串
	 * 整理时间：2019-09-25 17:55:55
	 * */
	function HandleDateToStrtotime($fpDate){
        return strtotime($fpDate);
	}
	
	
	/**日期数据转时间戳*/
	function HandleDateStrtotimeToDate($fpTimestamp){
	    return date('Y-m-d H:i:s',$fpTimestamp);
	}

	
