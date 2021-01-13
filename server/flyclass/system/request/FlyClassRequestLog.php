<?php

/**------------------------------------*
  * 作者：shark
  * 创建时间：2020-03-10 20:32:01
  * Fly编码：1583843521694FLY930323
  * 类对象名：ObjFlyRequestLog()
  * ------------------------------------ */

//引入区

class FlyClassRequestLog{

    
    //---------- 系统方法（system） ----------

    /**
     * 函数名称：请求日志:系统:记录添加
     * 函数调用：ObjFlyRequestLog() -> SystemFlyRequestLogAdd
     * 创建时间：2020-03-10 20:32:01
     * */
    public function AddLog($fpJson){
        //请求数据
        $vDescript = "请求日志";
        $fpUserType = GetParameterJson(FlyJson::$vFlySystemUserType,$fpJson);
        $fpUserTable = GetParameterJson(FlyJson::$vFlySystemUserTable,$fpJson);
        $fpUserId = GetParameterJson(FlyJson::$vFlySystemUserId,$fpJson);
        $fpRequestIp = GetIp();
        $fpRequestEvent = GetParameterJson(FlyJson::$vFlySystemEvent,$fpJson);
        $fpRequestEventTable = GetParameterJson(FlyJson::$vFlySystemEventTable,$fpJson);
        $fpRequestKeyField = GetParameterJson(FlyJson::$vFlyJsonKeyField,$fpJson);
        $vOnlyKey = GetId("R");
        $vAddTime = GetTimeNow();
        //添加记录
        $vSql = "INSERT IGNORE INTO fly_request_log(descript,userType,userTable,userId,requestIp,requestEvent,eventTable,eventKeyField,onlyKey,ADDTIME) VALUES ('{$vDescript}','{$fpUserType}','{$fpUserTable}','{$fpUserId}','{$fpRequestIp}','{$fpRequestEvent}','{$fpRequestEventTable}','{$fpRequestKeyField}','{$vOnlyKey}','{$vAddTime}');";
        return DBHelper::DataSubmit($vSql, null);
    }


    //---------- 管理员方法（admin） ----------

    /**
     * 函数名称：请求日志:管理员:记录查询
     * 函数调用：ObjFlyRequestLog() -> AdminFlyRequestLogPaging($fpAdminId)
     * 创建时间：2020-03-10 20:32:01
     * */
    public function AdminFlyRequestLogPaging($fpAdminId){

        $tableName = "fly_request_log";
        
        //--- 数据预定义 ---
        $json = "";

        //--- 参数获取区 ---
        $pPage = GetParameterNoCode("page","");     //参数:页码:page
        $pLimit = GetParameterNoCode("limit","");   //参数:条数:limit

        //参数：id
        $pId = GetParameterNoCode("id","");
        if(!IsNull($pId)&&!JudgeRegularIntRight($pId)){return JsonModelParameterException("id", $pId, 20, "值必须是正整数", __LINE__);}
        if(!IsNull($pId)){ $pPage = 1; $pLimit = 1; }

        //参数判断:页码:page
        if(IsNull($pPage)||!JudgeRegularIntRight($pPage)){return JsonModelParameterException("page", $pPage, 9, "值必须是正整数", __LINE__);}
        //参数判断:条数:limit
        if(IsNull($pLimit)||!JudgeRegularIntRight($pLimit)){return JsonModelParameterException("limit", $pLimit, 9, "值必须是正整数", __LINE__);}

        //参数：记录状态
        $pWhereState = GetParameterNoCode("wherestate","");
        if(!IsNull($pWhereState)&&!($pWhereState=="STATE_NORMAL"||$pWhereState=="STATE_DELETE")){return JsonModelParameterException("wherestate", $pWhereState, 64, "值必须是STATE_NORMAL/STATE_DELETE", __LINE__);}

        //参数：上下架状态
        $pWhereShelfState = GetParameterNoCode("whereshelfstate","");
        if(!IsNull($pWhereShelfState)&&!($pWhereShelfState=="true"||$pWhereShelfState=="false")){return JsonModelParameterException("whereshelfstate", $pWhereShelfState, 36, "值必须是true/false", __LINE__);}

        //参数：like
        $pLikeField = GetParameterNoCode("likefield","");
        if(!IsNull($pLikeField)&&!DBMySQLJudge::JudgeTableField($tableName,$pLikeField)){ return JsonInforFalse("搜索字段不存在", $pLikeField,__LINE__); }
        $pLikeKey = GetParameterNoCode("likekey","");
        if(!IsNull($pLikeKey)){ $pLikeKey = HandleStringAddslashes($pLikeKey); }

        //参数：state
        $pStateField = GetParameterNoCode("statefield","");
        if(!IsNull($pStateField)&&!DBMySQLJudge::JudgeTableField($tableName,$pStateField)){ return JsonInforFalse("状态字段不存在", $pStateField,__LINE__); }
        $pStateKey = GetParameterNoCode("statekey","");
        if(!IsNull($pStateKey)&&!JudgeRegularState($pStateKey)){ return JsonInforFalse("状态码错误", $pStateKey,__LINE__); }

        //字段定义
        $vDataField = DBMySQLSelect::TableFieldString($tableName);
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,userType,userId,requestIp,requestEvent";

        //--- Json组合区 ---
        $jsonKeyValueArray = array(
        	"table_name" => $tableName,
        	"data_field" => $vDataField,
        	"where_field" => "",
        	"where_value" => "",
        	"page" => $pPage,
        	"limit" => $pLimit,
        	//"descbo" => "true",
        	//"orderby" => "id",
        	"like_field" => $pLikeField,
        	"like_key" => $pLikeKey,
        );

        //条件字段判断组合
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "id", $pId);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "state", $pWhereState);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "shelfState", $pWhereShelfState);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, $pStateField, $pStateKey);
        //返回结果:分页
        return MIndexDataPaging(JsonHandleArray($jsonKeyValueArray));

    }


    //---------- 基础方法（base） ----------

    /**
     * 函数名称：获取数据表字段
     * 函数调用：ObjFlyRequestLog() -> GetTableField()
     * 创建时间：2020-03-10 20:32:01
     * */
    public function GetTableField(){
        return MIndexDBTableFields(JsonObj(JsonKeyValue("table_name", "fly_request_log")));
    }




}
