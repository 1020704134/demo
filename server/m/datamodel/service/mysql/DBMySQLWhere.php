<?php

class DBMySQLWhere{
    
    
    /**Where条件字符串拼接:whereField = ? AND whereField=?*/
    public static function WhereQuestion($fieldArray){
        $fieldNum = sizeof($fieldArray);
        if($fieldNum<1){
            return null;
        }
        $whereString = "";
        $fieldStr = "";
        $valueStr = "";
        for($i=0;$i<$fieldNum;$i++){
            $fieldStr = $fieldArray[$i];
            $valueStr = "?";
            $whereString .= $i==$fieldNum-1 ? $fieldStr."=".$valueStr : $fieldStr."=".$valueStr." AND ";
        }
        return $whereString;
    }

    
    /**Where条件字符串拼接:whereField connector ? connectKey whereField connector ?*/
    public static function WhereEqual($fieldArray,$valueArray){
        $fieldNum = sizeof($fieldArray);
        if($fieldNum<1){
            return null;
        }
        $whereString = "";
        $fieldStr = "";
        $valueStr = "";
        for($i=0;$i<$fieldNum;$i++){
            $fieldStr = $fieldArray[i];
            $valueStr = $valueArray[i];
            $whereString .= i==$fieldNum-1 ? $fieldStr."=".$valueStr : $fieldStr."=".$valueStr." AND ";
        }
        return $whereString;
    }
    
    /**Where条件字符串拼接:whereField connector ? connectKey whereField connector ?*/
    public static function WhereLabel($fieldArray,$labelArray,$valueArray){
        $fieldNum = sizeof($fieldArray);
        if($fieldNum<1){
            return null;
        }
        $whereString = "";
        $fieldStr = "";
        $labelStr = "";
        $valueStr = "";
        for($i=0;$i<$fieldNum;$i++){
            $fieldStr = $fieldArray[i];
            $labelStr = $labelArray[i];
            $valueStr = $valueArray[i];
            $whereString .= i==$fieldNum-1 ? $fieldStr.$labelStr.$valueStr : $fieldStr.$labelStr.$valueStr." AND ";
        }
        return $whereString;
    }
    
    
    
