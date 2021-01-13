<?php

/**------------------------------------*
 * 作者：shark
 * 创建时间：2021-01-13 12:04:28
 * Fly编码：1610510668730FLY929736
 * 类对象名：ObjTestUser()
 * ------------------------------------ */

//引入区

class FlyClassTestUser{
    
    
    //---------- 类成员（member） ----------
    
    //类描述
    public static $classDescript = "用户";
    //类数据表名
    public static $tableName = "test_user";
    
    
    //---------- 私有方法（private） ----------
    
    //---------- 游客方法（visitor） ----------
    
    /**
     * 函数名称：用户:游客:记录查询
     * 函数调用：ObjTestUser() -> VisitorTestUserPaging()
     * 创建时间：2021-01-13 12:04:28
     * */
    public function VisitorTestUserPaging(){
        
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
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,userName,eMail,phoneNumber,password,loginTimes,loginTime,loginIp,loginMac,refereeId,userIdCard,userIdCardPhoto,userIdCardName,userNick,userSex,userPhoto,userCheck,userCheckAdminId,userRegisterType,userRegisterDescript,userRegisterAdminId,userState,wecharOpenId,wechatAvatarUrl,wechatNickName,wechatPublicOpenId,wechatUnionId";
        
        //渲染提示
        //$vResultTips = GetParameterRenderTips();
        //if(JudgeJsonFalseString($vResultTips)){return $vResultTips;}
        
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
            //"result_tips" => $vResultTips,
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
     * 函数名称：用户:系统:记录添加
     * 函数调用：ObjTestUser() -> SystemTestUserAdd
     * 创建时间：2021-01-13 12:04:27
     * */
    public function SystemTestUserAdd($fpUserName,$fpEMail,$fpPhoneNumber,$fpPassword,$fpLoginTimes,$fpLoginTime,$fpLoginIp,$fpLoginMac,$fpRefereeId,$fpUserIdCard,$fpUserIdCardPhoto,$fpUserIdCardName,$fpUserNick,$fpUserSex,$fpUserPhoto,$fpUserCheck,$fpUserCheckAdminId,$fpUserRegisterType,$fpUserRegisterDescript,$fpUserRegisterAdminId,$fpUserState,$fpWecharOpenId,$fpWechatAvatarUrl,$fpWechatNickName,$fpWechatPublicOpenId,$fpWechatUnionId){
        
        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "userName,eMail,phoneNumber,password,loginTimes,loginTime,loginIp,loginMac,refereeId,userIdCard,userIdCardPhoto,userIdCardName,userNick,userSex,userPhoto,userCheck,userCheckAdminId,userRegisterType,userRegisterDescript,userRegisterAdminId,userState,wecharOpenId,wechatAvatarUrl,wechatNickName,wechatPublicOpenId,wechatUnionId",
            "username" => $fpUserName,
            "email" => $fpEMail,
            "phonenumber" => $fpPhoneNumber,
            "password" => $fpPassword,
            "logintimes" => $fpLoginTimes,
            "logintime" => $fpLoginTime,
            "loginip" => $fpLoginIp,
            "loginmac" => $fpLoginMac,
            "refereeid" => $fpRefereeId,
            "useridcard" => $fpUserIdCard,
            "useridcardphoto" => $fpUserIdCardPhoto,
            "useridcardname" => $fpUserIdCardName,
            "usernick" => $fpUserNick,
            "usersex" => $fpUserSex,
            "userphoto" => $fpUserPhoto,
            "usercheck" => $fpUserCheck,
            "usercheckadminid" => $fpUserCheckAdminId,
            "userregistertype" => $fpUserRegisterType,
            "userregisterdescript" => $fpUserRegisterDescript,
            "userregisteradminid" => $fpUserRegisterAdminId,
            "userstate" => $fpUserState,
            "wecharopenid" => $fpWecharOpenId,
            "wechatavatarurl" => $fpWechatAvatarUrl,
            "wechatnickname" => $fpWechatNickName,
            "wechatpublicopenid" => $fpWechatPublicOpenId,
            "wechatunionid" => $fpWechatUnionId,
            //"key_field" => "refereeId,userCheckAdminId,userRegisterAdminId,wecharOpenId,wechatPublicOpenId,wechatUnionId",
        );
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }
    
    
    //---------- 用户方法（user） ----------
    
