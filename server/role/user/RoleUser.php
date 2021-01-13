<?php

/**------------------------------------*
 * 名称：网站用户类
 * 说明：网站用户相关功能
 * 创建时间：2019-03-15 19:30
 * ------------------------------------*/

//--- 引用区 ---

class RoleUser{
    
    //------------ 类成员 ------------
    
    //角色名称
    private $roleName = "用户";
    
    //角色sessionKey
    private static $sessionKey = "user";

    //类描述
    public static $classDescript = "用户";
    
    //表名称
    public static $tableName = "fly_user";
    
    //短信发送类型：用户注册
    public static $sendTypeUserRegister = "用户注册";
    
    
    //------------ 私有方法区 ------------
    
    /**
     * 设置Session
     * 创建时间：2019-03-15 19:32
     * 说明：设置网站用户的ID值到Session中
     * 检测：逻辑
     * 检测时间：2019-03-15 19:32
     * */
    private function SessionSet($fpUserId){
        SetSessionKey(self::$sessionKey,$fpUserId);
    }
    
    /**
     * 获取Session
     * 创建时间：2019-03-15 19:32
     * 说明：获取Session中网站用户ID
     * 检测：逻辑
     * 检测时间：2019-03-15 19:32
     * */
    private function SessionGet(){
        return RBACSessionGetValue(self::$sessionKey);
    }
    
    /**
     * 清除Session
     * 创建时间：December 1,2018 10:14
     * 说明：清除Session中网站用户ID
     * 检测：逻辑
     * 检测时间：创建时间：December 1,2018 10:14
     * */
    private function SessionClear(){
        SetSessionKey(self::$sessionKey,"");

    }

    /** 登陆信息修改 */
    private function UserLoginInforSet($fpUserId){
        $vIp = GetPathRemoteAddr();
        $vNowTime = GetTimeNow();
        //修改:fly_user_admin:登陆次数、登陆时间、登陆IP
        $vSql = "UPDATE fly_user SET loginTimes=loginTimes+1,loginTime=?,loginIp=? WHERE id=?;";
        return DBHelper::DataSubmit($vSql, [$vNowTime,$vIp,$fpUserId]);
    }
    
    //------------ 系统方法（System）----------
    
    /**
     * 判断Session
     * 创建时间：December 1,2018 10:14
     * 说明：清除Session中网站用户ID
     * 检测：逻辑
     * 检测时间：创建时间：December 1,2018 10:14
     * */
    public function SessionJudge(){
        if(IsNull($this -> SessionGet())){
            return false;
        }
        return true;
    }
    
    /**
     * 获取超级管理员Session存储的用户ID
     * 创建时间：2019-05-25 01:50:00
     * */
    public function SessionUserId(){
        return $this -> SessionGet();
    }
    
    /**
     * 获取网站用户Session存储结果
     * 创建时间：December 7,2018 17:18
     * 说明：获取网站用户Session，如果存在返回true，否则返回false
     * 检测：对象、逻辑
     * 检测时间：Decemeber 7,2018 17:18
     * */
    public function SessionResult(){
        return $this -> SessionJudge();
    }


    /**
     * 通过OpenId获取用户ID
     * 创建时间：2019-12-19 17:00:21
     * */
    public function GetUserIdFromOpenId($fpOpenId){
        //获取:fly_user:表ID
        $vSql = "SELECT id FROM fly_user WHERE wecharOpenId=? LIMIT 0,1;";
        return DBHelper::DataString($vSql, [$fpOpenId]);
    }
    
