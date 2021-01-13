<?php

/**------------------------------------*
 * 名称：网站管理员类
 * 说明：网站管理员相关功能
 * 创建时间：December 1,2018 09:53
 * ------------------------------------*/

//--- 引用区 ---
include_once $_SERVER['DOCUMENT_ROOT'] . "/server/flyclass/system/log/FlyClassRoleLoginLog.php"; //登陆日志类
    
class RoleAdmin{
    
    //------------ 类成员区 ------------
    private $roleName = "网站管理员";                    //角色名称
    public static $classDescript = "网站管理员";         //类描述
    public static $tableName = "fly_user_admin";       //表名称    
    
    //---------- 基础方法 ----------
    
    /**
     * 获取数据表名称
     * 创建时间：2019-06-03 17:28:49
     * */
    public function GetTableName(){
        return self::$tableName;
    }
    
    /**
     * 获取类描述
     * 创建时间：2019-06-03 17:28:49
     * */
    public function GetClassDescript(){
        return self::$classDescript;
    }    
    
    
    //------------ 超级管理员方法区 ------------
    
    /**
     * 记录添加
     * 创建时间：2019-08-30 11:00:25
     * */
    public function AdminSuperFlyUserAdminAdd(){
    
        //--- 参数判断区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
    
        //--- 变量预定义 ---
        $json="";
    
        //--- 参数获取区 ---
    
        //参数:管理员手机号:phoneNumber
        $pPhoneNumber = GetParameter("phone_number",$json);
        if(IsNull($pPhoneNumber)||GetStringLength($pPhoneNumber)>36||!JudgeRegularPhone($pPhoneNumber)){return JsonModelParameterException("phone_number", $pPhoneNumber, 36, "值不符合规则", __LINE__);}
    
        //参数:密码:password
        $pPassword = GetParameter("password",$json);
        if(IsNull($pPassword)||GetStringLength($pPassword)>64||!JudgeRegularLetterNumber($pPassword)){return JsonModelParameterException("password", $pPassword, 64, "值不符合规则", __LINE__);}
        $pPassword = HandleStringMD5($pPassword);
    
        //参数:管理员姓名:adminName
        $pAdminName = GetParameter("admin_name",$json);
        if(!JudgeRegularFont($pAdminName)){return JsonModelParameterException("admin_name", $pAdminName, 36, "值不符合规则", __LINE__);}
    
        //参数:管理员身份证号:adminIdCard
        $pAdminIdCard = GetParameter("admin_idcard",$json);
        if(IsNull($pAdminIdCard)||GetStringLength($pAdminIdCard)>36||!JudgeRegularLetterNumber($pAdminIdCard)){return JsonModelParameterException("admin_idcard", $pAdminIdCard, 36, "值不符合规则", __LINE__);}
    
        //参数:管理员身份证照片:adminIdCardPhoto
        $pAdminIdCardPhoto = GetParameter("admin_idcard_photo",$json);
        if(!JudgeRegularUrl($pAdminIdCardPhoto)){return JsonModelParameterException("admin_idcard_photo", $pAdminIdCardPhoto, 128, "值不符合规则", __LINE__);}
    
        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "phoneNumber,password,adminName,adminIdCard,adminIdCardPhoto",
            "phoneNumber" => $pPhoneNumber,
            "password" => $pPassword,
            "adminname" => $pAdminName,
            "adminidcard" => $pAdminIdCard,
            "adminidcardphoto" => $pAdminIdCardPhoto,
            "key_field" => "phoneNumber",
        );
        //执行:添加
        return MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
    }
    
    
    //------------ 管理员方法区 ------------
    
    /**
     *
     * 记录查询
     * 创建时间：2019-08-30 11:37:04
     * */
    public function AdminFlyUserAdminInfor($fpAdminId){
    
        //--- 数据预定义 ---
        $json = "";
    
        //--- Json组合区 ---
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "data_field" => "id,onlyKey,addTime,phoneNumber,department,position,loginTimes,loginTime,loginIp,loginMac,adminName,adminSex,adminIdCard,adminIdCardPhoto,adminCheck,isRoleDeveloper,isRoleCreater",
            "where_field" => "id",
            "where_value" => "{$fpAdminId}",
            "page" => "1",
            "limit" => "1",
        );
        //返回结果:分页
        return MIndexDataPaging(JsonHandleArray($jsonKeyValueArray));
    }
    
    /**
     * 函数名称：管理员:管理员:记录修改
     * 函数调用：ObjFlyUserAdmin() -> AdminFlyUserAdminSet($fpAdminId)
     * 创建时间：2020-10-09 18:26:28
     * */
    public function AdminFlyUserAdminSet($fpAdminId){
        
        //判断管理员是否是创建者
        if(!(ObjRoleObjectAdmin() -> JudgeAdminIsCreater($fpAdminId))){
            return JsonInforFalse("权限不足", "程序创建者");
        }
        
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
        
        //参数:管理员手机号:phoneNumber
        $pPhoneNumber = GetParameterNoCode("phonenumber",$json);
        if(!IsNull($pPhoneNumber)&&!JudgeRegularPhone($pPhoneNumber)){return JsonModelParameterException("phonenumber", $pPhoneNumber, 36, "手机号格式错误", __LINE__);}
        
        //参数:密码:password
        $pPassword = GetParameterNoCode("password",$json);
        if(!IsNull($pPassword)&&!JudgeRegularFont($pPassword)){return JsonModelParameterException("password", $pPassword, 64, "内容格式错误", __LINE__);}
        
        //参数:部门:department
        $pDepartment = GetParameterNoCode("department",$json);
        if(!IsNull($pDepartment)&&!JudgeRegularFont($pDepartment)){return JsonModelParameterException("department", $pDepartment, 64, "内容格式错误", __LINE__);}
        
        //参数:部门职位:position
        $pPosition = GetParameterNoCode("position",$json);
        if(!IsNull($pPosition)&&!JudgeRegularFont($pPosition)){return JsonModelParameterException("position", $pPosition, 64, "内容格式错误", __LINE__);}
        
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
        
        //参数:管理员姓名:adminName
        $pAdminName = GetParameterNoCode("adminname",$json);
        if(!IsNull($pAdminName)&&!JudgeRegularFont($pAdminName)){return JsonModelParameterException("adminname", $pAdminName, 36, "内容格式错误", __LINE__);}
        
        //参数:管理员性别:adminSex
        $pAdminSex = GetParameterNoCode("adminsex",$json);
        if(!IsNull($pAdminSex)&&!JudgeRegularFont($pAdminSex)){return JsonModelParameterException("adminsex", $pAdminSex, 36, "内容格式错误", __LINE__);}
        
        //参数:管理员身份证号:adminIdCard
        $pAdminIdCard = GetParameterNoCode("adminidcard",$json);
        if(!IsNull($pAdminIdCard)&&!JudgeRegularLetterNumber($pAdminIdCard)){return JsonModelParameterException("adminidcard", $pAdminIdCard, 36, "手机号格式错误", __LINE__);}
        
        //参数:管理员身份证照片:adminIdCardPhoto
        $pAdminIdCardPhoto = GetParameterNoCode("adminidcardphoto",$json);
        if(!IsNull($pAdminIdCardPhoto)&&!JudgeRegularUrl($pAdminIdCardPhoto)){return JsonModelParameterException("adminidcardphoto", $pAdminIdCardPhoto, 128, "URL地址格式错误", __LINE__);}
        
        //参数:管理员审核:adminCheck
        $pAdminCheck = GetParameterNoCode("admincheck",$json);
        if(!IsNull($pAdminCheck)&&!JudgeRegularFont($pAdminCheck)){return JsonModelParameterException("admincheck", $pAdminCheck, 36, "内容格式错误", __LINE__);}
        
        //参数:是否为测试者:isRoleTester
        $pIsRoleTester = GetParameterNoCode("isroletester",$json);
        if(!IsNull($pIsRoleTester)&&!JudgeRegularFont($pIsRoleTester)){return JsonModelParameterException("isroletester", $pIsRoleTester, 36, "内容格式错误", __LINE__);}
        
        //参数:是否为开发程序员:isRoleDeveloper
        $pIsRoleDeveloper = GetParameterNoCode("isroledeveloper",$json);
        if(!IsNull($pIsRoleDeveloper)&&!JudgeRegularFont($pIsRoleDeveloper)){return JsonModelParameterException("isroledeveloper", $pIsRoleDeveloper, 36, "内容格式错误", __LINE__);}
        
        //参数:是否为程序创建者:isRoleCreater
        $pIsRoleCreater = GetParameterNoCode("isrolecreater",$json);
        if(!IsNull($pIsRoleCreater)&&!JudgeRegularFont($pIsRoleCreater)){return JsonModelParameterException("isrolecreater", $pIsRoleCreater, 36, "内容格式错误", __LINE__);}
        
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
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "phoneNumber", $pPhoneNumber);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "password", $pPassword);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "department", $pDepartment);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "position", $pPosition);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "loginTimes", $pLoginTimes);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "loginTime", $pLoginTime);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "loginIp", $pLoginIp);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "loginMac", $pLoginMac);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "adminName", $pAdminName);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "adminSex", $pAdminSex);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "adminIdCard", $pAdminIdCard);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "adminIdCardPhoto", $pAdminIdCardPhoto);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "adminCheck", $pAdminCheck);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "isRoleTester", $pIsRoleTester);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "isRoleDeveloper", $pIsRoleDeveloper);
        $jsonKeyValueArray = HandleFlyJsonAddUpdateField($jsonKeyValueArray, "isRoleCreater", $pIsRoleCreater);
        
        //判断字段值是否为空
        $vUpdateFieldJudgeInfor = JudgeFlyJsonAddUpdateField($jsonKeyValueArray,"descript,indexnumber,shelfstate,state,phonenumber,password,department,position,logintimes,logintime,loginip,loginmac,adminname,adminsex,adminidcard,adminidcardphoto,admincheck,isroletester,isroledeveloper,isrolecreater");
        if(JudgeJsonFalse($vUpdateFieldJudgeInfor)){ return $vUpdateFieldJudgeInfor; }
        
        //执行:修改
        $vJsonResult = MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));
        if(JudgeJsonTrue($vJsonResult)){
            ObjFlyUserAdminOperationLog() -> SystemFlyUserAdminOperationLogAdd($fpAdminId, __CLASS__, __FUNCTION__, "管理员修改记录", $pId);
        }
        return $vJsonResult;
        
    }
    
    /**
     * 管理员密码修改
     * 创建时间：2019-08-30 11:00:25
     * */
    public function AdminFlyUserAdminPasswordSet($fpAdminId){
    
        //--- 参数判断区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
    
        //--- 变量预定义 ---
        $json="";
    
        //--- 参数获取区 ---
    
        //参数:密码:password
        $pPassword = GetParameter("password",$json);
        if(!JudgeRegularLetterNumber($pPassword)){return JsonModelParameterException("password", $pPassword, 64, "值不符合规则", __LINE__);}
        $pPassword = HandleStringMD5($pPassword);
    
        //参数:密码:password
        $pNewPassword = GetParameter("new_password",$json);
        if(!JudgeRegularLetterNumber($pNewPassword)){return JsonModelParameterException("new_password", $pNewPassword, 64, "值不符合规则", __LINE__);}
        $pNewPassword = HandleStringMD5($pNewPassword);
        
        //判断两次密码相同
        if($pPassword==$pNewPassword){
            return JsonInforFalse("修改前的密码和修改后的密码不得相同", "网站管理员密码修改");
        }
    
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "update_field" => "password",
            "update_value" => $pNewPassword,
            "where_field" => "id,password",
            "where_value" => "{$fpAdminId},{$pPassword}",
        );
        //执行:添加
        return MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));
    }
    
    
    /**
     * 函数名称：管理员:管理员:记录查询
     * 函数调用：ObjFlyUserAdmin() -> AdminFlyUserAdminPaging($fpAdminId)
     * 创建时间：2020-02-19 22:55:54
     * */
    public function AdminFlyUserAdminPaging($fpAdminId){
    
        //--- 参数限制区 ---
        $vParameterArray = GetArray(FLY_PARAMETER_LIMIT, ",");
        $bParameterJudge = JudgeParameterLimit($vParameterArray);
        if(JudgeJsonFalse($bParameterJudge)){return $bParameterJudge;}
    
        //--- 数据预定义 ---
        $json = "";
    
        //--- 参数获取区 ---
        $pPage = GetParameterNoCode("page","");     //参数:页码:page
        $pLimit = GetParameterNoCode("limit","");   //参数:条数:limit
        
        //参数:角色名:role
        $pRole = GetParameterNoCode("role","");
        if(!IsNull($pRole)&&!JudgeRegularLetter($pRole)){return JsonModelParameterException("role", $pRole, 20, "值必须是字母", __LINE__);}
        if(!IsNull($pRole)){
            if(!($pRole=="isRoleTester"||$pRole=="isRoleDeveloper"||$pRole=="isRoleCreater")){
                $pRole = "";
            }
        }
    
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
        //$vDataField = "id,onlyKey,descript,indexNumber,updateTime,addTime,shelfState,state,phoneNumber,password,department,position,loginTimes,loginTime,loginIp,loginMac,adminName,adminSex,adminIdCard,adminIdCardPhoto,adminCheck,isRoleDeveloper,isRoleCreater";
    
        //--- Json组合区 ---
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "data_field" => $vDataField,
            "where_field" => "",
            "where_value" => "",
            "page" => $pPage,
            "limit" => $pLimit,
            "like_field" => $pLikeField,
            "like_key" => $pLikeKey,
        );
    
        //条件字段判断组合
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "id", $pId);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "state", $pWhereState);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, "shelfState", $pWhereShelfState);
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, $pRole, "true");
        $jsonKeyValueArray = HandleFlyJsonAddWhereField($jsonKeyValueArray, $pStateField, $pStateKey);
        //返回结果:分页
        return MIndexDataPaging(JsonHandleArray($jsonKeyValueArray));
    
    }
    
    
    /**
     * 函数名称：获取数据表字段
     * 函数调用：ObjFlyUserAdmin() -> GetTableField()
     * 创建时间：2020-02-19 22:55:54
     * */
    public function GetTableField(){
        return MIndexDBTableFields(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }
    
    
    //------------ 私有方法区 ------------
    
    /**
     * 设置Session
     * 创建时间：December 1,2018 10:11
     * 说明：设置网站管理员的ID值到Session中
     * 检测：逻辑
     * 检测时间：创建时间：December 1,2018 10:11
     * */
    private function SessionSet($recordId,$fpRoleDeveloper="false",$fpRoleCreater="false"){
        SetSessionKey("admin",$recordId);
        SetSessionKey("adminLoginType","admin");
        SetSessionKey("adminTableName",self::$tableName);
        SetSessionKey("adminRoleDeveloper",$fpRoleDeveloper);
        SetSessionKey("adminRoleCreater",$fpRoleCreater);
    }
    
    /**
     * 获取Session
     * 创建时间：December 1,2018 10:13
     * 说明：获取Session中网站管理员ID
     * 检测：逻辑
     * 检测时间：创建时间：December 1,2018 10:13
     * */
    private function SessionGet(){
        return RBACSessionGetValue('admin');
    }
    

    /**
     * 获取Session登陆类型
     * 创建时间：2019-05-23 00:54
     * 说明：获取Session中用户登陆类型
     * 检测：逻辑
     * 检测时间：2019-05-23 00:54
     * */
    private function SessionGetLoginType(){
        return RBACSessionGetValue('adminLoginType');
    }
    
    /**
     * 获取Session登陆数据表名称
     * 创建时间：2019-05-23 02:25
     * 说明：获取Session中用户登陆类型
     * 检测：逻辑
     * 检测时间：2019-05-23 00:54
     * */
    private function SessionGetTableName(){
        return RBACSessionGetValue('adminTableName');
    }
    
    /**
     * 清除Session
     * 创建时间：December 1,2018 10:14
     * 说明：清除Session中网站管理员ID
     * 检测：逻辑
     * 检测时间：创建时间：December 1,2018 10:14
     * */
    private function SessionClear(){
        SetSessionKey("admin","");
        SetSessionKey("adminLoginType","");
        SetSessionKey("adminTableName","");
        SetSessionKey("adminRoleDeveloper","");
        SetSessionKey("adminRoleCreater","");
    }
   
    
    /** 登陆信息修改 */
    private function AdminLoginInforSet($fpAdminId){
        $vIp = GetPathRemoteAddr();
        $vNowTime = GetTimeNow();
        //修改:fly_user_admin:登陆次数、登陆时间、登陆IP
        $vSql = "UPDATE fly_user_admin SET loginTimes=loginTimes+1,loginTime=?,loginIp=? WHERE id=?;";
        return DBHelper::DataSubmit($vSql, [$vNowTime,$vIp,$fpAdminId]);
    }
    
    //------------ 公开方法区 ------------

    
    /**
     * 判断Session
     * 创建时间：December 1,2018 10:14
     * 说明：判断Session中网站管理员ID
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
     * 网站管理员登陆
     * 创建时间：December 1,2018 09:59
     * 说明：网站管理员相关的操作必须在网站管理员登陆之后才可以进行进行
     * 检测：
     * 检测时间：
     * */
    public function Login(){
    
        //--- 对象创建区 ---
        //--- 变量区声明区 --
        //变量:网站管理员数据表名称
        $userAdminTableName = self::$tableName;
        
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
        $phoneNumber = GetParameter('phonenumber', "");
        if(IsNull($phoneNumber)){return JsonModelParameterNull("phonenumber");}
        //校验:密码
        $password = GetParameter('password', "");
        if(IsNull($password)){return JsonModelParameterNull("password");}
        $password = HandleStringMD5($password);
    
        //用户是否存在判断
        $jsonKeyValueArray = array(
            "table_name" => $userAdminTableName,
            "data_field" => "id",
            "where_field" => "phoneNumber",
            "where_value" => $phoneNumber,
            "page" => "1",
            "limit" => "1",
        );
        $json = MIndexDataPaging(JsonHandleArray($jsonKeyValueArray));
        //判断数据结果为false或为0
        if(JudgeJsonFalse($json)){ return $json; }
        if(JudgeJsonCountZero($json)){ return JsonInforFalse("管理员不存在", $role, FlyCode::$Code_Select_Success_Null); }
        
        //获取用户ID
        $dataArray = GetJsonValue($json,"data");
        $userId = $dataArray[0] -> id;
        
        //用户账号和密码是否正确
        $jsonKeyValueArray = array(
            "table_name" => $userAdminTableName,
            "data_field" => "id,isRoleDeveloper,isRoleCreater",
            "where_field" => "phoneNumber,password",
            "where_value" => "{$phoneNumber},{$password}",
            "page" => "1",
            "limit" => "1",
        );
        $json = MIndexDataPaging(JsonHandleArray($jsonKeyValueArray));
        
        //判断数据结果为false或为0，赋值返回结果
        $resultBo = true;
        if(JudgeJsonCountZero($json)){
            $resultBo = false;
        }
        
        //登陆成功
        $vIsRoleDeveloper = "false";
        $vIsRoleCreater = "false";
        if($resultBo){
            $vAdminData = GetJsonValue($json, "data");
            $vAdminDataMember = $vAdminData[0]; 
            $vIsRoleDeveloper = $vAdminDataMember-> isRoleDeveloper;
            $vIsRoleCreater = $vAdminDataMember-> isRoleCreater;
        }
        
        
        //登陆日志
        $result = ObjFlyRoleLoginLog() -> Login($role, $resultBo, $role, $groupName, $userId, $phoneNumber, $userAdminTableName);
        
        //登陆成功后写Session
        if(JudgeJsonValue($result, "result", "true")){
            $this -> SessionSet($userId,$vIsRoleDeveloper,$vIsRoleCreater);   //写用户ID到Session中
            self::AdminLoginInforSet($userId);
            OpreationLog("ADMIN", $userId, "管理员登陆", __FILE__, __FUNCTION__, __LINE__, "true", "登陆成功");
        }else{
            OpreationLog("ADMIN", $userId, "管理员登陆", __FILE__, __FUNCTION__, __LINE__, "false", GetJsonValue($result, "infor"));
        }
        
        //返回登陆日志处理结果
        return $result;
    }
    

    /**
     * 网站管理员登陆
     * 创建时间：December 1,2018 09:59
     * 说明：网站管理员相关的操作必须在网站管理员登陆之后才可以进行进行
     * 检测：
     * 检测时间：
     * */
    public function Register($fpAdminId){
        
        //校验是否是程序创建者
        if(!self::JudgeAdminIsCreater($fpAdminId)){return JsonInforFalse("权限不足，无法注册管理员！", "管理员权限校验");}
        
        //--- 参数获取区 ---
        //校验:手机号
        $phoneNumber = GetParameter('phonenumber', "");
        if(IsNull($phoneNumber)){return JsonModelParameterNull("phonenumber");}
        //校验:密码
        $password = GetParameter('password', "");
        if(IsNull($password)){return JsonModelParameterNull("password");}
        $password = HandleStringMD5($password);
        //校验:姓名
        $pAdminName = GetParameter('admin_name', "");
        $pAdminName = HandleStringNone($pAdminName);
        //校验:性别
        $pAdminSex = GetParameter('admin_sex', "");
        $pAdminSex = HandleStringNone($pAdminSex);
        
        $tableName = self::$tableName;
        
        $sql = "SELECT id FROM {$tableName} WHERE phoneNumber = '{$phoneNumber}';";
        $vAdminJson = DBHelper::DataStringJson($sql, null, "id");
        if(JudgeJsonEcode($vAdminJson,FlyCode::$Code_Select_Success_Have)){
            return JsonInforFalse("账号已存在", "管理员注册");
        }
        
        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => $tableName,
            "insert_field" => "descript,phoneNumber,password,adminName,adminSex",
            "key_field" => "phoneNumber",
            "descript" => self::$classDescript,
            "phoneNumber" => $phoneNumber,
            "password" => $password,
            "adminName" => $pAdminName,
            "adminSex" => $pAdminSex,
        );
        //返回结果
        return MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
        
    }
    
    
    
    /**
     * 创建者添加管理员
     * 创建时间：2020-07-20 20:09:06
     * */
    public function CreaterRegisterAdmin($fpAdminId){
        
        //判断管理员权限是否为程序创建者
        if(!(ObjRoleObjectAdmin() -> JudgeAdminIsCreater($fpAdminId))){
            return JsonInforFalse("权限不足", "管理员删除");
        }
        
        //注册用户
        $vPhonenumber = GetParameter('phonenumber', "");
        if(!JudgeRegularPhone($vPhonenumber)){return JsonInforFalse("手机号格式异常", "phonenumber");}
        
        $vPassword = GetParameter('password', "");
        if(!JudgeRegularPassword($vPassword)){return JsonInforFalse("密码格式异常", "password");}
        $vPassword = HandleStringMD5($vPassword);
        
        $vTableName = "fly_user_admin";
        
        $vSql = "SELECT id FROM {$vTableName} WHERE phoneNumber = '{$vPhonenumber}'";
        if(DBHelper::DataBoolean($vSql, null)){
            return JsonInforFalse("管理员已存在", $vTableName);
        }
        //添加用户
        $vSql = "INSERT INTO {$vTableName}(onlyKey,descript,phoneNumber,password) VALUES ('".GetId("R")."','".self::$classDescript."','{$vPhonenumber}','{$vPassword}');";
        if(DBHelper::DataSubmit($vSql, "")){
            return JsonInforTrue("管理员添加成功", $vTableName, $vPhonenumber);
        };
        return JsonInforFalse("管理员添加失败", $vTableName);
    }
    
    /**
     * 创建程序创建者
     * 创建时间：2020-03-23 21:09:10
     * */
    public function RegisterCreater(){
        
        //输出接口代码
        if(GetParameterRequest("interfacecode")=="true"){return WriteFunctionCode(__FILE__,__FUNCTION__);};
        
        //判断程序创建者是否存在
        $vAdminList = ObjRoleObjectAdmin() -> JudgeAdminCreater();
        if(JudgeJsonTrue($vAdminList)){ return JsonInforFalse("已存在程序创建者", "fly_user_admin"); }
    
        //--- 参数获取区 ---
        //手机号
        $phoneNumber = GetParameter('phonenumber', "");
        if(IsNull($phoneNumber)){return JsonModelParameterNull("phonenumber");}
        //密码
        $password = GetParameter('password', "");
        if(IsNull($password)){return JsonModelParameterNull("password");}
        $password = HandleStringMD5($password);
        //姓名
        $pAdminName = GetParameter('admin_name', "");
        $pAdminName = HandleStringNone($pAdminName);
        //性别
        $pAdminSex = GetParameter('admin_sex', "");
        $pAdminSex = HandleStringNone($pAdminSex);
        //系统密码
        $vSystemPasswordCheck = ObjSystem() -> CheckSystemPassword();
        if(JudgeJsonFalse($vSystemPasswordCheck)){return WriteEcho($vSystemPasswordCheck);}
    
        //判断管理员数据表是否存在
        if(!DBMySQLJudge::JudgeTable("fly_user_admin")){
            return JsonInforFalse("管理员数据表不存在，请创建。", "fly_user_admin");
        }
        
        //检测管理员数据表是否存在
        if(!DBMySQLJudge::JudgeTable(self::$tableName)){
            return JsonInforFalse("管理员数据表不存在", self::$tableName);
        }
        
        //--- Json组合区 ---
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "insert_field" => "descript,phoneNumber,password,adminName,adminSex,isRoleCreater",
            "key_field" => "phoneNumber",
            "descript" => self::$classDescript,
            "phoneNumber" => $phoneNumber,
            "password" => $password,
            "adminName" => $pAdminName,
            "adminSex" => $pAdminSex,
            "isRoleCreater" => "true",
        );
        //返回结果
        return MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
    
    }
    
    /**
     * 获取超级管理员Session存储的用户ID
     * 创建时间：2019-05-25 01:50:00
     * */
    public function SessionUserId(){
        return $this -> SessionGet();
    }    
    
    /**
     * 获取网站管理员Session存储结果
     * 创建时间：December 7,2018 17:18
     * 说明：获取网站管理员Session，如果存在返回true，否则返回false
     * 检测：对象、逻辑
     * 检测时间：Decemeber 7,2018 17:18
     * */
    public function SessionResult(){
        return $this -> SessionJudge();
    }
    
    /**
     * 获取网站管理员Session存储结果
     * 创建时间：2019-05-24 23:07:00
     * */
    public function SessionLoginType(){
        return $this -> SessionGetLoginType();
    }    
    
    /**
     * 获取网站管理员Session存储结果
     * 创建时间：2019-05-24 23:07:00
     * */
    public function SessionTableName(){
        return $this -> SessionGetTableName();
    }
    

    /**
     * 获取网站管理员Session存储结果
     * 创建时间：December 1,2018 15:05
     * 说明：获取网站管理员Session，如果存在返回true，否则返回false，以Json方式进行返回
     * 检测：对象、逻辑
     * 检测时间：Decemeber 1,2018 15:16
     * */
    public function SessionResultJson(){
        
        //--- 变量区 ---
        //角色
        $role = $this -> roleName;
        
        //--- 逻辑区 ---
        //Session判断
        if($this -> SessionJudge()){
            return JsonInforTrue("已登陆",$role);
        }
        return JsonInforFalse("未登陆",$role);
    }

   
    /**
     * 清除网站管理员的登陆Session
     * 创建时间：December 1,2018 15:17
     * 说明：清除网站管理员存储在Session中的值，以Json的方式进行返回
     * 检测：对象、逻辑
     * 检测时间：Decemeber 1,2018 15:26
     * */
    public function SessionClearJson(){
        
        //--- 变量区 ---
        //角色
        $role = $this -> roleName;
        
        //--- 逻辑区 ---
        //Session判断
        if(!($this -> SessionJudge())){
            return JsonInforFalse("未登陆",$role);
        }
        
        OpreationLog("ADMIN", $this->SessionUserId(), "管理员退出", __FILE__, __FUNCTION__, __LINE__, "true", "退出成功");
        $this -> SessionClear();
        return JsonInforTrue("退出成功",$role);
    }
    

    /**
     * 获取程序开发者身份标识
     * 2020-02-24 19:17:00
     * */
    public function SessionGetDeveloper(){
        return RBACSessionGetValue('adminRoleDeveloper');
    }
    
    /**
     * 获取程序创建者身份标识
     * 2020-02-24 19:17:12
     * */
    public function SessionGetCreater(){
        return RBACSessionGetValue('adminRoleCreater');
    }
    
    /**
     * 网站管理员未登陆
     * 创建时间：December 9,2018 21:05
     * 说明：网站管理员未登陆
     * 检测：逻辑
     * 检测时间：December 9,2018 21:05
     * */
    public function LoginNot(){
        return JsonInforFalse("未登陆", "网站管理员");
    }
    
    
    /**
     * 函数名称：删除管理员
     * 函数调用：ObjFlyUser() -> AdminFlyUserDelete($fpAdminId)
     * 创建时间：2020-03-19 16:50:36
     * */
    public function AdminDelete($fpAdminId){
        
        if(!(ObjRoleObjectAdmin() -> JudgeAdminIsCreater($fpAdminId))){
            return JsonInforFalse("权限不足", "管理员删除");
        }
        
        //--- 变量预定义 ---
        $json="";		//Json参数
        
        //--- 参数获取区 ---
        //参数：id
        $pId = GetParameterNoCode("id",$json);
        if(!JudgeRegularIntRight($pId)){return JsonModelParameterInfor("id","参数值不符合规则",20);}
        
        $sql = "SELECT isRoleCreater FROM fly_user_admin WHERE id='{$pId}'";
        $creater = DBHelper::DataString($sql, null);
        if($creater=="true"){
            return JsonInforFalse("无法删除程序创建者", "管理员删除");
        }
        
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
     * 图片文件
     * 创建时间：2019-01-01 15:24
     * 说明：图片文件管理
     * 检测：逻辑
     * 检测时间：2019-01-01 15:24
     * */
    public function ImageAdmin(){
         
        //--- 参数获取区 ---
        //参数:分页
        $page = GetParameter('page', "");
        if(IsNull($page)){return JsonModelParameterNull("page");}
        if(intval($page)<0){$page=1;}
        //参数:条数
        $limit = GetParameter('limit', "");
        if(IsNull($limit)){return JsonModelParameterNull("limit");}
         
        $hostdir = $_SERVER['DOCUMENT_ROOT'] . "/c/static/image";
        $filesnames = scandir($hostdir); //得到所有的文件
        $i = 0;
        $iLimit = 0;
        $fileArray = array();
        foreach($filesnames as $name) {
            $suffix = strtolower(strrchr($name,'.'));
            if($suffix==".jpg"||$suffix==".jpeg"||$suffix==".png"){
                $i++;
                if($i > ($page*$limit-$limit) && $iLimit < $limit){
                    $iLimit ++;
                    $fileArray[$i] = $name;
                }
            }
        }
        return JsonInforData("本地图片管理", "/c/static/image", "", $i, $fileArray);
    }
    
    
    /**
     * 数据静态化
     * 创建时间：2019-03-24 11:49:00
     * 说明：对数据进行静态化处理
     * 检测：逻辑
     * 检测时间：2019-03-24 11:49:00
     * */
    public function DataStatic(){
         
        //--- 参数获取区 ---
        //参数:数据地址
        $dataPath = GetParameter('datapath', "");
        if(IsNull($dataPath)){return JsonModelParameterNull("datapath");}
        //参数:文件路径
        $filePath = GetParameter("filepath");
        if(IsNull($filePath)){return JsonModelParameterNull("filepath");}
    
        //--- 逻辑区 ---
        //获取Json数据
        $data = GetHttp($dataPath);
        if(preg_match("/.[a-zA-Z]+$/",$filePath)) {
            //组合文件生成路径
            $filePath = $_SERVER['DOCUMENT_ROOT'] . $filePath;
        }else{
            $version = GetJsonValue($data, "version");
            if(!IsNull($version)){
                $filePath = $_SERVER['DOCUMENT_ROOT'] . $filePath ."/" . $version . ".html";
            }else{
                $filePath = $_SERVER['DOCUMENT_ROOT'] . $filePath ."/" . GetId("R") . ".html";
            }
        }
    
        //生成静态记录
        HandleDataStatic($filePath,$data);
    
        //返回结果
        return JsonInforTrue("静态数据已生成", "数据静态化");
    }
    
    /**
     * 添加或移除管理员角色
     * 创建时间：2020-10-09 09:58:52
     * */
    public function AdminRoleSet($fpAdminId){
        
        //判断管理员是否是创建者
        $vAdmin = ObjRoleObjectAdmin() -> JudgeAdminIsCreater("",true);
        if(JudgeJsonFalse($vAdmin)){return WriteEcho($vAdmin);}
        
        
        //参数:管理员ID:admin_id
        $pAdminId = GetParameterNoCode("admin_id","");
        if(!JudgeRegularInt($pAdminId)){return JsonModelParameterException("admin_id", $pAdminId, 11, "值必须是整数", __LINE__);}
        
        //参数:角色设置:role_set
        $pRoleSet = GetParameterNoCode("role_set","");
        if(!JudgeRegularLetter($pRoleSet)){return JsonModelParameterException("role_set", $pAdminId, 11, "值必须是字母", __LINE__);}
        if(!($pRoleSet=="true"||$pRoleSet=="false")){return JsonInforFalse("值必须是true或false", "程序测试者");}
        
        //Json数组
        $jsonKeyValueArray = array(
            "table_name" => self::$tableName,
            "update_field" => "isRoleTester",
            "update_value" => $pRoleSet,
            "where_field" => "$pAdminId",
            "where_value" => "{$fpAdminId}",
        );
        //执行:添加
        return MIndexDataUpdate(JsonHandleArray($jsonKeyValueArray));
    }
    
    /**
     * 判断用户是否是开发程序员
     * $fpAdminId：管理员ID
     * $fpJsonBo：是否返回Json结果，$fpJsonBo为false时返回boolean结果，$fpJsonBo为true时返回Json结果
     * 创建时间：2020-01-07 10:38:34
     * */
    public function JudgeAdminIsDeveloper($fpAdminId,$fpJsonBo=false){
        if(IsNull($fpAdminId)){ $fpAdminId = self::SessionUserId(); }
        //获取:fly_user_admin:是否为财务管理员
        $vSql = "SELECT isRoleDeveloper FROM fly_user_admin WHERE id=? LIMIT 0,1;";
        $vIsRoleDeveloper = DBHelper::DataString($vSql, [$fpAdminId]);
        if(IsNull($vIsRoleDeveloper)){
            if(!$fpJsonBo){return false;}
            return JsonInforFalse("管理员不存在", "fly_user_admin");
        }
        if(!$fpJsonBo){return $vIsRoleDeveloper == "true";}
        if($vIsRoleDeveloper == "true"){
            return JsonInforTrue("您是程序开发员", "fly_user_admin");
        }
        return JsonInforFalse("您不是程序开发员", "fly_user_admin");
    }
    
    /**
     * 判断用户是否是程序创建者
     * $fpAdminId：管理员ID
     * $fpJsonBo：是否返回Json结果，$fpJsonBo为false时返回boolean结果，$fpJsonBo为true时返回Json结果
     * 创建时间：2020-01-07 15:27:56
     * */
    public function JudgeAdminIsCreater($fpAdminId,$fpJsonBo=false){
        if(IsNull($fpAdminId)){ $fpAdminId = self::SessionUserId(); }
        //获取:fly_user_admin:是否为财务管理员
        $vSql = "SELECT isRoleCreater FROM fly_user_admin WHERE id=? LIMIT 0,1;";
        $vIsRoleCreater = DBHelper::DataString($vSql, [$fpAdminId]);
        if(IsNull($vIsRoleCreater)){
            if(!$fpJsonBo){return false;}
            return JsonInforFalse("管理员不存在", "fly_user_admin", __LINE__); 
        }
        if(!$fpJsonBo){return $vIsRoleCreater == "true";}
        if($vIsRoleCreater == "true"){
            return JsonInforTrue("您是程序创建者", "fly_user_admin");
        }
        return JsonInforFalse("您不是程序创建者", "fly_user_admin");
    }
    
    /**
     * 判断是否有程序创建者
     * 2020-03-23 16:15:46
     * */
    public function JudgeAdminCreater(){
        //获取:fly_user_admin:表ID
        $vSql = "SELECT id FROM fly_user_admin WHERE isRoleCreater=? LIMIT 0,1;";
        $vId = DBHelper::DataString($vSql, ["true"]);
        if(IsNull($vId)){ return JsonInforFalse("程序创建者未定义", "fly_user_admin", __LINE__); }    
        return JsonInforTrue("存在程序创建者", "fly_user_admin");
    }


    /**
     * 获取程序员列表
     * 创建时间：2020-01-20 10:22:04
     * */
    public function AdminDeveloperList(){
        //获取:fly_user_admin:表ID、管理员姓名
        $vSql = "SELECT id,adminName,department FROM fly_user_admin WHERE isRoleDeveloper=?;";
        $vFlyUserAdminList = DBHelper::DataList($vSql, ["true"], ["id","adminName","department"]);
        if(IsNull($vFlyUserAdminList)){ return JsonInforFalse("记录不存在", "fly_user_admin", __LINE__); }
        return JsonModelDataString($vFlyUserAdminList, sizeof(GetJsonObject($vFlyUserAdminList)), "开发程序员名单");
    }                        
    
    
}