    /**
     * 函数名称：用户:用户:记录查询
     * 函数调用：ObjTestUser() -> UserTestUserPaging($fpUserId)
     * 创建时间：2021-01-13 12:04:28
     * */
    public function UserTestUserPaging($fpUserId){
        
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
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,userName,eMail,phoneNumber,password,loginTimes,loginTime,loginIp,loginMac,refereeId,userIdCard,userIdCardPhoto,userIdCardName,userNick,userSex,userPhoto,userCheck,userCheckAdminId,userRegisterType,userRegisterDescript,userRegisterAdminId,userState,wecharOpenId,wechatAvatarUrl,wechatNickName,wechatPublicOpenId,wechatUnionId";
        
        //渲染提示
        //$vResultTips = GetParameterRenderTips();
        //if(JudgeJsonFalseString($vResultTips)){return $vResultTips;}
        
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
        //"result_tips" => $vResultTips,
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
     * 函数名称：用户:管理员:记录添加
     * 函数调用：ObjTestUser() -> AdminTestUserAdd($fpAdminId)
     * 创建时间：2021-01-13 12:04:27
     * */
    public function AdminTestUserAdd($fpAdminId){
        
        
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
        
        //参数:用户昵称:userNick
        $pUserNick = GetParameterNoCode("usernick",$json);
        if(!JudgeRegularFont($pUserNick)){return JsonModelParameterException("usernick", $pUserNick, 36, "内容格式错误", __LINE__);}
        
        //参数:手机号码[登陆标识]:phoneNumber
        $pPhoneNumber = GetParameterNoCode("phonenumber",$json);
        if(!JudgeRegularPhone($pPhoneNumber)){return JsonModelParameterException("phonenumber", $pPhoneNumber, 11, "请输入正确的手机号", __LINE__);}
        
        //参数:密码:password
        $pPassword = GetParameterNoCode("password",$json);
        if(!JudgeRegularPassword($pPassword)){return JsonModelParameterException("password", $pPassword, 64, "密码格式错误", __LINE__);}
        $pPassword = HandleStringMD5($pPassword);
        
        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "phoneNumber,password,userNick",
            "key_field" => "phoneNumber",
            "phonenumber" => $pPhoneNumber,
            "usernick" => $pUserNick,
            "password" => $pPassword,
            "execution_step" => "update,insert",
        );
        
        //执行:添加
        $vJsonResult = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    }
    
    
    /**
     * 函数名称：用户:管理员:记录查询
     * 函数调用：ObjTestUser() -> AdminTestUserPaging($fpAdminId)
     * 创建时间：2021-01-13 12:04:28
     * */
    public function AdminTestUserPaging($fpAdminId){
        
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
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,userName,eMail,phoneNumber,password,loginTimes,loginTime,loginIp,loginMac,refereeId,userIdCard,userIdCardPhoto,userIdCardName,userNick,userSex,userPhoto,userCheck,userCheckAdminId,userRegisterType,userRegisterDescript,userRegisterAdminId,userState,wecharOpenId,wechatAvatarUrl,wechatNickName,wechatPublicOpenId,wechatUnionId";
        
        //渲染提示
        //$vResultTips = GetParameterRenderTips();
        //if(JudgeJsonFalseString($vResultTips)){return $vResultTips;}
        
        //--- Json组合区 ---
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "data_field" => $vDataField,
            "where_field" => "",
            "where_value" => "",
            "page" => $pPage,
            "limit" => $pLimit,
            //"orderby" => "id:desc",
            "like_field" => $pLikeField,
            "like_key" => $pLikeKey,
            //"result_tips" => $vResultTips,
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
     * 函数名称：用户:管理员:记录修改
     * 函数调用：ObjTestUser() -> AdminTestUserSet($fpAdminId)
     * 创建时间：2021-01-13 12:04:28
     * */
    public function AdminTestUserSet($fpAdminId){
        
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
        
        //参数:用户名[登陆标识]:userName
        $pUserName = GetParameterNoCode("username",$json);
        if(!IsNull($pUserName)&&!JudgeRegularFont($pUserName)){return JsonModelParameterException("username", $pUserName, 36, "内容格式错误", __LINE__);}
        
        //参数:邮箱[登陆标识]:eMail
        $pEMail = GetParameterNoCode("email",$json);
        if(!IsNull($pEMail)&&!JudgeRegularFont($pEMail)){return JsonModelParameterException("email", $pEMail, 36, "内容格式错误", __LINE__);}
        
        //参数:手机号码[登陆标识]:phoneNumber
        $pPhoneNumber = GetParameterNoCode("phonenumber",$json);
        if(!IsNull($pPhoneNumber)&&!JudgeRegularPhone($pPhoneNumber)){return JsonModelParameterException("phonenumber", $pPhoneNumber, 11, "内容格式错误", __LINE__);}
        
        //参数:密码:password
        $pPassword = GetParameterNoCode("password",$json);
        if(!IsNull($pPassword)&&!JudgeRegularPassword($pPassword)){return JsonModelParameterException("password", $pPassword, 64, "密码格式错误", __LINE__);}
        if(!IsNull($pPassword)){$pPassword = HandleStringMD5($pPassword);}
        
        //参数:登陆次数:loginTimes
        $pLoginTimes = GetParameterNoCode("logintimes",$json);
        if(!IsNull($pLoginTimes)&&!JudgeRegularInt($pLoginTimes)){return JsonModelParameterException("logintimes", $pLoginTimes, 11, "值必须是整数", __LINE__);}
        
        //参数:登陆时间:loginTime
        $pLoginTime = GetParameterNoCode("logintime",$json);
        if(!IsNull($pLoginTime)&&!JudgeRegularDate($pLoginTime)){return JsonModelParameterException("logintime", $pLoginTime, 0, "日期格式错误", __LINE__);}
        
        //参数:登陆IP:loginIp
        $pLoginIp = GetParameterNoCode("loginip",$json);
        if(!IsNull($pLoginIp)&&!JudgeRegularFont($pLoginIp)){return JsonModelParameterException("loginip", $pLoginIp, 36, "内容格式错误", __LINE__);}
        
        //参数:登陆端MAC:loginMac
        $pLoginMac = GetParameterNoCode("loginmac",$json);
        if(!IsNull($pLoginMac)&&!JudgeRegularFont($pLoginMac)){return JsonModelParameterException("loginmac", $pLoginMac, 36, "内容格式错误", __LINE__);}
        
        //参数:用户推荐人:refereeId
        $pRefereeId = GetParameterNoCode("refereeid",$json);
        if(!IsNull($pRefereeId)&&!JudgeRegularFont($pRefereeId)){return JsonModelParameterException("refereeid", $pRefereeId, 36, "内容格式错误", __LINE__);}
        
        //参数:用户身份证:userIdCard
        $pUserIdCard = GetParameterNoCode("useridcard",$json);
        if(!IsNull($pUserIdCard)&&!JudgeRegularLetterNumber($pUserIdCard)){return JsonModelParameterException("useridcard", $pUserIdCard, 36, "手机号格式错误", __LINE__);}
        
        //参数:用户手持身份证照片:userIdCardPhoto
        $pUserIdCardPhoto = GetParameterNoCode("useridcardphoto",$json);
        if(!IsNull($pUserIdCardPhoto)&&!JudgeRegularLetterNumber($pUserIdCardPhoto)){return JsonModelParameterException("useridcardphoto", $pUserIdCardPhoto, 256, "手机号格式错误", __LINE__);}
        
        //参数:用户身份证姓名:userIdCardName
        $pUserIdCardName = GetParameterNoCode("useridcardname",$json);
        if(!IsNull($pUserIdCardName)&&!JudgeRegularLetterNumber($pUserIdCardName)){return JsonModelParameterException("useridcardname", $pUserIdCardName, 36, "手机号格式错误", __LINE__);}
        
        //参数:用户昵称:userNick
        $pUserNick = GetParameterNoCode("usernick",$json);
        if(!IsNull($pUserNick)&&!JudgeRegularFont($pUserNick)){return JsonModelParameterException("usernick", $pUserNick, 36, "内容格式错误", __LINE__);}
        
        //参数:用户性别:userSex
        $pUserSex = GetParameterNoCode("usersex",$json);
        if(!IsNull($pUserSex)&&!JudgeRegularFont($pUserSex)){return JsonModelParameterException("usersex", $pUserSex, 10, "内容格式错误", __LINE__);}
        
        //参数:用户照片:userPhoto
        $pUserPhoto = GetParameterNoCode("userphoto",$json);
        if(!IsNull($pUserPhoto)&&!JudgeRegularFont($pUserPhoto)){return JsonModelParameterException("userphoto", $pUserPhoto, 128, "内容格式错误", __LINE__);}
        
        //参数:用户审核:userCheck
        $pUserCheck = GetParameterNoCode("usercheck",$json);
        if(!IsNull($pUserCheck)&&!JudgeRegularFont($pUserCheck)){return JsonModelParameterException("usercheck", $pUserCheck, 36, "内容格式错误", __LINE__);}
        
        //参数:用户审核管理员ID:userCheckAdminId
        $pUserCheckAdminId = GetParameterNoCode("usercheckadminid",$json);
        if(!IsNull($pUserCheckAdminId)&&!JudgeRegularInt($pUserCheckAdminId)){return JsonModelParameterException("usercheckadminid", $pUserCheckAdminId, 11, "值必须是整数", __LINE__);}
        
        //参数:用户注册类型:userRegisterType
        $pUserRegisterType = GetParameterNoCode("userregistertype",$json);
        if(!IsNull($pUserRegisterType)&&!JudgeRegularFont($pUserRegisterType)){return JsonModelParameterException("userregistertype", $pUserRegisterType, 16, "内容格式错误", __LINE__);}
        
        //参数:用户注册描述:userRegisterDescript
        $pUserRegisterDescript = GetParameterNoCode("userregisterdescript",$json);
        if(!IsNull($pUserRegisterDescript)&&!JudgeRegularFont($pUserRegisterDescript)){return JsonModelParameterException("userregisterdescript", $pUserRegisterDescript, 64, "内容格式错误", __LINE__);}
        
        //参数:用户注册管理员ID[当该用户为管理员注册用户时]:userRegisterAdminId
        $pUserRegisterAdminId = GetParameterNoCode("userregisteradminid",$json);
        if(!IsNull($pUserRegisterAdminId)&&!JudgeRegularInt($pUserRegisterAdminId)){return JsonModelParameterException("userregisteradminid", $pUserRegisterAdminId, 11, "值必须是整数", __LINE__);}
        
        //参数:用户状态:userState
        $pUserState = GetParameterNoCode("userstate",$json);
        if(!IsNull($pUserState)&&!JudgeRegularState($pUserState)){return JsonModelParameterException("userstate", $pUserState, 36, "状态值格式错误", __LINE__);}
        
        //参数:微信OpenId:wecharOpenId
        $pWecharOpenId = GetParameterNoCode("wecharopenid",$json);
        if(!IsNull($pWecharOpenId)&&!JudgeRegularFont($pWecharOpenId)){return JsonModelParameterException("wecharopenid", $pWecharOpenId, 36, "内容格式错误", __LINE__);}
        
        //参数:微信头像:wechatAvatarUrl
        $pWechatAvatarUrl = GetParameterNoCode("wechatavatarurl",$json);
        if(!IsNull($pWechatAvatarUrl)&&!JudgeRegularFont($pWechatAvatarUrl)){return JsonModelParameterException("wechatavatarurl", $pWechatAvatarUrl, 256, "内容格式错误", __LINE__);}
        
        //参数:微信昵称:wechatNickName
        $pWechatNickName = GetParameterNoCode("wechatnickname",$json);
        if(!IsNull($pWechatNickName)&&!JudgeRegularFont($pWechatNickName)){return JsonModelParameterException("wechatnickname", $pWechatNickName, 64, "内容格式错误", __LINE__);}
        
        //参数:微信公众号OpenId:wechatPublicOpenId
        $pWechatPublicOpenId = GetParameterNoCode("wechatpublicopenid",$json);
        if(!IsNull($pWechatPublicOpenId)&&!JudgeRegularFont($pWechatPublicOpenId)){return JsonModelParameterException("wechatpublicopenid", $pWechatPublicOpenId, 64, "内容格式错误", __LINE__);}
        
        //参数:用户微信unionid:wechatUnionId
        $pWechatUnionId = GetParameterNoCode("wechatunionid",$json);
        if(!IsNull($pWechatUnionId)&&!JudgeRegularFont($pWechatUnionId)){return JsonModelParameterException("wechatunionid", $pWechatUnionId, 64, "内容格式错误", __LINE__);}
        
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
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "userName", $pUserName);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "eMail", $pEMail);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "phoneNumber", $pPhoneNumber);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "password", $pPassword);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "loginTimes", $pLoginTimes);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "loginTime", $pLoginTime);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "loginIp", $pLoginIp);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "loginMac", $pLoginMac);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "refereeId", $pRefereeId);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "userIdCard", $pUserIdCard);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "userIdCardPhoto", $pUserIdCardPhoto);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "userIdCardName", $pUserIdCardName);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "userNick", $pUserNick);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "userSex", $pUserSex);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "userPhoto", $pUserPhoto);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "userCheck", $pUserCheck);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "userCheckAdminId", $pUserCheckAdminId);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "userRegisterType", $pUserRegisterType);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "userRegisterDescript", $pUserRegisterDescript);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "userRegisterAdminId", $pUserRegisterAdminId);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "userState", $pUserState);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "wecharOpenId", $pWecharOpenId);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "wechatAvatarUrl", $pWechatAvatarUrl);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "wechatNickName", $pWechatNickName);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "wechatPublicOpenId", $pWechatPublicOpenId);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "wechatUnionId", $pWechatUnionId);
        
        //判断字段值是否为空
        $vUpdateFieldJudgeInfor = JudgeFlyJsonAddUpdateField($jsonKeyValueArray,"descript,indexnumber,shelfstate,state,username,email,phonenumber,password,logintimes,logintime,loginip,loginmac,refereeid,useridcard,useridcardphoto,useridcardname,usernick,usersex,userphoto,usercheck,usercheckadminid,userregistertype,userregisterdescript,userregisteradminid,userstate,wecharopenid,wechatavatarurl,wechatnickname,wechatpublicopenid,wechatunionid");
        if(JudgeJsonFalse($vUpdateFieldJudgeInfor)){ return $vUpdateFieldJudgeInfor; }
        
        //执行:修改
        $vJsonResult = MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));
        if(JudgeJsonTrue($vJsonResult)){
            ObjFlyUserAdminOperationLog() -> SystemFlyUserAdminOperationLogAdd($fpAdminId, __CLASS__, __FUNCTION__, "管理员修改记录", $pId);
        }
        return $vJsonResult;
        
    }
    
    
    /**
     * 函数名称：用户:管理员:记录状态修改
     * 函数调用：ObjTestUser() -> AdminTestUserSetState($fpAdminId)
     * 创建时间：2021-01-13 12:04:28
     * */
    public function AdminTestUserSetState($fpAdminId){
        
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
     * 函数名称：用户:管理员:数据上下架状态修改
     * 函数调用：ObjTestUser() -> AdminTestUserShelfState($fpAdminId)
     * 创建时间：2021-01-13 12:04:28
     * */
    public function AdminTestUserShelfState($fpAdminId){
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
     * 函数名称：用户:管理员:记录永久删除
     * 函数调用：ObjTestUser() -> AdminTestUserDelete($fpAdminId)
     * 创建时间：2021-01-13 12:04:28
     * */
    public function AdminTestUserDelete($fpAdminId){
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
    
    
    //---------- 测试方法（test） ----------
    
    //---------- 基础方法（base） ----------
    
    
    /**
     * 函数名称：获取数据表名称
     * 函数调用：ObjTestUser() -> GetTableName()
     * 创建时间：2021-01-13 12:04:27
     * */
    public function GetTableName(){
        return self::$tableName;
    }
    
    /**
     * 函数名称：获取类描述
     * 函数调用：ObjTestUser() -> GetClassDescript()
     * 创建时间：2021-01-13 12:04:27
     * */
    public function GetClassDescript(){
        return self::$classDescript;
    }
    
    /**
     * 函数名称：获取数据表字段
     * 函数调用：ObjTestUser() -> GetTableField()
     * 创建时间：2021-01-13 12:04:27
     * */
    public function GetTableField(){
        return MIndexDBTableFields(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }
    
    /**
     * 函数名称：类数据表创建
     * 函数调用：ObjTestUser() -> OprationCreateTable()
     * 创建时间：2021-01-13 12:04:27
     * */
    public function OprationCreateTable(){
        $sql = "CREATE TABLE `test_user` (  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '表ID',  `onlyKey` varchar(36) DEFAULT NULL COMMENT '唯一Key',  `descript` varchar(36) DEFAULT NULL COMMENT '记录描述',  `indexNumber` int(11) DEFAULT '-1' COMMENT '序号',  `updateTime` timestamp NULL DEFAULT NULL COMMENT '修改时间',  `addTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',  `shelfState` varchar(36) DEFAULT 'true' COMMENT '上下架状态',  `state` varchar(36) DEFAULT 'STATE_NORMAL' COMMENT '记录状态',  `userName` varchar(36) DEFAULT NULL COMMENT '用户名[登陆标识]',  `eMail` varchar(36) DEFAULT NULL COMMENT '邮箱[登陆标识]',  `phoneNumber` varchar(11) DEFAULT NULL COMMENT '手机号码[登陆标识]',  `password` varchar(64) DEFAULT NULL COMMENT '密码',  `loginTimes` int(11) DEFAULT '0' COMMENT '登陆次数',  `loginTime` timestamp NULL DEFAULT NULL COMMENT '登陆时间',  `loginIp` varchar(36) DEFAULT NULL COMMENT '登陆IP',  `loginMac` varchar(36) DEFAULT NULL COMMENT '登陆端MAC',  `refereeId` varchar(36) DEFAULT NULL COMMENT '用户推荐人',  `userIdCard` varchar(36) DEFAULT NULL COMMENT '用户身份证',  `userIdCardPhoto` varchar(256) DEFAULT NULL COMMENT '用户手持身份证照片',  `userIdCardName` varchar(36) DEFAULT NULL COMMENT '用户身份证姓名',  `userNick` varchar(36) DEFAULT NULL COMMENT '用户昵称',  `userSex` varchar(10) DEFAULT NULL COMMENT '用户性别',  `userPhoto` varchar(128) DEFAULT NULL COMMENT '用户照片',  `userCheck` varchar(36) DEFAULT 'false' COMMENT '用户审核',  `userCheckAdminId` int(11) DEFAULT NULL COMMENT '用户审核管理员ID',  `userRegisterType` varchar(16) DEFAULT NULL COMMENT '用户注册类型',  `userRegisterDescript` varchar(64) DEFAULT NULL COMMENT '用户注册描述',  `userRegisterAdminId` int(11) DEFAULT NULL COMMENT '用户注册管理员ID[当该用户为管理员注册用户时]',  `userState` varchar(36) DEFAULT 'USER_NORMAL' COMMENT '用户状态',  `wecharOpenId` varchar(36) DEFAULT NULL COMMENT '微信OpenId',  `wechatAvatarUrl` varchar(256) DEFAULT NULL COMMENT '微信头像',  `wechatNickName` varchar(64) DEFAULT NULL COMMENT '微信昵称',  `wechatPublicOpenId` varchar(64) DEFAULT NULL COMMENT '微信公众号OpenId',  `wechatUnionId` varchar(64) DEFAULT NULL COMMENT '用户微信unionid',  PRIMARY KEY (`id`) USING BTREE,  UNIQUE KEY `phonenumber` (`phoneNumber`) USING BTREE) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户'";
        DBHelper::DataSubmit($sql, "");
        $vTableCheck = DBMySQLServiceJson::GetDBTableCheck('{"table_name":"'.self::$tableName.'"}');
        if(JudgeJsonTrue($vTableCheck)){
            return JsonInforTrue("创建成功", self::$tableName);
        }
        return JsonInforFalse("创建失败", self::$tableName);
    }
    
    /**
     * 函数名称：类数据表基础字段检测
     * 函数调用：ObjTestUser() -> OprationTableFieldBaseCheck()
     * 创建时间：2021-01-13 12:04:27
     * */
    public function OprationTableFieldBaseCheck(){
        return DBMySQLServiceJson::OprationFieldBaseCheck(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }
    
    
    
    
}