    /**
     * 函数名称：获取数据表字段
     * 函数调用：ObjRoleObjectUser() -> GetTableField()
     * 创建时间：2020-02-27 12:22:41
     * */
    public function GetTableField(){
        return MIndexDBTableFields(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }
    
    //------------ 公开方法区 ------------

    
    
    /**
     * 网站用户登陆
     * 创建时间：December 1,2018 09:59
     * 说明：网站用户相关的操作必须在网站用户登陆之后才可以进行进行
     * 检测：
     * 检测时间：
     * */
    public function Login(){        
        //--- 变量区声明区 --
        //变量:网站用户数据表名称
        $userTableName = self::$tableName;
        
        //变量:角色名称、分类名称
        $role = $this -> roleName;
        //变量:组名称
        $groupName = "手机号";
        
        //--- 方法判断区 ---
        //Session判断
        if($this -> SessionJudge()){
            return JsonInforTrue("已登陆",$role);
        }
    
        //--- 参数获取区 ---
        //校验:手机号
        $phonenumber = GetParameter('phonenumber', "");
        if(IsNull($phonenumber)){return JsonModelParameterNull("phonenumber");}
        $password = GetParameter('password', "");
        if(IsNull($password)){return JsonModelParameterNull("password");}
        $password = HandleStringMD5($password);
    
        //用户判断
        $vSql = "SELECT id,password FROM fly_user WHERE phonenumber = '{$phonenumber}';";
        $vUserList = DBHelper::DataList($vSql, "", array("id","password"));
        if(IsNull($vUserList)){
            return JsonInforFalse("用户不存在", $role);
        }
        
        //判断用户密码是否为空
        $vUserJsonObj = GetJsonObject($vUserList);
        if(IsNull($vUserJsonObj[0] -> password)){
            return JsonInforFalse("请重置用户密码", $role);
        }
        
        //用户ID获取
        $userId = $vUserJsonObj[0] -> id;
        
        //用户账号和密码是否正确
        $jsonKeyValueArray = array(
            "table_name" => $userTableName,
            "where_field" => "phonenumber,password",
            "where_value" => "{$phonenumber},{$password}",
            "page" => "1",
            "limit" => "1",
        );
        $json = MIndexDataPaging(JsonHandleArray($jsonKeyValueArray));
        
        //判断数据结果为false或为0，赋值返回结果
        $resultBo = true;
        if(JudgeJsonCountZero($json)){
            $resultBo = false;
        }
        
        //登陆日志
        $result = ObjFlyRoleLoginLog() -> Login($role, $resultBo, $role, $groupName, $userId, $phonenumber, $userTableName);
        
        //登陆成功后写Session
        if(JudgeJsonValue($result, "result", "true")){
            $this -> SessionSet($userId);   //写用户ID到Session中 将用户的认证方式统一改为Token校验，不在使用Session方式2020-02-26 18:19:58 --- 
            $token = RBACTokenGet($userId, "USER", $phonenumber, 60, PROJECT_CONFIG_TOKEN_SECRET_USER,"登陆成功");
            self::UserLoginInforSet($userId);
            OpreationLog("USER", $userId, "用户登陆", __FILE__, __FUNCTION__, __LINE__, "true", "登陆成功");
            //给与登录积分
            ObjFlyUserIntegral() -> UserFlyUserIntegralLogin($userId);
            return $token;
        }else{
            OpreationLog("USER", $userId, "用户登陆", __FILE__, __FUNCTION__, __LINE__, "false", GetJsonValue($result, "infor"));
        }
        
        //返回登陆日志处理结果
        return $result;
    }


    /**
     * 获取网站用户Session存储结果
     * 创建时间：December 1,2018 15:05
     * 说明：获取网站用户Session，如果存在返回true，否则返回false，以Json方式进行返回
     * 检测：对象、逻辑
     * 检测时间：Decemeber 1,2018 15:16
     * */
    public function LoginState(){
        
        //--- 变量区 ---
        //角色
        $role = $this -> roleName;
        $userId = $this -> SessionGet();
        
        //--- 逻辑区 ---
        //Session判断
        if($this -> SessionJudge()){
            return JsonInforTrue("已登陆",$role,$userId);
        }
        return JsonInforFalse("未登陆",$role);
    }

   
    /**
     * 清除网站用户的登陆Session
     * 创建时间：December 1,2018 15:17
     * 说明：清除网站用户存储在Session中的值，以Json的方式进行返回
     * 检测：对象、逻辑
     * 检测时间：Decemeber 1,2018 15:26
     * */
    public function LoginClear(){
        
        //--- 变量区 ---
        //角色
        $role = $this -> roleName;
        
        //--- 逻辑区 ---
        //Session判断
        if(!($this -> SessionJudge())){
            return JsonInforFalse("未登陆",$role);
        }
        
        $this -> SessionClear();
        return JsonInforTrue("退出成功",$role);
    }
    
    /**
     * 网站用户未登陆
     * 创建时间：December 9,2018 21:05
     * 说明：网站用户未登陆
     * 检测：逻辑
     * 检测时间：December 9,2018 21:05
     * */
    public function LoginNot(){
        return JsonInforFalse("未登陆", "用户");
    }

    
    //------------ 用户方法区 ------------
    
    /**
     *
     * 记录查询
     * 创建时间：2019-08-30 11:37:04
     * */
    public function UserInfor($fpUserId){
        
        //--- 数据预定义 ---
        $json = "";
        
        //--- Json组合区 ---
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "data_field" => "id,phoneNumber,userNick",
            "where_field" => "id",
            "where_value" => "{$fpUserId}",
            "page" => "1",
            "limit" => "1",
        );
        //返回结果:分页
        return MIndexDataPaging(JsonHandleArray($jsonKeyValueArray));
    }
    
    
    /**
     *
     * 用户信息：归属地修改
     * 创建时间：2020-05-12 14:24:44
     * */
    public function UserInforHomeSet($fpUserId){
        
        //--- 参数获取区 ---
        $json = "";
        
        //参数:手机归属省份:phoneProvinceCode
        $pPhoneProvinceCode = GetParameter("phone_province_code",$json);
        if(!JudgeRegularNumberLetter($pPhoneProvinceCode)){return JsonModelParameterException("phone_province_code", $pPhoneProvinceCode, 32, "内容格式错误", __LINE__);}
        
        //参数:手机归属城市:phoneCity
        $pPhoneCityCode = GetParameter("phone_city_code",$json);
        if(!JudgeRegularNumberLetter($pPhoneCityCode)){return JsonModelParameterException("phone_city_code", $pPhoneCityCode, 32, "内容格式错误", __LINE__);}
        
        $dataList = DBHelper::DataList("SELECT areaName FROM fly_area WHERE areaCode='{$pPhoneProvinceCode}' OR areaCode='{$pPhoneCityCode}' ORDER BY areaLevel ASC;", NULL, ["areaName"]);
        //判断结果是否为空
        if(IsNull($dataList)){
            return JsonInforFalse("区域记录不存在", "phone_city_code");
        }
        //转换为Json数组
        $objJson = GetJsonObject($dataList);
        //判断成员数目
        if(sizeof($objJson)!=2){
            return JsonInforFalse("区域记录不存在", "phone_city_code");
        }
        
        $vProvinceName = $objJson[0]->areaName;
        $vCityName = $objJson[1]->areaName;
        
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "update_field" => "phoneProvince,phoneCity",
            "update_value" => "{$vProvinceName},{$vCityName}",
            "where_field" => "id",
            "where_value" => "{$fpUserId}",
        );
        //返回结果
        $result = MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));
        if(JudgeJsonFalse($result)){
            return $result;
        }
        return JsonInforTrue("修改成功", "{$vProvinceName},{$vCityName}");
    }
    
    
    
    /**
     * 用户推荐注册用户
     * 创建时间：2020-08-06 22:16:38
     * */
    public function UserRefereeUser($fpUserId=null){
        
        $vRefereeId = $fpUserId;
        if(IsNull($vRefereeId)){
            $vRefereeId = GetParameter('referee_id', "");
            if(!IsNull($vRefereeId)&&!JudgeRegularNumber($vRefereeId)){return JsonInforFalse("推荐人ID格式异常", "referee_id");}
            $vDbUserId = DBHelper::DataString("SELECT id FROM fly_user WHERE id = '{$vRefereeId}'", null);
            if(IsNull($vDbUserId)){
                return JsonInforFalse("推荐人ID不存在", "用户ID获取");
            }
        }
        
        $vRefereeMany = "100";
        
        $vUserName = GetParameter('user_name', "");
        if(!IsNull($vUserName)&&!JudgeRegularFont($vUserName)){return JsonInforFalse("用户名字格式异常", "user_name");}
        $vUserNameField = "";
        $vUserNameValue = "";
        if(!IsNull($vUserName)){
            $vUserNameField = ",userNick";
            $vUserNameValue = ",'{$vUserName}'";
        }
        
        $vPhonenumber = GetParameter('phonenumber', "");
        if(!JudgeRegularPhone($vPhonenumber)){return JsonInforFalse("手机号格式异常", "phonenumber");}
        
        $vPassword = GetParameter('password', "");
        if(!JudgeRegularPassword($vPassword)){return JsonInforFalse("密码格式异常", "password");}
        $vPassword = HandleStringMD5($vPassword);
        
        $vTableName = "fly_user";
        
        if(!IsNull($vRefereeId)){
            $sql = "SELECT id FROM {$vTableName} WHERE id='{$vRefereeId}'";
            $refereeId = DBHelper::DataString($sql, null);
            if(IsNull($refereeId)){
                return JsonInforFalse("推荐人不存在", "用户推荐注册用户");
            }
        }
        
        
        $vSql = "SELECT id FROM {$vTableName} WHERE phoneNumber = '{$vPhonenumber}'";
        if(DBHelper::DataBoolean($vSql, null)){
            return JsonInforFalse("用户已存在", $vTableName);
        }
        //添加用户
        $vSql = "";
        $vOnlyKey = GetId("R");
        if(!IsNull($vRefereeId)){
            $vSql = "INSERT INTO {$vTableName}(onlyKey,descript,phoneNumber,password{$vUserNameField},refereeId,userRegisterType,userRegisterDescript,userState) VALUES ('{$vOnlyKey}','".self::$classDescript."','{$vPhonenumber}','{$vPassword}'{$vUserNameValue},'{$vRefereeId}','用户注册','用户推荐注册用户','USER_INIT');";
        }else{
            $vSql = "INSERT INTO {$vTableName}(onlyKey,descript,phoneNumber,password{$vUserNameField},userRegisterType,userRegisterDescript,userState) VALUES ('{$vOnlyKey}','".self::$classDescript."','{$vPhonenumber}','{$vPassword}'{$vUserNameValue},'用户注册','用户推荐注册用户','USER_INIT');";
        }
        
        //提交SQL预计
        if(DBHelper::DataSubmit($vSql, "")){
            //返回注册信息
            return JsonInforTrue("用户注册成功", $vTableName, $vPhonenumber);
        };
        return JsonInforFalse("用户注册失败", $vTableName);
    }
    
    
    /**
     * 用户密码修改
     * 创建时间：2020-08-08 22:52:09
     * */
    public function UserPasswordSet($fpUserId){
        
        //--- 变量预定义 ---
        $json="";
        
        //--- 参数获取区 ---
        
        //参数:密码:password
        $pPassword = GetParameter("old_password",$json);
        if(!JudgeRegularLetterNumber($pPassword)){return JsonModelParameterException("old_password", $pPassword, 64, "值不符合规则", __LINE__);}
        $pPassword = HandleStringMD5($pPassword);
        
        //参数:密码:password
        $pNewPassword = GetParameter("new_password",$json);
        if(!JudgeRegularLetterNumber($pNewPassword)){return JsonModelParameterException("new_password", $pNewPassword, 64, "值不符合规则", __LINE__);}
        $pNewPassword = HandleStringMD5($pNewPassword);
        
        //判断两次密码相同
        if($pPassword==$pNewPassword){
            return JsonInforFalse("不得与旧密码相同", "用户密码修改");
        }
        
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "update_field" => "password",
            "update_value" => $pNewPassword,
            "where_field" => "id",
            "where_value" => "{$fpUserId}",
        );
        //执行:添加
        return MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));
    }
    
    //------------ 管理员方法区 ------------

    /**
     * 用户
     * 管理员查询用户
     * 创建时间：2019-12-11 14:30:02
     * */
    public function AdminUserPaging($fpAdminId){
    
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
    
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
        $pLike = GetParameterNoCode("like","");
        $pLiketype = GetParameterNoCode("likefield","");
        if(IsNull($pLiketype)){$pLiketype = "id";}
        if(!IsNull($pLiketype)&&!DBMySQLJudge::JudgeTableField(self::$tableName,$pLiketype)){ return JsonInforFalse("搜索字段不存在", $pLiketype,__LINE__); }
        if(!IsNull($pLike)){ $pLike = HandleStringAddslashes($pLike); }
    
        //字段定义
        $vDataField = DBMySQLSelect::TableFieldString(self::$tableName);
        //$vDataField = "id,onlyKey,fatherId,typeName,groupName,indexNumber,updateTime,addTime,descript,state,shelfState,userId,username,email,phonenumber,password,loginTimes,loginTime,loginIp,loginMac,refereeId,userIdCard,userSex,userIdCardPhoto,userPhoto,userIdCardName,userCheck,userInsertType,userInforType,sourceUserType,sourceUserTime,sourceUserActivate,wecharOpenId,wechatAvatarUrl,wechatNickName";
    
        //--- Json组合区 ---
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "data_field" => $vDataField,
            "where_field" => "",
            "where_value" => "",
            "page" => $pPage,
            "limit" => $pLimit,
            //"orderby" => "id:desc",
            "like_field" => $pLiketype,
            "like_key" => $pLike,
        );
    
        //条件字段判断组合
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "id", $pId);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "state", $pWhereState);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "shelfState", $pWhereShelfState);
        //返回结果:分页
        return MIndexDataPaging(JsonHandleArray($jsonKeyValueArray));
    
    }
   
    
    /**
     * 管理员注册用户
     * 创建时间：2019-10-27 18:39:39
     * */
    public function AdminRegisterUser($fpAdminId){
        
        $vRefereeId = GetParameter('referee_id', "");
        if(!IsNull($vRefereeId)&&!JudgeRegularNumber($vRefereeId)){return JsonInforFalse("推荐人ID格式异常", "referee_id");}
        
        $vRefereeMany = GetParameter('referee_many', "");
        if(!IsNull($vRefereeMany)&&!JudgeRegularNumber($vRefereeMany)){return JsonInforFalse("推荐人奖励金额格式异常", "referee_many");}
        
        $vUserName = GetParameter('user_name', "");
        if(!IsNull($vUserName)&&!JudgeRegularFont($vUserName)){return JsonInforFalse("用户名字格式异常", "user_name");}
        $vUserNameField = "";
        $vUserNameValue = "";
        if(!IsNull($vUserName)){
            $vUserNameField = ",userNick";
            $vUserNameValue = ",'{$vUserName}'";
        }
    
        $vPhonenumber = GetParameter('phonenumber', "");
        if(!JudgeRegularPhone($vPhonenumber)){return JsonInforFalse("手机号格式异常", "phonenumber");}
        
        $vPassword = GetParameter('password', "");
        if(!JudgeRegularPassword($vPassword)){return JsonInforFalse("密码格式异常", "password");}
        $vPassword = HandleStringMD5($vPassword);
        
        $vTableName = "fly_user";
        
        if(!IsNull($vRefereeId)){
            $sql = "SELECT id FROM {$vTableName} WHERE id='{$vRefereeId}'";
            $refereeId = DBHelper::DataString($sql, null);
            if(IsNull($refereeId)){
                return JsonInforFalse("推荐人不存在", "管理员注册用户");
            }
        }
    
        
        $vSql = "SELECT id FROM {$vTableName} WHERE phoneNumber = '{$vPhonenumber}'";
        if(DBHelper::DataBoolean($vSql, null)){
            return JsonInforFalse("用户已存在", $vTableName);
        }
        //添加用户
        $vSql = "";
        $vOnlyKey = GetId("R");
        if(!IsNull($vRefereeId)){
            $vSql = "INSERT INTO {$vTableName}(onlyKey,descript,phoneNumber,password{$vUserNameField},refereeId,userRegisterType,userRegisterDescript,userRegisterAdminId,userState) VALUES ('{$vOnlyKey}','".self::$classDescript."','{$vPhonenumber}','{$vPassword}'{$vUserNameValue},'{$vRefereeId}','管理员注册用户','管理员注册用户','{$fpAdminId}','USER_INIT');";
        }else{
            $vSql = "INSERT INTO {$vTableName}(onlyKey,descript,phoneNumber,password{$vUserNameField},userRegisterType,userRegisterDescript,userRegisterAdminId,userState) VALUES ('{$vOnlyKey}','".self::$classDescript."','{$vPhonenumber}','{$vPassword}'{$vUserNameValue},'管理员注册用户','管理员注册用户','{$fpAdminId}','USER_INIT');";
        }
        
        if(DBHelper::DataSubmit($vSql, "")){
            //返回注册信息
            return JsonInforTrue("管理员注册用户成功", $vTableName, $vPhonenumber);
        };
        return JsonInforFalse("管理员注册用户失败", $vTableName);
    }
    
    /**
     * 管理员获取用户Token
     * 创建时间：2020-04-01 13:24:23
     * */
    public function AdminGetUserToken($fpAdminId){
        //--- 变量区声明区 --
        //变量:网站用户数据表名称
        $userTableName = self::$tableName;
        
        //变量:角色名称、分类名称
        $role = $this -> roleName;
        //变量:组名称
        $groupName = "手机号";
        
        //--- 参数获取区 ---
        //校验:用户ID
        $pUserId = GetParameter('userid', "");
        if(IsNull($pUserId)){return JsonModelParameterNull("userid");}
        
        //用户判断
        $vSql = "SELECT phoneNumber FROM fly_user WHERE id = '{$pUserId}';";
        $vPhonenumber = DBHelper::DataString($vSql, null);
        if(IsNull($vPhonenumber)){
            return JsonInforFalse("用户不存在", $role);
        }
        
        //登陆日志
        $result = ObjFlyRoleLoginLog() -> Login($role, "true", $role, $groupName, $pUserId, $vPhonenumber, $userTableName, "管理员获取Token", $fpAdminId);
        
        //返回登陆日志处理结果
        $token = RBACTokenGet($pUserId, "USER", $vPhonenumber, 60, PROJECT_CONFIG_TOKEN_SECRET_USER, "登陆成功");
        return $token;
        
    }
    
    /**
     * 函数名称：用户:管理员:记录修改
     * 函数调用：ObjFlyUser() -> AdminFlyUserSet($fpAdminId)
     * 创建时间：2020-02-27 13:07:30
     * */
    public function AdminFlyUserSet($fpAdminId){
    
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
    
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
        if(!IsNull($pPhoneNumber)&&!JudgeRegularFont($pPhoneNumber)){return JsonModelParameterException("phonenumber", $pPhoneNumber, 11, "内容格式错误", __LINE__);}
    
        //参数:密码:password
        $pPassword = GetParameterNoCode("password",$json);
        if(!IsNull($pPassword)&&!JudgeRegularFont($pPassword)){return JsonModelParameterException("password", $pPassword, 64, "内容格式错误", __LINE__);}
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
        if(!IsNull($pUserIdCardPhoto)&&!JudgeRegularUrl($pUserIdCardPhoto)){return JsonModelParameterException("useridcardphoto", $pUserIdCardPhoto, 256, "URL地址格式错误", __LINE__);}
    
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
        if(!IsNull($pUserPhoto)&&!JudgeRegularUrl($pUserPhoto)){return JsonModelParameterException("userphoto", $pUserPhoto, 128, "URL地址格式错误", __LINE__);}
    
        //参数:用户审核:userCheck
        $pUserCheck = GetParameterNoCode("usercheck",$json);
        if(!IsNull($pUserCheck)&&!JudgeRegularFont($pUserCheck)){return JsonModelParameterException("usercheck", $pUserCheck, 36, "内容格式错误", __LINE__);}
    
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
        $vUpdateFieldJudgeInfor = JudgeFlyJsonAddUpdateField($jsonKeyValueArray,"descript,indexnumber,shelfstate,state,username,email,phonenumber,password,logintimes,logintime,loginip,loginmac,refereeid,useridcard,useridcardphoto,useridcardname,usernick,usersex,userphoto,usercheck,userregistertype,userregisterdescript,userregisteradminid,userstate,wecharopenid,wechatavatarurl,wechatnickname,wechatpublicopenid,wechatunionid");
        if(JudgeJsonFalse($vUpdateFieldJudgeInfor)){ return $vUpdateFieldJudgeInfor; }
    
        //返回结果
        return MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));
    
    }
    
    
    /**
     * 函数名称：用户:管理员:记录状态修改
     * 函数调用：ObjFlyUser() -> AdminFlyUserSetState($fpAdminId)
     * 创建时间：2020-03-19 16:50:36
     * */
    public function AdminFlyUserSetState($fpAdminId){
    
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
    
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
        return ServiceTableDataSystemSet(self::$tableName,"state","{$pState}","id","{$pId}");
    }
    
    /**
     * 函数名称：用户:管理员:数据上下架状态修改
     * 函数调用：ObjFlyUser() -> AdminFlyUserShelfState($fpAdminId)
     * 创建时间：2020-03-19 16:50:36
     * */
    public function AdminFlyUserShelfState($fpAdminId){
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
    
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
        //执行:修改
        $vJsonResult = MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));
        return $vJsonResult;
    
    }
    
    /**
     * 函数名称：用户:管理员:记录永久删除
     * 函数调用：ObjFlyUser() -> AdminFlyUserDelete($fpAdminId)
     * 创建时间：2020-03-19 16:50:36
     * */
    public function AdminFlyUserDelete($fpAdminId){
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
    
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
        return $vJsonResult;
    }
    
    
    /**
     * 函数名称：用户审核
     * 函数调用：ObjFlyUserRefereeMany() -> AdminUserCheckStateSet()
     * 创建时间：2020-08-08 11:48:18
     * */
    public function AdminUserCheckStateSet($fpAdminId){
        
        $vUserId = GetParameter('id', "");
        if(!JudgeRegularNumber($vUserId)){return JsonInforFalse("记录ID格式异常", "id");}
        
        //推荐人ID获取
        $vRefereeId = DBHelper::DataString("SELECT refereeId FROM fly_user WHERE id='{$vUserId}'", null);
        
        //添加推荐用户奖励奖励
        if(!IsNull($vRefereeId)){
        	//计算用户推荐人
        	ObjFlyUserReferee() -> SystemFlyUserRefereeCalc($vUserId);
        	//计算推荐积分
        	ObjFlyUserReferee() -> SystemFlyUserIntegralCalc($vUserId);
        	//给与推荐奖励
        	ObjFlyUserRefereeMany() -> SystemFlyUserRefereeManyAdd($vUserId, $vRefereeId, "100", "现金奖励");
        }
        
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "update_field" => "userCheck,userCheckAdminId",
            "update_value" => "true,{$fpAdminId}",
            "where_field" => "id",
            "where_value" => "{$vUserId}",
        );
        
        //返回结果
        return MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));
    }
    
    
    /**
     * 函数名称：用户登录限制解除
     * 函数调用：ObjFlyUserRefereeMany() -> AdminUserLoginLimitClear($fpAdminId)
     * 创建时间：2020-10-08 10:33:32
     * */
    public function AdminUserLoginLimitClear($fpAdminId){
        
        //校验:用户ID
        $pUserId = GetParameter('userid', "");
        if(IsNull($pUserId)){return JsonModelParameterNull("userid");}
        
        $vThisTime = GetTimeNow();
        $vBeforeTime = HandleDatePlusMinute(HandleDateToStrtotime($vThisTime), "30",false);
        
        $sql = "DELETE FROM fly_role_login_log WHERE loginUser='{$pUserId}' AND loginType='用户登陆' AND loginState='LOGIN_FAIL' AND ADDTIME>'{$vBeforeTime}' AND ADDTIME<'{$vThisTime}'";
        
        //执行语句
        DBHelper::DataSubmit($sql,null);
        return JsonInforTrue("用户登录限制解除成功", "fly_role_login_log");
        //if(DBHelper::DataSubmit($sql,null)){
        //    return JsonInforTrue("删除成功", "fly_role_login_log");
        //}
        //return JsonInforFalse("删除失败", "fly_role_login_log");
        
    }    
    
    //====================== 短信想更函数 ============================
    
    
    /**
     * 用户注册:发送短信验证码
     * 创建时间：2019-07-22 11:20:00
     * */
    public function FlyUserRegisterSendPhoneCode(){
        
        //--- 变量声明区 ---
        $json = "";		//参数Json数据
        
        //--- 参数获取区 ---
        //参数：phonenumber
        $phonenumber = GetParameterNoCode("phonenumber",$json);
        if(IsNull($phonenumber)){return JsonModelParameterNull("phonenumber");}
        if(!JudgeRegularPhone($phonenumber)){return JsonInforFalse("手机号格式错判", "phonenumber");}
        
        //--- Json提交区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "data_field" => "id",
            "where_field" => "phoneNumber",
            "where_value" => "{$phonenumber}",
            "page" => "1",
            "limit" => "1",
        );
        //返回结果:分页
        $returnPaging = MIndexDataPaging(JsonHandleArray($jsonKeyValueArray));
        
        //--- 数据处理区 ---
        //判断是否获取成功
        if(!JudgeJsonValue($returnPaging, "result", "true")){
            return JsonInforFalse("获取失败", self::$tableName);
        }
        
        //判断用户是否存在
        if(!JudgeJsonValue($returnPaging, "count", "0")){
            return JsonInforFalse("用户已存在", $phonenumber);
        }
        
        //创建短信对象
        $phoneCode = GetIdNumber(4);    //短信验证码
        return ObjThirdPhoneCode() -> PhoneCodeSend($phonenumber, $phoneCode, "用户注册", ""); 
        
    }
    
    /**
     * 用户注册：添加记录
     * 创建时间：2019-07-22 11:20:00
     * @param string $regType 注册类型(phone:手机号注册, phone_login:手机号登录并注册)
     * @return string
     * @throws ClassFlyException
     */
    public function FlyUserRegister(){
        //--- 变量声明区 ---
        $json = "";		//参数Json数据
        
        //--- 参数获取区 ---
        //参数：neckname
        $pNeckname = GetParameterNoCode("neckname",$json);
        if(IsNull($pNeckname)){return JsonModelParameterNull("neckname");}
        //参数：phonenumber 正则判断 手机号判断
        if(!JudgeRegularFont($pNeckname)){return JsonInforFalse("参数不符合规则", "neckname");}
        
        //参数：phonenumber
        $phonenumber = GetParameterNoCode("phonenumber",$json);
        if(IsNull($phonenumber)){return JsonModelParameterNull("phonenumber");}
        //参数：phonenumber 正则判断 手机号判断
        if(!JudgeRegularPhone($phonenumber)){return JsonInforFalse("参数不符合规则", "phonenumber");}
        
        //参数：phonecode
        $phoneCode = GetParameterNoCode("phone_code",$json);
        if(IsNull($phoneCode)){return JsonModelParameterNull("phone_code");}
        if(!JudgeRegularPhoneCode($phoneCode,4)){return JsonInforFalse("参数不符合规则", "phonecode");}
        
        //参数：password
        $password = GetParameterNoCode("password", $json);
        if (!JudgeRegularPassword($password)){return JsonInforFalse("参数不符合规则", "password");}
        $password = HandleStringMD5($password);
        
        //参数：shareKey
        $pShareKey = GetParameterNoCode("share_key",$json);
        $vRefereeId = "none";
        if(JudgeRegularLetterNumber($pShareKey)){
            //获取:fly_user_share:用户ID
            $vSql = "SELECT id FROM fly_user WHERE id=?;";
            $vUserRefereeId = DBHelper::DataString($vSql, [$pShareKey]);
            if(!IsNull($vUserRefereeId)){ $vRefereeId = $vUserRefereeId; }
        }
        
        //--- Json提交区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "data_field" => "id",
            "where_field" => "phonenumber",
            "where_value" => "{$phonenumber}",
            "page" => "1",
            "limit" => "1",
        );
        //返回结果:分页
        $returnPaging = MIndexDataPaging(JsonHandleArray($jsonKeyValueArray));
        
        //--- 数据处理区 ---
        //判断是否获取成功
        if(!JudgeJsonValue($returnPaging, "result", "true")){
            return JsonInforFalse("获取失败", self::$tableName);
        }
        
        //判断用户是否存在
        if(!JudgeJsonValue($returnPaging, "count", "0")){
            return JsonInforFalse("用户已存在", $phonenumber);
        }
        
        //验证短信验证码
        $result = ObjFlyPhoneCode()->SystemPhoneCodeJudge($phonenumber, $phoneCode,self::$sendTypeUserRegister);
        if(!JudgeJsonValue($result, "result", "true")){
            return $result;
        }
        
        //识别手机号归属地
        $MobileHome = ObjThirdBaidu()->MobileHome($phonenumber);
        $prov = "none";
        $city = "none";
        $type = "none";
        if(JudgeJsonTrue($MobileHome)){
            $vJsonObj = GetJsonObject($MobileHome);
            $prov = $vJsonObj->prov;
            $city = $vJsonObj->city;
            $type = $vJsonObj->type;
        }
        //--- 注册用户 ---
        //--- Json组合区 ---
        //Json数组
        $vTableName = self::$tableName;
        $jsonKeyValueArray = array(
            "table_name" => $vTableName,
            "insert_field" => "phonenumber,password,phoneProvince,phoneCity,phoneType,refereeId,userNick",
            "phonenumber" => $phonenumber,
            "password" => $password,
            "phoneprovince" => $prov,
            "phonecity" => $city,
            "phonetype" => $type,
            "refereeid" => $vRefereeId,
            "usernick" => $pNeckname,
            "keyfield" => "phonenumber",
        );
        //返回结果:添加
        $returnInsert = MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        //判断是否请求成功
        if(JudgeJsonFalse($returnInsert)){ return $returnInsert; }
        
        //--- 注册成功后获取用户ID，并计算三级推荐关系 ---
        $vUserOnlyKey = GetJsonValue($returnInsert, "value");
        $vSql = "SELECT id FROM {$vTableName} WHERE onlyKey = '{$vUserOnlyKey}'";
        $vUserId = DBHelper::DataString($vSql, "", "id");
        ObjFlyUserReferee() -> SystemFlyUserRefereeAdd($vUserId,$pShareKey);
        
        //--- 给予上线（推荐人）抽奖机会 ---
        //获取:ota_luck_draw_set:抽奖名称
        //ObjOtaLuckDrawUserTimes() -> SystemOtaLuckDrawUserTimesReferee($vUserId);
        
        //--- 用户注册成功 ---
        return JsonModelInsert("true", $vUserOnlyKey, "注册成功", $vTableName);
        
    }    
    
    
