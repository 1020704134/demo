<?php

/**------------------------------------*
  * 作者：shark
  * 创建时间：2019-11-28 14:56:20
  * Fly编码：1574924180535FLY315476
  * 类对象名：ObjFlyInterface()
  * ------------------------------------ */

//引入区

class FlyClassInterface{


    //---------- 类成员（member） ----------

    //类描述
    public static $classDescript = "请求接口";
    //类数据表名
    public static $tableName = "fly_interface";


    //---------- 私有方法（private） ----------

    //---------- 游客方法（visitor） ----------
    

    //---------- 系统方法（system） ----------

    /**
     * 请求接口
     * 记录添加
     * 创建时间：2019-11-28 14:56:20
     * */
    public function SystemFlyInterfaceAdd($fpInterfacePath,$fpInterfaceIndexLine,$fpInterfaceIndexLineDescript,$fpInterfaceIndexMethod,$fpInterfaceIndexMethodDescript,$fpInterfaceAccessMethod,$fpInterfacePower){

        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "interfacePath,interfaceIndexLine,interfaceIndexLineDescript,interfaceIndexMethod,interfaceIndexMethodDescript,interfaceAccessMethod,interfacePower",
            "key_field" => "interfacePath,interfaceIndexLine,interfaceIndexMethod",
            "interfacepath" => $fpInterfacePath,
            "interfaceindexline" => $fpInterfaceIndexLine,
            "interfaceindexlinedescript" => $fpInterfaceIndexLineDescript,
            "interfaceindexmethod" => $fpInterfaceIndexMethod,
            "interfaceindexmethoddescript" => $fpInterfaceIndexMethodDescript,
            "interfaceaccessmethod" => $fpInterfaceAccessMethod,
            "interfacepower" => $fpInterfacePower,
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }


    //---------- 用户方法（user） ----------

    //---------- 管理员方法（admin） ----------
    
    /**
     * 获取接口ID
     * 创建时间：2020-01-08 09:58:34
     * */
    public function AdminFlyInterfaceId(){
        
        //--- 参数获取区 ---
        $json = "";
        
        //参数:接口路径:interfacePath
        $pInterfacePath = GetParameter("interfacepath",$json);
        if(!JudgeRegularUrl($pInterfacePath)){return JsonModelParameterException("interfacepath", $pInterfacePath, 128, "内容格式错误", __LINE__);}
        
        //参数:接口索引业务线:interfaceIndexLine
        $pInterfaceIndexLine = GetParameter("interfaceindexline",$json);
        if(!JudgeRegularFont($pInterfaceIndexLine)){return JsonModelParameterException("interfaceindexline", $pInterfaceIndexLine, 128, "内容格式错误", __LINE__);}
        
        //参数:接口索引业务线方法:interfaceIndexMethod
        $pInterfaceIndexMethod = GetParameter("interfaceindexmethod",$json);
        if(!JudgeRegularFont($pInterfaceIndexMethod)){return JsonModelParameterException("interfaceindexmethod", $pInterfaceIndexMethod, 128, "内容格式错误", __LINE__);}
        
        //获取:fly_interface:表ID
        $vSql = "SELECT id FROM fly_interface WHERE interfacePath=? AND interfaceIndexLine=? AND interfaceIndexMethod=? LIMIT 0,1;";
        $vInterfaceId = DBHelper::DataString($vSql, [$pInterfacePath,$pInterfaceIndexLine,$pInterfaceIndexMethod]);
        if(IsNull($vInterfaceId)){
            return JsonInforFalse("接口ID不存在", "接口记录");
        }        
        return JsonInforTrue($vInterfaceId, "接口记录"); 
    }    
    
    /**
     * 接口路径
     * 创建时间：2020-01-07 14:42:41
     * */
    public function AdminFlyInterfacePath(){
        //获取:fly_interface:interfacePath
        $vSql = "SELECT DISTINCT interfacePath FROM fly_interface";
        $vSelectInterfacePathList = DBHelper::DataList($vSql, "", ["interfacePath"]);
        if(IsNull($vSelectInterfacePathList)){ return JsonInforFalse("记录不存在", "fly_interface"); }
        //返回Json
        return JsonModelDataString($vSelectInterfacePathList, sizeof(GetJsonObject($vSelectInterfacePathList)), self::$classDescript);
    }
    
    /**
     * 接口路径业务线
     * 创建时间：2020-01-07 14:42:41
     * */
    public function AdminFlyInterfacePathLine(){
        
        $json = "";
        
        //参数:接口路径:interfacePath
        $pInterfacePath = GetParameter("interfacepath",$json);
        if(!JudgeRegularUrl($pInterfacePath)){return JsonModelParameterException("interfacepath", $pInterfacePath, 128, "内容格式错误", __LINE__);}
        
        //获取:fly_interface:interfaceIndexLine,interfaceIndexLineDescript
        $vSql = "SELECT DISTINCT interfaceIndexLine,interfaceIndexLineDescript FROM fly_interface WHERE interfacePath=?";
        $vFlyInterfaceList = DBHelper::DataList($vSql, [$pInterfacePath], ["interfaceIndexLine","interfaceIndexLineDescript"]);
        if(IsNull($vFlyInterfaceList)){ return JsonInforFalse("记录不存在", "fly_interface"); }
        //返回Json
        return JsonModelDataString($vFlyInterfaceList, sizeof(GetJsonObject($vFlyInterfaceList)), self::$classDescript);
    }
    
