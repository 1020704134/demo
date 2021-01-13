<?php

/**------------------------------------*
 * 名称：超级管理员:模板新闻类
 * 创建时间：2019-03-19 12:30
 * ------------------------------------*/

//--- 引用区 ---

class FlyClassRoleLoginLog{
    
    //---------- 类成员 ----------
    
    //类描述
    public static $classDescript = "角色登陆日志";
    
    //表名称
    public static $tableName = "fly_role_login_log";
    
    
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
 
    /**
     * 登陆日志
     * 创建时间：December 1,2018 10:34
     * 说明：为角色登陆添加登陆日志，并判断用户是否在半小时内错误次数超过5次，超过5次则不可以进行登陆校验
     * 注释：可通过分组来对登陆日志记录进行分组控制、用户ID必须是通过用户名查到的ID
     * 检测：逻辑
     * 检测时间：
     * 参数说明：
	 *     $tips                                  :   登陆角色提示       
	 *     $loginResult                           :   传入登陆校验之后获取到的登陆结果 true/false
	 *     $typeName                              :   日志类型
	 *     $groupName                             :   分组类型
	 *     $userId                                :   用户ID
     * */
    public function Login($tips,$loginResult,$typeName,$groupName,$userId,$fpLoginUserName,$fpUserTable,$fpLoginType="用户登陆",$fpAdminId="0"){
        
        $thisTableName = self::$tableName;
        $jsonKeyValueArray = array(
            "table_name" => $thisTableName,
            "data_field" => "id,loginState,addTime",
            "page" => "1",
            "limit" => "10",
            "where_field" => "typeName,groupName,loginUser",
            "where_value" => "{$typeName},{$groupName},{$userId}",
            "orderby" => "id:desc",
        );
        $json = MIndexDataPaging(JsonHandleArray($jsonKeyValueArray));
        if(JudgeJsonFalse($json)){ return $json; }
        //判断是否是首次登陆
        
        if(JudgeJsonCountZero($json)){
            if($loginResult){
                $this -> LogAdd($typeName, $groupName, $userId, $fpLoginUserName, "LOGIN_SUCCESS", $fpUserTable, $fpLoginType, $fpAdminId);
                return JsonInforTrue("登陆成功", $tips);
            }else{
                $this -> LogAdd($typeName, $groupName, $userId, $fpLoginUserName, "LOGIN_FAIL", $fpUserTable, $fpLoginType, $fpAdminId);
                return JsonInforFalse("登陆失败,还有5次登陆机会", $tips);
            }
        }
        
        //当有登陆记录时
        $loginJudgeNumber = 0;  //声明登陆次数
        $jsonArray = GetJsonValue($json, "data");
        
        if(!IsNull($jsonArray)){
            foreach($jsonArray as $jsonData){
                $loginState = $jsonData -> loginState;
                $addTime = $jsonData -> addTime;
                //判断是否有一次成功，如果5次内有一次成功，则只计算前几次登陆失败次数，跳出循环
                if($loginState=="LOGIN_SUCCESS"){break;}
                //判断登陆失败
                if($loginState=="LOGIN_FAIL"){
                    $minute = GetTimeDifference(GetTimeNow(),$addTime, "minute");
                    if($minute<=30){
                        $loginJudgeNumber+=1;
                    }
                }
            }
        }
        

        //失败登陆次数超过5次
        if($loginJudgeNumber>=5){
            return JsonInforFalse("30分钟内无法登陆",$tips);
        }
        
        //判断用户登陆结果为true，即登陆成功
        if($loginResult){
            $this -> LogAdd($typeName, $groupName, $userId, $fpLoginUserName, "LOGIN_SUCCESS", $fpUserTable, $fpLoginType, $fpAdminId);
            return JsonInforTrue("登陆成功", $tips);
        }else{
            $this -> LogAdd($typeName, $groupName, $userId, $fpLoginUserName, "LOGIN_FAIL", $fpUserTable, $fpLoginType, $fpAdminId);
            $loginJudgeNumber = 5-$loginJudgeNumber;
            return JsonInforFalse("登陆失败,还有".$loginJudgeNumber."次登陆机会",$tips);
        }
        
    }
    
    
    
    /**
     * 登陆日志添加
     * 创建时间：December 1,2018 12:01
     * 说明：为角色登陆日志进行数据添加
     * 备注：可通过分组来对登陆日志记录进行分组控制
     * 检测：逻辑
     * 检测时间：
     * 参数说明：
     *     $tips                                  :   登陆角色提示
     *     $loginUserId                           :   登陆返回的Json结果中的用户ID
     *     $typeName                              :   日志类型
     *     $groupName                             :   分组类型
     *     $userId                                :   用户ID
     * */
    private function LogAdd($typeName,$groupName,$userId,$fpLoginUserName,$loginState,$fpUserTable,$fpLoginType="用户登陆",$fpAdminId="0"){
        $thisTableName = self::$tableName;
        $jsonKeyValueArray = array(
            "table_name" => $thisTableName,
            "insert_field" => "userTable,typeName,groupName,loginType,adminId,loginUser,loginUserName,loginState,loginIp",
            "usertable" => $fpUserTable,
            "typename" => $typeName,
            "groupname" => $groupName,
            "logintype" => $fpLoginType,
            "adminid" => $fpAdminId,
            "loginuser" => $userId,
            "loginUserName" => $fpLoginUserName,
            "loginstate" => $loginState,
            "loginIp" => GetIp(),
        );
        return MIndexDataInsert(JsonHandleArray($jsonKeyValueArray));
    }
    
    /**
     * 数据分页查询
     * 创建时间：2019-03-17 20:34:43
     * */
    public function FlyLoginLogPaging(){
        return ServiceTableDataPaging(self::$tableName,"id,onlyKey,userTable,typeName,groupName,loginType,adminId,loginUser,loginUserName,loginState,loginIp,indexNumber,updateTime,addTime,state");
    }
    
    
    /**
     * 函数名称：获取登陆日志表字段
     * 函数调用：ObjFlyRoleLoginLog() -> GetTableField()
     * 创建时间：2020-04-01 15:55:39
     * */
    public function GetTableField(){
        return MIndexDBTableFields(JsonObj(JsonKeyValue("table_name", self::$tableName)));
    }
    
}