//     /**
//      * 用户推荐：用户推荐下线明细
//      * 创建时间：2019-10-30 15:52:36
//      * */
//     public function FlyUserReferee($fpUserId){
        
//         //参数:page
//         $pPage = GetParameter("page");
//         if(IsNull($pPage)){ $pPage = 1; }
//         if(!JudgeRegularIntegerRight($pPage)){return JsonModelParameterException("page", $pPage, 20, "值必须是正整数", __LINE__);}
        
//         //参数:limit
//         $pLimit = GetParameter("limit");
//         if(IsNull($pLimit)){ $pLimit = 1; }
//         if(!JudgeRegularIntegerRight($pLimit)){return JsonModelParameterException("limit", $pLimit, 20, "值必须是正整数", __LINE__);}
        
//         //参数:referee
//         $pReferee = GetParameter("referee");
//         if(!($pReferee=="1"||$pReferee=="2"||$pReferee=="3")){return JsonModelParameterException("limit", $pLimit, 20, "值必须1、2、3", __LINE__);}
        
//         //处理页码
//         $pageInt = 1;
//         if(IsInt($pageInt)&&intval($pageInt)>0){ $pageInt = intval($pPage); }
//         //条数处理
//         $limitInt = 50;
//         if(IsInt($pLimit,true)&&(intval($pLimit))>0){
//             $limitInt = intval($pLimit);
//             if($limitInt>1000){ $limitInt = 1000; }
//         }
//         //条数字符串
//         $limitPageStr = " LIMIT " . ($pageInt-1)*$limitInt . "," . $limitInt;
        
