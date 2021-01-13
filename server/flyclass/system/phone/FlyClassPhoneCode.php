<?php

/**------------------------------------*
  * 创建时间：2019-05-27 18:54:39
  * ------------------------------------ */

//--- 引用区 ---

class FlyClassPhoneCode{
    
    
    //---------- 类成员 ----------
    
    //类描述
    public static $classDescript = "手机短信验证码";
    
    //表名称
    public static $tableName = "fly_phone_code";
    

    //---------- 游客方法（visitor） ----------
    
    /**
     * 函数名称：手机短信验证码:游客:记录查询
     * 函数调用：ObjFlyPhoneCode() -> VisitorFlyPhoneCodePaging()
     * 创建时间：2020-11-04 12:57:10
     * */
    public function VisitorFlyPhoneCodePaging(){
        
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 接口调试输出 ---
        if(GetParameterRequest("interfacecode")=="true"){return WriteFunctionCode(__FILE__,__FUNCTION__);};	//输出接口主函数代码
        
        
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
        if(!IsNull($pLikeField)&&!DBMySQLJudge::JudgeTableField(self::$tableName,$pLikeField)){ return JsonInforFalse("搜索字段不存在", $pLikeField,__LINE__); }
        $pLikeKey = GetParameterNoCode("likekey","");
        if(!IsNull($pLikeKey)){ $pLikeKey = HandleStringAddslashes($pLikeKey); }
        
        //参数：state
        $pStateField = GetParameterNoCode("statefield","");
        if(!IsNull($pStateField)&&!DBMySQLJudge::JudgeTableField(self::$tableName,$pStateField)){ return JsonInforFalse("状态字段不存在", $pStateField,__LINE__); }
        $pStateKey = GetParameterNoCode("statekey","");
        if(!IsNull($pStateKey)&&!JudgeRegularState($pStateKey)){ return JsonInforFalse("状态码错误", $pStateKey,__LINE__); }
        
        //字段定义
        $vDataField = DBMySQLSelect::TableFieldString(self::$tableName);
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,recodeName,recodeGroup,body,modelId,phonenumber,phoneCode,sendEvent";
        
        //--- Json组合区 ---
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "data_field" => $vDataField,
            "where_field" => "shelfState",
            "where_value" => "true",
            "page" => $pPage,
            "limit" => $pLimit,
            //"orderby" => "id:desc",
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
    
    
    //---------- 系统方法（system） ----------
    
    /**
     * 数据添加：验证码记录添加
     * 创建时间：2019-05-27 18:54:39
     * */
    public function SystemPhoneCodeAdd($fpApiSource,$phonenumber,$phoneCode,$sendEvent,$body,$modelId,$fpJudgeNumber){
        
        //--- 变量声明 ---
        $tableName = self::$tableName;
        if(IsNull($fpJudgeNumber)||$fpJudgeNumber<=0){$fpJudgeNumber=6;}
        
        //--- 当天相同号码相同事件发送总条数日期 ---
        $today = GetTimeDate();
        $sql = "SELECT COUNT(TRUE) count FROM {$tableName} WHERE phonenumber = '{$phonenumber}'  AND sendEvent = '{$sendEvent}' AND ADDTIME >= '{$today} 00:00:00' AND ADDTIME <= '{$today} 23:55:55'";
        $count = DBHelper::DataString($sql, null, "count");
        if($count >= $fpJudgeNumber){
            return JsonInforFalse("您今日验证码发送数量已达上限", "{$sendEvent}:{$phonenumber}");
        }
        
        //--- 判断未使用的间隔小于60秒的同事件同手机号的短信验证码 ---
        //获取同类型验证码发送时间
        $jsonKeyValueArray = array(
            "table_name" => $tableName,
            "where_field" => "phonenumber,sendEvent,state",
            "where_value" => "{$phonenumber},{$sendEvent},STATE_NORMAL",
            "orderby" => "id:desc",
            "page" => "1",
            "limit" => "1",
        );
        $json = MIndexDataPaging(JsonHandleArray($jsonKeyValueArray));
        //结果判断
        if(!JudgeJsonCountZero($json)){
            $dataArray = GetJsonValue($json,"data");
            $addTime = $dataArray[0] -> addTime;
            if(strtotime(GetTimeNow()) - strtotime($addTime) <= 60){
                return JsonInforFalse("60秒内不得再次发送同类型验证码", "{$sendEvent}:{$phonenumber}");
            }
        }
        
        //--- 生成验证码并存储到数据库记录中 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => $tableName,
            "insert_field" => "apiSource,body,modelId,phonenumber,phoneCode,sendEvent",
            "apisource" => $fpApiSource,
            "body" => $body,
            "modelid" => $modelId,
            "phonenumber" => $phonenumber,
            "phonecode" => $phoneCode,
            "sendevent" => $sendEvent,
        );
        //返回结果
        return MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
    }
    