    /**
     * 接口路径业务线
     * 创建时间：2020-01-07 14:42:41
     * */
    public function AdminFlyInterfaceLineMethodInfor(){
    
        $json = "";
    
        //参数:接口路径:interfacePath
        $pInterfacePath = GetParameter("interfacepath",$json);
        if(!JudgeRegularUrl($pInterfacePath)){return JsonModelParameterException("interfacepath", $pInterfacePath, 128, "内容格式错误", __LINE__);}
        
        //参数:接口索引业务线:interfaceIndexLine
        $pInterfaceIndexLine = GetParameter("interfaceindexline");
        if(!JudgeRegularLetterNumber($pInterfaceIndexLine)){return JsonModelParameterException("interfaceindexline", $pInterfaceIndexLine, 128, "内容格式错误", __LINE__);}
        
        //参数:接口索引业务线方法:interfaceIndexMethod
        $pInterfaceIndexMethod = GetParameter("interfaceindexmethod");
        if(!JudgeRegularLetterNumber($pInterfaceIndexMethod)){return JsonModelParameterException("interfaceindexmethod", $pInterfaceIndexMethod, 128, "内容格式错误", __LINE__);}
        
        $vDataField = "id,onlyKey,indexNumber,updateTime,addTime,state,interfacePath,interfaceIndexLine,interfaceIndexLineDescript,interfaceIndexMethod,interfaceIndexMethodDescript,interfaceAccessMethod,interfacePower";
        //获取:fly_interface:interfaceIndexLine,interfaceIndexLineDescript
        $vSql = "SELECT {$vDataField} FROM fly_interface WHERE interfacePath=? AND interfaceIndexLine=? AND interfaceIndexMethod=?";
        $vFlyInterfaceList = DBHelper::DataList($vSql, [$pInterfacePath,$pInterfaceIndexLine,$pInterfaceIndexMethod], ["id","onlyKey","indexNumber","updateTime","addTime","state","interfacePath","interfaceIndexLine","interfaceIndexLineDescript","interfaceIndexMethod","interfaceIndexMethodDescript","interfaceAccessMethod","interfacePower"]);
        if(IsNull($vFlyInterfaceList)){ return JsonInforFalse("记录不存在", "fly_interface"); }
        //返回Json
        return JsonModelDataString($vFlyInterfaceList, sizeof(GetJsonObject($vFlyInterfaceList)), self::$classDescript);
    }
    
    /**
     * 接口路径业务线
     * 创建时间：2020-01-07 14:42:41
     * */
    public function AdminFlyInterfaceLineMethodDelete($vAdminId){
        
        $json = "";
        
        if(!ObjRoleObjectAdmin() -> JudgeAdminIsCreater($vAdminId)){
            return JsonInforFalse("权限不足", "无法完成接口删除操作");
        }
        
        //参数:接口路径:interfacePath
        $pInterfacePath = GetParameter("interfacepath",$json);
        if(!JudgeRegularUrl($pInterfacePath)){return JsonModelParameterException("interfacepath", $pInterfacePath, 128, "内容格式错误", __LINE__);}
        
        //参数:接口索引业务线:interfaceIndexLine
        $pInterfaceIndexLine = GetParameter("interfaceindexline");
        if(!JudgeRegularLetterNumber($pInterfaceIndexLine)){return JsonModelParameterException("interfaceindexline", $pInterfaceIndexLine, 128, "内容格式错误", __LINE__);}
        
        //参数:接口索引业务线方法:interfaceIndexMethod
        $pInterfaceIndexMethod = GetParameter("interfaceindexmethod");
        if(!JudgeRegularLetterNumber($pInterfaceIndexMethod)){return JsonModelParameterException("interfaceindexmethod", $pInterfaceIndexMethod, 128, "内容格式错误", __LINE__);}
        
        //接口ID
        $vInterfaceId = DBHelper::DataString("SELECT id FROM fly_interface WHERE interfacePath='{$pInterfacePath}' AND interfaceIndexLine='{$pInterfaceIndexLine}' AND interfaceIndexMethod='{$pInterfaceIndexMethod}'", null);
        
        //--- 删除 fly_interface 接口文档数据 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "where_field" => "interfacePath,interfaceIndexLine,interfaceIndexMethod",
            "where_value" => "{$pInterfacePath},{$pInterfaceIndexLine},{$pInterfaceIndexMethod}",
        );
        //执行:删除
        $vInterfaceJsonResult = MIndexDataDelete(JsonHandleArray($jsonKeyValueArray));
        