//         $vSql = "";
//         $vCount = "";
//         if($pReferee=="1"){
//             $vSqlCount = "SELECT COUNT(TRUE) number FROM fly_user WHERE id IN (SELECT userId FROM fly_user_referee WHERE refereeOneId = '{$fpUserId}')";
//             $vCount = DBHelper::DataString($vSqlCount, "", "number");
//             $vSql = "SELECT id,ADDTIME,phonenumber,wecharOpenId,wechatAvatarUrl,wechatNickName FROM fly_user WHERE id IN (SELECT userId FROM fly_user_referee WHERE refereeOneId = '{$fpUserId}') {$limitPageStr}";
//         }else if($pReferee=="2"){
//             $vSqlCount = "SELECT COUNT(TRUE) number FROM fly_user WHERE id IN (SELECT userId FROM fly_user_referee WHERE refereeTwoId = '{$fpUserId}')";
//             $vCount = DBHelper::DataString($vSqlCount, "", "number");
//             $vSql = "SELECT id,ADDTIME,phonenumber,wecharOpenId,wechatAvatarUrl,wechatNickName FROM fly_user WHERE id IN (SELECT userId FROM fly_user_referee WHERE refereeTwoId = '{$fpUserId}') {$limitPageStr}";
//         }else if($pReferee=="3"){
//             $vSqlCount = "SELECT COUNT(TRUE) number FROM fly_user WHERE id IN (SELECT userId FROM fly_user_referee WHERE refereeThreeId = '{$fpUserId}')";
//             $vCount = DBHelper::DataString($vSqlCount, "", "number");
//             $vSql = "SELECT id,ADDTIME,phonenumber,wecharOpenId,wechatAvatarUrl,wechatNickName FROM fly_user WHERE id IN (SELECT userId FROM fly_user_referee WHERE refereeThreeId = '{$fpUserId}') {$limitPageStr}";
//         }
        