    /**
     * 验证码判断：内部方法
     * 创建时间：2019-05-27 21:17:00
     * */
    public function SystemPhoneCodeJudge($phonenumber,$phoneCode,$sendEvent){
        
        //--- 变量声明 ---
        $tableName = self::$tableName;
        
        //--- 判断未使用的间隔小于60秒的同事件同手机号的短信验证码 ---
        //获取同类型验证码发送时间
        $jsonKeyValueArray = array(
            "table_name" => $tableName,
            "where_field" => "phonenumber,phoneCode,sendEvent,state",
            "where_value" => "{$phonenumber},{$phoneCode},{$sendEvent},STATE_NORMAL",
            "orderby" => "id:desc",
            "page" => "1",
            "limit" => "1",
        );
        $json = MIndexDataPaging(JsonHandleArray($jsonKeyValueArray));
        //判断记录是否存在
        if(JudgeJsonCountZero($json)){
            return JsonInforFalse("验证码错误或已使用", $phonenumber);
        }
        
        //判断验证码是否超时
        $dataArray = GetJsonValue($json,"data");
        $addTime = $dataArray[0] -> addTime;
        $codeId = $dataArray[0] -> id;
        if(strtotime(GetTimeNow()) - strtotime($addTime) >= 1800){
            return JsonInforFalse("验证码已失效", "{$phonenumber}:{$phoneCode}");
        }
        
        //--- 修改验证码状态 ---
        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => $tableName,
            "update_field" => "state",
            "update_value" => "STATE_USED",
            "where_field" => "id",
            "where_value" => $codeId,
        );
        //组合Json
        $json = JsonHandleArray($jsonKeyValueArray);
        //返回结果
        return MIndexDataUpdate($json);
    }
    
    
    //---------- 用户方法（user） ----------
    
    /**
     * 函数名称：手机短信验证码:用户:记录查询
     * 函数调用：ObjFlyPhoneCode() -> UserFlyPhoneCodePaging($fpUserId)
     * 创建时间：2020-11-04 12:57:10
     * */
    public function UserFlyPhoneCodePaging($fpUserId){
        
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 接口调试输出 ---
        if(GetParameterRequest("interfacecode")=="true"){return WriteFunctionCode(__FILE__,__FUNCTION__);};	//输出接口主函数代码
        
        
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
        if(!IsNull($pLikeField)&&!DBMySQLJudge::JudgeTableField(self::$tableName,$pLikeField)){ return JsonInforFalse("搜索字段不存在", $pLikeField,__LINE__); }
        $pLikeKey = GetParameterNoCode("likekey","");
        if(!IsNull($pLikeKey)){ $pLikeKey = HandleStringAddslashes($pLikeKey); }
        
        //参数：state
        $pStateField = GetParameterNoCode("statefield","");
        if(!IsNull($pStateField)&&!DBMySQLJudge::JudgeTableField(self::$tableName,$pStateField)){ return JsonInforFalse("状态字段不存在", $pStateField,__LINE__); }
        $pStateKey = GetParameterNoCode("statekey","");
        if(!IsNull($pStateKey)&&!JudgeRegularState($pStateKey)){ return JsonInforFalse("状态码错误", $pStateKey,__LINE__); }
        
        //字段定义
        $vDataField = DBMySQLSelect::TableFieldString(self::$tableName);
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,recodeName,recodeGroup,body,modelId,phonenumber,phoneCode,sendEvent";
        
        //--- Json组合区 ---
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "data_field" => $vDataField,
            "where_field" => "userId",
            "where_value" => "{$fpUserId}",
            "page" => $pPage,
            "limit" => $pLimit,
            //"orderby" => "id:desc",
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
    
    
    //---------- 管理员方法（admin） ----------
    
    /**
     * 函数名称：手机短信验证码:管理员:记录添加
     * 函数调用：ObjFlyPhoneCode() -> AdminFlyPhoneCodeAdd($fpAdminId)
     * 创建时间：2020-11-04 12:57:10
     * */
    public function AdminFlyPhoneCodeAdd($fpAdminId){
        
        
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 接口调试输出 ---
        if(GetParameterRequest("interfacecode")=="true"){return WriteFunctionCode(__FILE__,__FUNCTION__);};	//输出接口主函数代码
        
        
        //--- 变量预定义 ---
        $json="";
        
        //--- 参数获取区 ---
        //参数:记录ID（用于修改记录）:id
        $pId = GetParameterNoCode("id","");
        if(!IsNull($pId)&&!JudgeRegularIntRight($pId)){return JsonModelParameterException("id", $pId, 20, "值必须是正整数", __LINE__);}
        
        //参数:记录名称:recodeName
        $pRecodeName = GetParameterNoCode("recodename",$json);
        if(!JudgeRegularFont($pRecodeName)){return JsonModelParameterException("recodename", $pRecodeName, 36, "内容格式错误", __LINE__);}
        
        //参数:记录组:recodeGroup
        $pRecodeGroup = GetParameterNoCode("recodegroup",$json);
        if(!JudgeRegularFont($pRecodeGroup)){return JsonModelParameterException("recodegroup", $pRecodeGroup, 36, "内容格式错误", __LINE__);}
        
        //参数:验证码内容:body
        $pBody = GetParameterNoCode("body",$json);
        if(!JudgeRegularFont($pBody)){return JsonModelParameterException("body", $pBody, 256, "内容格式错误", __LINE__);}
        
        //参数:验证码模板:modelId
        $pModelId = GetParameterNoCode("modelid",$json);
        if(!JudgeRegularFont($pModelId)){return JsonModelParameterException("modelid", $pModelId, 36, "内容格式错误", __LINE__);}
        
        //参数:电话号码:phonenumber
        $pPhonenumber = GetParameterNoCode("phonenumber",$json);
        if(!JudgeRegularPhone($pPhonenumber)){return JsonModelParameterException("phonenumber", $pPhonenumber, 36, "内容格式错误", __LINE__);}
        
        //参数:短信验证码:phoneCode
        $pPhoneCode = GetParameterNoCode("phonecode",$json);
        if(!JudgeRegularFont($pPhoneCode)){return JsonModelParameterException("phonecode", $pPhoneCode, 36, "内容格式错误", __LINE__);}
        
        //参数:发送事件:sendEvent
        $pSendEvent = GetParameterNoCode("sendevent",$json);
        if(!JudgeRegularFont($pSendEvent)){return JsonModelParameterException("sendevent", $pSendEvent, 36, "内容格式错误", __LINE__);}
        
        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "recodeName,recodeGroup,body,modelId,phonenumber,phoneCode,sendEvent",
            "recodename" => $pRecodeName,
            "recodegroup" => $pRecodeGroup,
            "body" => $pBody,
            "modelid" => $pModelId,
            "phonenumber" => $pPhonenumber,
            "phonecode" => $pPhoneCode,
            "sendevent" => $pSendEvent,
            //"key_field" => "modelId",
            //- 修改记录 -
            "where_field" => "",
            "where_value" => "",
            "update_field" => "",
            "update_value" => "",
            "execution_step" => "update,insert",
        );
        
        //修改字段判断组合
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "id", $pId);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "recodeName", $pRecodeName);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "recodeGroup", $pRecodeGroup);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "body", $pBody);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "modelId", $pModelId);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "phonenumber", $pPhonenumber);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "phoneCode", $pPhoneCode);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "sendEvent", $pSendEvent);
        
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        if(JudgeJsonTrue($vJsonResult)){
            ObjFlyUserAdminOperationLog() -> SystemFlyUserAdminOperationLogAdd($fpAdminId, __CLASS__, __FUNCTION__, "管理员添加记录", "0");
        }
        return $vJsonResult;
    }
    
    
    /**
     * 函数名称：手机短信验证码:管理员:记录查询
     * 函数调用：ObjFlyPhoneCode() -> AdminFlyPhoneCodePaging($fpAdminId)
     * 创建时间：2020-11-04 12:57:10
     * */
    public function AdminFlyPhoneCodePaging($fpAdminId){
        
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 接口调试输出 ---
        if(GetParameterRequest("interfacecode")=="true"){return WriteFunctionCode(__FILE__,__FUNCTION__);};	//输出接口主函数代码
        
        
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
        if(!IsNull($pLikeField)&&!DBMySQLJudge::JudgeTableField(self::$tableName,$pLikeField)){ return JsonInforFalse("搜索字段不存在", $pLikeField,__LINE__); }
        $pLikeKey = GetParameterNoCode("likekey","");
        if(!IsNull($pLikeKey)){ $pLikeKey = HandleStringAddslashes($pLikeKey); }
        
        //参数：state
        $pStateField = GetParameterNoCode("statefield","");
        if(!IsNull($pStateField)&&!DBMySQLJudge::JudgeTableField(self::$tableName,$pStateField)){ return JsonInforFalse("状态字段不存在", $pStateField,__LINE__); }
        $pStateKey = GetParameterNoCode("statekey","");
        if(!IsNull($pStateKey)&&!JudgeRegularState($pStateKey)){ return JsonInforFalse("状态码错误", $pStateKey,__LINE__); }
        
        //字段定义
        $vDataField = DBMySQLSelect::TableFieldString(self::$tableName);
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,recodeName,recodeGroup,body,modelId,phonenumber,phoneCode,sendEvent";
        
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
        );
        
        //条件字段判断组合
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "id", $pId);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "state", $pWhereState);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "shelfState", $pWhereShelfState);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, $pStateField, $pStateKey);
        //返回结果:分页
        return MIndexDataPaging(JsonHandleArray($jsonKeyValueArray));
        
    }
    
    
    /**
     * 函数名称：手机短信验证码:管理员:记录修改
     * 函数调用：ObjFlyPhoneCode() -> AdminFlyPhoneCodeSet($fpAdminId)
     * 创建时间：2020-11-04 12:57:10
     * */
    public function AdminFlyPhoneCodeSet($fpAdminId){
        
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 接口调试输出 ---
        if(GetParameterRequest("interfacecode")=="true"){return WriteFunctionCode(__FILE__,__FUNCTION__);};	//输出接口主函数代码
        
        
        //--- 数据预定义 ---
        $json = "";
        
        //--- 参数获取区 ---
        //参数:表ID:id
        $pId = GetParameterNoCode("id",$json);
        if(!JudgeRegularIntRight($pId)){return JsonModelParameterException("id", $pId, 20, "值必须是正整数", __LINE__);}
        
        //参数:记录描述:descript
        $pDescript = GetParameterNoCode("descript",$json);
        if(!IsNull($pDescript)&&!JudgeRegularFont($pDescript)){return JsonModelParameterException("descript", $pDescript, 36, "内容格式错误", __LINE__);}
        
        //参数:序号:indexNumber
        $pIndexNumber = GetParameterNoCode("indexnumber",$json);
        if(!IsNull($pIndexNumber)&&!JudgeRegularInt($pIndexNumber)){return JsonModelParameterException("indexnumber", $pIndexNumber, 11, "值必须是整数", __LINE__);}
        
        //参数:上下架状态:shelfState
        $pShelfState = GetParameterNoCode("shelfstate",$json);
        if(!IsNull($pShelfState)&&!JudgeRegularState($pShelfState)){return JsonModelParameterException("shelfstate", $pShelfState, 36, "状态值格式错误", __LINE__);}
        
        //参数:记录状态:state
        $pState = GetParameterNoCode("state",$json);
        if(!IsNull($pState)&&!JudgeRegularFont($pState)){return JsonModelParameterException("state", $pState, 36, "内容格式错误", __LINE__);}
        
        //参数:记录名称:recodeName
        $pRecodeName = GetParameterNoCode("recodename",$json);
        if(!IsNull($pRecodeName)&&!JudgeRegularFont($pRecodeName)){return JsonModelParameterException("recodename", $pRecodeName, 36, "内容格式错误", __LINE__);}
        
        //参数:记录组:recodeGroup
        $pRecodeGroup = GetParameterNoCode("recodegroup",$json);
        if(!IsNull($pRecodeGroup)&&!JudgeRegularFont($pRecodeGroup)){return JsonModelParameterException("recodegroup", $pRecodeGroup, 36, "内容格式错误", __LINE__);}
        
        //参数:验证码内容:body
        $pBody = GetParameterNoCode("body",$json);
        if(!IsNull($pBody)&&!JudgeRegularFont($pBody)){return JsonModelParameterException("body", $pBody, 256, "内容格式错误", __LINE__);}
        
        //参数:验证码模板:modelId
        $pModelId = GetParameterNoCode("modelid",$json);
        if(!IsNull($pModelId)&&!JudgeRegularFont($pModelId)){return JsonModelParameterException("modelid", $pModelId, 36, "内容格式错误", __LINE__);}
        
        //参数:电话号码:phonenumber
        $pPhonenumber = GetParameterNoCode("phonenumber",$json);
        if(!IsNull($pPhonenumber)&&!JudgeRegularPhone($pPhonenumber)){return JsonModelParameterException("phonenumber", $pPhonenumber, 36, "内容格式错误", __LINE__);}
        
        //参数:短信验证码:phoneCode
        $pPhoneCode = GetParameterNoCode("phonecode",$json);
        if(!IsNull($pPhoneCode)&&!JudgeRegularFont($pPhoneCode)){return JsonModelParameterException("phonecode", $pPhoneCode, 36, "内容格式错误", __LINE__);}
        
        //参数:发送事件:sendEvent
        $pSendEvent = GetParameterNoCode("sendevent",$json);
        if(!IsNull($pSendEvent)&&!JudgeRegularFont($pSendEvent)){return JsonModelParameterException("sendevent", $pSendEvent, 36, "内容格式错误", __LINE__);}
        
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
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "descript", $pDescript);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "indexNumber", $pIndexNumber);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "shelfState", $pShelfState);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "state", $pState);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "recodeName", $pRecodeName);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "recodeGroup", $pRecodeGroup);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "body", $pBody);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "modelId", $pModelId);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "phonenumber", $pPhonenumber);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "phoneCode", $pPhoneCode);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "sendEvent", $pSendEvent);
        
        //判断字段值是否为空
        $vUpdateFieldJudgeInfor = JudgeFlyJsonAddUpdateField($jsonKeyValueArray,"descript,indexnumber,shelfstate,state,recodename,recodegroup,body,modelid,phonenumber,phonecode,sendevent");
        if(JudgeJsonFalse($vUpdateFieldJudgeInfor)){ return $vUpdateFieldJudgeInfor; }
        
        //执行:修改
        $vJsonResult = MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));
        if(JudgeJsonTrue($vJsonResult)){
            ObjFlyUserAdminOperationLog() -> SystemFlyUserAdminOperationLogAdd($fpAdminId, __CLASS__, __FUNCTION__, "管理员修改记录", $pId);
        }
        return $vJsonResult;
        
    }
    
    
    /**
     * 函数名称：手机短信验证码:管理员:记录状态修改
     * 函数调用：ObjFlyPhoneCode() -> AdminFlyPhoneCodeSetState($fpAdminId)
     * 创建时间：2020-11-04 12:57:10
     * */
    public function AdminFlyPhoneCodeSetState($fpAdminId){
        
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 接口调试输出 ---
        if(GetParameterRequest("interfacecode")=="true"){return WriteFunctionCode(__FILE__,__FUNCTION__);};	//输出接口主函数代码
        
        
        //--- 变量预定义 ---
        $json="";		//Json参数
        
        //--- 参数获取区 ---
        //参数：id
        $pId = GetParameterNoCode("id",$json);
        if(!JudgeRegularFont($pId)){return JsonModelParameterInfor("id","参数值不符合规则",20);}
        
        //参数:记录状态:state
        $pState = GetParameterNoCode("state",$json);
        if(IsNull($pState)||GetStringLength($pState)>36||!($pState=="STATE_NORMAL"||$pState=="STATE_DELETE")){return JsonModelParameterInfor("state","值必须是STATE_NORMAL/STATE_DELETE",36);}
        
        //--- 数据提交区 ---
        //执行:修改
        $vJsonResult = ServiceTableDataSystemSet(self::$tableName,"state","{$pState}","id","{$pId}");
        if(JudgeJsonTrue($vJsonResult)){
            ObjFlyUserAdminOperationLog() -> SystemFlyUserAdminOperationLogAdd($fpAdminId, __CLASS__, __FUNCTION__, "管理员修改记录状态", $pId);
        }
        return $vJsonResult;
    }
    
    /**
     * 函数名称：手机短信验证码:管理员:数据上下架状态修改
     * 函数调用：ObjFlyPhoneCode() -> AdminFlyPhoneCodeShelfState($fpAdminId)
     * 创建时间：2020-11-04 12:57:10
     * */
    public function AdminFlyPhoneCodeShelfState($fpAdminId){
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 接口调试输出 ---
        if(GetParameterRequest("interfacecode")=="true"){return WriteFunctionCode(__FILE__,__FUNCTION__);};	//输出接口主函数代码
        
        
        //--- 参数获取区 ---
        //参数：id
        $pId = GetParameterNoCode("id","");
        if(IsNull($pId)){return JsonModelParameterNull("id");}
        //参数：id：正整数正则判断
        if(!JudgeRegularIntRight($pId)){return JsonInforFalse("id必须是正整数", "id");}
        
        //参数：shelfstate
        $pShelfState = GetParameterNoCode("shelfstate","");
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
        //执行:上下降状态修改
        $vJsonResult = MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));
        if(JudgeJsonTrue($vJsonResult)){
            ObjFlyUserAdminOperationLog() -> SystemFlyUserAdminOperationLogAdd($fpAdminId, __CLASS__, __FUNCTION__, "管理员记录上下架修改", $pId);
        }
        return $vJsonResult;
        
    }
    
    /**
     * 函数名称：手机短信验证码:管理员:记录永久删除
     * 函数调用：ObjFlyPhoneCode() -> AdminFlyPhoneCodeDelete($fpAdminId)
     * 创建时间：2020-11-04 12:57:10
     * */
    public function AdminFlyPhoneCodeDelete($fpAdminId){
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
        //--- 接口调试输出 ---
        if(GetParameterRequest("interfacecode")=="true"){return WriteFunctionCode(__FILE__,__FUNCTION__);};	//输出接口主函数代码
        
        
        //--- 变量预定义 ---
        $json="";		//Json参数
        
        //--- 参数获取区 ---
        //参数：id
        $pId = GetParameterNoCode("id",$json);
        if(!JudgeRegularIntRight($pId)){return JsonModelParameterInfor("id","参数值不符合规则",20);}
        
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "where_field" => "id",
            "where_value" => "{$pId}",
        );
        //执行:删除
        $vJsonResult = MIndexDataDelete(JsonHandleArray($jsonKeyValueArray));
        if(JudgeJsonTrue($vJsonResult)){
            ObjFlyUserAdminOperationLog() -> SystemFlyUserAdminOperationLogAdd($fpAdminId, __CLASS__, __FUNCTION__, "管理员删除记录", $pId);
        }
        return $vJsonResult;
    }
    
    /**
     * 修改验证码状态
     * 创建时间：2019-05-27 21:17:00
     * */
    public function AdminFlyPhoneCodeStateNormal(){
        
        //参数：id
        $pId = GetParameter("id","");
        if(IsNull($pId)){return JsonModelParameterNull("id");}
        
        $vTime = GetTimeNow();
        
        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "update_field" => "updateTime,addTime,state",
            "update_value" => "{$vTime},{$vTime},STATE_NORMAL",
            "where_field" => "id",
            "where_value" => $pId,
        );
        //返回结果
        return MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));
    }
    
    
    //---------- 测试方法（test） ----------
    
    //---------- 基础方法（base） ----------
    
    
    /**
     * 函数名称：获取数据表名称
     * 函数调用：ObjFlyPhoneCode() -> GetTableName()
     * 创建时间：2020-11-04 12:57:10
     * */
    public function GetTableName(){
        return self::$tableName;
    }
    
    /**
     * 函数名称：获取类描述
     * 函数调用：ObjFlyPhoneCode() -> GetClassDescript()
     * 创建时间：2020-11-04 12:57:10
     * */
    public function GetClassDescript(){
        return self::$classDescript;
    }
    
    /**
     * 函数名称：获取数据表字段
     * 函数调用：ObjFlyPhoneCode() -> GetTableField()
     * 创建时间：2020-11-04 12:57:10
     * */
    public function GetTableField(){
        return MIndexDBTableFields(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }
    
    /**
     * 函数名称：类数据表创建
     * 函数调用：ObjFlyPhoneCode() -> OprationCreateTable()
     * 创建时间：2020-11-04 12:57:10
     * */
    public function OprationCreateTable(){
        $sql = "CREATE TABLE `fly_phone_code` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `recodeName` varchar(36) DEFAULT NULL COMMENT '记录名称',  `recodeGroup` varchar(36) DEFAULT NULL COMMENT '记录组',  `body` varchar(256) DEFAULT NULL COMMENT '验证码内容',  `modelId` varchar(36) DEFAULT NULL COMMENT '验证码模板',  `phonenumber` varchar(36) DEFAULT NULL COMMENT '电话号码',  `phoneCode` varchar(36) DEFAULT NULL COMMENT '短信验证码',  `sendEvent` varchar(36) DEFAULT NULL COMMENT '发送事件',  PRIMARY KEY (`id`) USING BTREE) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='手机短信验证码'";
        DBHelper::DataSubmit($sql, "");
        $vTableCheck = DBMySQLServiceJson::GetDBTableCheck('{"table_name":"'.self::$tableName.'"}');
        if(JudgeJsonTrue($vTableCheck)){
            return JsonInforTrue("创建成功", self::$tableName);
        }
        return JsonInforFalse("创建失败", self::$tableName);
    }
    
    /**
     * 函数名称：类数据表基础字段检测
     * 函数调用：ObjFlyPhoneCode() -> OprationTableFieldBaseCheck()
     * 创建时间：2020-11-04 12:57:10
     * */
    public function OprationTableFieldBaseCheck(){
        return DBMySQLServiceJson::OprationFieldBaseCheck(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }
    
    
   
}