        //--- 删除 fly_interface_demo 接口DEMO数据 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => "fly_interface_demo",
            "where_field" => "interfacePath,interfaceIndexLine,interfaceIndexMethod",
            "where_value" => "{$pInterfacePath},{$pInterfaceIndexLine},{$pInterfaceIndexMethod}",
        );
        //执行:删除
        $vJsonResult = MIndexDataDelete(JsonHandleArray($jsonKeyValueArray));
        
        //--- 删除 fly_interface_demo 接口DEMO数据 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => "fly_interface_param",
            "where_field" => "id",
            "where_value" => "{$vInterfaceId}",
        );
        //执行:删除
        $vJsonResult = MIndexDataDelete(JsonHandleArray($jsonKeyValueArray));
        
        //返回执行结果
        return $vInterfaceJsonResult;
    }
    
    /**
     * 接口路径方法
     * 创建时间：2020-01-07 14:42:41
     * */
    public function AdminFlyInterfacePathMethod(){
    
        //参数:接口路径:interfacePath
        $pInterfacePath = GetParameter("interfacepath");
        if(!JudgeRegularUrl($pInterfacePath)){return JsonModelParameterException("interfacepath", $pInterfacePath, 128, "内容格式错误", __LINE__);}
        
        //参数:接口索引业务线:interfaceIndexLine
        $pInterfaceIndexLine = GetParameter("interfaceindexline");
        if(!JudgeRegularLetterNumber($pInterfaceIndexLine)){return JsonModelParameterException("interfaceindexline", $pInterfaceIndexLine, 128, "内容格式错误", __LINE__);}
    
        //获取:fly_interface:interfaceIndexMethod,interfaceIndexMethodDescript
        $vSql = "SELECT id,interfaceIndexMethod,interfaceIndexMethodDescript,addTime FROM fly_interface WHERE interfacePath=? AND interfaceIndexLine=? ORDER BY addTime DESC;";
        $vFlyInterfaceList = DBHelper::DataList($vSql, [$pInterfacePath,$pInterfaceIndexLine], ["id","interfaceIndexMethod","interfaceIndexMethodDescript","addTime"]);
        if(IsNull($vFlyInterfaceList)){ return JsonInforFalse("记录不存在", "fly_interface"); }
        //返回Json
        return JsonModelDataString($vFlyInterfaceList, sizeof(GetJsonObject($vFlyInterfaceList)), self::$classDescript);
        
    }

    /**
     * 接口业务线
     * 创建时间：2019-04-26 20:14:01
     * */
    public function AdminFlyInterfaceLine(){
        $vTableName = self::$tableName;
        $sql = "SELECT interfacePath,interfaceIndexLine,interfaceIndexLineDescript FROM {$vTableName} WHERE interfacePath!='none' GROUP BY interfacePath,interfaceIndexLine ORDER BY interfacePath;";
        $list = DBHelper::DataList($sql, null, array("interfacePath","interfaceIndexLine","interfaceIndexLineDescript"));
        if(IsNull($list)){
            return JsonModelDataNull("记录条数为0", self::$tableName);
        }
        return JsonModelSelectDataHave("有记录", self::$tableName, sizeof($list), $list);
    }
    
    /**
     * 接口业务线方法
     * 创建时间：2019-11-30 17:12:21
     * */
    public function AdminFlyInterfaceLineMethod(){
        
        $json = "";
        
        //参数:接口路径:interfacePath
        $pInterfacePath = GetParameter("interfacepath",$json);
        if(!JudgeRegularUrl($pInterfacePath)){return JsonModelParameterException("interfacepath", $pInterfacePath, 128, "内容格式错误", __LINE__);}
        
        //参数:接口索引业务线:interfaceIndexLine
        $pInterfaceIndexLine = GetParameter("interfaceindexline",$json);
        if(!JudgeRegularLetterNumber($pInterfaceIndexLine)){return JsonModelParameterException("interfaceindexline", $pInterfaceIndexLine, 128, "内容格式错误", __LINE__);}
        
        $vTableName = self::$tableName;
        $sql = "SELECT id,interfacePath,interfaceIndexLine,interfaceIndexLineDescript,interfaceIndexMethod,interfaceIndexMethodDescript,interfacePower FROM {$vTableName} WHERE interfacePath='{$pInterfacePath}' AND interfaceIndexLine='{$pInterfaceIndexLine}';";
        $list = DBHelper::DataList($sql, null, array("id","interfacePath","interfaceIndexLine","interfaceIndexLineDescript","interfaceIndexMethod","interfaceIndexMethodDescript","interfacePower"));
        if(IsNull($list)){
            return JsonModelDataNull("记录条数为0", self::$tableName);
        }
        return JsonModelSelectDataHave("有记录", self::$tableName, sizeof($list), $list);
    }
    
    /**
     * 请求接口
     * 记录添加
     * 创建时间：2019-11-28 14:56:20
     * */
    public function AdminFlyInterfaceAdd(){


        //--- 参数判断区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}

        //--- 变量预定义 ---
        $json="";

        //--- 参数获取区 ---

	    //参数:接口路径:interfacePath
	    $pInterfacePath = GetParameter("interfacepath",$json);
	    if(!JudgeRegularUrl($pInterfacePath)){return JsonModelParameterException("interfacepath", $pInterfacePath, 128, "内容格式错误", __LINE__);}

	    //参数:接口索引业务线:interfaceIndexLine
	    $pInterfaceIndexLine = GetParameter("interfaceindexline",$json);
	    if(!JudgeRegularFont($pInterfaceIndexLine)){return JsonModelParameterException("interfaceindexline", $pInterfaceIndexLine, 128, "内容格式错误", __LINE__);}

	    //参数:接口索引业务线描述:interfaceIndexLineDescript
	    $pInterfaceIndexLineDescript = GetParameter("interfaceindexlinedescript",$json);
	    if(!JudgeRegularFont($pInterfaceIndexLineDescript)){return JsonModelParameterException("interfaceindexlinedescript", $pInterfaceIndexLineDescript, 64, "内容格式错误", __LINE__);}

	    //参数:接口索引业务线方法:interfaceIndexMethod
	    $pInterfaceIndexMethod = GetParameter("interfaceindexmethod",$json);
	    if(!JudgeRegularFont($pInterfaceIndexMethod)){return JsonModelParameterException("interfaceindexmethod", $pInterfaceIndexMethod, 128, "内容格式错误", __LINE__);}

	    //参数:接口索引业务线方法描述:interfaceIndexMethodDescript
	    $pInterfaceIndexMethodDescript = GetParameter("interfaceindexmethoddescript",$json);
	    if(!JudgeRegularFont($pInterfaceIndexMethodDescript)){return JsonModelParameterException("interfaceindexmethoddescript", $pInterfaceIndexMethodDescript, 64, "内容格式错误", __LINE__);}

	    //参数:接口方法:interfaceAccessMethod
	    $pInterfaceAccessMethod = GetParameter("interfaceaccessmethod",$json);
	    if(!JudgeRegularFont($pInterfaceAccessMethod)){return JsonModelParameterException("interfaceaccessmethod", $pInterfaceAccessMethod, 36, "内容格式错误", __LINE__);}

	    //参数:接口权限:interfacePower
	    $pInterfacePower = GetParameter("interfacepower",$json);
	    if(!JudgeRegularFont($pInterfacePower)){return JsonModelParameterException("interfacepower", $pInterfacePower, 36, "内容格式错误", __LINE__);}

        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "interfacePath,interfaceIndexLine,interfaceIndexLineDescript,interfaceIndexMethod,interfaceIndexMethodDescript,interfaceAccessMethod,interfacePower",
            "key_field" => "interfacePath,interfaceIndexLine,interfaceIndexMethod",
            "interfacepath" => $pInterfacePath,
            "interfaceindexline" => $pInterfaceIndexLine,
            "interfaceindexlinedescript" => $pInterfaceIndexLineDescript,
            "interfaceindexmethod" => $pInterfaceIndexMethod,
            "interfaceindexmethoddescript" => $pInterfaceIndexMethodDescript,
            "interfaceaccessmethod" => $pInterfaceAccessMethod,
            "interfacepower" => $pInterfacePower,
            //修改记录字段
            "update_field" => "interfaceIndexLineDescript,interfaceIndexMethodDescript",
            "update_value" => "{$pInterfaceIndexLineDescript},{$pInterfaceIndexMethodDescript}",
            "where_field" => "interfacePath,interfaceIndexLine,interfaceIndexMethod",
            "where_value" => "{$pInterfacePath},{$pInterfaceIndexLine},{$pInterfaceIndexMethod}",
            "execution_step" => "update,insert",
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        if(JudgeJsonFalse($vJsonResult)){ return $vJsonResult; }
        //结果获取
        $vResultBo = GetJsonValue($vJsonResult, "result");
        $vResultInfor = $vResultBo=="true"?"执行成功":GetJsonValue($vJsonResult, "infor");
        //结果Json组合
        $keyValue  = "";
        $keyValue .= JsonKeyValue("result", $vResultBo).",";
        $keyValue .= JsonKeyValue("code", "").",";
        $keyValue .= JsonKeyValue("infor", $vResultInfor).",";
        $keyValue .= JsonKeyValue("tips", GetJsonValue($vJsonResult, "tips")).",";
        $keyValue .= JsonKeyValue("path", $pInterfacePath).",";
        $keyValue .= JsonKeyValue("line", $pInterfaceIndexLine).",";
        $keyValue .= JsonKeyValue("lineDescript", $pInterfaceIndexLineDescript).",";
        $keyValue .= JsonKeyValue("method", $pInterfaceIndexMethod).",";
        $keyValue .= JsonKeyValue("methodDescript", $pInterfaceIndexMethodDescript).",";
        $keyValue .= JsonKeyValue("interfaceOnlyKey", GetJsonValue($vJsonResult, "value")).",";
        $insertResult = JsonObj(HandleStringDeleteLast($keyValue));
        return $insertResult;
    }

    
    /**
     * 请求参数更新
     * 记录添加或修改
     * 创建时间：2019-11-28 14:56:20
     * */
    public function AdminFlyInterfaceParameterReplace(){
        
        //--- 参数获取区 ---
        $json = "";
        
        //参数:接口路径:interfacePath
        $pInterfacePath = GetParameterNoCode("interfacepath",$json);
        if(!JudgeRegularUrl($pInterfacePath)){return JsonModelParameterException("interfacepath", $pInterfacePath, 128, "内容格式错误", __LINE__);}
        
        //参数:接口索引业务线:interfaceIndexLine
        $pInterfaceIndexLine = GetParameterNoCode("interfaceindexline",$json);
        if(!JudgeRegularLetterNumber($pInterfaceIndexLine)){return JsonModelParameterException("interfaceindexline", $pInterfaceIndexLine, 128, "内容格式错误", __LINE__);}
        
        //参数:接口索引业务线方法:interfaceIndexMethod
        $pInterfaceIndexMethod = GetParameterNoCode("interfaceindexmethod",$json);
        if(!JudgeRegularLetterNumber($pInterfaceIndexMethod)){return JsonModelParameterException("interfaceindexmethod", $pInterfaceIndexMethod, 128, "内容格式错误", __LINE__);}
        
        //获取:fly_interface:表ID
        $vSql = "SELECT id FROM fly_interface WHERE interfacePath=? AND interfaceIndexLine=? AND interfaceIndexMethod=? LIMIT 0,1;";
        $vId = DBHelper::DataString($vSql, [$pInterfacePath,$pInterfaceIndexLine,$pInterfaceIndexMethod]);
        if(IsNull($vId)){ return JsonInforFalse("记录不存在", "fly_interface", __LINE__); }
        
        //参数:接口路径:interfaceOnlyKey
        //$pInterfaceOnlyKey = GetParameter("interfaceonlykey",$json);
        //if(!JudgeRegularLetterNumber($pInterfaceOnlyKey)){return JsonModelParameterException("interfaceonlykey", $pInterfaceOnlyKey, 64, "内容格式错误", __LINE__);}
        //获取:fly_interface:表ID
        //$vSql = "SELECT id FROM fly_interface WHERE onlyKey=? LIMIT 0,1;";
        //$vId = DBHelper::DataString($vSql, [$pInterfaceOnlyKey]);
        //if(IsNull($vId)){ return JsonInforFalse("接口文档记录不存在", "fly_interface", __LINE__); }
        
        //参数:接口ID:interfaceId
        $pInterfaceId = $vId;
        
        //参数:参数名称:parameterName
        $pParameterName = GetParameter("parametername",$json);
        if(!JudgeRegularFont($pParameterName)){return JsonModelParameterException("parametername", $pParameterName, 64, "内容格式错误", __LINE__);}
        
        //参数:参数值:parameterValue
        $pParameterValue = GetParameter("parametervalue",$json);
        if(!JudgeRegularFont($pParameterValue)){return JsonModelParameterException("parametervalue", $pParameterValue, 64, "内容格式错误", __LINE__);}
        
        //参数:参数值类型:parameterType
        $pParameterType = GetParameter("parametertype",$json);
        if(!JudgeRegularFont($pParameterType)){return JsonModelParameterException("parametertype", $pParameterType, 36, "内容格式错误", __LINE__);}
        
        //参数:参数是否必填:parameterMust
        $pParameterMust = GetParameter("parametermust",$json);
        if(!JudgeRegularFont($pParameterMust)){return JsonModelParameterException("parametermust", $pParameterMust, 36, "内容格式错误", __LINE__);}
        
        //参数:参数说明:parameterDescript
        $pParameterDescript = GetParameter("parameterdescript",$json);
        if(!JudgeRegularFont($pParameterDescript)){return JsonModelParameterException("parameterdescript", $pParameterDescript, 64, "内容格式错误", __LINE__);}
        
        //参数:参数备注:parameterRemarks
        $pParameterRemarks = GetParameterNoCode("parameterremarks",$json);
        if(!JudgeRegularFont($pParameterRemarks)){return JsonModelParameterException("parameterremarks", $pParameterRemarks, 128, "内容格式错误", __LINE__);}
        
        //参数:参数例程:parameterDemo
        $pParameterDemo = GetParameter("parameterdemo",$json);
        if(!JudgeRegularFont($pParameterDemo)){return JsonModelParameterException("parameterdemo", $pParameterDemo, 64, "内容格式错误", __LINE__);}
        
        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => "fly_interface_param",
            "insert_field" => "descript,shelfState,interfaceId,parameterName,parameterValue,parameterType,parameterMust,parameterDescript,parameterDemo",
            "key_field" => "interfaceId,parameterName",
            "descript" => self::$classDescript,
            "shelfstate" => "true",
            "interfaceid" => $pInterfaceId,
            "parametername" => $pParameterName,
            "parametervalue" => $pParameterValue,
            "parametertype" => $pParameterType,
            "parametermust" => $pParameterMust,
            "parameterdescript" => $pParameterDescript,
            "parameterremarks" => $pParameterRemarks,
            "parameterdemo" => $pParameterDemo,
            "where_field" => "interfaceId,parameterName",
            "where_value" => "{$pInterfaceId},{$pParameterName}",
            "update_field" => "parameterValue,parameterType,parameterMust,parameterDescript,parameterRemarks,parameterDemo",
            "update_value" => "{$pParameterValue},{$pParameterType},{$pParameterMust},{$pParameterDescript},{$pParameterRemarks},{$pParameterDemo}",
        );
        //执行:添加
        $jsonKeyValueArray = HandleFlyJsonUpdateField($jsonKeyValueArray); 
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
        
    }

    /**
     * 请求接口
     * 记录查询
     * 创建时间：2019-11-28 14:56:20
     * */
    public function AdminFlyInterfacePaging($fpAdminId){

        //--- 参数判断区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}

        //--- 数据预定义 ---
        $json = "";

        //--- 参数获取区 ---
        
        //参数:接口路径:interfacePath
        $pInterfacePath = GetParameter("interfacepath",$json);
        if(!IsNull($pInterfacePath)&&!JudgeRegularUrl($pInterfacePath)){return JsonModelParameterException("interfacepath", $pInterfacePath, 128, "内容格式错误", __LINE__);}
        //参数:接口索引业务线:interfaceIndexLine
        $pInterfaceIndexLine = GetParameter("interfaceindexline");
        if(!IsNull($pInterfaceIndexLine)&&!JudgeRegularLetterNumber($pInterfaceIndexLine)){return JsonModelParameterException("interfaceindexline", $pInterfaceIndexLine, 128, "内容格式错误", __LINE__);}
        //参数:接口索引业务线方法:interfaceIndexMethod
        $pInterfaceIndexMethod = GetParameter("interfaceindexmethod");
        if(!IsNull($pInterfaceIndexMethod)&&!JudgeRegularLetterNumber($pInterfaceIndexMethod)){return JsonModelParameterException("interfaceindexmethod", $pInterfaceIndexMethod, 128, "内容格式错误", __LINE__);}
        
        //参数：page
        $pPage = GetParameter("page","");
        if(IsNull($pPage)||!JudgeRegularIntRight($pPage)){return JsonModelParameterException("page", $pPage, 9, "值必须是正整数", __LINE__);}

        //参数：limit
        $pLimit = GetParameter("limit","");
        if(IsNull($pLimit)||!JudgeRegularIntRight($pLimit)){return JsonModelParameterException("limit", $pLimit, 9, "值必须是正整数", __LINE__);}

        //参数：id
        $pId = GetParameter("id","");
        if(!IsNull($pId)&&!JudgeRegularIntRight($pId)){return JsonModelParameterException("id", $pId, 20, "值必须是正整数", __LINE__);}

        //参数：记录状态
        $pWhereState = GetParameter("wherestate","");
        if(!IsNull($pWhereState)&&!($pWhereState=="STATE_NORMAL"||$pWhereState=="STATE_DELETE")){return JsonModelParameterException("wherestate", $pWhereState, 64, "值必须是STATE_NORMAL/STATE_DELETE", __LINE__);}

        //参数：上下架状态
        $pWhereShelfState = GetParameter("whereshelfstate","");
        if(!IsNull($pWhereShelfState)&&!($pWhereShelfState=="true"||$pWhereShelfState=="false")){return JsonModelParameterException("whereshelfstate", $pWhereShelfState, 36, "值必须是true/false", __LINE__);}

        //参数：like
        $pLike = GetParameter("like","");
        $pLiketype = GetParameter("liketype","");
        if(!IsNull($pLiketype)&&!DBMySQLJudge::JudgeTableField(self::$tableName,$pLiketype)){ return JsonInforFalse("搜索字段不存在", $pLiketype); }
        if(!IsNull($pLike)){ $pLike = HandleStringAddslashes($pLike); }

        //字段定义
        $vDataField = DBMySQLSelect::TableFieldString(self::$tableName);
        //$vDataField = "id,onlyKey,indexNumber,updateTime,addTime,state,interfacePath,interfaceIndexLine,interfaceIndexLineDescript,interfaceIndexMethod,interfaceIndexMethodDescript,interfaceAccessMethod,interfacePower";

        //--- Json组合区 ---
        $jsonKeyValueArray = array(
        	"table_name" => self::$tableName,
        	"data_field" => $vDataField,
        	"where_field" => "",
        	"where_value" => "",
        	"page" => $pPage,
        	"limit" => $pLimit,
        	"orderby" => "id:desc",
        	"like_field" => $pLiketype,
        	"like_key" => $pLike,
        );

        //条件字段判断组合
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "id", $pId);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "state", $pWhereState);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "shelfState", $pWhereShelfState);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "interfacePath", $pInterfacePath);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "interfaceIndexLine", $pInterfaceIndexLine);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "interfaceIndexMethod", $pInterfaceIndexMethod);
        //返回结果:分页
        return MIndexDataPaging(JsonHandleArray($jsonKeyValueArray));

    }


    /**
     * 请求接口
     * 记录修改
     * 创建时间：2019-11-28 14:56:20
     * */
    public function AdminFlyInterfaceSet(){

        //--- 参数判断区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}

        //--- 数据预定义 ---
        $json = "";

        //--- 参数获取区 ---
	    //参数:表ID:id
	    $pId = GetParameter("id",$json);
	    if(!JudgeRegularIntRight($pId)){return JsonModelParameterException("id", $pId, 20, "值必须是正整数", __LINE__);}

	    //参数:接口权限:interfacePower
	    $pInterfacePower = GetParameter("interfacepower",$json);
	    if(!JudgeRegularLetterNumber($pInterfacePower)){return JsonModelParameterException("interfacepower", $pInterfacePower, 36, "内容格式错误", __LINE__);}

	    //--- Json组合区 ---

	    //Json数组
	    $jsonKeyValueArray = array(
	        "table_name" => self::$tableName,
	        "update_field" => "",
	        "update_value" => "",
	        "where_field" => "id",
	        "where_value" => "{$pId}",
	    );

	    //修改字段判断组合
	    $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "interfacePower", $pInterfacePower);

	    //判断字段值是否为空
	    $vUpdateFieldJudgeInfor = JudgeFlyJsonAddUpdateField($jsonKeyValueArray,"interfacepower");
	    if(JudgeJsonFalse($vUpdateFieldJudgeInfor)){ return $vUpdateFieldJudgeInfor; }

	    //返回结果
	    return MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));

    }


    /**
     * 请求接口
     * 记录状态修改
     * 创建时间：2019-11-28 14:56:20
     * */
    public function AdminFlyInterfaceSetState($fpAdminId){

        //--- 参数判断区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}

        //--- 变量预定义 ---
        $json="";		//Json参数

        //--- 参数获取区 ---
        //参数：id
        $pId = GetParameter("id",$json);
        if(!JudgeRegularFont($pId)){return JsonModelParameterInfor("id","参数值不符合规则",20);}

        //参数:记录状态:state
        $pState = GetParameter("state",$json);
        if(IsNull($pState)||GetStringLength($pState)>36||!($pState=="STATE_NORMAL"||$pState=="STATE_DELETE")){return JsonModelParameterInfor("state","值必须是STATE_NORMAL/STATE_DELETE",36);}

        //--- 数据提交区 ---
        return ServiceTableDataSystemSet(self::$tableName,"state","{$pState}","id","{$pId}");
    }

    /**
     * 请求接口
     * 数据上下架状态修改
     * 创建时间：2019-11-28 14:56:20
     * */
    public function AdminFlyInterfaceShelfState($fpAdminId){
        //--- 参数判断区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}

        //--- 参数获取区 ---
        //参数：id
        $pId = GetParameter("id","");
        if(IsNull($pId)){return JsonModelParameterNull("id");}
        //参数：id：正整数正则判断
        if(!JudgeRegularIntRight($pId)){return JsonInforFalse("id必须是正整数", "id");}

        //参数：shelfstate
        $pShelfState = GetParameter("shelfstate","");
        if(IsNull($pShelfState)){return JsonModelParameterNull("shelfstate");}
        //参数：shelfstate：正整数正则判断
        if(!($pShelfState=="true"||$pShelfState=="false")){ return JsonInforFalse("上架状态值必须是true或false", "shelfstate"); }

        //--- Json提交区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "update_field" => "shelfState",
            "update_value" => "{$pShelfState}",
            "where_field" => "id",
            "where_value" => "{$pId}",
        );
        //执行:修改
        $vJsonResult = MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;

    }

    /**
     * 请求接口
     * 记录永久删除
     * 创建时间：2019-11-28 14:56:20
     * */
    public function AdminFlyInterfaceDelete($fpAdminId){
        //--- 参数判断区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}

        //--- 变量预定义 ---
        $json="";		//Json参数

        //--- 参数获取区 ---
        //参数：id
        $pId = GetParameter("id",$json);
        if(!JudgeRegularIntRight($pId)){return JsonModelParameterInfor("id","参数值不符合规则",20);}

        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "where_field" => "id",
            "where_value" => "{$pId}",
        );
        //执行:删除
        $vJsonResult = MIndexDataDelete(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }


    //---------- 基础方法（base） ----------


    /**
     * 获取数据表名称
     * 创建时间：2019-11-28 14:56:20
     * */
    public function GetTableName(){
        return self::$tableName;
    }

    /**
     * 获取类描述
     * 创建时间：2019-11-28 14:56:20
     * */
    public function GetClassDescript(){
        return self::$classDescript;
    }

    /**
     * 获取记录
     * 参数说明：$json:JSON数据，$whereField:条件字段，$sub:记录下标
     * 创建时间：2019-11-28 14:56:20
     * */
    public function GetRecodeJson($json,$whereField){
        return ServiceTableDataRecodeJson(self::$tableName, $json, $whereField);
    }
    
    /**
     * 函数名称：获取数据表字段
     * 函数调用：ObjFlyInterface() -> GetTableField()
     * 创建时间：2020-01-08 18:58:56
     * */
    public function GetTableField(){
        return MIndexDBTableFields(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }    

    /**
     * 判断ID是否存在
     * 参数说明：记录ID
     * 创建时间：2019-11-28 14:56:20
     * */
    public function JudgeId($id){
        //记录ID判断
        if(!JudgeJsonTrue(ServiceTableDataRecode(self::$tableName,'{"id":"'.$id.'"}', "id", 0))){
            return JsonInforFalse("id不存在", self::$classDescript);
        }
        //记录上下架状态判断
        if(!JudgeJsonTrue(ServiceTableDataRecode(self::$tableName,'{"id":"'.$id.'","shelfState":"true"}', "id,shelfState", 0))){
            return JsonInforFalse("记录已下架", self::$classDescript);
        }
        return JsonInforTrue("记录正常", self::$classDescript);
    }

    /**
     * 操作：类数据表创建
     * 创建时间：2019-11-28 14:56:20
     * */
    public function OprationCreateTable(){
        $sql = "CREATE TABLE `fly_interface` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `state` varchar(36) DEFAULT 'NORMAL' COMMENT '记录状态',  `interfacePath` varchar(128) DEFAULT NULL COMMENT '接口路径',  `interfaceIndexLine` varchar(128) DEFAULT NULL COMMENT '接口索引业务线',  `interfaceIndexLineDescript` varchar(64) DEFAULT NULL COMMENT '接口索引业务线描述',  `interfaceIndexMethod` varchar(128) DEFAULT NULL COMMENT '接口索引业务线方法',  `interfaceIndexMethodDescript` varchar(64) DEFAULT NULL COMMENT '接口索引业务线方法描述',  `interfaceAccessMethod` varchar(36) DEFAULT 'POST' COMMENT '接口方法',  `interfacePower` varchar(36) DEFAULT NULL COMMENT '接口权限',  PRIMARY KEY (`id`) USING BTREE) ENGINE=InnoDB AUTO_INCREMENT=152 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT";
        DBHelper::DataSubmit($sql, "");
        $vTableCheck = DBMySQLServiceJson::GetDBTableCheck('{"tableName":"'.self::$tableName.'"}');
        if(JudgeJsonTrue($vTableCheck)){
            return JsonInforTrue("创建成功", self::$tableName);
        }
        return JsonInforFalse("创建失败", self::$tableName);
    }

    /**
     * 操作：类数据表基础字段检测
     * 创建时间：2019-11-28 14:56:20
     * */
    public function OprationTableFieldBaseCheck(){
        return DBMySQLServiceJson::OprationFieldBaseCheck('{"tablename":"'.self::$tableName.'"}');
    }

    /**
     * 操作：类数据表字段检测
     * 创建时间：2019-11-28 14:56:20
     * */
    public function OprationTableFieldCheck(){
        //表字段信息
        $vF1 = array("field"=>"id" , "type"=>"bigint(20)" , "default"=>"" , "null"=>"NOT NULL" , "comment"=>"表ID" , "key"=>"PRI" , "extra"=>"auto_increment");
        $vF2 = array("field"=>"onlyKey" , "type"=>"varchar(36)" , "default"=>"" , "null"=>"NULL" , "comment"=>"唯一Key" , "key"=>"" , "extra"=>"");
        $vF3 = array("field"=>"indexNumber" , "type"=>"int(11)" , "default"=>"-1" , "null"=>"NULL" , "comment"=>"序号" , "key"=>"" , "extra"=>"");
        $vF4 = array("field"=>"updateTime" , "type"=>"timestamp" , "default"=>"" , "null"=>"NULL" , "comment"=>"修改时间" , "key"=>"" , "extra"=>"");
        $vF5 = array("field"=>"addTime" , "type"=>"timestamp" , "default"=>"CURRENT_TIMESTAMP" , "null"=>"NOT NULL" , "comment"=>"添加时间" , "key"=>"" , "extra"=>"");
        $vF6 = array("field"=>"state" , "type"=>"varchar(36)" , "default"=>"NORMAL" , "null"=>"NULL" , "comment"=>"记录状态" , "key"=>"" , "extra"=>"");
        $vF7 = array("field"=>"interfacePath" , "type"=>"varchar(128)" , "default"=>"" , "null"=>"NULL" , "comment"=>"接口路径" , "key"=>"" , "extra"=>"");
        $vF8 = array("field"=>"interfaceIndexLine" , "type"=>"varchar(128)" , "default"=>"" , "null"=>"NULL" , "comment"=>"接口索引业务线" , "key"=>"" , "extra"=>"");
        $vF9 = array("field"=>"interfaceIndexLineDescript" , "type"=>"varchar(64)" , "default"=>"" , "null"=>"NULL" , "comment"=>"接口索引业务线描述" , "key"=>"" , "extra"=>"");
        $vF10 = array("field"=>"interfaceIndexMethod" , "type"=>"varchar(128)" , "default"=>"" , "null"=>"NULL" , "comment"=>"接口索引业务线方法" , "key"=>"" , "extra"=>"");
        $vF11 = array("field"=>"interfaceIndexMethodDescript" , "type"=>"varchar(64)" , "default"=>"" , "null"=>"NULL" , "comment"=>"接口索引业务线方法描述" , "key"=>"" , "extra"=>"");
        $vF12 = array("field"=>"interfaceAccessMethod" , "type"=>"varchar(36)" , "default"=>"POST" , "null"=>"NULL" , "comment"=>"接口方法" , "key"=>"" , "extra"=>"");
        $vF13 = array("field"=>"interfacePower" , "type"=>"varchar(36)" , "default"=>"" , "null"=>"NULL" , "comment"=>"接口权限" , "key"=>"" , "extra"=>"");
        //表字段数组
        $vFieldArray = array($vF1,$vF2,$vF3,$vF4,$vF5,$vF6,$vF7,$vF8,$vF9,$vF10,$vF11,$vF12,$vF13);
        //表字段判断
        return DBMySQLServiceJson::OprationFieldCheck(self::$tableName, $vFieldArray);
    }




}