//         $dataList = DBHelper::DataList($vSql, "", array("id","ADDTIME","phonenumber","wecharOpenId","wechatAvatarUrl","wechatNickName"));
//         if(IsNull($dataList)){ $dataList = "[]"; }
//         return JsonModelDataString($dataList, $vCount);
        
//     }
    
    
//     /**
//      * 用户登陆:发送短信验证码
//      * 创建时间：2019-10-03 16:55:46
//      * */
//     public function FlyUserLoginSendPhoneCode(){
        
//         //--- 参数判断区 ---
//         $vParameterArray = array("line","method","phonenumber");
//         $bParameterJudge = JudgeParameterPermit($vParameterArray);
//         if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
//         //--- 变量声明区 ---
//         $json = "";		//参数Json数据
        
//         //--- 参数获取区 ---
//         //参数：phonenumber
//         $phonenumber = GetParameterNoCode("phonenumber",$json);
//         if(IsNull($phonenumber)){return JsonModelParameterNull("phonenumber");}
//         //参数：phonenumber 正则判断 手机号判断
//         if(!JudgeRegularPhone($phonenumber)){return JsonInforMsgFalse("参数不符合规则", "phonenumber");}
        
//         //--- Json提交区 ---
//         //Json数组
//         $jsonKeyValueArray = array(
//             "tablename" => self::$tableName,
//             "datafield" => "id,onlyKey,fatherId,typeName,groupName,userId,username,email,phonenumber,password,indexNumber,updateTime,addTime,state",
//             "wherefield" => "phonenumber",
//             "wherevalue" => "{$phonenumber}",
//             "page" => "1",
//             "limit" => "10",
//         );
//         //返回结果:分页
//         $returnPaging = MIndexDataPaging(JsonHandleArray($jsonKeyValueArray));
        
