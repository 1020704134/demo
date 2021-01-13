<?php

/**------------------------------------*
  * 作者：shark
  * 创建时间：2020-02-14 11:40:42
  * Fly编码：1581651642037FLY080246
  * 类对象名：ObjFlyTokenLog()
  * ------------------------------------ */

//引入区

class FlyClassTokenLog{


    //---------- 类成员（member） ----------

    //类描述
    public static $classDescript = "Token生成日志";
    //类数据表名
    public static $tableName = "fly_token_log";

    //---------- 系统方法（system） ----------

    /**
     * 函数名称：Token生成日志:系统:记录添加
     * 函数调用：ObjFlyTokenLog() -> SystemFlyTokenLogAdd
     * 创建时间：2020-02-14 11:40:41
     * */
    public function SystemFlyTokenLogAdd($fpRequestIp,$fpIss,$fpSub,$fpAud,$fpIat,$fpNbf,$fpExp,$fpJti,$fpUtype,$fpUid,$fpUName){
        
        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "descript,shelfState,requestIp,iss,sub,aud,iat,nbf,exp,jti,utype,uid,uName",
            "descript" => self::$classDescript,
            "shelfstate" => "false",
            "requestip" => $fpRequestIp,
            "iss" => $fpIss,
            "sub" => $fpSub,
            "aud" => $fpAud,
            "iat" => $fpIat,
            "nbf" => $fpNbf,
            "exp" => $fpExp,
            "jti" => $fpJti,
            "utype" => $fpUtype,
            "uid" => $fpUid,
            "uName" => $fpUName,
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }


    //---------- 管理员方法（admin） ----------

    /**
     * 函数名称：Token生成日志:管理员:记录查询
     * 函数调用：ObjFlyTokenLog() -> AdminFlyTokenLogPaging($fpAdminId)
     * 创建时间：2020-02-14 11:40:41
     * */
    public function AdminFlyTokenLogPaging($fpAdminId){

        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}

        //--- 数据预定义 ---
        $json = "";

        //--- 参数获取区 ---
        $pPage = GetParameter("page","");     //参数:页码:page
        $pLimit = GetParameter("limit","");   //参数:条数:limit

        //参数：id
        $pId = GetParameter("id","");
        if(!IsNull($pId)&&!JudgeRegularIntRight($pId)){return JsonModelParameterException("id", $pId, 20, "值必须是正整数", __LINE__);}
        if(!IsNull($pId)){ $pPage = 1; $pLimit = 1; }

        //参数判断:页码:page
        if(IsNull($pPage)||!JudgeRegularIntRight($pPage)){return JsonModelParameterException("page", $pPage, 9, "值必须是正整数", __LINE__);}
        //参数判断:条数:limit
        if(IsNull($pLimit)||!JudgeRegularIntRight($pLimit)){return JsonModelParameterException("limit", $pLimit, 9, "值必须是正整数", __LINE__);}

        //参数：debug
        $pDebug = GetParameter("debug","");
        if($pDebug!="true"){$pDebug = "false";}

        //参数：记录状态
        $pWhereState = GetParameter("wherestate","");
        if(!IsNull($pWhereState)&&!($pWhereState=="STATE_NORMAL"||$pWhereState=="STATE_DELETE")){return JsonModelParameterException("wherestate", $pWhereState, 64, "值必须是STATE_NORMAL/STATE_DELETE", __LINE__);}

        //参数：上下架状态
        $pWhereShelfState = GetParameter("whereshelfstate","");
        if(!IsNull($pWhereShelfState)&&!($pWhereShelfState=="true"||$pWhereShelfState=="false")){return JsonModelParameterException("whereshelfstate", $pWhereShelfState, 36, "值必须是true/false", __LINE__);}

        //参数：like
        $pLikeField = GetParameter("likefield","");
        if(!IsNull($pLikeField)&&!DBMySQLJudge::JudgeTableField(self::$tableName,$pLikeField)){ return JsonInforFalse("搜索字段不存在", $pLikeField,__LINE__); }
        $pLikeKey = GetParameter("likekey","");
        if(!IsNull($pLikeKey)){ $pLikeKey = HandleStringAddslashes($pLikeKey); }

        //参数：state
        $pStateField = GetParameter("statefield","");
        if(!IsNull($pStateField)&&!DBMySQLJudge::JudgeTableField(self::$tableName,$pStateField)){ return JsonInforFalse("状态字段不存在", $pStateField,__LINE__); }
        $pStateKey = GetParameter("statekey","");
        if(!IsNull($pStateKey)&&!JudgeRegularState($pStateKey)){ return JsonInforFalse("状态码错误", $pStateKey,__LINE__); }

        //字段定义
        $vDataField = DBMySQLSelect::TableFieldString(self::$tableName);

        //--- Json组合区 ---
        $jsonKeyValueArray = array(
        	"table_name" => self::$tableName,
        	"data_field" => $vDataField,
        	"where_field" => "",
        	"where_value" => "",
        	"page" => $pPage,
        	"limit" => $pLimit,
        	"orderby" => "id:desc",
        	"like_field" => $pLikeField,
        	"like_key" => $pLikeKey,
        	"sql_debug" => $pDebug,
        );

        //条件字段判断组合
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "id", $pId);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "state", $pWhereState);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "shelfState", $pWhereShelfState);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, $pStateField, $pStateKey);
        //返回结果:分页
        return MIndexDataPaging(JsonHandleArray($jsonKeyValueArray));

    }



    

}
