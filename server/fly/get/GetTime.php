<?php 
	
    /** 获取当前时间*/
    function GetTimeNow(){return date('Y-m-d H:i:s',time());}
    /** 获取当前时间数字*/
    function GetTimeNowNumber(){return date('YmdHis',time());}
    /** 获取当前日期*/
    function GetTimeDate(){return date('Y-m-d',time());}
    /** 获取当前日期*/
    function GetTimeDateNumber(){return date('Ymd',time());}
    /** 获取当前时间*/
    function GetTime(){return date('H:i:s',time());}
    /** 获取当前年*/
    function GetTimeYear(){return date('Y',time());}
    /** 获取当前月*/
    function GetTimeMonth(){return date('m',time());}
    /** 获取当前天*/
    function GetTimeDay(){return date('d',time());}
    /** 获取当前时间*/
    function GetTimeHour(){return date('H',time());}
    /** 获取当前分*/
    function GetTimeMinute(){return date('i',time());}
    /** 获取当前天*/
    function GetTimeSecond(){return date('s',time());}
    /** 获取时间戳*/
    function GetTimestamp(){return time();}
    
    /** 获取当天开始时间*/
    function GetTimeDayState($fpDateTime=""){
        if(!IsNull($fpDateTime)){
            return date('Y-m-d',$fpDateTime)." 00:00:00";
        }
        return date('Y-m-d',time())." 00:00:00";
    }
    
    /** 获取当天结束时间*/
    function GetTimeDayEnd($fpDateTime=""){
        if(!IsNull($fpDateTime)){
            return date('Y-m-d',$fpDateTime)." 23:59:59";
        }
        return date('Y-m-d',time())." 23:59:59";
    }
    
    /** 获取月开始时间*/
    function GetTimeMonthStart($fpYearMonth,$fpYear=null,$fpMonth=null){
        try {
            if(IsNull($fpYearMonth)){
                $fpYearMonth = "{$fpYear}-{$fpMonth}-01";
            }
            return date('Y-m-d',strtotime($fpYearMonth))." 00:00:00";
        } catch (Exception $e) {
            return "";
        }
    }
    
    /** 获取月结束时间*/
    function GetTimeMonthEnd($fpYearMonth,$fpYear=null,$fpMonth=null){
        try {
            if(IsNull($fpYearMonth)){
                $fpYearMonth = "{$fpYear}-{$fpMonth}-01";
            }
            return date('Y-m-t',strtotime($fpYearMonth))." 23:59:59";
        } catch (Exception $e) {
            return "";
        }
    }
    
    /** 获取时间差*/
    function GetTimeDifference($timeOne,$timeTwo,$type) {
        if($type=="day"){
            return floor((strtotime($timeOne)-strtotime($timeTwo))/86400);  
        }else if($type=="hour"){
            return floor((strtotime($timeOne)-strtotime($timeTwo))%86400/3600);
        }else if($type=="minute"){
            return floor((strtotime($timeOne)-strtotime($timeTwo))%86400/60);
        }else if($type=="second"){
            return floor((strtotime($timeOne)-strtotime($timeTwo))%86400%60);
        }
        return 0;
    }

    /** 获取变化后的时间*/
    function GetTimeChange($stateTime,$secondNumher) {
        return date("Y-m-d H:i:s",strtotime($stateTime)+$secondNumher);
    }
        
    /** 获取当前日期为周几*/
    function GetDateWeekDay($date) {
        //获取当前日期为周几
        $workDay = (string)date("w",strtotime($date));
        //PHP取周日是0而不是7
        $vWeekNumber = "";
        if($workDay == "0"){
            $vWeekNumber = "7";
        }else{
            $vWeekNumber = $workDay;
        }
        return $vWeekNumber;
    }
    
    /** 获取当前周范围时间*/
    function GetDateWeekRange($fpDate) {
        if(IsNull($fpDate)){
            $fpDate = GetTimeDate();
        }else{
            $fpDate = HandleDateFormat($fpDate, FlyCode::$Format_Date_Ymd);
        }
        //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
        $w = date('w', strtotime($fpDate));
        //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
        $week_start = date('Y-m-d', strtotime("$fpDate -" . ($w ? $w - 1 : 6) . ' days'));
        //本周结束日期
        $week_end = date('Y-m-d', strtotime("$week_start +6 days"));
        return array("week_start" => $week_start, "week_end" => $week_end);
    }
    