//         //--- 数据处理区 ---
//         //判断是否获取成功
//         if(!JudgeJsonValue($returnPaging, "result", "true")){
//             return JsonInforMsgFalse("获取失败", self::$tableName);
//         }
        
//         //结果为0时，判断用户是否存在
//         if(JudgeJsonValue($returnPaging, "count", "0")){
            
//             //----- 查询历史数据 -----
//             //使用历史数据注册用户
//             $vHistoryUser = ObjZmoveWechatUser() -> UserLogin($phonenumber);
//             //返回注册结果，结果不为true时返回用户不存在(继续走登录并注册流程)
//             //if(!JudgeJsonTrue($vHistoryUser)){
//             //    return JsonInforMsgFalse("用户不存在", $phonenumber);
//             //}
//             //----- 查询历史数据 -----
//         }
        
//         //创建短信对象
//         $phoneCode = GetIdNumber(4);    //短信验证码
//         $phoneBody = "【湖北文旅卡】尊敬的用户：您正在登录湖北文旅官方平台，验证码是{$phoneCode}，工作人员不会索取。请勿泄漏。";
//         $phoneModelId = "3396788";      //短信模板
//         $phoneEvent = "USER_LOGIN";  //短信事件
//         return ObjThirdYunPianWang() -> SendPhoneCode($phonenumber, $phoneEvent,$phoneCode,$phoneModelId,$phoneBody);
        
//     }
    
    
    
//     /**
//      * 用户登陆:历史用户注册
//      * 创建时间：2019-10-11 21:16:58
//      * */
//     public function FlyUserLoginHistory(){
        
//         //--- 参数获取区 ---
//         //参数：cardnumber
//         $cardNumber = GetParameterNoCode("cardnumber","");
//         if(IsNull($cardNumber)){return JsonModelParameterNull("cardnumber");}
        
//         //----- 查询历史数据进行注册 -----
//         //实体卡
//         $vSql = 'SELECT phone FROM zmove_entiy_card WHERE card_sn = "'.$cardNumber.'";';
//         $vPhone = DBHelper::DataString($vSql, "", "phone");
//         if(!IsNull($vPhone)){
//             $vResult = ObjRemoveService() -> UserRegisterCardBagAdd($cardNumber, "实体卡");
//             if(JudgeJsonTrue($vResult)){ return $vResult; }
//         }
        
//         //电子卡
//         $vSql = 'SELECT phone FROM zmove_online_card WHERE card_sn = "'.$cardNumber.'";';
//         $vPhone = DBHelper::DataString($vSql, "", "phone");
//         if(!IsNull($vPhone)){
//             $vResult = ObjRemoveService() -> UserRegisterCardBagAdd($cardNumber, "电子卡");
//             if(JudgeJsonTrue($vResult)){ return $vResult; }
//         }
        
//         return JsonInforMsgFalse("用户不存在", "历史数据查询");
        
//     }
    
    
//     /**
//      * 用户找回密码:发送短信验证码
//      * 创建时间：2019-07-22 11:20:00
//      * */
//     public function FlyUserLoginHistoryPhone(){
        
//         //--- 参数判断区 ---
//         $vParameterArray = array("line","method","phonenumber");
//         $bParameterJudge = JudgeParameterPermit($vParameterArray);
//         if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
//         //--- 变量声明区 ---
//         $json = "";		//参数Json数据
        
//         //--- 参数获取区 ---
//         //参数：phonenumber
//         $phonenumber = GetParameterNoCode("phonenumber",$json);
//         if(IsNull($phonenumber)){return JsonModelParameterNull("phonenumber");}
//         //参数：phonenumber 正则判断 手机号判断
//         if(!JudgeRegularPhone($phonenumber)){return JsonInforMsgFalse("参数不符合规则", "phonenumber");}
        
        
//         //是否有用户
//         $vUserFindBool = false;
        
//         //查询用户表
//         $vSql = "SELECT id FROM fly_user WHERE phonenumber = '{$phonenumber}';";
//         $vRecodeId = DBHelper::DataString($vSql, "", "id");
//         if(!IsNull($vRecodeId)){
//             $vUserFindBool = true;
//             return JsonInforMsgTrue("用户已存在:无需注册", $phonenumber);
//         }
        
//         //实体卡数据查询
//         if(!$vUserFindBool){
//             $vSql = "SELECT id FROM zmove_entiy_card WHERE phone = '{$phonenumber}';";
//             $vRecodeId = DBHelper::DataString($vSql, "", "id");
//             if(!IsNull($vRecodeId)){
//                 //添加用户
//                 $vSql = 'INSERT INTO fly_user(onlyKey,descript,phonenumber,userInsertType,userInforType,sourceUserType,sourceUserTime) VALUES ("'.GetId("R").'","用户数据","'.$phonenumber.'","找回密码历史用户注册","历史实体卡数据","湖北腾旅","'.GetTimeNow().'");';
//                 DBHelper::DataSubmit($vSql, "");
//                 $vUserFindBool = true;
//                 return JsonInforMsgTrue("用户注册成功:实体卡用户", $phonenumber);
//             }
//         }
        
//         //电子卡数据查询
//         if(!$vUserFindBool){
//             $vSql = "SELECT id FROM zmove_online_card WHERE phone = '{$phonenumber}';";
//             $vRecodeId = DBHelper::DataString($vSql, "", "id");
//             if(!IsNull($vRecodeId)){
//                 //添加用户
//                 $vSql = 'INSERT INTO fly_user(onlyKey,descript,phonenumber,userInsertType,userInforType,sourceUserType,sourceUserTime) VALUES ("'.GetId("R").'","用户数据","'.$phonenumber.'","找回密码历史用户注册","历史电子卡数据","湖北腾旅","'.GetTimeNow().'");';
//                 DBHelper::DataSubmit($vSql, "");
//                 $vUserFindBool = true;
//                 return JsonInforMsgTrue("用户注册成功:电子卡用户", $phonenumber);
//             }
//         }
        
//         //判断用户是否存在
//         if(!$vUserFindBool){
//             return JsonInforMsgFalse("用户不存在", $phonenumber);
//         }
        
