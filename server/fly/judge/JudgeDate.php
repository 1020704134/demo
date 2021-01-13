<?php 

	/**
	 * 判断时间范围
	 * $fpJudgeDate：要判断的日期时间
	 * $fpStartDate：范围开始时间
	 * $endDate：范围结束时间
	 * 整理时间：2019-09-24 18:02:11
	 * */
    function JudgeDateRange($fpJudgeDate,$fpStartDate,$fpEndDate){
        $curTime = strtotime($fpJudgeDate);
        $timestampStart = strtotime($fpStartDate);
        $timestampEnd = strtotime($fpEndDate);
        if( $curTime>$timestampStart && $curTime<$timestampEnd ){
            return true;
        }
        return false;
    }
    
	