    //---------Servlet-----相关------
    /**
     * 处理Servle传递过来的参数,并组合成Where字符串
     * 参数:1.开发判断数组，2.条件字符串，3.条件值，4.条件连接符号
     * @throws Exception
     * */
    public static function WhereHandleLabel($whereOpenFieldArray,$whereFieldString,$whereValueString,$whereLabelString){
        //处理传入的字符串
        if(IsNull($whereFieldString)){
            return null;
        }
        $whereFieldArray = explode(",",$whereFieldString);
        $whereValueArray = explode(",",$whereValueString);
        $whereLabelArray = null;
        if(!IsNull($whereLabelString)){
            $whereLabelArray = explode(",",$whereLabelString);
        }
        if(sizeof($whereFieldArray)!=sizeof($whereValueArray)){
            echo JsonInforFalse("WHERE:条件字段|条件值:数量不相等", "SQL条件语句组合");
            return null;
        }
        if(!IsNull($whereLabelString)&&sizeof($whereFieldArray)!=sizeof($whereLabelArray)){
            echo JsonInforFalse("WHERE:条件字段|条件符号:数量不相等","SQL条件语句组合");
            return null;
        }
        //判断条件数组是否全部是表字段
        if(!IsNull($whereOpenFieldArray)){
            $bo = false;
            $requestFieldName = "";
            for($i=0;$i<sizeof($whereFieldArray);$i++){
                $bo = false;
                $requestFieldName = $whereFieldArray[i];
                for($c=0;$c<sizeof($whereOpenFieldArray);$c++){
                    if(strtolower($requestFieldName)==strtolower($whereOpenFieldArray[c])){
                        $bo = true;
                        continue;
                    }
                }
                if(!$bo){
                    echo JsonInforFalse("WHERE:条件字段不存在或为限制字段","SQL条件语句组合");
                    return null;
                }
            }
        }
        //处理条件字符串
        $whereString = "";
        $whereLabelThisString = "";
        for($i=0;$i<sizeof($whereFieldArray);$i++){
            $whereLabelThisString = IsNull($whereLabelString)?"=":$whereLabelArray[i];
            $whereString .= $i==sizeof($whereFieldArray)-1 ? $whereFieldArray[$i]." ".$whereLabelThisString." "."?" : $whereFieldArray[$i]." ".$whereLabelThisString." "."?"." AND ";
        }
        return $whereString;
    }
    
    
    /**
     * 处理Servle传递过来的参数,并组合成Where字符串
     * 创建时间:November 02,2018 15:39
     * @throws Exception
     * */
    public static function WhereHandle($whereField,$whereValue,$whereSon){
        //--- Where语句处理区 ---
        $whereString = "";
        //条件字段和添加值组合
        if(!IsNull($whereField)&&!IsNull($whereValue)){
            $whereFieldArray = explode(",",$whereField);
            $whereValueArray = explode(",",$whereValue);
            if(sizeof($whereValueArray)!=sizeof($whereFieldArray)){
                echo JsonInforFalse("SQL语句条件字段与条件值数量不匹配","SQL条件语句组合");
                return null;
            }
            
            for($i=0;$i<sizeof($whereFieldArray);$i++){
                if(!IsNull($whereFieldArray[$i])){
                    if(IsNull($whereString)){
                        $whereString .= $whereFieldArray[$i]." = '".$whereValueArray[$i]."'";
                    }else{
                        $whereString .= " AND " . $whereFieldArray[$i]." = '".$whereValueArray[$i]."'";
                    }
                }
                
            }
        }
        
        //条件子字符串组合
        if(!IsNull($whereSon)){
            //去除首位空格
            $vHandleWhereSon = HandleStringDeleteSpace($whereSon);
            //匹配之条件字符串连接符：and、or
            if(preg_match('/^and\s|^or\s/i',$vHandleWhereSon)){
                if(IsNull($whereString)){
                    $whereString = " ".str_replace('/^and\s|^or\s/i',"",$vHandleWhereSon);
                }else{
                    $whereString = $whereString." ".$whereSon;
                }
            }else{
                if(IsNull($whereString)){
                    $whereString = " ".$whereSon;
                }else{
                    $whereString = $whereString." AND ".$whereSon;
                }
            }
        }
        
        //--- SQL语句组合区 ---
        return $whereString;
    }
    
    
    /**
     * 处理Servle传递过来的参数,并组合成Where字符串(条件调试组合字符串)
     * */
    public static function WherePdoHandleDebug($whereField,$whereValue,$whereSon){
        //--- Where语句处理区 ---
        $whereString = "";
        //条件字段和添加值组合
        if(!IsNull($whereField)&&!IsNull($whereValue)){
            $whereFieldArray = explode(",",$whereField);
            $whereValueArray = explode(",",$whereValue);
            if(sizeof($whereValueArray)!=sizeof($whereFieldArray)){
                echo JsonInforFalse("SQL语句条件字段与条件值数量不匹配","SQL条件语句组合");
                return null;
            }
    
            for($i=0;$i<sizeof($whereFieldArray);$i++){
                if(!IsNull($whereFieldArray[$i])){
                    if(IsNull($whereString)){
                        $whereString .= $whereFieldArray[$i]." = '".$whereValueArray[$i]."'";
                    }else{
                        $whereString .= " AND " . $whereFieldArray[$i]." = '".$whereValueArray[$i]."'";
                    }
                }
    
            }
        }
    
        //条件子字符串组合
        if(!IsNull($whereSon)){
            if(IsNull($whereString)){
                $whereString .= " ".$whereSon;
            }else{
                $whereString .= " AND ".$whereSon;
            }
        }
    
        //--- SQL语句组合区 ---
        if(!IsNull($whereString)){
            return " WHERE " . $whereString;
        }
    
        //--- SQL语句组合区 ---
        return $whereString;
    }
    
    
    /**
     * 处理Servle传递过来的参数,并组合成Where字符串
     * 创建时间:November 02,2018 15:39
     * @throws Exception
     * */
    public static function WherePdoHandle($whereField,$whereValueArray,$whereSon){
        //--- Where语句处理区 ---
        $whereString = "";
        //条件字段和添加值组合
        if(!IsNull($whereField)&&!IsNull($whereValueArray)){
            $whereFieldArray = explode(",",$whereField);
            if(sizeof($whereValueArray)!=sizeof($whereValueArray)){
                echo JsonInforFalse("SQL语句条件字段与条件值数量不匹配","SQL条件语句组合");
                return null;
            }
    
            for($i=0;$i<sizeof($whereFieldArray);$i++){
                if(!IsNull($whereFieldArray[$i])){
                    if(IsNull($whereString)){
                        $whereString .= $whereFieldArray[$i]." = ?";
                    }else{
                        $whereString .= " AND " . $whereFieldArray[$i]." = ?";
                    }
                }
    
            }
        }
    
        //条件子字符串组合
        if(!IsNull($whereSon)){
            if(IsNull($whereString)){
                $whereString .= " ".$whereSon;
            }else{
                $whereString .= " AND ".$whereSon;
            }
        }
    
        //--- SQL语句组合区 ---
        if(!IsNull($whereString)){
            return " WHERE " . $whereString;
        }
        
        return $whereString;
    }
    
    
    //----- SQL条件组合 -----
    
    /**
     * 函数:SQL语句:年时间范围
     * 说明:返回时间范围
     * 更新时间:October 13, 2018 14:56:00
     * */
    public static function SQLDateRangeYear($fpField,$fpYear){
        return " {$fpField}>='{$fpYear}-01-01 00:00:00' AND {$fpField}<='{$fpYear}-12-31 23:59:59' ";
    }
    
    /**
     * 函数:SQL语句:月时间范围
     * 说明:返回时间范围
     * 更新时间:October 13, 2018 14:56:00
     * */
    public static function SQLDateRangeMonth($fpField,$fpStartYearMonth,$fpEndYearMonth){
        $vMonthStartTime = GetTimeMonthStart($fpStartYearMonth);
        $vMonthEndTime = GetTimeMonthEnd($fpEndYearMonth);
        if(IsNull($vMonthStartTime)||IsNull($vMonthEndTime)){
            return "";
        }
        return " {$fpField}>='{$vMonthStartTime}' AND {$fpField}<='{$vMonthEndTime}' ";
    }
    
    /**
     * 函数:SQL语句:日时间范围
     * 说明:返回时间范围
     * 更新时间:October 13, 2020 15:38:00
     * */
    public static function SQLDateRangeDate($fpField,$fpStartDate,$fpEndDate){
        //开始时间
        $fpStartDate = HandleDateFormat($fpStartDate, FlyCode::$Format_Date_Ymd);
        //结束时间
        if(JudgeRegularDateMonth($fpEndDate)){
            $fpEndDate = date("Y-m-d",strtotime("{$fpEndDate} +1 month -1 day"));
        }else{
            $fpEndDate = HandleDateFormat($fpEndDate, FlyCode::$Format_Date_Ymd);
        }
        if(IsNull($fpStartDate)||IsNull($fpEndDate)){
            return "";
        }
        $fpStartDate = $fpStartDate . " 00:00:00";
        $fpEndDate = $fpEndDate . " 23:59:59";
        return " {$fpField}>='{$fpStartDate}' AND {$fpField}<='{$fpEndDate}' ";
    }
    
}