//         return JsonInforMsgFalse("无效流程", "历史用户找回",__LINE__);
        
//     }
    
    
//     /**
//      * 用户登陆：手机号验证码登陆
//      * 创建时间：2019-07-22 11:20:00
//      * */
//     public function FlyUserLoginPhone(){
        
        
//         //--- 参数判断区 ---
//         $vParameterArray = array("tablename","updatefield","updatevalue","wherefield","wherevalue","sqldebug");
//         $bParameterJudge = JudgeParameterLimit($vParameterArray);
//         if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
//         //--- 变量声明区 ---
//         $json = "";		//参数Json数据
        
//         //--- 参数获取区 ---
//         //参数：phonenumber
//         $phonenumber = GetParameterNoCode("phonenumber",$json);
//         if(IsNull($phonenumber)){return JsonModelParameterNull("phonenumber");}
//         //参数：phonenumber 正则判断 手机号判断
//         if(!JudgeRegularPhone($phonenumber)){return JsonInforMsgFalse("参数不符合规则", "phonenumber");}
        
//         //参数：phonecode
//         $phoneCode = GetParameterNoCode("phonecode",$json);
//         if(IsNull($phoneCode)){return JsonModelParameterNull("phonecode");}
//         //参数：phonenumber 正则判断 手机号判断
//         if(!JudgeRegularCode($phoneCode,4)){return JsonInforMsgFalse("参数不符合规则", "phonecode");}
        
//         // 参数：注册来源 (wx_mini:微信小程序, wx_public:微信公众号)
//         $source = GetParameterNoCode('source', $json);
//         if (IsNull($source)) $source = 'wx_mini';
        
//         //参数：微信OpenId
//         $wechatOpenId = GetParameterNoCode("wecharopenid",$json);
//         if(IsNull($wechatOpenId)){return JsonModelParameterNull("wecharopenid");}
        
//         //参数：微信头像
//         $wechatAvatarUrl = GetParameterNoCode("wechatavatarurl",$json);
//         if(IsNull($wechatAvatarUrl)){return JsonModelParameterNull("wechatavatarurl");}
        
//         //参数：微信昵称
//         $wechatNickName = GetParameterNoCode("wechatnickname",$json);
//         if(IsNull($wechatNickName)){return JsonModelParameterNull("wechatnickname");}
        
//         // ---- 活动附加参数 start ---
//         /** 活动类型，活动名 */
//         $activityType = GetParameterNoCode('activity_type', $json);
//         /** 活动分享ID  */
//         $activityShareId = GetParameterNoCode('activity_share_id', $json);
//         // ---- 活动附加参数 end ---
        
//         //--- Json提交区 ---
//         //Json数组
//         $selectUserJsonKeyValueArray = array(
//             "tablename" => self::$tableName,
//             "datafield" => "id,onlyKey,fatherId,typeName,groupName,userId,username,email,phonenumber,password,indexNumber,updateTime,addTime,state",
//             "wherefield" => "phonenumber",
//             "wherevalue" => "{$phonenumber}",
//             "page" => "1",
//             "limit" => "10",
//         );
//         //返回结果:分页
//         $returnPaging = MIndexDataPaging(JsonHandleArray($selectUserJsonKeyValueArray));
        
//         //--- 数据处理区 ---
//         //判断是否获取成功
//         if(!JudgeJsonValue($returnPaging, "result", "true")){
//             return JsonInforMsgFalse("获取失败", self::$tableName);
//         }
        
//         //判断用户是否存在
//         $isNewUser = !JudgeJsonValue($returnPaging, "count", "1");
//         if ($isNewUser) {
//             //return JsonInforMsgFalse("用户不存在", $phonenumber);
//             // 不存在用户，走注册流程
//             try {
//                 $result = $this->FlyUserRegister('phone_login');
//                 if (!JudgeJsonValue($result, "result", "true")) return $result;
//             } catch (ClassFlyException $e) {
//                 return JsonInforMsgFalse('注册失败', $phonenumber);
//             }
//             // 重新查询用户
//             $returnPaging = MIndexDataPaging(JsonHandleArray($selectUserJsonKeyValueArray));
//             if (!JudgeJsonValue($returnPaging, "count", "1")) return JsonInforMsgFalse('注册失败', '');
//         } else {
//             // 存在用户，验证短信验证码
//             $phoneEvent = "USER_LOGIN";  // 短信事件
//             $result = ObjFlyClassPhoneCode()->SystemPhoneCodeJudge($phonenumber, $phoneCode, $phoneEvent);
//             if (!JudgeJsonValue($result, "result", "true")) return $result;
//         }
        
//         //--- Json组合区 ---
//         //登陆日志
//         //变量:角色名称、分类名称
//         $role = $this -> roleName;
//         //变量:组名称
//         $groupName = "手机号";
//         //登陆结果
//         $resultBo = true;
//         //用户ID
//         $vData = GetJsonValue($returnPaging, "data");
//         $userId = $vData[0] -> id;
        
//         //--- 修改用户数据 ---
//         //Json数组
//         $jsonKeyValueArray = array(
//             "tablename" => self::$tableName,
//             "updatefield" => "wechatAvatarUrl,wechatNickName",
//             "updatevalue" => "{$wechatAvatarUrl},{$wechatNickName}",
//             "wherefield" => "id",
//             "wherevalue" => "{$userId}",
//         );
//         if ($source == 'wx_mini') {
//             $jsonKeyValueArray['updatefield'] .= ',wecharOpenId';
//             $jsonKeyValueArray['updatevalue'] .= ",{$wechatOpenId}";
//         } else if ($source == 'wx_public') {
//             $jsonKeyValueArray['updatefield'] .= ',wechatPublicOpenId';
//             $jsonKeyValueArray['updatevalue'] .= ",{$wechatOpenId}";
//         } else {
//             return JsonInforMsgFalse('登录类型异常', '');
//         }
//         //返回结果:修改
//         $returnInsert = MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));
//         //--- 修改用户数据 ---
        
//         $result = ObjFlyRoleLoginLog() -> Login($role, $resultBo, $role, $groupName, $userId);
        
//         // --- 活动触发事件 ---
//         ObjOtaLuckDrawUserTimes()->SystemOtaLuckDrawUserTimesLogin($userId); // 给予每个用户一次抽奖次数
        
//         switch ($activityType) {
//             case 'ShareCollectCard': // 转发集字活动，新用户增加次数
//                 if ($isNewUser) ObjOtaActivityShareCollectCard()->addShareHistory($activityShareId, $userId);
//                 break;
//             default:
//                 break;
//         }
//         // --- 活动触发事件 ---
        
//         //登陆成功后写Session
//         if(JudgeJsonValue($result, "result", "true")){
//             $this -> SessionSet($userId);   //写用户ID到Session中
//             $token = RBACTokenGet($userId, "USER:{$userId}:{$phonenumber}", 60, APPSECRET,"登陆成功");
//             return $token;
//         }
        
//         //返回登陆日志处理结果
//         return $result;
        
//     }
    
    
//     /**
//      * 用户找回密码:发送短信验证码
//      * 创建时间：2019-07-22 11:20:00
//      * */
//     public function FlyUserFindPasswordSendPhoneCode(){
        
//         //--- 参数判断区 ---
//         $vParameterArray = array("line","method","phonenumber");
//         $bParameterJudge = JudgeParameterPermit($vParameterArray);
//         if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
//         //--- 变量声明区 ---
//         $json = "";		//参数Json数据
        
//         //--- 参数获取区 ---
//         //参数：phonenumber
//         $phonenumber = GetParameterNoCode("phonenumber",$json);
//         if(IsNull($phonenumber)){return JsonModelParameterNull("phonenumber");}
//         //参数：phonenumber 正则判断 手机号判断
//         if(!JudgeRegularPhone($phonenumber)){return JsonInforMsgFalse("参数不符合规则", "phonenumber");}
        
//         //是否有用户
//         $vUserFindBool = false;
        
//         //查询用户表
//         $vSql = "SELECT id FROM fly_user WHERE phonenumber = '{$phonenumber}';";
//         $vRecodeId = DBHelper::DataString($vSql, "", "id");
//         if(!IsNull($vRecodeId)){
//             $vUserFindBool = true;
//         }
        
//         //实体卡数据查询
//         if(!$vUserFindBool){
//             $vSql = "SELECT id FROM zmove_entiy_card WHERE phone = '{$phonenumber}';";
//             $vRecodeId = DBHelper::DataString($vSql, "", "id");
//             if(!IsNull($vRecodeId)){
//                 //添加用户
//                 $vSql = 'INSERT INTO fly_user(onlyKey,descript,phonenumber,userInsertType,userInforType,sourceUserType,sourceUserTime) VALUES ("'.GetId("R").'","用户数据","'.$phonenumber.'","找回密码历史用户注册","历史实体卡数据","湖北腾旅","'.GetTimeNow().'");';
//                 DBHelper::DataSubmit($vSql, "");
//                 $vUserFindBool = true;
//             }
//         }
        
//         //电子卡数据查询
//         if(!$vUserFindBool){
//             $vSql = "SELECT id FROM zmove_online_card WHERE phone = '{$phonenumber}';";
//             $vRecodeId = DBHelper::DataString($vSql, "", "id");
//             if(!IsNull($vRecodeId)){
//                 //添加用户
//                 $vSql = 'INSERT INTO fly_user(onlyKey,descript,phonenumber,userInsertType,userInforType,sourceUserType,sourceUserTime) VALUES ("'.GetId("R").'","用户数据","'.$phonenumber.'","找回密码历史用户注册","历史电子卡数据","湖北腾旅","'.GetTimeNow().'");';
//                 DBHelper::DataSubmit($vSql, "");
//                 $vUserFindBool = true;
//             }
//         }
        
//         //判断用户是否存在
//         if(!$vUserFindBool){
//             return JsonInforMsgFalse("用户不存在", $phonenumber);
//         }
        
//         //创建短信对象
//         $phoneCode = GetIdNumber(4);    //短信验证码
//         $phoneBody = "【湖北文旅卡】尊敬的用户：您正在修改密码，验证码是{$phoneCode}，工作人员不会索取，请勿泄漏。";
//         $phoneModelId = "3396802";      //短信模板
//         $phoneEvent = "USER_FIND_PASSWORD";  //短信事件
//         return ObjThirdYunPianWang() -> SendPhoneCode($phonenumber, $phoneEvent,$phoneCode,$phoneModelId,$phoneBody);
        
//     }
    
    
//     /**
//      * 用户注册：添加记录
//      * 创建时间：2019-07-22 11:20:00
//      * */
//     public function FlyUserFindPassword(){
        
        
//         //--- 参数判断区 ---
//         $vParameterArray = array("tablename","updatefield","updatevalue","wherefield","wherevalue","sqldebug");
//         $bParameterJudge = JudgeParameterLimit($vParameterArray);
//         if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
        
//         //--- 变量声明区 ---
//         $json = "";		//参数Json数据
        
//         //--- 参数获取区 ---
//         //参数：phonenumber
//         $phonenumber = GetParameterNoCode("phonenumber",$json);
//         if(IsNull($phonenumber)){return JsonModelParameterNull("phonenumber");}
//         //参数：phonenumber 正则判断 手机号判断
//         if(!JudgeRegularPhone($phonenumber)){return JsonInforMsgFalse("参数不符合规则", "phonenumber");}
        
//         //参数：phonecode
//         $phoneCode = GetParameterNoCode("phonecode",$json);
//         if(IsNull($phoneCode)){return JsonModelParameterNull("phonecode");}
//         //参数：phonenumber 正则判断 手机号判断
//         if(!JudgeRegularCode($phoneCode,4)){return JsonInforMsgFalse("参数不符合规则", "phonecode");}
        
//         //参数：password
//         $password = GetParameterNoCode("password",$json);
//         if(IsNull($password)){return JsonModelParameterNull("password");}
//         //参数：password 正则判断 密码判断
//         if(!JudgeRegularPassword($password)){return JsonInforMsgFalse("参数不符合规则", "password");}
        
//         //参数：微信OpenId
//         $wecharOpenId = GetParameterNoCode("wecharopenid",$json);
        
//         //参数：微信头像
//         $wechatAvatarUrl = GetParameterNoCode("wechatavatarurl",$json);
        
//         //参数：微信昵称
//         $wechatNickName = GetParameterNoCode("wechatnickname",$json);
        
//         //--- Json提交区 ---
//         //Json数组
//         $jsonKeyValueArray = array(
//             "tablename" => self::$tableName,
//             "datafield" => "id,onlyKey,fatherId,typeName,groupName,userId,username,email,phonenumber,password,indexNumber,updateTime,addTime,state",
//             "wherefield" => "phonenumber",
//             "wherevalue" => "{$phonenumber}",
//             "page" => "1",
//             "limit" => "10",
//         );
//         //返回结果:分页
//         $returnPaging = MIndexDataPaging(JsonHandleArray($jsonKeyValueArray));
        
//         //--- 数据处理区 ---
//         //判断是否获取成功
//         if(!JudgeJsonValue($returnPaging, "result", "true")){
//             return JsonInforMsgFalse("获取失败", self::$tableName);
//         }
        
//         //判断用户是否存在
//         if(JudgeJsonValue($returnPaging, "count", "0")){
//             return JsonInforMsgFalse("用户不存在", $phonenumber);
//         }
        
//         //验证短信验证码
//         $phoneEvent = "USER_FIND_PASSWORD";  //短信事件
//         $result = ObjFlyClassPhoneCode() -> SystemPhoneCodeJudge($phonenumber, $phoneCode, $phoneEvent);
//         if(!JudgeJsonValue($result, "result", "true")){
//             return $result;
//         }
        
//         //--- 注册用户 ---
//         //处理密码
//         $password = HandleStringMD5($password);
        
//         //--- Json组合区 ---
//         //Json数组
//         $jsonKeyValueArray = array(
//             "tablename" => self::$tableName,
//             "updatefield" => "password",
//             "updatevalue" => "{$password}",
//             "wherefield" => "phonenumber",
//             "wherevalue" => "{$phonenumber}",
//         );
//         //添加修改值
//         $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "wecharOpenId", $wecharOpenId);
//         $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "wechatAvatarUrl", $wechatAvatarUrl);
//         $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "wechatNickName", $wechatNickName);
//         //返回结果:修改
//         $returnInsert = MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));
//         //判断是否请求成功
//         if(!JudgeJsonValue($returnInsert, "result", "true")){
//             return JsonInforMsgFalse("修改失败", self::$tableName);
//         }
//         //用户密码修改成功
//         return JsonModelInsert("true", GetJsonValue($returnInsert, "infor"), "修改成功", self::$tableName);
        
//     }
    
    
//     /**
//      * 获取用户ID推荐人
//      * 创建时间：2019-12-15 16:03:44
//      * */
//     public function FlyUserRefereeId($fpUserId){
//         //获取:fly_user:用户推荐人
//         $vSql = "SELECT refereeId FROM fly_user WHERE id=?;";
//         $vRefereeId = DBHelper::PdoDataString($vSql, [$fpUserId]);
//         if(JudgeRegularIntegerRight($vRefereeId)){
//             return $vRefereeId;
//         }
//         return "";
//     }
    
    
         
    
}